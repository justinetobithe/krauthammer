<?php
/*
Template Name: Login
*/ 
get_header();
?>

<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-6 col-md-6">
			<?php echo isset($message) ? $message : "Something went wrong..." ?>
		</div>
		<div class="col-lg-6 col-md-6"></div>
	</div>
</div>

<?php
get_footer();
?>