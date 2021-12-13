<?php
$script_version =isset($script_version) ? $script_version : md5(0);
?>
<div class="ace-settings-container hide" id="ace-settings-container">
	<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
		<i class="icon-cog bigger-150"></i>
	</div>

	<div class="ace-settings-box hide" id="ace-settings-box">
		<div>
			<div class="pull-left">
				<select id="skin-colorpicker" class="hide">
					<option data-skin="default" value="#438EB9">#438EB9</option>
					<option data-skin="skin-1" value="#222A2D">#222A2D</option>
					<option data-skin="skin-2" value="#C6487E">#C6487E</option>
					<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
				</select>
			</div>
			<span>&nbsp; Choose Skin</span>
		</div>

		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
			<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
		</div>
		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
			<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
		</div>
		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
			<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
		</div>
		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
			<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
		</div>
		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
			<label class="lbl" for="ace-settings-add-container">
				Inside
				<b>.container</b>
			</label>
		</div>
	</div>
</div><!--/#ace-settings-container-->
</div><!--/.main-content-->

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
	<i class="icon-double-angle-up icon-only bigger-110"></i>
</a>

<!--basic scripts-->

<!--[if !IE]>-->
<script type="text/javascript">
	window.jQuery || document.write("<script src='<?php echo URL; ?>assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>

<!--<![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo URL; ?>assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
	if("ontouchend" in document) document.write("<script src='<?php echo URL; ?>assets/js/jquery.mobile.custom.min.js?v=<?php echo $script_version; ?>'>"+"<"+"/script>");
</script>
<script src="<?php echo URL; ?>assets/js/bootstrap.min.js?v=<?php echo $script_version; ?>"></script>

<!--page specific plugin scripts-->
<script type="text/javascript" src="<?php echo URL; ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo URL; ?>assets/js/jquery.dataTables.bootstrap.js"></script>

<script src="<?php echo URL; ?>assets/js/chosen.jquery.min.js"></script>

<?php if (isset($main_js_file) && (strrpos($main_js_file, 'settings.js') || strrpos($main_js_file, 'products.js'))): ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&&key=AIzaSyBqGLBhU4ksMMIYXLWx8zmRXQ9kG6lEPjg"></script>
<?php endif ?>

<script src="<?php echo URL; ?>assets/js/jquery.tmpl.js"></script>
<script src="<?php echo URL; ?>assets/js/fuelux/data/fuelux.tree-sampledata.js"></script>
<script src="<?php echo URL; ?>assets/js/fuelux/fuelux.wizard.min.js"></script>
<script src="<?php echo URL; ?>assets/js/fuelux/fuelux.tree.min.js"></script>
<script src="<?php echo URL; ?>assets/js/chosen.jquery.min.js"></script>
<script src="<?php echo URL; ?>assets/js/date-time/bootstrap-datepicker.min.js"></script>
<script src="<?php echo URL; ?>assets/js/date-time/bootstrap-timepicker.min.js"></script>
<script src="<?php echo URL; ?>assets/js/date-time/moment.min.js"></script>
<script src="<?php echo URL; ?>assets/js/date-time/daterangepicker.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo URL; ?>assets/js/additional-methods.min.js"></script>
<script src="<?php echo URL; ?>assets/js/bootbox.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.maskedinput.min.js"></script>
<script src="<?php echo URL; ?>assets/js/select2.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery-ui-1.10.3.full.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo URL; ?>assets/js/plugins/ajax-form/ajax-form.js"></script>
<script src="<?php echo URL; ?>assets/js/dropzone.min.js"></script>
<script src="<?php echo URL; ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo URL; ?>assets/js/bootstrap-tag.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo URL; ?>assets/js/bootbox.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.easy-pie-chart.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.gritter.min.js"></script>
<script src="<?php echo URL; ?>assets/js/spin.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.colorbox-min.js"></script>
<script src="<?php echo URL; ?>assets/js/jqGrid/jquery.jqGrid.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jqGrid/i18n/grid.locale-en.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.nestable.min.js"></script>
<!--ace scripts-->

<script src="<?php echo URL; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo URL; ?>assets/js/ace.min.js"></script>
<script src="<?php echo URL; ?>assets/js/tinymce_4.7.2/tinymce.min.js"></script>
<script src="<?php echo URL; ?>assets/js/jquery.slimscroll.min.js"></script>
<script src="<?php echo URL; ?>assets/js/cropper-master-2/cropper.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php echo URL; ?>assets/js/plugins/blueimp/canvas-to-blob.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php echo URL; ?>assets/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?php echo URL; ?>assets/js/plugins/blueimp/load-image.all.min.js"></script>
<!-- blueimp Gallery script -->
<script src="<?php echo URL; ?>assets/js/plugins/blueimp/tmpl.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="<?php echo URL; ?>assets/js/plugins/jquery_upload/js/main.js"></script>
<!-- Add Jquery Lightbox Pluging -->
<script src="<?php echo FRONTEND_URL; ?>/plugins/lightbox2-master/dist/js/lightbox.min.js"></script>
<!-- The Accounting Plugin -->
<script src="<?php echo URL; ?>assets/plugin/accounting/accounting.js"></script>

<!--inline scripts related to this page-->

<script type="text/javascript" src="<?php echo URL; ?>assets/js/controllers/functions.js?v=<?php echo $script_version; ?>"></script>     
<script type="text/javascript" src="<?php echo URL; ?>assets/js/controllers/patcher-main.js?v=<?php echo $script_version; ?>"></script>     
<?php 
$url = isset($_GET['url']) ? $_GET['url'] : "/"; 
$end = explode('/', $url);

$url = $end[0];

if(isset($end[1]) && $end[0] == 'products')
	if($end[1]  == 'categories')
		$url = 'product-categories';

	if(isset($end[1]) && $end[0] == 'post')
		if($end[1]  == 'categories')
			$url = 'post-categories';	
		?>

		<?php if (isset($main_js_file)): ?>
			<?php if($main_js_file != ""): ?>
				<script type="text/javascript" src="<?php echo $main_js_file; ?>?v=<?php echo $script_version; ?>" data-description="main-js"></script>
			<?php endif;  ?>
		<?php endif ?>

		<?php if (isset($js_files) && count($js_files)): ?>
			<?php foreach ($js_files as $js_files_key => $js_files_value): ?>
				<?php if(file_exists(ROOT."assets/js/controllers/". getJavascriptByUrl($js_files_value))): ?>
					<script type="text/javascript" src="<?php echo URL; ?>assets/js/controllers/<?php echo getJavascriptByUrl($js_files_value); ?>?v=<?php echo $script_version; ?>"></script>
				<?php endif;  ?>
			<?php endforeach ?>
		<?php endif ?>

		<?php if (isset($js_plugin_files)): ?>
			<?php foreach ($js_plugin_files as $js_files_key => $js_files_value): ?>
				<script type="text/javascript" src="<?php echo $js_files_value; ?>?v=<?php echo $script_version; ?>"></script>
			<?php endforeach ?>
		<?php endif ?>

		
		<script type="text/javascript">
			$('.form_select').chosen();
			$('.chosen-full-width').chosen({ 
				placeholder_text_single: "Select Country...",
				no_results_text: "Oops, nothing found!",
				width : '100%' 
			});
		</script>
	</body>
	</html>