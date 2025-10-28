<?php

/** Template Part: Photo Block — $photo requis (WP_Post) */
if (!isset($photo) || !($photo instanceof WP_Post)) return;
?>
<a href="<?= esc_url(get_permalink($photo->ID)) ?>"
    class="photo-block"
    data-id="<?= esc_attr($photo->ID) ?>">
    <?= get_the_post_thumbnail($photo->ID, 'full'); ?>
</a>