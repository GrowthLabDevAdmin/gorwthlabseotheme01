<?php
if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;
?>

    <section
        id="<?= $block_id ?? "" ?>"
        class="block logos-carousel<?= !$background_color ? ' bg-bicolor' : '' ?>"
        <?php
        echo isset($extract_block_from_content) && $extract_block_from_content ? "data-extract='$place'" : '';
        if ($background_color) "style='background-color: $background_color'";
        ?>>

        <div class="logos-carousel__wrapper container tx-center">
            <?php print_title($title, $title_tag, "logos-carousel__title") ?>

            <?php
            if (!empty($logos)):
            ?>
                <div class="logos-carousel__carousel">
                    <div class="splide">
                        <div class="splide__track">
                            <div class="splide__list">
                                <?php foreach ($logos as $logo):
                                    if (empty($logo)) continue;
                                    $has_link = !empty($logo['link']['url']);
                                ?>
                                    <div class="logo splide__slide">
                                        <?php if ($has_link): ?>
                                            <a href="<?= esc_url($logo['link']['url']); ?>"
                                                target="<?= esc_attr($logo['link']['target']); ?>"
                                                class="logo__link">
                                            <?php endif; ?>

                                            <?= img_generate_picture_tag(
                                                img: $logo['logo'],
                                                max_size: "thumbnail",
                                                classes: "logo__pic"
                                            ); ?>


                                            <?php if ($has_link): ?>
                                                <span><?= $logo['link']['title'] ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            ?>
        </div>
    </section>

<?php
endif;
?>