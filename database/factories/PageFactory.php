<?php

namespace Database\Factories;

use App\Enums\Language;
use App\Models\Page;
use App\Models\PageLocale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Real copy, not lorem: a seeded site should read like the auberge so the
     * layout is judged against sentences of the length it will actually carry.
     *
     * Sourced from the auberge's own pages and listings (Grand'Rue 31, 1297
     * Founex — village centre, 500 m from the lake, renovated July 2012).
     * The `title` of a page is its hero lead; `content` is the body paragraph.
     *
     * @var array<string, array<string, array{title: string, content: string}>>
     */
    private const COPY = [
        \App\Enums\Page::HOME->value => [
            'fr' => [
                'title' => "Nous sommes ravis de vous accueillir dans une auberge traditionnelle et chaleureuse.",
                'content' => "Au centre du village, à cinq minutes du lac Léman, l'Auberge de Founex réunit sous un même toit une salle à manger, un bar et quelques chambres à l'étage. La maison a été rénovée en juillet 2012. On y sert une cuisine régionale à midi comme le soir, et sur la terrasse dès les beaux jours.",
            ],
            'en' => [
                'title' => 'We are delighted to welcome you to a traditional, warm-hearted village inn.',
                'content' => 'In the middle of the village, five minutes from Lake Geneva, the Auberge de Founex gathers a dining room, a bar and a handful of rooms upstairs under one roof. The house was renovated in July 2012. Regional cooking is served at midday and in the evening, and on the terrace once the weather turns.',
            ],
        ],
        \App\Enums\Page::ABOUT->value => [
            'fr' => [
                'title' => "La maison",
                'content' => "L'Auberge Communale tient la place du village depuis longtemps : une horloge, des volets, une porte en bois sous une marquise en fer forgé. À l'intérieur, une salle boisée, une brasserie, un bar où l'on s'arrête entre deux services, et un jardin qui donne sur le terrain de pétanque.",
            ],
            'en' => [
                'title' => 'The house',
                'content' => 'The Auberge Communale has held the village square for a long time: a clock tower, shutters, a wooden door under wrought-iron glass. Inside there is a panelled dining room, a brasserie, a bar to stop at between services, and a garden looking onto the pétanque court.',
            ],
        ],
        \App\Enums\Page::MENU->value => [
            'fr' => [
                'title' => 'La carte',
                'content' => "Le chef vous propose une cuisine traditionnelle et soignée : les filets de perche du lac Léman, ou notre souris d'agneau confite au Pinot Noir de Founex. La carte suit les saisons, et le plat du jour est écrit à l'ardoise.",
            ],
            'en' => [
                'title' => 'The carte',
                'content' => 'The chef cooks traditional, carefully made food: perch fillets from Lake Geneva, or our lamb shank slow-cooked in Founex Pinot Noir. The carte follows the seasons, and the dish of the day is chalked on the board.',
            ],
        ],
        \App\Enums\Page::RESTAURANT->value => [
            'fr' => [
                'title' => 'Le restaurant',
                'content' => "Au centre du village, à 500 mètres du lac Léman, l'auberge a été rénovée en juillet 2012. On y mange en salle, à la brasserie, ou dès les beaux jours sur la terrasse ombragée qui donne sur la place et le jardin. Le bar reste ouvert entre les services.",
            ],
            'en' => [
                'title' => 'The restaurant',
                'content' => 'In the centre of the village, 500 metres from Lake Geneva, the auberge was renovated in July 2012. You can eat in the dining room, in the brasserie, or — once the weather turns — on the shaded terrace looking onto the square and the garden. The bar stays open between services.',
            ],
        ],
        \App\Enums\Page::HOTEL->value => [
            'fr' => [
                'title' => "Les chambres",
                'content' => "Quelques chambres à l'étage, simples et calmes, toutes rénovées. Wi-Fi gratuit dans les parties communes et parking privé gratuit. L'arrêt de bus est à 20 mètres, l'aéroport de Genève à 15 kilomètres, et le lac à cinq minutes à pied.",
            ],
            'en' => [
                'title' => 'The rooms',
                'content' => 'A handful of quiet, simply furnished rooms upstairs, all renovated. Free Wi-Fi in the public areas and free private parking. The bus stop is 20 metres away, Geneva Airport 15 kilometres, and the lake a five-minute walk.',
            ],
        ],
        \App\Enums\Page::CONTACT->value => [
            'fr' => [
                'title' => 'Nous écrire',
                'content' => "Une table, une chambre, une question : appelez-nous, écrivez-nous, ou passez simplement à la Grand'Rue.",
            ],
            'en' => [
                'title' => 'Write to us',
                'content' => "A table, a room, a question: call, write, or simply come by the Grand'Rue.",
            ],
        ],
    ];

    public function definition(): array
    {
        return [
            'image' => 'storage/mock/hero-bg.jpg',
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Page $page) {
           // The state sets `name` from the enum; it is still the case object
           // in memory, and only becomes a string once the row is read back.
           $name = $page->name instanceof \App\Enums\Page
               ? $page->name->value
               : $page->name;

           PageLocale::factory()
               ->for($page)
               ->count(2)
               ->sequence(function (Sequence $sequence) use ($name) {
                   $lang = $sequence->index == 0
                        ? Language::FR
                        : Language::EN;

                   // A page whose name has no copy on file keeps the faker
                   // default, so a new page type is never seeded blank.
                   return ['lang' => $lang] + (self::COPY[$name][$lang->value] ?? []);
               })->create();

        });
    }

    public function home(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::HOME,
        ]);
    }

    public function about(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::ABOUT,
        ]);
    }

    public function menu(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::MENU,
            'image' => 'storage/mock/carte.jpg',
        ]);
    }

    public function restaurant(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::RESTAURANT,
            'image' => 'storage/mock/restaurant.jpg',
        ]);
    }

    public function hotel(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::HOTEL,
            'image' => 'storage/mock/hotel.jpg',
        ]);
    }

    public function contact(): self
    {
        return $this->state([
            'name' => \App\Enums\Page::CONTACT,
        ]);
    }
}
