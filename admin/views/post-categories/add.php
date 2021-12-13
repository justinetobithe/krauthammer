
<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Create New Post Categories   
			</h1>
		</div><!-- /.page-header -->

		<input type="hidden" id="action" value="add">



		<div class="row-fluid">
			<div class="span12">
				<form class="form-horizontal" id="post_category_form" enctype="multipart/form-data" method="post">

					<div class="widget-box">

						<div class="widget-header header-color-blue">
							<h5 class="bigger lighter">

								Category Details
							</h5>
						</div>

						<div class="widget-body">

							<div class="widget-main" style="margin-right: 10px;">

								<div id="alert_post_category">
								</div>

								<div class="control-group" style="margin-bottom: 5px">
									<label class="control-label" for="name">Name:</label>
									<div class="controls">
										<input type="text" id="category_name" name="name" class="input span12">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="url_slug"><i></i></label>
									<div class="controls">
										<a href="#" id="permalink" target="_blank"></a> 
										<input type="hidden" id="link" />          
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="url_slug">Url Slug:</label>
									<div class="controls">
										<input type="text" id="url_slug" name="url_slug" class="input span12">           
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="language">Language:</label>
									<div class="controls">
										<select name="language" id="language" class="dropdown-select" readonly="readonly" disabled="true">
											<?php foreach ($language as $key => $value): ?>
												<option value="<?php echo $value->slug ?>"><?php echo $value->value . ($value->selected == 'selected' ? " [default]" : ""); ?></option>
											<?php endforeach ?>
										</select>
										<p><em>Translation will be available after creating the a Post Category</em></p>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="parent">Parent:</label>
									<div class="controls">
										<select id="parent" name="parent">
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
										<input type="submit" value="Save" id="save_category"  onclick="return false;" class="btn btn-success">
										&nbsp;
										<input type="submit" value= "Cancel" class="btn btn-danger" onclick="reset_form(); return false;" />

									</div>
								</div>   

							</div>

						</div>
					</div>

				</form>
			</div>
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