<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** The plats du jour on the home page, without a date. */
class DailySpecialTest extends TestCase
{
    use RefreshDatabase;

    private function special(string $title, bool $shown = true): MenuItem
    {
        $item = MenuItem::factory()->create([
            'menu_section_id' => MenuSection::factory()->create()->id,
            'daily' => $shown,
        ]);
        $item->locales()->updateOrCreate(['lang' => 'fr'], ['title' => $title]);

        return $item;
    }

    public function test_the_card_is_on_the_home_page_with_no_date(): void
    {
        Carbon::setTestNow('2026-11-18 12:00'); // a Wednesday

        $this->special('Civet de chevreuil');
        $this->special('Tarte aux pruneaux');

        $this->get(route('guest.fr.home'))
            ->assertOk()
            ->assertSee('id="plat-du-jour"', false)
            ->assertSee('Civet de chevreuil')
            ->assertSee('Tarte aux pruneaux')
            ->assertDontSee('mercredi 18 novembre')
            ->assertDontSee('daily__when', false)
            ->assertDontSee('daily__date', false);
    }

    public function test_an_unticked_card_is_not_on_the_home_page(): void
    {
        $this->special('Civet de chevreuil', shown: false);

        $this->get(route('guest.fr.home'))
            ->assertOk()
            ->assertDontSee('id="plat-du-jour"', false);
    }

    public function test_the_editor_asks_for_no_date(): void
    {
        $this->special('Civet de chevreuil');

        $this->actingAs(User::factory()->create())
            ->get(route('guest.fr.home'))
            ->assertOk()
            ->assertSee('Civet de chevreuil')
            ->assertDontSee('name="daily_on"', false);
    }

    public function test_saving_the_card_keeps_it_on_the_home_page(): void
    {
        $section = MenuSection::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [
                'daily' => '1',
                'daily_ids' => [null],
                'daily_sections' => [$section->id],
                'daily_titles' => ['Civet de chevreuil'],
                'daily_prices' => ['32.50'],
            ])
            ->assertRedirect();

        $this->assertSame(1, MenuItem::specials()->count());
        $this->assertNull(MenuItem::specials()->first()->daily_on);
    }
}
