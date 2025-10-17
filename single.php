<?php

/**
 * Template de base pour les articles
 */

get_header(); ?>

<main id="site-content" role="main" class="single-container">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <p class="entry-meta">
                        Publié le <?php echo get_the_date(); ?> par <?php the_author(); ?>
                    </p>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>

        <?php endwhile;
    else : ?>
        <p>Aucun article trouvé.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>