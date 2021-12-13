<?php
/*
Template Name: Publications
*/
?>

<?php
    get_header();
?> 

<?php
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }else {
        $page = 1;
    }

?>

<div class="containment">
    <section class="container-fluid publication-banner d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6 d-flex flex-column align-items-start f_j_b t16 c_141515">
                    <h1 class="title f_j_bld t50 c_2a7db1"><?php echo cms_get_post_title(); ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid publication-body">
        <div class="container">
            <div class="intro row">
                <div class="col-12">
                    <h4 class="title f_j_s t32 c_222 text-center"><?php echo cms_get_post_title(); ?></h4>
                    <p class="sub-title text-center">Our thoghts on issues that matter to people and learning</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs d-flex justify-content-center f_j_m t14 c_222" id="myTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link text-uppercase" id="interview-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/interview'" role="tab" aria-controls="interview-tab-pane" aria-selected="false">Interview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="leadership-and-management-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/leadership-and-management'" role="tab" aria-controls="leadership-and-management-tab-pane" aria-selected="false">Leadership and Management</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="leadership-and-management-articles-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/leadership-and-management-articles'" role="tab" aria-controls="leadership-and-management-articles-tab-pane" aria-selected="true">Leadership and Management Articles</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="new-manager-skills-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/new-manager-skills'" role="tab" aria-controls="new-manager-skills-tab-pane" aria-selected="false">New Manager Skills</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="personal-development-articles-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/personal-development-articles'" role="tab" aria-controls="personal-development-articles-tab-pane" aria-selected="true">Personal Development Articles</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link text-uppercase" id="remote-sales-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/remote-sales'" role="tab" aria-controls="remote-sales-tab-pane" aria-selected="false">Remote Sales</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="sales-articles-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/sale-articles'" role="tab" aria-controls="sales-acrticles-tab-pane" aria-selected="false">Sales Articles</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="sales-excellence-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/sales-excellence'" role="tab" aria-controls="sales-excellence-tab-pane" aria-selected="true">Sales Excellence</button>
                        </li>
                        <!--<li class="nav-item">-->
                        <!--    <button class="nav-link text-uppercase" id="leadership-and-managment-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/leadership-and-management'" role="tab" aria-controls="leadership-and-managment" aria-selected="false">Leadership-->
                        <!--        and management</button>-->
                        <!--</li>-->
                        <!--<li class="nav-item" role="presentation">-->
                        <!--    <button class="nav-link text-uppercase" id="sales-excellence-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/sale-excellence'" role="tab" aria-controls="sales-excellence-tab-pane" aria-selected="false">Sales Excellence</button>-->
                        <!--</li>-->
                        <!--<li class="nav-item" role="presentation">-->
                        <!--    <button class="nav-link text-uppercase" id="personal-development-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/personal-development'" role="tab" aria-controls="personal-development-tab-pane" aria-selected="true">Personal-->
                        <!--        Development</button>-->
                        <!--</li>-->
                        <!--<li class="nav-item" role="presentation">-->
                        <!--    <button class="nav-link text-uppercase" id="blog-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/blog'" role="tab" aria-controls="blog-tab-pane" aria-selected="false">Blog</button>-->
                        <!--</li>-->
                        <!--<li class="nav-item" role="presentation">-->
                        <!--    <button class="nav-link text-uppercase" id="videos-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/video'" role="tab" aria-controls="videos-tab-pane" aria-selected="true">Videos</button>-->
                        <!--</li>-->
                    </ul>
                </div>
            </div>
            <div class="body row">  
                        <?php  
                            $pageNo = '?page=' . $page;
                            
                            get_publicationAll($pageNo);    
                        ?>        
            </div> 
            <div class="bottom row">
                <div class="col-12">
                        <ul class="nav pagination d-flex align-items-center justify-content-center">
                            <?php 
                                get_paginationAll($pageNo);
                            ?>
                        </ul>
                </div>
            </div>
            
        </div>

        
        
        

    </section>


    <?php cms_get_fragment('', 'contact'); ?>

</div>


<?php
get_footer();
?>