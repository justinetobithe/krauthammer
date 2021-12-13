<?php
get_header('product');
?>

<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10">
			<?php echo cms_generate_payment_button(); ?>
			<!-- <form action="<?php echo cms_get_payment_url(); ?>" method='post'>
				<p>Finish you purchase by paying through paypal</p>
				<button class="btn btn-success" name="proceed-paypal" value="proceed-paypal">Proceed to Paypal</button>
			</form> -->
		</div>
		<div class="col-lg-4 col-md-2">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php
get_footer();
?>