<?php
// Register Customizer settings for color scheme
function theme_customize_register($wp_customize)
{
    // Primary color
    $wp_customize->add_setting('primary_color', array(
        'default'   => '#15253f',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'section' => 'colors',
        'label'   => esc_html__('Primary Color', 'theme'),
    )));

    $wp_customize->add_setting('primary_color_dark', array(
        'default'   => '#08182f',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color_dark', array(
        'section' => 'colors',
        'label'   => esc_html__('Primary Color Dark', 'theme'),
    )));

    $wp_customize->add_setting('primary_color_light', array(
        'default'   => '#2C3D5B',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color_light', array(
        'section' => 'colors',
        'label'   => esc_html__('Primary Color Light', 'theme'),
    )));

    // Secondary color (ahora usa por defecto los valores previos de tertiary)
    $wp_customize->add_setting('secondary_color', array(
        'default'   => '#F4F3EE',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'section' => 'colors',
        'label'   => esc_html__('Secondary Color', 'theme'),
    )));

    $wp_customize->add_setting('secondary_color_dark', array(
        'default'   => '#E7E5DF',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color_dark', array(
        'section' => 'colors',
        'label'   => esc_html__('Secondary Color Dark', 'theme'),
    )));

    $wp_customize->add_setting('secondary_color_light', array(
        'default'   => '#FFFFFF',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color_light', array(
        'section' => 'colors',
        'label'   => esc_html__('Secondary Color Light', 'theme'),
    )));

    // Tertiary color (ahora usa por defecto los valores previos de secondary)
    $wp_customize->add_setting('tertiary_color', array(
        'default'   => '#BC9061',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tertiary_color', array(
        'section' => 'colors',
        'label'   => esc_html__('Tertiary Color', 'theme'),
    )));

    $wp_customize->add_setting('tertiary_color_dark', array(
        'default'   => '#9D7A55',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tertiary_color_dark', array(
        'section' => 'colors',
        'label'   => esc_html__('Tertiary Color Dark', 'theme'),
    )));

    $wp_customize->add_setting('tertiary_color_light', array(
        'default'   => '#DCAB77',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tertiary_color_light', array(
        'section' => 'colors',
        'label'   => esc_html__('Tertiary Color Light', 'theme'),
    )));

    // Text Color
    $wp_customize->add_setting('text_color', array(
        'default'   => '#15253f',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'section' => 'colors',
        'label'   => esc_html__('Text Color', 'theme'),
    )));
}

add_action('customize_register', 'theme_customize_register');

// Generate CSS based on Customizer settings
function theme_get_customizer_css()
{
    ob_start();

    $colorsHex = array(
        "primary_color" => get_theme_mod('primary_color', ''),
        "primary_color" => get_theme_mod('primary_color_dark', ''),
        "primary_color" => get_theme_mod('primary_color_light', ''),
        "secondary_color" => get_theme_mod('secondary_color', ''),
        "secondary_color" => get_theme_mod('secondary_color_dark', ''),
        "secondary_color" => get_theme_mod('secondary_color_light', ''),
        "tertiary_color" => get_theme_mod('tertiary_color', ''),
        "tertiary_color" => get_theme_mod('tertiary_color_dark', ''),
        "tertiary_color" => get_theme_mod('tertiary_color_light', ''),
        "text_color" => get_theme_mod('text_color', '')
    );

    $colorsRGB = array();

    // Convert hex colors to RGB
    foreach ($colorsHex as $key => $color) {

        if (str_contains($color, '#')) {
            $colorsRGB[$key] = hex_converter($color);
        } else {
            $colorsRGB[$key] = $color;
        }
    }

    // Output CSS variables
    if (!empty($colorsRGB)):
?>
        :root {
        --primary: <?= $colorsRGB['primary_color'] ? $colorsRGB['primary_color'] : '21, 37, 63'  ?>;
        --primary-dark: <?= $colorsRGB['primary_color_dark'] ? $colorsRGB['primary_color_dark'] : '8, 24, 47'  ?>;
        --primary-light: <?= $colorsRGB['primary_color_light'] ? $colorsRGB['primary_color_light'] : '44, 61, 91'  ?>;
        --secondary: <?= $colorsRGB['secondary_color'] ? $colorsRGB['secondary_color'] : '244, 243, 238' ?>;
        --secondary-dark: <?= $colorsRGB['secondary_color_dark'] ? $colorsRGB['secondary_color_dark'] : '231, 229, 223' ?>;
        --secondary-light: <?= $colorsRGB['secondary_color_light'] ? $colorsRGB['secondary_color_light'] : '255, 255, 255' ?>;
        --tertiary: <?= $colorsRGB['tertiary_color'] ? $colorsRGB['tertiary_color'] : '188, 144, 97' ?>;
        --tertiary-dark: <?= $colorsRGB['tertiary_color_dark'] ? $colorsRGB['tertiary_color_dark'] : '157, 122, 85' ?>;
        --tertiary-light: <?= $colorsRGB['tertiary_color_light'] ? $colorsRGB['tertiary_color_light'] : '220, 171, 119' ?>;
        --text: <?= $colorsRGB['text_color'] ? $colorsRGB['text_color'] : '21, 37, 63' ?>;
        }
<?php
    endif;

    $css = ob_get_clean();
    return $css;
}

// Convert hex color to RGB
function hex_converter($color)
{
    $trimmed_color_string = ltrim($color, '#');

    list($red, $green, $blue) = array_map('hexdec', str_split($trimmed_color_string, 2));

    $RGB_value = $red . ', ' . $green . ', ' . $blue;

    return $RGB_value;
}

// Enqueue inline styles with customizer CSS
function theme_enqueue_styles()
{
    $color_scheme = theme_get_customizer_css();
    wp_add_inline_style('growthlabtheme01-main-stylesheet', $color_scheme);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
