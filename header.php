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

            <!-- Logo -->
            <div class="site-logo">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    // Logo par défaut si aucun logo n'est défini
                    echo '<a href="' . esc_url(home_url('/')) . '" class="site-title">NATHALIE MOTA</a>';
                }
                ?>
            </div>

            <!-- Menu principal -->
            <nav class="site-nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'main',
                    'container'      => false,
                    'menu_class'     => 'menu',
                    'fallback_cb'    => false,
                ]);
                ?>
            </nav>

        </div>
    </header>