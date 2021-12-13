<?php
get_header('product');
?>

<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10">
			<?php EcommerceManager::enquire_layout(); ?>
		</div>
		<div class="col-lg-4 col-md-2">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php
get_footer();
?>