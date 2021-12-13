<?php

$sample_paypal_script = "";
$sample_paypal_script .= '<div id="paypal-button"></div>\n';
$sample_paypal_script .= '<script src="https://www.paypalobjects.com/api/checkout.js"></script>\n';
$sample_paypal_script .= '<script>
paypal.Button.render({
  // Configure environment
  env: "sandbox",
  // Customize button (optional)
  style: {"size":"medium","shape":"pill","color":"blue","label":"checkout","tagline":true},
  // Set up a payment
  payment: function (data, actions) {
    return actions.request.post("http://pvs-cms.com/api/paypal_checkout_create_payment/"")
    .then(function(res) {
      // 3. Return res.id from the response
      return res.id;
    });
  },
  // Execute the payment
  onAuthorize: function (data, actions) {
    return actions.request.post("http://pvs-cms.com/api/paypal_checkout_execute_payment/", {
        paymentID: data.paymentID,
        payerID:   data.payerID
      })
        .then(function(res) {
          // 3. Show the buyer a confirmation message.
          window.alert("Thank you for your purchase!"");
          console.log(res)
          if(res.status){
            window.location.href = "http://pvs-cms.com/order-confirmed/";
          }else{
            window.location.href = "http://pvs-cms.com/cancelled/"
          }
        });
  },
}, "#paypal-button");
</script>';

$shipping = '<strong>cms_product_set_payment_method()</strong> 
- Set selected product_payment_method

<strong>cms_product_get_payment_method()</strong> 
- Get selected product_payment_method

<strong>cms_generate_payment_button()</strong> 
- Generates Paypal Button

<strong>cms_product_set_user_detail( $data )</strong> 
-use to set user detail manually:
$data : {
  "company",
  "email",
  "first_name",
  "last_name",
  "phone",
  "billing_name",
  "billing_address",
  "billing_address_line_2",
  "billing_city",
  "billing_postal",
  "billing_state",
  "billing_country,
  "billing_email",
  "billing_phone",
  "shipping_name",
  "shipping_address",
  "shipping_address_line_2",
  "shipping_city",
  "shipping_postal",
  "shipping_state",
  "shipping_country",
  "shipping_email",
  "shipping_phone",
  "meta_data",
  "payment_method_id",
  "message",
  "invoice_number",
}

<strong>cms_product_set_other_fees( $data )</strong> 
-used to manually set other fees detail:
$data : {
  tax       : (decimal),
  insurance : (decimal),
  handling  : (decimal),
  shipping  : (decimal),
  shipping_discount : (decimal),
}

<strong>cms_get_last_order_id()</strong> 
-used to get the ID of latest order transaction:

<strong>cms_order_detail( $order_id )</strong> 
-used to get the detail of a certain order using the [order_id]:
$order_id  = the order ID.
@return: {
  order             : (Array),
  order_additional  : (Array Multiple),
  order_delivery    : (Array),
  order_details     : (Array),
  order_payment     : (Array),
}
';

$sample_paypal_script = str_replace("<", "&lt;", $sample_paypal_script);
$sample_paypal_script = str_replace(">", "&gt;", $sample_paypal_script);
$sample_paypal_script = str_replace("\\n", "<br>", $sample_paypal_script);
?>

<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Payment Gateways    
			</h1>
		</div>
		<div id="result"></div>
		<div class="row-fluid">
			<div class="span8">
				<input type="hidden" id="action" value="index" />
				<div class="widget-box" >
					<div class="widget-header header-color-blue2">
						<h5>Available Payment Gateways</h5>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<div id="payment-method-container">
								<div id="payment-method-main-container" class="accordion accordion-style2">
                  <div class="accordion-group">
                    <div class="accordion-heading">
                      <a href="#collapse-1" data-parent="payment-method-main-container" data-toggle="collapse" class="accordion-toggle collapsed">
                        <strong>Paypal Checkout<?php echo isset($gateway[6]->display_name) ? " - " . $gateway[6]->display_name : "" ; ?></strong>
                      </a>
                    </div>
                    <div class="accordion-body collapse" id="collapse-1">
                      <div class="accordion-inner">
                        <div class="payment-method">
                          <br>
                          <div class="hide">
                            <input type="hidden" class="method_id" value="6">
                            <input type="hidden" class="method_gateway_type" value="PAYPAL_CHECKOUT">
                            <input type="hidden" class="method_tax" value="N">
                          </div>

                          <div class="form-horizontal">
                            <div class="control-group">
                              <label class="control-label"><small>Display Name</small></label>
                              <div class="controls">
                                <div class="row-fluid">
                                  <input type="text" class="input span12 method_display_name" id="paypal_checkout_display_name" value="<?php echo isset($gateway[6]->display_name) ? $gateway[6]->display_name : ''; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label"><small>Email</small></label>
                              <div class="controls">
                                <div class="row-fluid">
                                  <input type="text" class="input span12 method_option" data-name="paypal_checkout_email" value="<?php echo isset($options['paypal_checkout_email']) ? $options['paypal_checkout_email'] : ''; ?>" />
                                </div>
                              </div>
                            </div>

                            <hr>
                            <div class="tabbable">
                              <ul class="nav nav-tabs">
                                <li class="active" style="width: 45%; margin-left: 5%">
                                  <a data-toggle="tab" href="#tab-checkout-1">
                                    <i class="blue icon-inbox bigger-110"></i>
                                    Sandbox
                                  </a>
                                </li>

                                <li  style="width: 45%">
                                  <a data-toggle="tab" href="#tab-checkout-2">
                                    <i class="blue icon-globe bigger-110"></i>
                                    Production
                                  </a>
                                </li>
                              </ul>

                              <div class="tab-content">
                                <div id="tab-checkout-1" class="tab-pane in active">
                                  <div class="control-group">
                                    <label><small>Sandbox Client ID</small></label>
                                    <div>
                                      <div class="row-fluid">
                                        <input type="text" class="input span12 method_option" data-name="paypal_checkout_sandbox_client_id" value="<?php echo isset($options['paypal_checkout_sandbox_client_id']) ? $options['paypal_checkout_sandbox_client_id'] : ''; ?>" />
                                      </div>
                                    </div>
                                  </div>
                                  <div class="control-group">
                                    <label><small>Sandbox Secret</small></label>
                                    <div>
                                      <div class="row-fluid">
                                        <input type="text" class="input span12 method_option" data-name="paypal_checkout_sandbox_secret" value="<?php echo isset($options['paypal_checkout_sandbox_secret']) ? $options['paypal_checkout_sandbox_secret'] : ''; ?>" id="sandbox-secret"/>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div id="tab-checkout-2" class="tab-pane">
                                  <div class="control-group">
                                    <label><small>Production Client ID</small></label>
                                    <div>
                                      <div class="row-fluid">
                                        <input type="text" class="input span12 method_option" data-name="paypal_checkout_client_id" value="<?php echo isset($options['paypal_checkout_client_id']) ? $options['paypal_checkout_client_id'] : ''; ?>" />
                                      </div>
                                    </div>
                                  </div>
                                  <div class="control-group">
                                    <label><small>Production Secret</small></label>
                                    <div>
                                      <div class="row-fluid">
                                        <input type="text" class="input span12 method_option" data-name="paypal_checkout_secret" value="<?php echo isset($options['paypal_checkout_secret']) ? $options['paypal_checkout_secret'] : ''; ?>" id="production-secret" />
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="control-group">
                              <div class="row-fluid">
                                <div class="span3">
                                  <label class="control-label"><small>Enable</small></label>
                                  <div class="controls">
                                    <label>
                                      <input name="paypal_checkout_enable" id="paypal_checkout_enable" class="method_gateway_enabled ace ace-switch ace-switch-2" type="checkbox" <?php echo isset($gateway[6]->enabled) && $gateway[6]->enabled == 'Y'? 'checked="checked"': ''; ?>>
                                      <span class="lbl"></span>
                                    </label>
                                  </div>
                                </div>
                                <div class="span3">
                                  <label class="control-label"><small>Sandbox</small></label>
                                  <div class="controls">
                                    <label>
                                      <input name="paypal_checkout_sandbox" id="paypal_checkout_sandbox" data-name="paypal_checkout_sandbox" class="ace ace-switch ace-switch-2 method_option" type="checkbox" <?php echo isset($options['paypal_checkout_sandbox']) && $options['paypal_checkout_sandbox'] == 'Y'? 'checked="checked"': ''; ?>>
                                      <span class="lbl"></span>
                                    </label>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <hr>
                            <div class="control-group">
                              <label class="control-label" for=""><small>URL Confirmed</small></label>
                              <div class="controls">
                                <input type="text" class="input span12 method_option" data-name="paypal_checkout_url_confirmed" placeholder="Enter URL to redirect after payment is completed" value="<?php echo isset($options['paypal_checkout_url_confirmed']) ? $options['paypal_checkout_url_confirmed'] : ''; ?>"  />
                              </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label" for=""><small>URL Cancelled</small></label>
                              <div class="controls">
                                <input type="text" class="input span12 method_option" data-name="paypal_checkout_url_cancelled" placeholder="Enter URL to redirect after payment is cancelled" value="<?php echo isset($options['paypal_checkout_url_cancelled']) ? $options['paypal_checkout_url_cancelled'] : ''; ?>" />
                              </div>
                            </div>
                            <hr>
                            <div class="control-group">
                              <label class="control-label" for=""><small>Notes to Payer</small></label>
                              <div class="controls">
                                <textarea cols="30" rows="5" data-name="paypal_checkout_note_to_payer" placeholder="Enter some notes for the payers. (this field will reflect on Paypal)" class="input span12 method_option" ><?php echo isset($options['paypal_checkout_note_to_payer']) ? htmlentities($options['paypal_checkout_note_to_payer']) : ''; ?></textarea>
                              </div>
                            </div>
                            <hr>

                            <div class="control-group">
                              <label class="control-label"><small></small></label>
                              <div class="controls">
                                <div class="row-fluid">
                                  <a href="javascript:void(0)" id="paypal_checkout_manage_button" class="btn btn-small btn-primary"><i class="icon icon-cog"></i>Manage Paypal Button</a>
                                  <a href="javascript:void(0)" id="paypal_checkout_manual_button" class="btn btn-small btn-success"><i class="icon icon-zoom-in"></i>Read Guide</a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapse-2" data-parent="payment-method-main-container" data-toggle="collapse" class="accordion-toggle collapsed">
												<strong>Paypal Subscription<?php echo isset($gateway[7]->display_name) ? " - " . $gateway[7]->display_name : "" ; ?></strong>
											</a>
										</div>
										<div class="accordion-body collapse" id="collapse-2">
											<div class="accordion-inner">
                        <div>
                          <p class="alert alert-warning">Manage Paypal Subscription <a href="<?php echo URL . "payment-gateways/subscription/" ?>">Here</a>.</p>
                          <hr>
                        </div>
											</div>
										</div>
									</div>

									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapse-3" data-parent="payment-method-main-container" data-toggle="collapse" class="accordion-toggle collapsed">
												<strong>Offline Payment - <?php echo isset($gateway[3]->display_name) ? $gateway[3]->display_name : ""; ?></strong>
											</a>
										</div>

										<div class="accordion-body collapse" id="collapse-3">
											<div class="accordion-inner">
												<div class="payment-method">
													<br>
													<form class="form-horizontal" action="#" enctype="multipart/form-data" method="post" onsubmit="return false;">
											      <div class="hide">
											        <input type="hidden" class="method_id" value="3">
											        <input type="hidden" class="method_gateway_type" value="OFFLINE_PAYMENT">
											        <input type="hidden" class="method_tax" value="N">
											      </div>
											      <div class="row-fluid">
											        <div class="span12">
											          <div class="control-group">
											            <label class="control-label">Display Name</label>
											            <div class="controls">
											              <input type="text" id="display_name_cash" class="span12 method_display_name" value="<?php echo isset($gateway[3]->display_name) ? $gateway[3]->display_name : ""; ?>">
											            </div>
											          </div>
											          <div class="control-group">
											            <label class="control-label">Gateway Enabled</label>
											            <div class="controls">
											              <label>
											                <input name="gateway_enabled_cash" class="method_gateway_enabled ace ace-switch ace-switch-2" type="checkbox" <?php echo isset($gateway[3]->enabled) && $gateway[3]->enabled =='Y' ? 'checked="checked"': ''; ?>>
											                <span class="lbl"></span>
											              </label>
											            </div>
											          </div>

											          <div>
											            <h5><strong>Offline Payment Instructions</strong></h5>
											            <p>
											              Use this area to provide your customers with instructions on how to make payments offline.
											            </p>
											            <textarea rows="5" id="offline_cash" class="method_option span12" data-name="offline_1_instruction"><?php echo isset($options['offline_1_instruction']) ? $options['offline_1_instruction'] : ""; ?></textarea>
											          </div>

											        </div>
											      </div>
											      <div class="row-fluid">
											        <div class="span6">
											          
											        </div>
											        <div class="span6">
											          
											        </div>
											      </div>
											    </form>
												</div>
											</div>
										</div>
									</div>

									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapse-4" data-parent="payment-method-main-container" data-toggle="collapse" class="accordion-toggle collapsed">
												<strong>Offline Payment - <?php echo isset($gateway[4]->display_name) ? $gateway[4]->display_name : ""; ?></strong>
											</a>
										</div>

										<div class="accordion-body collapse" id="collapse-4">
											<div class="accordion-inner">
												<div class="payment-method">
													<br>
												  <form class="form-horizontal" action="#" enctype="multipart/form-data" method="post" onsubmit="return false;">
											      <div class="hide">
											        <input type="hidden" class="method_id" value="4">
											        <input type="hidden" class="method_gateway_type" value="OFFLINE_PAYMENT">
											        <input type="hidden" class="method_tax" value="N">
											      </div>
											      <div class="row-fluid">
											        <div class="span12">
											          <div class="control-group">
											            <label class="control-label">Gateway Enabled</label>
											            <div class="controls">
											              <label>
											                <input name="gateway_enabled_cheque" class="method_gateway_enabled ace ace-switch ace-switch-2" type="checkbox" <?php echo isset($gateway[4]->enabled) && $gateway[4]->enabled == 'Y'? 'checked="checked"': ''; ?>>
											                <span class="lbl"></span>
											              </label>
											            </div>
											          </div>
											          <div class="control-group">
											            <label class="control-label">Display Name</label>
											            <div class="controls">

											              <input type="text" class="span12  method_display_name" id="display_name_cheque" value="<?php echo isset($gateway[4]->display_name) ? $gateway[4]->display_name : ""; ?>">
											            </div>
											          </div>

											          <div class="control-group">
											            <h5><strong>Offline Payment Instructions</strong></h5>
											            <p>
											              Use this area to provide your customers with instructions on how to make payments offline.
											            </p>
											            <textarea rows="5" id="offline_check" class="span12 method_option" data-name="offline_2_instruction" ><?php echo isset($options['offline_2_instruction']) ? $options['offline_2_instruction'] : ""; ?></textarea>
											          </div>
											        </div>
											      </div>
											    </form>
											    <br>
												</div>
											</div>
										</div>
									</div>

									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapse-5" data-parent="payment-method-main-container" data-toggle="collapse" class="accordion-toggle collapsed">
												<strong>Offline Payment - <?php echo isset($gateway[5]->display_name) ? $gateway[5]->display_name : ""; ?></strong>
											</a>
										</div>

										<div class="accordion-body collapse" id="collapse-5">
											<div class="accordion-inner">
												<div class="payment-method">
													<br>
												  <form class="form-horizontal" action="#" enctype="multipart/form-data" method="post" onsubmit="return false;">
											      <div class="hide">
											        <input type="hidden" class="method_id" value="5">
											        <input type="hidden" class="method_gateway_type" value="OFFLINE_PAYMENT">
											        <input type="hidden" class="method_tax" value="N">
											      </div>
											      <div class="row-fluid">
											        <div class="span12">
											          <div class="control-group">
											            <label class="control-label">Gateway Enabled</label>
											            <div class="controls">
											              <label>
											                <input name="gateway_enabled_transfer" class="method_gateway_enabled ace ace-switch ace-switch-2" type="checkbox" <?php echo isset($gateway[5]->enabled) && $gateway[5]->enabled == 'Y'? 'checked="checked"': ''; ?>>
											                <span class="lbl"></span>
											              </label>
											            </div>
											          </div>
											          <div class="control-group">
											            <label class="control-label">Display Name</label>
											            <div class="controls">

											              <input type="text" class="span12 method_display_name" id="display_name_transfer" value="<?php echo isset($gateway[5]->display_name) ? $gateway[5]->display_name : ""; ?>">

											            </div>
											          </div>

											          <div class="control-group">
											            <h5><strong>Offline Payment Instructions</strong></h5>
											            <p>
											              Use this area to provide your customers with instructions on how to make payments offline.
											            </p>
											            <textarea rows="5" id="offine_transfer" class="span12 method_option" data-name="offline_3_instruction" ><?php echo isset($options['offline_3_instruction']) ? $options['offline_3_instruction'] : ""; ?></textarea>
											          </div>
											        </div>
											      </div>
											    </form>
											    <br>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
							<div class="alert alert-info hide">
								<strong>Note:</strong> More payment gateways can be downloaded from our <a href="#">website here.</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span4">
				<div class="widget-box">
					<div class="widget-header header-color-blue2">
						<h5>Currency Settings</h5>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<div class="row-fluid">
								<div class="span12">
									<div class="control-group">
										<label class="control-label" >Currency</label>
										<div class="controls">
											<select id="currency_code" class="chosen-select">
												<option value="AUD" <?php echo $settings[14]['option_value'] == 'AUD' ? 'selected=""' : '';?> >Australian dollar (AUD)</option>
												<option value="CAD" <?php echo $settings[14]['option_value'] == 'CAD' ? 'selected=""' : '';?> >Canadian dollar (CAD)</option>
												<option value="CZK" <?php echo $settings[14]['option_value'] == 'CZK' ? 'selected=""' : '';?> >Czech koruna (CZK)</option>
												<option value="DKK" <?php echo $settings[14]['option_value'] == 'DKK' ? 'selected=""' : '';?> >Danish krone (DKK)</option>
												<option value="EUR" <?php echo $settings[14]['option_value'] == 'EUR' ? 'selected=""' : '';?> >Euro (EUR)</option>
												<option value="HKD" <?php echo $settings[14]['option_value'] == 'HKD' ? 'selected=""' : '';?> >Hong Kong dollar (HKD)</option>
												<option value="ILS" <?php echo $settings[14]['option_value'] == 'ILS' ? 'selected=""' : '';?> >Israeli new shekel (ILS)</option>
												<option value="MXN" <?php echo $settings[14]['option_value'] == 'MXN' ? 'selected=""' : '';?> >Mexican peso (MXN)</option>
												<option value="NZD" <?php echo $settings[14]['option_value'] == 'NZD' ? 'selected=""' : '';?> >New Zealand dollar (NZD)</option>
												<option value="NOK" <?php echo $settings[14]['option_value'] == 'NOK' ? 'selected=""' : '';?> >Norwegian krone (NOK)</option>
												<option value="PHP" <?php echo $settings[14]['option_value'] == 'PHP' ? 'selected=""' : '';?> >Philippine peso (PHP)</option>
												<option value="PLN" <?php echo $settings[14]['option_value'] == 'PLN' ? 'selected=""' : '';?> >Polish złoty (PLN)</option>
												<option value="GBP" <?php echo $settings[14]['option_value'] == 'GBP' ? 'selected=""' : '';?> >Pound sterling (GBP)</option>
												<option value="RUB" <?php echo $settings[14]['option_value'] == 'RUB' ? 'selected=""' : '';?> >Russian ruble (RUB)</option>
												<option value="SGD" <?php echo $settings[14]['option_value'] == 'SGD' ? 'selected=""' : '';?> >Singapore dollar (SGD)</option>
												<option value="SEK" <?php echo $settings[14]['option_value'] == 'SEK' ? 'selected=""' : '';?> >Swedish krona (SEK)</option>
												<option value="CHF" <?php echo $settings[14]['option_value'] == 'CHF' ? 'selected=""' : '';?> >Swiss franc (CHF)</option>
												<option value="THB" <?php echo $settings[14]['option_value'] == 'THB' ? 'selected=""' : '';?> >Thai baht (THB)</option>
												<option value="USD" <?php echo $settings[14]['option_value'] == 'USD' ? 'selected=""' : '';?> >United States dollar (USD)</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" >Symbol ($)</label>
										<div class="controls">
											<input type="text" class="span12 input-text input-small" id="currency_symbol" value="<?php echo $settings[13]['option_value'];?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" >Currency Position</label>
										<div class="controls">
											<select class="span12">
												<option value="before">Before</option>
												<option value="after">After</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="widget-box">
					<div class="widget-header header-color-blue2">
						<h5>Invoice Settings</h5>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<div class="row-fluid">
								<div class="span12">
									<div class="control-group">
										<label class="control-label" >Company Name</label>
										<div class="controls">
											<input type="text" class="span12 input-text input-xlarge" id="company_name" value="<?php echo $settings[15]['option_value'];?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" >Company Address</label>
										<div class="controls">
											<textarea rows="5" id="company_address" class="span12"><?php echo $settings[16]['option_value'];?></textarea>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" >Invoice Number Prefix</label>
										<div class="controls">
											<input type="text" class="span12 input-text input-xlarge" id="invoice_number_prefix" value="<?php echo $settings[17]['option_value'];?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" >Next Invoice Number</label>
										<div class="controls">
											<input type="text" class="span12 input-text input-xlarge" id="next_invoice_number" value="<?php echo $settings[18]['option_value'];?>">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="well well-small">
					<button class="btn btn-success btn-primary" type="button" id="save_settings" style="width: 100%">
						<i class="icon-save bigger-110"></i>
						Save Changes
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal-paypal-checkout-customize-button" class="modal fade" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Paypal Button: Checkout</h4>
  </div>

  <div class="modal-body overflow-visible">
    <div class="row-fluid">
      <div class="span12">
        <div class="well well-small" style="position: relative;">
          <div class="overlay-disabler" style="width: 100%; height: 100%; position: absolute; z-index: 999; margin: -9px;"></div>
          <br>
          <div id="paypal-button-container" class="text-center paypal-container"></div>
        </div>
        <div class="row-fluid">
          <div class="span12">
            <div class="control-group">
              <label for="form-field-select-3"><small>Label</small></label>
              <div class="controls">
                <select class="chosen-select span12 paypal-button-label" >
                  <option value="checkout">Checkout</option>
                  <option value="pay">Pay with Paypal</option>
                  <option value="buynow">Buy Now</option>
                  <option value="paypal">Paypal</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span3">
            <div class="control-group">
              <label for="form-field-select-3"><small>Shape</small></label>
              <div class="controls">
                <select class="chosen-select paypal-button-shape" >
                  <option value="pill">Pill</option>
                  <option value="rect">Rectangle</option>
                </select>
              </div>
            </div>
          </div>
          <div class="span3">
            <div class="control-group">
              <label for="form-field-select-3"><small>Size</small></label>
              <div class="controls">
                <select class="chosen-select paypal-button-size" >
                  <option value="small">Small</option>
                  <option value="medium">Medium</option>
                  <option value="large">Large</option>
                  <option value="responsive">Responsive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="span1"></div>
          <div class="span2">
            <div class="control-group">
              <label for="form-field-select-3"><small>Color</small></label>
              <div class="controls">
                <select class="paypal-button-color">
                  <option value="#ffad46">gold</option>
                  <option value="#4986e7">blue</option>
                  <option value="#c2c2c2">silver</option>
                  <option value="#555555">black</option>
                </select>
              </div>
            </div>
          </div>
          <div class="span2">
            <div class="control-group">
              <label for="form-field-select-$('#simple-colorpicker-1').ace_colorpicker();3"><small>Tagline</small></label>
              <div class="controls">
                  <label>
                    <input class="ace ace-switch ace-switch-2 paypal-button-tagline" type="checkbox" >
                    <span class="lbl"></span>
                  </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button class="btn btn-small" data-dismiss="modal">
      <i class="icon-remove"></i>
      Cancel
    </button>

    <button class="btn btn-small btn-primary" id="paypal-checkout-button-save">
      <i class="icon-ok"></i>
      Save
    </button>
  </div>
</div>
<div id="modal-paypal-subscription-customize-button" class="modal fade" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="bigger">Paypal Button: Subscription</h4>
	</div>

	<div class="modal-body overflow-visible">
		<div class="row-fluid">
			<div class="span12">
				<div class="well well-small" style="position: relative;">
					<div class="overlay-disabler" style="width: 100%; height: 100%; position: absolute; z-index: 999; margin: -9px;"></div>
					<br>
					<div id="paypal-container-subscription" class="text-center paypal-container"></div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="control-group">
							<label for="form-field-select-3"><small>Label</small></label>
							<div class="controls">
								<select class="chosen-select span12 paypal-button-label" >
									<option value="checkout">Checkout</option>
									<option value="pay">Pay with Paypal</option>
									<option value="buynow">Buy Now</option>
									<option value="paypal">Paypal</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3">
						<div class="control-group">
							<label for="form-field-select-3"><small>Shape</small></label>
							<div class="controls">
								<select class="chosen-select paypal-button-shape" >
									<option value="pill">Pill</option>
									<option value="rect">Rectangle</option>
								</select>
							</div>
						</div>
					</div>
					<div class="span3">
						<div class="control-group">
							<label for="form-field-select-3"><small>Size</small></label>
							<div class="controls">
								<select class="chosen-select paypal-button-size" >
									<option value="small">Small</option>
									<option value="medium">Medium</option>
									<option value="large">Large</option>
									<option value="responsive">Responsive</option>
								</select>
							</div>
						</div>
					</div>
					<div class="span1"></div>
					<div class="span2">
						<div class="control-group">
							<label for="form-field-select-3"><small>Color</small></label>
							<div class="controls">
								<select class="paypal-button-color">
									<option value="#ffad46">gold</option>
									<option value="#4986e7">blue</option>
									<option value="#c2c2c2">silver</option>
									<option value="#555555">black</option>
								</select>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group">
							<label for="form-field-select-$('#simple-colorpicker-1').ace_colorpicker();3"><small>Tagline</small></label>
							<div class="controls">
									<label>
										<input class="ace ace-switch ace-switch-2 paypal-button-tagline" type="checkbox" >
										<span class="lbl"></span>
									</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal-footer">
		<button class="btn btn-small" data-dismiss="modal">
			<i class="icon-remove"></i>
			Cancel
		</button>

		<button class="btn btn-small btn-primary" id="paypal-subscription-button-save">
			<i class="icon-ok"></i>
			Save
		</button>
	</div>
</div>

<div id="modal-paypal-checkout-guide" class="modal fade" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Quick Guide: Paypal Checkout </h4>
  </div>

  <div class="modal-body">
    <p>Get your paypal <strong>Secret</strong> and <strong>Password</strong> from <a href="https://www.paypal.com/" target="_blank">https://www.paypal.com/</a>. </p>
    <p><a href="https://developer.paypal.com/developer/applications/" target="_blank">(Create Paypal Credential)</a></p>
    <p>Use function <strong>cms_generate_payment_button()</strong> to generate button on your frontend page. </p>

    <strong>Usage:</strong>
    <pre>&lt;?php cms_generate_payment_button() ?&gt;</pre>
    <hr>

    <strong>Sample Output:</strong>
    <pre id="pre-sample-output"><?php echo $sample_paypal_script; ?></pre>

    <hr>

    <h3>Function</h3>
    <pre id="pre-shipping"><?php echo $shipping; ?></pre>
  </div>

  <div class="modal-footer">
    <button class="btn btn-small" data-dismiss="modal">
      <i class="icon-remove"></i>
      Close
    </button>
  </div>
</div>
<div id="modal-paypal-subscription-guide" class="modal fade" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="bigger">Quick Guide: Paypal Subscription </h4>
	</div>

	<div class="modal-body">
		<p>Get your paypal <strong>Secret</strong> and <strong>Password</strong> from <a href="https://www.paypal.com/" target="_blank">https://www.paypal.com/</a>. </p>
		<p><a href="https://developer.paypal.com/developer/applications/" target="_blank">(Create Paypal Credential)</a></p>
		<p>Use function <strong>cms_generate_payment_button()</strong> to generate button on your frontend page. </p>

		<strong>Usage:</strong>
		<pre>&lt;?php cms_generate_payment_button() ?&gt;</pre>
		<hr>

		<strong>Sample Output:</strong>
		<pre><?php echo $sample_paypal_script; ?></pre>

    <hr>

    <h3>Function</h3>
    <pre><?php echo $shipping; ?></pre>
	</div>

	<div class="modal-footer">
		<button class="btn btn-small" data-dismiss="modal">
			<i class="icon-remove"></i>
			Close
		</button>
	</div>
</div>

<!--PAGE CONTENT ENDS-->

<script src="https://www.paypalobjects.com/api/checkout.js"></script>