<?php
if (!defined('ABSPATH')) exit; // Sécurité

function nathalie_mota_setup()
{
    // Support du titre automatique
    add_theme_support('title-tag');
    // Images à la une
    add_theme_support('post-thumbnails');
    // Logo personnalisé
    add_theme_support('custom-logo');

    // Enregistre les emplacements de menu
    register_nav_menus([
        'main'   => __('Menu principal', 'nathalie-mota'),
        'footer' => __('Menu bas de page', 'nathalie-mota'),
    ]);
}
add_action('after_setup_theme', 'nathalie_mota_setup');
