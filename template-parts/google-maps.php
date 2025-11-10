<?php if ($args['iframe_code']):
    // Extract iframe src from the embed code
    preg_match('/src=["\']([^"\']+)["\']/', $args['iframe_code'], $matches);
    $map_src = $matches[1] ?? '';

    if ($map_src): ?>
        <div class="<?= $args["classes"] ?> gmap-lazy"
            data-src="<?= esc_url($map_src) ?>"
            data-city="<?= esc_attr($args['name']) ?>"
            style="width:100%;height:300px;background:linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);position:relative;border-radius:8px;overflow:hidden;">
            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#999;text-align:center;">
                <svg style="width:48px;height:48px;margin-bottom:8px;opacity:0.5;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div style="font-size:14px;"><?= esc_html($args['name']) ?></div>
            </div>
        </div>
    <?php endif; ?>
<?php endif ?>