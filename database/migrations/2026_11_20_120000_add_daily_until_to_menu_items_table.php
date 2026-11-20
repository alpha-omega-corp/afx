<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The last day the card of the day is on show.
 *
 * `daily_on` comes back into use as the first day. Together they are the
 * stretch of days staff choose in the editor; outside it the card is off the
 * home page by itself, so a dish written for Friday is not still announced a
 * month later (client review 4, ticket #3). Both are optional: a card with
 * neither stays up until the "show on the home page" box is cleared.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->date('daily_until')->nullable()->after('daily_on');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('daily_until');
        });
    }
};
