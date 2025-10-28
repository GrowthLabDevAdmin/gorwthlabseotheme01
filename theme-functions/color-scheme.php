<?php
// Definir colores por defecto
$default_colors = [
    'primary' => [
        'default' => '#15253f',    // rgb(21, 37, 63)
        'dark'    => '#08182f',    // rgb(8, 24, 47)
        'light'   => '#2C3D5B'     // rgb(44, 61, 91)
    ],
    'secondary' => [
        'default' => '#F4F3EE',    // rgb(244, 243, 238)
        'dark'    => '#E7E5DF',    // rgb(231, 229, 223)
        'light'   => '#FFFFFF'     // rgb(255, 255, 255)
    ],
    'tertiary' => [
        'default' => '#BC9061',    // rgb(188, 144, 97)
        'dark'    => '#9D7A55',    // rgb(157, 122, 85)
        'light'   => '#DCAB77'     // rgb(220, 171, 119)
    ],
    'text' => '#15253f'           // rgb(21, 37, 63)
];

// Registrar settings en el Customizer
function theme_customize_register($wp_customize) {
    global $default_colors;
    
    foreach ($default_colors as $color_name => $variants) {
        if ($color_name === 'text') {
            // Caso especial para text que no tiene variantes
            register_color_setting($wp_customize, 'text_color', $variants);
            continue;
        }
        
        // Registrar cada variante del color
        foreach (['default' => '', 'dark' => '_dark', 'light' => '_light'] as $variant => $suffix) {
            $setting_name = "{$color_name}_color{$suffix}";
            $default = $variants[$variant] ?? '';
            register_color_setting($wp_customize, $setting_name, $default);
        }
    }
}

// Helper para registrar un setting de color
function register_color_setting($wp_customize, $name, $default) {
    $wp_customize->add_setting($name, [
        'default'           => $default,
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color'
    ]);

    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize, $name, [
            'section' => 'colors',
            'label'   => esc_html__(ucwords(str_replace('_', ' ', $name)), 'theme')
        ])
    );
}

// Generar CSS optimizado
function theme_get_customizer_css() {
    global $default_colors;
    
    $css_vars = [];
    foreach ($default_colors as $color_name => $variants) {
        if ($color_name === 'text') {
            $hex = get_theme_mod('text_color', $variants);
            $css_vars["--text"] = hex_to_rgb($hex);
            continue;
        }
        
        foreach (['default' => '', 'dark' => '-dark', 'light' => '-light'] as $variant => $suffix) {
            $setting_name = "{$color_name}_color" . ($variant === 'default' ? '' : '_' . $variant);
            $hex = get_theme_mod($setting_name, $variants[$variant]);
            $css_vars["--{$color_name}{$suffix}"] = hex_to_rgb($hex);
        }
    }
    
    // Generar CSS
    $css = ":root {\n";
    foreach ($css_vars as $var => $value) {
        $css .= "    {$var}: {$value};\n";
    }
    $css .= "}";
    
    return $css;
}

// Convertir hex a RGB (optimizado)
function hex_to_rgb($hex) {
    $hex = ltrim($hex, '#');
    $rgb = array_map('hexdec', str_split($hex, 2));
    return implode(', ', $rgb);
}

// Enqueue inline styles with customizer CSS
function theme_enqueue_styles()
{
    $color_scheme = theme_get_customizer_css();
    wp_add_inline_style('growthlabtheme01-main-stylesheet', $color_scheme);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
add_action('customize_register', 'theme_customize_register');