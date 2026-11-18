<?php

namespace Tests\Feature;

use App\Support\Opening;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** "Ouvert jusqu'à 22h00" in the top bar over the navigation. */
class OpenNowPillTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_service_is_read_on_founex_time(): void
    {
        // Tuesday 12:00 in Founex is 11:00 UTC: lunch is being served.
        $this->assertTrue(Opening::isOpenAt(Carbon::parse('2026-11-17 11:00', 'UTC')));
        // Between services, and on a closed Monday.
        $this->assertFalse(Opening::isOpenAt(Carbon::parse('2026-11-17 16:00', 'Europe/Zurich')));
        $this->assertFalse(Opening::isOpenAt(Carbon::parse('2026-11-16 12:00', 'Europe/Zurich')));
    }

    public function test_the_pill_says_open_during_a_service(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-17 12:30', 'Europe/Zurich'));

        // On every page, not just the home page.
        $page = $this->get(route('guest.fr.contact'))->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/class="[^"]*\bopen-status\b[^"]*\bis-open\b[^"]*"/', $page);
        $this->assertStringContainsString('Ouvert', $page);
    }

    public function test_the_pill_says_closed_outside_the_services(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-16 12:30', 'Europe/Zurich'));

        $this->get(route('guest.fr.home'))
            ->assertOk()
            ->assertSee('open-status__dot', false)
            ->assertSee('Fermé');

        $this->assertDoesNotMatchRegularExpression(
            '/class="[^"]*\bopen-status\b[^"]*\bis-open\b[^"]*"/',
            $this->get(route('guest.fr.home'))->getContent(),
        );
    }

    public function test_the_bar_says_until_when_and_when_it_reopens(): void
    {
        app()->setLocale('fr');

        // Tuesday, during dinner.
        $this->assertSame(['open' => true, 'detail' => 'jusqu’à 22h00'],
            Opening::status(Carbon::parse('2026-11-17 19:00', 'Europe/Zurich')));
        // Tuesday, between lunch and dinner.
        $this->assertSame(['open' => false, 'detail' => 'ouvre à 18h00'],
            Opening::status(Carbon::parse('2026-11-17 15:00', 'Europe/Zurich')));
        // Tuesday night, after dinner.
        $this->assertSame(['open' => false, 'detail' => 'ouvre demain à 11h30'],
            Opening::status(Carbon::parse('2026-11-17 23:00', 'Europe/Zurich')));
        // Saturday night: Sunday and Monday are closed.
        $this->assertSame(['open' => false, 'detail' => 'ouvre mardi à 11h30'],
            Opening::status(Carbon::parse('2026-11-21 23:00', 'Europe/Zurich')));
    }

    public function test_the_top_bar_is_on_every_page(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-17 19:00', 'Europe/Zurich'));

        $this->get(route('guest.fr.menu'))
            ->assertOk()
            ->assertSee('class="top-bar"', false)
            ->assertSee('jusqu’à 22h00');
    }
}
