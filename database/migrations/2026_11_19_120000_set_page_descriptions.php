<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * The words under each inner page's hero — the carte, the restaurant, the
 * rooms and contact — as they were rewritten for the short first sentence
 * and the page preview: the live site still had the earlier copy (contact
 * still spoke of a form that is gone). Only the text is set, by page name
 * and language; titles, photographs and every other row stay as the owner
 * left them. A page or a language that is not there is skipped.
 */
return new class extends Migration
{
    private const CONTENT = [
        ['menu', 'fr', 'Le chef vous propose une cuisine traditionnelle et soignée : les filets de perche du lac Léman, ou notre souris d\'agneau confite au Pinot Noir de Founex. La carte suit les saisons, et le plat du jour est écrit à l\'ardoise.'],
        ['menu', 'en', 'The chef cooks traditional, carefully made food: perch fillets from Lake Geneva, or our lamb shank slow-cooked in Founex Pinot Noir. The carte follows the seasons, and the dish of the day is chalked on the board.'],
        ['restaurant', 'fr', 'Au centre du village, à 500 mètres du lac Léman, l\'auberge a été rénovée en juillet 2012. On y mange en salle, à la brasserie, ou dès les beaux jours sur la terrasse ombragée qui donne sur la place et le jardin. Le bar reste ouvert entre les services.'],
        ['restaurant', 'en', 'In the centre of the village, 500 metres from Lake Geneva, the auberge was renovated in July 2012. You can eat in the dining room, in the brasserie, or — once the weather turns — on the shaded terrace looking onto the square and the garden. The bar stays open between services.'],
        ['hotel', 'fr', 'Quelques chambres à l\'étage, simples et calmes, toutes rénovées. Wi-Fi gratuit dans les parties communes et parking privé gratuit. L\'arrêt de bus est à 20 mètres, l\'aéroport de Genève à 15 kilomètres, et le lac à cinq minutes à pied.'],
        ['hotel', 'en', 'A handful of quiet, simply furnished rooms upstairs, all renovated. Free Wi-Fi in the public areas and free private parking. The bus stop is 20 metres away, Geneva Airport 15 kilometres, and the lake a five-minute walk.'],
        ['contact', 'fr', 'Une table, une chambre, une question : appelez-nous, écrivez-nous, ou passez simplement à la Grand\'Rue.'],
        ['contact', 'en', 'A table, a room, a question: call, write, or simply come by the Grand\'Rue.'],
    ];

    public function up(): void
    {
        foreach (self::CONTENT as [$name, $lang, $content]) {
            $page = DB::table('pages')->where('name', $name)->value('id');

            if ($page === null) {
                continue;
            }

            DB::table('page_locales')
                ->where('page_id', $page)
                ->where('lang', $lang)
                ->update(['content' => $content, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // The earlier copy is not kept here; a restore of the backup is
        // the way back.
    }
};
