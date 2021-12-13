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
			<h1>Category: <?php echo $category_info->category_name; ?></h1>
			<?php $post_ctr = 0; ?>
			<?php while ( cms_have_posts() ) : cms_the_post(); ?>
				<div class="post-preview">
					<a href="<?php echo get_bloginfo("baseurl") . cms_get_post_permalink(); ?>">
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
				<?php $post_ctr++; ?>
			<?php endwhile; ?>


			<?php if (!$post_ctr): ?>
				<p>No Post for this category</p>
			<?php endif ?>

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
		</div>
		<div class="col-lg-4 col-md-2">
			<?php get_sidebar('post-category'); ?>
		</div>
	</div>
</div>

<?php
get_footer();
?>