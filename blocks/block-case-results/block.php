<?php

/**
 * Case Results Block Template
 * Displays case results either as a carousel (filtered) or grid (all results with pagination)
 */

if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;

    $prev_arrow = '
            <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2823 0.220341C10.3522 0.290009 10.4076 0.372773 10.4454 0.46389C10.4832 0.555008 10.5027 0.65269 10.5027 0.751341C10.5027 0.849992 10.4832 0.947674 10.4454 1.03879C10.4076 1.12991 10.3522 1.21267 10.2823 1.28234L1.81184 9.75134L10.2823 18.2203C10.4232 18.3612 10.5023 18.5522 10.5023 18.7513C10.5023 18.9505 10.4232 19.1415 10.2823 19.2823C10.1415 19.4232 9.95051 19.5023 9.75134 19.5023C9.55218 19.5023 9.36117 19.4232 9.22034 19.2823L0.22034 10.2823C0.150495 10.2127 0.0950809 10.1299 0.0572712 10.0388C0.0194616 9.94767 0 9.84999 0 9.75134C0 9.65269 0.0194616 9.55501 0.0572712 9.46389C0.0950809 9.37277 0.150495 9.29001 0.22034 9.22034L9.22034 0.220341C9.29001 0.150496 9.37277 0.0950816 9.46389 0.057272C9.55501 0.0194623 9.65269 0 9.75134 0C9.84999 0 9.94767 0.0194623 10.0388 0.057272C10.1299 0.0950816 10.2127 0.150496 10.2823 0.220341Z" fill="#BC9061" />
            </svg>
            <span class="arrow__placeholder">Prev</span>
        ';

    $next_arrow = '
            <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.220588 0.220341C0.150743 0.290009 0.0953293 0.372773 0.057519 0.46389C0.0197096 0.555008 0.000247002 0.65269 0.000247002 0.751341C0.000247002 0.849992 0.0197096 0.947674 0.057519 1.03879C0.0953293 1.12991 0.150743 1.21267 0.220588 1.28234L8.69109 9.75134L0.220588 18.2203C0.0797577 18.3612 0.000640869 18.5522 0.000640869 18.7513C0.000640869 18.9505 0.0797577 19.1415 0.220588 19.2823C0.361418 19.4232 0.552424 19.5023 0.751588 19.5023C0.950751 19.5023 1.14176 19.4232 1.28259 19.2823L10.2826 10.2823C10.3524 10.2127 10.4078 10.1299 10.4457 10.0388C10.4835 9.94767 10.5029 9.84999 10.5029 9.75134C10.5029 9.65269 10.4835 9.55501 10.4457 9.46389C10.4078 9.37277 10.3524 9.29001 10.2826 9.22034L1.28259 0.220341C1.21292 0.150496 1.13016 0.0950816 1.03904 0.057272C0.94792 0.0194623 0.850239 0 0.751588 0C0.652937 0 0.555256 0.0194623 0.464138 0.057272C0.37302 0.0950816 0.290257 0.150496 0.220588 0.220341Z" fill="#BC9061" />
            </svg>
            <span class="arrow__placeholder">Next</span>
        ';

    // Get current page number
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if (is_front_page()) {
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    }

    /**
     * Setup WP_Query arguments
     * Base query configuration for case results
     */
    $args = array(
        'post_type' => 'case-result',
        'posts_per_page' => 9,
        'post_status' => 'publish',
        'meta_key' => 'numerical_amount',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'paged' => $paged,
    );

    /**
     * Conditional filtering: Carousel mode with specific posts
     * If NOT showing all results AND specific posts are selected
     */
    if (!$show_all_results && !empty($select_results_posts) && is_array($select_results_posts)) {
        $args['post__in'] = $select_results_posts;
        $args['orderby'] = 'post__in';
        $args['posts_per_page'] = -1;
        unset($args['paged']); // Remove pagination when filtering
    }

    $query = new WP_Query($args);
?>

    <section
        id="<?= $block_id ?? "" ?>"
        class="block case-results <?php if ($background_type === "bg_color" && !$background_color) echo "bg-bicolor"; ?>"
        <?php
        if ($background_type === "bg_color" && isset($background_color)) echo "style='background-color: $background_color'";
        if (isset($extract_block_from_content) && $extract_block_from_content) echo "data-extract='$place'";
        ?>>

        <?php if ($background_type === "bg_image" && $background_image) img_print_picture_tag(img: $background_image, is_cover: true, classes: "case-results__bg bg-image gradient-overlay"); ?>

        <div class="case-results__wrapper container tx-center border-box">

            <?php
            print_title($title, $title_tag, "case-results__title");
            get_template_part('template-parts/ampersand', 'separator', array('classes' => 'case-results__separator'));
            ?>

            <?php if ($text_content): ?>
                <div class="case-results__content formatted-text">
                    <?= $text_content ?>
                </div>
            <?php endif ?>

            <?php if ($query->have_posts()): ?>
                <div class="<?= $show_all_results ? "case-results__grid"  : "case-results__carousel"; ?>">

                    <?php
                    /**
                     * Carousel wrapper (Splide.js)
                     * Only used when showing filtered results
                     */
                    if (!$show_all_results):
                    ?>
                        <div class="splide">
                            <div class="splide__track">
                                <div class="splide__list">
                                <?php endif; ?>

                                <?php
                                while ($query->have_posts()): $query->the_post();
                                    foreach (get_fields(get_the_ID()) as $field => $content) $$field = $content;
                                ?>

                                    <div class="result-card splide__slide">
                                        <div class="result-card__wrapper">
                                            <div class="result-card__inner border-box tx-center">
                                                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M60.1227 29.88L56.1227 25.88C55.844 25.6004 55.5128 25.3785 55.1482 25.227C54.7835 25.0756 54.3926 24.9977 53.9977 24.9977C53.6029 24.9977 53.2119 25.0756 52.8473 25.227C52.4826 25.3785 52.1514 25.6004 51.8727 25.88L51.5802 26.1725L37.8302 12.4225L38.1252 12.13C38.4049 11.8513 38.6268 11.5202 38.7782 11.1555C38.9296 10.7908 39.0076 10.3999 39.0076 10.005C39.0076 9.6102 38.9296 9.21925 38.7782 8.85459C38.6268 8.48994 38.4049 8.15876 38.1252 7.88005L34.1252 3.88005C33.8465 3.60037 33.5153 3.37846 33.1507 3.22704C32.786 3.07562 32.3951 2.99768 32.0002 2.99768C31.6054 2.99768 31.2144 3.07562 30.8498 3.22704C30.4851 3.37846 30.1539 3.60037 29.8752 3.88005L13.8752 19.88C13.5955 20.1588 13.3736 20.4899 13.2222 20.8546C13.0708 21.2192 12.9928 21.6102 12.9928 22.005C12.9928 22.3999 13.0708 22.7908 13.2222 23.1555C13.3736 23.5202 13.5955 23.8513 13.8752 24.13L17.8752 28.13C18.1539 28.4097 18.4851 28.6316 18.8498 28.7831C19.2144 28.9345 19.6054 29.0124 20.0002 29.0124C20.3951 29.0124 20.786 28.9345 21.1507 28.7831C21.5153 28.6316 21.8465 28.4097 22.1252 28.13L22.4177 27.835L25.5852 31L8.53521 48.0525C7.63303 49.0516 7.14908 50.3591 7.18334 51.7048C7.21761 53.0504 7.76747 54.3316 8.71933 55.2834C9.67119 56.2353 10.9523 56.7852 12.298 56.8194C13.6437 56.8537 14.9511 56.3697 15.9502 55.4675L33.0002 38.415L36.1727 41.5875L35.8777 41.88C35.598 42.1588 35.3761 42.4899 35.2247 42.8546C35.0733 43.2192 34.9954 43.6102 34.9954 44.005C34.9954 44.3999 35.0733 44.7908 35.2247 45.1555C35.3761 45.5202 35.598 45.8513 35.8777 46.13L39.8777 50.13C40.1564 50.4097 40.4876 50.6316 40.8523 50.7831C41.2169 50.9345 41.6079 51.0124 42.0027 51.0124C42.3976 51.0124 42.7885 50.9345 43.1532 50.7831C43.5178 50.6316 43.849 50.4097 44.1277 50.13L60.1277 34.13C60.4074 33.8513 60.6293 33.5202 60.7807 33.1555C60.9321 32.7908 61.0101 32.3999 61.0101 32.005C61.0101 31.6102 60.9321 31.2192 60.7807 30.8546C60.6293 30.4899 60.4074 30.1588 60.1277 29.88H60.1227ZM19.2927 26.7075L15.2927 22.7075C15.1997 22.6147 15.126 22.5044 15.0757 22.383C15.0253 22.2616 14.9994 22.1315 14.9994 22C14.9994 21.8686 15.0253 21.7385 15.0757 21.6171C15.126 21.4957 15.1997 21.3854 15.2927 21.2925L31.2927 5.29255C31.3856 5.19957 31.4959 5.12581 31.6173 5.07549C31.7387 5.02516 31.8688 4.99926 32.0002 4.99926C32.1316 4.99926 32.2618 5.02516 32.3832 5.07549C32.5046 5.12581 32.6148 5.19957 32.7077 5.29255L36.7077 9.29255C36.8947 9.48001 36.9998 9.734 36.9998 9.9988C36.9998 10.2636 36.8947 10.5176 36.7077 10.705L20.7077 26.705C20.615 26.7982 20.5048 26.8721 20.3835 26.9227C20.2622 26.9732 20.1322 26.9993 20.0007 26.9996C19.8693 26.9998 19.7391 26.9741 19.6177 26.924C19.4962 26.8739 19.3858 26.8004 19.2927 26.7075ZM14.5427 54.0525C14.2485 54.3829 13.8899 54.6497 13.489 54.8366C13.088 55.0235 12.6532 55.1265 12.211 55.1395C11.7688 55.1524 11.3286 55.075 10.9174 54.9119C10.5062 54.7487 10.1327 54.5034 9.81963 54.1909C9.50657 53.8783 9.26061 53.5051 9.09683 53.0942C8.93304 52.6833 8.85486 52.2433 8.86708 51.8011C8.87931 51.3589 8.98168 50.9238 9.16793 50.5226C9.35417 50.1213 9.62037 49.7623 9.95021 49.4675L27.0002 32.415L31.5852 37L14.5427 54.0525ZM23.8277 26.415L36.4152 13.8275L50.1652 27.5775L37.5777 40.165L23.8277 26.415ZM58.7077 32.7075L42.7077 48.7075C42.6148 48.8005 42.5046 48.8743 42.3832 48.9246C42.2618 48.9749 42.1316 49.0008 42.0002 49.0008C41.8688 49.0008 41.7387 48.9749 41.6173 48.9246C41.4959 48.8743 41.3856 48.8005 41.2927 48.7075L37.2927 44.7075C37.1057 44.5201 37.0007 44.2661 37.0007 44.0013C37.0007 43.7365 37.1057 43.4825 37.2927 43.295L53.2927 27.295C53.3856 27.2021 53.4959 27.1283 53.6173 27.078C53.7387 27.0277 53.8688 27.0018 54.0002 27.0018C54.1316 27.0018 54.2618 27.0277 54.3832 27.078C54.5046 27.1283 54.6148 27.2021 54.7077 27.295L58.7077 31.295C58.8007 31.3879 58.8745 31.4982 58.9248 31.6196C58.9751 31.741 59.001 31.8711 59.001 32.0025C59.001 32.134 58.9751 32.2641 58.9248 32.3855C58.8745 32.5069 58.8007 32.6172 58.7077 32.71V32.7075Z" fill="#BC9061" />
                                                    <path d="M60.333 62V63.5H33.333V62H60.333ZM61.833 60.5V56.5C61.833 55.6716 61.1614 55 60.333 55H33.333C32.5046 55 31.833 55.6716 31.833 56.5V60.5C31.833 61.3284 32.5046 62 33.333 62V63.5L33.1787 63.4961C31.6447 63.4184 30.4146 62.1883 30.3369 60.6543L30.333 60.5V56.5C30.333 54.8431 31.6762 53.5 33.333 53.5H60.333C61.9899 53.5 63.333 54.8431 63.333 56.5V60.5L63.3291 60.6543C63.2514 62.1883 62.0213 63.4184 60.4873 63.4961L60.333 63.5V62C61.1614 62 61.833 61.3284 61.833 60.5Z" fill="#AE853A" />
                                                </svg>

                                                <span class="result-card__amount">$<?= format_number_abbreviated($numerical_amount) ?></span>

                                                <p class="result-card__title"><?= $case_title ?></p>

                                                <p class="result-card__description"><?= $case_description ?></p>
                                            </div>
                                        </div>
                                    </div>

                                <?php endwhile; ?>

                                <?php
                                /**
                                 * Carousel controls (Splide.js)
                                 * Navigation arrows and optional link to locations page
                                 */
                                if (!$show_all_results):
                                ?>
                                </div>
                            </div>

                            <div class="splide__arrows pagination-buttons case-results__arrows">
                                <button class="splide__arrow splide__arrow--prev arrow arrow--prev btn btn--secondary">
                                    <?= $prev_arrow ?>
                                </button>

                                <?php if ($all_results_link['url']): ?>
                                    <a href="<?= $all_results_link['url'] ?>" target="<?= $all_results_link['target'] ?>" class="cta-btn btn btn--secondary">
                                        <span><?= $all_results_link['title'] ?></span>
                                    </a>
                                <?php endif ?>

                                <button class="splide__arrow splide__arrow--next arrow arrow--next btn btn--secondary">
                                    <?= $next_arrow ?>
                                </button>
                            </div>

                        </div>
                    <?php endif ?>

                </div>

                <?php
                /**
                 * Pagination Navigation
                 * Only displays when:
                 * - Showing all results ($show_all_results = true)
                 * - More than one page exists
                 */
                if ($show_all_results && $query->max_num_pages > 1):
                ?>
                    <div class="case-results__pagination">
                        <?php
                        /**
                         * Generate pagination links as an array
                         * Returns array of link HTML for custom markup
                         */
                        $pagination = paginate_links(array(
                            'format'    => '?paged=%#%',
                            'current'   => max(1, $paged),
                            'total'     => $query->max_num_pages,
                            'prev_text' => $prev_arrow,
                            'next_text' => $next_arrow,
                            'type'      => 'array',
                            'add_args'  => array(), // Ensure no extra query args are added
                            'mid_size'  => 2,
                            'end_size'  => 1,
                        ));

                        if (!empty($pagination)):
                        ?>
                            <ul class="pagination pagination-buttons">
                                <?php
                                foreach ($pagination as $page_link):
                                    // Add general pagination link class
                                    $page_link = str_replace('page-numbers', 'page-numbers pagination__link', $page_link);

                                    // Determine li class based on link type
                                    $li_class = 'pagination__item btn btn--secondary';

                                    if (strpos($page_link, 'prev') !== false) {
                                        $li_class .= ' pagination__item--prev arrow arrow--prev';
                                        $page_link = str_replace('prev', 'prev pagination__link--nav', $page_link);
                                    } elseif (strpos($page_link, 'next') !== false) {
                                        $li_class .= ' pagination__item--next arrow arrow--next';
                                        $page_link = str_replace('next', 'next pagination__link--nav', $page_link);
                                    } elseif (strpos($page_link, 'current') !== false) {
                                        $li_class .= ' pagination__item--current is-active';
                                        $page_link = str_replace('current', 'current pagination__link--active', $page_link);
                                    } elseif (strpos($page_link, 'dots') !== false) {
                                        $li_class .= ' pagination__item--dots';
                                        $page_link = str_replace('dots', 'dots pagination__link--dots', $page_link);
                                    } else {
                                        $li_class .= ' pagination__item--number';
                                    }
                                ?>
                                    <li class="<?= esc_attr($li_class) ?>">
                                        <?= $page_link ?>
                                    </li>
                                <?php
                                endforeach;
                                ?>
                            </ul>
                        <?php
                        endif;
                        ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

            <?php
            /**
             * Reset post data to restore global $post variable
             * CRITICAL: Always call after custom WP_Query loops
             */
            wp_reset_postdata();
            ?>

        </div>
    </section>

<?php
endif;
?>