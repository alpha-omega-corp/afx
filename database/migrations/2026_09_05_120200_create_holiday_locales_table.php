<?php

use App\Enums\Locale;
use App\Models\Holiday;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holiday_locales', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Holiday::class)->constrained()->cascadeOnDelete();
            $table->enum('lang', Locale::values());
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['holiday_id', 'lang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holiday_locales');
    }
};
