<?php ?>
<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Add Post       
			</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="add">
		<form class="form-horizontal" onsubmit="return false;">
			<div class="row-fluid">
				<div class="span9">
					<div class="widget-box" style="margin-bottom:10px; border:none;">
						<div id="alert_page"></div>
						<input type="text" name ="title" class="input input-xxlarge" id="txt_post_title" style="width:98%;" placeholder = "Enter Post Title Here">
						<input type="hidden" name="link" id="link" />
						<p><i>Permalink:</i> <a target="_blank" id="permalink"></a></p>
						<div style="margin-bottom:5px;">
							<input type="text" name ="url_slug" class="input input-xxlarge" id="txt_url_slug" style="width:98%;"  placeholder = "URL Slug">
						</div>
					</div><!-- PAGE CONTENT BEGINS -->
					<div class="widget-box">
						<div class="widget-body">
							<input type="hidden" id="hdn_content" name="content">
							<textarea id="content"></textarea>
						</div>
					</div>
					<div class="tabbable tabs-left" >
						<ul class="nav nav-tabs" id="myTab3">
							<li class="active">
								<a data-toggle="tab" href="#seo_settings">
									<i class="green icon-barcode bigger-110"></i>
									SEO Settings
								</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#container_comments">
									<i class="blue icon-comments bigger-110"></i>
									Comments
								</a>
							</li>
							</li>
							<li class="">
								<a data-toggle="tab" href="#container_author">
									<i class="blue icon-user bigger-110"></i>
									Author
								</a>
							</li>
						</ul>

						<div class="tab-content">
							<div id="seo_settings" class="tab-pane in active" style="overflow-x: hidden;">
								<div class="">
									<div class="span11">
										<div id='alert_seo_settings'></div>

										<div class="hr"></div>

										<div class="control-group">
											<label class="control-label" for="seo_title">Title:</label>
											<div class="controls">
												<div class="input-group">
													<input type="text" id="seo_title" class="input-xlarge" name="seo_title" style="width: 100%;"/>
													<br>
													<p><strong id="title_char">0</strong> characters. Most search engines use a maximum of <b><span id="seo_title_limit_label"></span></b> chars for the title.</p>
												</div>
											</div>
										</div>

										<div class="control-group">
											<label class="control-label" for="seo_description">Description:</label>
											<div class="controls">
												<div class="input-group">
													<textarea id="seo_description" name="seo_description" style="width:100%;" rows="6"></textarea>
													<br>
													<p><strong id="desc_char">0</strong> characters. Most search engines use a maximum 1 of <b><span id="seo_description_limit_label"></span></b> chars for the description.</p>
												</div>
											</div>
										</div>

										<div class="control-group">
											<label class="control-label" for="seo_no_index">Robots Meta NOINDEX:</label>
											<div class="controls">
												<div class="input-group">
													<label>
														<input id="seo_no_index" class="ace ace-switch ace-switch-7" type="checkbox" onchange="change_value();">
														<span class="lbl"></span>
													</label> 
													<input type="hidden" id="hdn_no_index"  name="seo_no_index">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>  

							<div id="container_comments" class="tab-pane" style="overflow-x: hidden;">
								<div class="table-responsive">
									<br>
									<p><i>This section will be available after saving or on edit mode.</i></p>
									<br>
								</div>
							</div>  

							<div id="container_author" class="tab-pane" style="min-height: 300px;">
								<div class="row-fluid">
									<div class="span12">
										<label for="field-chosen-author">Author: </label>
										<select id="field-chosen-author">
											<option value="0">-Select Author-</option>
											<?php if (isset($author) && count($author)): ?>
											<?php foreach ($author as $key => $value): ?>
												<option value="<?php echo $value->id ?>" <?php echo $value->username == $current_user ? 'selected="selected"' : '' ?>><?php echo $value->username; ?></option>
											<?php endforeach ?>
											<?php else: ?>
												<option value="0">No Author</option>
											<?php endif ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="span3">
					<div class="widget-box" style="width:100%;">
						<div class="widget-header header-color-blue2">
							<h5>Save Post</h5>
						</div>

						<div class="widget-body">

							<div class="widget-main">
								<select id="status">
									<option value="publish">Publish</option>
									<option value="draft">Draft</option>
								</select>
								<br>
								<br>
								<div>
									<input type="submit" class="btn btn-small btn-success" style="width:100%;" name="submit" id="btn_save_post" value="Save" />
								</div>
							</div>     
						</div>

					</div>
					<div class="widget-box">
						<div class="widget-header header-color-blue2">
							<h5>Language</h5>
						</div>

						<div class="widget-body">
							<div class="widget-main padding-8">
								<select name="language" id="language" style="width:100%; " class="dropdown-select" disabled="disabled">
								<?php foreach ($language as $key => $value): ?>
									<option value="<?php echo $value->slug ?>"><?php echo $value->value . ($value->selected == 'selected' ? " [default]" : ""); ?></option>
								<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>
					<div class="widget-box">
						<div class="widget-header header-color-blue2">
							<h5>Post Categories</h5>
						</div>

						<div class="widget-body">
							<div class="widget-main padding-8">
								<div id="alertProductCategory"></div>
								<input type="hidden" id="product_categories" name="product_category">
								<div id="tags">
									<div id="product_category_tree" class="tree"></div>
								</div>
								<button class="btn btn-small btn-info" style="width: 100%;" onclick="return false" id="btn_add_category">Add Categories</button>
							</div>
						</div>
					</div>
					<div class="widget-box">
						<div class="widget-header header-color-blue2">
							<h5>Featured Image</h5>
						</div>

						<div class="widget-body">

							<div class="widget-main">
								<div id="messageAlertForProductImage">
								</div>
								<div class="no-padding center" style="width:100%; height: 155px;">
									<img src="" style="min-width: 200px; max-width: 100%; height: 155px;" alt="" id="featured_image" />
								</div>
								<br>
								<input type="file" id="id-input-file-3" name="image_file" accept="image/*" onchange="changeImage(this);"/>
								<div id="edit_photo_holder" style="display: none;">
									<button class="btn btn-info btn-small"  onclick="show_cropper(); return false;">Edit Thumbnails</button>
								</div>

							</div>
						</div>    
					</div>
				</div>    
			</div><!--PAGE Row END-->
			<!--PAGE SPAN END-->
		</form>
	</div><!--PAGE CONTENT END-->

</div><!--MAIN CONTENT END-->
<div id="content_tree">

</div>

<div id="categories_modal" class="modal fade modal-xlarge">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Add Product Categories
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">
				<div id="alert_quick_add_modal">
				</div>

				<div class="row-fluid">
					<div class="span3">
						<lable>Category Name</label>
						</div>
						<div class="span8">
							<input type="text" class="input input-xlarge" id="category_name"/>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span3">
							<label>Select Parent</label>
						</div>
						<div class="span8">
							<select id="category_parent">
								<option value="0">
									Select Parent
								</option>
								<?php foreach ($categories as $key => $category) { ?>
								<option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

				</div><!-- /.modal-content -->
				<div class="modal-footer no-margin-top">
					<button class="btn btn-sm btn-info pull-right" onclick="save_category()">
						<i class="icon-check"></i>
						Save
					</button>
				</div>

			</div>
		</div>
	</div>
	<div id="modal-gallery-loading" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						Uploading Photo <span id="modal-gallery-loading-name"></span>
					</div>
				</div>
				<div class="modal-body" style="max-height: 100%; overflow-y: visible">
					<div id="modal-gallery-loading-completed"></div>
					<div class="progress progress-mini progress-success" data-percent="0%" id="modal-gallery-loading-progress" style="display: none;">
						<div class="bar" style="width:0%;"></div>
					</div>
				</div>
				<div class="modal-footer no-margin-top" style="display: none;">
					<button class="btn btn-small btn-primary" id="close_button" data-dismiss="modal">
						<i class="icon-remove"></i>
						Close
					</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>