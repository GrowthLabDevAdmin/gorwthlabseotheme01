<?php
/* ACF Functions
-------------------------------------------------------------- */

// Custom Block Categories
function growthlabtheme01_blocks_category($categories, $post)
{
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'growthlabtheme01-blocks',
                'title' => __('Growthlab Theme 01 Blocks', 'growthlabtheme01-blocks'),
            )
        )
    );
}
add_filter('block_categories_all', 'growthlabtheme01_blocks_category', 10, 2);

// Register Block Types
function register_acf_blocks()
{
    $blocks = glob(get_stylesheet_directory() . '/blocks/*/block.json');

    foreach ($blocks as $block) {
        register_block_type(dirname($block));
    }
}
add_action('init', 'register_acf_blocks', 5);

// Move Block Scripts to the Footer
add_action('wp_enqueue_scripts', function () {
    global $wp_scripts;

    if (empty($wp_scripts->registered)) {
        return;
    }

    foreach ($wp_scripts->registered as $handle => $script) {
        if (
            !empty($script->src)
            && str_contains($script->src, '/blocks/')
            && empty($script->extra['group'])
        ) {
            $wp_scripts->registered[$handle]->extra['group'] = 1; // 1 = footer
        }
    }
}, 999);

// Add ACF Options Page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Site Options',
        'menu_title' => 'Site Options',
        'menu_slug' => 'site_options',
        'position' => 70,
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}


// Customize ACF JSON Save and Load Points
function my_acf_json_save_point($path)
{
    // update path
    $path = get_stylesheet_directory() . '/acf-json';

    // return
    return $path;
}

function my_acf_json_load_point($paths)
{
    // remove original path (optional)
    unset($paths[0]);

    // append path
    $paths[] = get_stylesheet_directory() . '/acf-json';

    // return
    return $paths;
}

add_action('init', function () {
    add_filter('acf/settings/save_json', 'my_acf_json_save_point');
    add_filter('acf/settings/load_json', 'my_acf_json_load_point');
});

add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        $path = get_stylesheet_directory() . '/acf-json';
        error_log('=== ACF JSON DEBUG ===');
        error_log('Path: ' . $path);
        error_log('Exists: ' . (file_exists($path) ? 'YES' : 'NO'));
        error_log('Writable: ' . (is_writable($path) ? 'YES' : 'NO'));
        error_log('Current theme: ' . get_stylesheet());
    }
});

// Allow HTML in ACF fields
add_filter('acf/shortcode/allow_unsafe_html', function () {
    return true;
}, 10, 2);
add_filter('acf/the_field/allow_unsafe_html', function () {
    return true;
}, 10, 2);
add_filter('acf/the_sub_field/allow_unsafe_html', function () {
    return true;
}, 10, 2);

if (is_admin()) {
    add_filter('acf/admin/prevent_escaped_html_notice', '__return_true');
}

/**
 * ACF Color Picker Custom Palette
 * Adds custom color palette from Customizer to all ACF color picker fields
 */

/**
 * Get theme colors from Customizer
 * @return array Array of colors with hex codes and names
 */
function get_theme_color_palette_for_acf()
{
    return array(
        array(
            'name'  => 'Primary Color',
            'color' => get_theme_mod('primary_color', '#15253f'),
        ),
        array(
            'name'  => 'Primary Dark',
            'color' => get_theme_mod('primary_color_dark', '#08182f'),
        ),
        array(
            'name'  => 'Primary Light',
            'color' => get_theme_mod('primary_color_light', '#2C3D5B'),
        ),
        array(
            'name'  => 'Secondary Color',
            'color' => get_theme_mod('secondary_color', '#F4F3EE'),
        ),
        array(
            'name'  => 'Secondary Dark',
            'color' => get_theme_mod('secondary_color_dark', '#E7E5DF'),
        ),
        array(
            'name'  => 'Secondary Light',
            'color' => get_theme_mod('secondary_color_light', '#FFFFFF'),
        ),
        array(
            'name'  => 'Tertiary Color',
            'color' => get_theme_mod('tertiary_color', '#BC9061'),
        ),
        array(
            'name'  => 'Tertiary Dark',
            'color' => get_theme_mod('tertiary_color_dark', '#9D7A55'),
        ),
        array(
            'name'  => 'Tertiary Light',
            'color' => get_theme_mod('tertiary_color_light', '#DCAB77'),
        ),
        array(
            'name'  => 'Text Color',
            'color' => get_theme_mod('text_color', '#15253f'),
        ),
    );
}


/**
 * Inject color palette into ACF color picker via JavaScript
 */
function acf_color_picker_palette_script()
{
    $colors = get_theme_color_palette_for_acf();
    $palette = array();

    foreach ($colors as $color) {
        $palette[] = $color['color'];
    }

    $palette_json = json_encode($palette);
?>
    <script type="text/javascript">
        (function($) {
            if (typeof acf !== 'undefined') {
                acf.addAction('ready', function() {
                    // Override default ACF color picker settings
                    acf.add_filter('color_picker_args', function(args, $field) {
                        args.palettes = <?php echo $palette_json; ?>;
                        return args;
                    });
                });
            }
        })(jQuery);
    </script>
    <style>
        /* Style for ACF color picker palette */
        .acf-color-picker .wp-picker-container .iris-palette {
            width: 100% !important;
            max-width: 100% !important;
        }
    </style>
<?php
}
add_action('acf/input/admin_head', 'acf_color_picker_palette_script');
add_action('acf/input/admin_footer', 'acf_color_picker_palette_script'); // Backup for late-loaded fields
