<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The week the auberge serves, one row per day.
 *
 * Until now this lived in two places that did not agree: a PHP constant said
 * which days the kitchen was shut, and the footer printed a sentence written
 * by hand. This table is the one place both read from.
 *
 * `day` is Carbon's own numbering — Sunday 0 through Saturday 6 — so a date
 * can be checked against it without a lookup table. Times are stored as the
 * "HH:MM" an <input type="time"> sends and prints, because nothing here ever
 * does arithmetic on them; they are read, grouped and formatted.
 */
return new class extends Migration
{
    /** The auberge's published week, as it read on aubergedefounex.ch. */
    private const PUBLISHED = [
        0 => null,                                 // dimanche — fermé
        1 => null,                                 // lundi — fermé
        2 => ['11:30', '14:00', '18:00', '22:00'], // mardi
        3 => ['11:30', '14:00', '18:00', '22:00'],
        4 => ['11:30', '14:00', '18:00', '22:00'],
        5 => ['11:30', '14:00', '18:00', '22:00'],
        6 => ['11:30', '14:00', '18:00', '22:00'], // samedi
    ];

    public function up(): void
    {
        Schema::create('opening_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('day')->unique();
            $table->boolean('closed')->default(false);
            $table->string('lunch_from', 5)->nullable();
            $table->string('lunch_to', 5)->nullable();
            $table->string('dinner_from', 5)->nullable();
            $table->string('dinner_to', 5)->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('opening_hours')->insert(
            collect(self::PUBLISHED)
                ->map(fn (?array $hours, int $day): array => [
                    'day' => $day,
                    'closed' => $hours === null,
                    'lunch_from' => $hours[0] ?? null,
                    'lunch_to' => $hours[1] ?? null,
                    'dinner_from' => $hours[2] ?? null,
                    'dinner_to' => $hours[3] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->values()
                ->all()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('opening_hours');
    }
};
