<?php

/**
 * Responsive Image Helper Functions
 * Generates <picture> elements with WebP support and multiple breakpoints
 */

$GLOBALS['breakpoints'] = [
    'mobile' => '',
    'tablet' => '810px',
    'ldpi' => '1024px',
    'mdpi' => '1280px',
    'hdpi' => '1480px',
];

$GLOBALS['sizes'] = [
    'featured-small',
    'hero-mobile',
    'hero-tablet',
    'hero-desktop',
    'full',
];

// Cache for image metadata to avoid repeated database queries
$GLOBALS['img_metadata_cache'] = [];

/**
 * Main function to generate responsive picture element
 *
 * @param array|string $img Main image (array from ACF or URL string)
 * @param array|string $mobile_img Mobile-specific image
 * @param array|string $tablet_img Tablet-specific image
 * @param string $size Size to use ('thumbnail', 'medium', 'large', 'full')
 * @param string $classes CSS classes for picture element
 * @param string $id HTML id attribute
 * @param string $alt_text Alternative text (overrides image alt)
 * @param bool $is_cover Whether this is a cover/hero image
 * @param string $img_attr Additional img attributes (use with caution)
 * @param bool $is_priority Whether this is an above-the-fold priority image
 * @return string HTML picture element
 */
function img_print(
    array|string $img,
    array|string $mobile_img = [],
    array|string $tablet_img = [],
    string $size = 'full',
    string $classes = '',
    string $id = '',
    string $alt_text = '',
    bool $is_cover = false,
    string $img_attr = '',
    bool $is_priority = false
): string {
    if (empty($img)) {
        return '';
    }

    // Validate size parameter
    if (!in_array($size, $GLOBALS['sizes'], true)) {
        $size = 'full';
    }

    // Get main image fields
    $img_fields = img_get_fields($img);

    // Handle SVG images separately
    if (in_array($img_fields['type'], ['image/svg+xml', 'image/svg'], true)) {
        return image_to_svg($img);
    }

    // Get WebP version if available
    $img_webp_fields = img_evaluate_webp($img_fields['urls']['full'])
        ? img_get_fields($img_fields['urls']['full'], true)
        : null;

    // Prepare attributes
    $attrs = img_prepare_attributes($id, $classes, $alt_text, $img_fields['alt'], $img_attr, $is_priority);

    // Build picture element based on configuration
    if ($size === 'thumbnail') {
        return img_build_thumbnail($img_fields, $img_webp_fields, $attrs);
    }

    if ($is_cover && empty($tablet_img) && empty($mobile_img)) {
        return img_build_simple_cover($img_fields, $img_webp_fields, $size, $attrs);
    }

    return img_build_responsive_picture(
        $img_fields,
        $img_webp_fields,
        $mobile_img,
        $tablet_img,
        $size,
        $is_cover,
        $attrs
    );
}

/**
 * Prepare and sanitize HTML attributes
 */
function img_prepare_attributes(
    string $id,
    string $classes,
    string $alt_text,
    string $fallback_alt,
    string $img_attr,
    bool $is_priority
): array {
    return [
        'id' => $id ? esc_attr($id) : '',
        'class' => $classes ? esc_attr($classes) : '',
        'alt' => esc_attr($alt_text ?: $fallback_alt),
        'loading' => $is_priority ? 'eager' : 'lazy',
        'fetchpriority' => $is_priority ? 'high' : 'auto',
        'decoding' => 'async',
        'extra' => $img_attr ? ' ' . wp_kses_post($img_attr) : '',
    ];
}

/**
 * Build simple thumbnail picture element
 */
function img_build_thumbnail(array $img_fields, ?array $img_webp_fields, array $attrs): string
{
    $sources = [];

    if ($img_webp_fields) {
        $sources[] = img_create_source_tag(
            $img_webp_fields['urls']['thumbnail'],
            $img_webp_fields['type']
        );
    }

    $img_tag = img_create_img_tag(
        $img_fields['urls']['thumbnail'],
        $img_fields['sizes']['thumbnail']['width'],
        $img_fields['sizes']['thumbnail']['height'],
        $attrs
    );

    return img_wrap_picture($sources, $img_tag, $attrs);
}

/**
 * Build simple cover image (no responsive variants)
 */
function img_build_simple_cover(array $img_fields, ?array $img_webp_fields, string $size, array $attrs): string
{
    $sources = [];

    if ($img_webp_fields) {
        $sources[] = img_create_source_tag(
            $img_webp_fields['urls'][$size],
            $img_webp_fields['type']
        );
    }

    $img_tag = img_create_img_tag(
        $img_fields['urls'][$size],
        $img_fields['sizes'][$size]['width'],
        $img_fields['sizes'][$size]['height'],
        $attrs
    );

    return img_wrap_picture($sources, $img_tag, $attrs);
}

/**
 * Build full responsive picture element with breakpoints
 */
function img_build_responsive_picture(
    array $img_fields,
    ?array $img_webp_fields,
    array|string $mobile_img,
    array|string $tablet_img,
    string $size,
    bool $is_cover,
    array $attrs
): string {
    $sources = [];

    // Desktop sources
    if ($size === 'full' || $is_cover) {
        $media_breakpoint = img_determine_media_breakpoint($mobile_img, $tablet_img);
        $sources = array_merge($sources, img_create_desktop_sources(
            $img_fields,
            $img_webp_fields,
            $size,
            $media_breakpoint
        ));
    }

    // Tablet sources
    if (!empty($tablet_img)) {
        $sources = array_merge($sources, img_create_device_sources(
            $tablet_img,
            'tablet'
        ));
    }

    // Mobile sources (fallback)
    if (!empty($mobile_img)) {
        $mobile_fields = img_get_fields($mobile_img);
        $mobile_webp_fields = img_evaluate_webp($mobile_fields['urls']['full'])
            ? img_get_fields($mobile_fields['urls']['full'], true)
            : null;

        if ($mobile_webp_fields) {
            $sources[] = img_create_source_tag(
                $mobile_webp_fields['urls']['full'],
                $mobile_webp_fields['type']
            );
        }

        $img_tag = img_create_img_tag(
            $mobile_fields['urls']['full'],
            $mobile_fields['sizes']['full']['width'],
            $mobile_fields['sizes']['full']['height'],
            $attrs
        );

        return img_wrap_picture($sources, $img_tag, $attrs);
    }

    // Standard responsive sizes
    if ($size !== 'full' && !$is_cover) {
        $sources = array_merge($sources, img_create_size_based_sources(
            $img_fields,
            $img_webp_fields,
            $size
        ));
    }

    // Fallback img tag
    $fallback_size = ($size === 'full' || $is_cover) ? $size : 'medium';
    $img_tag = img_create_img_tag(
        $img_fields['urls'][$fallback_size],
        $img_fields['sizes'][$fallback_size]['width'],
        $img_fields['sizes'][$fallback_size]['height'],
        $attrs
    );

    return img_wrap_picture($sources, $img_tag, $attrs);
}

/**
 * Determine appropriate media breakpoint based on available images
 */
function img_determine_media_breakpoint(array|string $mobile_img, array|string $tablet_img): string
{
    if (!empty($mobile_img) && !empty($tablet_img)) {
        return 'mdpi';
    } elseif (!empty($mobile_img)) {
        return 'ldpi';
    }
    return 'hdpi';
}

/**
 * Create desktop source tags
 */
function img_create_desktop_sources(
    array $img_fields,
    ?array $img_webp_fields,
    string $size,
    string $breakpoint
): array {
    $sources = [];
    $media = "(min-width: {$GLOBALS['breakpoints'][$breakpoint]})";

    if ($img_webp_fields) {
        $sources[] = img_create_source_tag(
            $img_webp_fields['urls'][$size],
            $img_webp_fields['type'],
            $media
        );
    }

    $sources[] = img_create_source_tag(
        $img_fields['urls'][$size],
        $img_fields['type'],
        $media
    );

    return $sources;
}

/**
 * Create device-specific source tags (tablet/mobile)
 */
function img_create_device_sources(array|string $device_img, string $device_type): array
{
    $sources = [];
    $device_fields = img_get_fields($device_img);
    $device_webp_fields = img_evaluate_webp($device_fields['urls']['full'])
        ? img_get_fields($device_fields['urls']['full'], true)
        : null;

    $media = "(min-width: {$GLOBALS['breakpoints'][$device_type]})";

    if ($device_webp_fields) {
        $sources[] = img_create_source_tag(
            $device_webp_fields['urls']['full'],
            $device_webp_fields['type'],
            $media
        );
    }

    $sources[] = img_create_source_tag(
        $device_fields['urls']['full'],
        $device_fields['type'],
        $media
    );

    return $sources;
}

/**
 * Create size-based source tags for responsive images
 */
function img_create_size_based_sources(
    array $img_fields,
    ?array $img_webp_fields,
    string $target_size
): array {
    $sources = [];
    $available_sizes = array_values(array_diff($GLOBALS['sizes'], ['thumbnail', 'full']));
    $size_index = array_search($target_size, $available_sizes, true);

    if ($size_index === false) {
        return $sources;
    }

    $breakpoint_values = array_values($GLOBALS['breakpoints']);

    for ($i = $size_index; $i >= 0; $i--) {
        $current_size = $available_sizes[$i];
        $media = ($i !== 0) ? "(min-width: {$breakpoint_values[$i]})" : null;

        if ($img_webp_fields) {
            $sources[] = img_create_source_tag(
                $img_webp_fields['urls'][$current_size],
                $img_webp_fields['type'],
                $media
            );
        }

        $sources[] = img_create_source_tag(
            $img_fields['urls'][$current_size],
            $img_fields['type'],
            $media
        );
    }

    return $sources;
}

/**
 * Create a source tag
 */
function img_create_source_tag(string $srcset, string $type, ?string $media = null): string
{
    $srcset_attr = "srcset='" . esc_url($srcset) . "'";
    $type_attr = "type='" . esc_attr($type) . "'";
    $media_attr = $media ? " media='" . esc_attr($media) . "'" : '';

    return "<source {$srcset_attr} {$type_attr}{$media_attr}>";
}

/**
 * Create an img tag
 */
function img_create_img_tag(string $src, int $width, int $height, array $attrs): string
{
    $src_attr = "src='" . esc_url($src) . "'";
    $width_attr = "width='" . (int)$width . "'";
    $height_attr = "height='" . (int)$height . "'";
    $alt_attr = "alt='{$attrs['alt']}'";
    $loading_attr = "loading='{$attrs['loading']}'";
    $fetchpriority_attr = $attrs['fetchpriority'] !== 'auto' ? " fetchpriority='{$attrs['fetchpriority']}'" : '';
    $decoding_attr = "decoding='{$attrs['decoding']}'";

    return "<img {$src_attr} {$width_attr} {$height_attr} {$alt_attr} {$loading_attr}{$fetchpriority_attr} {$decoding_attr}{$attrs['extra']}>";
}

/**
 * Wrap sources and img tag in picture element
 */
function img_wrap_picture(array $sources, string $img_tag, array $attrs): string
{
    $id_attr = $attrs['id'] ? "id='{$attrs['id']}'" : '';
    $class_attr = $attrs['class'] ? "class='{$attrs['class']}'" : '';
    $picture_attrs = trim("{$id_attr} {$class_attr}");

    $picture = $picture_attrs ? "<picture {$picture_attrs}>" : "<picture>";
    $picture .= implode('', $sources);
    $picture .= $img_tag;
    $picture .= "</picture>";

    return $picture;
}

/**
 * Get image fields from various sources with caching
 */
function img_get_fields(array|string $img, bool $is_webp = false): array
{
    // Generate cache key
    $cache_key = is_array($img) ? md5(serialize($img)) : md5($img . $is_webp);

    if (isset($GLOBALS['img_metadata_cache'][$cache_key])) {
        return $GLOBALS['img_metadata_cache'][$cache_key];
    }

    if (is_array($img) && isset($img['sizes'])) {
        $result = img_parse_acf_image($img);
    } else {
        $result = img_parse_url_image($img, $is_webp);
    }

    $GLOBALS['img_metadata_cache'][$cache_key] = $result;
    return $result;
}

/**
 * Parse ACF image array format
 */
function img_parse_acf_image(array $img): array
{
    $sizes_urls = [];
    $sizes_dimensions = [];

    foreach ($GLOBALS['sizes'] as $size) {
        if ($size === 'full') {
            $sizes_urls[$size] = $img['url'];
            $sizes_dimensions[$size] = [
                'width' => (int)$img['width'],
                'height' => (int)$img['height'],
            ];
        } else {
            $sizes_urls[$size] = $img['sizes'][$size] ?? $img['url'];
            $sizes_dimensions[$size] = [
                'width' => (int)($img['sizes']["{$size}-width"] ?? $img['width']),
                'height' => (int)($img['sizes']["{$size}-height"] ?? $img['height']),
            ];
        }
    }

    return [
        'sizes' => $sizes_dimensions,
        'urls' => $sizes_urls,
        'alt' => $img['alt'] ?? '',
        'title' => $img['title'] ?? '',
        'type' => $img['mime_type'] ?? 'image/jpeg',
    ];
}

/**
 * Parse WordPress image from URL
 */
function img_parse_url_image(string $img_url, bool $is_webp): array
{
    $img_id = attachment_url_to_postid($img_url);

    if (!$img_id) {
        return img_get_empty_fields();
    }

    $img_meta = wp_get_attachment_metadata($img_id);

    if (!$img_meta) {
        return img_get_empty_fields();
    }

    $img_type = $is_webp ? 'image/webp' : get_post_mime_type($img_id);
    $img_extension = $is_webp ? '.webp' : '';
    $sizes_urls = [];
    $sizes_dimensions = [];

    foreach ($GLOBALS['sizes'] as $size) {
        $sizes_urls[$size] = wp_get_attachment_image_url($img_id, $size) . $img_extension;

        if ($size === 'full') {
            $sizes_dimensions[$size] = [
                'width' => (int)$img_meta['width'],
                'height' => (int)$img_meta['height'],
            ];
        } else {
            $sizes_dimensions[$size] = [
                'width' => (int)($img_meta['sizes'][$size]['width'] ?? $img_meta['width']),
                'height' => (int)($img_meta['sizes'][$size]['height'] ?? $img_meta['height']),
            ];
        }
    }

    return [
        'sizes' => $sizes_dimensions,
        'urls' => $sizes_urls,
        'alt' => get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: '',
        'title' => get_the_title($img_id) ?: '',
        'type' => $img_type,
    ];
}

/**
 * Return empty fields structure
 */
function img_get_empty_fields(): array
{
    return [
        'sizes' => [],
        'urls' => [],
        'alt' => '',
        'title' => '',
        'type' => 'image/jpeg',
    ];
}

/**
 * Check if WebP version exists
 */
function img_evaluate_webp(string $img_url): bool
{
    static $webp_cache = [];

    if (isset($webp_cache[$img_url])) {
        return $webp_cache[$img_url];
    }

    $file_path = str_replace(home_url(), ABSPATH, $img_url) . '.webp';
    $exists = file_exists($file_path);

    $webp_cache[$img_url] = $exists;
    return $exists;
}
