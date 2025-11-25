  <footer id="site-footer" class="site-footer">

    <?php
    $es = filterContentByLanguage() ? '_es' : '';
    $options = get_field_options('options' . $es);
    foreach ($options as $key => $value) $$key = $value;
    $phone_number = $contact_phone ?: $main_phone_number;
    ?>

    <?php
    if (!$form_section['hide_section'] && !get_field('hide_form_section')):
      foreach ($form_section as $form_field => $form_content) $$form_field = $form_content;
    ?>
      <section class="contact-form-footer">

        <div class="contact-form-footer__wrapper">
          <?php if ($background_image) img_print_picture_tag(img: $background_image, is_cover: true, classes: "contact-form-footer__bg bg-image gradient-overlay"); ?>

          <div class="contact-form-footer__inner container">

            <div class="contact-form shadow-box">

              <?php
              print_title($contact_form_title, $contact_form_title_tag, "contact-form__title tx-center");
              get_template_part('template-parts/ampersand', 'separator', array('classes' => 'contact-form__separator'));
              ?>

              <div class="contact-form__description formatted-text tx-center">
                <?php echo wp_kses_post(wpautop($contact_form_description)); ?>
              </div>

              <div class="contact-form__form">
                <?php gravity_form($contact_form, display_title: false, display_description: false); ?>
              </div>

              <div class="contact-form__message formatted-text tx-center flex-center">
                <?= $message_before_submit ?>
              </div>

            </div>

            <?php if ($side_picture) img_print_picture_tag(img: $side_picture,  classes: "contact-form-footer__side-pic shadow-box"); ?>
          </div>

        </div>
      </section>
    <?php
    endif;
    ?>

    <?php
    if (!$locations_section['hide_section'] && !get_field('hide_locations_section')):
      foreach ($locations_section as $form_field => $form_content) $$form_field = $form_content;
    ?>

      <section class="locations-footer bg-bicolor">
        <div class="locations-footer__wrapper container border-box">

          <div class="locations-footer__content tx-center">
            <?php
            print_title($locations_title, $locations_title_tag, "locations-footer__title");
            get_template_part('template-parts/ampersand', 'separator', array('classes' => 'locations-footer__separator'));
            echo $locations_main_content;
            ?>
          </div>

          <div class="locations-cards">

            <div class="location-card location-card--first">
              <div class="location-card__wrapper">
                <div class="location-card__inner flex-center">
                  <?php if ($first_card['logo']) img_print_picture_tag(img: $first_card['logo'], max_size: "thumbnail",  classes: "location-card__logo"); ?>
                  <div class="location-card__content tx-center">
                    <?= $first_card['content'] ?>
                  </div>
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
                          <div class="location-card__wrapper">

                            <?php if ($location['google_maps_embed_code']): ?>
                              <?php
                              $args = array(
                                "iframe_src" => $location['google_maps_embed_code'],
                                "name" => $location['city'],
                                "classes" => "location-card__map"
                              );
                              get_template_part("template-parts/google", "maps", $args);
                              ?>
                            <?php
                            endif
                            ?>

                            <div class="location-card__inner tx-center">

                              <?php
                              $tp_url = $location['target_page_url'];
                              $city = $tp_url ? "<a href='$tp_url' target='_blank'>" : '';
                              $city .= $location['city'];
                              $city .= $tp_url ? "</a>" : '';
                              ?>

                              <?= print_title($city, $location['city_tag'], "location-card__city"); ?>

                              <p class="location-card__address"><?= $location['address'] ?></p>

                              <?php if ($location['phone']): ?>
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
                              <?php endif ?>

                              <?php if ($location['google_map_url']): ?>
                                <a href="<?= $location['google_map_url'] ?>" class="location-card__cta">
                                  <?= filterContentByLanguage() ? "Obetner Direcciones" : "Get Directions" ?>
                                </a>
                              <?php endif ?>

                            </div>
                          </div>
                        </div>
                      <?php
                      endforeach;
                      ?>
                    </div>
                  </div>

                  <?php
                  get_template_part('template-parts/splide', 'navigation', array(
                    'nav_link' => $locations_page_link,
                    'classes' => 'locations-cards__arrows'
                  ));
                  ?>

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
    if (!$copyright_section['hide_section'] && !get_field("hide_copyright_section")):
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
          get_template_part('template-parts/ampersand', 'separator', array('classes' => 'copyright-footer__separator'));
          ?>

          <a href="https://growthlabseo.com/" target="_blank" class="copyright-footer__logo">
            <img src="<?= get_stylesheet_directory_uri() . "/assets/img/Growth-Lab-Logo.png" ?>" alt="Growth Lab SEO Logo" width="270" height="50">
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