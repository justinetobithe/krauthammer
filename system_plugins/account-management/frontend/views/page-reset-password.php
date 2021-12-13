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
			<form name="" action="<?php echo get_bloginfo("baseurl") . "customer/submit_reset_password/"; ?>" method="post" class="cms-form" novalidate="novalidate" _lpchecked="1">
				<div style="display: none;">
					<input type="hidden" name="_cmslf_" value="resetpasswordform">
				</div>

				<h3>Reset Password</h3>

				<?php if (isset($_GET['error'])): ?>
				<p class="well well-sm"><?php echo '<span>Login Error</span>'; ?></p>
				<?php endif ?>

				<p>
					<span class="cms-form-control-wrap"><input type="hidden" name="reset_code" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Email" aria-required="true" aria-invalid="false" value="<?php echo $reset_hash; ?>" ><span> </span></span>
				</p>
				<p>New Password<br>
					<span class="cms-form-control-wrap"><input type="password" name="reset_password" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="New Password" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Confirm Password<br>
					<span class="cms-form-control-wrap"><input type="password" name="reset_password_confirm" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Confirm Password" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>

				<span class="cms-form-control-wrap">
					<span>
						<p>
							<span class="cms-form-control-wrap">
								<input type="submit" name="reset_btn" class="cms-form-control cms-button uppercase cms-validates-as-required btn-m" value="Reset">
								<span></span>
							</span>
						</p>
						<div class="cms-response-output cms-display-none"></div>
						<p></p>
					</span>
				</span>
			</form>
		</div>
		<div class="col-lg-6 col-md-6"></div>
	</div>
</div>

<?php
get_footer();
?>