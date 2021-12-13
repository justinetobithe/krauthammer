<?php
/*
Template Name: Full Page Sidebar
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
                <div class="col-12 col-lg-6 d-flex flex-column align-items-start f_j_b t16 c_141515">
                    <h1 class="title f_j_bld t32 c_2a7db1">Full Page Sidebar</h1>
                </div>
            </div>
        </div>
    </section> 

    <?php cms_get_fragment('', 'contact-full-page-sidebar'); ?>

</div>


<?php
get_footer();
?>