<?php

/**
 * Template Part: Modale de contact
 */
?>

<div id="contact-modal" class="contact-overlay">
    <div class="contact-modal">
        <button class="modal-close" aria-label="Fermer">&times;</button>

        <!-- Image en-tête -->
        <div class="modal-header-image">
            <img src="http://nathaliemota.local/wp-content/uploads/2025/10/Contact-header.png" alt="Contact Nathalie Mota">
        </div>

        <div class="modal-body">
            <?php echo do_shortcode('[contact-form-7 id="e236954" title="Formulaire de contact"]'); ?>
        </div>
    </div>
</div>