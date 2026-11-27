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

    public function test_the_editor_offers_the_next_open_days(): void
    {
        Carbon::setTestNow('2026-11-22 12:00'); // a Sunday: closed, as is Monday
        MenuSection::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('guest.fr.home'))
            ->assertOk()
            ->assertSee('dailyPlan(', false)
            // Tuesday first: the two closed days are not offered.
            ->assertDontSee('2026-11-22')
            ->assertDontSee('2026-11-23')
            ->assertSeeInOrder(['2026-11-24', '2026-11-25', '2026-11-26']);
    }

    public function test_the_editor_opens_on_the_plan_grouped_by_day(): void
    {
        Carbon::setTestNow('2026-11-23 12:00'); // a Monday: closed
        $this->special('Tarte aux pruneaux', from: '2026-11-27', until: '2026-11-27');
        $this->special('Civet de chevreuil', from: '2026-11-25', until: '2026-11-25');
        $this->special('Salade de saison');

        $this->actingAs(User::factory()->create())
            ->get(route('admin.menu'))
            ->assertOk()
            // Every day first, then by day.
            ->assertSeeInOrder(['Salade de saison', 'Civet de chevreuil', 'Tarte aux pruneaux'])
            ->assertSeeInOrder(['Demain', '24 novembre', 'Mercredi', '25 novembre', 'Jeudi', '26 novembre']);
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

    public function test_staff_see_an_ended_dish_only_in_the_editor_to_plan_again(): void
    {
        $this->special('Civet de chevreuil', from: '2026-10-16', until: '2026-10-16');
        Carbon::setTestNow('2026-11-18 12:00'); // a Wednesday

        $html = $this->actingAs(User::factory()->create())
            ->get(route('guest.fr.home'))
            ->assertOk()
            ->assertDontSee('class="daily__title"', false)
            ->assertSee('Terminé')
            ->getContent();

        // Offered back under the plan, not among its rows, and without its
        // old day: planning it again puts it on a day still to come.
        preg_match('/dailyPlan\(JSON\.parse\(\'(.*?)\'\)\)/s', $html, $match);
        $config = json_decode(json_decode('"' . $match[1] . '"'), true);

        $this->assertSame([], $config['rows']);
        $this->assertSame('Civet de chevreuil', $config['served'][0]['title']);
        $this->assertSame('', $config['served'][0]['from']);
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
                'daily_ids' => [null, null],
                'daily_sections' => [$section->id, $section->id],
                'daily_titles' => ['Civet de chevreuil', 'Tarte aux pruneaux'],
                'daily_prices' => ['32.50', '9.50'],
                'daily_froms' => ['2026-11-24', '2026-11-27'],
                'daily_untils' => ['2026-11-24', '2026-11-24'],
            ])
            ->assertSessionHasErrors('daily_untils.1')
            ->assertSessionDoesntHaveErrors('daily_untils.0');
    }

    public function test_each_dish_keeps_days_of_its_own(): void
    {
        $section = MenuSection::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [
                'daily_ids' => [null, null, null],
                'daily_sections' => [$section->id, $section->id, $section->id],
                'daily_titles' => ['Salade de saison', 'Civet de chevreuil', 'Tarte aux pruneaux'],
                'daily_prices' => ['12.00', '32.50', '9.50'],
                'daily_froms' => ['', '2026-11-24', '2026-11-26'],
                'daily_untils' => ['', '2026-11-25', '2026-11-26'],
            ])
            ->assertRedirect();

        $days = MenuItem::specials()->mapWithKeys(fn (MenuItem $dish) => [
            $dish->localeIn(\App\Enums\Language::FR)->title => [$dish->daily_on?->toDateString(), $dish->daily_until?->toDateString()],
        ])->all();

        $this->assertSame([
            'Salade de saison' => [null, null],
            'Civet de chevreuil' => ['2026-11-24', '2026-11-25'],
            'Tarte aux pruneaux' => ['2026-11-26', '2026-11-26'],
        ], $days);

        // And each goes up on its own day.
        foreach ([
            '2026-11-24 12:00' => ['Salade de saison', 'Civet de chevreuil'],
            '2026-11-26 12:00' => ['Salade de saison', 'Tarte aux pruneaux'],
        ] as $now => $shown) {
            Carbon::setTestNow($now);

            $this->assertSame($shown, MenuItem::onShow()->map(fn ($dish) => $dish->localeIn(\App\Enums\Language::FR)->title)->all());
        }
    }

    public function test_a_dish_taken_off_the_plan_goes_back_to_the_carte_but_a_served_one_does_not(): void
    {
        Carbon::setTestNow('2026-11-18 12:00');
        $kept = $this->special('Civet de chevreuil', from: '2026-11-20', until: '2026-11-20');
        $dropped = $this->special('Tarte aux pruneaux', from: '2026-11-21', until: '2026-11-21');
        $served = $this->special('Soupe à la courge', from: '2026-11-10', until: '2026-11-10');

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [
                'daily_ids' => [$kept->id],
                'daily_sections' => [$kept->menu_section_id],
                'daily_titles' => ['Civet de chevreuil'],
                'daily_prices' => ['32.50'],
                'daily_froms' => ['2026-11-20'],
                'daily_untils' => ['2026-11-20'],
            ])
            ->assertRedirect();

        $this->assertTrue($kept->fresh()->daily);
        $this->assertFalse($dropped->fresh()->daily);
        $this->assertTrue($served->fresh()->daily);
        $this->assertSame('2026-11-10', $served->fresh()->daily_until->toDateString());
    }

    public function test_the_same_dish_on_two_days_is_two_dishes_of_the_plan(): void
    {
        $section = MenuSection::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [
                'daily_ids' => [null, null],
                'daily_sections' => [$section->id, $section->id],
                'daily_titles' => ['Civet de chevreuil', 'Civet de chevreuil'],
                'daily_prices' => ['32.50', '32.50'],
                'daily_froms' => ['2026-11-24', '2026-11-27'],
                'daily_untils' => ['2026-11-24', '2026-11-27'],
            ])
            ->assertRedirect();

        $this->assertSame(
            ['2026-11-24', '2026-11-27'],
            MenuItem::specials()->map(fn ($dish) => $dish->daily_on->toDateString())->sort()->values()->all(),
        );
    }

    public function test_an_empty_plan_takes_every_dish_off(): void
    {
        $dish = $this->special('Civet de chevreuil');

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertFalse($dish->fresh()->daily);
    }

    public function test_saving_the_card_keeps_it_on_the_home_page(): void
    {
        $section = MenuSection::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.menu.daily'), [
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
