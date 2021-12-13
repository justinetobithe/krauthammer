<?php
/*
Template Name: Contact Form
*/ 
if (isset($_POST['_cmscf_id'])) {
    custom_function_add_subscribers();
    exit();
}

get_header('page');
?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10">
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