<?php

/**
 * Template Part: Lightbox
 */
?>

<div id="nm-lightbox" class="nm-lightbox" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Visionneuse">
    <div class="nm-lightbox__overlay" data-lightbox="close"></div>

    <div class="nm-lightbox__dialog" role="document">
        <button type="button" class="nm-lightbox__close" data-lightbox="close" aria-label="Fermer">
            <img src="/wp-content/themes/NathalieMota/assets/images/croix-blanche.svg" alt="Fermer" width="24" height="24">
        </button>

        <figure class="nm-lightbox__figure">
            <img class="nm-lightbox__img" src="" alt="">
            <figcaption class="nm-lightbox__caption">
                <span class="nm-lightbox__caption-ref"></span>
                <span class="nm-lightbox__caption-cat"></span>
            </figcaption>
        </figure>

        <nav class="nm-lightbox__nav" aria-label="Navigation diaporama">
            <button type="button" class="nm-lightbox__prev" data-lightbox="prev" aria-label="Photo précédente">
                <img src="/wp-content/themes/NathalieMota/assets/images/arrow-left-white.svg" width="24" height="24" alt="">
                <span class="nm-lightbox__nav-text">Précédente</span>
            </button>

            <button type="button" class="nm-lightbox__next" data-lightbox="next" aria-label="Photo suivante">
                <span class="nm-lightbox__nav-text">Suivante</span>
                <img src="/wp-content/themes/NathalieMota/assets/images/arrow-right-white.svg" width="24" height="24" alt="">
            </button>
        </nav>
    </div>
</div>