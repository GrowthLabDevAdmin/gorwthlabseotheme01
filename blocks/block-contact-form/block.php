<?php
if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;
?>

    <section
        id="<?= $block_id ?? "" ?>"
        class="block contact-form 
        <?php
        if ($side_picture) {
            echo "contact-form--side-pic ";
            if ($title || $main_content && !$background_image) {
                echo "bg-bicolor";
            }
        } else if (!$background_image){
            echo "bg-bicolor";
        }
        ?>"
        <?php if (isset($extract_block_from_content) && $extract_block_from_content) echo "data-extract='$place'"; ?>>

        <div class="contact-form__layer">

            <div class="contact-form__wrapper
            <?php
            if ($side_picture && !$title && !$main_content && !$background_image) echo "bg-bicolor ";
            ?>
            ">
                <?php if ($background_image) img_print_picture_tag(img: $background_image, is_cover: true, classes: "contact-form__bg bg-image gradient-overlay"); ?>

                <div class="contact-form__inner container">

                    <?php
                    if (isset($title) && $title) {
                        print_title($title, $title_tag, "contact-form__title");
                        get_template_part('template-parts/ampersand', 'separator', array('classes' => 'contact-form__separator'));
                    }

                    if ($main_content):
                    ?>
                        <div class="contact-form__content formatted-text tx-center">
                            <?= $main_content ?>
                        </div>
                    <?php
                    endif
                    ?>


                    <div class="form-box shadow-box">

                        <?php
                        print_title($contact_form_title, $contact_form_title_tag, "form-box__title tx-center");
                        get_template_part('template-parts/ampersand', 'separator', array('classes' => 'form-box__separator'));
                        ?>

                        <div class="form-box__description formatted-text tx-center">
                            <?php echo wp_kses_post(wpautop($contact_form_description)); ?>
                        </div>

                        <div class="form-box__form">
                            <?php gravity_form($contact_form, display_title: false, display_description: false); ?>
                        </div>

                        <div class="form-box__message formatted-text tx-center flex-center">
                            <?= $message_before_submit ?>
                        </div>

                    </div>

                    <?php if ($side_picture) img_print_picture_tag(img: $side_picture,  classes: "contact-form__side-pic shadow-box"); ?>
                </div>

            </div>

        </div>

    </section>

<?php
endif;
?>