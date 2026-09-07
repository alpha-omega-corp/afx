<?php

namespace Database\Seeders;

use App\Enums\Page as PageEnum;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Every page header the site draws, seeded once.
     *
     * Idempotent on purpose. This is not only the seeder that fills a new
     * database — it is also the repair for one whose pages were never
     * written, which is a thing that happens after a `migrate:fresh` without
     * `--seed`. Running it twice must leave the site with one home page, not
     * two, so a page that is already there is stepped over rather than
     * created again.
     */
    public function run(): void
    {
        foreach (PageEnum::cases() as $page) {
            if (Page::where('name', $page->value)->exists()) {
                continue;
            }

            // One factory state per page, named after the case it fills.
            Page::factory()->{$page->value}()->create();
        }
    }
}
