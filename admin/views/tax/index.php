<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Tax    
			</h1>
		</div>
		<div id="result"></div>
		<div class="row-fluid">
			<div class="span7">
				<input type="hidden" id="action" value="index" class="hide" >
				<div class="widget-box" >
					<div class="widget-header">
						<h5>Tax Setting</h5>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<div class="form-horizontal">
								<div class="control-group">
									<label class="control-label">Enable Tax</label>
									<div class="controls">
										<label>
                      <input name="form-field-checkbox" type="checkbox" class="ace" id="tax-rate-enable" style="display:none;">
                      <span class="lbl"></span>
                    </label>
									</div>
								</div>
								<div class="control-group" id="tax-rate-container">
									<label class="control-label">Tax Rate</label>
									<div class="controls">
										<input type="text" class="input-small" id="tax-rate">
										<span>%</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span4">
				<div class="widget-box">
					<div class="widget-header">
						<h5>Option</h5>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<button class="btn btn-info" type="button" id="save_tax">
								<i class="icon-ok bigger-110"></i>
								Save Changes
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>