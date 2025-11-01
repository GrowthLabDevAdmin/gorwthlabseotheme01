  <footer class="site-footer">

    <?php
    $es = filterContentByLanguage() ? '_es' : '';
    $options = get_field_options('options' . $es);
    foreach ($options as $key => $value) $$key = $value;
    $phone_number = $contact_phone ?: $main_phone_number;
    ?>

    <?php
    if (!$form_section['hide_section']):
      foreach ($form_section as $form_field => $form_content) $$form_field = $form_content;
    ?>
      <section class="contact-form-footer">

        <?php if ($background_image) img_print_picture_tag(img: $background_image, is_cover: true, classes: "contact-form-footer__bg bg-image gradient-overlay"); ?>

        <div class="contact-form-footer__wrapper container">

          <div class="contact-form shadow-box">

            <?php
            print_title($contact_form_title_tag, $contact_form_title, "contact-form__title tx-center");
            get_template_part('template-parts/ampersand', 'separator', $args = array('classes' => 'contact-form__separator'));
            ?>

            <div class="contact-form__description formatted-text tx-center">
              <?php echo wp_kses_post(wpautop($contact_form_description)); ?>
            </div>

            <div class="contact-form__form">
              <?php gravity_form($contact_form, display_title: false, display_description: false); ?>
            </div>

            <div class="contact-form__message formatted-text tx-center">
              <?= $message_before_submit ?>
            </div>

          </div>

          <?php if ($side_picture) img_print_picture_tag(img: $side_picture,  classes: "contact-form-footer__side-pic shadow-box"); ?>

        </div>
      </section>
    <?php
    endif;
    ?>

    <?php
    if (!$locations_section['hide_section']):
      foreach ($locations_section as $form_field => $form_content) $$form_field = $form_content;
    ?>

      <section class="locations-footer bg-bicolor">
        <div class="locations-footer__wrapper container border-box">

          <div class="locations-footer__content tx-center">
            <?php
            print_title($locations_title_tag, $locations_title, "locations-footer__title");
            get_template_part('template-parts/ampersand', 'separator', $args = array('classes' => 'locations-footer__separator'));
            echo $locations_main_content;
            ?>
          </div>

          <div class="locations-cards">

            <div class="location-card location-card--first">
              <div class="location-card__inner">
                <?php if ($first_card['logo']) img_print_picture_tag(img: $first_card['logo'], max_size: "thumbnail",  classes: "location-card__logo"); ?>
                <div class="location-card__content tx-center">
                  <?= $first_card['content'] ?>
                </div>
              </div>
            </div>

            <?php
            $locations = $options['offices'];
            if (!empty($locations)):
            ?>
              <div class="locations-cards__carousel">
                <div class="splide">
                  <div class="splide__track">
                    <div class="splide__list">
                      <?php
                      foreach ($locations as $location):
                      ?>
                        <div class="location-card splide__slide">

                          <div class="location-card__map">
                            <?= $location['google_maps_embed_code'] ?>
                          </div>

                          <div class="location-card__inner tx-center">

                            <?php
                            $city = $location['target_page_url'] ? "<a href='$target_page_url' target='_blanck'>" : '';
                            $city .= $location['city'];
                            $city .= $location['target_page_url'] ? "</a>" : '';
                            ?>

                            <?= print_title($location['city_tag'], $city, "location-card__city"); ?>

                            <p class="location-card__address"><?= $location['address'] ?></p>

                            <a href="tel:+1<?= get_flat_number($location['phone']) ?>" class="location-card__btn btn btn--secondary">
                              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_4735_5808)">
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M1.88498 0.511147C2.05996 0.336436 2.27006 0.200905 2.50138 0.113537C2.7327 0.0261686 2.97994 -0.0110424 3.22672 0.00436978C3.47351 0.019782 3.7142 0.0874655 3.93285 0.202935C4.15149 0.318404 4.34311 0.479023 4.49498 0.674147L6.28998 2.98015C6.61898 3.40315 6.73498 3.95415 6.60498 4.47415L6.05798 6.66415C6.0297 6.77758 6.03123 6.89639 6.06242 7.00906C6.09361 7.12172 6.1534 7.22441 6.23598 7.30715L8.69298 9.76415C8.77582 9.8469 8.87868 9.90679 8.99153 9.93798C9.10439 9.96918 9.22341 9.97061 9.33698 9.94215L11.526 9.39515C11.7826 9.33099 12.0504 9.326 12.3093 9.38057C12.5681 9.43514 12.8111 9.54784 13.02 9.71015L15.326 11.5041C16.155 12.1491 16.231 13.3741 15.489 14.1151L14.455 15.1491C13.715 15.8891 12.609 16.2141 11.578 15.8511C8.93917 14.9227 6.54325 13.412 4.56798 11.4311C2.58727 9.45616 1.07659 7.06061 0.147983 4.42215C-0.214017 3.39215 0.110983 2.28515 0.850983 1.54515L1.88498 0.511147Z" fill="#BC9061" />
                                </g>
                                <defs>
                                  <clipPath id="clip0_4735_5808">
                                    <rect width="16" height="16" fill="white" />
                                  </clipPath>
                                </defs>
                              </svg>
                              <span>
                                <?= $location['phone'] ?>
                              </span>
                            </a>

                            <a href="<?= $location['google_map_url'] ?>" class="location-card__cta">
                              <?= filterContentByLanguage() ? "Obetner Direcciones" : "Get Directions" ?>
                            </a>

                          </div>
                        </div>
                      <?php
                      endforeach;
                      ?>
                    </div>
                  </div>

                  <div class="splide__arrows locations-cards__arrows">
                    <button class="splide__arrow splide__arrow--prev arrow arrow--prev btn btn--secondary">
                      <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2823 0.220341C10.3522 0.290009 10.4076 0.372773 10.4454 0.46389C10.4832 0.555008 10.5027 0.65269 10.5027 0.751341C10.5027 0.849992 10.4832 0.947674 10.4454 1.03879C10.4076 1.12991 10.3522 1.21267 10.2823 1.28234L1.81184 9.75134L10.2823 18.2203C10.4232 18.3612 10.5023 18.5522 10.5023 18.7513C10.5023 18.9505 10.4232 19.1415 10.2823 19.2823C10.1415 19.4232 9.95051 19.5023 9.75134 19.5023C9.55218 19.5023 9.36117 19.4232 9.22034 19.2823L0.22034 10.2823C0.150495 10.2127 0.0950809 10.1299 0.0572712 10.0388C0.0194616 9.94767 0 9.84999 0 9.75134C0 9.65269 0.0194616 9.55501 0.0572712 9.46389C0.0950809 9.37277 0.150495 9.29001 0.22034 9.22034L9.22034 0.220341C9.29001 0.150496 9.37277 0.0950816 9.46389 0.057272C9.55501 0.0194623 9.65269 0 9.75134 0C9.84999 0 9.94767 0.0194623 10.0388 0.057272C10.1299 0.0950816 10.2127 0.150496 10.2823 0.220341Z" fill="#BC9061" />
                      </svg>
                      <span class="arrow__placeholder">Prev</span>
                    </button>
                    <a href="<?= $locations_page_link['url'] ?>" class="cta-btn btn btn--secondary">
                      <span><?= $locations_page_link['title'] ?></span>
                    </a>
                    <button class="splide__arrow splide__arrow--next arrow arrow--next btn btn--secondary">
                      <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.220588 0.220341C0.150743 0.290009 0.0953293 0.372773 0.057519 0.46389C0.0197096 0.555008 0.000247002 0.65269 0.000247002 0.751341C0.000247002 0.849992 0.0197096 0.947674 0.057519 1.03879C0.0953293 1.12991 0.150743 1.21267 0.220588 1.28234L8.69109 9.75134L0.220588 18.2203C0.0797577 18.3612 0.000640869 18.5522 0.000640869 18.7513C0.000640869 18.9505 0.0797577 19.1415 0.220588 19.2823C0.361418 19.4232 0.552424 19.5023 0.751588 19.5023C0.950751 19.5023 1.14176 19.4232 1.28259 19.2823L10.2826 10.2823C10.3524 10.2127 10.4078 10.1299 10.4457 10.0388C10.4835 9.94767 10.5029 9.84999 10.5029 9.75134C10.5029 9.65269 10.4835 9.55501 10.4457 9.46389C10.4078 9.37277 10.3524 9.29001 10.2826 9.22034L1.28259 0.220341C1.21292 0.150496 1.13016 0.0950816 1.03904 0.057272C0.94792 0.0194623 0.850239 0 0.751588 0C0.652937 0 0.555256 0.0194623 0.464138 0.057272C0.37302 0.0950816 0.290257 0.150496 0.220588 0.220341Z" fill="#BC9061" />
                      </svg>
                      <span class="arrow__placeholder">Next</span>
                    </button>
                  </div>
                </div>
              </div>
            <?php
            endif;
            ?>

          </div>

        </div>
      </section>

    <?php
    endif;
    ?>

    <?php
    if (!$copyright_section['hide_section']):
      foreach ($copyright_section as $form_field => $form_content) $$form_field = $form_content;
    ?>
      <section class="copyright-footer">
        <div class="copyright-footer__wrapper container">

          <?php
          wp_nav_menu(
            array(
              'menu'  => $footer_links_menu,
              'container'          => 'nav',
              'container_class' => 'footer-nav',
              'menu_class'      => 'footer-nav__menu tx-center',
              'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
              'link_before'          => '<span>',
              'link_after'              => '</span>'
            )
          );
          get_template_part('template-parts/ampersand', 'separator', $args = array('classes' => 'copyright-footer__separator'));
          ?>

          <a href="https://growthlabseo.com/" target="_blank" class="copyright-footer__logo">
            <img src="<?= get_stylesheet_directory_uri() . "/assets/img/Growth-Lab-Logo.png" ?>" alt="Growth Lab SEO Logo">
          </a>

          <p class="copyright-footer__advertisement">
            <?= $copyright ?>
          </p>

        </div>
      </section>
    <?php
    endif;
    ?>

  </footer>

  <?php wp_footer(); ?>

  </body>

  </html>