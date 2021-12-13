<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Testimonials      
			</h1>
		</div>

		<input type="hidden" id="action" value="manage"/>

		<div class="row-fluid">
			<div class="span12">
				<div class="well well-small">
					<button class="btn btn-success btn-small" id="btn-add-testimonial"><i class="icon icon-testimonial"></i> Add New Testimonial</button>
				</div>
				<table id="table-testimonials" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>Image</th>
							<th>Name</th>
							<th>Testimonial</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="modal-edit" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Edit Testimonial
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">
				<input type="hidden" id="current-selected-testimonial"/>
				<div class="alert alert-info" id="current-selected-testimonial-loader" style="display: none;">
					<p>Please Wait.. Loading Content...</p>
				</div>
				<br>
				<div class="testimonial-image-container text-center" style="width: 100%; margin: auto;">
					<div class="previewer-container">
						<img src="<?php echo trim(FRONTEND_URL, '/') . "/thumbnails/78x66/uploads/default.png"; ?>" alt="<?php echo trim(FRONTEND_URL, '/') . "/thumbnails/78x66/uploads/default.png"; ?>" id="current-selected-testimonial-image-previewer" style="max-width: 100%; max-height: 200px; background-color: #ffffff; ">
					</div>
					<br>
					<div class="previewer-container">
						<input type="file" name="file" id="current-selected-testimonial-image">
					</div>
				</div>

				<hr>

				<div class="form-horizontal" id="current-selected-testimonial-container">
					<div class="control-group">
						<label class="control-label">Name</label>
						<div class="controls">
							<input type="text" class="input input-xlarge" id="current-selected-testimonial-name">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Company</label>
						<div class="controls">
							<input type="text" class="input input-xlarge" id="current-selected-testimonial-company">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Position</label>
						<div class="controls">
							<input type="text" class="input input-xlarge" id="current-selected-testimonial-position">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select id="current-selected-testimonial-status" class="input-chosen">
								<option value="approved">Approved</option>
								<option value="pending">Pending</option>
								<option value="deleted">Deleted</option>
							</select>
						</div>
					</div>
					<hr>
					<div class="">
						<label class="control-label">Testimonial</label>
						<div class="controls">
							<textarea id="current-selected-testimonial-content" cols="30" rows="5"  class="input input-xlarge" style="resize: vertical;"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->

		<div class="modal-footer no-margin-top">
			<button class="btn btn-sm btn-success pull-right" id="btn-testimonial-save-changes">
				<i class="icon-save"></i>
				Save
			</button>
		</div>
	</div>
</div><!-- /.modal-dialog -->
</div>
