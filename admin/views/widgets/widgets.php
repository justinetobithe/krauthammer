<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Widget
			</h1>
		</div>
		<div class="row-fluid ">
			<div class="span6">
				<div class="widget-box transparent">
					<div class="widget-header">
						<h4>Available Widgets</h4>
						<br>
						<p><small><em>To activate a widget drag it to a sidebar or click on it. To deactivate a widget and delete its settings, drag it back.</em></small></p>
					</div>
					<div class="widget-body">
						<div class="widget-main no-padding" id="cms-widget-element-container" style="display: none;">
						</div>
					</div>
				</div>
			</div>
			<div class="span6">
				<div id="cms-widget-sidebar-container" style="display: none;"></div>
			</div>	
		</div>
	</div>
</div>

<?php include ROOT . "libraries/plugins/widget/widget_layout/widget-modal.php" ?>
<?php include ROOT . "libraries/plugins/widget/widget_layout/widget-layout-elements.php" ?>
<?php include ROOT . "libraries/plugins/widget/widget_layout/widget-layout-sidebars.php" ?>
<?php include ROOT . "libraries/plugins/widget/widget_layout/widget-layout-fields.php" ?>