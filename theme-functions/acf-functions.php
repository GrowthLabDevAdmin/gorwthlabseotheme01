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
                'slug' => 'growthlabtheme01-blocks',
                'title' => __('growthlabtheme01 Blocks', 'growthlabtheme01-blocks'),
            )
        )
    );
}
add_filter('block_categories', 'growthlabtheme01_blocks_category', 10, 2);

// Register Block Types
add_action('init', 'register_acf_blocks', 5);
function register_acf_blocks()
{
    register_block_type(__DIR__ . '/blocks/firm-intro');
    register_block_type(__DIR__ . '/blocks/trust-logos');
    register_block_type(__DIR__ . '/blocks/settlements');
    register_block_type(__DIR__ . '/blocks/practice-areas');
    register_block_type(__DIR__ . '/blocks/our-team');
    register_block_type(__DIR__ . '/blocks/testimonials');
    register_block_type(__DIR__ . '/blocks/video-testimonials');
    register_block_type(__DIR__ . '/blocks/in-the-media');
    register_block_type(__DIR__ . '/blocks/centered-video');
    register_block_type(__DIR__ . '/blocks/breaking-news');
    register_block_type(__DIR__ . '/blocks/content-video');
}

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
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point($path)
{
    // update path
    $path = get_stylesheet_directory() . '/acf-json';

    // return
    return $path;
}

add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point($paths)
{
    // remove original path (optional)
    unset($paths[0]);

    // append path
    $paths[] = get_stylesheet_directory() . '/acf-json';

    // return
    return $paths;
}

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
