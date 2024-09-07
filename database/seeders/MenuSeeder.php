<?php

namespace Database\Seeders;

use App\Models\MenuSection;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        MenuSection::factory()
        ->count(5)
        ->has(MenuItem::factory()->count(5), 'items')
        ->create();
    }
}
