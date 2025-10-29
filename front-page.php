<?php

/**
 * Template d'accueil (front-page)
 */
get_header();

/**
 * HERO — source de l'image :
 * 1) si ACF hero_image réglé sur la page d'accueil → prendre celle-ci
 * 2) sinon → fallback = photo aléatoire du CPT photo
 */
$hero_url = '';

$front_id = get_option('page_on_front');
if (function_exists('get_field') && $front_id) {
    $hero_id = get_field('hero_image', $front_id);
    if ($hero_id) {
        $hero_url = wp_get_attachment_image_url($hero_id, 'full');
    }
}

if (!$hero_url) {
    $random_photo = get_posts([
        'post_type'      => 'photo',
        'posts_per_page' => 1,
        'orderby'        => 'rand',
        'fields'         => 'ids',
    ]);
    if (!empty($random_photo)) {
        $hero_url = get_the_post_thumbnail_url($random_photo[0], 'full');
    }
}
?>

<main class="home">

    <!-- HERO -->
    <section class="home-hero" style="<?= $hero_url ? 'background-image:url(' . esc_url($hero_url) . ');' : '' ?>">
        <div class="home-hero__inner">
            <h1>PHOTOGRAPHE&nbsp;EVENT</h1>
        </div>
    </section>


    <section class="home-content container">

        <!-- Barre de filtres (sur taxes + ordre) -->
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
                            <button class="dropdown__item" data-value="<?= esc_attr($cat->slug) ?>">
                                <?= esc_html($cat->name) ?>
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
                            <button class="dropdown__item" data-value="<?= esc_attr($fmt->slug) ?>">
                                <?= esc_html($fmt->name) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <div class="filters-right">
                <!-- Tri par date -->
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


        <!-- Liste des photos initiale (avant AJAX / Load More) -->
        <div class="related-photos__list js-photo-list">
            <?php
            // Filtres GET (front-page utilise querystring avant réécriture)
            $categorie = isset($_GET['categorie']) ? sanitize_title($_GET['categorie']) : '';
            $format    = isset($_GET['format'])    ? sanitize_title($_GET['format'])    : '';
            $ordre     = (isset($_GET['ordre']) && $_GET['ordre'] === 'anciennes') ? 'ASC' : 'DESC';

            // Filtrage taxonomies
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

            // Query WP (premier batch)
            $args = [
                'post_type'      => 'photo',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => $ordre,
                'paged'          => 1,
            ];
            if ($tax_query) {
                $args['tax_query'] = $tax_query;
            }

            $photos = new WP_Query($args);

            if ($photos->have_posts()):
                while ($photos->have_posts()): $photos->the_post();
                    $photo = get_post();
                    include locate_template('template-parts/photo-block.php');
                endwhile;
                wp_reset_postdata();
            else:
                echo "<p class='no-results'>Aucune photo trouvée.</p>";
            endif;
            ?>
        </div>

        <!-- Load more -->
        <div class="load-more-wrapper">
            <button id="load-more" class="btn-load-more">Charger plus</button>
        </div>

    </section>
</main>

<?php get_footer(); ?>