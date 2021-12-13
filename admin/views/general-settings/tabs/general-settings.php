<div id="home3" class="tab-pane in active">
	<div class="row-fluid">
		<div class="widget-box">
			<div class="widget-header header-color-blue">
				<h4 class="lighter">Website Logo </h4>
			</div>
			<br>
			<div id='messageAlert1'></div>
			<form class="form-horizontal" id="general_settings_form" action="<?php echo URL;?>settings/save_logo" enctype="multipart/form-data" method="post" >
				<div class="hr"></div>
				<div class="control-group">
					<label class="control-label" for="txt_image">Image:</label>
					<div class="controls">
						<div class="span7 input-group">
							<div id="msg_alert_company_image"></div>

							<input type="file" id="id-input-file-3" name="logo"  accept="image/*"/>            

						</div>
					</div>
				</div>

				<br>
				<div class="center" id="image_div">
					<img src="<?php echo FRONTEND_URL;?>/images/uploads/default.png"  id="company_logo" height="200" width="400" alt="" style="max-width: 200px; max-height: 200px;">
					<input type="hidden" name="logo_url" id="logo_url" value="<?php echo FRONTEND_URL;?>/images/uploads/default.png" />
				</div>

			</div>
		</div>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-header header-color-blue">
					<h4 class="lighter">Footer Copyright Text</h4>
				</div>
				<br>
				<div class="control-group">
					<label class="control-label" for="copyright_text">Copyright Text:</label>
					<div class="controls">
						<div class="input-group">
							<textarea class="input-xxlarge" rows="7" style="width: 95%;" name="footer_text" id="copyright_text"></textarea>
						</div>
					</div>
				</div> 
			</div>
		</div>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-header header-color-blue">
					<h4 class="lighter">Website Analytics</h4>
				</div>
				<br>
				<div>
					<p>Google analytics is a free web analytics tool from Google that allows you to track you website visitors and statistics. <a href="#"> Signup free here.</a></p>
				</div>

				<div class="hr"></div>

				<div>
					<div>
						<div class="span7">
							<label class="span6" for="copyright_text">Google Event Tracking:</label>
							<input type="hidden" name="switch" id="switch" value="OFF" />
							<input id="switch_google_event_tracking" name="switch_google_event_tracking" class="ace ace-switch ace-switch-7" type="checkbox" onchange="change_switch_google();" />
							<span class="lbl"></span>

						</div>
					</div>
				</div>            
				<div class="control-group">
					<textarea class="input-xxlarge" name="analytic_codes"  rows="7" style="width: 95%;" id="web_analytics"></textarea>
				</div>                      
			</div>
		</div>
		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-header header-color-blue">
					<h4 class="lighter">Conversion Tracking Code</h4>
				</div>

				<br>
				<div>
					<p>The code you enter here will be displayed on your callback page for successful orders.</p>
				</div>
				<div class="hr"></div>

				<div class="control-group">
					<textarea class="input-xxlarge" rows="7" name="tracking_code" style="width: 95%;" id="conversion_tracking_code"></textarea>
				</div>                      
			</div>
		</div>

		<div class="row-fluid">
			<div class="widget-box">
				<div class="widget-header header-color-blue">
					<h4 class="lighter">Website Name</h4>
				</div>



				<br>
				<div>
					<div class="span7">
						<label class="span6">Website Name:</label>
						<input type="text" class="input" name="website_name" id="website_name"/>

					</div>
				</div>

				<br>
				<br>    

			</div>
		</div>
		<br>
		<button class=" pull-right btn btn-success " id="btn_save_image">

			<i class="icon-spinner icon-spin green bigger-125" id="loading_save_image"></i>
			Save Settings
		</button>
	</form>
</div>