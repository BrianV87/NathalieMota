<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <header class="site-header">
        <div class="container header-inner">

            <div class="brand">
                <?php
                if (function_exists('the_custom_logo') && has_custom_logo()) {
                    the_custom_logo();
                } else {
                    // Fallback si aucun logo défini
                    echo '<a href="' . esc_url(home_url('/')) . '" class="site-title">'
                        . get_bloginfo('name') . '</a>';
                }
                ?>
            </div>

            <nav class="site-nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'main',
                    'container'      => false,
                    'menu_class'     => 'menu',
                ]);
                ?>
            </nav>

            <button class="burger" aria-label="Menu mobile">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- =========================
       MENU MOBILE DYNAMIQUE
  ========================== -->
    <div id="mobileMenu" class="mobile-menu">
        <div class="mobile-menu-header">
            <img src="http://nathaliemota.local/wp-content/uploads/2025/10/logo-nathalie-mota.png" alt="Nathalie Mota">
            <button class="mobile-menu-close" aria-label="Fermer le menu">&times;</button>
        </div>

        <nav class="mobile-menu-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'main', // Même menu que la nav principale
                'container'      => false,
                'menu_class'     => 'menu-mobile',
            ]);
            ?>
        </nav>
    </div>