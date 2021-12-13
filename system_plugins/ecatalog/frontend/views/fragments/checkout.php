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
<form action="<?php echo cms_get_checkout_url() ?>" method="post">
	<div class="hide">
		<input type="hidden" name="checkout-action" value="checkout-submit">
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

	<div class="row">
		<div class="col-sm-6">
			<h3>Billing Address</h3>
			<div class="row">
				<div class="col-sm-12">
					<label for="country" class="control-label">Country</label>
					<div class="">
						<input type="text" id="country" name="country" class="form-control input" value="<?php echo $form_defualt_value['country'] ?>">
					</div>
				</div>
				<div class="col-sm-12">
					<label for="address" class="control-label">Address</label>
					<div class="">
						<input type="text" id="address" name="address" class="form-control input" value="<?php echo $form_defualt_value['address'] ?>">
						<br>
						<input type="text" id="address-2" name="address-2" class="form-control input" value="<?php echo $form_defualt_value['address2'] ?>">
					</div>
				</div>
				<div class="col-sm-12">
					<label for="town" class="control-label">Town/City</label>
					<div class="">
						<input type="text" id="town" name="town" class="form-control input" value="<?php echo $form_defualt_value['town'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="country" class="control-label">Country</label>
					<div class="">
						<input type="text" id="country" name="country2" class="form-control input" value="<?php echo $form_defualt_value['country2'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="post-code" class="control-label">Post Code</label>
					<div class="">
						<input type="text" id="post-code" name="post-code" class="form-control input" value="<?php echo $form_defualt_value['post_code'] ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<h3>Shipping Address</h3>
			<div class="row">
				<div class="col-sm-12">
					<label for="shipping-country" class="control-label">Country</label>
					<div class="">
						<input type="text" id="shipping-country" name="shipping-country" class="form-control input" value="<?php echo $form_defualt_value['shipping_country'] ?>">
					</div>
				</div>
				<div class="col-sm-12">
					<label for="shipping-address" class="control-label">Address</label>
					<div class="">
						<input type="text" id="shipping-address" name="shipping-address" class="form-control input" value="<?php echo $form_defualt_value['shipping_address'] ?>">
						<br>
						<input type="text" id="shipping-address-2" name="shipping-address-2" class="form-control input" value="<?php echo $form_defualt_value['shipping_address2'] ?>">
					</div>
				</div>
				<div class="col-sm-12">
					<label for="shipping-town" class="control-label">Town/City</label>
					<div class="">
						<input type="text" id="shipping-town" name="shipping-town" class="form-control input" value="<?php echo $form_defualt_value['shipping_town'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="shipping-country" class="control-label">Country</label>
					<div class="">
						<input type="text" id="shipping-country2" name="shipping-country2" class="form-control input" value="<?php echo $form_defualt_value['shipping_country2'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<label for="shipping-post-code" class="control-label">Post Code</label>
					<div class="">
						<input type="text" id="shipping-post-code" name="shipping-post-code" class="form-control input" value="<?php echo $form_defualt_value['shipping_post_code'] ?>">
					</div>
				</div>
			</div>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-sm-12">
			<h2>Cart Detail</h2>
			<?php 
			$sub_total = 0;
			$total = 0;
			?>
			<table id="product-cart" class="product-cart-table">
				<thead>
					<tr>
						<th>Product Name</th>
						<th>Price</th>
						<th>SKU</th>
						<th>Quantity</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($products as $key => $value): ?>
						<?php
						$_total = intval($value['quantity']) * intval($value['product']['price']);
						$sub_total += $_total;
						$total += $_total;
						?>
						<tr>
						<td><?php echo $value['product']['product_name'] ?></td>
						<td><?php echo $value['product']['price'] ?></td>
						<td><?php echo $value['product']['sku'] ?></td>
							<td><?php echo $value['quantity'] ?></td>
							<td><?php echo $_total; ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-6">
			<h2>Cart Total</h2>
			<table id="product-cart-total" class="product-cart-table">
				<tbody>
					<tr>
						<td><b>SubTotal</b></td>
						<td><?php echo $sub_total ?></td>
					</tr>
					<tr>
						<td><b>Total</b></td>
						<td><?php echo $total ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<hr>
	<div class="text-center">
		<button class="btn btn-success">Continue to Payment</button>
	</div>
</form>
<?php else: ?>
	<p>No product to checkout.</p>
<?php endif ?>