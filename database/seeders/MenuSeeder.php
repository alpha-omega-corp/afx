<?php

namespace Database\Seeders;

use App\Models\MenuSection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        MenuSection::factory()
        ->count(5)
        ->state(new Sequence(
            fn (Sequence $sequence) => ['position' => $sequence->index],
        ))
        ->create();
    }
}
