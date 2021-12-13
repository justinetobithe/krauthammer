<?php
$fixed_delivery_time = array(
	"7am-9am" => "Between 7am-9am",
	"10am-12pm" => "Between 7am-9am",
	"10am-12pm" => "Between 10am-12pm",
	"1pm-2pm" => "Between 1pm-2pm",
	"2pm-3pm" => "Between 2pm-3pm",
	"3pm-4pm" => "Between 3pm-4pm",
	"4pm-5pm" => "Between 4pm-5pm",
	);
function generateForm($field_type = "", $label="", $id_class="", $collection = array()){
	switch ($field_type) {
		case 'banner':
			$class = $id_class != "" ? $id_class : "label-info";
			?>
			<div class="row-fluid">
				<div class="span6 label label-large <?php echo $class; ?> arrowed-in arrowed-right">
					<b><?php echo $label; ?></b>
				</div>
			</div>
			<?php
			break;
		case 'text':
			?>
			<div class="row-fluid">
				<div class="span4">
					<label for="<?php echo $id_class; ?>" class="text-right">
						<?php echo $label; ?>
					</label>
				</div>
				<div class="span8">
					<input type="text" class="input input-xlarge" id="<?php echo $id_class; ?>">
				</div>
			</div>
			<?php
			break;
		case 'timepicker':
			?>
			<div class="row-fluid">
				<div class="span4">
					<label for="<?php echo $id_class; ?>" class="text-right">
						<?php echo $label; ?>
					</label>
				</div>
				<div class="span8">
					<div class="input-append bootstrap-timepicker">
            <input type="text" class="input input-small timepicker"  id="<?php echo $id_class; ?>" >
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
			<div class="row-fluid">
				<div class="span4">
					<label for="<?php echo $id_class; ?>" class="text-right">
						<?php echo $label; ?>
					</label>
				</div>
				<div class="span8">
					<input type="text" class="input input-xlarge datepicker" id="<?php echo $id_class; ?>">
				</div>
			</div>
			<?php
			break;
		case 'textarea':
			?>
			<div class="row-fluid">
				<div class="span4">
					<label for="<?php echo $id_class; ?>" class="text-right">
						<?php echo $label; ?>
					</label>
				</div>
				<div class="span8">
					<textarea id="<?php echo $id_class; ?>" class="input input-xlarge" cols="30" rows="5"></textarea>
				</div>
			</div>
			<?php
			break;
		case 'switch':
			?>
			<div class="row-fluid">
				<div class="span4">
					<label for="<?php echo $id_class; ?>" class="text-right">
						<?php echo $label; ?>
					</label>
				</div>
				<div class="span8">
					<div class="span3">
						<label>
							<input name="switch-field-1" class="ace ace-switch ace-switch-7" type="checkbox" id="<?php echo $id_class; ?>">
							<span class="lbl"></span>
						</label>
					</div>
				</div>
			</div>
			<?php
			break;
		case 'select':
			?>
			<div class="row-fluid">
				<div class="span4">
					<label for="<?php echo $id_class; ?>" class="text-right">
						<?php echo $label; ?>
					</label>
				</div>
				<div class="span8">
				<select id="<?php echo $id_class; ?>">
				<?php foreach ($collection as $key => $value): ?>
					<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php endforeach ?>
				</select>
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
		<div class="page-header">
			<h1> System Settings </h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="index" />
		<div id="result"></div>
		<div class="row-fluid">
			<div class="span11">
				<!-- PAGE CONTENT BEGINS -->
				<div class="widget-box">	
					<div class="tabbable tabs-left">
						<ul class="nav nav-tabs" id="myTab3">
							<li class="active">
								<a data-toggle="tab" href="#home3">
									<i class="pink icon-cogs bigger-110"></i>
									System Settings
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#module_management">
									<i class="icon-lightbulb"></i>
									Module Management
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#panel-eCommerce">
									<i class="icon-cog"></i>
									eCommerce
								</a>
							</li>
						</ul>

						<div class="tab-content" id="tab_content">
							<div id="home3" class="tab-pane in active">
								<div class="span12">
									<form class="form-horizontal" onsubmit="return false;">
										<div class="control-group">
											<label class="control-label" for="copyright_text">System Type:</label>
											<div class="controls">
												<div class="span7 input-group">
													<select id="system_type" class="chosen-select">
														<option value="CMS" <?php echo $system_type == 'CMS'? 'selected': ''; ?>>CMS</option>
														<option value="ECATALOG" <?php echo $system_type == 'ECATALOG'? 'selected': ''; ?>>ECATALOG</option>
														<option value="ECOMMERCE" <?php echo $system_type == 'ECOMMERCE'? 'selected': ''; ?>>ECOMMERCE</option>
													</select>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="copyright_text">System Modules:</label>
											<div class="controls">
												<div class="span7 input-group">
													<?php $system_modules = getModules(); ?>
													<select id="system_type_modules" class="chosen-select tag-input-style" multiple="multiple">
														<?php foreach ($modules as $key => $value): ?>
															<option value="<?php echo $value['module_name']; ?>"><?php echo $value['module_label']; ?></option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
										</div>
										<hr>
										<div class="control-group">
											<div class="controls">
												<div class="span7 input-group">
													<button id="save_system_settings" class="btn btn-success align-right"> Save </button>
												</div>
											</div>
										</div> 
									</form>
									<br>
									<br>
									<br>
									<br>
									<br>
									<br>
									<br>
									<br>
									<br>
									<br>
								</div> 
							</div>

							<div id="module_management" class="tab-pane">
								<div class="widget-box">
									<form class="form-horizontal" onsubmit="return false;">
										<div class="widget-header widget-header-blue widget-header-flat">
											<h4 class="lighter">Module Permission for User</h4>
										</div>
										<br>
										<div id="alert_for_assign_acess"></div>

										<div class="row-fluid">
											<div class="span7">
												<label class="span5">Select User:</label>
												<div>
													<select id="users">
														<option value="0">Select User</option>
														<?php foreach ($users as $key => $user) { ?>
														<option value="<?php echo $user['id']; ?>"><?php echo $user['user_fullname']; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
										<br>

										<div class="row-fluid">
											<div class="span7">
												<label class="span5">Module Allowed To Access:</label>
												<div class="span6">
													<?php foreach ($modules as $key => $module) { ?>
													<div class="checkbox">
														<label>
															<input name="form-field-checkbox" type="checkbox" class="ace check_module" value="<?php echo $module['id']; ?>">
															<span class="lbl"> <?php echo $module['module_label']; ?></span>
														</label>
													</div>
													<?php } ?>
												</div>
											</div>
										</div>    
										<br>
										<div>  
											<div class="span3"></div>
											<div class="span7 input-group">
												<button id="save_module_priviles" class="btn btn-success align-right"> Save </button>
											</div>
										</div> 
									</form>
								</div>
							</div>

							<div id="panel-eCommerce" class="tab-pane">
								<div class="widget-box">
									<div class="widget-header header-color-blue">
										<h5 class="bigger lighter">
											<i class="icon-table"></i>
											eCommerce
										</h5>
									</div>
									<div class="widget-body">
										<div class="widget-main">
											<div class="tabbable">
												<ul class="nav nav-tabs" id="tab-eCommernce">
													<li class="active">
														<a data-toggle="tab" href="#tab-eCommernce-1">
															<i class="icon-cog"></i>
															eCommerce Delivery Details
														</a>
													</li>

													<li>
														<a data-toggle="tab" href="#tab-eCommernce-2">
															<i class="icon-cog"></i>
															eCommerce Shipping and Billing Info
														</a>
													</li>
												</ul>

												<div class="tab-content" style="overflow: inherit;">
													<div id="tab-eCommernce-1" class="tab-pane in active">
														<?php generateForm("switch", "Enable eCommerce Delivery Detail", "e-form-enable-delivery"); ?>
														<?php generateForm("text", "Self - Collection discount (%)", "e-form-self-collect-discount"); ?>
														<?php generateForm("text", "Normal Delivery Charge", "e-form-normal-charge"); ?>
														<?php generateForm("textarea", 'Normal Delivery Time <br> <small>(Separated by newline)</small>', "e-form-delivery-time"); ?>
														<?php generateForm("text", 'Express Delivery Surcharge', "e-form-surcharge"); ?>

														<hr>

														<div id="e-form-main-container-order-detail" style="display: none;">
															<div id="e-form-container-order-detail">
																<?php generateForm("banner", "Order Detail"); ?>
																<br>
																<?php generateForm("text", "First Name", "e-form-order-detail-fname"); ?>
																<?php generateForm("text", "Last Name", "e-form-order-detail-lname"); ?>
																<?php generateForm("text", "Email", "e-form-order-detail-email"); ?>
																<?php generateForm("text", "Company Name", "e-form-order-detail-company-name"); ?>
																<?php generateForm("textarea", "Billing Address", "e-form-order-detail-billing-address"); ?>
																<?php generateForm("text", "Billing Postal", "e-form-order-detail-billing-postal"); ?>
																<?php generateForm("textarea", "Remarks", "e-form-order-detail-remarks"); ?>
																<?php generateForm("text", "Discount", "e-form-order-detail-discount"); ?>
															</div>

															<div id="e-form-container-order-delivery-detail">
																<?php generateForm("banner", "Delivery Details", "label-success"); ?>
																<br>
																
																<div class="row-fluid">
																	<div class="span4">
																		<label class="text-right">
																			Mode of Delivery
																		</label>
																	</div>
																	<div class="span8">
																		<label>
	                                    <input name="e-form-order-detail-mode" type="radio" class="ace" checked="checked" value="self-collection">
	                                    <span class="lbl"> Self - Collection (5% - discount)</span>
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

																	<div class="row-fluid">
																		<div class="span4">
																			<label class="text-right">
																				Type of Delivery
																			</label>
																		</div>
																		<div class="span8">
																			<label>
		                                    <input name="e-form-order-detail-delivery-type" type="radio" class="ace" checked="checked" value="normal">
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

													<div id="tab-eCommernce-2" class="tab-pane">
														<?php generateForm("switch", "Enable eCommerce Shipping Detail in Order:", "e-form-enable-shilling-detail"); ?>
														<?php generateForm("switch", "Enable eCommerce Billing Detail in Order:", "e-form-enable-billing-detail"); ?>

														<hr>

														<div id="eCommerce-shipping-detail" style="display: none;">
															<?php generateForm("banner", "Shipping Detail"); ?>
															<br>

															<?php generateForm("text", "Shipping Address", "e-form-shipping-address"); ?>
															<?php generateForm("text", "Shipping Address Line 2", "e-form-shipping-address-2"); ?>
															<?php generateForm("text", "City", "e-form-shipping-city"); ?>
															<?php generateForm("text", "Postal/Zip code", "e-form-shipping-postal"); ?>
															<?php generateForm("text", "State", "e-form-shipping-state"); ?>

															<div class="row-fluid">
																<div class="span4">
																	<label for="e-form-shipping-country" class="text-right">
																		Country
																	</label>
																</div>
																<div class="span8">
																	<select id="e-form-shipping-country">
																	<?php foreach ($countries as $key => $value): ?>
																	<?php if ($key == ''): ?>
																		<?php foreach ($value as $k => $v): ?>
																			<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>"><?php echo $v->name; ?></option>
																		<?php endforeach ?>
																	<?php elseif ($key == 'others'): ?>
																		<optgroup label="Other countries">
																			<?php foreach ($value as $k => $v): ?>
																				<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>"><?php echo $v->name; ?></option>
																			<?php endforeach ?>
																		</optgroup>
																	<?php endif ?>
																<?php endforeach ?>
																</select>
																</div>
															</div>

															<?php generateForm("text", "Email", "e-form-shipping-email"); ?>
															<?php generateForm("text", "Phone", "e-form-shipping-phone"); ?>
															<?php generateForm("text", "Payment Info", "e-form-shipping-payment-info"); ?>
														</div>

														<div id="eCommerce-billing-detail" style="display: none;">
															<?php generateForm("banner", "Billing Detail", "label-success"); ?>
															<br>

															<?php generateForm("text", "Billing Name", "e-form-billing-name"); ?>
															<?php generateForm("text", "Billing Address", "e-form-billing-address"); ?>
															<?php generateForm("text", "Billing Address Line 2", "e-form-billing-address-2"); ?>
															<?php generateForm("text", "City", "e-form-billing-city"); ?>
															<?php generateForm("text", "Postal/Zip code", "e-form-billing-postal"); ?>
															<?php generateForm("text", "State", "e-form-billing-state"); ?>

															<div class="row-fluid">
																<div class="span4">
																	<label for="e-form-billing-country" class="text-right">
																		Country
																	</label>
																</div>
																<div class="span8">
																	<select id="e-form-billing-country">
																	<?php foreach ($countries as $key => $value): ?>
																	<?php if ($key == ''): ?>
																		<?php foreach ($value as $k => $v): ?>
																			<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>"><?php echo $v->name; ?></option>
																		<?php endforeach ?>
																	<?php elseif ($key == 'others'): ?>
																		<optgroup label="Other countries">
																			<?php foreach ($value as $k => $v): ?>
																				<option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>"><?php echo $v->name; ?></option>
																			<?php endforeach ?>
																		</optgroup>
																	<?php endif ?>
																<?php endforeach ?>
																</select>
																</div>
															</div>

															<?php generateForm("text", "Email", "e-form-billing-email"); ?>
															<?php generateForm("text", "Phone", "e-form-billing-phone"); ?>
														</div>

													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<hr>
												
								<div class="row-fluid">
									<div class="span12 text-center">
										<button class="btn btn-success" id="btn-eCommerce-save"><i class="icon-save"></i> Save</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.page-content -->
</div>