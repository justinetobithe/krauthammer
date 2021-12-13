<div id="profile3" class="tab-pane">
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">System Email </h4>
			<br>
		</div>
		<br>
		<div id='messageAlert2'></div>
		<form class="form-horizontal">
			<div class="hr"></div>
			<div class="control-group">
				<label class="control-label" for="txt_email">Email:</label>
				<div class="controls">
					<div class="span7 input-group">
						<span class="input-icon input-icon-right">
							<input type="text" id="txt_email" class="input-xxlarge">
							<i class="icon-envelope green"></i>
						</span>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="txt_name">Name:</label>
				<div class="controls">
					<div class="span7 input-group">
						<span class="input-icon input-icon-right">
							<input type="text" id="txt_name" class="input-xxlarge">
							<i class="icon-user"></i>
						</span>
					</div>
				</div>
			</div>       

			<br>
			<div class="control-group">
				<div class="controls">
					<div class="span7 input-group">
						<button class="  btn btn-success " onclick="saveEmail(); return false;" id="btn_save_email">
							<i class="icon-spinner icon-spin green bigger-125" id="loading_save_email"></i>
							Save Email
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>