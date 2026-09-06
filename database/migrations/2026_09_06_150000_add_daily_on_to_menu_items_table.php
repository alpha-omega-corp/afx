<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The day the special is for, written by whoever wrote the dish.
 *
 * Deliberately stored rather than taken from the clock: a card that prints
 * today's date announces that today's dish is today's, which is a claim only
 * the person who wrote it can make. A date left behind is visibly stale; a
 * date generated on every request never is, and is wrong instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->date('daily_on')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('daily_on');
        });
    }
};
