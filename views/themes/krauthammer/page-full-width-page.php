<?php
/*
Template Name: Full Width Page
*/
?>

<?php
get_header();
?>

<?php bloginfo('template_directory'); ?>/
<div class="containment">

    <section class="container-fluid publication-banner d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 d-flex flex-column align-items-start f_j_b t16 c_141515">
                    <h1 class="title f_j_bld t32 c_2a7db1"><?php echo cms_get_post_title(); ?></h1>
                </div>
            </div>
        </div>
    </section>
 
    <?php cms_get_fragment('', 'contact-full-width'); ?>

</div>


<?php
get_footer();
?>