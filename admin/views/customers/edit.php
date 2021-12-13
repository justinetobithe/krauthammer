<div class="main-content">
	<div class="page-content">
		<div class="hide">
			<input type="hidden" id="action" value="edit">
		</div>
		<div class="widget-box transparent invoice-box">
			<div class="widget-header widget-header-large">
				<h3 class="grey lighter pull-left position-relative">
					<i class="icon-user green"></i>
					Edit Customer
				</h3>

				<div class="widget-toolbar hidden-480">
					<a href="#">
						<i class="icon-print"></i>
					</a>
				</div>
			</div>
			<div id="result"></div>
			<div class="widget-body">
				<div class="widget-main">

					<form class="form-horizontal" id="customer_form" action="<?php echo URL;?>customers/update_customer/" enctype="multipart/form-data" method="post" onsubmit="return validate()">
						<div class="row-fluid">
							<div class="span12">
								<div class="hide">
									<input type="hidden" value="<?php echo $customer['id']; ?>" name="id">
									<input type="hidden" value="<?php echo $customer['salt']; ?>" name="salt">
								</div>

								<div class="widget-box">
									<div class="widget-header header-color-blue">
										<h5>Customer Details</h5>
									</div>
									<div class="widget-body">
										<div class="widget-main ">
											<div class="row-fluid">
												<div class="span12">
													<div id="alert_customer_details"></div>
													<div class="control-group">
														<label class="control-label" for="customer_email"><span class="red">*</span> Email:</label>
														<div class="controls">
															<input type="text" id="customer_email" name="email" class="span10" value="<?php echo $customer['email']; ?>">
															<input type="hidden" id="hdn_customer_email" class="span10" value="<?php echo $customer['email']; ?>">           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="customer_password"><span class="red">*</span> Password:</label>
														<div class="controls">
															<input type="password" id="customer_password" name="password" class="input-xlarge" value="*****">           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="con_password"><span class="red">*</span> Confirm Password:</label>
														<div class="controls">
															<input type="password" id="con_password" class="input-xlarge" value="*****">           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="customer_name_first">First Name:</label>
														<div class="controls">
															<input type="text" id="customer_name_first" name = "name" class="span10" value="<?php echo $customer['firstname']; ?>">           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="customer_name_last">Surname Name:</label>
														<div class="controls">
															<input type="text" id="customer_name_last" name = "lastname" class="span10" value="<?php echo $customer['lastname']; ?>">           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="customer_contact">Contact Number:</label>
														<div class="controls">
															<input type="text" id="customer_contact" name = "contact" class="span10" value="<?php echo $customer['contact_number']; ?>">           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="customer_company">Company:</label>
														<div class="controls">
															<input type="text" id="customer_company" name = "company" class="span10" value="<?php echo $customer['company_name']; ?>">           
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<br>
								
								<div class="widget-box">
									<div class="widget-header header-color-blue">
										<h5>Shipping information</h5>
									</div>
									<div class="widget-body">
										<div class="widget-main ">
											<div class="row-fluid hide">
												<div class="span12">
													<div id="alert_customer_billing"></div>
												</div>
											</div>
											<div class="row-fluid">
												<!-- BILLING CONTAINER -->
												<div class="span6">
													<div class="control-group">
														<div class="span12 label label-large label-success arrowed-in arrowed-right">
															<b>Billing Info</b>
														</div>
													</div>
													<br>

													<div class="control-group">
														<label class="control-label"></label>
														<div class="controls"></div>
													</div>
													<div class="control-group">
														<label class="control-label" for="billing_address"> Address:</label>
														<div class="controls">
															<textarea id="billing_address" name="billing_address" rows="5" style="width:300px;"><?php echo $customer['billing_address']; ?></textarea>           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="billing_address_2"> Address 2:</label>
														<div class="controls">
															<textarea id="billing_address_2" name="billing_address_2" rows="5" style="width:300px;"><?php echo $customer['billing_address_2']; ?></textarea>           
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="billing_postal_code">Postal Code:</label>
														<div class="controls">
															<input type="text" id="billing_postal_code" name="billing_postal_code" class="input-xlarge" value="<?php if($customer['billing_postal_code'] != 0) {echo $customer['billing_postal_code'];} ?>" style="width:300px;">
														</div>
													</div>
													<input type="hidden" id="billing_country_val" value="<?php echo $customer['billing_country'];?>">
													<div class="control-group">
														<label class="control-label" for="billing_country">Country:</label>
														<div class="controls">
															<select id="billing_country" name="billing_country">
																<?php foreach ($countries as $key => $value): ?>
																	<?php if ($key == ''): ?>
																		<?php foreach ($value as $k => $v): ?>
																			<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" <?php if($customer['billing_country'] == $v->value) echo "selected"; ?> ><?php echo $v->name; ?></option>
																		<?php endforeach ?>
																	<?php elseif ($key == 'others'): ?>
																		<optgroup label="Other countries">
																			<?php foreach ($value as $k => $v): ?>
																				<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" <?php if($customer['billing_country'] == $v->value) echo "selected"; ?> ><?php echo $v->name; ?></option>
																			<?php endforeach ?>
																		</optgroup>
																	<?php endif ?>
																<?php endforeach ?>
															</select>
														</div>
													</div>
													<input type="hidden" id="billing_city_val" value="<?php echo $customer['billing_city'];?>">
													<div class="control-group">
														<label class="control-label" for="billing_city">City:</label>
														<div class="controls">
															<input type="text" id="billing_city" name = "billing_city" class="input-xlarge" value="<?php echo $customer['billing_city']; ?>" style="width:300px;">
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="billing_state_region">State / Region:</label>
														<div class="controls">
															<input type="text" id="billing_state_region" name = "billing_state_region" class="input-xlarge" value="<?php echo $customer['billing_state']; ?>" style="width:300px;">
														</div>
													</div>

													<div class="control-group">
														<label class="control-label" for="billing_email">Email:</label>
														<div class="controls">
															<input type="text" id="billing_email" name = "billing_email" class="input-xlarge" value="<?php echo isset($customer['billing_email']) ? $customer['billing_email'] : ""; ?>" style="width:300px;">
														</div>
													</div>
													<div class="control-group">
														<label class="control-label" for="billing_phone">Phone:</label>
														<div class="controls">
															<input type="text" id="billing_phone" name = "billing_phone" class="input-xlarge" value="<?php echo isset($customer['billing_phone']) ? $customer['billing_phone'] : ""; ?>" style="width:300px;">
														</div>
													</div>
												</div>
												<!-- SHIPPING CONTAINER -->
												<div class="span6">
													<div class="row-fluid">
														<div class="span12 label label-large label-info arrowed-in arrowed-right">
															<b>Shipping Info</b>
														</div>
													</div>
													<br>

													<div id="alert_customer_shipping"></div>

													<div class="control-group">
														<div class="controls">
															<label class="inline">
																<input type="checkbox" id="different_shipping_address" class="ace" <?php echo $customer['different_shipping_address']=='Y' ? "checked" : ""; ?> name="different_shipping_address">
																<span class="lbl"> Different Shipping Address</span>
															</label>
														</div>
													</div>

													<div id="shipping-info-container" style="display: none;">
														<div class="control-group">
															<label class="control-label" for="shipping_address"> Address:</label>
															<div class="controls">
																<textarea id="shipping_address" name="shipping_address" rows="5" style="width:300px;"><?php echo $customer['shipping_address']; ?></textarea>           
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="shipping_address_2"> Address 2:</label>
															<div class="controls">
																<textarea id="shipping_address_2" name="shipping_address_2" rows="5" style="width:300px;"><?php echo $customer['shipping_address_2']; ?></textarea>           
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="shipping_postal_code">Postal Code:</label>
															<div class="controls">
																<input type="text" id="shipping_postal_code" name="shipping_postal_code" class="input-xlarge" value="<?php if($customer['shipping_postal_code'] != 0) { echo $customer['shipping_postal_code']; } ?>" style="width: 300px;">
															</div>
														</div>
														<input type="hidden" id="shipping_country_val" value="<?php echo $customer['shipping_country'];?>">
														<div class="control-group">
															<label class="control-label" for="shipping_country">Country:</label>
															<div class="controls">

																<select id="shipping_country" class="" name="shipping_country">
																	<?php foreach ($countries as $key => $value): ?>
																		<?php if ($key == ''): ?>
																			<?php foreach ($value as $k => $v): ?>
																				<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" <?php if($customer['shipping_country'] == $v->value) echo "selected"; ?> ><?php echo $v->name; ?></option>
																			<?php endforeach ?>
																		<?php elseif ($key == 'others'): ?>
																			<optgroup label="Other countries">
																				<?php foreach ($value as $k => $v): ?>
																					<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>"  <?php if($customer['shipping_country'] == $v->value) echo "selected"; ?>><?php echo $v->name; ?></option>
																				<?php endforeach ?>
																			</optgroup>
																		<?php endif ?>
																	<?php endforeach ?>
																</select>

															</div>
														</div>
														<input type="hidden" id="shipping_city_val" value="<?php echo $customer['shipping_city'];?>">
														<div class="control-group">
															<label class="control-label" for="shipping_city">City:</label>
															<div class="controls">
																<input type="text" id="shipping_city" name = "shipping_city" class="input-xlarge" value="<?php echo $customer['shipping_city']; ?>" style="width: 300px;">
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="shipping_state_region">State / Region:</label>
															<div class="controls">
																<input type="text" id="shipping_state_region" name = "shipping_state_region" class="input-xlarge" value="<?php echo $customer['shipping_state']; ?>" style="width: 300px;">
															</div>
														</div>

														<div class="control-group">
															<label class="control-label" for="shipping_email">Email:</label>
															<div class="controls">
																<input type="text" id="shipping_email" name = "shipping_email" class="input-xlarge" value="<?php echo isset($customer['shipping_email']) ? $customer['shipping_email'] : ""; ?>" style="width: 300px;">
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="shipping_phone">Phone:</label>
															<div class="controls">
																<input type="text" id="shipping_phone" name = "shipping_phone" class="input-xlarge" value="<?php echo isset($customer['shipping_phone']) ? $customer['shipping_phone'] : ""; ?>" style="width: 300px;">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<br>

								<div class="widget-box">
									<div class="widget-header header-color-blue">
										<h5>Custom Fields</h5>
									</div>
									<div class="widget-body">
										<div class="widget-main ">
											<div class="row-fluid">
												<!-- Custom Fields -->
												<div class="span8">
													<div class="row-fluid" id="custom-fields-container">
														<?php foreach ($cf as $key => $value): ?>
															<?php echo $value; ?>
														<?php endforeach ?>
													</div>
												</div>
												<!-- Others -->
												<div class="span4">
													<div class="row-fluid">
														<div class="span12 label label-large label-success arrowed-in arrowed-in-right text-center">
															<b>Notes Here</b>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div><!--PAGE Row END-->

						<br>
						<div class="row-fluid">
							<div class="span12">
								<div class="control-group">
									<div class="controls">
										<button class="btn btn-success">Save</button> 
										<button class="btn" onclick="back(); return false;">Cancel</button> 
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div><!--PAGE CONTENT END-->

</div>

<div id="loading" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="modal-body">
					Wait for a moment saving customer..
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
