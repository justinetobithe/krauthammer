<?php
$discount = get_system_option('ecommerce-self-collection-discount');
$normal_delivery_time = get_system_option('ecommerce-normal-delivery-time');
$normal_delivery_time = explode("\n", trim($normal_delivery_time));
$fixed_delivery_time = array();
$currency = get_system_option("currency_symbol");
foreach ($normal_delivery_time as $key => $value) {
	$fixed_delivery_time[$value] = array("value"=>$value, "selected"=>$delivery_detail['delivery_time'] == $value ? true : false);
}
function generateForm($field_type = "", $label="", $id_class="", $values = array()){
	if ($field_type != "select" && gettype($values) == 'array') {
		$values = "";
	}

	switch ($field_type) {
		case 'text':
		?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $id_class; ?>"><?php echo $label; ?></label>
			<div class="controls">
				<input type="text" id="<?php echo $id_class; ?>" name="name" class="input-xxlarge" value="<?php echo $values; ?>" readonly>
			</div>
		</div>
		<?php
		break;
		case 'textarea':
		?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $id_class; ?>"><?php echo $label; ?></label>
			<div class="controls">
				<textarea id="<?php echo $id_class; ?>" class="input input-xxlarge" cols="30" rows="5" readonly><?php echo $values; ?></textarea>
			</div>
		</div>
		<?php
		break;
		case 'switch':
		?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $id_class; ?>"><?php echo $label; ?></label>
			<div class="controls">
				<label>
					<input name="switch-field-1" class="ace ace-switch ace-switch-7" type="checkbox" id="<?php echo $id_class; ?>" <?php echo $values=="checked" ? 'checked="checked"' : ""; ?>>
					<span class="lbl"></span>
				</label>
			</div>
		</div>
		<?php
		break;
		case 'select':
		?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $id_class; ?>"><?php echo $label; ?></label>
			<div class="controls">
				<select id="<?php echo $id_class; ?>" disabled>
					<?php foreach ($values as $key => $value): ?>
						<option value="<?php echo $value['value']; ?>" <?php echo $value['selected'] ? 'selected' : '' ?>><?php echo $value['value']; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<?php
		break;
		case 'timepicker':
		?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $id_class; ?>"><?php echo $label; ?></label>
			<div class="controls">
				<div class="input-append bootstrap-timepicker">
					<input type="text" class="input input-small timepicker" id="<?php echo $id_class; ?>" value="<?php echo $values; ?>" readonly >
					<span class="add-on">
						<i class="icon-time"></i>
					</span>
				</div>
			</div>
		</div>
		<?php
		break;
		case 'datepicker':
		?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $id_class; ?>"><?php echo $label; ?></label>
			<div class="controls">
				<div class="input-append bootstrap-timepicker">
					<input type="text" class="input input-small datepicker" id="<?php echo $id_class; ?>" value="<?php echo $values; ?>" readonly>
					<span class="add-on">
						<i class="icon-calendar"></i>
					</span>
				</div>
			</div>
		</div>
		<?php
		break;
		
		default:
			# code...
		break;
	}
}

?>
<div class="main-content" id="main-content" data-content="content">
	<div class="page-content">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box transparent invoice-box">
					<div class="widget-header widget-header-large">
						<h3 class="grey lighter pull-left position-relative">
							<i class="icon-leaf green"></i>
							Order Details
						</h3>

						<?php if($order['order_status'] != 'cancelled'){ ?>
						<div class="widget-toolbar hidden-480">
							<a id="cancel_order" class="red" href="#" title="Cancel Order">
								<i class="icon-trash"></i>
							</a>
						</div>
						<?php } ?>
						<div class="widget-toolbar hidden-480">
							<a id="print_order" href="<?php echo URL.'orders/print-order/'.$order['id']; ?>">
								Print Order
								<i class="icon-print"></i>
							</a>
						</div>
						<div class="widget-toolbar hidden-480">
							<a id="print_invoice" href="<?php echo URL.'orders/print-invoice/'.$order['id']; ?>" >
								Print Invoice
								<i class="icon-print"></i>
							</a>
						</div>

						<div class="widget-toolbar no-border invoice-info">
							<span class="invoice-info-label">Invoice:</span>
							<span class="red">#<?php echo $order['invoice_number']?></span>

							<br />
							<span class="invoice-info-label">Date:</span>
							<span class="blue"><?php echo format_date($order['order_timestamp']); ?></span>
							<input type="hidden" value="<?php echo $order['order_timestamp']?>" id="order_timestamp">
							<input type="hidden" value="<?php echo $order['invoice_number']?>" id="invoice_number">
						</div>


					</div>
					<div id="result"></div>
					<div class="widget-body">
						<div class="widget-main">
							<div class="row-fluid">
								<div class="span12">
									<input type="hidden" id="action" value="view_orders">
									<div class="row-fluid">
										<form class="form-horizontal">
											<div class="widget-box">
												<div class="widget-header widget-header-small header-color-blue">
													<h5 class="bigger lighter">
														Customer Details
													</h5>
												</div>
												<div class="widget-body">
													<input type="hidden" id="hidden_id" value="<?php echo $order['id'];?>">
													<div class="widget-main">
														<div id="alertOrder"></div>
														<div class="control-group">
															<label class="control-label" for="first_name">First Name:</label>
															<div class="controls">
																<input type="text" value="<?php echo $order['first_name']; ?>" id="first_name" name="name" class="span10" readonly >
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="last_name">Last Name:</label>
															<div class="controls">
																<input type="text" value="<?php echo $order['last_name']; ?>" id="last_name" name="name" class="span10" readonly >
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="order_email">Email:</label>
															<div class="controls">
																<input type="text" id="order_email" value="<?php echo $order['email']?>" name="email" class="span10" readonly >
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="phone">Contact Number:</label>
															<div class="controls">
																<input type="text"  value="<?php echo $order['phone']?>" id="phone" name="contact_num" class="span10" readonly >
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="message">Message:</label>
															<div class="controls">
																<textarea id="message" rows="8" name="message" style="width:77%;" readonly><?php echo $order['message']?></textarea>     
															</div>
														</div>
													</div>

												</div>
											</div>
										</form>
									</div><!--PAGE Row END-->
								</div>
							</div>

							<div class="row-fluid">
								<div class="span12">
									<?php if (get_system_option('ecommerce-shipping-detail-enable') == "Y"): ?>
										<div class="widget-box">
											<div class="widget-header widget-header-small header-color-green">
												<h5 class="bigger lighter">
													Delivery Detail
												</h5>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<div id="e-form-container-order-delivery-detail" class="form-horizontal">
														<div class="control-group">
															<label class="control-label" for="">Mode of Delivery</label>
															<div class="controls">
																<label>
																	<input name="e-form-order-detail-mode" type="radio" class="ace" value="self-collection" <?php echo $delivery_detail['mode'] == 'self-collection' ? 'checked="checked"' : "" ?> disabled>
																	<span class="lbl"> Self - Collection (<?php echo $discount; ?>% - discount)</span>
																</label>
																<label>
																	<input name="e-form-order-detail-mode" type="radio" class="ace" value="delivery-to-home" <?php echo $delivery_detail['mode'] == 'delivery-to-home' ? 'checked="checked"' : "" ?> disabled>
																	<span class="lbl"> Delivery to Home and Others</span>
																</label>
															</div>
														</div>

														<div id="container-order-detail-self-collection" style="display: none;">
															<?php generateForm("datepicker", "Collection Date", "e-form-order-detail-collection-date", $delivery_detail['delivery_date']); ?>
															<?php generateForm("timepicker", "Collection Time", "e-form-order-detail-collection-time", $delivery_detail['delivery_time']); ?>
														</div>
														<div id="container-order-detail-home-delivery" style="display: none;">
															<?php generateForm("textarea", "Delivery Address", "e-form-order-detail-delivery-address", $delivery_detail['delivery_address']); ?>
															<?php generateForm("text", "Postal Code", "e-form-order-detail-postal-code", $delivery_detail['delivery_postal']); ?>
															<?php generateForm("datepicker", "Delivery Date", "e-form-order-detail-delivery-date", $delivery_detail['delivery_date']); ?>

															<div class="control-group">
																<label class="control-label" for="">Type of Delivery</label>
																<div class="controls">
																	<label>
																		<input name="e-form-order-detail-delivery-type" type="radio" class="ace" value="normal" <?php echo $delivery_detail['delivery_type'] == 'normal' ? 'checked="checked"' : "" ?> disabled >
																		<span class="lbl"> Normal Delivery Time</span>
																	</label>
																	<label>
																		<input name="e-form-order-detail-delivery-type" type="radio" class="ace" value="express" <?php echo $delivery_detail['delivery_type'] == 'express' ? 'checked="checked"' : "" ?> disabled >
																		<span class="lbl"> Express Delivery Time</span>
																	</label>
																</div>
															</div>

															<div id="container-order-detail-delivery-time-1" style="display: none;">
																<?php generateForm("select", "Delivery Time", "e-form-order-detail-delivery-time-1", $fixed_delivery_time); ?>
															</div>
															<div id="container-order-detail-delivery-time-2" style="display: none;">
																<?php generateForm("timepicker", "Delivery Time", "e-form-order-detail-delivery-time-2", $delivery_detail['delivery_time']); ?>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="widget-body">
												<div class="widget-main">

												</div>
											</div>
										</div>
									<?php endif ?>

									<?php if (isset( $cf ) && count($cf)): ?>
										<br>
										<div class="widget-box">
											<div class="widget-header widget-header-small header-color-blue">
												<h5 class="bigger lighter">
													Other Detail
												</h5>
											</div>

											<div class="widget-body">
												<div class="widget-main" id="other-fields">
													<div class="form-horizontal">
														<?php foreach ($cf as $key => $value): ?>
															<?php echo $value; ?>
														<?php endforeach ?>
													</div>
												</div>
											</div>
										</div>
									<?php endif ?>
								</div>
							</div>

							<br>
							<div class="row-fluid">
								<div class="span12">
									<?php if (!empty($products_ordered)): ?>
										<div>
											<div class="table-header">
												Products Ordered
											</div>
											<table id="orders_details" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th style="width:15%;">Product Image</th>
														<th>Product Name</th>
														<th style="width:10%;" >Quanity</th>
														<th style="width:10%;">Price</th>
														<th style="width:10%;">Total</th>
													</tr>
												</thead>
												<tbody>
													<?php if (!empty($products_ordered)): ?>
														<?php foreach ($products_ordered as $key => $product): ?>
															<?php
															$default_image = FRONTEND_URL . '/images/uploads/default.png';
															$image = $default_image;
															if ($product['image_url']!= '') $image = FRONTEND_URL . $product['image_url'];
															// $image  = str_replace('/images/', '/thumbnails/78x66/', $image);
															$product_description = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $product['product_description']);
															?>
															<tr>
																<td>
																	<div class="text-center">
																		<img src="<?php echo $image; ?>" alt="<?php echo $default_image; ?>" style="width: 100%; max-height: 200px;">
																	</div>
																</td>
																<td>
																	<?php echo $product['item_name'];?>
																	<?php if ($product_description != null && $product_description != ""): ?>                                                            
																	<div class="product-description-container">
																		<small><a href="javascript:void(0)" class="product-description-toggle">View Description</a></small>
																		<div class="product-item-description well well-small" style="display: none;"><?php echo $product_description; ?></div>
																	</div>
																<?php endif ?>
																</td>
																<td><div class="text-right"><?php echo $currency . number_format($product['quantity'], 2, '.',','); ?></div></td>
																<td><div class="text-right"><?php echo $currency . number_format($product['price'], 2, '.',','); ?></div></td>
																<td><div class="text-right"><?php echo $currency . number_format($product['quantity'] * $product['price'], 2, '.', ','); ?></div></td>
															</tr>
														<?php endforeach ?>
													<?php endif ?>
												</tbody>
											</table>
											<button class="btn btn-info hide" onclick="add_additional_order(<?php echo $order['id']; ?>);" style="margin:1% 0 1% 0;">Add new Item</button>
										</div>
									<?php endif ?>

									<?php if (!empty($additional_products)): ?>
										<div>
											<div class="table-header">
												Additional Orders
											</div>

											<table id="additional_order_details" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center" style="width:5%;">
															<label>
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th>
														<th style="width:15%;">Product Image</th>
														<th>Product Name</th>

														<th style="width:10%;" >Quanity</th>
														<th style="width:11%;">Price</th>
														<th>Time Stamp</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($additional_products as $key => $product): ?>
														<?php 
															$default_image = FRONTEND_URL . '/images/uploads/default.png';
															$image = $product['featured_image_url'] != "" ? $product['featured_image_url'] : $default_image; 
														?>
														<?php if ($product['product_id'] == 0): ?>
															<tr>
																<td class="center">
																	<label>
																		<input type="checkbox" class="ace" />
																		<span class="lbl"></span>
																	</label>
																</td>
																<td>
																	<img src="<?php echo str_replace('/images/', '/thumbnails/78x66/', $image); ?>" width="72" height="52" alt="<?php echo $default_image; ?>">
																</td>
																<td><?php echo $product['new_product_name'];?></td>
																<td><?php echo $product['quantity']; ?></td>
																<td><?php echo $product['price']; ?></td>
																<td><?php echo $product['order_timestamp']; ?></td>
																<td>
																	<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																		<button class="btn btn-minier btn-info" data-rel="tooltip" data-placement="top" title="Edit" onclick="new_edit_additional_order(<?php echo $product['id']; ?>,'<?php echo $product['new_product_name']; ?>',<?php echo $product['quantity']; ?>,<?php echo $product['price']; ?>)"><i class="icon-edit bigger-120"></i></button>
																		<button class="btn btn-minier btn-danger" data-rel="tooltip" data-placement="top" title="Delete" onclick="new_delete_additional_order(<?php echo $product['id']; ?>,'<?php echo $product['new_product_name']; ?>',<?php echo $product['quantity']; ?>,<?php echo $product['price']; ?>)"><i class="icon-trash bigger-120"></i></button>
																	</div>
																</td>
															</tr>
														<?php else: ?>
															<tr>
																<td class="center">
																	<label>
																		<input type="checkbox" class="ace" />
																		<span class="lbl"></span>
																	</label>
																</td>
																<td>
																	<img src="<?php echo str_replace('/images/', '/thumbnails/78x66/', $image); ?>" width="72" height="52" alt="">
																</td>
																<td><?php echo $product['product_name'];?></td>
																<td><?php echo $product['quantity']; ?></td>
																<td><?php echo $product['price']; ?></td>
																<td><?php echo $product['order_timestamp']; ?></td>
																<td>
																	<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																		<button class="btn btn-minier btn-info" data-rel="tooltip" data-placement="top" title="Edit" onclick="edit_additional_order(<?php echo $product['id']; ?>,<?php echo $product['product_id']; ?>,<?php echo $product['quantity']; ?>,<?php echo $product['price']; ?>)"><i class="icon-edit bigger-120"></i></button>
																		<button class="btn btn-minier btn-danger" data-rel="tooltip" data-placement="top" title="Delete" onclick="delete_additional_order(<?php echo $product['id']; ?>,<?php echo $product['product_id']; ?>,<?php echo $product['quantity']; ?>,<?php echo $product['price']; ?>)"><i class="icon-trash bigger-120"></i></button>
																	</div>
																</td>
															</tr>
														<?php endif ?>
													<?php endforeach ?>
												</tbody>
											</table>
										</div>
									<?php endif ?>
								</div>
							</div>

							<div class="row-fluid">
								<div class="span6">
									<div class="row-fluid">
										<div class="span12 label label-large label-info arrowed-in arrowed-right">
											<b>Shipping Info</b>
										</div>
									</div>

									<div class="row-fluid">
										<ul class="list-unstyled spaced">
											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_name">Shipping Name:</label>
												<input type="text" id="shipping_name" name="shipping_name" value="<?php echo $order['shipping_name']; ?>" readonly >
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_address">Shipping Address:</label>
												<input type="text" id="shipping_address" name="shipping_address" value="<?php echo $order['shipping_address']; ?>"  readonly>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_address_line_2">Shipping Address Line 2:</label>
												<input type="text" id="shipping_address_line_2" name="shipping_address_line_2" value="<?php echo $order['shipping_address_line_2']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_city">City:</label>
												<input type="text" id="shipping_city" name="shipping_city" value="<?php echo $order['shipping_city']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_postal">Postal/Zipcode:</label>
												<input type="text" id="shipping_postal" name="shipping_postal" value="<?php echo $order['shipping_postal']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_state">State:</label>
												<input type="text" id="shipping_state" name="shipping_state" value="<?php echo $order['shipping_state']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_country">Country:</label>
												<input type="text" id="shipping_country" name="shipping_country" value="<?php echo $order['shipping_country']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="phone">Phone:</label>
												<input type="text" id="phone" name="phone" value="<?php echo $order['phone']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="payment_info">Payment Info:</label>
												<select id="payment_info" disabled="disabled">
													<?php foreach ($payment_info as $key => $value): ?>
														<?php if ($order['payment_mode_id'] == $value['id']): ?>
															<option value="<?php echo $value['id']; ?>" selected><?php echo $value['display_name']; ?></option>
														<?php else: ?>
															<option value="<?php echo $value['id']; ?>" ><?php echo $value['display_name']; ?></option>
														<?php endif ?>
													<?php endforeach ?>
												</select> 
											</li>
										</ul>
									</div>
								</div><!-- /span -->

								<div class="span6">
									<div class="row">
										<div class="span12 label label-large label-success arrowed-in arrowed-right">
											<b>Billing Info</b>
										</div>
									</div>

									<div>
										<ul class="list-unstyled  spaced">
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_name">Billing Name:</label>
												<input type="text" id="billing_name" name="billing_name" value="<?php echo $order['billing_name']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_address">Billing Address:</label>
												<input type="text" id="billing_address" name="billing_address" value="<?php echo $order['billing_address']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_address_line_2">Billing Address Line 2:</label>
												<input type="text" id="billing_address_line_2" name="billing_address_line_2" value="<?php echo $order['billing_address_line_2']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_city">City:</label>
												<input type="text" id="billing_city" name="billing_city" value="<?php echo $order['billing_city']; ?>" readonly
											</li>

											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_postal">Postal/Zipcode:</label>
												<input type="text" id="billing_postal" name="billing_postal" value="<?php echo $order['billing_postal']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_state">State:</label>
												<input type="text" id="billing_state" name="billing_state" value="<?php echo $order['billing_state']; ?>" readonly>
											</li>

											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_country">Country:</label>
												<input type="text" id="billing_country" name="billing_country" value="<?php echo $order['billing_country']; ?>" readonly>
											</li>
										</ul>
									</div>
								</div><!-- /span -->
							</div><!-- row -->

							<div class="row-fluid">
								<div class="span12">
									<div>
										<div class="table-header">
											Order Payments
										</div>
										<table id="additional_order_payments" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th style="width:25%;">Payment Referral Number</th>
													<th>Payment Mode</th>
													<th style="width:10%;" >Amount</th>
													<th style="width:11%;">Status</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($order_payments as $key => $order_payment): ?>
													<tr>                         
														<td><?php echo $order_payment['payment_ref_number'];?></td>
														<td><?php echo $order_payment['display_name'];?></td>
														<td><?php echo $currency . number_format((double)$order_payment['payment_total_amount'], 2, '.',','); ?></td>
														<td><?php echo $order_payment['status_name']; ?></td>
														<td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group"><button class="btn btn-minier btn-info" onclick="modal_edit_payment(<?php echo $order_payment['id'];?>,'<?php echo $order_payment['payment_ref_number']; ?>', <?php echo $order_payment['payment_mode_id']; ?>,'<?php echo $order_payment['payment_total_amount']?>', <?php echo $order_payment['payment_status']; ?>); return false;" data-rel="tooltip" data-placement="top" title="Edit"><i class="icon-edit bigger-120"></i></button><button class="btn btn-minier btn-danger" onclick="delete_payment_modal(<?php echo $order_payment['id']; ?>); return false;" data-rel="tooltip" data-placement="top" title="Delete"><i class="icon-trash bigger-120"></i></button></div></td>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
										<button class="btn btn-info" onclick="modal_new_payment(); return false;" style="margin:0 0 1% 0;" style="display: none;" >Add New Payment</button>
									</div>
								</div>
							</div>

							<hr>

							<div class="row-fluid">
								<div class="span12">
									<div class="text-center">
										<div class="controls">
											<button class="btn" onclick="backView(); return false;">Back</button>
											&nbsp;
											<button class="btn btn-success" id="save_order" onclick="return false;">Save</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div><!--PAGE CONTENT END-->
		<div id="editor"></div>
	</div><!--MAIN CONTENT END-->
	<!--MODAL-->
	<div class="hide" id="modal_container">
		<div id="add_additional_order" class="modal fade" role="dialog">
			<div class="modal-dialog" >

				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="table-header">
							<span class="modal_header">Add new Product</span>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								<span class="white">×</span>
							</button>
						</div>
					</div>

					<div class="modal-body no-overflow" style="height:325px;">
						<div class="tabbable">
							<ul class="nav nav-tabs" id="myTab">
								<li class="active" id="add_order_tab">
									<a data-toggle="tab" href="#add_order" id="add_order_title">
										Add Additional Order
									</a>
								</li>

								<li class="" id="add_new_product_tab">
									<a data-toggle="tab" href="#add_new_product_modal" id="add_product_title">
										Add New Product
									</a>
								</li>
							</ul>

							<div class="tab-content" style="height:250px; overflow:hidden;">
								<div id="add_order" class="tab-pane active">
									<form class="form-horizontal" role="form">
										<input type="hidden" id="product_price_hidden">
										<div class="row">
											<input type="hidden" id="products_hidden" value="<?php echo base64_encode(json_encode($products)); ?>" />
											<div class="form-group">
												<div class="span2">
													<label class="control-label"> Product Name: </label>
												</div>
												<div class="span3">
													<select id="product_name" class="form_select">
														<option value="-1"> Select Product <option>
															<?php foreach ($products as $key => $product) { ?>
															<option value="<?php echo $product['id']; ?>" data-value="<?php echo $product['price']; ?>"><?php echo $product['product_name']; ?></option>
															<?php }?>
														</select>
													</div>
												</div>
											</div>
											<div class="row" id="image_row" style="height:145px;">
												<div class="form-group">
													<div class="span2">

													</div>
													<div class="span3" >
													</br>
													<img id="product_image"/>
												</div>
											</div>
										</div>
										<?php  $system = check_system('system_type');
										if($system['option_value'] == 'ECOMMERCE') {?>

										<div class="row">
											<div class="form-group">
												<div class="span2">
													<label class="control-label"> Price: </label>
												</div>
												<div class="span3">
													<input type="text" class="input input-small" value="1" id="product_price" />
												</div>
											</div>
										</div>
										<?php } ?> 
									</br>
									<div class="row">
										<div class="form-group">
											<div class="span2">
												<label class="control-label"> Quantity: </label>
											</div>
											<div class="span3">
												<input type="text" class="input input-small" value="1" id="product_quantity" />
											</div>
										</div>
									</div>          
								</form>

							</div>

							<div id="add_new_product_modal" class="tab-pane">
								<form class="form-horizontal" role="form">
									<input type="hidden" id="new_product_price_hidden">
									<div class="row">
										<div class="form-group">
											<div class="span2">
												<label class="control-label"> Product Name: </label>
											</div>
											<div class="span3">
												<input type="text" class="input" id="new_product_name" />
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="form-group">
											<div class="span2">
												<label class="control-label"> Quantity: </label>
											</div>
											<div class="span3">
												<input type="text" class="input input-small" id="new_product_quantity" />
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="form-group">
											<div class="span2">
												<label class="control-label"> Price: </label>
											</div>
											<div class="span3">
												<input type="text" class="input" id="new_product_price" />
											</div>
										</div>
									</div>           
								</form>
							</div>

						</div>
					</div>
				</div><!-- /.modal-content -->
				<div class="modal-footer no-margin-top">
					<div class="pull-left">
						<label><strong>Total: <span id="total_price">0</span></strong></label>
					</div>
					<button class="btn btn-sm btn-info pull-right" id="button_additional_order" onclick="click_additional_product(<?php echo $order['id']; ?>)">
						<i class="icon-plus" id="icon_addtional_orders"></i>
						Add to Product 
					</button>
				</div>
			</div><!-- /.modal-dialog -->
		</div>
	</div>

	<div id="delete_order" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header error">
						Delete Order
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">×</span>
						</button>
					</div>
				</div>

				<div class="modal-body">
					<h5 class="warning">Are you sure to cancel this order ?</h5>       
				</div><!-- /.modal-content -->

				<div class="modal-footer no-margin-top">
					<button class="btn btn-sm btn-info " data-dismiss="modal">No</buttton>
						<button class="btn btn-sm btn-danger pull-right" onclick="cancel_order(); return false;">
							<i class="icon-trash"></i>
							Confirm
						</button>
					</div>

				</div>
			</div>
		</div><!-- #dialog-confirm -->

		<div id="modal_new_payment" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="table-header error">
							Add New Payment
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								<span class="white">×</span>
							</button>
						</div>
					</div>

					<div class="modal-body">
						<input type="hidden" value="0" id="hidden_payment_id">
						<div class="row">
							<div class="form-group">
								<div class="span2">
									<label class="control-label"> Payment Referral Num: </label>
								</div>
								<div class="span3">
									<input type="text" id="new_payment_referral_num">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="span2">
									<label class="control-label"> Payemnt Type: </label>
								</div>
								<div class="span3">
									<select id="new_payment_type">
										<?php foreach ($payment_info as $key => $value) {
											?>
											<option value="<?php echo $value['id']; ?>"><?php echo $value['display_name'];?></option>
											<?php } ?>

										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<div class="span2">
										<label class="control-label"> Amount: </label>
									</div>
									<div class="span3">
										<input type="text" id="new_amount">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<div class="span2">
										<label class="control-label"> Payment Status: </label>
									</div>
									<div class="span3">
										<select id="new_payment_status">
											<?php foreach ($payment_statuses as $key => $value) { ?>
											<option value="<?php echo $value['id']; ?>" ><?php echo $value['status_name'];?></option>
											<?php }?>
										</select>
									</div>
								</div>
							</div>

						</div><!-- /.modal-content -->

						<div class="modal-footer no-margin-top">
							<button class="btn btn-sm btn-info pull-right" onclick="add_new_payment(); return false;">
								Save Payment
							</button>
						</div>

					</div>
				</div>
			</div>
			<!-- #dialog-confirm -->
			<div id="modal_delete_payment" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header no-padding">
							<div class="table-header error">
								Delete Order
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									<span class="white">×</span>
								</button>
							</div>
						</div>

						<div class="modal-body">
							<input type="hidden" value="0" id="delete_hidden_payment_id">
							<h5 class="warning">Are you sure to cancel this Payment ?</h5>       
						</div><!-- /.modal-content -->

						<div class="modal-footer no-margin-top">
							<button class="btn btn-sm btn-danger pull-right" onclick="delete_payment(); return false;">
								<i class="icon-trash"></i>
								Confirm
							</button>
						</div>

					</div>
				</div>
			</div>
		</div>