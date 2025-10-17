<?php
if (!defined('ABSPATH')) exit; // Sécurité de base

function nathalie_mota_setup()
{
    // Support du titre automatique dans l'onglet
    add_theme_support('title-tag');
    // Support des images à la une
    add_theme_support('post-thumbnails');
    // Support du logo
    add_theme_support('custom-logo');
}

add_action('after_setup_theme', 'nathalie_mota_setup');
