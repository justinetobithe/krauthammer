<?php
$format_url = get_system_option('product_category_format_url');
?>
<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Create New Product Categories   
			</h1>
		</div><!-- /.page-header -->

		<input type="hidden" id="action" value="add">

		<div class="row-fluid">

			<form class="form-horizontal" id="product_category_form" action="<?php echo URL;?>product-categories/add_product_category" enctype="multipart/form-data" method="post" onsubmit="return validate_form()">
				<div class="widget-box span12">


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
									<input type="text" id="name" name="name" class="span12">  
									<br>
									<span>Permalink: <a href="#" id="permalink"></a></span>
									<br>
									<span>Change Product Category <a href="<?php echo URL . "settings/?tab=product&section=product-category-format-url" ?>" target="_blank" >URL format here</a></span>
								</div>
							</div>
							<div class="control-group hide">
								<label class="control-label" for="url_slug">Link:</label>
								<div class="controls">
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
								<div class="controls span4">
									<img id="image_view" src="<?php echo FRONTEND_URL;?>/images/uploads/default.png"  style="width:100%; height:auto;" alt="200x320"/>          
									<input type="file" id="image" name="image" accept="image/*" onchange='change_view_image(this);' > 
									<input type='hidden' id="hdn_image" value="<?php echo FRONTEND_URL;?>/images/uploads/default.png" />
								</div>

								
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
								<label class="control-label" for="description_tiny">Description:</label>
								<div class="controls">
									<textarea id="description_tiny"></textarea>     
								</div>
								<input type="hidden" id="description" name="description">
							</div>

							<div class="control-group">

								<div class="controls">
									<input type="submit" value="  Save  " id="save_orders" class="btn btn-success">
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
<div id="loads" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">

				</div>
			</div>

			<div class="modal-body">
				<div style="text-align: center;">
					<i class="icon-spinner icon-spin blue bigger-300 hide"></i>
					Please wait for a moment..
				</div>
			</div>
		</div>
	</div>
</div>