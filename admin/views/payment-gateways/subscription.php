<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Payment Gateways - Paypal Subscription
			</h1>
		</div>
		<div id="result"></div>
		<div class="row-fluid">
			<div class="span9">
				<input type="hidden" id="action" value="index" />
				<div class="widget-box payment-method" id="container-general-settings" >
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
                  <hr>
                  <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls">
                      <div class="row-fluid">
                        <a href="javascript:void(0)" id="btn-read-documentation">Read Guide</a>
                      </div>
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
            <div class="widget-toolbar no-border">
              <button class="btn btn-mini btn-light bigger" data-rel="tooltip" title="Add Subscription Plan" id="btn-add-subscription-plan">
                <i class="icon-plus"></i>
                Add Subscription Plan
              </button>
            </div>
          </div>

          <div class="widget-body">
            <div class="widget-main">
              <div class="row-fluid">
                <div class="span6">
                  Legend: 
                  <span class="label label-primary arrowed arrowed-right">Created</span>
                  <span class="label label-success arrowed arrowed-right">Active</span>
                  <span class="label label-important arrowed arrowed-right">Deleted</span>
                </div>
                <div class="span6">
                  <label class="text-right">
                      <input name="form-field-checkbox" type="checkbox" class="ace" id="subscription-plan-show-deleted">
                      <span class="lbl"> <small>Show Deleted Subscription</small></span>
                  </label>
                </div>
              </div>
              <hr>
              <div class="row-fluid row-item-container" id="row-item-container"></div>
            </div>
          </div>
        </div>

        <div class="widget-box" id="container-susbcription-default-product-setting" >
          <div class="widget-header header-color-blue2">
            <h5>Products Default Subscription Settings</h5>
            <div class="widget-toolbar">
              <a href="javascript:void(0)" data-action="collapse">
                <i class="icon-chevron-up"></i>
              </a>
            </div>
          </div>

          <div class="widget-body">
            <div class="widget-main">
              <div class="form-horizontal">
                <div class="control-group">
                  <label class="control-label"></label>
                  <div class="controls">
                    <h4>Default Subscription Settings</h4>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Default Return URL</small></label>
                  <div class="controls">
                    <input type="text" class="input span12" data-name="product_subscription_default_confirmed" id="product_subscription_default_confirmed" placeholder="Enter URL to redirect after payment is completed" value="<?php echo isset($options['prod_subs_default_return']) ? $options['prod_subs_default_return'] : '' ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Default Cancel URL</small></label>
                  <div class="controls">
                    <input type="text" class="input span12" data-name="product_subscription_default_cancelled" id="product_subscription_default_cancelled" placeholder="Enter URL to redirect after payment is cancelled" value="<?php echo isset($options['prod_subs_default_cancel']) ? $options['prod_subs_default_cancel'] : '' ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Default Plan Type</small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input chosen-select" data-name="product_subscription_default_type" id="product_subscription_default_type" >
                          <option value="INFINITE" <?php echo isset($options['prod_subs_default_type']) && $options['prod_subs_default_type'] == 'INFINITE' ? 'selected' : '' ?>>INFINITE</option>
                          <option value="FIXED" <?php echo isset($options['prod_subs_default_type']) && $options['prod_subs_default_type'] == 'FIXED' ? 'selected' : '' ?>>FIXED</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>Indicates whether the payment definitions in the plan have a fixed number of or infinite payment cycles.</em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Default Auto Billing</small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input chosen-select" data-name="product_subscription_default_auto_billing" id="product_subscription_default_auto_billing" >
                          <option value="YES" <?php echo isset($options['prod_subs_default_auto_billing']) && $options['prod_subs_default_auto_billing'] == 'YES' ? 'selected' : '' ?>>YES</option>
                          <option value="NO" <?php echo isset($options['prod_subs_default_auto_billing']) && $options['prod_subs_default_auto_billing'] == 'NO' ? 'selected' : '' ?>>NO</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>Indicates whether PayPal automatically bills the outstanding balance in the next billing cycle. The outstanding balance is the total amount of any previously failed scheduled payments. </em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Default Initial Fail Action</small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input chosen-select" data-name="product_subscription_default_initial_fail_action" id="product_subscription_default_initial_fail_action" >
                          <option value="CONTINUE" <?php echo isset($options['prod_subs_default_initial_fail_action']) && $options['prod_subs_default_initial_fail_action'] == 'CONTINUE' ? 'selected' : '' ?>>CONTINUE</option>
                          <option value="CANCEL" <?php echo isset($options['prod_subs_default_initial_fail_action']) && $options['prod_subs_default_initial_fail_action'] == 'CANCEL' ? 'selected' : '' ?>>CANCEL</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>The action if the customer's initial payment fails. </em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Default Max Fail Attempts</small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <input type="text" class="input span12" data-name="product_subscription_default_max_fail_attempts" id="product_subscription_default_max_fail_attempts" placeholder="Enter Max Fail Attempts" value="<?php echo isset($options['prod_subs_default_max_fail_attempts']) ? $options['prod_subs_default_max_fail_attempts'] : '' ?>"  />
                      </div>
                      <div class="span8">
                        <p><em>The maximum number of allowed failed payment attempts. The default value, which is 0, defines infinite failed payment attempts. </em></p>
                      </div>
                    </div>
                  </div>
                </div>

                <hr>
                <div class="control-group">
                  <label class="control-label"></label>
                  <div class="controls">
                    <h4>Default Agreement Settings</h4>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Default Agreement Name</small></label>
                  <div class="controls">
                    <input type="text" class="input span12" data-name="product_subscription_default_agreement_name" id="product_subscription_default_agreement_name" placeholder="Enter the name of the plan (Max: 128 characters)" value="<?php echo isset($options['prod_subs_default_agreement_name']) ? $options['prod_subs_default_agreement_name'] : '' ?>"  />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Default Agreement Description</small></label>
                  <div class="controls">
                    <textarea rows="5" class="input span12"  data-name="product_subscription_default_agreement_description" id="product_subscription_default_agreement_description" placeholder="Enter the description of the plan (Max: 128 characters)" ><?php echo isset($options['prod_subs_default_agreement_description']) ? $options['prod_subs_default_agreement_description'] : '' ?></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="widget-box" id="container-billing-period-item" >
          <div class="widget-header header-color-blue2">
            <h5>Products Default Billing Period</h5>
            <div class="widget-toolbar">
              <a href="javascript:void(0)" data-action="collapse">
                <i class="icon-chevron-up"></i>
              </a>
            </div>
          </div>

          <div class="widget-body">
            <div class="widget-main">
              <div id="container-product-default-billing-period-items">
                <table id="table-billing-period" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Default</th>
                      <th>Enable</th>
                      <th>Name</th>
                      <th>Frequency</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <hr>
              <div class="text-center">
                <button class="btn btn-success btn-medium bigger" data-rel="tooltip" title="Add Items for Billing Period" id="btn-add-billimg-subscription-plan">
                  <i class="icon-plus"></i>
                  Add Default Billing Period
                </button>
              </div>
            </div>
          </div>
        </div>
			</div>
			<div class="span3 sticky">
				<div class="well well-small">
					<button class="btn btn-success btn-primary" type="button" id="save_subscription" style="width: 100%">
						<i class="icon-save bigger-110"></i> Save
					</button>
				</div>
        <div class="well well-small" id="subscription-error-warning" style="display: none;"></div>
			</div>
		</div>
	</div>
</div>

<!-- Modals -->
<div id="modal-paypal-subscription-plan-detail" class="modal modal-subscription fade" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Paypal Subscription Plan Detail </h4>
    <br>
    <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#tab-subscription-1">
          <i class="blue icon-cogs bigger-110"></i>
          Subscription Detail
        </a>
      </li>

      <li >
        <a data-toggle="tab" href="#tab-subscription-2">
          <i class="blue icon-check-sign bigger-110"></i>
          Agreement
        </a>
      </li>

      <li >
        <a data-toggle="tab" href="#tab-subscription-3">
          <i class="blue icon-group bigger-110"></i>
          Subscribers
        </a>
      </li>
    </ul>
  </div>
  <div class="modal-body">
    <div id="subscription-plan-options-loading" style="display: none;">
      <p class="text-center"><strong>Loading Subscription Plan...</strong></p>
    </div>

    <div class="modal-main" id="subscription-plan-options">
      <div class="tabbable">
        <div class="tab-content">
          <div id="tab-subscription-1" class="tab-pane in active">
            <div class="hide">
              <input type="hide" id="subscription-plan-item-id" data-description="This contains the ID of the table.">
            </div>
            <div class="form-horizontal row-fluid">
              <div class="control-group">
                <label class="control-label"><small>Plan ID: </small></label>
                <div class="controls">
                  <input type="text" class="input span12" id="paypal_subscription_plan_id" disabled="disabled"/>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Plan Name: </small></label>
                <div class="controls">
                  <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_plan_name" placeholder="Enter Plan name" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Plan Description: </small></label>
                <div class="controls">
                  <textarea rows="5" class="input method_option span12 subscription_field" data-name="paypal_subscription_plan_description" placeholder="Enter the description of the plan (Max: 127 characters)" ></textarea>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Return URL</small></label>
                <div class="controls">
                  <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_url_confirmed" placeholder="Enter URL to redirect after payment is completed" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Cancel URL</small></label>
                <div class="controls">
                  <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_url_cancelled" placeholder="Enter URL to redirect after payment is cancelled" />
                </div>
              </div>


              <br>
              <div id="container-subscription-detail-advance">
                <div class="control-group">
                  <label class="control-label"><small>Plan Type: </small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_plan_type" id="paypal_subscription_plan_type" >
                          <option value="INFINITE">INFINITE</option>
                          <option value="FIXED">FIXED</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>Indicates whether the payment definitions in the plan have a fixed number of or infinite payment cycles.</em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Auto Billing: </small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_plan_auto_billing" >
                          <option value="YES">YES</option>
                          <option value="NO">NO</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>Indicates whether PayPal automatically bills the outstanding balance in the next billing cycle. The outstanding balance is the total amount of any previously failed scheduled payments. </em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Initial Fail Action: </small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_plan_initial_fail_action" >
                          <option value="CONTINUE">CONTINUE</option>
                          <option value="CANCEL">CANCEL</option>
                        </select>
                      </div>
                      <div class="span8">
                        <p><em>The action if the customer's initial payment fails. </em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Max Fail Attempts: </small></label>
                  <div class="controls">
                    <div class="row-fluid">
                      <div class="span4">
                        <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_plan_max_fail_attempts" placeholder="Enter Max Fail Attempts" />
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
                      <label class="control-label"><small>Trial Title</small></label>
                      <div class="controls">
                        <textarea type="text" row="5" class="input span12 method_option subscription_field" style="max-width: 100%; min-width: 100%;" data-name="paypal_subscription_title_trial" placeholder="Enter title for trial."></textarea>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><small>Amount</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_amount_trial" placeholder="Enter trial amount. Set to 0 if free." />
                        </div>
                        <div class="span8">
                          <p><em>The amount of the charge to make at the end of each payment cycle for this definition.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><small>Frequency</small></label>
                      <div class="controls">
                        <div class="span4">
                          <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_frequency_trial" id="paypal_subscription_frequency_trial" >
                            <option value="DAY">DAY</option>
                            <option value="WEEK">WEEK</option>
                            <option value="MONTH">MONTH</option>
                            <option value="YEAR">YEAR</option>
                          </select>
                        </div>
                        <div class="span8">
                          <p><em>The frequency of the payment in this definition.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><small>Frequency Interval</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="number" class="input span12 method_option subscription_field" data-name="paypal_subscription_frequency_interval_trial" placeholder="Enter frequency interval" id="paypal_subscription_frequency_interval_trial" min="1" />
                        </div>
                        <div class="span8">
                          <p><em>The interval at which the customer is charged. Value cannot be greater than <strong>12</strong> months.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><small>Cycle</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="number" class="input span12 method_option subscription_field" data-name="paypal_subscription_cycle_trial" placeholder="Enter payment cycle" id="paypal_subscription_cycle_trial" min="1" />
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
                      <label class="control-label"><small>Regular Title</small></label>
                      <div class="controls">
                        <textarea type="text" row="5" class="input span12 method_option subscription_field" data-name="paypal_subscription_title" placeholder="Enter title for regular payment." style="max-width: 100%; min-width: 100%;"></textarea>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><small>Amount</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_amount" placeholder="Enter the Regular Subscription amount" />
                        </div>
                        <div class="span8">
                          <p><em>The amount of the charge to make at the end of each payment cycle for this definition.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><small>Frequency</small></label>
                      <div class="controls">
                        <div class="span4">
                          <select class="input method_option chosen-select subscription_field" data-name="paypal_subscription_frequency" id="paypal_subscription_frequency" >
                            <option value="DAY">DAY</option>
                            <option value="WEEK">WEEK</option>
                            <option value="MONTH">MONTH</option>
                            <option value="YEAR">YEAR</option>
                          </select>
                        </div>
                        <div class="span8">
                          <p><em>The frequency of the payment in this definition.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><small>Frequency Interval</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="number" class="input span12 method_option subscription_field" data-name="paypal_subscription_frequency_interval" placeholder="Enter frequency interval" id="paypal_subscription_frequency_interval" min="1" />
                        </div>
                        <div class="span8">
                          <p><em>The interval at which the customer is charged. Value cannot be greater than <strong>12</strong> months.</em></p>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><small>Cycle</small></label>
                      <div class="controls">
                        <div class="span4">
                          <input type="number" class="input span12 method_option subscription_field" data-name="paypal_subscription_cycle" placeholder="Enter payment cycle" id="paypal_subscription_cycle" min="1" />
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
          <div id="tab-subscription-2" class="tab-pane">
            <div class="form-horizontal row-fluid">
              <div class="control-group">
                <label class="control-label"><small>Name: </small></label>
                <div class="controls">
                  <input type="text" class="input span12 method_option subscription_field" data-name="paypal_subscription_agreement_name" placeholder="Enter Plan name" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Description: </small></label>
                <div class="controls">
                  <textarea rows="5" class="input method_option span12 subscription_field" data-name="paypal_subscription_agreement_description" placeholder="Enter the description of the plan (Max: 127 characters)" ></textarea>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-subscription-3" class="tab-pane">
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
                          <th>Product</th>
                          <th>Reference</th>
                          <th >Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-medium btn-success pull-left" id="btn-activate-paypal-subscription-plan"><i class="icon-check"></i> Activate</button>
    <button class="btn btn-medium btn-danger pull-left" id="btn-delete-paypal-subscription-plan"><i class="icon-trash"></i> Delete</button>
    <button class="btn btn-medium btn-primary" id="btn-save-paypal-subscription-plan"><i class="icon-save"></i> Save</button>
    <button class="btn btn-medium" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
  </div>
</div>
<div id="modal-product-default-billing-period" class="modal modal-subscription fade" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Products Default Billing Periods </h4>
  </div>
  <div class="modal-body">
    <div id="modal-billing-period-loading" style="display: none;">
      <p class="text-center"><strong>Loading Billing Plan...</strong></p>
    </div>

    <div class="modal-main" id="modal-billing-period-item">
      <div class="hide">
        <input type="hide" id="default-billing-period-item-id" data-description="This contains the ID of the table.">
      </div>
      <div class="form-horizontal row-fluid">
        <div class="control-group">
          <label class="control-label">Billing Period Name</label>
          <div class="controls">
            <input type="text" class="input span12 billing_period_field" data-name="billing_period_name" placeholder="Enter Billing Period Name." id="billing_period_name" />
          </div>
        </div>
      </div>
      <hr>
      <div class="tabbable">
        <ul class="nav nav-tabs">
          <li class="active" style="width: 45%; margin-left: 5%;">
            <a data-toggle="tab" href="#tab-billing-period-1">
              <i class="blue icon-calendar bigger-110"></i>
              Regular Payment
            </a>
          </li>
          <li style="width: 45%;">
            <a data-toggle="tab" href="#tab-billing-period-2">
              <i class="blue icon-bookmark-empty bigger-110"></i>
              Trial
            </a>
          </li>
        </ul>

        <div class="tab-content">
          <div id="tab-billing-period-1" class="tab-pane in active">
            <div class="form-horizontal row-fluid">
              <div class="control-group">
                <label class="control-label">Regular Payment Title</label>
                <div class="controls">
                  <textarea type="text" row="5" class="input span12 billing_period_field" data-name="billing_period_title" placeholder="Default Regular Payment Name." style="max-width: 100%; min-width: 100%;"></textarea>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Amount</small></label>
                <div class="controls">
                  <div class="span12">
                    <p><em>The default value for the amount will be the price of the product.</em></p>
                  </div>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Frequency</small></label>
                <div class="controls">
                  <div class="span4">
                    <select class="input chosen-select billing_period_field" data-name="billing_period_frequency" id="paypal_subscription_frequency" >
                      <option value="DAILY">DAILY</option>
                      <option value="MONTH">MONTH</option>
                      <option value="YEAR">YEAR</option>
                    </select>
                  </div>
                  <div class="span8">
                    <p><em>The frequency of the payment in this definition.</em></p>
                  </div>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Frequency Interval</small></label>
                <div class="controls">
                  <div class="span4">
                    <input type="number" class="input span12 billing_period_field" data-name="billing_period_frequency_interval" placeholder="Enter frequency interval" id="paypal_subscription_frequency_interval" min="1" />
                  </div>
                  <div class="span8">
                    <p><em>The interval at which the customer is charged. Value cannot be greater than <strong>12</strong> months.</em></p>
                  </div>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"><small>Cycle</small></label>
                <div class="controls">
                  <div class="span4">
                    <input type="number" class="input span12 billing_period_field" data-name="billing_period_cycle" placeholder="Enter payment cycle" id="paypal_subscription_cycle" min="0" />
                  </div>
                  <div class="span8">
                    <p><em>The number of payment cycles. For infinite plans with a regular payment definition, set cycles to <strong>0</strong>.</em></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="tab-billing-period-2" class="tab-pane">
            <br>
            <div class="form-horizontal row-fluid">
              <div class="control-group">
                <label class="control-label"><small>Enable</small></label>
                <div class="controls">
                  <label>
                    <input name="billing_period_enable" id="billing_period_enable" data-name="billing_period_enable" class="billing_period_enable ace ace-switch ace-switch-7" type="checkbox">
                    <span class="lbl"></span>
                  </label>
                </div>
              </div>

              <div id="container-billing-period-trial">
                <div class="control-group">
                  <label class="control-label"><small>Trial Title</small></label>
                  <div class="controls">
                    <textarea type="text" row="5" class="input span12 method_option billing_period_field" style="max-width: 100%; min-width: 100%;" data-name="billing_period_title_trial" placeholder="Enter title for trial."></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Amount</small></label>
                  <div class="controls">
                    <div class="span4">
                      <input type="text" class="input span12 method_option billing_period_field" data-name="billing_period_amount_trial" placeholder="Enter trial amount. Set to 0 if free." />
                    </div>
                    <div class="span8">
                      <p><em>This will be the default value of products trial amount.</em></p>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Frequency</small></label>
                  <div class="controls">
                    <div class="span4">
                      <select class="input method_option chosen-select billing_period_field" data-name="billing_period_frequency_trial" id="paypal_subscription_frequency_trial" >
                        <option value="DAY">DAY</option>
                        <option value="WEEK">WEEK</option>
                        <option value="MONTH">MONTH</option>
                        <option value="YEAR">YEAR</option>
                      </select>
                    </div>
                    <div class="span8">
                      <p><em>The frequency of the payment in this definition.</em></p>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Frequency Interval</small></label>
                  <div class="controls">
                    <div class="span4">
                      <input type="number" class="input span12 method_option billing_period_field" data-name="billing_period_frequency_interval_trial" placeholder="Enter frequency interval" id="paypal_subscription_frequency_interval_trial" min="1" />
                    </div>
                    <div class="span8">
                      <p><em>The interval at which the customer is charged. Value cannot be greater than <strong>12</strong> months.</em></p>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><small>Cycle</small></label>
                  <div class="controls">
                    <div class="span4">
                      <input type="number" class="input span12 method_option billing_period_field" data-name="billing_period_cycle_trial" placeholder="Enter payment cycle" id="paypal_subscription_cycle_trial" min="1" />
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
  <div class="modal-footer">
    <button class="btn btn-medium btn-primary" id="btn-save-billing-period"><i class="icon-save"></i> Save</button>
    <button class="btn btn-medium" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
  </div>
</div>
<div id="modal-documentation" class="modal modal-subscription fade" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Paypal Subscription Guide</h4>
  </div>
  <div class="modal-body">
    <div class="modal-main">
      <?php include 'subscription-guide.php'; ?>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-medium" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
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
            <li class="text-center"><strong>${title}</strong></li>
            <li class="text-center">${transaction_id}</li>
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
