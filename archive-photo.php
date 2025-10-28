<?php

/**
 * Template pour l'archive des photos (avec filtres)
 * Aligné sur le rendu du front-page.php et compatible avec les URLs propres
 */
get_header();
?>

<main class="home">
    <!-- HERO (optionnel) -->
    <?php
    $hero_url = '';
    $random_photo = get_posts([
        'post_type'      => 'photo',
        'posts_per_page' => 1,
        'orderby'        => 'rand',
        'fields'         => 'ids',
    ]);
    if (!empty($random_photo)) {
        $hero_url = get_the_post_thumbnail_url($random_photo[0], 'full');
    }
    ?>
    <?php if ($hero_url): ?>
        <section class="home-hero" style="background-image:url(<?= esc_url($hero_url) ?>);">
            <div class="home-hero__inner">
                <h1>PHOTOGRAPHE&nbsp;EVENT</h1>
            </div>
        </section>
    <?php endif; ?>

    <!-- CONTENU -->
    <section class="home-content container">
        <!-- Barre de filtres -->
        <div class="filters-bar">
            <div class="filters-left">
                <!-- Catégories -->
                <div class="dropdown" data-filter="categorie">
                    <button class="dropdown__toggle">
                        <span class="dropdown__label">Catégories</span>
                        <span class="dropdown__caret"></span>
                    </button>
                    <div class="dropdown__menu">
                        <button class="dropdown__item" data-value="">Toutes les catégories</button>
                        <?php
                        $cats = get_terms(['taxonomy' => 'categorie', 'hide_empty' => true]);
                        foreach ($cats as $cat): ?>
                            <button class="dropdown__item" data-value="<?php echo esc_attr($cat->slug); ?>">
                                <?php echo esc_html($cat->name); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Formats -->
                <div class="dropdown" data-filter="format">
                    <button class="dropdown__toggle">
                        <span class="dropdown__label">Formats</span>
                        <span class="dropdown__caret"></span>
                    </button>
                    <div class="dropdown__menu">
                        <button class="dropdown__item" data-value="">Tous les formats</button>
                        <?php
                        $fmts = get_terms(['taxonomy' => 'format', 'hide_empty' => true]);
                        foreach ($fmts as $fmt): ?>
                            <button class="dropdown__item" data-value="<?php echo esc_attr($fmt->slug); ?>">
                                <?php echo esc_html($fmt->name); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="filters-right">
                <!-- Trier par -->
                <div class="dropdown" data-filter="ordre">
                    <button class="dropdown__toggle">
                        <span class="dropdown__label">Trier par</span>
                        <span class="dropdown__caret"></span>
                    </button>
                    <div class="dropdown__menu">
                        <button class="dropdown__item" data-value="recentes">À partir des plus récentes</button>
                        <button class="dropdown__item" data-value="anciennes">À partir des plus anciennes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des photos -->
        <div class="related-photos__list js-photo-list">
            <?php
            // Lire les filtres depuis l'URL propre (ex: /categorie/mariage/format/paysage/)
            $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
            $pathParts = explode('/', $path);

            // Extraire les valeurs des filtres
            $categorie = in_array('categorie', $pathParts) ? $pathParts[array_search('categorie', $pathParts) + 1] : '';
            $format = in_array('format', $pathParts) ? $pathParts[array_search('format', $pathParts) + 1] : '';
            $ordre = in_array('ordre', $pathParts) ? $pathParts[array_search('ordre', $pathParts) + 1] : 'recentes';

            // Construire tax_query
            $tax_query = [];
            if ($categorie) {
                $tax_query[] = [
                    'taxonomy' => 'categorie',
                    'field'    => 'slug',
                    'terms'    => $categorie,
                ];
            }
            if ($format) {
                $tax_query[] = [
                    'taxonomy' => 'format',
                    'field'    => 'slug',
                    'terms'    => $format,
                ];
            }
            if (count($tax_query) > 1) {
                $tax_query['relation'] = 'AND';
            }

            // Requête principale
            $args = [
                'post_type'      => 'photo',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => ($ordre === 'anciennes') ? 'ASC' : 'DESC',
                'paged'          => 1,
            ];
            if (!empty($tax_query)) {
                $args['tax_query'] = $tax_query;
            }

            $photos = new WP_Query($args);

            if ($photos->have_posts()) :
                while ($photos->have_posts()) : $photos->the_post();
                    $photo = get_post();
                    include locate_template('template-parts/photo-block.php');
                endwhile;
                wp_reset_postdata();
            else :
                echo "<p class='no-results'>Aucune photo trouvée.</p>";
            endif;
            ?>
        </div>

        <!-- BOUTON LOAD MORE -->
        <div class="load-more-wrapper">
            <button id="load-more" class="btn-load-more">Charger plus</button>
        </div>
    </section>
</main>

<?php get_footer(); ?>