<div id="maps" class="tab-pane">
	<div id="alert_maps_page"></div>
	<form class="form-horizontal">
		<div class="widget-box">
			<div class="widget-header header-color-blue">
				<h4 class="lighter">Maps (via Google Maps)</h4>
			</div>
			<div class="widget-main">
				<div class="row-fluid">
					<div class="col-sm-12">
						<form class="form-horizontal" id="general_settings_form" action="<?php echo URL;?>settings/save_logo" enctype="multipart/form-data" method="post" >
							<div class="control-group">
								<label class="control-label" for="txt_image">Google API:</label>
								<div class="controls">
									<div class="span12 input-group">
										<div id="msg_alert_company_image"></div>
										<input type="text" id="id-input-file-3" class="span12" name="logo"  accept="image/*"/>            
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th style="width:20%;">Short Code</th>
								<th>Title</th>
								<th style="width:35%;">Description</th>
								<th style="width:10px;">Actions</th>
							</tr>
						</thead>

						<?php if (empty($maps)): ?>
							<tr><td>No Maps Available Yet.</td></tr>
						<?php else:  ?>
							<?php foreach ($maps as $key => $map): ?>
								<tr>
									<td><?php echo $map['short_code'];?></td>
									<td><?php echo $map['title'];?></td>
									<td><?php echo $map['description'];?></td>
									<td>
										<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
											<button class="btn btn-minier btn-info" data-rel="tooltip" data-placement="top" title="Edit" >
												<i class="icon-edit bigger-120" onclick="edit_map(<?php echo $map['id'];?>,'<?php echo $map['position'];?>','<?php echo $map['title'];?>', '<?php echo $map['description'];?>', '<?php echo $map['width'];?>', '<?php echo $map['height'];?>'); return false;"></i></button><button class="btn btn-minier btn-danger " data-rel="tooltip" data-placement="top" title="Delete" onclick="delete_map(<?php echo $map['id']?>); return false;" ><i class="icon-trash bigger-120"></i></button></div></td>
								</tr>
							<?php endforeach ?>
						<?php endif ?>
						<tbody>
						</tbody>
					</table>      
				</div>

				<button class="btn btn-primary" onclick="show_add_maps(); return false;"><i class="icon icon-map-marker"></i>Add New Maps</button>
			</div>
		</div>
	</form>
</div>