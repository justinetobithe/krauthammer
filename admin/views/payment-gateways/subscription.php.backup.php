<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Payment Gateways - Paypal Subscription
			</h1>
		</div>
		<div id="result"></div>
		<div class="row-fluid">
			<div class="span9 payment-method">
				<input type="hidden" id="action" value="index" />
				<div class="widget-box" id="container-general-settings" >
					<div class="widget-header header-color-blue2">
						<h5>General Setting</h5>
            <div class="widget-toolbar">
              <a href="javascript:void(0)" data-action="collapse">
                <i class="icon-chevron-up"></i>
              </a>
            </div>
					</div>

					<div class="widget-body">
						<div class="widget-main">
              <br>
              <div class="hide">
                <input type="hidden" class="method_id" value="7">
                <input type="hidden" class="method_gateway_type" value="PAYPAL_SUBSCRIPTION">
                <input type="hidden" class="method_tax" value="N">
              </div>

              <div class="form-horizontal">
                <div class="control-group">
                  <label class="control-label"><small>Display Name</small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <input type="text" class="input span12 method_display_name" id="paypal_subscription_display_name" value="<?php echo isset($gateway[7]->display_name) ? $gateway[7]->display_name : ''; ?>" />
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div class="row-fluid">
                    <div class="span12">
                      <label class="control-label"><small>Enable</small></label>
                      <div class="controls">
                        <label>
                          <input name="paypal_subscription_enable" id="paypal_subscription_enable" class="method_gateway_enabled ace ace-switch ace-switch-2" type="checkbox" <?php echo isset($gateway[7]->enabled) && $gateway[7]->enabled == 'Y'? 'checked="checked"': ''; ?>>
                          <span class="lbl"></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <hr>
                <div class="form-horizontal">
                  <div class="control-group">
                    <label class="control-label"><small>Client ID</small></label>
                    <div class="controls">
                      <div class="row-fluid">
                        <input type="text" class="input span12 method_option" data-name="paypal_subscription_client_id" value="<?php echo isset($options['paypal_subscription_client_id']) ? $options['paypal_subscription_client_id'] : ''; ?>" />
                      </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label"><small>Secret</small></label>
                    <div class="controls">
                      <div class="row-fluid">
                        <input type="text" class="input span12 method_option" data-name="paypal_subscription_secret" value="<?php echo isset($options['paypal_subscription_secret']) ? $options['paypal_subscription_secret'] : ''; ?>" id="production-subscription-secret"/>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label"><small></small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <a href="javascript:void(0)" id="btn_paypal_subscription_manual" class="btn btn-small btn-success"><i class="icon icon-zoom-in"></i>Read Guide</a>
                      <a href="javascript:void(0)" id="button-open-modal-subscription-detail" class="btn btn-small btn-primary"><i class="icon icon-bookmark"></i>View Plans</a>
                    </div>
                  </div>
                </div>
              </div>
						</div>
					</div>
				</div>

        <div class="widget-box" id="container-subscription-plans" >
          <div class="widget-header header-color-blue2">
            <h5>Subscription Plan</h5>
            <div class="widget-toolbar">
              <a href="javascript:void(0)" data-action="collapse">
                <i class="icon-chevron-up"></i>
              </a>
            </div>
          </div>

          <div class="widget-body">
            <div class="widget-main">
              <div class="row-fluid row-item-container" id="row-item-container">
                
              </div>
            </div>
          </div>
        </div>
			</div>
			<div class="span3 sticky">
        <div class="well well-small">
          Paypal Status: <strong id="label-status">Actived</strong> <a href="javascript:void(0)" class="pull-right">Remove</a>
        </div>
				<div class="well well-small">
					<button class="btn btn-success btn-primary" type="button" id="save_subscription" style="width: 100%">
						<i class="icon-save bigger-110"></i> Save
					</button>
				</div>
        <div class="well well-small">
          <h5>Go To</h5>
          <p><a href="#container-general-settings">General Settings</a></p>
          <p><a href="#container-subscription-detail">Subscription Detail</a></p>
          <p><a href="#container-billing-agreement-detail">Agreement</a></p>
          <p><a href="#container-subscribers">Subscribers</a></p>
        </div>
			</div>
		</div>
	</div>
</div>

<!-- Modals -->
<div id="modal-paypal-subscription-detail" class="modal fade" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Paypal Subscription Plan Info </h4>
  </div>

  <div class="modal-body">
    <div class="modal-content form-horizontal"></div>
    <hr>
    <div clsas="modal-disclaimer">
      <p><strong>Note: </strong><em>Information is retrieved from Paypal. </em></p>
    </div>
  </div>

  <div class="modal-footer">
    <button class="btn btn-medium btn-success" id="btn-activate-paypal-subscription-plan-info"><i class="icon-check"></i> Activate</button>
    <button class="btn btn-medium btn-primary" id="btn-refresh-paypal-subscription-plan-info"><i class="icon-refresh"></i> Refresh</button>
    <button class="btn btn-medium" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
  </div>
</div>
<div id="modal-paypal-subscription-plan-detail" class="modal modal-subscription fade" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Paypal Subscription Plan Detail </h4>
  </div>

  <div class="modal-body">
    <div id="subscription-plan-options-loading" style="display: none;">
      <p class="text-center"><strong>Loading Subscription Plan...</strong></p>
    </div>
    <div class="modal-main" id="subscription-plan-options">
      <div class="widget-box" id="container-subscription-detail" >
        <div class="widget-header header-color-blue2">
          <h5>Subscription Detail</h5>
        </div>

        <div class="widget-body">
          <div class="widget-main">
            <div class="hide">
              <input type="hide" id="subscription-plan-item-id" data-description="This contains the ID of the table.">
            </div>
            <div class="form-horizontal row-fluid">
              <div class="control-group">
                <label class="control-label" for=""><small>Plan Name: </small></label>
                <div class="controls">
                  <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_plan_name" placeholder="Enter Plan name" value="<?php echo isset($options['paypal_subscription_plan_name']) ? $options['paypal_subscription_plan_name'] : ''; ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for=""><small>Plan Description: </small></label>
                <div class="controls">
                  <textarea rows="5" class="input method_option span12 subscription_field" data-name="paypal_subscription_plan_description" placeholder="Enter the description of the plan (Max: 127 characters)" ><?php echo isset($options['paypal_subscription_plan_description']) ? $options['paypal_subscription_plan_description'] : ''; ?></textarea>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for=""><small>Return URL</small></label>
                <div class="controls">
                  <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_url_confirmed" placeholder="Enter URL to redirect after payment is completed" value="<?php echo isset($options['paypal_subscription_url_confirmed']) ? $options['paypal_subscription_url_confirmed'] : ''; ?>"  />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for=""><small>Cancel URL</small></label>
                <div class="controls">
                  <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_url_cancelled" placeholder="Enter URL to redirect after payment is cancelled" value="<?php echo isset($options['paypal_subscription_url_cancelled']) ? $options['paypal_subscription_url_cancelled'] : ''; ?>" />
                </div>
              </div>


              <br>
              <div id="container-subscription-detail-advance">
                <div class="control-group">
                  <label class="control-label" for=""><small>Plan Type: </small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_plan_type" id="paypal_subscription_plan_type" >
                          <option value="INFINITE" <?php echo isset($options['paypal_subscription_plan_type']) && $options['paypal_subscription_plan_type'] == 'INFINITE' ? 'selected' : ''; ?>>INFINITE</option>
                          <option value="FIXED" <?php echo isset($options['paypal_subscription_plan_type']) && $options['paypal_subscription_plan_type'] == 'FIXED'  ? 'selected' : ''; ?>>FIXED</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>Indicates whether the payment definitions in the plan have a fixed number of or infinite payment cycles.</em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for=""><small>Auto Billing: </small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_plan_auto_billing" >
                          <option value="YES" <?php echo isset($options['paypal_subscription_plan_auto_billing']) && $options['paypal_subscription_plan_auto_billing'] == 'YES' ? 'selected' : ''; ?>>YES</option>
                          <option value="NO" <?php echo isset($options['paypal_subscription_plan_auto_billing']) && $options['paypal_subscription_plan_auto_billing'] == 'NO'  ? 'selected' : ''; ?>>NO</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>Indicates whether PayPal automatically bills the outstanding balance in the next billing cycle. The outstanding balance is the total amount of any previously failed scheduled payments. </em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for=""><small>Initial Fail Action: </small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_plan_initial_fail_action" >
                          <option value="CONTINUE" <?php echo isset($options['paypal_subscription_plan_initial_fail_action']) && $options['paypal_subscription_plan_initial_fail_action'] == 'CONTINUE' ? 'selected' : ''; ?>>CONTINUE</option>
                          <option value="CANCEL" <?php echo isset($options['paypal_subscription_plan_initial_fail_action']) && $options['paypal_subscription_plan_initial_fail_action'] == 'CANCEL'  ? 'selected' : ''; ?>>CANCEL</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>The action if the customer's initial payment fails. </em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for=""><small>Max Fail Attempts: </small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_plan_max_fail_attempts" placeholder="Enter Max Fail Attempts" value="<?php echo isset($options['paypal_subscription_plan_max_fail_attempts']) ? $options['paypal_subscription_plan_max_fail_attempts'] : '0'; ?>" />
                      </div>
                      <div class="span8">
                        <p><em>The maximum number of allowed failed payment attempts. The default value, which is 0, defines infinite failed payment attempts. </em></p>
                      </div>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>

            <div class="tabbable">
              <ul class="nav nav-tabs">
                <li class="active" style="width: 45%; margin-left: 5%">
                  <a data-toggle="tab" href="#tab-subscription-payment-1">
                    <i class="blue icon-bookmark-empty bigger-110"></i>
                    Trial
                  </a>
                </li>

                <li  style="width: 45%">
                  <a data-toggle="tab" href="#tab-subscription-payment-2">
                    <i class="blue icon-calendar bigger-110"></i>
                    Regular Payment
                  </a>
                </li>
              </ul>

              <div class="tab-content">
                <div id="tab-subscription-payment-1" class="tab-pane in active">
                  <div class="form-horizontal row-fluid">
                    <div class="control-group">
                      <label class="control-label" for=""><small>Trial Title</small></label>
                      <div class="controls">
                        <textarea type="text" row="5" class="input span12 method_option subscription_field" style="max-width: 100%; min-width: 100%;" data-name="paypal_subscription_title_trial" placeholder="Enter title for trial."><?php echo isset($options['paypal_subscription_title_trial']) ? $options['paypal_subscription_title_trial'] : ''; ?></textarea>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for=""><small>Amount</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_amount_trial" placeholder="Enter trial amount. Set to 0 if free." value="<?php echo isset($options['paypal_subscription_amount_trial']) ? $options['paypal_subscription_amount_trial'] : ''; ?>"  />
                        </div>
                        <div class="span8">
                          <p><em>The amount of the charge to make at the end of each payment cycle for this definition.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for=""><small>Frequency</small></label>
                      <div class="controls">
                        <div class="span4">
                          <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_frequency_trial" id="paypal_subscription_frequency_trial" >
                            <option value="DAY" <?php echo isset($options['paypal_subscription_frequency_trial']) && $options['paypal_subscription_frequency_trial'] == 'DAY'  ? 'selected' : ''; ?>>DAY</option>
                            <option value="WEEK" <?php echo isset($options['paypal_subscription_frequency_trial']) && $options['paypal_subscription_frequency_trial'] == 'WEEK' ? 'selected' : ''; ?>>WEEK</option>
                            <option value="MONTH" <?php echo isset($options['paypal_subscription_frequency_trial']) && $options['paypal_subscription_frequency_trial'] == 'MONTH'? 'selected' : ''; ?>>MONTH</option>
                            <option value="YEAR" <?php echo isset($options['paypal_subscription_frequency_trial']) && $options['paypal_subscription_frequency_trial'] == 'YEAR' ? 'selected' : ''; ?>>YEAR</option>
                          </select>
                        </div>
                        <div class="span8">
                          <p><em>The frequency of the payment in this definition.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for=""><small>Frequency Interval</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="number" class="input span12 method_option subscription_field" data-name="paypal_subscription_frequency_interval_trial" placeholder="Enter frequency interval" value="<?php echo isset($options['paypal_subscription_frequency_interval_trial']) ? $options['paypal_subscription_frequency_interval_trial'] : ''; ?>" id="paypal_subscription_frequency_interval_trial" min="1" />
                        </div>
                        <div class="span8">
                          <p><em>The interval at which the customer is charged. Value cannot be greater than <strong>12</strong> months.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for=""><small>Cycle</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="number" class="input span12 method_option subscription_field" data-name="paypal_subscription_cycle_trial" placeholder="Enter payment cycle" value="<?php echo isset($options['paypal_subscription_cycle_trial']) ? $options['paypal_subscription_cycle_trial'] : ''; ?>" id="paypal_subscription_cycle_trial" min="1" />
                        </div>
                        <div class="span8">
                          <p><em>The number of payment cycles. For infinite plans with a regular payment definition, set cycles to <strong>0</strong>.</em></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="tab-subscription-payment-2" class="tab-pane">
                  <div class="form-horizontal row-fluid">
                    <div class="control-group">
                      <label class="control-label" for=""><small>Regular Title</small></label>
                      <div class="controls">
                        <textarea type="text" row="5" class="input span12 method_option subscription_field" data-name="paypal_subscription_title" placeholder="Enter title for regular payment." style="max-width: 100%; min-width: 100%;"><?php echo isset($options['paypal_subscription_title']) ? $options['paypal_subscription_title'] : ''; ?></textarea>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for=""><small>Amount</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_amount" placeholder="Enter the Regular Subscription amount" value="<?php echo isset($options['paypal_subscription_amount']) ? $options['paypal_subscription_amount'] : ''; ?>"  />
                        </div>
                        <div class="span8">
                          <p><em>The amount of the charge to make at the end of each payment cycle for this definition.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for=""><small>Frequency</small></label>
                      <div class="controls">
                        <div class="span4">
                          <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_frequency" id="paypal_subscription_frequency" >
                            <option value="DAY" <?php echo isset($options['paypal_subscription_frequency']) && $options['paypal_subscription_frequency'] == 'DAY'  ? 'selected' : ''; ?>>DAY</option>
                            <option value="WEEK" <?php echo isset($options['paypal_subscription_frequency']) && $options['paypal_subscription_frequency'] == 'WEEK' ? 'selected' : ''; ?>>WEEK</option>
                            <option value="MONTH" <?php echo isset($options['paypal_subscription_frequency']) && $options['paypal_subscription_frequency'] == 'MONTH'? 'selected' : ''; ?>>MONTH</option>
                            <option value="YEAR" <?php echo isset($options['paypal_subscription_frequency']) && $options['paypal_subscription_frequency'] == 'YEAR' ? 'selected' : ''; ?>>YEAR</option>
                          </select>
                        </div>
                        <div class="span8">
                          <p><em>The frequency of the payment in this definition.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for=""><small>Frequency Interval</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="number" class="input span12 method_option subscription_field" data-name="paypal_subscription_frequency_interval" placeholder="Enter frequency interval" value="<?php echo isset($options['paypal_subscription_frequency_interval']) ? $options['paypal_subscription_frequency_interval'] : ''; ?>" id="paypal_subscription_frequency_interval" min="1" />
                        </div>
                        <div class="span8">
                          <p><em>The interval at which the customer is charged. Value cannot be greater than <strong>12</strong> months.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for=""><small>Cycle</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="number" class="input span12 method_option subscription_field" data-name="paypal_subscription_cycle" placeholder="Enter payment cycle" value="<?php echo isset($options['paypal_subscription_cycle']) ? $options['paypal_subscription_cycle'] : ''; ?>" id="paypal_subscription_cycle" min="1" />
                        </div>
                        <div class="span8">
                          <p><em>The number of payment cycles. For infinite plans with a regular payment definition, set cycles to <strong>0</strong>.</em></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="widget-box" id="container-billing-agreement-detail" >
        <div class="widget-header header-color-blue2">
          <h5>Agreement</h5>
        </div>

        <div class="widget-body">
          <div class="widget-main">
            <div class="form-horizontal row-fluid">
              <div class="control-group">
                <label class="control-label" for=""><small>Name: </small></label>
                <div class="controls">
                  <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_agreement_name" placeholder="Enter Plan name" value="<?php echo isset($options['paypal_subscription_agreement_name']) ? $options['paypal_subscription_agreement_name'] : ''; ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for=""><small>Description: </small></label>
                <div class="controls">
                  <textarea rows="5" class="input method_option span12 subscription_field" data-name="paypal_subscription_agreement_description" placeholder="Enter the description of the plan (Max: 127 characters)" ><?php echo isset($options['paypal_subscription_agreement_description']) ? $options['paypal_subscription_agreement_description'] : ''; ?></textarea>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
      <div class="widget-box" id="container-subscribers" >
        <div class="widget-header header-color-blue2">
          <h5>Subscribers</h5>
          <div class="widget-toolbar">
            <a href="javascript:void(0)" data-action="collapse" id="btn-refresh-subscribers">
              <i class="icon-refresh"></i>
            </a>
            <a href="javascript:void(0)" data-action="collapse">
              <i class="icon-chevron-up"></i>
            </a>
          </div>
        </div>

        <div class="widget-body">
          <div class="widget-main">
            <div class="table-responsive">
              <table id="table-subscribers" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Subscriber Name</th>
                    <th>ID</th>
                    <th>Status</th>
                    <th >Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>

              <hr>

              <button class="btn btn-small btn-primary">Test Subscription</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button class="btn btn-medium btn-success" id="btn-save-paypal-subscription-plan"><i class="icon-save"></i> Save</button>
  </div>
</div>

<!--PAGE CONTENT ENDS-->

<script id="tmpl-subscription-plan-item" type="text/x-jquery-tmpl">
  <div class="span4">
    <div class="widget-box pricing-box">
      <div class="widget-header header-color-blue">
        <h5 class="bigger lighter">Plan</h5>
      </div>

      <div class="widget-body">
        <div class="widget-main">
          <ul class="unstyled spaced2">
            <li><strong>${title}</strong></li>
            <li>${transaction_id}</li>
          </ul>
        </div>

        <div>
          <a href="javascript:void(0)" data-id="${id}" class="btn btn-block btn-primary btn-load-billing-plan">
            <small>View</small>
          </a>
        </div>
      </div>
    </div>
  </div>
</script>
