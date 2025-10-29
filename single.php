<?php

/**
 * Template de base pour l'affichage d'un article (single post)
 */
get_header(); ?>

<main id="site-content" role="main" class="single-container">

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!-- En-tête de l'article -->
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <p class="entry-meta">
                        Publié le <?php echo get_the_date(); ?> par <?php the_author(); ?>
                    </p>
                </header>

                <!-- Contenu principal de l'article -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

            </article>

        <?php endwhile; ?>

    <?php else : ?>
        <p>Aucun article trouvé.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>