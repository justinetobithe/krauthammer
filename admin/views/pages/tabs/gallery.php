<?php
/*
Title: Gallery
Order: 2
ID: gallery
*/ 

$_SESSION['upload_timestamp'] = time();

?>
<div id="gallery" class="tab-pane" style="overflow-x: hidden; min-height: 350px;">
	<div class="hide">
		<input type="hidden" id="gallery-token" value="<?php echo md5('unique_salt' . $_SESSION['upload_timestamp']); ?>">
	</div>
	<div class="gallery-container">
		<button class="btn btn-primary btn-small" id="btn_add_new_album"><i class="icon icon-plus"></i> Add New Album</button>
		<a href="javascript:void(0)" class="btn btn-warning btn-small pull-right" id="btn_add_show_all">Show All</a>
		<a href="javascript:void(0)" class="btn btn-info btn-small pull-right" id="btn_add_hide_all">Hide All</a>
	</div>

	<hr>

	<div id="accordion-gallery" class="accordion"></div>
</div>

<script id="template-gallery-album" type="text/x-tmpl">
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#album-countainer-${id_counter}" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed">${album_title}</a>
		</div>
		<div class="accordion-body collapse" id="album-countainer-${id_counter}">
			<div class="accordion-inner">
				<div class="hide album-hidden-content">
					<input type="hidden" class="album-id" value="${id}">
					<input type="hidden" class="album-return-id" value="album-countainer-${id_counter}">
				</div>
				<div class="form-horizontal">
					<div class="control-group">
						<label class="control-label control-label-sidebar">Album Name:</label> 
						<div class="controls">
							<input type="text" class="input-xlarge album_name" value="${album_title}">
							<a href="javascript:void(0)" class="btn btn-danger btn-small tooltip-error btn-gallery-remove-album" title="Delete Album" data-title="Delete Album" data-placement="top" ><i class="icon icon-trash"></i></a>
						</div>
					</div>
				</div>

				<div class="album-uploader-container">
					<input type="file" multiple="" class="upload_images" name="album[]" accept="image/*">
				</div>
				<hr>

				<table data-table="6" role="presentation" class="table table-striped gallery-table">
					<tbody class="files ui-sortable">

					</tbody>
				</table>

				<div class="uploaded-images">

				</div>

			</div>
		</div>
	</div>
</script>
<script id="template-gallery-album-item" type="text/x-tmpl">
	<div class="album-photo-item " id="album-photo-item-${id}">
		<div class="row-fluid">
			<div class="span3">
				<a href="${url}" data-lightbox="${name}" data-title="${name}"><img src="${url}" alt="" class="album-photo-item-image"></a>
			</div>
			<div class="span6">
				<p class="album-photo-name"><b>${name}</b></p>
				<label>
					<input name="album-photo-item-featured-${album_id}" class="album-photo-item-featured ace" type="radio" data-value="${id}" ${featured}>
					<span class="lbl"> <small>Featured</small></span>
				</label>
				<hr>
				<p class="album-photo-description"><em>${description}</em></p>
				<hr>
			</div>
			<div class="span3 text-right">
				<button class="btn btn-info btn-small btn-gallery-edit-item" data-value="${id}">
					<i class="icon-edit"></i>
				</button>
				<button class="btn btn-danger btn-small btn-gallery-remove-item" data-value="${id}">
					<i class="icon-trash"></i>
				</button>
			</div>
		</div>
		<br>
	</div>
</script>

<!-- removed unused script template template-gallery-album-extra -->