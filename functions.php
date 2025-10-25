<?php

/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage growthlabtheme01
 * 
 */

/**
 * Disable the emoji's
 */

if (!is_admin()) add_action('init', 'disable_emojis');

function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param    array  $plugins  
 * @return   array  Difference betwen the two arrays
 */
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}
// Disable Dashicons on front-end
function wpdocs_dequeue_dashicon()
{
    if (current_user_can('update_core')) {
        return;
    }
    wp_deregister_style('guiones');
}
add_action('wp_enqueue_scripts', 'wpdocs_dequeue_dashicon');


if (!function_exists('growthlabtheme01_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     *
     *
     * @return void
     */

    function growthlabtheme01_setup()
    {
        /*
		* Let WordPress manage the document title.
		* This theme does not use a hard-coded <title> tag in the document head,
		* WordPress will provide it for us.
		*/
        add_theme_support('title-tag');

        /*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
        add_theme_support('post-thumbnails', array('post', 'page'));

        //Add custom sized images
        add_image_size('hero-desktop', 1920, 1080, true);
        add_image_size('hero-tablet', 1280, 720, true);
        add_image_size('hero-mobile', 768, 432, true);
        add_image_size('featured-large', 1200, 800, true);
        add_image_size('featured-medium', 800, 533, true);
        add_image_size('featured-small', 400, 267, true);
        add_image_size('thumb-grid', 300, 200, true);

        // Tipography and Color Support
        add_theme_support('appearance-tools');

        // Font Sizes support
        add_theme_support('editor-font-sizes', array(
            array(
                'name' => esc_attr__(
                    'Small',
                    'growthlabtheme01'
                ),
                'size' => 12,
                'slug' => 'small'
            ),
            array(
                'name' => esc_attr__(
                    'Regular',
                    'growthlabtheme01'
                ),
                'size' => 16,
                'slug' => 'regular'
            ),
            array(
                'name' => esc_attr__(
                    'Medium',
                    'growthlabtheme01'
                ),
                'size' => 18,
                'slug' => 'medium'
            ),
            array(
                'name' => esc_attr__(
                    'Large',
                    'growthlabtheme01'
                ),
                'size' => 22,
                'slug' => 'large'
            ),
            array(
                'name' => esc_attr__(
                    'Extra Large',
                    'growthlabtheme01'
                ),
                'size' => 28,
                'slug' => 'xl'
            ),
            array(
                'name' => esc_attr__(
                    'Huge',
                    'growthlabtheme01'
                ),
                'size' => 32,
                'slug' => 'xl'
            )
        ));


        // Color Palette support
        add_theme_support(
            'editor-color-palette',
            array(
                array(
                    'name'  => __(
                        'Primary Color',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'primary-color',
                    'color' => get_theme_mod('primary_color', '#15253f'),
                ),
                array(
                    'name'  => __(
                        'Primary Color Dark',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'primary-color-dark',
                    'color' => get_theme_mod('primary_color_dark', '#08182f'),
                ),
                array(
                    'name'  => __(
                        'Primary Color Light',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'primary-color-light',
                    'color' => get_theme_mod('primary_color_light', '#2C3D5B'),
                ),
                array(
                    'name'  => __(
                        'Secondary Color',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'secondary-color',
                    'color' => get_theme_mod('secondary_color', '#F4F3EE'), // swapped -> tertiary default
                ),
                array(
                    'name'  => __(
                        'Secondary Color Dark',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'secondary-color-dark',
                    'color' => get_theme_mod('secondary_color_dark', '#E7E5DF'), // swapped -> tertiary dark
                ),
                array(
                    'name'  => __(
                        'Secondary Color Light',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'secondary-color-light',
                    'color' => get_theme_mod('secondary_color_light', '#FFFFFF'), // swapped -> tertiary light
                ),
                array(
                    'name'  => __(
                        'Tertiary Color',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'tertiary-color',
                    'color' => get_theme_mod('tertiary_color', '#BC9061'), // swapped -> secondary default
                ),
                array(
                    'name'  => __(
                        'Tertiary Color Dark',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'tertiary-color-dark',
                    'color' => get_theme_mod('tertiary_color_dark', '#9D7A55'), // swapped -> secondary dark
                ),
                array(
                    'name'  => __(
                        'Tertiary Color Light',
                        'growthlabtheme01'
                    ),
                    'slug'  => 'tertiary-color-light',
                    'color' => get_theme_mod('tertiary_color_light', '#DCAB77'), // swapped -> secondary light
                ),
                array(
                    'name'  => __(
                        'Text Color',
                        'growthlabtheme01 '
                    ),
                    'slug'  => 'text-color',
                    'color' => get_theme_mod('text_color', '#15253f'),
                ),
            )
        );


        // Register Navigation Menus
        register_nav_menus(
            array(
                'primary' => esc_html__('Main Menu', 'growthlabtheme01')
            )
        );
    }
}
add_action('after_setup_theme', 'growthlabtheme01_setup');

/**
 * Enqueue scripts and styles.
 *
 *
 * @return void
 */
function growthlabtheme01_scripts()
{

    // Third party stylesheet
    /* wp_register_style('slick-min', get_template_directory_uri() . '/assets/scss/slick-min.css', array(), '1.0', 'all');
    wp_register_style('slick-theme-min', get_template_directory_uri() . '/assets/scss/slick-theme-min.css', array(), '1.0', 'all'); */

    // Template Stylesheets
    /* wp_register_style('growthlabtheme01-template-default', get_template_directory_uri() . '/assets/scss/page-templates/template-default.css', array(), '1.0'); */

    // Global stylesheet.
    wp_enqueue_style('growthlabtheme01-main-stylesheet', get_template_directory_uri() . "/styles/main.css", array(
        /*  'slick-min',
        'slick-theme-min' */), '1.0');

    // Third party JS scripts.
    /* wp_register_script('slick-min', get_template_directory_uri() . '/assets/js/slick-min.js', array('jquery'), '1.0', ['in_footer' => true]); */

    // Main JS scripts.
    /*  wp_enqueue_script('growthlabtheme01-main-scripts', get_template_directory_uri() . '/assets/js/main-min.js', array('jquery', 'slick-min'), '1.0.6', true); */

    // Load specific template stylesheet
    /* if (is_page()) {
        if (is_page_template('page-templates/template-homepage.php')) {
            wp_enqueue_style('growthlabtheme01-template-homepage', get_template_directory_uri() . '/assets/scss/page-templates/template-homepage.css', array(), '1.0.9');
        } else {
            wp_enqueue_style('growthlabtheme01-template-default');
        }

        switch (get_page_template_slug()) {
            case 'page-templates/template-about.php':
                wp_enqueue_style('growthlabtheme01-template-about', get_template_directory_uri() . '/assets/scss/page-templates/template-about.css', array(), '1.0.1');
                break;
        }
    }
    if (is_home() || is_archive() || is_single()) {
        wp_enqueue_style('growthlabtheme01-template-default');
        wp_enqueue_style('growthlabtheme01-blog', get_template_directory_uri() . '/assets/scss/page-templates/template-blog.css', array(), '1.0.0');
    }
    if (is_404()) {
        wp_enqueue_style('growthlabtheme01-template-default');
    } */
}

add_action('wp_enqueue_scripts', 'growthlabtheme01_scripts');


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @return void
 */
function growthlabtheme01_widgets_init()
{

    register_sidebar(
        array(
            'name'          => esc_html__('Default Sidebar', 'growthlabtheme01'),
            'id'            => 'sidebar-default',
            'description'   => esc_html__('Add widgets here to appear in the page sidebar.', 'growthlabtheme01'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Blog Sidebar', 'growthlabtheme01'),
            'id'            => 'sidebar-blog',
            'description'   => esc_html__('Add widgets here to appear in the Blog sidebar.', 'growthlabtheme01'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );
}

add_action('widgets_init', 'growthlabtheme01_widgets_init');


// YouTube Video ID Extractor
function get_yt_code($url = false)
{
    // Here is a sample of the URLs this regex matches: (there can be more content after the given URL that will be ignored)

    // http://youtu.be/dQw4w9WgXcQ
    // http://www.youtube.com/embed/dQw4w9WgXcQ
    // http://www.youtube.com/watch?v=dQw4w9WgXcQ
    // http://www.youtube.com/?v=dQw4w9WgXcQ
    // http://www.youtube.com/v/dQw4w9WgXcQ
    // http://www.youtube.com/e/dQw4w9WgXcQ
    // http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ
    // http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ
    // http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ
    // http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ

    // It also works on the youtube-nocookie.com URL with the same above options.
    // It will also pull the ID from the URL in an embed code (both iframe and object tags)
    if (! $url) return false;
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    return $match[1];
}

// Gravity Forms 
add_filter('gform_disable_css', '__return_true');
add_filter('gform_init_scripts_footer', '__return_true');


include locate_template('theme-functions/acf-functions.php');
include locate_template('theme-functions/color-scheme.php');
include locate_template('theme-functions/svg-support.php');
include locate_template('theme-functions/picture-optimization.php');
