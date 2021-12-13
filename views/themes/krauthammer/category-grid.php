<?php
get_header('page');
?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10">
					<div class="post-preview">
					    <h2 class="post-title">
					        POST CATEGORIES
					    </h2>
					    <?php cms_get_sidebar_item( 'categories' ) ?>
					</div>
					<hr/>
        </div>
        <div class="col-lg-4 col-md-2">
            <?php get_sidebar('post-category'); ?>
        </div>
    </div>
</div>

<?php
get_footer();
?>