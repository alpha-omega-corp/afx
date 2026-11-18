<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Support\Translations;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * The auberge's own carte rather than faker words: the menu page is a long
     * list of two-line names and three-digit prices, and it only shows its
     * spacing problems when it is seeded with the real thing.
     *
     * The plats du jour are seeded apart, in DAILY below.
     *
     * @var array<int, array{title: string, items: array<int, array{0: string, 1: float, 2?: string}>}>
     */
    private const CARTE = [
        [
            'title' => 'Suggestions',
            'items' => [
                ['Émincé de veau zurichois', 41.00, 'Tagliatelles et légumes'],
                ['Filets de perche à volonté', 39.50, 'Frites maison et salade — le mercredi'],
                ['Fondue au fromage', 25.50, 'Servie côté brasserie uniquement'],
            ],
        ],
        [
            'title' => 'Nos entrées',
            'items' => [
                ['Salade verte', 6.50],
                ['Salade mêlée', 9.50],
                ['Salade de chèvre chaud sur toast', 15.50],
                ['Cassolette de crevettes', 17.50, "Sautées à l'ail et au persil"],
                ['Assiette de viandes séchées', 25.50],
            ],
        ],
        [
            'title' => 'Nos pâtes',
            'items' => [
                ['Spaghetti bolognaise', 21.50],
                ['Spaghetti carbonara', 21.50],
                ['Tagliatelles aux crevettes', 29.50, 'Crevettes sautées et légumes'],
            ],
        ],
        [
            'title' => 'Nos viandes',
            'items' => [
                ['Souris d\'agneau confite au Pinot Noir de Founex', 41.50],
                ['Cordon bleu de veau maison', 40.50, 'Frites maison et légumes'],
                ['Escalope de veau viennoise', 39.50, 'Frites maison et légumes'],
                ['Tartare de bœuf coupé au couteau', 36.50, 'Toasts, beurre et frites maison'],
                ['Rumsteak de bœuf nature', 35.50, 'Sauce poivre vert ou Café de Paris, +5.00'],
                ['Émincé de poulet au curry rouge', 34.50, 'Riz basmati et légumes'],
            ],
        ],
        [
            'title' => 'Nos poissons et crustacés',
            'items' => [
                ['Filets de perche du Léman façon meunière', 41.50, 'Selon arrivage'],
                ['Crevettes sautées', 36.50, "À l'ail et au persil"],
            ],
        ],
        [
            'title' => 'Carte estivale',
            'items' => [
                ['Risotto aux crevettes', 35.00, 'Ail et persil'],
                ['Foie de veau aux oignons', 34.50, 'Riz et légumes'],
                ['Rosbif froid', 31.50, 'Sauce tartare, salade et frites maison'],
                ['Salade de crevettes', 29.50, "Sautées à l'ail et au persil"],
                ['Salade du chef', 28.50, 'Crudités, jambon cru, rosbif, gruyère, melon et œuf'],
                ['Salade de poulet au curry', 24.50, 'Et ses crudités'],
                ['Melon au jambon cru', 24.50],
                ['Salade paysanne', 23.50, 'Verdure, crudités, lardons, croûtons et gruyère'],
            ],
        ],
        [
            'title' => "Pour les p'tits loups",
            'items' => [
                ['Filets de perche', 15.50, 'Frites maison'],
                ['Spaghetti bolognaise', 13.50],
                ['Spaghetti carbonara', 13.50],
                ['Cordon bleu de poulet', 13.50, 'Frites maison'],
            ],
        ],
        [
            'title' => 'Nos desserts',
            'items' => [
                ['Café gourmand', 14.50],
                ['Moelleux au chocolat', 13.50],
                ['Tarte fine aux pommes', 12.50, 'Servie avec une glace vanille'],
                ['Coupe chaud-froid', 12.50, 'Coulis de fruits rouges chaud et deux boules vanille'],
                ['Crème brûlée à la cassonade', 10.50],
                ['Assiette de trois fromages', 10.50],
            ],
        ],
        [
            'title' => 'Glaces et sorbets',
            'items' => [
                ['Coupe valaisanne', 12.50],
                ['Coupe Williams', 12.50],
                ['Coupe framboise', 12.50],
                ['Colonel', 12.50],
                ['Coupe Danemark', 11.00],
                ['Café glacé', 11.00],
                ['Boule de glace ou de sorbet', 4.00, 'Parfums du jour'],
            ],
        ],
    ];

    /**
     * A plat du jour to look at: a short autumn lunch, in the order it is
     * eaten. Each dish is a dish of the carte carrying the daily flag, filed
     * under the section named here. Staff replace it from the home page or
     * Administration → Carte. Every line is in the glossary, so the English
     * is there without a network call.
     *
     * @var array<int, array{section: string, title: string, price: float, description: string}>
     */
    private const DAILY = [
        [
            'section' => 'Nos entrées',
            'title' => 'Velouté de courge',
            'price' => 12.50,
            'description' => 'Graines de courge torréfiées et crème fraîche',
        ],
        [
            'section' => 'Nos viandes',
            'title' => 'Civet de chevreuil',
            'price' => 38.50,
            'description' => 'Spätzli maison, chou rouge et marrons glacés',
        ],
        [
            'section' => 'Nos desserts',
            'title' => 'Tarte aux pruneaux',
            'price' => 9.50,
            'description' => 'Servie tiède, crème double de la Gruyère',
        ],
    ];

    /**
     * The carte is seeded in French, which is the language it is written in.
     * The English comes from the shipped glossary — every line above is in it
     * — so a fresh database serves a complete carte in both languages without
     * a single network call. See App\Support\Translations.
     */
    public function run(): void
    {
        $sections = [];

        foreach (self::CARTE as $position => $section) {
            $record = MenuSection::create(['position' => $position]);

            Translations::write($record, ['fr' => ['title' => $section['title']]]);

            foreach ($section['items'] as $item) {
                $dish = MenuItem::create([
                    'menu_section_id' => $record->id,
                    'price' => $item[1],
                ]);

                Translations::write($dish, ['fr' => [
                    'title' => $item[0],
                    'description' => $item[2] ?? null,
                ]]);
            }

            $sections[$section['title']] = $record->id;
        }

        foreach (self::DAILY as $position => $special) {
            $dish = MenuItem::create([
                'menu_section_id' => $sections[$special['section']],
                'price' => $special['price'],
                'daily' => true,
                'daily_position' => $position,
            ]);

            Translations::write($dish, ['fr' => [
                'title' => $special['title'],
                'description' => $special['description'],
            ]]);
        }
    }
}
