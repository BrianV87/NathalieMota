<?php
if (!defined('ABSPATH')) exit; // Sécurité

// ===============================
// Configuration du thème
// ===============================
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

// ===============================
// Chargement des styles et scripts
// ===============================
function nathalie_mota_assets()
{
    // Polices Google
    wp_enqueue_style(
        'nm-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&family=Space+Mono:ital,wght@0,400;1,700&display=swap',
        [],
        null
    );

    // Feuille de style principale
    wp_enqueue_style(
        'nm-main',
        get_template_directory_uri() . '/style.css',
        ['nm-fonts'],
        filemtime(get_template_directory() . '/style.css')
    );

    // JavaScript principal (on le créera juste après)
    wp_enqueue_script(
        'nm-scripts',
        get_template_directory_uri() . '/assets/js/scripts.js',
        [],
        filemtime(get_template_directory() . '/assets/js/scripts.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'nathalie_mota_assets');

// ================================
// 1️⃣ — Enregistrement du CPT "photo"
// ================================
function nm_register_post_type_photo()
{

    $labels = [
        'name'                  => 'Photos',
        'singular_name'         => 'Photo',
        'menu_name'             => 'Photos',
        'name_admin_bar'        => 'Photo',
        'add_new'               => 'Ajouter',
        'add_new_item'          => 'Ajouter une photo',
        'edit_item'             => 'Modifier la photo',
        'new_item'              => 'Nouvelle photo',
        'view_item'             => 'Voir la photo',
        'all_items'             => 'Toutes les photos',
        'search_items'          => 'Rechercher des photos',
        'not_found'             => 'Aucune photo trouvée',
        'not_found_in_trash'    => 'Aucune photo dans la corbeille',
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
        'show_in_menu'       => true,
        'publicly_queryable' => true,
        'hierarchical'       => false,
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
    ];

    register_post_type('photo', $args);
}
add_action('init', 'nm_register_post_type_photo', 0);


// ================================
// 2️⃣ — Taxonomies : Catégories & Formats (version 2025)
// ================================
function nm_register_photo_taxonomies()
{

    /**
     * === Taxonomy : Catégories ===
     * (hiérarchique, comme les catégories classiques)
     */
    register_taxonomy('categorie', ['photo'], [
        'labels' => [
            'name'                       => 'Catégories',
            'singular_name'              => 'Catégorie',
            'menu_name'                  => 'Catégories',
            'all_items'                  => 'Toutes les catégories',
            'edit_item'                  => 'Modifier la catégorie',
            'view_item'                  => 'Voir la catégorie',
            'update_item'                => 'Mettre à jour la catégorie',
            'add_new_item'               => 'Ajouter une nouvelle catégorie',
            'new_item_name'              => 'Nom de la nouvelle catégorie',
            'parent_item'                => 'Catégorie parente',
            'parent_item_colon'          => 'Catégorie parente :',
            'search_items'               => 'Rechercher des catégories',
            'not_found'                  => 'Aucune catégorie trouvée',
        ],
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'show_in_nav_menus'     => true,
        'show_tagcloud'         => false,
        'show_in_quick_edit'    => true,
        'show_in_rest'          => true,                 // ✅ pour Gutenberg
        'rest_base'             => 'categories-photo',   // ✅ nécessaire pour l’UI dans l’éditeur bloc
        'rewrite'               => ['slug' => 'categorie', 'with_front' => true],
    ]);

    /**
     * === Taxonomy : Formats ===
     * (non hiérarchique, comme les étiquettes)
     */
    register_taxonomy('format', ['photo'], [
        'labels' => [
            'name'                       => 'Formats',
            'singular_name'              => 'Format',
            'menu_name'                  => 'Formats',
            'all_items'                  => 'Tous les formats',
            'edit_item'                  => 'Modifier le format',
            'view_item'                  => 'Voir le format',
            'update_item'                => 'Mettre à jour le format',
            'add_new_item'               => 'Ajouter un nouveau format',
            'new_item_name'              => 'Nom du nouveau format',
            'search_items'               => 'Rechercher des formats',
            'not_found'                  => 'Aucun format trouvé',
        ],
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'show_in_nav_menus'     => true,
        'show_tagcloud'         => true,
        'show_in_quick_edit'    => true,
        'show_in_rest'          => true,                // ✅ pour Gutenberg
        'rest_base'             => 'formats-photo',     // ✅ pour affichage correct dans Gutenberg
        'rewrite'               => ['slug' => 'format', 'with_front' => true],
    ]);
}
add_action('init', 'nm_register_photo_taxonomies', 1);



// ================================
// 3️⃣ — Flush des permaliens à l’activation du thème
// ================================
function nm_flush_rewrite_on_switch()
{
    nm_register_post_type_photo();
    nm_register_photo_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'nm_flush_rewrite_on_switch');


// ================================
// 4️⃣ — Métabox "Informations de la photo"
// ================================
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
    // Sécurité
    wp_nonce_field('nm_save_photo_meta', 'nm_photo_meta_nonce');

    // Valeurs existantes
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


// ================================
// 5️⃣ — Sauvegarde sécurisée des métadonnées
// ================================
function nm_save_photo_fields($post_id)
{

    // Vérifications de sécurité
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['nm_photo_meta_nonce']) || !wp_verify_nonce($_POST['nm_photo_meta_nonce'], 'nm_save_photo_meta')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Sauvegarde sécurisée
    $fields = [
        '_reference' => isset($_POST['photo_reference']) ? sanitize_text_field($_POST['photo_reference']) : '',
        '_annee'     => isset($_POST['photo_annee']) ? absint($_POST['photo_annee']) : '',
        '_type'      => isset($_POST['photo_type']) ? sanitize_text_field($_POST['photo_type']) : '',
    ];

    foreach ($fields as $key => $value) {
        if (!empty($value)) {
            update_post_meta($post_id, $key, $value);
        } else {
            delete_post_meta($post_id, $key);
        }
    }
}
add_action('save_post_photo', 'nm_save_photo_fields');

// ===============================
// Optimisations images & Green Code
// ===============================
add_filter('big_image_size_threshold', function() {
    return 2560; // Limite la taille des images originales
});

// Tailles optimisées pour les photos
add_image_size('photo_large', 1600, 0, false);
add_image_size('photo_medium', 1024, 0, false);
add_image_size('photo_small', 600, 0, false);
