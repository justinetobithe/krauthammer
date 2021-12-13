<?php
/*
Title: SEO Settings
Order: 1
ID: seo_settings
*/ 
?>
<div id="seo_settings" class="tab-pane in active" style="overflow-x: hidden;">
	<div class="row-fluid">
		<div class="span12">
			<div id='alert_seo_settings'></div>

			<div class="row">
				<div class="control-group">
					<label class="control-label" for="seo_title">Title:</label>
					<div class="controls">
						<div class="span11 input-group">
							<input type="text" id="seo_title" class="input span12" name="seo_title" style="width: 100%;"/>
							<br>
							<p><i><strong id="title_char">0</strong> characters. Most search engines use a maximum of <b><span id="seo_title_limit_label"></span></b> chars for the title.</i></p>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="seo_description">Description:</label>
					<div class="controls">
						<div class="span11 input-group">
							<textarea id="seo_description" name="seo_description" class="span12" rows="6"></textarea>
							<br>
							<p><i><strong id="desc_char">0</strong> characters. Most search engines use a maximum 1 of <b><span id="seo_description_limit_label"></span></b> chars for the description.</i></p>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="seo_canonical_url">Canonical URL:</label>
					<div class="controls">
						<div class="span11 input-group">
							<input type="text" id="seo_canonical_url" class="input span12" name="seo_canonical_url" style="width: 100%;" value="" placeholder="Optional" />
							<br>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="seo_no_index">Robots Meta NOINDEX:</label>
					<div class="controls">
						<div class="span11 input-group">
							<label>
								<input id="seo_no_index" class="ace ace-switch ace-switch-7" type="checkbox" onchange="change_value();">
								<span class="lbl"></span>
							</label> 
							<input type="hidden" id="hdn_no_index"  name="seo_no_index" value="N">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>  