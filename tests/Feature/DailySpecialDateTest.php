<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** A plat du jour whose day has gone by was still shown to guests, weeks later. */
class DailySpecialDateTest extends TestCase
{
    use RefreshDatabase;

    private function special(?string $on): MenuItem
    {
        return MenuItem::factory()->create([
            'menu_section_id' => MenuSection::factory()->create()->id,
            'daily' => true,
            'daily_on' => $on,
        ]);
    }

    public function test_nobody_gets_a_past_card_not_even_staff(): void
    {
        Carbon::setTestNow('2026-10-16 12:00'); // a Friday, open

        $this->special('2026-09-17');
        $today = $this->special('2026-10-16');
        $undated = $this->special(null);

        $this->assertEqualsCanonicalizing([$today->id, $undated->id], MenuItem::currentSpecials()->pluck('id')->all());

        $this->actingAs(User::factory()->create());
        $this->assertEqualsCanonicalizing([$today->id, $undated->id], MenuItem::currentSpecials()->pluck('id')->all());
    }

    /** Client review 4, ticket #3: "c'est toujours marqué plat du jour vendredi 16 octobre". */
    public function test_a_stale_card_is_off_the_home_page_but_still_in_the_editor(): void
    {
        $stale = $this->special('2026-10-16');
        Carbon::setTestNow('2026-11-03 12:00'); // a Tuesday, open

        $this->get(route('guest.fr.home'))
            ->assertOk()
            ->assertDontSee('vendredi 16 octobre')
            ->assertDontSee('id="plat-du-jour"', false);

        $this->actingAs(User::factory()->create())
            ->get(route('guest.fr.home'))
            ->assertOk()
            ->assertDontSee('vendredi 16 octobre')
            ->assertDontSee('daily__dish', false)
            ->assertViewHas('editing', fn ($editing) => $editing->pluck('id')->all() === [$stale->id]);
    }

    public function test_the_editor_does_not_offer_a_past_date_back(): void
    {
        Carbon::setTestNow('2026-10-16 12:00');
        $this->special('2026-09-17');

        $this->actingAs(User::factory()->create())
            ->get(route('guest.fr.home'))
            ->assertOk()
            ->assertSee('value="2026-10-16"', false)
            ->assertDontSee('value="2026-09-17"', false);
    }

    public function test_a_card_written_for_a_closed_day_is_not_past_until_it_is_served(): void
    {
        // Written for Sunday, served Tuesday (Sunday and Monday are closed).
        Carbon::setTestNow('2026-10-19 12:00');

        $this->assertFalse($this->special('2026-10-18')->isPast());
    }
}
