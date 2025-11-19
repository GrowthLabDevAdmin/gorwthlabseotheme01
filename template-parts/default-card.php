<div class="default-card <?= $args["classes"] ?>">
    <div class="default-card__wrapper">

        <?php
        if (isset($args['picture']) && $args['picture']):
            if ($args['link_url']) echo "<a href=" . $args['link_url'] . " class='default-card__pic-link' target=" . $args['link_target'] . ">";
            img_print_picture_tag(img: $args["picture"], max_size: "medium", classes: "default-card__pic");
            if ($args['link_url']) echo "</a>";
        endif;
        ?>

        <div class="default-card__inner tx-center">

            <p class="default-card__title"><?= $args["title"] ?></p>

            <p class="default-card__content"><?= $args["content"] ?></p>

            <?php if ($args['link_url']): ?>
                <div class="default-card__btn">
                    <a href="<?= $args['link_url'] ?>" target="<?= $args['link_target'] ?>" class="btn btn--secondary">
                        <span>Read More</span>
                    </a>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>