<?php

/**
 * Template Part: Photo Block
 * Variables attendues : $photo (WP_Post object)
 */

if (! isset($photo) || ! $photo instanceof WP_Post) return;
?>
<a href="<?= esc_url(get_permalink($photo->ID)) ?>"
    class="photo-block"
    data-id="<?= $photo->ID ?>">
    <?= get_the_post_thumbnail($photo->ID, 'full'); ?>
</a>