<?php

/** 
 * Template Part: Photo Card with Hover — $photo requis (WP_Post)
 * Carte photo utilisée dans les listes (archive / related / AJAX)
 */
if (!isset($photo) || !($photo instanceof WP_Post)) return;

// Récupération dynamique des données
$ref = get_post_meta($photo->ID, '_reference', true);

// Première catégorie trouvée (s'il y en a plusieurs)
$cats = get_the_terms($photo->ID, 'categorie');
$cat_name = '';
if ($cats && !is_wp_error($cats)) {
    $first = array_shift($cats);
    $cat_name = $first ? $first->name : '';
}

// URL vers la page single
$permalink = get_permalink($photo->ID);

// Texte alternatif sur la mini — fallback = titre
$alt = get_the_title($photo->ID);
?>

<figure class="photo-card" data-id="<?= esc_attr($photo->ID) ?>">
    <div class="photo-card__media">

        <?php
        // Image optimisée (srcset fourni automatiquement par WP)
        echo get_the_post_thumbnail(
            $photo->ID,
            'photo_medium',
            ['alt' => esc_attr($alt), 'loading' => 'lazy', 'decoding' => 'async']
        );
        ?>

        <!-- Overlay visible au survol (desktop) ou via JS (mobile) -->
        <div class="photo-card__overlay" aria-hidden="true">

            <!-- Bouton plein écran → ouvre la lightbox -->
            <button
                type="button"
                class="photo-card__btn photo-card__btn--fullscreen"
                aria-label="Ouvrir en plein écran"
                data-lightbox="open"
                data-photo-id="<?= esc_attr($photo->ID) ?>"
                data-photo-ref="<?= esc_attr($ref) ?>"
                data-photo-cat="<?= esc_attr($cat_name) ?>">
                <img
                    src="<?= esc_url(get_stylesheet_directory_uri() . '/assets/images/Icon_fullscreen.png') ?>"
                    alt=""
                    width="24" height="24"
                    loading="lazy">
            </button>

            <!-- Bouton œil → lien vers la page single -->
            <a
                class="photo-card__btn photo-card__btn--eye"
                href="<?= esc_url($permalink) ?>"
                aria-label="Voir la fiche de la photo">
                <img
                    src="<?= esc_url(get_stylesheet_directory_uri() . '/assets/images/Icon_eye.png') ?>"
                    alt=""
                    width="32" height="32"
                    loading="lazy">
            </a>

            <!-- Méta en bas de la carte (réf + catégorie) -->
            <div class="photo-card__meta">
                <span class="photo-card__ref">
                    <?= $ref ? esc_html($ref) : '&nbsp;' ?>
                </span>
                <span class="photo-card__cat">
                    <?= $cat_name ? esc_html($cat_name) : '&nbsp;' ?>
                </span>
            </div>

        </div>
    </div>
</figure>