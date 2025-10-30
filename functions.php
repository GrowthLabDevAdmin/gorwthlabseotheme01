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

//Disable the emoji's
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

if (!is_admin()) {
    add_action('init', 'disable_emojis');
    add_action('wp_enqueue_scripts', 'wpdocs_dequeue_dashicon');
}

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

        // Custom Logo Support
        $defaults = array(
            'height'               => 200,
            'width'                => 360,
            'flex-height'          => true,
            'flex-width'           => true,
            'unlink-homepage-logo' => true,
        );

        add_theme_support('custom-logo', $defaults);

        //Add custom sized images
        add_image_size('cover-desktop', 1920, 1080, true);
        add_image_size('cover-tablet', 1280, 720, true);
        add_image_size('cover-mobile', 800, 533, true);
        add_image_size('featured-small', 400, 267, true);

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
                'main' => esc_html__('Main Menu', 'growthlabtheme01')
            )
        );
        register_nav_menus(
            array(
                'main_es' => esc_html__('Main Menu Spanish', 'growthlabtheme01')
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
    wp_enqueue_style('growthlabtheme01-main-stylesheet', get_template_directory_uri() . "/styles/main-min.css", array(
        /*  'slick-min',
        'slick-theme-min' */), '1.0');

    // Third party JS scripts.
    /* wp_register_script('slick-min', get_template_directory_uri() . '/assets/js/slick-min.js', array('jquery'), '1.0', ['in_footer' => true]); */

    // Main JS scripts.
    wp_enqueue_script('growthlabtheme01-main-scripts', get_template_directory_uri() . '/js/main-min.js', array(), '1.0', true);

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


// Gravity Forms 
add_filter('gform_disable_css', '__return_true');
add_filter('gform_disable_theme_editor_styles', '__return_true');
add_filter('gform_init_scripts_footer', '__return_true');
add_filter('gform_submit_button', function ($button, $form) {

    $id = $class = $onclick = $value = '';

    if (preg_match('/id=["\']([^"\']+)["\']/', $button, $m)) $id = $m[1];
    if (preg_match('/class=["\']([^"\']+)["\']/', $button, $m)) $class = $m[1];
    if (preg_match('/onclick=["\']([^"\']+)["\']/', $button, $m)) $onclick = $m[1];
    if (preg_match('/value=["\']([^"\']+)["\']/', $button, $m)) $value = $m[1];

    // Retornar nuevo botón con el valor dinámico y el SVG
    return sprintf(
        '<button type="submit" id="%s" class="%s" %s>
            <span>%s</span>
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.25 9.99981C1.25 9.83405 1.31585 9.67508 1.43306 9.55787C1.55027 9.44066 1.70924 9.37481 1.875 9.37481H16.6163L12.6825 5.44231C12.5651 5.32495 12.4992 5.16578 12.4992 4.99981C12.4992 4.83384 12.5651 4.67467 12.6825 4.55731C12.7999 4.43995 12.959 4.37402 13.125 4.37402C13.291 4.37402 13.4501 4.43995 13.5675 4.55731L18.5675 9.55731C18.6257 9.61537 18.6719 9.68434 18.7034 9.76027C18.7349 9.8362 18.7511 9.9176 18.7511 9.99981C18.7511 10.082 18.7349 10.1634 18.7034 10.2394C18.6719 10.3153 18.6257 10.3843 18.5675 10.4423L13.5675 15.4423C13.4501 15.5597 13.291 15.6256 13.125 15.6256C12.959 15.6256 12.7999 15.5597 12.6825 15.4423C12.5651 15.325 12.4992 15.1658 12.4992 14.9998C12.4992 14.8338 12.5651 14.6747 12.6825 14.5573L16.6163 10.6248H1.875C1.70924 10.6248 1.55027 10.559 1.43306 10.4418C1.31585 10.3245 1.25 10.1656 1.25 9.99981Z" fill="#F4F3EE"/>
            </svg>
        </button>',
        esc_attr($id),
        esc_attr($class),
        esc_attr($onclick),
        esc_html($value)
    );
}, 10, 2);


// Include Theme Functions
include locate_template('theme-functions/acf-functions.php');
include locate_template('theme-functions/helpers.php');
include locate_template('theme-functions/color-scheme.php');
include locate_template('theme-functions/svg-support.php');
include locate_template('theme-functions/picture-optimization.php');
