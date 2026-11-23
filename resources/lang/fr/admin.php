<?php

return [
    // Shell
    'title' => 'Administration',
    'nav_label' => 'Sections administrables',
    'nav_toggle' => 'Ouvrir le menu d’administration',
    'view_site' => 'Voir le site',

    // Panels — one per group of actions
    'pages_title' => 'Pages',
    'pages_description' => 'L’image, le titre et le texte affichés en haut de chaque page publique.',
    'opening_title' => 'Ouverture',
    'status_title' => 'État du site',
    'status_description' => 'Ferme l’auberge immédiatement, en dehors des congés planifiés.',
    'hours_title' => 'Horaires d’ouverture',
    'hours_description' => 'Les heures de service affichées dans le pied de page et sur la page contact. Les jours fermés servent aussi à dater le plat du jour.',
    'holidays_title' => 'Calendrier des fermetures',
    'holidays_description' => 'Périodes pendant lesquelles l’auberge est fermée. Le bandeau s’affiche automatiquement.',
    'gallery_title' => 'Galerie',
    'gallery_description' => 'Sélectionnez des photos pour les supprimer, ou ajoutez-en de nouvelles.',
    'daily_description' => 'Ce qui change tous les jours. Ces plats sont affichés sur la page d’accueil et en tête de la carte ; ils ne réapparaissent pas dans les sections ci-dessous.',
    'menu_title' => 'Sections de la carte',
    'menu_description' => 'Glissez une section pour la réordonner, ou passez au tableau pour trier et chercher parmi tous les plats.',
    'review_title' => 'À relire',
    'review_description' => 'Les plats dont la version anglaise est encore celle de la machine.',
    'review_more' => 'les :n premiers sont listés ci-dessous',

    'auto' => 'auto',

    // La carte en tableau : tous les plats sur une grille, à lire, trier, chercher
    'view' => [
        'label' => 'Affichage de la carte',
        'sections' => 'Sections',
        'table' => 'Tableau',
    ],

    'table' => [
        'search' => 'Chercher un plat, une section, un mot de la description…',
        'only_auto' => 'Anglais automatique seulement',
        'section' => 'Section',
        'dish' => 'Plat (français)',
        'dish_en' => 'Anglais',
        'price' => 'Prix',
        'edit' => 'Modifier la section :name',
        'dishes' => '{0} aucun plat|{1} 1 plat|[2,*] :count plats',
        'shown' => ':shown plats sur :total',
        'empty' => 'Aucun plat ne correspond à cette recherche.',
    ],

    // Métriques — une bande de chiffres en tête de chaque écran, lus avant
    // qu'un panneau ait à être ouvert.
    'metric' => [
        'status' => 'État actuel',
        'days_open' => 'Jours de service',
        'next_closure' => 'Prochaine fermeture',
        'sections' => 'Sections',
        'dishes' => 'Plats à la carte',
        'daily' => 'Plats du jour',
        'to_review' => 'traductions à relire',
        'pages' => 'Pages publiques',
        'photos' => 'Photos',
    ],

    'hint' => [
        'daily_dates' => 'Choisissez le jour, ou plusieurs jours avec les deux dates. Le plat du jour quitte la page d’accueil tout seul après le dernier jour ; une date laissée vide n’impose pas de limite.',
        'translation' => 'Écrivez en français. Un champ anglais laissé vide est traduit automatiquement et suit ensuite le français ; dès que vous y écrivez quelque chose, c’est votre texte qui reste.',
        'auto' => 'Traduction automatique, pas encore relue.',
    ],

    // Where the card of the day stands, and its days in words
    'daily_state' => [
        'current' => 'En ligne',
        'upcoming' => 'Programmé',
        'ended' => 'Terminé, plus affiché',
        'always' => 'En ligne',
    ],

    'daily_when' => [
        'day' => 'le :from',
        'range' => 'du :from au :until',
        'from' => 'à partir du :from',
        'until' => 'jusqu’au :until',
        'always' => 'jusqu’à nouvel ordre',
    ],

    'pending_migrations' => 'Cette section attend une migration de la base de données. Lancez-la avant de modifier les horaires ou les fermetures — rien n’est enregistré tant qu’elle n’a pas tourné.',

    // Actions
    'action' => [
        'edit' => 'Modifier',
        'create' => 'Ajouter',
        'delete' => 'Supprimer',
        'read' => 'Lire',
        'cancel' => 'Annuler',
        'confirm' => 'Confirmer',
        'save' => 'Enregistrer',
        'add_images' => 'Ajouter des photos',
        'delete_images' => 'Supprimer la sélection',
        'add_section' => 'Nouvelle section',
        'add_holiday' => 'Nouvelle période',
        'edit_holiday' => 'Modifier la période',
        'close' => 'Fermer le site',
        'reopen' => 'Rouvrir le site',
        'reorder' => 'Réordonner',
        'edit_daily' => 'Modifier le plat du jour',
        'add_daily' => 'Ajouter un plat',
        'edit_hours' => 'Modifier les horaires',
    ],

    // Menu editor fields
    'field' => [
        'items' => 'Plats de la section',
        'dish' => 'Plat',
        'description' => 'Description',
        'section_title_fr' => 'Titre de la section (français)',
        'section_title_en' => 'Titre de la section (anglais)',
        'dish_fr' => 'Plat (français)',
        'dish_en' => 'Plat (anglais)',
        'description_fr' => 'Description (français)',
        'description_en' => 'Description (anglais)',
        'price' => 'Prix',
        'daily_dishes' => 'Plats de la carte du jour',
        'daily_new' => 'Nouveau plat',
        'daily_when' => 'Sur la page d’accueil',
        'french' => 'Français',
        'english' => 'Anglais',
        'closed_all_day' => 'Fermé toute la journée',
        'lunch_from' => 'Midi — de',
        'lunch_to' => 'Midi — à',
        'dinner_from' => 'Soir — de',
        'dinner_to' => 'Soir — à',
        'section' => 'Section de la carte',
        'daily_name' => 'Nom',
        'daily_day' => 'Pour quel jour',
        'today' => 'Aujourd’hui',
        'tomorrow' => 'Demain',
        'daily_from' => 'Afficher du',
        'daily_until' => 'Au (inclus)',
        'show_on_home' => 'Afficher sur la page d’accueil',
        'starts_on' => 'Du',
        'ends_on' => 'Au',
        'reason' => 'Motif affiché aux visiteurs (optionnel)',
    ],

    // Confirmations
    'confirm' => [
        'delete_section' => 'Supprimer la section :name et tous ses plats ?',
        'delete_images' => 'Supprimer les photos sélectionnées ?',
        'delete_holiday' => 'Supprimer la fermeture du :from au :to ?',
        'close' => 'L’auberge sera annoncée comme fermée sur tout le site, jusqu’à réouverture manuelle.',
        'reopen' => 'Le bandeau de fermeture disparaîtra, sauf si une période de congés est en cours.',
    ],

    // Empty states
    'empty' => [
        'menu' => 'Aucune section pour l’instant. Créez-en une pour composer la carte.',
        'gallery' => 'Aucune photo dans cette galerie.',
        'holidays' => 'Aucune fermeture planifiée.',
        'daily' => 'Aucun plat du jour affiché. Le bouton ci-dessus en compose un.',
        'hours' => 'Aucun horaire enregistré.',
        'daily_sections' => 'Créez d’abord une section dans la carte : le plat du jour y est rangé comme n’importe quel plat.',
        'review' => 'Tout l’anglais de la carte a été relu.',
    ],

    'gallery' => [
        'delicacies' => 'Accueil',
        'restaurant' => 'Restaurant',
        'hotel' => 'Hôtel',
        'select' => 'Sélectionner la photo :n',
    ],

    'status' => [
        'open' => 'Ouvert',
        'closed' => 'Fermé',
        'open_hint' => 'Le site n’annonce aucune fermeture.',
        'closed_manually' => 'Fermé manuellement, jusqu’à réouverture.',
        'closed_by_calendar' => 'Congés en cours, du :from au :to.',
    ],

    'holiday' => [
        'current' => 'En cours',
        'past' => 'Passée',
        'upcoming' => 'Dans :days jours',
        'no_reason' => 'Sans motif précisé',
    ],

    'selected' => 'sélectionnée(s)',
];
