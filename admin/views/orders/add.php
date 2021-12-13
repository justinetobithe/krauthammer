<?php
$discount = get_system_option('ecommerce-self-collection-discount');
$normal_delivery_time = get_system_option('ecommerce-normal-delivery-time');
$normal_delivery_time = explode("\n", trim($normal_delivery_time));
$fixed_delivery_time = array();
foreach ($normal_delivery_time as $key => $value) {
	$fixed_delivery_time[$value] = array("value"=>$value, "selected"=>false);
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
					<input type="text" id="<?php echo $id_class; ?>" name="name" class="input-xxlarge" value="<?php echo $values; ?>">
				</div>
			</div>
			<?php
			break;
		case 'textarea':
			?>
			<div class="control-group">
				<label class="control-label" for="<?php echo $id_class; ?>"><?php echo $label; ?></label>
				<div class="controls">
					<textarea id="<?php echo $id_class; ?>" class="input input-xxlarge" cols="30" rows="5"><?php echo $values; ?></textarea>
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
					<select id="<?php echo $id_class; ?>">
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
            <input type="text" class="input input-small timepicker" id="<?php echo $id_class; ?>" value="<?php echo $values; ?>" >
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
            <input type="text" class="input input-small datepicker" id="<?php echo $id_class; ?>" value="<?php echo $values; ?>">
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
<div class="main-content">
	<div class="page-content">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box transparent invoice-box">
					<div class="widget-header widget-header-large">
						<h3 class="grey lighter pull-left position-relative">
							<i class="icon-leaf green"></i>
							Create New Order
						</h3>

						<div class="widget-toolbar no-border invoice-info">
							<span class="invoice-info-label">Invoice:</span>
							<span class="red">#<?php echo $invoice_number; ?></span>

							<br />
							<span class="invoice-info-label">Date:</span>
							<span class="blue"><?php echo format_date(date("Y-m-d h:i:sa")); ?></span>

						</div>
					</div>
					<div id="result"></div>
					<div class="widget-body">
						<div class="widget-main">

							<div class="table-responsive">

								<input type="hidden" id="action" value="add_orders">
								<input type="hidden" value="<?php echo date("Y-m-d h:i:s"); ?>" id="order_timestamp">
								<input type="hidden" value="<?php echo $invoice_number; ?>" id="invoice_number">
								<div class="row-fluid">

									<form class="form-horizontal">
										&nbsp;
										<div class="widget-box">
											<div class="widget-header header-color-blue">
												<h5 class="bigger lighter">

													Customer Details
												</h5>


											</div>

											<div class="widget-body">
												<input type="hidden" id="hidden_id" value="0">
												<div class="widget-main">
													<div id="alertOrder"></div>
													<div class="control-group">
														<label class="control-label" for="first_name">First Name:</label>
														<div class="controls">
															<input type="text" id="first_name" name="name" class="input-xxlarge" >     
															<a href="javascript:void(0)" class="btn btn-primary btn-mini" id="modal_btn_current_customer"><i class="icon icon-plus"></i> Current Customer</a>      
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="last_name">Last Name:</label>
														<div class="controls">
															<input type="text" id="last_name" name="name" class="input-xxlarge" >           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="email">Email:</label>
														<div class="controls">
															<input type="text" id="email" name="email" class="input-xxlarge">           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="phone">Contact Number:</label>
														<div class="controls">
															<input type="text"  id="phone" name="contact_num" class="input-xxlarge">           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="message">Message:</label>
														<div class="controls">
															<textarea id="message" rows="8" name="message" style="width:77%;"></textarea>     
														</div>
													</div>  
												</div>
											</div>
										</div>

										<?php if (get_system_option('ecommerce-enable-delivery-detail') == "Y"): ?>
										<div class="widget-box">
											<div class="widget-header widget-header-small header-color-green">
												<h5 class="bigger lighter">
													Delivery Detail
												</h5>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<div id="e-form-container-order-delivery-detail">
														<div class="control-group">
															<label class="control-label" for="">Mode of Delivery</label>
															<div class="controls">
																<label>
	                                <input name="e-form-order-detail-mode" type="radio" class="ace" value="self-collection" checked="checked">
	                                <span class="lbl"> Self - Collection (<?php echo $discount; ?>% - discount)</span>
	                              </label>
																<label>
	                                <input name="e-form-order-detail-mode" type="radio" class="ace" value="delivery-to-home">
	                                <span class="lbl"> Delivery to Home and Others</span>
	                              </label>
															</div>
														</div>

														<div id="container-order-detail-self-collection" style="display: none;">
															<?php generateForm("datepicker", "Collection Date", "e-form-order-detail-collection-date"); ?>
															<?php generateForm("timepicker", "Collection Time", "e-form-order-detail-collection-time"); ?>
														</div>
														<div id="container-order-detail-home-delivery" style="display: none;">
															<?php generateForm("textarea", "Delivery Address", "e-form-order-detail-delivery-address"); ?>
															<?php generateForm("text", "Postal Code", "e-form-order-detail-postal-code"); ?>
															<?php generateForm("datepicker", "Delivery Date", "e-form-order-detail-delivery-date"); ?>

															<div class="control-group">
																<label class="control-label" for="">Type of Delivery</label>
																<div class="controls">
																	<label>
	                                  <input name="e-form-order-detail-delivery-type" type="radio" class="ace" value="normal" checked="checked">
	                                  <span class="lbl"> Normal Delivery Time</span>
	                                </label>
																	<label>
	                                  <input name="e-form-order-detail-delivery-type" type="radio" class="ace" value="express">
	                                  <span class="lbl"> Express Delivery Time</span>
	                                </label>
																</div>
															</div>

															<div id="container-order-detail-delivery-time-1" style="display: none;">
																<?php generateForm("select", "Delivery Time", "e-form-order-detail-delivery-time-1", $fixed_delivery_time); ?>
															</div>
															<div id="container-order-detail-delivery-time-2" style="display: none;">
																<?php generateForm("timepicker", "Delivery Time", "e-form-order-detail-delivery-time-2"); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php endif ?>


										<?php if (isset( $cf ) && count($cf)): ?>
											<div class="widget-box">
												<div class="widget-header header-color-blue">
													<h5 class="bigger lighter">
														Other Detail
													</h5>
												</div>

												<div class="widget-body">
													<div class="widget-main" id="other-fields">
														<?php foreach ($cf as $key => $value): ?>
															<?php echo $value; ?>
														<?php endforeach ?>
													</div>
												</div>
											</div>
										<?php endif ?>
									</form>
								</div><!--PAGE Row END-->
							</div>


							<div class="row-fluid">
							<?php if (get_system_option('ecommerce-shipping-detail-enable') == "Y"): ?>
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
												<input type="text" id="shipping_name" name="shipping_name" >
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_address">Shipping Address:</label>
												<input type="text" id="shipping_address" name="shipping_address" >

											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_address_line_2">Shipping Address Line 2:</label>
												<input type="text" id="shipping_address_line_2" name="shipping_address_line_2">

											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_city">City:</label>
												<input type="text" id="shipping_city" name="shipping_city" >

											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_postal">Postal/Zipcode:</label>
												<input type="text" id="shipping_postal" name="shipping_postal" >

											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_state">State:</label>
												<input type="text" id="shipping_state" name="shipping_state" >

											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_country">Country:</label>

												<select id="shipping_country" class="form_select" name="shipping_country">
													<?php foreach ($countries as $key => $value): ?>
														<?php if ($key == ''): ?>
															<?php foreach ($value as $k => $v): ?>
																<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" ><?php echo $v->name; ?></option>
															<?php endforeach ?>
														<?php elseif ($key == 'others'): ?>
															<optgroup label="Other countries">
																<?php foreach ($value as $k => $v): ?>
																	<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" ><?php echo $v->name; ?></option>
																<?php endforeach ?>
															</optgroup>
														<?php endif ?>
													<?php endforeach ?>
												</select>
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_email">Email:</label>
												<input type="text" id="shipping_email" name="shipping_email" >
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="shipping_phone">Phone:</label>
												<input type="text" id="shipping_phone" name="shipping_phone" >
											</li>

											<li>
												<i class="icon-caret-right blue"></i>
												<label for="payment_info">Payment Info:</label>
												<select id="payment_info">
													<?php
													foreach ($payment_info as $key => $value) {

														?>
														<option value="<?php echo $value['id']; ?>" ><?php echo $value['display_name']; ?></option>
														<?php   
													}
													?>
												</select> 
											</li>
										</ul>
									</div>
								</div><!-- /span -->
							<?php endif ?>

							<?php if (get_system_option('ecommerce-billing-detail-enable') == "Y"): ?>
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
												<input type="text" id="billing_name" name="billing_name" >

											</li>
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_address">Billing Address:</label>
												<input type="text" id="billing_address" name="billing_address" >

											</li>
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_address_line_2">Billing Address Line 2:</label>
												<input type="text" id="billing_address_line_2" name="billing_address_line_2" >

											</li>
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_city">City:</label>
												<input type="text" id="billing_city" name="billing_city" >

											</li>
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_postal">Postal/Zipcode:</label>
												<input type="text" id="billing_postal" name="billing_postal" >

											</li>
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_state">State:</label>
												<input type="text" id="billing_state" name="billing_state" >
											</li>
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_country">Country:</label>

												<select id="billing_country" class="form_select" name="billing_country">
													<?php foreach ($countries as $key => $value): ?>
														<?php if ($key == ''): ?>
															<?php foreach ($value as $k => $v): ?>
																<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" ><?php echo $v->name; ?></option>
															<?php endforeach ?>
														<?php elseif ($key == 'others'): ?>
															<optgroup label="Other countries">
																<?php foreach ($value as $k => $v): ?>
																	<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" ><?php echo $v->name; ?></option>
																<?php endforeach ?>
															</optgroup>
														<?php endif ?>
													<?php endforeach ?>
												</select>
											</li>
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_email">Email:</label>
												<input type="text" id="billing_email" name="billing_email" >
											</li>
											<li>
												<i class="icon-caret-right green"></i>
												<label for="billing_phone">Phone:</label>
												<input type="text" id="billing_phone" name="billing_phone" >
											</li>

										</ul>
									</div>
								</div><!-- /span -->
							<?php endif ?>
							</div><!-- row -->

							<hr>

							<div class="row-fluid">
								<div class="span12 text-center">
									<button class="btn" onclick="backView(); return false;"><i class="icon icon-arrow-left"></i> Back</button> &nbsp;
									<button class="btn btn-success" id="save_order" onlick="return false;"><i class="icon icon-save"></i> Save</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--PAGE CONTENT END-->
	</div><!--MAIN CONTENT END-->
	<!--MODAL-->
	<div id="delete" class="modal fade">
		<div class="modal-dialog">

			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						Delete Order
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">×</span>
						</button>
					</div>
				</div>

				<div class="modal-body">

					<div id="delete_msg">
						<h5 class="red"></h5>
					</div>
					<input type="hidden" id="hidden_order_id"/>
				</div><!-- /.modal-content -->
				<div class="modal-footer no-margin-top">
					<button class="btn btn-sm btn-danger pull-right" onclick="deleteOrderModal();">
						<i class="icon-trash"></i>
						Delete
					</button>
				</div>
			</div><!-- /.modal-dialog -->
		</div>
	</div>

	<div id="modal-current-customer" class="modal fade" style="width: 800px; display: none; margin-left: -400px;">
		<div class="modal-dialog modal-large">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header error">
						Current Customers
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">×</span>
						</button>
					</div>
				</div>

				<div class="modal-body">
					<table id="customer-table" class="table table-important table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>Email</th>
								<th>Full Name</th>
								<th>Phone</th>
								<th>Company</th>
								<th>Actions</th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table> 
				</div><!-- /.modal-content -->

				<div class="modal-footer no-margin-top">
					<button class="btn btn-sm btn-info" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div><!-- #dialog-confirm -->

	<div id="modal-current-customer-loading" class="modal fade" style="display: none; ">
		<div class="modal-dialog modal-large">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header error">
						Current Customers Loading Detail...
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">×</span>
						</button>
					</div>
				</div>

				<div class="modal-body">
					<p>Please wait for a moment while retrieving customer detail...</p>
				</div><!-- /.modal-content -->
			</div>
		</div>
	</div><!-- #dialog-confirm -->