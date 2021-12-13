<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>Media</h1>
		</div><!-- /.page-header -->
		<div class="row-fluid">
			<div class="span12">
				<p>Drag the files to the page.</p>

				<div class="well well-small">
					Type: 
					<select name="file-type" id="file-type" class="">
						<option value="all">All</option>
						<option value="image">Images</option>
						<option value="audio">Audio</option>
						<option value="video">Video</option>
						<option value="other">others</option>
					</select>

					<button class="btn btn-mini btn-success" id="file-filter-btn"><i class="icon icon-zoom-in"></i> Filter</button>

					<span class="btn btn-small pull-right" data-rel="popover" title="Media" data-content="Drag the files to the page." data-trigger="hover" data-placement="left" >Read Me</span>
				</div>

				<ul class="ace-thumbnails" id="media-gallery" style="display: table; width: 100%;"></ul>
				<hr>
				<div class="container-load-more text-center">
					<button class="btn btn-primary" id="btn-load-more">Load More</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="file-drop-zone" style="display: none;"><div class="drop-container"><h5>Drop File Here</h5></div></div>

<div id="file-delete-modal" style="display: none;" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Delete Page
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">

				<div id="delete_msg"> 
					<h5>Are you sure to delete this link?</h5>
				</div>
				<input type="hidden" id="hdn_div_id"/>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-danger pull-right" onclick="delete_menu_ui();">
					<i class="icon-trash"></i>
					Delete
				</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>

<div id="file-upload-modal" style="display: none;" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Uploading Files
				</div>
			</div>

			<div class="modal-body">
				<div class="upload-container"></div>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="file-upload-modal btn btn-sm btn-primary pull-right" data-dismiss="modal" >Close</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>

<div id="file-viewer" style="display: none;" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					File
				</div>
			</div>

			<div class="modal-body">
				<div class="file-container text-center"></div>
				<div class="">
					<span><b>File Name:</b> </span>
					<p><small class="item-name"></small></p>
					<span><b>URL:</b> </span>
					<div class="row-fluid">
						<div class="span12">
							<input type="text" readonly="readonly" class="item-url span12">
						</div>
					</div>
				</div>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="file-upload-modal btn btn-sm btn-primary pull-right" data-dismiss="modal" >Close</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>
<div id="file-url" style="display: none;" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					URL
				</div>
			</div>

			<div class="modal-body">
				<div class="row-fluid">
					<div class="span12">
						<div class="file-container">
							<b>URL</b>
							<input type="text" readonly="readonly" class="span12 file-url" id="file-url-field">
						</div>
					</div>
				</div>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top text-right">
				<button class="file-upload-modal btn btn-sm btn-success" id="file-url-copy" >Copy</button>
				<button class="file-upload-modal btn btn-sm btn-primary" data-dismiss="modal" >Close</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>

<script id="media-item-template" type="text/x-jquery-tmpl"> 
	<?php include __DIR__ . '/fragments/media-item.php' ?>
</script> 

<script id="media-upload-item-template" type="text/x-jquery-tmpl"> 
	<?php include __DIR__ . '/fragments/media-upload-item.php' ?>
</script> 