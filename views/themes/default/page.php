<?php
get_header('page');
?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10">
            <?php //print_r(json_encode(cms_get_shipping_info())) ?>
            <?php cms_post_content(); ?>
        </div>
        <div class="col-lg-4 col-md-2">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php
get_footer();
?>