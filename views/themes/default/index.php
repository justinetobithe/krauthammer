<?php
get_header();
?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
        <?php cms_reset_query(); ?>
        <?php cms_query_posts("post_type=post"); ?>
        <?php
            while ( cms_have_posts() ) : cms_the_post();
        ?>
            <div class="post-preview">
                <a href="<?php echo cms_get_post_permalink(); ?>">
                    <h2 class="post-title">
                        <?php echo cms_get_post_title(); ?>
                    </h2>
                    <h3 class="post-subtitle">
                        <?php echo cms_get_post_excerpt(); ?>
                    </h3>
                </a>
                <p class="post-meta">Posted by <a href="#">Unknown</a> <?php echo cms_get_post_date("F d, Y"); ?></p>
            </div>
            <hr>
        <?php
            endwhile;
        ?>
            <!-- Pager -->
            <ul class="pager">
            <?php if (cms_have_prev_page_offset()): ?>
                <li class="previous">
                    <a href="<?php echo get_current_uri() ?>?page=<?php echo cms_prev_page_offset() ?>">&larr; Earlier Posts</a>
                </li>
            <?php endif ?>
            <?php if (cms_have_next_page_offset()): ?>
                <li class="next">
                    <a href="<?php echo get_current_uri() ?>?page=<?php echo cms_next_page_offset() ?>">Older Posts &rarr;</a>
                </li>
            <?php endif ?>
            </ul>
        </div>
    </div>
</div>

<?php
get_footer();
?>