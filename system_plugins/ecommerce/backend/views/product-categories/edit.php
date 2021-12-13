<?php
$format_url = get_system_option('product_category_format_url');
?>
<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Edit Product Categories   
			</h1>
		</div><!-- /.page-header -->

		<input type="hidden" id="action" value="edit">

		<div class="row-fluid">

			<form class="form-horizontal" id="product_category_form" action="<?php echo URL;?>product-categories/update_product_category/" enctype="multipart/form-data" method="post" onsubmit="return validate_form()">
				<div class="widget-box span12">
					<input type="hidden" name="old_slug" id="old_slug"/>
					<input type="hidden" name="id" id="hdn_id" value="<?php echo $id; ?>" >
					<div class="widget-header header-color-blue">
						<h5 class="bigger lighter">
							Category Details
						</h5>
					</div>

					<div class="widget-body">
						<div class="widget-main">

							<div id="alertProductCategory">
							</div>

							<div class="control-group">
								<label class="control-label" for="name">Name:</label>
								<div class="controls">
									<input type="text" id="name" name="name" class="span12" />
									<br>
									<span>Permalink: <a href="#" id="permalink" target="_blank"></a></span>
									<br>
									<span>Change Product Category <a href="<?php echo URL . "settings/?tab=product&section=product-category-format-url" ?>" target="_blank" >URL format here</a></span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="url_slug">Url Slug:</label>
								<div class="controls">
									<input type="text" id="url_slug" name="url_slug" class="input span12">           
								</div>
							</div>

							<hr>

							<div class="control-group">
								<label class="control-label" for="image">Image:</label>
								<div class="controls" style="max-width:300px;">
									<img id="image_view" src="<?php echo FRONTEND_URL;?>/images/uploads/default.png" style="max-width:100%;" alt="200x320" />
									<div class="space"></div>
									<input type="file" id="image" name="image" accept="image/*" onchange='change_view_image(this);'>
									<input type='hidden' id='hdn_image' />
									<span id="image_error_msg" class="red"></span>
								</div>
							</div>

							<div class="control-group hide" id="btn_edit_photo">
								<label class="control-label" > </label>
								<div class="controls">
									<button class="btn btn-primary btn-small" data-toggle="modal" data-target="#cropper-modal" type="button">Edit Thumbnails</button>
								</div>
								<div id="result_sample"></div>
							</div>

							<div class="control-group">
								<label class="control-label" for="hide">Hide Category:</label>
								<div class="controls">
									<select id="hide" name="hide">
										<option value="N" selected>No</option>
										<option value="Y">Yes</option>
									</select>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="parent">Parent:</label>
								<div class="controls">
									<select id="parent" name="parent">
										<option value="0">None</option>
									</select>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="parent">Languages:</label>
								<div class="controls">
									<select id="languages" name="language">
									<?php foreach ($languages as $key => $value): ?>
										<option value="<?php echo $value->slug; ?>"><?php echo $value->value ?></option>
									<?php endforeach ?>
									</select>
								</div>
							</div>

							<hr>

							<div class="control-group">
								<label class="control-label" for="description_tiny">
									Description: <br>
									<span class="badge badge-info" id="is-translated">Identifying...</span>
								</label>
								<div class="controls">
									<textarea id="description_tiny"></textarea>
								</div>
								<input type="hidden" name="description" id="description">
							</div>

							<div class="control-group">
								<div class="controls">
									<input type="submit" value="Save" class="btn btn-info" name="submit">
									&nbsp;
									<input type="submit" value="Save and Add New" id="save_orders" class="btn btn-success" name="submit">
									&nbsp;
									<input type="submit" value= "Cancel" class="btn btn-danger" onclick="reset_form(); return false;" />
								</div>
							</div> 

						</div>
					</div>
				</div>
			</form>
		</div><!--PAGE Row END-->
	</div><!--PAGE CONTENT END-->
</div><!--MAIN CONTENT END-->
<!--MODAL-->
<div id="loads" class="modal fade" style="display: none;" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">

				</div>
			</div>

			<div class="modal-body">
				<div style="text-align:center;">
					<i class="icon-spinner icon-spin blue bigger-300 hide"></i>
					Please wait for a moment..
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="cropper-modal" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div id="cropper-example-2">
					<img src="<?php echo FRONTEND_URL;?>/images/uploads/default.png" id="modal_picture" alt="Picture">
				</div>
			</div>
			<div class="modal-footer">
				<button id="btn_get_canvass" class="btn btn-info">Crop Thumbnail</button>
			</div>
		</div>
	</div>
</div>