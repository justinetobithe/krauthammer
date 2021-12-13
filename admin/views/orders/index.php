<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Orders
			</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="manage_order">
		<div class="row-fluid">
			<div class="span12">
				<div class="table-header">
					Orders
				</div>

				<div class="table-responsive">
					<table id="ordersTable" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th style="width:10%;">ID</th>
								<th style="width:10%;">Status</th>
								<th style="width:10%;">Invoice #</th>
								<th style="width:10%;">Date/Time</th>
								<th>Name</th>
								<th>Email</th>
								<th style="width:11%;">Contact Number</th>
								<th>Message</th>
								<th style="width:10%;">Actions</th>
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
<div id="delete" class="modal fade">
	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Delete Order
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">

				<div id="delete_msg">
					<h5 class="red"></h5>
				</div>
				<input type="hidden" id="hidden_order_id"/>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-danger pull-right" onclick="deleteOrderModal();">
					<i class="icon-trash"></i>
					Delete
				</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>

<div id="modal-view-email" class="modal fade">
	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Order Email
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">
				<input type="text" class="input" id="modal-view-email-item" style="width: 500px;">
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-primary" data-dismiss="modal">
					<i class="icon-trash"></i>
					Close
				</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>
<div id="modal-view-message" class="modal fade">
	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Order Email
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">
				<p id="modal-view-message-item"></p>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-primary" data-dismiss="modal">
					<i class="icon-trash"></i>
					Close
				</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>