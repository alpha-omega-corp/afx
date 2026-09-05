<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The gallery lays photographs out by their own shape, so it has to know
     * that shape before the file loads. Nullable: rows added before this
     * migration keep working and fall back to a default ratio until measured.
     */
    public function up(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->unsignedInteger('width')->nullable()->after('image');
            $table->unsignedInteger('height')->nullable()->after('width');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn(['width', 'height']);
        });
    }
};
