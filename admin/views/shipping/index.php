<?php
$_other_data = array(
  "Array(
  [default] => stdClass Object(
    [area_id] => 0
    [rate_name] => International Rate
    [rate_description] => \"sample Description\"
    [rate_type] => default
    [rate_min] => 0
    [rate_max] => 0
    [rate_free] => N
    [rate_amount] => 1500
    )
  [area] => Array(
    [Regular Rating] => Array(
      [detail] => stdClass Object(
        [area_name] => Regular Rating
        [area_id] => 1
        [rate_name] => Rate
        [rate_description] => \"sample Description\"
        [rate_type] => other-method
        [rate_min] => 0
        [rate_max] => 1000
        [rate_free] => N
        [rate_amount] => 20
        [country_ids] => 213,376,244
        [main_id] => 1
        [date_added] => 2018-07-12 14:46:25
        [date_create] => 2018-07-12 14:18:38
        )
      [rate] => Array(
        [0] => Array(
          [rate_name] => Rate
          [rate_description] => \"sample Description\"
          [rate_type] => other-method
          [rate_min] => 0
          [rate_max] => 1000
          [rate_free] => N
          [rate_amount] => 20
          [countries] => 213,376,244
          )
        [1] => Array(
          [rate_name] => WB1
          [rate_description] => asdad
          [rate_type] => weight-base
          [rate_min] => 0
          [rate_max] => 10
          [rate_free] => Y
          [rate_amount] => 0
          [countries] => 213,376,244
          )
        )
      [country] => Array(
        [213] => Algeria
        [376] => Andorra
        [244] => Angola
        )
      )
    [Local] => Array(
      [detail] => stdClass Object(
        [area_name] => Local
        [area_id] => 2
        [rate_name] => Test Rate
        [rate_description] => \"sample Description\"
        [rate_type] => other-method
        [rate_min] => 0
        [rate_max] => 100
        [rate_free] => Y
        [rate_amount] => 0
        [country_ids] => 1268,1242
        [main_id] => 2
        [date_create] => 2018-07-13 16:54:54
        [date_added] => 2018-07-13 16:54:55
        )
      [rate] => Array(
        [0] => Array(
          [rate_name] => Test Rate
          [rate_description] => \"sample Description\"
          [rate_type] => other-method
          [rate_min] => 0
          [rate_max] => 100
          [rate_free] => Y
          [rate_amount] => 0
          [countries] => 1268,1242
          )
        )
      [country] => Array(
        [1268] => Antigua & Barbuda
        [1242] => Bahamas
        )
      )
    )
  )",
);
?>

<style>
  #shipping-rates-container .widget-main{ padding: 5px; }
  .shipping-areas-container .shipping-area{ padding: 10px; }
  .shipping-areas-container .shipping-area:hover{ background-color: #f9f9f9; }
</style>

<div class="main-content">
  <div class="page-content">
    <div class="page-header">
      <h1>
         Shipping    
      </h1>
    </div>
    <div class="row-fluid">
    	<div class="span12">

        <div class="row-fluid">
          <div class="span4">
            <h4>Shipping Options</h4>
            <p>Set shipping options</p>
            <br>
            <p><a href="javascript:void(0)" id="btn-toggle-shipping-guide"><strong>Shipping Rate</strong> guide for frontend implementation here</a>.</p>
          </div>
          <div class="span8" id="shipping-default-rates-container">
            <div class="widget-box" >
              <div class="widget-header header-color-blue">
                <h5>Options</h5>
              </div>

              <div class="widget-body">
                <div class="widget-main">
                  <div class="form-horizontal">
                    <div class="control-group">
                      <label class="control-label" for="shipping_option_enable"><small>Enable Shipping</small></label>
                      <div class="controls">
                        <label>
                          <input name="shipping_option_enable" class="ace ace-switch ace-switch-7" type="checkbox" id="shipping_option_enable" />
                          <span class="lbl"></span>
                        </label>
                        <p><small>Shipping detail will be use in product checkout cost. </small></p>
                      </div>
                    </div>
                    <hr>
                    <div class="control-group">
                      <label class="control-label"></label>
                      <div class="controls">
                        <button class="btn btn-small btn-success" id="btn-shipping-option-save"><i class="icon icon-save"></i> Save</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row-fluid">
          <div class="span4">
            <h4>Shipping Origin Address</h4>
            <p>This address is used for shipping rates and shipping labels. The address used to calculate shipping rates</p>
          </div>
          <div class="span8">
            <div class="widget-box" id="shipping-origin-container">
              <div class="widget-header header-color-blue">
                <h5>Shipping Origin Address</h5>

                <div class="widget-toolbar no-border">
                  <button class="btn btn-mini btn-primary" id="btn-edit-shipping-origin-address">
                    <i class="icon-edit"></i>
                    Edit
                  </button>
                </div>
              </div>

              <div class="widget-body">
                <div class="widget-main">
                  <address>
                    <strong class="shipping-origin-title">----</strong>
                    <br>
                    <span class="shipping-origin-address-1">---,---,---</span>
                    <br>
                    <span class="shipping-origin-postal-city">---- ----</span>
                    <br>
                    <span class="shipping-origin-country">---------</span>
                    <br>
                    <abbr title="Phone">P:</abbr>
                    <span class="shipping-origin-phone">000-0000</span>
                  </address>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row-fluid">
          <div class="span4">
            <h4>Shipping Default Rate</h4>
            <p>Set the default shipping rate</p>
            <br>
          </div>
          <div class="span8" id="shipping-default-rates-container">
            <div class="widget-box" >
              <div class="widget-header header-color-blue">
                <h5>Shipping Default Rate</h5>

                <div class="widget-toolbar no-border">
                  <button class="btn btn-mini btn-primary bigger" id="btn-add-shipping-default-rate">
                    <i class="icon-edit"></i>
                    Edit
                  </button>
                </div>
              </div>

              <div class="widget-body">
                <div class="widget-main">
                  <div class="shipping-areas-container">
                    <p>Shipping Name <strong class="pull-right" id="shipping-default-rate-name">International Shipping Rate</strong></p>
                    <p>Rate <strong class="pull-right" id="shipping-default-rate-amount">$1000.00</strong></p>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

    		<div class="row-fluid">
		      <div class="span4">
		      	<h4>Shipping Areas and Rates</h4>
		      	<p>Use shipping areas to choose the regions that you ship to, and define your shipping rates for each area</p>
		      </div>
		      <div class="span8" id="shipping-rates-container">
		    		<div class="widget-box" >
							<div class="widget-header header-color-blue">
								<h5>Shipping Area</h5>

								<div class="widget-toolbar no-border">
									<button data-href="<?php echo URL . "shipping/area/add"; ?>" class="btn btn-mini btn-primary bigger" id="btn-add-shipping-area-link">
										<i class="icon-plus"></i>
										Add
									</button>
								</div>
							</div>

							<div class="widget-body">
								<div class="widget-main">
									<div class="shipping-areas-container accordion accordion-style2" id="shipping-area-container" style="margin-bottom: 0px;"></div>
								</div>
							</div>

						</div>
		    	</div>
    		</div>

    	</div>
    </div>
  </div>
</div>

<div id="template-container" class="hide">
	<div id="template-area">
		<div class="widget-box transparent shipping-area" >
			<div class="widget-header header-color-blue">
				<h5 class="area-name"><i class="icon-globe"></i><strong>Asia</strong></h5>

				<div class="widget-toolbar no-border">
					<a href="#" data-value="" class="area-id">
						<i class="icon-cog"></i>
						Edit
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row-fluid">
						<div class="span12 area-countries"></div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<h5><b>Rates</b></h5>
							<dl class="rate-list"></dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="template-rate">
		<dt>Standard Shipping</dt>
		<dd>0.0 kg – 5.0 kg <span class="pull-right">$8.00</span></dd>
	</div>
</div>

<div id="shipping-origin-modal" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      	<h3 class="header smaller lighter green">Shipping Origin Address</h3>
      </div>
      <div class="modal-body">
        <form class="form" id="shipping-origin-form" action="<?php echo URL;?>shipping/ajax_shipping_origin_processor" enctype="multipart/form-data" method="post">
          <div class="row-fluid">
            <div class="span12">
              <div class="alert_customer_details"></div>
              <div class="hide">
              	<input type="hidden" name="operation" value="update">
              </div>
              <div class="control-group">
                <label class="control-label" for="shipping_origin_name"><span class="red">*</span> Shipping Name:</label>
                <div class="controls">
                  <input type="text" id="shipping_origin_name" name="shipping_origin_name" class="span12" value="">
                  <input type="hidden" id="shipping_origin_name_hdn" class="span12" value="">           
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="shipping_origin_address_1"><span class="red">*</span> Address Line 1:</label>
                <div class="controls">
                  <textarea name="shipping_origin_address_1" id="shipping_origin_address_1" cols="30" rows="5" class="span12"></textarea>
                  <input type="hidden" id="shipping_origin_address_1_hdn" class="span12" value="">           
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="shipping_origin_address_2"><span class="red">*</span> Address Line 2:</label>
                <div class="controls">
                  <textarea name="shipping_origin_address_2" id="shipping_origin_address_2" cols="30" rows="5" class="span12"></textarea>
                  <input type="hidden" id="shipping_origin_address_2_hdn" class="span12" value="">           
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="shipping_origin_city"><span class="red">*</span> City:</label>
                <div class="controls">
                  <input type="text" id="shipping_origin_city" name="shipping_origin_city" class="span12" value="">
                  <input type="hidden" id="shipping_origin_city_hdn" class="span12" value="">           
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="shipping_origin_postal"><span class="red">*</span> Zip/Postal Code:</label>
                <div class="controls">
                  <input type="text" id="shipping_origin_postal" name="shipping_origin_postal" class="span12" value="">
                  <input type="hidden" id="shipping_origin_postal_hdn" class="span12" value="">           
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="shipping_origin_country"><span class="red">*</span> Country:</label>
                <div class="controls">
                  <select id="shipping_origin_country" class="chosen_top" name="shipping_origin_country">
                  <?php foreach ($countries as $key => $value): ?>
                  <?php if ($key == ''): ?>
                    <?php foreach ($value as $k => $v): ?>
                    <option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" ><?php echo "{$v->name} (+{$v->value})"; ?></option>
                    <?php endforeach ?>
                  <?php elseif ($key == 'others'): ?>
                    <optgroup label="Other countries">
                    <?php foreach ($value as $k => $v): ?>
                      <option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" ><?php echo "{$v->name} (+{$v->value})"; ?></option>
                    <?php endforeach ?>
                    </optgroup>
                  <?php endif ?>
                  <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="shipping_origin_phone"><span class="red">*</span> Phone:</label>
                <div class="controls">
                  <input type="text" id="shipping_origin_phone" name="shipping_origin_phone" class="span12" value="">
                  <input type="hidden" id="shipping_origin_phone_hdn" class="span12" value="">           
                </div>
              </div>

            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
      	<a class="btn btn-primary" href="javascript:void(0);" id="btn-save-shipping-origin-address">OK</a>
        <a data-dismiss="modal" class="btn null" href="javascript:void(0);">Cancel</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div id="shipping-default-rate-modal" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Shipping Default Rate</h3>
      </div>
      <div class="modal-body">
        <div id="modal-default-rate-container" class="row-fluid">
          <div class="span12">
            <div class="form form-horizontal">
              <div class="control-group">
                <label class="control-label" for="shipping_default_rate_name"><strong>Shipping Name:</strong></label>
                <div class="controls">
                  <input type="text" id="shipping_default_rate_name" name="shipping_default_rate_name" class="span12" value="">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="shipping_default_rate"><strong>Rate:</strong></label>
                <div class="controls">
                  <input type="text" id="shipping_default_rate" name="shipping_default_rate" class="span12" value="">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="moda-default-rate-loading" style="display: none;">
          Loading content... Please wait...
        </div>
      </div>
      <div class="modal-footer">
        <div class="form form-horizontal">
          <div class="control-group">
            <label class="control-label" for="shipping_origin_name"></label>
            <div class="controls text-left">
              <button class="btn btn-primary" id="btn-save-shipping-default-rate">OK</a>
              <button data-dismiss="modal" class="btn null" href="javascript:void(0);">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div id="shipping-guide-modal" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Shipping Rate Frontend Function Guide</h3>
      </div>
      <div class="modal-body">
        <p>Use function <strong>cms_get_shipping_info(&lt;String&gt;)</strong> to get <strong>Shipping Rate</strong> detail. </p>
        <p>Functions: <strong>cms_get_shipping_info()</strong><br>
          Parameter: <strong>String</strong><br>-Name of Shipping Area you want to retrieve. <br>-If empty, it will return all registered shipping area in the system.</p>
        <p>Output: <strong>JSON</strong> object of shipping detail.</p>
        <pre><?php echo $_other_data[0]; ?></pre>
        <br>
        <h5><strong>Definition:</strong></h5>
        <p>
          <strong>[ default ]</strong> -Contains default Shipping Rate info. <br>
          <strong>[ area ]</strong> -List of Shipping Area. <br>
          <strong>[ detail ]</strong> -Contains info for Shipping Area. <br>
          <strong>[ rate ]</strong> -List of rates under a Shipping Area. <br>
          <strong>[ country ]</strong> -List of countries included in the shipping rate. <br><br>
          <strong>[ area_name ]</strong> - Shipping Area name. <br>
          <strong>[ area_id ]</strong> - Shipping Area id. <br>
          <strong>[ rate_name ]</strong> - Shipping Area Rate name. <br>
          <strong>[ rate_description ]</strong> - Shipping Area description. <br>
          <strong>[ rate_type ]</strong> - Shipping Area type <br>(<strong>price-base</strong> / <strong>weight-base</strong> / <strong>item-base</strong> / <strong>order-base</strong> / <strong>other-method</strong> / <strong>default</strong>) <br><br>
          <strong>[ rate_min ]</strong> - Shipping Area minimum range. <br>
          <strong>[ rate_max ]</strong> - Shipping Area maximum range. <br>
          <strong>[ rate_free ]</strong> - Free shipping indicator (N/Y) <br>
          <strong>[ rate_amount ]</strong> - Shipping Rate. <br>
          <strong>[ country_ids ]</strong> - Comma separated ID of countries included in a Shipping Area. <br>
        </p>
      </div>
      <div class="modal-footer">
        <div class="text-left">
          <button data-dismiss="modal" class="btn btn-primary"><i class="icon icon-check"></i>Ok</button>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>


<script id="tmpl-shipping-area" type="text/x-jquery-tmpl"> 
<div class="accordion-group">
  <div class="accordion-heading">
    <a href="#shipping-area-item-${id}" data-parent="#shipping-area-collapse-container" data-toggle="collapse" class="accordion-toggle shipping-area-name-lbl">Area: <strong>[shilling_area_name]</strong></a>
  </div>
  <div class="accordion-body in collapse" id="shipping-area-item-${id}" style="height: auto;">
    <div class="accordion-inner">
      <div class="row-fluid">
        <div class="span8">
          <div class="country-container">
            <div class="row-fluid">
              <div class="span4">
                <h5><strong>Rate</strong></h5>
              </div>
              <div class="span4">
                <h5><strong>Range</strong></h5>
              </div>
              <div class="span4">
                <h5><strong>Amount</strong></h5>
              </div>
            </div>
            <div class="rates-list"></div>
          </div>
        </div>
        <div class="span4">
          <div class="country-container">
            <h5><strong>Countries</strong></h5>
            <div class="country-list"></div>
          </div>
        </div>
      </div>
      <hr>
      <div>
        <a href="javascript:void(0)" class="btn btn-small btn-success btn-edit-shipping-area"><i class="icon-edit"></i> Edit</a>
      </div>
    </div>
  </div>
</div>
</script> 
<script id="tmpl-shipping-area-rate" type="text/x-jquery-tmpl"> 
  <div class="row-fluid">
    <div class="span4">
      <div class="rate-name"></div>
    </div>
    <div class="span4">
      <div class="rate-range"></div>
    </div>
    <div class="span4">
      <div class="rate-amount"></div>
    </div>
  </div>
</script> 