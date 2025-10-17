<?php

/**
 * Template de base pour les pages
 */

get_header(); ?>

<main id="site-content" role="main" class="page-container">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <a href="#" data-modal="contact" class="btn-open-contact">Contact</a>

                </header>

                <div class="page-content">
                    <?php the_content(); ?>
                </div>
            </article>

        <?php endwhile;
    else : ?>
        <p>Aucune page trouvée.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>