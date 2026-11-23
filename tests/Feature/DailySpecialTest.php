<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\User;
use App\Support\Opening;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** The plats du jour on the home page, and the days they are on show. */
class DailySpecialTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Opening::flush();

        parent::tearDown();
    }

    private function special(string $title, bool $shown = true, ?string $from = null, ?string $until = null): MenuItem
    {
        $item = MenuItem::factory()->create([
            'menu_section_id' => MenuSection::factory()->create()->id,
            'daily' => $shown,
            'daily_on' => $from,
            'daily_until' => $until,
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

    public function test_the_plats_du_jour_are_not_on_the_carte(): void
    {
        $special = $this->special('Civet de chevreuil');

        // A dish of the carte, in the same section as the special.
        $dish = MenuItem::factory()->create(['menu_section_id' => $special->menu_section_id, 'daily' => false]);
        $dish->locales()->updateOrCreate(['lang' => 'fr'], ['title' => 'Salade verte']);

        // A section that holds nothing but a plat du jour.
        // (The section factory files five dishes of its own; these go.)
        $alone = $this->special('Tarte aux pruneaux');
        MenuItem::where('menu_section_id', $alone->menu_section_id)->whereKeyNot($alone->id)->delete();
        $alone->section->locales()->updateOrCreate(['lang' => 'fr'], ['title' => 'Section du jour seulement']);

        $this->get(route('guest.fr.menu'))
            ->assertOk()
            ->assertSee('Salade verte')
            ->assertDontSee('Civet de chevreuil')
            ->assertDontSee('Tarte aux pruneaux')
            ->assertDontSee('Section du jour seulement');
    }

    public function test_staff_edit_the_card_from_a_button_under_it(): void
    {
        $this->special('Civet de chevreuil');

        $this->actingAs(User::factory()->create())
            ->get(route('guest.fr.home'))
            ->assertOk()
            ->assertSee('class="daily__foot"', false)
            ->assertSee(__('admin.action.edit_daily'));
    }

    public function test_an_unticked_card_is_not_on_the_home_page(): void
    {
        $this->special('Civet de chevreuil', shown: false);

        $this->get(route('guest.fr.home'))
            ->assertOk()
            ->assertDontSee('id="plat-du-jour"', false);
    }

    public function test_the_editor_offers_the_next_open_day_for_a_new_card(): void
    {
        Carbon::setTestNow('2026-11-22 12:00'); // a Sunday: closed, as is Monday
        MenuSection::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('guest.fr.home'))
            ->assertOk()
            ->assertSee('name="daily_from"', false)
            ->assertSee('value="2026-11-24"', false); // Tuesday
    }

    public function test_the_editor_offers_the_next_open_days_to_pick_from(): void
    {
        Carbon::setTestNow('2026-11-23 12:00'); // a Monday: closed
        $this->special('Civet de chevreuil', from: '2026-11-26', until: '2026-11-26');

        $html = $this->actingAs(User::factory()->create())
            ->get(route('admin.menu'))
            ->assertOk()
            ->assertSeeInOrder(['Demain', '24 novembre', 'Mercredi', '25 novembre', 'Jeudi', '26 novembre', 'Vendredi', 'Samedi', '28 novembre'])
            ->getContent();

        // Thursday, the card's own day, is the one shown as chosen.
        $this->assertMatchesRegularExpression("/aria-pressed=\"true\"\\s*>\\s*<span class=\"daily-fields__day-name\">Jeudi/", $html);
        $this->assertSame(1, substr_count($html, 'aria-pressed="true"'));
    }

    public function test_the_card_is_shown_only_within_its_days(): void
    {
        $this->special('Civet de chevreuil', from: '2026-11-24', until: '2026-11-27');

        foreach ([
            '2026-11-23 12:00' => false,
            '2026-11-24 08:00' => true,
            '2026-11-27 22:00' => true,
            '2026-11-28 09:00' => false,
        ] as $now => $shown) {
            Carbon::setTestNow($now);

            $response = $this->get(route('guest.fr.home'))->assertOk();
            $shown
                ? $response->assertSee('Civet de chevreuil')
                : $response->assertDontSee('Civet de chevreuil');
        }
    }

    public function test_the_last_day_ends_at_midnight_in_founex_not_utc(): void
    {
        $this->special('Civet de chevreuil', until: '2026-11-27');

        // 23:30 UTC on Friday is already Saturday 00:30 in Founex.
        Carbon::setTestNow(Carbon::parse('2026-11-27 23:30', 'UTC'));

        $this->get(route('guest.fr.home'))->assertDontSee('Civet de chevreuil');
    }

    public function test_staff_see_an_ended_card_only_in_the_editor(): void
    {
        $this->special('Civet de chevreuil', from: '2026-10-16', until: '2026-10-16');
        Carbon::setTestNow('2026-11-18 12:00'); // a Wednesday

        $this->actingAs(User::factory()->create())
            ->get(route('guest.fr.home'))
            ->assertOk()
            ->assertDontSee('class="daily__title"', false)
            ->assertSee('Terminé')
            // In the editor, ready to re-date: on the next open day, not on
            // the old one.
            ->assertSee('Civet de chevreuil')
            ->assertSee('value="2026-11-18"', false)
            ->assertDontSee('value="2026-10-16"', false);
    }

    public function test_the_admin_carte_says_when_the_card_is_on_show(): void
    {
        $this->special('Civet de chevreuil', from: '2026-11-24', until: '2026-11-27');
        Carbon::setTestNow('2026-11-20 12:00');

        $this->actingAs(User::factory()->create())
            ->get(route('admin.menu'))
            ->assertOk()
            ->assertSee('Programmé')
            ->assertSee('du mardi 24 novembre au vendredi 27 novembre');
    }

    public function test_the_last_day_cannot_come_before_the_first(): void
    {
        $section = MenuSection::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [
                'daily' => '1',
                'daily_from' => '2026-11-27',
                'daily_until' => '2026-11-24',
                'daily_ids' => [null],
                'daily_sections' => [$section->id],
                'daily_titles' => ['Civet de chevreuil'],
                'daily_prices' => ['32.50'],
            ])
            ->assertSessionHasErrors('daily_until');
    }

    public function test_saving_the_card_stores_its_days(): void
    {
        $section = MenuSection::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [
                'daily' => '1',
                'daily_from' => '2026-11-24',
                'daily_until' => '2026-11-27',
                'daily_ids' => [null, null],
                'daily_sections' => [$section->id, $section->id],
                'daily_titles' => ['Civet de chevreuil', 'Tarte aux pruneaux'],
                'daily_prices' => ['32.50', '9.50'],
            ])
            ->assertRedirect();

        foreach (MenuItem::specials() as $dish) {
            $this->assertSame('2026-11-24', $dish->daily_on->toDateString());
            $this->assertSame('2026-11-27', $dish->daily_until->toDateString());
        }
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
        // No dates given: on show until the box is cleared.
        $this->assertNull(MenuItem::specials()->first()->daily_on);
        $this->assertNull(MenuItem::specials()->first()->daily_until);
    }

    public function test_the_editor_asks_only_name_description_and_price(): void
    {
        $this->special('Civet de chevreuil');

        $this->actingAs(User::factory()->create())
            ->get(route('admin.menu'))
            ->assertOk()
            ->assertSee('name="daily_titles[]"', false)
            ->assertSee('name="daily_descriptions[]"', false)
            ->assertSee('name="daily_prices[]"', false)
            ->assertDontSee('name="daily_titles_en[]"', false)
            ->assertDontSee('name="daily_descriptions_en[]"', false)
            ->assertDontSee('daily-section-', false);
    }

    public function test_saving_without_english_keeps_a_corrected_english_name(): void
    {
        $dish = $this->special('Civet de chevreuil');
        $dish->locales()->updateOrCreate(['lang' => 'en'], ['title' => 'Venison stew', 'auto' => []]);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [
                'daily' => '1',
                'daily_ids' => [$dish->id],
                'daily_sections' => [$dish->menu_section_id],
                'daily_titles' => ['Civet de chevreuil'],
                'daily_descriptions' => ['Spätzli maison'],
                'daily_prices' => ['34.00'],
            ])
            ->assertRedirect();

        $this->assertSame('Venison stew', $dish->fresh()->localeIn(\App\Enums\Language::EN)->title);
        $this->assertSame('Spätzli maison', $dish->fresh()->localeIn(\App\Enums\Language::FR)->description);
    }
}
