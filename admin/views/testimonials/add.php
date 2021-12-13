<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Add New Testimonials
			</h1>
		</div><!-- /.page-header -->

		<input type="hidden" id="action" value="add">

		<div class="row-fluid">
			<form class="form-horizontal" id="product_category_form" action="<?php echo URL;?>testimonials/add_testimonial" enctype="multipart/form-data" method="post" onsubmit="return validate_form()">
				<div class="widget-box span9">
					<div class="widget-header header-color-blue">
						<h5 class="bigger lighter">
							Testimonial Details
						</h5>
					</div>
					<div class="widget-body">
						<div class="widget-main">
							<div id="alertProductCategory"></div>
							<div class="control-group">
								<label class="control-label" for="image">Image:</label>
								<div class="controls span5">
									<input type="file" id="image" name="image" accept="image/*">           
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="name">Name:</label>
								<div class="controls">
									<input type="text" id="name" name="name" class="input-xxlarge">           
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="description_tiny">Testimonial:</label>
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