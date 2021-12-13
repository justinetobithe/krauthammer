<?php
/*
Template Name: Publications Blog
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
                    <h1 class="title f_j_bld t50 c_2a7db1">Publications</h1></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid publication-body">
        <div class="container">
            <div class="intro row">
                <div class="col-12">
                    <h4 class="title f_j_s t32 c_222 text-center">Publications</h4>
                    <p class="sub-title text-center">Our thoghts on issues that matter to people and learning</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs d-flex justify-content-center f_j_m t14 c_222" id="myTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link text-uppercase" id="leadership-and-managment-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/leadership-and-management/'" role="tab" aria-controls="leadership-and-managment" aria-selected="false">Leadership
                                and management</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="sales-excellence-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/sale-excellence/'" role="tab" aria-controls="sales-excellence-tab-pane" aria-selected="false">Sales Excellence</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="personal-development-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/personal-development/'" role="tab" aria-controls="personal-development-tab-pane" aria-selected="true">Personal
                                Development</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase active" id="blog-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/blog/'" role="tab" aria-controls="blog-tab-pane" aria-selected="false">Blog</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-uppercase" id="videos-tab" data-toggle="tab" onclick="window.location.href='<?php get_bloginfo('baseurl'); ?>/krauthammer/publications/video/'" role="tab" aria-controls="videos-tab-pane" aria-selected="true">Videos</button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="body row"> 
                        <?php  
                         $pageNo = '?page=' . $page;    
                            echo publicationDisplay("blog", $pageNo); 
                        ?>  
            </div> 
            <div class="row">
                <div class="col-12"> 
                        <ul class="nav pagination d-flex align-items-center justify-content-center"> 
                            <?php 
                            $pageNo = '?page=' . $page;  
                                paginationDisplay("blog", $pageNo); 
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