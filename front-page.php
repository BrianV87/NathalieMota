<?php

/**
 * Template d'accueil (front-page)
 */
get_header();

/** HERO source
 *  1) Si ACF est présent et qu'une image "hero_image" est remplie sur la page d'accueil → on l'utilise
 *  2) Sinon → on prend une photo ALÉATOIRE du CPT "photo"
 */
$hero_url = '';

$front_id = get_option('page_on_front'); // id de la page d'accueil
if (function_exists('get_field') && $front_id) {
    $hero_id = get_field('hero_image', $front_id); // champ ACF: Image (retour = ID)
    if ($hero_id) {
        $hero_url = wp_get_attachment_image_url($hero_id, 'full');
    }
}

if (!$hero_url) {
    // Fallback aléatoire
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

    <!-- CONTENU -->
    <section class="home-content container">
        <!-- filtres + loop + infinite scroll ici -->
    </section>

</main>

<?php get_footer(); ?>