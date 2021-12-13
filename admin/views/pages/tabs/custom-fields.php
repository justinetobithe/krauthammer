<?php
/*
Title: Custom Fields
Order: 3
ID: custom-fields
*/ 

$_SESSION['upload_timestamp'] = time();

?>
<div id="custom-fields" class="tab-pane" style="overflow-x: hidden; min-height: 350px;">
	<h4>Custom Fields</h4>
	<hr>
	<div class="well well-small">
		<p><i>Front-end Function: <b>cms_meta_data</b>([custom_field_name])</i></p>
		<p>call: <b>cms_meta_data()</b> <br><b>returns:</b> array(array(...), array(...), ...)</p>
		<p>call: <b>cms_meta_data([custom_field_name])</b> <br><b>returns:</b> array(...)</p>
	</div>
	<!-- custom fields here -->
	<div id="custom-fields-container"></div>

	<div class="well well-small">
		<input type="text" class="input input-xlarge" id="input-append-custom-field">
		<a href="javascript:void(0)" class="btn btn-small btn-success pull-right" id="btn-append-custom-field"><i class="icon icon-plus"></i> Add Field</a>
	</div>
</div>

<script id="template-custom-field" type="text/x-tmpl">
	<div class="row-fluid page-custom-fields" style="margin-bottom: 10px;">
		<div class="span6"><input type="text" class="input custom-field-name" value="${field_name}"></div>
		<div class="span6">
			<textarea class="input custom-field-value" rows="1" style="resize: vertical;" >${field_value}</textarea>
			<a href="javscript:void(0)" class="btn btn-mini btn-danger custom-field-btn-remove"><i class="icon icon-trash"></i></a>
		</div>
	</div>
</script>

<!-- removed unused script template template-gallery-album-extra -->