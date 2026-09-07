<?php

/**
 * The auberge's own carte, French to English.
 *
 * The first thing every translation is looked up in, and for this site very
 * nearly the last: a village carte is a closed vocabulary of a hundred-odd
 * phrases, and a curated line is better English than any machine will write
 * for "coupe chaud-froid". Anything missing here falls through to the free
 * machine pass, and whatever that returns can be corrected by hand in
 * Administration → Carte, which is the only place any of this is authoritative.
 *
 * Keys are matched loosely — case, accents, apostrophes and surrounding
 * punctuation are all normalised away first — so "Nos Entrées" and
 * "nos entrees" both land on the same line. See App\Services\Translator.
 *
 * Swiss and French names that an English menu would keep as they are —
 * Café de Paris, Pinot Noir, Williams, Danemark — are deliberately kept.
 */
return [
    // --- Section headings ------------------------------------------------
    'suggestions' => 'Suggestions',
    'nos entrées' => 'Our starters',
    'nos pâtes' => 'Our pasta',
    'nos viandes' => 'Our meats',
    'nos poissons et crustacés' => 'Our fish and shellfish',
    'nos desserts' => 'Our desserts',
    'nos salades' => 'Our salads',
    'nos fromages' => 'Our cheeses',
    'carte estivale' => 'Summer menu',
    'carte hivernale' => 'Winter menu',
    'glaces et sorbets' => 'Ice creams and sorbets',
    "pour les p'tits loups" => 'For little ones',
    'menu du jour' => 'Menu of the day',
    'plat du jour' => 'Dish of the day',
    'boissons' => 'Drinks',
    'vins' => 'Wines',
    'apéritifs' => 'Aperitifs',
    'cafés et digestifs' => 'Coffee and digestifs',
    'entrées' => 'Starters',
    'desserts' => 'Desserts',
    'viandes' => 'Meats',
    'poissons' => 'Fish',
    'pâtes' => 'Pasta',
    'salades' => 'Salads',
    'fromages' => 'Cheeses',

    // --- Dishes ----------------------------------------------------------
    'émincé de veau zurichois' => 'Zurich-style veal',
    'filets de perche à volonté' => 'All-you-can-eat perch fillets',
    'fondue au fromage' => 'Cheese fondue',
    'salade verte' => 'Green salad',
    'salade mêlée' => 'Mixed salad',
    'salade de chèvre chaud sur toast' => 'Warm goat cheese salad on toast',
    'cassolette de crevettes' => 'Prawn cassolette',
    'assiette de viandes séchées' => 'Plate of air-dried meats',
    'spaghetti bolognaise' => 'Spaghetti bolognese',
    'spaghetti carbonara' => 'Spaghetti carbonara',
    'tagliatelles aux crevettes' => 'Tagliatelle with prawns',
    "souris d'agneau confite au pinot noir de founex" => 'Lamb shank confit in Founex Pinot Noir',
    'cordon bleu de veau maison' => 'Homemade veal cordon bleu',
    'escalope de veau viennoise' => 'Wiener schnitzel of veal',
    'tartare de bœuf coupé au couteau' => 'Hand-cut beef tartare',
    'rumsteak de bœuf nature' => 'Plain beef rump steak',
    'émincé de poulet au curry rouge' => 'Red curry chicken strips',
    'filets de perche du léman façon meunière' => 'Lake Geneva perch fillets meunière',
    'crevettes sautées' => 'Pan-fried prawns',
    'risotto aux crevettes' => 'Prawn risotto',
    'foie de veau aux oignons' => 'Calf liver with onions',
    'rosbif froid' => 'Cold roast beef',
    'salade de crevettes' => 'Prawn salad',
    'salade du chef' => "Chef's salad",
    'salade de poulet au curry' => 'Curried chicken salad',
    'melon au jambon cru' => 'Melon with cured ham',
    'salade paysanne' => 'Country salad',
    'filets de perche' => 'Perch fillets',
    'cordon bleu de poulet' => 'Chicken cordon bleu',
    'café gourmand' => 'Coffee with a plate of sweets',
    'moelleux au chocolat' => 'Warm chocolate fondant',
    'tarte fine aux pommes' => 'Thin apple tart',
    'coupe chaud-froid' => 'Hot-and-cold sundae',
    'crème brûlée à la cassonade' => 'Brown sugar crème brûlée',
    'assiette de trois fromages' => 'Plate of three cheeses',
    'coupe valaisanne' => 'Valais sundae',
    'coupe williams' => 'Williams pear sundae',
    'coupe framboise' => 'Raspberry sundae',
    'colonel' => 'Lemon sorbet with vodka',
    'coupe danemark' => 'Danemark sundae',
    'café glacé' => 'Iced coffee',
    'boule de glace ou de sorbet' => 'Scoop of ice cream or sorbet',

    // --- Descriptions ----------------------------------------------------
    'tagliatelles et légumes' => 'Tagliatelle and vegetables',
    'frites maison et salade — le mercredi' => 'Homemade fries and salad — Wednesdays',
    'servie côté brasserie uniquement' => 'Served in the brasserie only',
    "sautées à l'ail et au persil" => 'Pan-fried with garlic and parsley',
    'crevettes sautées et légumes' => 'Pan-fried prawns and vegetables',
    'frites maison et légumes' => 'Homemade fries and vegetables',
    'toasts, beurre et frites maison' => 'Toast, butter and homemade fries',
    'sauce poivre vert ou café de paris, +5.00' => 'Green peppercorn or Café de Paris sauce, +5.00',
    'riz basmati et légumes' => 'Basmati rice and vegetables',
    'selon arrivage' => 'Subject to the day’s catch',
    "à l'ail et au persil" => 'With garlic and parsley',
    'ail et persil' => 'Garlic and parsley',
    'riz et légumes' => 'Rice and vegetables',
    'sauce tartare, salade et frites maison' => 'Tartare sauce, salad and homemade fries',
    'crudités, jambon cru, rosbif, gruyère, melon et œuf' => 'Raw vegetables, cured ham, roast beef, gruyère, melon and egg',
    'et ses crudités' => 'With raw vegetables',
    'verdure, crudités, lardons, croûtons et gruyère' => 'Greens, raw vegetables, bacon, croutons and gruyère',
    'frites maison' => 'Homemade fries',
    'servie avec une glace vanille' => 'Served with vanilla ice cream',
    'coulis de fruits rouges chaud et deux boules vanille' => 'Warm red berry coulis and two scoops of vanilla',
    'parfums du jour' => "Today's flavours",

    // --- Everyday words, for phrases the lines above do not cover ---------
    'maison' => 'Homemade',
    'nouveau' => 'New',
    'végétarien' => 'Vegetarian',
    'de saison' => 'Seasonal',
    'sans gluten' => 'Gluten free',
    'supplément' => 'Extra',
    'au choix' => 'Your choice',
    'servi avec' => 'Served with',
    'salade' => 'Salad',
    'frites' => 'Fries',
    'légumes' => 'Vegetables',
    'riz' => 'Rice',
    'poulet' => 'Chicken',
    'bœuf' => 'Beef',
    'veau' => 'Veal',
    'agneau' => 'Lamb',
    'porc' => 'Pork',
    'poisson' => 'Fish',
    'crevettes' => 'Prawns',
    'fromage' => 'Cheese',
    'dessert' => 'Dessert',
    'café' => 'Coffee',
    'thé' => 'Tea',
    'vin rouge' => 'Red wine',
    'vin blanc' => 'White wine',
    'bière' => 'Beer',
    'eau minérale' => 'Mineral water',
];
