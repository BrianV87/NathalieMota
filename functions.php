<?php
if (!defined('ABSPATH')) exit; // Sécurité

// ===============================
// Configuration du thème
// ===============================
function nathalie_mota_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 48,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
      ]);
      

    register_nav_menus([
        'main'   => __('Menu principal', 'nathalie-mota'),
        'footer' => __('Menu bas de page', 'nathalie-mota'),
    ]);
}
add_action('after_setup_theme', 'nathalie_mota_setup');

// ===============================
// Chargement des styles et scripts
// ===============================
function nathalie_mota_assets()
{
    // Polices Google
    wp_enqueue_style(
        'nm-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&family=Space+Mono:ital,wght@0,400;1,700&display=swap',
        [],
        null
    );

    // Feuille de style principale
    wp_enqueue_style(
        'nm-main',
        get_template_directory_uri() . '/style.css',
        ['nm-fonts'],
        filemtime(get_template_directory() . '/style.css')
    );

    // JavaScript principal (on le créera juste après)
    wp_enqueue_script(
        'nm-scripts',
        get_template_directory_uri() . '/assets/js/scripts.js',
        [],
        filemtime(get_template_directory() . '/assets/js/scripts.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'nathalie_mota_assets');
