<?php
// ========================================
// Sécurité — empêche l'accès direct
// ========================================
if (! defined('ABSPATH')) exit;

// ========================================
// Configuration du thème (supports + menus)
// ========================================
function nathalie_mota_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('custom-logo', [
        'height'      => 48,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'main'   => __('Menu principal', 'nathalie-mota'),
        'footer' => __('Menu bas de page', 'nathalie-mota'),
    ]);
}
add_action('after_setup_theme', 'nathalie_mota_setup');

// ========================================
// Chargement des assets (CSS / JS)
// ========================================
function nathalie_mota_assets()
{
    // ---------- Polices ----------
    wp_enqueue_style(
        'nm-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap',
        [],
        null
    );

    // ---------- CSS ----------
    wp_enqueue_style(
        'nm-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['nm-fonts'],
        filemtime(get_template_directory() . '/assets/css/main.css')
    );

    // ---------- JS : modale contact ----------
    wp_enqueue_script(
        'nm-modal',
        get_template_directory_uri() . '/assets/js/modal.js',
        [],
        filemtime(get_template_directory() . '/assets/js/modal.js'),
        true
    );

    // ---------- JS : lightbox ----------
    wp_enqueue_script(
        'nm-lightbox',
        get_template_directory_uri() . '/assets/js/lightbox.js',
        [],
        filemtime(get_template_directory() . '/assets/js/lightbox.js'),
        true
    );

    // ---------- JS : burger menu ----------
    wp_enqueue_script(
        'nm-burger',
        get_template_directory_uri() . '/assets/js/burger.js',
        [],
        filemtime(get_template_directory() . '/assets/js/burger.js'),
        true
    );

    // ---------- JS : infinite scroll ----------
    wp_enqueue_script(
        'nm-infinite',
        get_template_directory_uri() . '/assets/js/infinite-scroll.js',
        ['jquery'],
        filemtime(get_template_directory() . '/assets/js/infinite-scroll.js'),
        true
    );

    // ---------- JS : second thumb ----------
    wp_enqueue_script(
        'nm-second-thumb',
        get_template_directory_uri() . '/assets/js/second-thumb.js',
        ['jquery'],
        filemtime(get_template_directory() . '/assets/js/second-thumb.js'),
        true
    );

    // Passage de l'URL AJAX au JS
    wp_localize_script('nm-infinite', 'nm_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'nathalie_mota_assets');

// ========================================
// CPT : Photo
// ========================================
function nm_register_post_type_photo()
{
    $labels = [
        'name'               => 'Photos',
        'singular_name'      => 'Photo',
        'menu_name'          => 'Photos',
        'add_new'            => 'Ajouter',
        'add_new_item'       => 'Ajouter une photo',
        'edit_item'          => 'Modifier la photo',
        'new_item'           => 'Nouvelle photo',
        'view_item'          => 'Voir la photo',
        'all_items'          => 'Toutes les photos',
        'search_items'       => 'Rechercher des photos',
        'not_found'          => 'Aucune photo trouvée',
        'not_found_in_trash' => 'Aucune photo dans la corbeille',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-format-image',
        'menu_position'      => 5,
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'photos', 'with_front' => true],
        'show_in_rest'       => true,
    ];

    register_post_type('photo', $args);
}
add_action('init', 'nm_register_post_type_photo', 0);

// ========================================
// Taxonomies : Catégorie & Format
// ========================================
function nm_register_photo_taxonomies()
{
    register_taxonomy('categorie', ['photo'], [
        'labels'       => ['name' => 'Catégories', 'singular_name' => 'Catégorie'],
        'hierarchical' => true,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'categorie'],
    ]);

    register_taxonomy('format', ['photo'], [
        'labels'       => ['name' => 'Formats', 'singular_name' => 'Format'],
        'hierarchical' => true,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'format'],
    ]);
}
add_action('init', 'nm_register_photo_taxonomies', 1);

// ========================================
// Flush des permaliens à l'activation thème
// ========================================
function nm_flush_rewrite_on_switch()
{
    nm_register_post_type_photo();
    nm_register_photo_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'nm_flush_rewrite_on_switch');

// ========================================
// Metabox "Informations de la photo"
// ========================================
function nm_add_photo_metaboxes()
{
    add_meta_box(
        'photo_infos',
        'Informations de la photo',
        'nm_render_photo_metabox',
        'photo',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nm_add_photo_metaboxes');

function nm_render_photo_metabox($post)
{
    wp_nonce_field('nm_save_photo_meta', 'nm_photo_meta_nonce');
    $reference = get_post_meta($post->ID, '_reference', true);
    $annee     = get_post_meta($post->ID, '_annee', true);
    $type      = get_post_meta($post->ID, '_type', true);
?>
    <div style="display:grid;gap:12px;max-width:420px;">
        <label><strong>Référence :</strong><br>
            <input type="text" name="photo_reference" value="<?php echo esc_attr($reference); ?>" style="width:100%">
        </label>

        <label><strong>Année :</strong><br>
            <input type="number" name="photo_annee" value="<?php echo esc_attr($annee); ?>" min="1900" max="<?php echo date('Y'); ?>" style="width:100%">
        </label>

        <label><strong>Type :</strong><br>
            <select name="photo_type" style="width:100%">
                <option value="">— Sélectionner —</option>
                <option value="Argentique" <?php selected($type, 'Argentique'); ?>>Argentique</option>
                <option value="Numérique" <?php selected($type, 'Numérique'); ?>>Numérique</option>
            </select>
        </label>
    </div>
<?php
}

// ========================================
// Sauvegarde sécurisée des métadonnées
// ========================================
function nm_save_photo_fields($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['nm_photo_meta_nonce']) || !wp_verify_nonce($_POST['nm_photo_meta_nonce'], 'nm_save_photo_meta')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        '_reference' => isset($_POST['photo_reference']) ? sanitize_text_field($_POST['photo_reference']) : '',
        '_annee'     => isset($_POST['photo_annee']) ? absint($_POST['photo_annee']) : '',
        '_type'      => isset($_POST['photo_type']) ? sanitize_text_field($_POST['photo_type']) : '',
    ];

    foreach ($fields as $key => $value) {
        if (!empty($value)) update_post_meta($post_id, $key, $value);
        else delete_post_meta($post_id, $key);
    }
}
add_action('save_post_photo', 'nm_save_photo_fields');

// ========================================
// Optimisation des images
// ========================================
add_filter('big_image_size_threshold', function () {
    return 2560;
});

add_image_size('photo_large', 1600, 0, false);
add_image_size('photo_medium', 1024, 0, false);
add_image_size('photo_small', 600, 0, false);

// ========================================
// AJAX : Load more photos
// ========================================
add_action('wp_ajax_load_more_photos', 'nm_load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'nm_load_more_photos');
function nm_load_more_photos()
{
    $ppp    = isset($_GET['ppp'])    ? max(1, (int)$_GET['ppp'])    : 8;
    $offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;
    $tax_query = [];

    if (!empty($_GET['categorie'])) {
        $tax_query[] = [
            'taxonomy' => 'categorie',
            'field'    => 'slug',
            'terms'    => sanitize_title($_GET['categorie'])
        ];
    }
    if (!empty($_GET['format'])) {
        $tax_query[] = [
            'taxonomy' => 'format',
            'field'    => 'slug',
            'terms'    => sanitize_title($_GET['format'])
        ];
    }
    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }

    $ordre = isset($_GET['ordre']) ? sanitize_text_field($_GET['ordre']) : 'recentes';
    $order = ($ordre === 'anciennes') ? 'ASC' : 'DESC';

    $args = [
        'post_type'           => 'photo',
        'posts_per_page'      => $ppp,
        'offset'              => $offset,
        'orderby'             => 'date',
        'order'               => $order,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
        'post_status'         => 'publish',
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
    ];

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    $q = new WP_Query($args);
    $output = '';

    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();
            $photo = get_post();
            ob_start();
            include locate_template('template-parts/photo-block.php');
            $output .= ob_get_clean();
        }
        wp_reset_postdata();
    }

    echo $output;
    wp_die();
}

// ========================================
// Réécriture des URL propres (filters front)
// ========================================
add_action('init', 'nm_custom_rewrite_rules');
function nm_custom_rewrite_rules()
{
    add_rewrite_rule('^categorie/([^/]+)/?$', 'index.php?post_type=photo&categorie=$matches[1]', 'top');
    add_rewrite_rule('^format/([^/]+)/?$', 'index.php?post_type=photo&format=$matches[1]', 'top');
    add_rewrite_rule('^categorie/([^/]+)/format/([^/]+)/?$', 'index.php?post_type=photo&categorie=$matches[1]&format=$matches[2]', 'top');
    add_rewrite_rule('^categorie/([^/]+)/ordre/([^/]+)/?$', 'index.php?post_type=photo&categorie=$matches[1]&ordre=$matches[2]', 'top');
    add_rewrite_rule('^format/([^/]+)/ordre/([^/]+)/?$', 'index.php?post_type=photo&format=$matches[1]&ordre=$matches[2]', 'top');
    add_rewrite_rule('^categorie/([^/]+)/format/([^/]+)/ordre/([^/]+)/?$', 'index.php?post_type=photo&categorie=$matches[1]&format=$matches[2]&ordre=$matches[3]', 'top');
}

// ========================================
// ♻️ GREEN CODE — Optimisations écologiques et performance
// ========================================

// Support HTML5 (balises plus légères et sémantiques)
add_action('after_setup_theme', function () {
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'comment-form', 'comment-list']);
});

// Supprime les tailles d’images inutiles (évite de générer trop de fichiers)
add_filter('intermediate_image_sizes_advanced', function ($sizes) {
    unset($sizes['medium_large'], $sizes['1536x1536'], $sizes['2048x2048']);
    return $sizes;
});

// Ajoute "defer" à tous les scripts personnalisés du thème (pour améliorer le temps de chargement)
add_filter('script_loader_tag', function ($tag, $handle) {
    if (strpos($handle, 'nm-') === 0) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}, 10, 2);
