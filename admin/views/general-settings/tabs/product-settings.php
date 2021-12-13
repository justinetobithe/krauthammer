<?php if ($system_type == 'ECOMMERCE' || $system_type == 'ECATALOG'): ?>

<?php 
$product_no_index_ecommerce_ecatalog 	= get_system_option(array('option_name' =>'product_no_index_ecommerce_ecatalog')); 
$product_no_index_category_page 			= get_system_option(array('option_name' =>'product_no_index_category_page')); 
$product_no_index_detail_page 				= get_system_option(array('option_name' =>'product_no_index_detail_page')); 
$product_no_index_cart 								= get_system_option(array('option_name' =>'product_no_index_cart')); 
$product_no_index_checkout 						= get_system_option(array('option_name' =>'product_no_index_checkout')); 
$product_no_index_order_enquiry 			= get_system_option(array('option_name' =>'product_no_index_order_enquiry')); 
$product_base_url 										= get_system_option(array('option_name' =>'product_url')); 
$product_categories_base_url 					= get_system_option(array('option_name' =>'product_category_url')); 
$product_category_format_url 					= get_system_option(array('option_name' =>'product_category_format_url')); 
$product_base_url 										= $product_base_url != '' ? $product_base_url : 'products';
$product_categories_base_url 					= $product_categories_base_url != '' ? $product_categories_base_url : 'product-category';
?>
	<div id="product-setting" class="tab-pane">
		<div class="widget-box">
			<div class="widget-header header-color-blue">
				<h5 class="lighter">Product Settings</h5>

				<div class="widget-toolbar no-border">
					<button class="btn btn-mini btn-success" id="btn-save-product-setting">
						<i class="icon-save"></i>
						Save
					</button>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-main">
					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Language: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<select name="product-language" id="product-language">
								<?php foreach ($languages as $key => $language): ?>
									<option value="<?php echo $language->meta != $res_lang->meta ? $language->meta : ''; ?>" <?php echo $language->guid == '1' ? 'selected' : '' ?> ><?php echo $language->value ?></option>
								<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<br>

					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Prodcuct Base Directory: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<input id="product-base-url" name="switch_indexing" class="ace input span12" type="text" value="<?php echo $product_base_url ?>"/>
								<span class="lbl"></span>
								<p id="product-base-url-flag"></p>
							</div>
						</div>
					</div>
					
					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Prodcuct Category Base Directory: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<input id="product-categories-base-url" name="switch_indexing" class="ace input span12" type="text" value="<?php echo $product_categories_base_url ?>"/>
								<span class="lbl"></span>
								<p id="product-categories-base-url-flag"></p>
							</div>
						</div>
					</div>

					<hr id="anchor-product-category-format-url">

					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Prodcuct Category URL Format: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<label>
									<input name="product_category_format_url" type="radio" class="ace" value="show_parent" <?php echo $product_category_format_url=='show_parent' || $product_category_format_url == '' ? 'checked="checked"': "" ?> > &nbsp;
									<span class="lbl"><small>/.../<b class="text-info">[parent-slug]</b>/<b>[product-cateogry-url-slug]/</b></small></span>
								</label>
								<label>
									<input name="product_category_format_url" type="radio" class="ace" value="no_parent" <?php echo $product_category_format_url=='no_parent'? 'checked="checked"': "" ?>  > &nbsp;
									<span class="lbl"><small>/...<b>/[product-cateogry-url-slug]/</b></small></span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="widget-box">
			<div class="widget-header header-color-blue">
				<h5 class="lighter">Product Page Indexing</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main">
					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Disallow eCommerce/eCatalog Indexing: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<input id="product_no_index_ecommerce_ecatalog" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox" <?php echo $product_no_index_ecommerce_ecatalog == 'Y' ? 'checked' : '' ?>/>
								<span class="lbl"></span>
							</div>
						</div>
					</div>
					<br>
					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Disallow Product Category Indexing: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<input id="product_no_index_category_page" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox" <?php echo $product_no_index_category_page == 'Y' ? 'checked' : '' ?>/>
								<span class="lbl"></span>
							</div>
						</div>
					</div>
					<br>
					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Disallow Product Details Indexing: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<input id="product_no_index_detail_page" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox" <?php echo $product_no_index_detail_page == 'Y' ? 'checked' : '' ?>/>
								<span class="lbl"></span>
							</div>
						</div>
					</div>
					<br>
					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Disallow Product Cart Indexing: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<input id="product_no_index_cart" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox" <?php echo $product_no_index_cart == 'Y' ? 'checked' : '' ?>/>
								<span class="lbl"></span>
							</div>
						</div>
					</div>
					<br>
					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Disallow Product Checkout Indexing: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<input id="product_no_index_checkout" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox" <?php echo $product_no_index_checkout == 'Y' ? 'checked' : '' ?>/>
								<span class="lbl"></span>
							</div>
						</div>
					</div>
					<br>
					<div class="row-fluid">
						<div class="span5">
							<label class="text-right"><small>Disallow Product Order/Enquery Indexing: </small></label>
						</div>
						<div class="span7">
							<div class="controls">
								<input id="product_no_index_order_enquiry" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox" <?php echo $product_no_index_order_enquiry == 'Y' ? 'checked' : '' ?>/>
								<span class="lbl"></span>
							</div>
						</div>
					</div>
					<br>
				</div>
			</div>
		</div>
		<div class="widget-box">
			<div class="widget-header header-color-blue">
				<h5 class="lighter">Product Pages</h5>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<?php 
					$product_home_page = get_system_option(array('option_name' =>'product_home_page')); 
					$product_cart_page = get_system_option(array('option_name' =>'product_cart_page')); 
					$product_checkout_page = get_system_option(array('option_name' =>'product_checkout_page')); 
					$product_enquire_page = get_system_option(array('option_name' =>'product_enquire_page')); 
					$product_payment_method_page = get_system_option(array('option_name' =>'product_payment_method_page')); 
					$product_confirmation_page = get_system_option(array('option_name' =>'product_confirmation_page')); 
					$product_confirmed_page = get_system_option(array('option_name' =>'product_confirmed_page')); 
					?>

					<div class="row-fluid">
						<div class="span12">
							<p>Copy the shortcode and paste in a <a href="<?php echo URL; ?>pages/">Page</a> content.</p>
						</div>
					</div>
					<hr>
					<div class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="blog-post-count"><small>Product Homepage</small></label>
							<div class="controls">
								<select name="selected-product-home-page" id="selected-product-home-page">
									<option value="" >- Select a Page -</option>
									<?php foreach ($pages as $key => $page): ?>
										<option value="<?php echo $page->id; ?>" <?php echo $product_home_page == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
									<?php endforeach ?>
								</select> | 
								<span class="well well-small">[product_homepage]</span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="blog-post-count"><small>Product Cart</small></label>
							<div class="controls">
								<select name="selected-product-cart-page" id="selected-product-cart-page">
									<option value="" >- Select a Page -</option>
									<?php foreach ($pages as $key => $page): ?>
										<option value="<?php echo $page->id; ?>" <?php echo $product_cart_page == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
									<?php endforeach ?>
								</select> | 
								<span class="well well-small">[product_cart]</span>
							</div>
						</div>

						<?php if ($system_type == 'ECOMMERCE'): ?>
							<div class="control-group">
								<label class="control-label" for="blog-post-count"><small>Product Checkout</small></label>
								<div class="controls">
									<select name="selected-product-checkout-page" id="selected-product-checkout-page">
										<option value="" >- Select a Page -</option>
										<?php foreach ($pages as $key => $page): ?>
											<option value="<?php echo $page->id; ?>" <?php echo $product_checkout_page == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
										<?php endforeach ?>
									</select> | 
									<span class="well well-small">[product_checkout]</span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="blog-post-count"><small>Product Payment Method</small></label>
								<div class="controls">
									<select name="selected-product-payment-method-page" id="selected-product-payment-method-page">
										<option value="" >- Select a Page -</option>
										<?php foreach ($pages as $key => $page): ?>
											<option value="<?php echo $page->id; ?>" <?php echo $product_payment_method_page == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
										<?php endforeach ?>
									</select> | 
									<span class="well well-small">[product_payment_method]</span>
								</div>
							</div>
						<?php endif ?>
						<?php if ($system_type == 'ECATALOG'): ?>
							<div class="control-group">
								<label class="control-label" for="blog-post-count"><small>Product Enquiry</small></label>
								<div class="controls">
									<select name="selected-product-enquire-page" id="selected-product-enquire-page">
										<option value="" >- Select a Page -</option>
										<?php foreach ($pages as $key => $page): ?>
											<option value="<?php echo $page->id; ?>" <?php echo $product_enquire_page == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
										<?php endforeach ?>
									</select> | 
									<span class="well well-small">[product_enquire]</span>
								</div>
							</div>
						<?php endif ?>
						<div class="control-group">
							<label class="control-label" for="blog-post-count"><small>Product Confirmation</small></label>
							<div class="controls">
								<select name="selected-product-confirmation-page" id="selected-product-confirmation-page">
									<option value="" >- Select a Page -</option>
									<?php foreach ($pages as $key => $page): ?>
										<option value="<?php echo $page->id; ?>" <?php echo $product_confirmation_page == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
									<?php endforeach ?>
								</select> | 
								<span class="well well-small">[product_confirmation]</span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="blog-post-count"><small>Product Confirmed</small></label>
							<div class="controls">
								<select name="selected-product-confirmed-page" id="selected-product-confirmed-page">
									<option value="" >- Select a Page -</option>
									<?php foreach ($pages as $key => $page): ?>
										<option value="<?php echo $page->id; ?>" <?php echo $product_confirmed_page == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
									<?php endforeach ?>
								</select> | 
								<span class="well well-small">[product_confirmed]</span>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="widget-box" id="product-custom-field">
			<div class="widget-header header-color-blue">
				<h5 class="lighter">Product Custom Page Templates</h5>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row-fluid">
						<div class="span12">
							<div class="form-horizontal">
								<div class="control-group">
									<label for="product-custom-field-selection" class="control-label"><small>Select Template</small></label>
									<div class="controls">
										<select id="product-custom-field-selection"></select>
										<a href="javascript:void(0)" id="btn-product-custom-field-selection-delete">Delete</a>
									</div>
								</div>
							</div>
							<hr>
							<div id="product-custom-field-preview"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>