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
