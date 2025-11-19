<div class="post-card <?= $args["classes"] ?>">
    <div class="post-card__wrapper">

        <?php
        if (isset($args['picture']) && $args['picture']):
            if ($args['link_url']) {
                echo "<a href=" . $args['link_url'] . " class='post-card__pic-wrapper' target=" . $args['link_target'] . ">";
            } else {
                echo "<div class='post-card__pic-wrapper'>";
            }
            img_print_picture_tag(img: $args["picture"], max_size: "medium", classes: "post-card__pic");
            if ($args['link_url']) {
                echo "</a>";
            } else {
                echo "</div>";
            }
        endif;
        ?>

        <div class="post-card__inner">
            <span class="post-card__meta"><?= $args["meta"] ?></span>

            <p class="post-card__title"><?= $args["title"] ?></p>

            <p class="post-card__content"><?= $args["excerpt"] ?></p>

            <?php if ($args['link_url']): ?>
                <div class="post-card__btn">
                    <a href="<?= $args['link_url'] ?>" target="<?= $args['link_target'] ?>" class="btn btn--secondary">
                        <span>Read More</span>
                    </a>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>