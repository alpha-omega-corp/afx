<?php

use App\Enums\Locale;
use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Services\Translator;
use App\Enums\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The carte, in both languages.
 *
 * Section headings and dish names lived in one column each and were therefore
 * French on the English site too: a visitor switching to EN got an English
 * shell around a French menu. They move here, one row per language, the same
 * shape `page_locales` and `holiday_locales` already use.
 *
 * The existing text becomes the French row verbatim — nothing is lost and
 * nothing is guessed at. The English row is filled from the shipped glossary
 * where it knows the phrase, and otherwise carries the French text marked
 * `auto`, which is the flag that says "a machine put this here, and it may
 * still be improved". `php artisan carte:translate` completes those over the
 * network afterwards; staff can overwrite any of it by hand at any time.
 *
 * Deliberately no network call in here. A migration that reaches out to a
 * third party is a migration that fails on a bad day.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_section_locales', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MenuSection::class)->constrained()->cascadeOnDelete();
            $table->enum('lang', Locale::values());
            $table->string('title');
            // Which of the fields above a machine wrote. Per field rather
            // than per row: correcting a heading must not freeze anything
            // else on the row against its French source.
            $table->json('auto')->nullable();
            $table->timestamps();

            $table->unique(['menu_section_id', 'lang']);
        });

        Schema::create('menu_item_locales', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MenuItem::class)->constrained()->cascadeOnDelete();
            $table->enum('lang', Locale::values());
            $table->string('title');
            $table->string('description')->nullable();
            // See the note on menu_section_locales.auto.
            $table->json('auto')->nullable();
            $table->timestamps();

            $table->unique(['menu_item_id', 'lang']);
        });

        $this->backfill();

        // The old columns go only once their contents are safely in the new
        // tables: up to this line the migration is still reversible by hand.
        Schema::table('menu_sections', fn (Blueprint $table) => $table->dropColumn('title'));
        Schema::table('menu_items', fn (Blueprint $table) => $table->dropColumn(['title', 'description']));
    }

    public function down(): void
    {
        Schema::table('menu_sections', fn (Blueprint $table) => $table->string('title')->nullable());
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('description')->nullable();
        });

        // The French text goes back where it came from, so a rollback leaves
        // a working carte rather than a table of empty names.
        foreach (['menu_section' => 'title', 'menu_item' => 'title'] as $entity => $_) {
            DB::table("{$entity}s")->orderBy('id')->chunkById(200, function ($rows) use ($entity) {
                foreach ($rows as $row) {
                    $locale = DB::table("{$entity}_locales")
                        ->where("{$entity}_id", $row->id)
                        ->where('lang', Language::FR->value)
                        ->first();

                    if (! $locale) {
                        continue;
                    }

                    $values = ['title' => $locale->title];

                    if ($entity === 'menu_item') {
                        $values['description'] = $locale->description;
                    }

                    DB::table("{$entity}s")->where('id', $row->id)->update($values);
                }
            });
        }

        Schema::dropIfExists('menu_item_locales');
        Schema::dropIfExists('menu_section_locales');
    }

    private function backfill(): void
    {
        $now = now();
        $fr = Language::FR;
        $en = Language::EN;

        foreach (DB::table('menu_sections')->orderBy('id')->get() as $section) {
            $title = (string) $section->title;

            DB::table('menu_section_locales')->insert([
                [
                    'menu_section_id' => $section->id,
                    'lang' => $fr->value,
                    'title' => $title,
                    'auto' => json_encode([]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'menu_section_id' => $section->id,
                    'lang' => $en->value,
                    'title' => Translator::phrase($title, $fr, $en) ?? $title,
                    'auto' => json_encode(['title']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        foreach (DB::table('menu_items')->orderBy('id')->get() as $item) {
            $title = (string) $item->title;
            $description = $item->description;

            DB::table('menu_item_locales')->insert([
                [
                    'menu_item_id' => $item->id,
                    'lang' => $fr->value,
                    'title' => $title,
                    'description' => $description,
                    'auto' => json_encode([]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'menu_item_id' => $item->id,
                    'lang' => $en->value,
                    'title' => Translator::phrase($title, $fr, $en) ?? $title,
                    'description' => filled($description)
                        ? (Translator::phrase($description, $fr, $en) ?? $description)
                        : null,
                    'auto' => json_encode(['title', 'description']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
};
