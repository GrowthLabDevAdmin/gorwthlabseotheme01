<?php

// Helper function to get ACF option fields
function get_field_options($field_name, $format_value = true)
{
    return get_field($field_name, 'option', $format_value);
}

// Language Filter
function filterContentByLanguage($lang = 'es')
{
    if (empty($lang)) return false;

    $current_url = $_SERVER['REQUEST_URI'] ?? '/';
    $lang_escaped = preg_quote($lang, '#');
    $pattern = '#^/' . $lang_escaped . '(/|$)#';

    return preg_match($pattern, $current_url) === 1;
}

//Phone number format remover
function get_flat_number($phone)
{
    if (! $phone) return;
    return preg_replace("/[^0-9]/", '', $phone);
}

//Print title helper
function print_title($tag = 'p', $title, $classes = '')
{
    if (!$title) return;
    echo "<$tag class='$classes'>" . $title . "</$tag>";
}

//debug helper
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

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