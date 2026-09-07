<?php

namespace App\Console\Commands;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Support\Translations;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

/**
 * Fills in every carte translation the glossary could not answer.
 *
 * The migration that split the carte into languages used the glossary only —
 * a migration must not depend on a third party being up — so anything it did
 * not know is still sitting there in French, marked as the machine's. This is
 * the command that finishes the job, and it is safe to run as often as you
 * like: a field is only touched while it still carries its French source, so
 * anything already translated, and anything a person has since corrected, is
 * passed over.
 */
class TranslateCarte extends Command
{
    protected $signature = 'carte:translate
                            {--pause=1 : Seconds between calls, to stay inside the free rate limit}';

    protected $description = 'Translate the parts of the carte the shipped glossary could not';

    public function handle(): int
    {
        $pause = max(0, (int) $this->option('pause'));
        $filled = 0;

        foreach ([MenuSection::class, MenuItem::class] as $model) {
            $records = $model::with('locales')->orderBy('id')->get();

            if ($records->isEmpty()) {
                continue;
            }

            $this->components->task(class_basename($model), function () use ($records, $pause, &$filled): bool {
                foreach ($records as $record) {
                    $wrote = Translations::machinePass($record);
                    $filled += $wrote;

                    // Only sleep when something actually went over the wire.
                    if ($wrote > 0 && $pause > 0) {
                        sleep($pause);
                    }
                }

                return true;
            });
        }

        $filled === 0
            ? $this->components->info('Nothing left to translate.')
            : $this->components->info("Translated {$filled} field(s).");

        return self::SUCCESS;
    }
}
