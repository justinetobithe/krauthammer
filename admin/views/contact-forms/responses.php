<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Contact Form Data Collected
			</h1>
		</div><!-- /.page-header -->
		<input type="hidden" value="response" id="action" />
		<input type="hidden" value="<?php echo $contact_form_id;?>" id="contact_form_id" />
		<div class="row-fluid">
			<div class="span12">
				<!-- PAGE CONTENT BEGINS -->
				<div class="well well-small">
					<div class="form-horizontal">
						<div class="">
							<label class="control-label">Select Contact Form</label>
							<div class="controls">
								<select name="cfforms" id="cfforms" class="chosen">
								<?php foreach ($contact_forms as $key => $value): ?>
									<option value="<?php echo $value->id; ?>" <?php echo $contact_form_id == $value->id ? 'selected' : ""; ?>><?php echo $value->name; ?></option>
								<?php endforeach ?>
									<option value="0" data-type="promotional" <?php echo $contact_form_id == 'promotional' ? 'selected' : '' ?>>[Default] Promotional Popup</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div id="contact-form-collection-container-empty" style="display: none;">
					<div class="alert alert-error">Selected Contact Form doesn't have any collected data.</div>
				</div>
				<div id="contact-form-collection-container">
					<table id="contact-form-collection" class="table table-striped table-bordered table-hover">
						<thead>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!--/.page-content-->
</div>

<div id="modal-edit-item" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Contact Form Data Collected
				</div>
			</div>
			<div class="modal-body">
				<div class="hide">
					<input type="hidden" id="modal-selected-id">
				</div>
				<div>
					<div class="form" id="modal-container-items"></div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="btn btn-medium btn-success" id="modal-btn-edit-save"><i class="icon icon-save"></i> Save</div>
				<div class="btn btn-medium btn-primary" data-dismiss="modal"><i class="icon"></i> Close</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<script id="tmpl-items" type="text/x-jquery-tmpl"> 
<div class="control-group">
	<div class="controls">
		<div class="row-fluid">
			<div class="span6">
				<label class=""><small>Key</small></label>
				<input type="text" class="input span12 input-small modal-item-name" value="${key}">
			</div>
			<div class="span6">
				<label class=""><small>Value</small></label>
				<textarea id="" cols="30" rows="1" class="input span12 input-small modal-item-value" style="max-width: 100%; min-width: 100%;">${value}</textarea>
			</div>
		</div>
	</div>
</div>
</script>