
<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Edit Post Categories   
			</h1>
		</div><!-- /.page-header -->

		<div class="hide">
			<input type="hidden" id="action" value="edit">
		</div>

		<div class="row-fluid">
			<div class="span12">
				<form class="form-horizontal" id="post_category_form" enctype="multipart/form-data/" method="post">
					<input type="hidden" id="hidden_id" value="<?php echo $id; ?>" />
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
										<input type="text" id="category_name" name="name" class="input " style="width: 100%;">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="url_slug"><i></i></label>
									<div class="controls">
										<a href="#" id="permalink" target="_blank"></a> 
										<input type="hidden" id="link" />   
										<input type="hidden" id="sort_index" />        
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="url_slug">Url Slug:</label>
									<div class="controls">
										<input type="text" id="url_slug" name="url_slug" class="input" style="width: 100%;">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="parent">Parent:</label>
									<div class="controls">
										<select id="parent" name="parent"></select>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="language">Language:</label>
									<div class="controls">
										<select name="language" id="language" class="dropdown-select">
										<?php foreach ($language as $key => $value): ?>
											<option value="<?php echo $value->slug ?>"><?php echo $value->value . ($value->selected == 'selected' ? " [default]" : ""); ?></option>
										<?php endforeach ?>
										</select>
										<a href="javascript:void(0)" id="btn-delete-category-translation" class="text-error" style="display: none;">Delete</a>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="description_tiny">
										Description: <br>
										<span class="badge badge-info" id="is-translated">Identifying...</span>
									</label>
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