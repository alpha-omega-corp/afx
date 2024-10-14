<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_locales', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Page::class);
            $table->enum('lang', \App\Enums\Locale::values());
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_locales');
    }
};
