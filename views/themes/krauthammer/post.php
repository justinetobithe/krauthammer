<?php
get_header();
?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <?php echo cms_post_content(); ?>
            <p><small>Author: <?php echo cms_get_author(); ?></small></p>
        </div>
    </div>
</div>

<?php
get_footer();
?>