<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Comments
			</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="manage_pages">
		<div class="row-fluid">
			<div class="span12">
				<div class="table-header">
					Comments
				</div>

				<div class="table-responsive">
					<div>
						<br>
						<p><i>Adding comments is done in Post Module.</i></p>
						<br>
					</div>
					<table id="table-comments" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="center" style="width:5%;">
									<label>
										<input type="checkbox" class="ace" />
										<span class="lbl"></span>
									</label>
								</th>
								<th>Comment</th>
								<th>Author</th>
								<th>in Post</th>
								<th>Submitted Date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>      
				</div>
			</div>

		</div>
		<!--PAGE SPAN END-->
	</div><!--PAGE Row END-->
</div><!--MAIN CONTENT END-->

<div id="modal-post-comment" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Post Comment
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">
				<div class="form">
					<div class="hide" style="display : none;">
						<input type="text" id="modal-post-comment-id">
					</div>
					<div class="control-group">
						<label class="control-label">Author Name</label>
						<div class="controls">
							<input type="text" id="modal-post-comment-author-name" class="input" style="width: 500px;">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Author E-mail</label>
						<div class="controls">
							<input type="text" id="modal-post-comment-author-email" class="input" style="width: 500px;">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Author URL</label>
						<div class="controls">
							<input type="text" id="modal-post-comment-author-url" class="input" style="width: 500px;">
						</div>
					</div>
					<hr>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select id="modal-post-comment-status" class="input input-medium">
								<option value="approved">Approved</option>
								<option value="pending">Pending</option>
								<option value="trashed">Trashed</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Comment</label>
						<div class="controls">
							<textarea id="modal-post-comment-content" class="input" cols="30" rows="5" style="max-width: 500px; width: 500px;"></textarea>
						</div>
					</div>
				</div>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-success" id="btn-modal-save-post-comment">
					<i class="icon-save"></i>
					Success
				</button>
				<button class="btn btn-sm btn-primary" data-dismiss="modal">
					Close
				</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>