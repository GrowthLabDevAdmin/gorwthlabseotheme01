<?php
function theme_customize_register($wp_customize)
{
    // Primary color
    $wp_customize->add_setting('primary_color', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'section' => 'colors',
        'label'   => esc_html__('Primary Color', 'theme'),
    )));

    // Secondary color
    $wp_customize->add_setting('secondary_color', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'section' => 'colors',
        'label'   => esc_html__('Secondary Color', 'theme'),
    )));

    // Tertiary color
    $wp_customize->add_setting('tertiary_color', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'tertiary_color', array(
        'section' => 'colors',
        'label'   => esc_html__('Tertiary Color', 'theme'),
    )));

    // Light grey color
    $wp_customize->add_setting('light_grey', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'light_grey', array(
        'section' => 'colors',
        'label'   => esc_html__('Light Grey Color', 'theme'),
    )));

    // Text Color
    $wp_customize->add_setting('text_color', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'section' => 'colors',
        'label'   => esc_html__('Text Color', 'theme'),
    )));
}

add_action('customize_register', 'theme_customize_register');

function theme_get_customizer_css()
{
    ob_start();

    $colorsHex = array(
        "primary_color" => get_theme_mod('primary_color', ''),
        "secondary_color" => get_theme_mod('secondary_color', ''),
        "tertiary_color" => get_theme_mod('tertiary_color', ''),
        "light_grey" => get_theme_mod('light_grey', ''),
        "text_color" => get_theme_mod('text_color', '')
    );

    $colorsRGB = array();

    foreach ($colorsHex as $key => $color) {

        if (str_contains($color, '#')) {
            $colorsRGB[$key] = hex_converter($color);
        } else {
            $colorsRGB[$key] = $color;
        }
    }

    if (!empty($colorsRGB)):
?>
        :root {
        --primary-color: <?= $colorsRGB['primary_color'] ? $colorsRGB['primary_color'] : '223, 215, 205'  ?>;
        --secondary-color: <?= $colorsRGB['secondary_color'] ? $colorsRGB['secondary_color'] : '171, 162, 153' ?>;
        --tertiary-color: <?= $colorsRGB['tertiary_color'] ? $colorsRGB['tertiary_color'] : '89, 82, 78' ?>;
        --light-grey: <?= $colorsRGB['light_grey'] ? $colorsRGB['light_grey'] : '247, 244, 239' ?>;
        --text-color: <?= $colorsRGB['text_color'] ? $colorsRGB['text_color'] : '89, 82, 78' ?>;
        }
<?php
    endif;

    $css = ob_get_clean();
    return $css;
}

function hex_converter($color)
{
    $trimmed_color_string = ltrim($color, '#');

    list($red, $green, $blue) = array_map('hexdec', str_split($trimmed_color_string, 2));

    $RGB_value = $red . ', ' . $green . ', ' . $blue;

    return $RGB_value;
}

// Modify our styles registration like so:

function theme_enqueue_styles()
{
    $color_scheme = theme_get_customizer_css();
    wp_add_inline_style('growthlabtheme01-main-stylesheet', $color_scheme);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
