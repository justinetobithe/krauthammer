<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>Contact Exporter</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="manage_contact_exporter">
		<div class="row-fluid">
			<div class="span12">
				<div class="table-header">
					Customers
				</div>
				<div class="table-responsive">
					<table id="contact_exporter" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="center" style="width:30px;">
									<label>
										<input type="checkbox" class="ace" />
										<span class="lbl"></span>
									</label>
								</th>
								<th><small>Name</small></th>
								<th><small>Contact No.</small></th>
								<th><small>Address</small></th>
								<th class="center"><small>Sync Status</small></th>
								<th class="center"><small>Action</small></th>
							</tr>
						</thead>
					</table>      
				</div>
			</div>
		</div>
		<!--PAGE SPAN END-->
	</div><!--PAGE Row END-->
</div><!--MAIN CONTENT END-->

<div id="delete" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Delete Contact Exporter
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">

				<div id="delete_msg">
					<h5 class="red"> </h5>
				</div>
				<input type="hidden" id="hidden_contact_exporter_id"/>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-danger pull-right" onclick="deletecontact_exporter();">
					<i class="icon-trash"></i>
					Delete
				</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>