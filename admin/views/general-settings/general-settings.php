<div class="main-content" >
	<div class="page-content">
		<div class="page-header">
			<h1>General Settings </h1>
		</div><!-- /.page-header -->

		<div class="row-fluid" style="z-index: inherit;">
			<div class="span11">
				<!-- PAGE CONTENT BEGINS -->
				<div class="widget-box">	
					<div class="tabbable tabs-left">

						<ul class="nav nav-tabs" id="myTab3">
						<?php foreach ($sytem_setting_tabs as $key => $value): ?>
						<?php if ($value['tab']['show']): ?>
							<li class="<?php echo $value['tab']['active']; ?>">
								<a data-toggle="tab" href="#<?php echo $value['tab']['id']; ?>">
									<i class="<?php echo $value['tab']['icon']; ?> bigger-110"></i>
									<?php echo $value['tab']['label'] ?>
								</a>
							</li>
						<?php endif ?>
						<?php endforeach ?>
						</ul>

						<div class="tab-content" id="tab_content">
						<?php foreach ($sytem_setting_tabs as $key => $value): ?>
						<?php if(is_file($value['layout'])) include($value['layout']); ?>
						<?php endforeach ?>
						</div>
					</div>
				</div>
			</div><!-- /.col -->

		</div><!-- /.row -->
	</div><!-- /.page-content -->

</div>

<div id="modal_container" class="hide">
	<div id="google_maps" class="modal fade">
		<div class="modal-dialog " style="overflow-y:none;" >
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						Google Maps
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">×</span>
						</button>
					</div>
				</div>

				<div class="modal-body" style="overflow:hidden; max-height:700px;">
					<div id="map_canvas" style="height:300px; width:100%;"></div>
					<div class="row">
						<div class="span6">
							<div class="input-group">
								<input type="text" class="form-control search-query" style="width:72%;" id="map_address">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-small" onclick="navigate();">
										Search
										<i class="icon-search icon-on-right bigger-110"></i>
									</button>
								</span>
							</div>
						</div>
					</div>
					<br>
					<input type="hidden" id="hidden_map_position">
					<div class="row">
						<div class="span6">
							<div class="input-group">
								<label>Title: </label>
								<input type="text" class="form-control" id="map_title" style="width:70%">

							</div>
						</div>
					</div>
					<div class="row">
						<div class="span6">
							<div class="input-group">
								<label>Description: </label>
								<textarea style="width:70%" id="map_description"></textarea>

							</div>
						</div>
					</div>
					<div class="row">
						<div class="span6">
							<div class="input-group">
								<span>Width: </span><input type="text" class="form-control search-query" style="width:10%;" id="map_w">
								<span>Height: </span><input type="text" class="form-control search-query" style="width:10%;" id="map_h">
								<button onclick="save_map();" class="  btn btn-success" style="margin-left:28%">Save Maps</button>
							</div>
						</div>
					</div>

				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
	<div id="delete" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						Delete Map
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">×</span>
						</button>
					</div>
				</div>

				<div class="modal-body">

					<div id="delete_msg">
						<h5 class="red"> Are you sure to delete this Map?</h5>
					</div>
					<input type="hidden" id="hidden_map_id"/>
				</div><!-- /.modal-content -->
				<div class="modal-footer no-margin-top">
					<button class="btn btn-sm btn-danger pull-right" onclick="delete_map_db();">
						<i class="icon-trash"></i>
						Delete
					</button>
				</div>
			</div><!-- /.modal-dialog -->
		</div>
	</div>
	<div id="xml-loading" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						Generating XML Sitemap
					</div>
				</div>

				<div class="modal-body">
					<p>Please wait while generating xml sitemap files</p>
				</div><!-- /.modal-content -->
				<div class="modal-footer no-margin-top">

				</div>
			</div><!-- /.modal-dialog -->
		</div>
	</div>
	<div id="modal-product-loading" class="modal fade" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						Product Setting
					</div>
				</div>

				<div class="modal-body">
					<p class="message">Saving Products Settings...</p>
				</div><!-- /.modal-content -->
				<div class="modal-footer no-margin-top">
					
				</div>
			</div><!-- /.modal-dialog -->
		</div>
	</div>

	<div id="modal-sitemap-loading" class="modal fade" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						Sitemap
					</div>
				</div>

				<div class="modal-body">
					<p class="message"></p>
				</div><!-- /.modal-content -->
				<div class="modal-footer no-margin-top">
					
				</div>
			</div><!-- /.modal-dialog -->
		</div>
	</div>
