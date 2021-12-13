<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Customers
			</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="manage">
		<div class="row-fluid">
			<div id="customer_div" class="span12">
				<div class="table-header">
					Customers
				</div>

				<div class="table-responsive">
					<table id="customer_table" class="table table-important table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="center" style="width:5%;">
									<label>
										<input type="checkbox" class="ace" />
										<span class="lbl"></span>
									</label>
								</th>
								<th>Email</th>
								<th>Full Name</th>
								<th>Company</th>
								<th>Contact Number</th>
								<th>Status</th>
								<th><div class="text-center">Actions</div></th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table>      
				</div>
			</div>
		</div>
	</div>
</div>
<div id="delete" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Delete Customer
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">

				<div id="delete_msg">
					<h5> Are you sure to delete this customer?</h5>
				</div>
				<input type="hidden" id="hidden_customer_id"/>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-danger pull-right" onclick="__delete_customer();">
					<i class="icon-trash"></i>
					Delete
				</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>