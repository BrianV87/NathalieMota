<?php

/**
 * Template de base pour les pages
 */

get_header(); ?>

<main id="site-content" role="main" class="page-container">

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!-- Titre de la page -->
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>

                <!-- Contenu principal -->
                <div class="page-content">
                    <?php the_content(); ?>
                </div>

            </article>

        <?php endwhile; ?>

    <?php else : ?>
        <p>Aucune page trouvée.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>