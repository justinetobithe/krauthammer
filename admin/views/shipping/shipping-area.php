<div class="main-content">
  <div class="page-content">
    <div class="page-header">
      <h1>
         Shipping Area (Add)
      </h1>
    </div>
    <div class="row-fluid">
    	<div class="span12">

    		<div class="row-fluid">
		      <div class="span8">

		    		<div class="widget-box" >
							<div class="widget-header header-color-blue">
								<h5>Country</h5>
							</div>
							<div class="widget-body">
								<div class="widget-main">
									<div id="shiping-area-country-list">
										<select id="shipping_origin_country" class="chosen-full-width tag-input-style hide" name="shipping_origin_country" data-placeholder="Select Country" multiple>
	                  <?php foreach ($countries as $key => $value): ?>
	                  <?php if ($key == ''): ?>
	                    <?php foreach ($value as $k => $v): ?>
	                    <option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" <?php if (in_array($v->value, $disabled_countries)) echo 'disabled="true"'; ?> ><?php echo "{$v->name} (+{$v->value})"; ?></option>
	                    <?php endforeach ?>
	                  <?php elseif ($key == 'others'): ?>
	                    <optgroup label="Other countries">
	                    <?php foreach ($value as $k => $v): ?>
	                      <option data-countryCode="<?php echo $v->code; ?>" value="<?php echo $v->value; ?>" <?php if (in_array($v->value, $disabled_countries)) echo 'disabled="true"'; ?> ><?php echo "{$v->name} (+{$v->value})"; ?></option>
	                    <?php endforeach ?>
	                    </optgroup>
	                  <?php endif ?>
	                  <?php endforeach ?>
	                  </select>
									</div>
									<p>Add country to this area.</p>
								</div>
							</div>
						</div>

		    		<div class="widget-box" >
							<div class="widget-header header-color-blue">
								<h5>Price Base Rates</h5>
								<div class="widget-toolbar no-border">
									<button class="btn btn-mini btn-primary" id="btn-open-shipping-rate-form">
										<i class="icon-plus"></i>
										Add
									</button>
								</div>
							</div>
							<div class="widget-body">
								<div class="widget-main" id="price-base-container">
									<p>Add rates based on the price of a customer's order.</p>
								</div>
							</div>
						</div>

		    		<div class="widget-box" >
							<div class="widget-header header-color-blue">
								<h5>Weight Based Rates</h5>
								<div class="widget-toolbar no-border">
									<button class="btn btn-mini btn-primary" id="btn-open-shipping-weight-form">
										<i class="icon-plus"></i>
										Add
									</button>
								</div>
							</div>
							<div class="widget-body">
								<div class="widget-main" id="weight-base-container">
									<p>Add rates based on the weight of a customer's order.</p>
								</div>
							</div>
						</div>

		    		<div class="widget-box" >
							<div class="widget-header header-color-blue">
								<h5>Other Rates</h5>
								<div class="widget-toolbar no-border">
									<button class="btn btn-mini btn-primary" id="btn-open-shipping-other-method">
										<i class="icon-plus"></i>
										Add
									</button>
								</div>
							</div>
							<div class="widget-body">
								<div class="widget-main" id="other-method-container">
									<p>Add other rates.</p>
								</div>
							</div>
						</div>
		      </div>

		      <div class="span4">
		    		<div class="widget-box" >
							<div class="widget-header header-color-blue">
								<h5>Shipping Area Name</h5>
							</div>

							<div class="widget-body">
								<div class="widget-main">

									<form class="form" id="shipping-area-name-form" action="<?php echo URL;?>shipping/ajax_shipping_origin_processor" enctype="multipart/form-data" method="post">
					          <div class="row-fluid">
					            <div class="span12">
					              <div class="alert_customer_details"></div>
					              <div class="hide">
					              	<input type="hidden" name="operation" value="update">
					              </div>
					              <div class="control-group">
					                <label class="control-label" for="shipping_area_name"><span class="red">*</span> Area Name:</label>
					                <div class="controls">
					                  <input type="text" id="shipping_area_name" name="shipping_area_name" class="span12" value="">
					                  <input type="hidden" id="shipping_area_name_hdn" class="span12" value="">           
					                </div>
					              </div>
					            </div>
					          </div>
					        </form>

								</div>
							</div>
						</div>

            <div class="widget-box" >
              <div class="widget-header header-color-blue">
                <h5>Save</h5>
              </div>
              <div class="widget-body">
                <div class="widget-main">
                  <button class="btn btn-success" id="btn-save-shipping-area"><i class="icon icon-save"></i> Save</button>
                </div>
              </div>
            </div>
		    	</div>
    		</div>

    	</div>
    </div>
  </div>
</div>

<div class="hidden">
	<div id="shipping-rate-tmpl">
		<div class="shipping-rate">
			<p class="title"><b></b> <a href="javascript:void(0)" class="pull-right btn-rate-edit">Edit</a></p>
			<p class="detail"><span class="pull-right"></span></p>

			<div class="hidden data-container">
				<input type="hidden" class="rate-id" value="0">
				<input type="hidden" class="rate-name">
				<input type="hidden" class="rate-description">
				<input type="hidden" class="rate-type">
				<input type="hidden" class="rate-min">
				<input type="hidden" class="rate-max">
				<input type="hidden" class="rate-free">
				<input type="hidden" class="rate-amount">
			</div>
		</div>
	</div>
</div>

<div id="shipping-area-rate-modal" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      	<h3 class="header smaller lighter green">Price Base Rate</h3>
      </div>
      <div class="modal-body overflow-visible">

        <div class="row-fluid">
        	<div class="span12">

		        <div class="form-inline">
		        	<div class="hide">
		        		<input type="hidden" id="shipping_rate_type" name="shipping_rate_type">
		        	</div>
              <div class="message"></div>
		          <div class="control-group">
		            <label class="control-label" for="shipping_rate_name"><b>Name</b></label>
		            <div class="controls">
		              <input type="text" id="shipping_rate_name" name="shipping_rate_name" class="span12" value="">
		            </div>
		          </div>
		          <div class="control-group">
		            <label class="control-label" for="shipping_rate_description"><b>Description</b></label>
		            <div class="controls">
		              <textarea name="shipping_rate_description" id="shipping_rate_description"  class="span12" value="" cols="30" rows="4" placeholder="Optional"></textarea>
		            </div>
		          </div>
		          <div class="control-group">
		          	<label class="control-label"><b>Range</b></label>
		          </div>
		          <div class="control-group" id="shipping-range-container">
		            <div class="row-fluid">
		            	<div class="span6">
			            	<label class="control-label" for="shipping_rate_min"><span class="red">*</span> Minimum </label>
			            	<div class="controls">
		            			<input type="text" class="input span12" id="shipping_rate_min" placeholder="" />
		            		</div>
		            	</div>
		            	<div class="span6">
			            	<label class="control-label" for="shipping_rate_max"><span class="red">*</span> Maximum </label>
			            	<div class="controls">
		            			<input type="text" class="input span12" id="shipping_rate_max" placeholder="" />
		            		</div>
		            	</div>
		            </div>
		          </div>
		          <div class="control-group">
                <label class="control-label" for="shipping_rate_free"><b>Rate</b></label>

                <div class="controls">
                  <input class="ace" type="checkbox" id="shipping_rate_free" />
                  <label class="lbl" for="shipping_rate_free"> Free Shipping</label>
                </div>
		          </div>
		          <div class="control-group">
                <label class="control-label" for="shipping_rate_amount">Shipping Rate</label>

                <div class="controls">
                  <input type="text" class="input span12" id="shipping_rate_amount" placeholder="$0.00" />
                  <input type="hidden" class="input hide" id="shipping_rate_amount_hdn" />
                </div>
		          </div>

		        </div>
        	</div>
        </div>
      </div>
      <div class="modal-footer">
      	<a data-dismiss="modal" class="btn null" href="javascript:void(0);">Cancel</a>
      	<a class="btn btn-primary" href="javascript:void(0);" id="btn-add-shipping-area-rate">Confirm</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>