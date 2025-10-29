  <?php
    $es = filterContentByLanguage() ? '_es' : '';
    $options = get_field_options('options' . $es);
    foreach ($options as $key => $value) $$key = $value;
    $phone_number = $contact_phone ?: $main_phone_number;
    ?>

  <?php
    if (!$form_section['hide_section']):
        foreach ($form_section as $field => $content) $$field = $content;
    ?>
      <section class="contact-form-footer">
          <?php if ($background_image) img_print_picture_tag(img: $background_image,  classes: "contact-form-footer__bg") ?>

          <div class="contact-form-footer__wrapper container">
              <div class="contact-form"></div>

              <?php if ($side_picture) img_print_picture_tag(img: $side_picture,  classes: "contact-form-footer__side-pic") ?>


          </div>
      </section>
  <?php
    endif;
    ?>

  <footer class="site-footer">

  </footer>

  <?php wp_footer(); ?>

  </body>

  </html>