<?php
if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if (is_front_page()) {
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    }


    $args = array(
        'post_type' => 'case-result',
        'posts_per_page' => 9,
        'post_status' => 'publish',
        'meta_key' => 'numerical_amount',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'paged' => $paged,
    );

  
    if (!$show_all_results && !empty($select_results_posts) && is_array($select_results_posts)) {
        $args['post__in'] = $select_results_posts;
        $args['orderby'] = 'post__in';
        $args['posts_per_page'] = -1;
        unset($args['paged']);
    }

    $query = new WP_Query($args);

    if ($show_all_results && $query->max_num_pages > 1) {
        get_template_part('template-parts/posts', 'pagination', array(
            'paged' => $paged,
            'query' => $query,
            'classes' => 'case-results__pagination'
        ));
    }

    wp_reset_postdata();
