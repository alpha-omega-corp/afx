<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Every public page is its own page to a search engine: its own description,
 * its own canonical, and the same page in the other language beside it.
 */
class PageMetaTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_page_names_itself_and_its_other_language(): void
    {
        Page::factory()->home()->create();
        Page::factory()->menu()->create();
        Page::factory()->restaurant()->create();
        Page::factory()->hotel()->create();

        $hotel = $this->get('/hotel')->assertOk()
            ->assertSee('<link rel="canonical" href="'.url('/hotel').'">', false)
            ->assertSee('<link rel="alternate" hreflang="fr" href="'.url('/hotel').'">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="'.url('/en/hotel').'">', false)
            ->assertSee('<link rel="alternate" hreflang="x-default" href="'.url('/hotel').'">', false)
            ->getContent();

        $this->get('/en/menu')->assertOk()
            ->assertSee('<link rel="canonical" href="'.url('/en/menu').'">', false);

        $home = $this->get('/')->assertOk()->getContent();
        $description = fn (string $html) => preg_match('/<meta name="description" content="([^"]*)">/', $html, $m) ? $m[1] : null;
        $this->assertNotEmpty($description($hotel));
        $this->assertNotSame($description($hotel), $description($home));

        // The switcher's own link, not the <head> alternate.
        $this->assertMatchesRegularExpression(
            '#<a\s+href="'.preg_quote(url('/en/restaurant'), '#').'"\s+hreflang="en"#',
            $this->get('/restaurant')->assertOk()->getContent(),
        );
    }
}
