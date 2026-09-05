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
    'holidays_title' => 'Calendrier des fermetures',
    'holidays_description' => 'Périodes pendant lesquelles l’auberge est fermée. Le bandeau s’affiche automatiquement.',
    'gallery_title' => 'Galerie',
    'gallery_description' => 'Sélectionnez des photos pour les supprimer, ou ajoutez-en de nouvelles.',
    'menu_title' => 'Sections de la carte',
    'menu_description' => 'Glissez une section pour la réordonner. Chaque section contient ses plats.',
    'messages_title' => 'Messages reçus',
    'messages_description' => 'Messages envoyés depuis le formulaire de contact.',

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
    ],

    // Menu editor fields
    'field' => [
        'items' => 'Plats de la section',
        'dish' => 'Plat',
        'description' => 'Description',
        'price' => 'Prix',
        'starts_on' => 'Du',
        'ends_on' => 'Au',
        'reason' => 'Motif affiché aux visiteurs (optionnel)',
    ],

    // Confirmations
    'confirm' => [
        'delete_section' => 'Supprimer la section :name et tous ses plats ?',
        'delete_images' => 'Supprimer les photos sélectionnées ?',
        'delete_message' => 'Supprimer définitivement le message de :name ?',
        'delete_holiday' => 'Supprimer la fermeture du :from au :to ?',
        'close' => 'L’auberge sera annoncée comme fermée sur tout le site, jusqu’à réouverture manuelle.',
        'reopen' => 'Le bandeau de fermeture disparaîtra, sauf si une période de congés est en cours.',
    ],

    // Empty states
    'empty' => [
        'menu' => 'Aucune section pour l’instant. Créez-en une pour composer la carte.',
        'gallery' => 'Aucune photo dans cette galerie.',
        'messages' => 'Aucun message reçu pour l’instant.',
        'holidays' => 'Aucune fermeture planifiée.',
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
