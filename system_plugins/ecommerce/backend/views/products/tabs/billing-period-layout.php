<div class="modal modal-large fade" id="modal-product-billing-period">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Product Period Subcsription </h4>
  </div>
  <div class="modal-body">
    <div class="modal-dialog">
      <div id="subscription-plan-options-loading" style="display: none;">
        <p class="text-center"><strong>Loading Subscription Plan...</strong></p>
      </div>
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
      </ul>
      <div class="modal-main" id="subscription-plan-options">
        <div class="tabbable">
          <div class="tab-content">
            <div id="tab-subscription-1" class="tab-pane in active">
              <div class="hide">
                <input type="hide" id="subscription-plan-item-id" data-description="This contains the ID of the table.">
              </div>
              <div class="form-horizontal row-fluid">
                <div class="control-group">
                  <label class="control-label" for=""><small>Plan Name: </small></label>
                  <div class="controls">
                    <input type="text" class="input span12 method_option" id="paypal_subscription_plan_name" data-name="paypal_subscription_plan_name" placeholder="Enter Plan name" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for=""><small>Plan Description: </small></label>
                  <div class="controls">
                    <textarea rows="5" class="input method_option span12" id="paypal_subscription_plan_description" data-name="paypal_subscription_plan_description" placeholder="Enter the description of the plan (Max: 127 characters)" ></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for=""><small>Return URL</small></label>
                  <div class="controls">
                    <input type="text" class="input span12 method_option" id="paypal_subscription_url_confirmed" data-name="paypal_subscription_url_confirmed" placeholder="Enter URL to redirect after payment is completed" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for=""><small>Cancel URL</small></label>
                  <div class="controls">
                    <input type="text" class="input span12 method_option" id="paypal_subscription_url_cancelled" data-name="paypal_subscription_url_cancelled" placeholder="Enter URL to redirect after payment is cancelled" />
                  </div>
                </div>

                <br>
                <div id="container-subscription-detail-advance">
                  <div class="control-group">
                    <label class="control-label" for=""><small>Plan Type </small></label>
                    <div class="controls">
                      <div class="row-fluid">
                        <div class="span4">
                          <select class="input method_option chosen-select" id="paypal_subscription_plan_type" data-name="paypal_subscription_plan_type" id="paypal_subscription_plan_type" >
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
                    <label class="control-label" for=""><small>Auto Billing: </small></label>
                    <div class="controls">
                      <div class="row-fluid">
                        <div class="span4">
                          <select class="input method_option chosen-select" id="paypal_subscription_plan_auto_billing" data-name="paypal_subscription_plan_auto_billing" >
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
                    <label class="control-label" for=""><small>Initial Fail Action: </small></label>
                    <div class="controls">
                      <div class="row-fluid">
                        <div class="span4">
                          <select class="input method_option chosen-select" id="paypal_subscription_plan_initial_fail_action" data-name="paypal_subscription_plan_initial_fail_action" >
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
                    <label class="control-label" for=""><small>Max Fail Attempts: </small></label>
                    <div class="controls">
                      <div class="row-fluid">
                        <div class="span4">
                          <input type="number" class="input span12 method_option" id="paypal_subscription_plan_max_fail_attempts" data-name="paypal_subscription_plan_max_fail_attempts" placeholder="Enter Max Fail Attempts" min="0" />
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
                  <li class="active" style="width: 45%; margin-left: 5%;">
                    <a data-toggle="tab" href="#tab-subscription-payment-1">
                      <i class="blue icon-calendar bigger-110"></i>
                      Regular Payment
                    </a>
                  </li>
                  <li style="width: 45%;">
                    <a data-toggle="tab" href="#tab-subscription-payment-2">
                      <i class="blue icon-bookmark-empty bigger-110"></i>
                      Trial
                    </a>
                  </li>
                </ul>

                <div class="tab-content" style="min-height: 364px;">
                  <div id="tab-subscription-payment-1" class="tab-pane in active">
                    <div class="form-horizontal row-fluid">
                      <div class="control-group">
                        <label class="control-label" for=""><small>Regular Title</small></label>
                        <div class="controls">
                          <textarea type="text" row="5" class="input span12 method_option" id="paypal_subscription_title" data-name="paypal_subscription_title" placeholder="Enter title for regular payment." style="max-width: 100%; min-width: 100%;"></textarea>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for=""><small>Amount</small></label>
                        <div class="controls">
                          <div class="span4">
                            <input type="text" class="input span12 method_option" id="paypal_subscription_amount" data-name="paypal_subscription_amount" placeholder="Enter the Regular Subscription amount" />
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
                            <select class="input method_option chosen-select" id="paypal_subscription_frequency" data-name="paypal_subscription_frequency" id="paypal_subscription_frequency" >
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
                        <label class="control-label" for=""><small>Frequency Interval</small></label>
                        <div class="controls">
                          <div class="span4">
                            <input type="number" class="input span12 method_option" id="paypal_subscription_frequency_interval" data-name="paypal_subscription_frequency_interval" placeholder="Enter frequency interval" id="paypal_subscription_frequency_interval" min="1" />
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
                            <input type="number" class="input span12 method_option" id="paypal_subscription_cycle" data-name="paypal_subscription_cycle" placeholder="Enter payment cycle" id="paypal_subscription_cycle" min="1" />
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
                        <label class="control-label" for=""><small>Enable Trial</small></label>
                        <div class="controls">
                          <label>
                            <input name="switch-field-1" class="ace ace-switch ace-switch-7" type="checkbox" id="paypal_subscription_enable_trial" >
                            <span class="lbl"></span>
                          </label>
                        </div>
                      </div>
                      <div id="container-billing-period-trial" style="display: none"">
                        <div class="control-group">
                          <label class="control-label" for=""><small>Trial Title</small></label>
                          <div class="controls">
                            <textarea type="text" row="5" class="input span12 method_option" style="max-width: 100%; min-width: 100%;" id="paypal_subscription_title_trial" data-name="paypal_subscription_title_trial" placeholder="Enter title for trial."></textarea>
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label" for=""><small>Amount</small></label>
                          <div class="controls">
                            <div class="span4">
                              <input type="text" class="input span12 method_option" id="paypal_subscription_amount_trial" data-name="paypal_subscription_amount_trial" placeholder="Enter trial amount. Set to 0 if free." />
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
                              <select class="input method_option chosen-select" id="paypal_subscription_frequency_trial" data-name="paypal_subscription_frequency_trial" id="paypal_subscription_frequency_trial" >
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
                          <label class="control-label" for=""><small>Frequency Interval</small></label>
                          <div class="controls">
                            <div class="span4">
                              <input type="number" class="input span12 method_option" id="paypal_subscription_frequency_interval_trial" data-name="paypal_subscription_frequency_interval_trial" placeholder="Enter frequency interval" id="paypal_subscription_frequency_interval_trial" min="1" />
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
                              <input type="number" class="input span12 method_option" id="paypal_subscription_cycle_trial" data-name="paypal_subscription_cycle_trial" placeholder="Enter payment cycle" id="paypal_subscription_cycle_trial" min="1" />
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
            <div id="tab-subscription-2" class="tab-pane">
              <div class="form-horizontal row-fluid">
                <div class="control-group">
                  <label class="control-label" for=""><small>Name: </small></label>
                  <div class="controls">
                    <input type="text" class="input span12 method_option" id="paypal_subscription_agreement_name" data-name="paypal_subscription_agreement_name" placeholder="Enter Plan name" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for=""><small>Description: </small></label>
                  <div class="controls">
                    <textarea rows="5" class="input method_option span12" id="paypal_subscription_agreement_description" data-name="paypal_subscription_agreement_description" placeholder="Enter the description of the plan (Max: 127 characters)" ></textarea>
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
    <button class="btn btn-medium btn-success" id="btn-save-paypal-subscription-plan" data-dismiss="modal" ><i class="icon-save"></i> Save</button>
    <button class="btn btn-medium" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
  </div>
</div>

<div class="modal fade" id="modal-product-billing-period-global-subscription-detail">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Global Subscription Detail </h4>
  </div>
  <div class="modal-body">
    <div class="modal-dialog">
      <div id="container-global-susbcription-detail" class="form-horizontal"></div>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-medium btn-success pull-left hide" id="btn-activate-paypal-subscription-plan" disabled="disabled"><i class="icon-check"></i> Activate</button>
    <button class="btn btn-medium" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
  </div>
</div>

<div class="modal modal-large fade" id="modal-product-downloadable-files" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Select Files </h4>
  </div>
  <div class="modal-body">
    <div class="modal-dialog">
      <table id="table-billing-period-media" class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th><div class="text-center"><label><input type="checkbox" class="item-checkbox" id="table-billing-period-media-check-all"><span class="lbl"></span></label></div></th>
            <th>File Name</th>
            <th>Type</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  <div class="modal-footer">
    <div class="text-center">
      <button class="btn btn-medium btn-primary" id="btn-downloadable-add-selected-files"><i class="icon-check"></i> Select</button>
      <button class="btn btn-medium" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
    </div>
  </div>
</div>

<div class="modal modal-large fade" id="modal-suspend-user-loading" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Suspending Account!!!</h4>
  </div>
  <div class="modal-body">
    <div class="modal-dialog">
      <p>Please wait for a moment while processing account suspension.</p>
    </div>
  </div>
</div>
<div class="modal modal-large fade" id="modal-reactivate-user-loading" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="bigger">Reactivating Account!!!</h4>
  </div>
  <div class="modal-body">
    <div class="modal-dialog">
      <p>Please wait for a moment while processing account reactivation.</p>
    </div>
  </div>
</div>


<script id="tmpl-product-billing-period-item" type="text/x-tmpl">
  <li><a href="javascript:void(0)" data-value="${id}">${filename}</a></li>
</script>