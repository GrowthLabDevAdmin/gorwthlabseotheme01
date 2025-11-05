<?php
// 1️⃣ Load editor CSS
function my_acf_editor_styles($mce_css)
{
    $editor_style = get_template_directory_uri() . '/styles/vendor/tiny-mce/tiny-mce-styles-min.css';
    $editor_style .= '?ver=' . time();

    if (!empty($mce_css)) {
        $mce_css .= ',' . $editor_style;
    } else {
        $mce_css = $editor_style;
    }
    return $mce_css;
}
add_filter('mce_css', 'my_acf_editor_styles');


// 2️⃣ TinyMCE configuration - For standard WordPress
function my_acf_wysiwyg_custom_settings($init)
{
    // Custom fonts
    $init['font_formats'] = 'Open Sans=Open Sans,sans-serif;Fraunces=Fraunces,serif;Arial=Arial,Helvetica,sans-serif;Times New Roman=Times New Roman,Times,serif';

    // Font sizes
    $init['fontsize_formats'] = '8px 10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 40px 48px';

    // DO NOT configure textcolor_map here for standard WordPress
    // We'll do it only in ACF with JavaScript

    return $init;
}
add_filter('tiny_mce_before_init', 'my_acf_wysiwyg_custom_settings', 1);


// 3️⃣ Apply to ACF WYSIWYG - Only fonts and sizes
function my_acf_tinymce_settings($init, $id)
{
    $init['font_formats'] = 'Open Sans=Open Sans,sans-serif;Fraunces=Fraunces,serif;Arial=Arial,Helvetica,sans-serif;Times New Roman=Times New Roman,Times,serif';
    $init['fontsize_formats'] = '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 40pt 48pt';

    // DO NOT configure textcolor_map here

    return $init;
}
add_filter('acf_wysiwyg_tinymce_settings', 'my_acf_tinymce_settings', 10, 2);


// 4️⃣ Custom toolbar
function my_acf_override_full_toolbar($toolbars)
{
    $toolbars['Full'][1] = array(
        'formatselect',
        'fontselect',
        'fontsizeselect',
        'bold',
        'italic',
        'underline',
        'forecolor',
        'backcolor',
        'bullist',
        'numlist',
        'alignleft',
        'aligncenter',
        'alignright',
        'link',
        'unlink',
        'removeformat',
        'undo',
        'redo'
    );
    return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars', 'my_acf_override_full_toolbar');


// 5️⃣ Inject colors dynamically with JavaScript
function my_acf_tinymce_colors_script()
{
    // Get colors dynamically from PHP
    $colors = array(
        get_theme_mod('primary_color', '#15253f') => 'Primary Color',
        get_theme_mod('primary_color_dark', '#08182f') => 'Primary Dark',
        get_theme_mod('primary_color_light', '#2C3D5B') => 'Primary Light',
        get_theme_mod('secondary_color', '#F4F3EE') => 'Secondary Color',
        get_theme_mod('secondary_color_dark', '#E7E5DF') => 'Secondary Dark',
        get_theme_mod('secondary_color_light', '#FFFFFF') => 'Secondary Light',
        get_theme_mod('tertiary_color', '#BC9061') => 'Tertiary Color',
        get_theme_mod('tertiary_color_dark', '#9D7A55') => 'Tertiary Dark',
        get_theme_mod('tertiary_color_light', '#DCAB77') => 'Tertiary Light',
        get_theme_mod('text_color', '#15253f') => 'Text Color',
    );

    // Build JavaScript array
    $color_array = array();
    foreach ($colors as $hex => $name) {
        $color_array[] = str_replace('#', '', $hex);
        $color_array[] = $name;
    }

    // Convert to valid JSON
    $colors_json = json_encode($color_array);
?>
    <script type="text/javascript">
        (function($) {
            // Custom colors from PHP
            var customColors = <?php echo $colors_json; ?>;

            //console.log('🎨 Colors loaded from PHP:', customColors);

            // Hook BEFORE ACF initializes TinyMCE
            acf.addFilter('wysiwyg_tinymce_settings', function(mceInit, id, field) {
                //console.log('🔧 Modifying configuration for:', id);

                // Inject custom colors
                mceInit.textcolor_map = customColors;
                mceInit.textcolor_cols = 5;

                //console.log('✅ Colors injected:', mceInit.textcolor_map);

                return mceInit;
            });

        })(jQuery);
    </script>
<?php
}
add_action('acf/input/admin_head', 'my_acf_tinymce_colors_script');
