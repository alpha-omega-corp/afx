<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::factory()->home()->create();
        Page::factory()->about()->create();
        Page::factory()->menu()->create();
        Page::factory()->restaurant()->create();
        Page::factory()->hotel()->create();
        Page::factory()->contact()->create();
    }
}
