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
    'menu_title' => 'Sections de la carte',
    'menu_description' => 'Glissez une section pour la réordonner. Chaque section contient ses plats.',

    'auto' => 'auto',

    'hint' => [
        'translation' => 'Écrivez en français. Un champ anglais laissé vide est traduit automatiquement et suit ensuite le français ; dès que vous y écrivez quelque chose, c’est votre texte qui reste.',
        'daily_language' => 'La carte du jour s’écrit en français. L’anglais est traduit automatiquement et se corrige dans Administration → Carte.',
        'auto' => 'Traduction automatique, pas encore relue.',
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
        'daily_on' => 'Date affichée sur la carte du jour',
        'daily_dishes' => 'Plats de la carte du jour',
        'closed_all_day' => 'Fermé toute la journée',
        'lunch_from' => 'Midi — de',
        'lunch_to' => 'Midi — à',
        'dinner_from' => 'Soir — de',
        'dinner_to' => 'Soir — à',
        'section' => 'Section de la carte',
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
    ],

    'gallery' => [
        'delicacies' => 'Accueil',
        'restaurant' => 'Restaurant',
        'hotel' => 'Hôtel',
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
