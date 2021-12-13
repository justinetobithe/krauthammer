<?php if (count($products)):?>
<?php
	cms_set_logit_user_info_cache();

	function _get_default_form_data($key = "", $loginuserdatakey = ""){
		$logged_in_user_info = cms_get_logit_user_info_cache();
		$val = EcommerceManager::get_current_user_detail($key);
		return $val != "" ? $val : (isset($logged_in_user_info->$loginuserdatakey) ? $logged_in_user_info->$loginuserdatakey : "");
	}

	$form_defualt_value = array(
		"firstname" => _get_default_form_data("firstname", "firstname"),
		"lastname" => _get_default_form_data("lastname", "lastname"),
		"company_name" => _get_default_form_data("company_name", "company_name"),
		"email" => _get_default_form_data("email", "email"),
		"phone" => _get_default_form_data("phone", "shipping_phone"),
		"notes" => _get_default_form_data("notes", ""),

		"country" => _get_default_form_data("country", "billing_country"),
		"address" => _get_default_form_data("address", "billing_address"),
		"address2" => _get_default_form_data("address2", "billing_address_2"),
		"town" => _get_default_form_data("town", "billing_city"),
		"country2" => _get_default_form_data("country2", "billing_country"),
		"post_code" => _get_default_form_data("post_code", "billing_postal_code"),

		"shipping_country" => _get_default_form_data("shipping_country", "shipping_country"),
		"shipping_address" => _get_default_form_data("shipping_address", "shipping_address"),
		"shipping_address2" => _get_default_form_data("shipping_address2", "shipping_address_2"),
		"shipping_town" => _get_default_form_data("shipping_town", "shipping_city"),
		"shipping_country2" => _get_default_form_data("shipping_country2", "shipping_country"),
		"shipping_post_code" => _get_default_form_data("shipping_post_code", "shipping_postal_code"),
	);

?>
<form action="<?php echo cms_get_enquiry_url() ?>" method="post">
	<div class="hide">
		<input type="hidden" name="enquire-action" value="enquire-action">
	</div>
	<div class="row">
		<div class="col-sm-6">
			<h3>Checkout</h3>
			<div class="row">
				<div class="col-sm-6">
					<label for="firstname" class="control-label">First Name</label>
					<div class="">
						<input type="text" id="firstname" name="firstname" class="form-control input" value="<?php echo $form_defualt_value['firstname'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="lastname" class="control-label">Last Name</label>
					<div class="">
						<input type="text" id="lastname" name="lastname" class="form-control input" value="<?php echo $form_defualt_value['lastname'] ?>">
					</div>
				</div>
				<div class="col-sm-12">
					<label for="company-name" class="control-label">Company Name</label>
					<div class="">
						<input type="text" id="company-name" name="company-name" class="form-control input" value="<?php echo $form_defualt_value['company_name'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="email" class="control-label">Email</label>
					<div class="">
						<input type="email" id="email" name="email" class="form-control input" value="<?php echo $form_defualt_value['email'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="phone" class="control-label">Phone</label>
					<div class="">
						<input type="text" id="phone" name="phone" class="form-control input" value="<?php echo $form_defualt_value['phone'] ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<h3>Additional Information</h3>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-horizontal">
						<div class="col-sm-">
							<label for="other-notes" class="control-label">Other Notes</label>
							<div class="">
								<textarea name="other-notes" id="other-notes" cols="30" rows="5"><?php echo $form_defualt_value['notes'] ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<hr>
	<div class="text-center">
		<button class="btn btn-success">Enquire</button>
	</div>
</form>
<?php else: ?>
	<p>No product found. <a href="<?php echo cms_get_product_url(); ?>">Back to Product Page</a></p>
<?php endif ?>