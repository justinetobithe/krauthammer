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
			<form name="" action="<?php echo get_bloginfo("baseurl") . "customer/submit_login/"; ?>" method="post" class="cms-form" novalidate="novalidate" _lpchecked="1">
				<div style="display: none;">
					<input type="hidden" name="_cmslf_" value="loginform">
				</div>

				<h3>Login</h3>

				<?php if (isset($_GET['error'])): ?>
				<p class="well well-sm"><?php echo '<span>Login Error</span>'; ?></p>
				<?php endif ?>

				<p>Email<br>
					<span class="cms-form-control-wrap"><input type="email" name="login_email" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Email" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Password<br>
					<span class="cms-form-control-wrap"><input type="password" name="login_password" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Your" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>

				<span class="cms-form-control-wrap"><label><input type="checkbox" name="login_remember"> &nbsp; Remember Me!!!</label>
					<span>
						<p>
							<span class="cms-form-control-wrap">
								<input type="submit" class="cms-form-control cms-button uppercase cms-validates-as-required btn-m" value="Login">
								<span></span>
							</span>
							<span class="pull-right"><a href="<?php echo get_bloginfo('baseurl') ?>customer/forgot_password/">Forgot Password</a></span>
						</p>
						<div class="cms-response-output cms-display-none"></div>
						<p></p>
					</span>
				</span>
			</form>
		</div>
		<div class="col-lg-6 col-md-6">
		<?php if (get_system_option("enable_customer_registration") == "ON"): ?>
			
			<form name="" action="<?php echo get_bloginfo("baseurl") . "customer/submit_registration/"; ?>" method="post" class="cms-form" novalidate="novalidate" _lpchecked="1">
				<div style="display: none;">
					<input type="hidden" name="_cmsrf_" value="registrationform">
					<input type="hidden" name="from-singapore" value="">
					<input type="hidden" name="job-country" value="">
					<input type="hidden" name="office-address" value="">
					<input type="hidden" name="travelling-experience" value="">
					<input type="hidden" name="card-number-exclusive" value="">
					<input type="hidden" name="promotional-code-ex-full-discount" value="">
					<input type="hidden" name="overseas-telephone-number" value="">
					<input type="hidden" name="alternate-email-3" value="">
					<input type="hidden" name="pw-agent-id-1225" value="">
					<input type="hidden" name="job-experience" value="">
					<input type="hidden" name="job-title" value="">
				</div>

				<h3>Sign Up</h3>

				<p>Email<br>
					<span class="cms-form-control-wrap"><input type="email" name="register_email" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Email" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Password<br>
					<span class="cms-form-control-wrap"><input type="password" name="register_password" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Your" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Confirm Password<br>
					<span class="cms-form-control-wrap"><input type="password" name="register_password" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Your" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>

				<h4>Detail</h4>

				<p>First Name<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_firstname" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="First Name" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Last Name<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_lastname" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Last Name" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Company Name<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_company_name" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Company Name" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>

				<h4>Address</h4>
				<p>Address 1<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_address1" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Address 1" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Address 2<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_address2" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Address 2" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>City<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_city" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="City" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Country<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_country" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Country" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>State<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_state" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="State" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Postal<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_postal" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Postal" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>
				<p>Telephone<br>
					<span class="cms-form-control-wrap"><input type="text" name="register_phone" class="cms-form-control cms-text cms-validates-as-required input-m" placeholder="Telephone" aria-required="true" aria-invalid="false"><span> </span></span>
				</p>

				<span class="cms-form-control-wrap">
					<span>
						<p><span class="cms-form-control-wrap"><input type="submit" class="cms-form-control cms-button uppercase cms-validates-as-required btn-m" name="register_submit" value="Sign Up"><span></span></span></p>
						<div class="cms-response-output cms-display-none"></div>
						<p></p>
					</span>
				</span>
			</form>

			<script>
				$(document).ready(function(){
					$("form.cms-form").submit(function(e){
						e.preventDefault();

						var data = {};

						$.each($(this).find('input'), function(k, v){
							data[$(this).attr('name')] = $(this).val();
						});

						$.post($(this).attr('action'),{data},function(response) {
							console.log(response)
						});
					});
				});
			</script>
		<?php else: ?>
			<div>
				<br><br>
				<p>Please contact the administration to obtain an account.</p>
			</div>
		<?php endif ?>
		</div>
	</div>
</div>

<?php
get_footer();
?>