<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** The carte as a table, beside the sections, on the admin menu screen. */
class MenuTableTest extends TestCase
{
    use RefreshDatabase;

    private function dish(MenuSection $section, string $title, bool $daily = false): MenuItem
    {
        $item = MenuItem::factory()->create([
            'menu_section_id' => $section->id,
            'price' => 24.5,
            'daily' => $daily,
        ]);
        $item->locales()->updateOrCreate(['lang' => 'fr'], ['title' => $title]);

        return $item;
    }

    /** @return array<string, mixed> */
    private function config(): array
    {
        $html = $this->actingAs(User::factory()->create())
            ->get(route('admin.menu'))
            ->assertOk()
            ->assertSee('role="tablist"', false)
            ->getContent();

        preg_match("/data-config='([^']*)'/", $html, $match);
        $this->assertNotEmpty($match, 'The table carries its rows in data-config.');

        return json_decode(html_entity_decode($match[1], ENT_QUOTES), true, flags: JSON_THROW_ON_ERROR);
    }

    public function test_the_table_holds_every_carte_dish_but_not_the_card_of_the_day(): void
    {
        $section = MenuSection::factory()->create();
        $this->dish($section, 'Filets de perche');
        $this->dish($section, 'Civet de chevreuil', daily: true);

        $config = $this->config();

        $rows = array_column($config['rows'], null, 'title_fr');
        $this->assertArrayNotHasKey('Civet de chevreuil', $rows);
        $this->assertArrayHasKey('Filets de perche', $rows);
        $this->assertSame(24.5, $rows['Filets de perche']['price']);
        $this->assertSame($section->id, $rows['Filets de perche']['section']);
    }

    public function test_each_section_points_at_an_editor_outside_the_hidden_list(): void
    {
        $section = MenuSection::factory()->create();
        $this->dish($section, 'Filets de perche');

        $config = $this->config();
        $modal = $config['sections'][0]['modal'];

        $html = $this->get(route('admin.menu'))->getContent();

        // The editor exists, and is declared after the sortable list closes:
        // a dialog inside a display:none ancestor cannot be opened.
        $list = strpos($html, 'id="menu-view-sections-panel"');
        $listEnd = strpos($html, '</ul>', strrpos($html, 'admin-section__items'));
        $editor = strpos($html, 'id="'.$modal.'"');

        $this->assertNotFalse($editor);
        $this->assertGreaterThan($listEnd, $editor);
        $this->assertGreaterThan($list, $listEnd);
    }
}
