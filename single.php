<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();

global $post;
$post_id =  $post->ID;
?>

<section class="post__inner bg-bicolor">

    <div class="post__wrapper container">

        <main class="post__main border-box">

            <?php while (have_posts()) {
                the_post();
                img_print_picture_tag(img: get_the_post_thumbnail_url(), max_size: 'large', is_cover: true, classes: "post__image");
                the_content();
            }
            ?>

        </main>

        <?php
        $args = array('ID' => $post_id, 'classes' => 'post__sidebar');
        get_sidebar('blog', null, $args);
        ?>

    </div>

</section>

<?php get_footer() ?>