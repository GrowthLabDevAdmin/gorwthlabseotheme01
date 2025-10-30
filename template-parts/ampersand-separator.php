<?php
foreach ($args as $key => $value) $$key = $value;
?>

<div class="ampersand-separator <?= esc_attr($classes); ?>">
    <hr>
    <?php include get_template_directory() . '../assets/img/ampersand-symbol.svg'; ?>
    <hr>
</div>