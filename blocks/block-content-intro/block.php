<?php
if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;
?>

    <section
        id="<?= $block_id ?? "" ?>"
        class="block content-intro <?php if (!$background_image) echo "bg-bicolor"; ?>"
        <?php if (isset($extract_block_from_content) && $extract_block_from_content) echo "data-extract='$place'"; ?>>

        <?php
        if (isset($background_image) && $background_image) img_print_picture_tag(img: $background_image, is_cover: true, classes: "content-intro__bg bg-image gradient-overlay");
        ?>

        <div class="content-intro__wrapper container">
            <div class="content-intro__inner">

                <?php if ($title || $first_paragraph): ?>
                    <div class="content-intro__heading border-box tx-center">
                        <?php
                        print_title($title, $title_tag, "content-intro__title");
                        get_template_part('template-parts/ampersand', 'separator', array('classes' => 'content-intro__separator'));
                        echo $first_paragraph;
                        ?>
                    </div>
                <?php endif ?>


                <div class="content-intro__content formatted-text">

                    <div class="content-intro__pic-wrapper">
                        <?php
                        if (isset($side_picture) && $side_picture) img_print_picture_tag(img: $side_picture, max_size: "medium", classes: "content-intro__pic shadow-box");
                        if (isset($side_picture_caption) && $side_picture_caption):
                        ?>

                            <div class="content-intro__caption">
                                <?php include get_stylesheet_directory() . '/assets/img/ampersand-symbol.svg'; ?>
                                <p><?= $side_picture_caption ?></p>
                            </div>

                        <?php endif ?>
                    </div>

                    <?= $text_content ?>

                    <?php if ($cta_link): ?>
                        <div class="content-intro__btn">
                            <a href="<?= $cta_link['url'] ?>" target="<?= $cta_link['target'] ?>" class="btn btn--secondary">
                                <span><?= $cta_link['title'] ?></span>
                            </a>
                        </div>
                    <?php endif ?>

                </div>

            </div>
        </div>

    </section>

<?php
endif;
?>