<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The card of the day holds several dishes, and their order is the order they
 * are eaten in: an entrée cannot print under the dessert because it happened
 * to be typed second. Null for every dish that is not on the card.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedSmallInteger('daily_position')->nullable()->after('daily_on');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('daily_position');
        });
    }
};
