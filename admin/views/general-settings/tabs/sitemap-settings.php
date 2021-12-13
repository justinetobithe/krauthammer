<div id="container-sitemap" class="tab-pane">
	<?php 
	$enable_sitemap = get_system_option('sitemap-enable');  
	$auto_ping = get_system_option('sitemap-auto-ping-google');  
	?>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">XML-Sitemap</h4>

			<div class="widget-toolbar no-border">
				<button class="btn btn-mini btn-success" id="btn-save-sitemap">
					<i class="icon-save"></i>
					Save
				</button>
			</div>
		</div>
		<div class="widget-body">
			<div class="widget-main">
				<div>
					<label class="span6">Enable XML Sitemap:</label>
					<input id="switch-xml-sitemap" name="switch-xml-sitemap" class="ace ace-switch ace-switch-7" type="checkbox" <?php echo strtolower($enable_sitemap) == "on" ? 'checked="checked"' : "" ?> >
					<span class="lbl"></span>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<p>sitemap.xml is generated/rebuild after saving. <br></p>
						<p>The sitemap.xml file is located in /xmls/ directory. You can also nagivate in (<a href="<?php echo FRONTEND_URL . "/xmls/sitemap.xml" ?>" target="_blank"><?php echo FRONTEND_URL . "/xmls/sitemap.xml" ?></a>) </p>
						<p>Add the following ".htaccess" code to enable (<a href="<?php echo FRONTEND_URL . "/sitemap.xml" ?>" target="_blank"><?php echo FRONTEND_URL . "/sitemap.xml" ?></a>) url</p>
						<p>Notify Search Engines about your <a href="javascript:void(0)" id="btn-ping-sitemap">sitemap</a>.</p>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<p>You can access your sitemap on: <a href="<?php echo FRONTEND_URL . "/sitemap.xml" ?>" target="_blank">sitemap.xml</a></p>
					</div>
				</div>
				<hr>
				<div class="row-fluid">
					<div class="span12">
						<p>Google: <span id="notification-google"></span><span id="notification-google-last-ping"></span></p>
					</div>
				</div>
			</div>
		</div>	
	</div>

	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">XML-Sitemap Options</h4>
		</div>
		<div class="widget-body">
			<div class="widget-main">
				<div class="row-fluid">
					<div class="span12">
						<label class="control-label" for="sitemap-auto-notify-google">
							<input type="checkbox" class="ace ace-checkbox-2" id="sitemap-auto-notify-google" <?php echo strtolower($auto_ping) == "on" ? 'checked="checked"' : "" ?>>
							<span class="lbl" style="margin-left: 20px;"> Notify Google automatically via Ping upon any new changes detected on any posts/page on Sitemap.</span>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">.htaccess</h4>
		</div>
		<div class="widget-body">
			<div class="widget-main">
				<div class="row-fluid">
					<div class="span12">
						<p class="alert alert-warning"><b>Note: </b><br>Add the following comment in your <b>.htaccess</b> files to enable sitemap settings. <b>#Sitemap START</b> and <b>#Sitemap END</b></p>
						<div class="well well-small">
							<ul class="list-unstyled">
								<li><b>#Sitemap START</b></li>
								<li><b>#Sitemap END</b></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>