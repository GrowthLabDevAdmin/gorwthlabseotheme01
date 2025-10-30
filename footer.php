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
          print_title($contact_form_title_tag, $contact_form_title, "contact-form__title txt-center");
          get_template_part('template-parts/ampersand', 'separator', $args = array('classes' => 'contact-form__separator'));
          ?>

          <div class="contact-form__description formatted-text txt-center">
            <?php echo wp_kses_post(wpautop($contact_form_description)); ?>
          </div>

          <div class="contact-form__form">
            <?php gravity_form($contact_form, display_title: false, display_description: false); ?>
          </div>

          <div class="contact-form__message formatted-text txt-center">
            <?= $message_before_submit ?>
          </div>

        </div>

        <?php if ($side_picture) img_print_picture_tag(img: $side_picture,  classes: "contact-form-footer__side-pic"); ?>

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