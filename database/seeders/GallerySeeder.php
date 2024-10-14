<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        Gallery::factory()->delicacies()->create();
        Gallery::factory()->restaurant()->create();
        Gallery::factory()->hotel()->create();
    }
}
