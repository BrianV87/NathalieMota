<?php
get_header();
?>

<main class="single-photo">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="photo-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="photo-content">
                    <?php the_content(); ?>
                </div>

                <ul class="photo-infos">
                    <li><strong>Référence :</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_reference', true)); ?></li>
                    <li><strong>Année :</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_annee', true)); ?></li>
                    <li><strong>Type :</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_type', true)); ?></li>
                    <li><strong>Catégorie :</strong> <?php the_terms(get_the_ID(), 'categorie'); ?></li>
                    <li><strong>Format :</strong> <?php the_terms(get_the_ID(), 'format'); ?></li>
                </ul>
            </article>
        <?php endwhile;
    endif;
    ?>
</main>

<?php
get_footer();
