<?php
/*
Template Name: Lost Password
*/ 
get_header();
?>

<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<form name="" action="<?php echo get_bloginfo("baseurl") . "customer/submit_forgot_password/"; ?>" method="post" class="cms-form" novalidate="novalidate" _lpchecked="1">
				<div style="display: none;">
					<input type="hidden" name="_cmslf_" value="forgotpasswordform">
				</div>

				<h3>Lost Password</h3>

				<?php if (isset($_GET['error'])): ?>
				<p class="well well-sm"><?php echo '<span>Login Error</span>'; ?></p>
				<?php endif ?>

				<p>Email<br>
					<span class="cms-form-control-wrap"><input type="email" name="login_email" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Email" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>

				<span class="cms-form-control-wrap">
					<span>
						<p>
							<span class="cms-form-control-wrap">
								<input type="submit" class="cms-form-control cms-button uppercase cms-validates-as-required btn-m" value="Confirm">
								<span></span>
							</span>
						</p>
						<div class="cms-response-output cms-display-none"></div>
						<p></p>
					</span>
				</span>
			</form>
		</div>
	</div>
</div>

<?php
get_footer();
?>