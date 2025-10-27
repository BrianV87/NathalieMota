<?php

/** Single Photo Template  **/
get_header();
if (have_posts()) :
    while (have_posts()) : the_post();

        // --- Custom fields ---
        $reference = get_post_meta(get_the_ID(), '_reference', true);
        $annee     = get_post_meta(get_the_ID(), '_annee', true);
        $type      = get_post_meta(get_the_ID(), '_type', true);

        // --- Taxonomies ---
        $categories = get_the_terms(get_the_ID(), 'categorie');
        $formats    = get_the_terms(get_the_ID(), 'format');
        $cat_names    = $categories && ! is_wp_error($categories) ? implode(', ', wp_list_pluck($categories, 'name')) : '';
        $format_names = $formats && ! is_wp_error($formats) ? implode(', ', wp_list_pluck($formats, 'name')) : '';

        // === GLOBAL NAV | all photos loop infinite ===
        $all_photo_ids = get_posts([
            'post_type'      => 'photo',
            'fields'         => 'ids',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'categorie',
                    'field'    => 'term_id',
                    'terms'    => wp_list_pluck($categories, 'term_id'),
                ],
            ],
        ]);

        $current_id    = get_the_ID();
        $current_index = array_search($current_id, $all_photo_ids, true);
        $total         = count($all_photo_ids);
        $prev_id = $all_photo_ids[($current_index - 1 + $total) % $total];
        $next_id = $all_photo_ids[($current_index + 1) % $total];
?>

        <main class="single-photo container">
            <!-- A -->
            <div class="single-photo__row first-row">
                <div class="single-photo__col-left">
                    <div class="content-bottom">
                        <h1><?php the_title(); ?></h1>
                        <?php if ($reference): ?><p>Référence : <?= esc_html($reference) ?></p><?php endif; ?>
                        <?php if ($cat_names): ?><p>Catégorie : <?= esc_html($cat_names) ?></p><?php endif; ?>
                        <?php if ($format_names): ?><p>Format : <?= esc_html($format_names) ?></p><?php endif; ?>
                        <?php if ($type): ?><p>Type : <?= esc_html($type) ?></p><?php endif; ?>
                        <?php if ($annee): ?><p>Année : <?= esc_html($annee) ?></p><?php endif; ?>
                    </div>

                </div>
                <div class="single-photo__col-right">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            </div>

            <!-- B NAV -->
            <div class="single-photo__row second-row">
                <div class="second-left">
                    <p>Cette photo vous intéresse ?</p>
                    <a class="btn-contact" data-modal="contact" data-photo-ref="<?= esc_attr($reference) ?>">Contact</a>
                </div>
                <div class="second-right">
                    <?php if ($total > 1 && $next_id && $next_id != $current_id): ?>
                        <a class="second-thumb" href="<?= esc_url(get_permalink($next_id)) ?>">
                            <?= get_the_post_thumbnail($next_id, 'thumbnail'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="second-arrows">
                        <?php if ($total > 1): ?>
                            <a href="<?= esc_url(get_permalink($prev_id)) ?>"><img src="<?= esc_url(get_stylesheet_directory_uri() . '/assets/images/Line-6.png') ?>" alt=""></a>
                            <a href="<?= esc_url(get_permalink($next_id)) ?>"><img src="<?= esc_url(get_stylesheet_directory_uri() . '/assets/images/Line-7.png') ?>" alt=""></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="single-photo__divider"></div>

            <!-- C -->
            <div class="related-photos">
                <h2>Vous aimerez aussi</h2>
                <div class="related-photos__list">
                    <?php
                    $related_photos = get_posts([
                        'post_type'      => 'photo',
                        'posts_per_page' => 2,
                        'post__not_in'   => [$current_id],
                        'tax_query'      => [
                            [
                                'taxonomy' => 'categorie',
                                'field'    => 'term_id',
                                'terms'    => wp_list_pluck($categories, 'term_id'),
                            ],
                        ],
                    ]);
                    if ($related_photos) :
                        foreach ($related_photos as $photo) :
                            include locate_template('template-parts/photo-block.php');
                        endforeach;
                    else :
                        echo '<p>Aucune autre photo disponible pour le moment.</p>';
                    endif;
                    ?>
                </div>
            </div>
        </main>
<?php endwhile;
endif;
get_footer();
