<?php
/*
Template Name: Blog Page
*/ 
get_header('page');
?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10">
        <?php
        //DO POST QUERY HERE\
        $cat_ids = cms_get_post_category_ids( cms_get_post_id() );
        
        cms_reset_query();
        cms_query_posts("post_type=post"); // retrieving posts
        cms_query_blog_posts( $cat_ids ); //reseting cms posts queries
        cms_set_page_offset( isGet('page') ? intval(get('page')) - 1 : 0 );
        ?>
        <?php if ( cms_have_posts() ): ?>
            <?php while ( cms_have_posts() ) : cms_the_post(); ?>
            <div class="post-preview">
                <a href="<?php echo cms_get_post_link(); ?>">
                    <h2 class="post-title">
                        <?php echo cms_get_post_title(); ?>
                    </h2>
                    <h3 class="post-subtitle">
                        <?php echo cms_get_post_excerpt(); ?>
                    </h3>
                </a>
                <p class="post-meta">Posted by <a href="#">Unknown</a> <?php echo cms_get_post_date(); ?></p>
            </div>
            <hr>
            <?php endwhile; ?>

            <!-- Pager -->
            <ul class="pager">
            <?php if (cms_have_prev_page_offset()): ?>
                <li class="previous">
                    <a href="<?php echo get_current_uri() ?><?php echo cms_prev_page_offset() != 1 ? "?page=" . cms_prev_page_offset() : "" ?>">&larr; Earlier Posts</a>
                </li>
            <?php endif ?>
            <?php if (cms_have_next_page_offset()): ?>
                <li class="next">
                    <a href="<?php echo get_current_uri() ?>?page=<?php echo cms_next_page_offset() ?>">Older Posts &rarr;</a>
                </li>
            <?php endif ?>
            </ul>
            
        <?php else: ?>
            <p>No Post Found</p>
        <?php endif ?>
        </div>
        <div class="col-lg-4 col-md-2">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php
get_footer();
?>