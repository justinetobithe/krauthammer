<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Edit Post       
			</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="edit">
		<input type="hidden" id="hidden_id" value="<?php echo $id;?>" />
		<form class="form-horizontal" onsubmit="return false;">
			<div class="row-fluid">
				<div class="span9">
					<div class="widget-box" style="margin-bottom:10px; border:none;">
						<div id="alert_page"></div>
						<input type="text" name ="title" class="input-xxlarge" id="txt_post_title" style="width:98%;" placeholder = "Enter Post Title Here">
						<input type="hidden" name="link" id="link" />
						<p><i>Permalink:</i> <a target="_blank" id="permalink"></a></p>
						<div style="margin-bottom:5px;">
							<input type="text" name ="url_slug" class="input input-xxlarge" id="txt_url_slug"  style="width:98%;"  placeholder = "URL Slug">
						</div>
					</div><!-- PAGE CONTENT BEGINS -->
					<div class="widget-box">
						<div class="widget-body">
							<input type="hidden" id="hdn_content" name="content">
							<textarea id="content" style="width: 98%;"></textarea>
						</div>
					</div>

					<hr>

					<div class="tabbable tabs-left" >
						<ul class="nav nav-tabs" id="myTab3">
							<li class="active">
								<a data-toggle="tab" href="#container_seo_settings">
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
							<li class="">
								<a data-toggle="tab" href="#container_author">
									<i class="blue icon-user bigger-110"></i>
									Author
								</a>
							</li>
						</ul>

						<div class="tab-content">
							<div id="container_seo_settings" class="tab-pane in active" style="overflow-x: hidden;">
								<div class="">
									<div class="span11">
										<div class="control-group">
											<label class="control-label" for="seo_title">Title:</label>
											<div class="controls">
												<div class="input-group">
													<input type="text" id="seo_title" class="input-xlarge" name="seo_title" style="width: 100%;"/>
													<br>
													<p><i><strong id="title_char">0</strong> characters. Most search engines use a maximum of <b><span id="seo_title_limit_label"></span></b> chars for the title.</i></p>
												</div>
											</div>
										</div>

										<div class="control-group">
											<label class="control-label" for="seo_description">Description:</label>
											<div class="controls">
												<div class="input-group">
													<textarea id="seo_description" name="seo_description" style="width:100%;" rows="6"></textarea>
													<br>
													<p><i><strong id="desc_char">0</strong> characters. Most search engines use a maximum of <b><span id="seo_description_limit_label"></span></b> chars for the description.</i></p>
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
									<div>
										<p><button id="post-comment-add" class="btn btn-small btn-primary" style="margin-right: 20px;"><i class="icon icon-plus"></i> Add Comment</button></p>
									</div>
									<hr>
									<table id="table-post-comments" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Comment</th>
												<th>Author</th>
												<th>Sumbitted Date</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>      
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
												<option value="<?php echo $value->id ?>"><?php echo $value->username; ?></option>
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
					<div class="widget-box">
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
								<select name="language" id="language" style="width:100%; " class="dropdown-select">
								<?php foreach ($language as $key => $value): ?>
									<option value="<?php echo $value->slug ?>"><?php echo $value->value . ($value->selected == 'selected' ? " [default]" : ""); ?></option>
								<?php endforeach ?>
								</select>
								<div style="padding-top: 10px;"></div>
								<div class="row-fluid">
									<div class="span6">
										<div id="translation-status-container"></div>
									</div>
									<div class="span6 text-right">
										<a href="javascript:void(0)" class="text-error" id="btn-delete-translation" style="display: none;">Delete</a>
									</div>
								</div>
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
								<div class="no-padding center">
									<img src="" style="max-width: 100%; max-height: 300px; word-break: break-all;" alt="" id="featured_image" />
								</div>
								<br>
								<input type="file" id="id-input-file-3" name="image_file"  accept="image/*" onchange="changeImage(this);"/>
								<div id="edit_photo_holder" style="display: none;">
									<button class="btn btn-info btn-small" id="btn-cropper-edit-thumbnail" style="width: 100%;" >Edit Thumbnails</button>
								</div>

							</div>
						</div>    
					</div>

					<div class="widget-box">
						<div class="widget-header header-color-blue2">
							<h5>Archived Version</h5>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div>
									<ul class="pager">
										<li class="previous">
											<a href="#" id="older" class="btn btn-info btn-small">← Older(<span>0</span>)</a>
										</li>

										<li class="next">
											<a href="#" id="newer" class="btn btn-small">(<span>0</span>)Newer →</a>
										</li>
									</ul>
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
<div id="content_tree"></div>
<div id="loading" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Wait for a moment posting page
				</div>
			</div>

			<div class="modal-body">

				<div class="center">
					<i id="product_loading" class="icon-spinner icon-spin blue bigger-300 hide"></i>
				</div>
				<div id="loading_msg_success" class="center hide">
					<h4 class="green">Page Successfully Posted</h4>
				</div>
				<div id="loading_msg_error" class="center hide">
					<h4 class="red">Page Unsuccessfully Posted</h4>
				</div>
			</div>

			<div class="modal-footer no-margin-top hide" id="modal_footer">
				<button class="btn btn-sm pull-left btn-danger hide" id="close_button" data-dismiss="modal">
					<i class="icon-remove"></i>
					Close
				</button>
				<button class="btn btn-sm pull-right btn-success hide" id="continue_button" onclick="closeModal();">
					<i class="icon-check"></i>
					Continue
				</button>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<div id="loading-2" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Processing...
				</div>
			</div>

			<div class="modal-body">
				<p class="text-center"><b>Please wait for a moment posting page...</b></p>
			</div>

			<div class="modal-footer no-margin-top hide" id="modal_footer">
				<button class="btn btn-sm pull-left btn-danger hide" id="close_button" data-dismiss="modal">
					<i class="icon-remove"></i>
					Close
				</button>
				<button class="btn btn-sm pull-right btn-success hide" id="continue_button" onclick="closeModal();">
					<i class="icon-check"></i>
					Continue
				</button>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<div id="categories_modal" class="modal fade modal-xlarge" style="display: none;">
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
				<div id="alert_quick_add_modal"></div>

				<div class="row-fluid">
					<div class="span3">
						<label>Category Name</label>
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
						<select id="category_parent" class="input input-xlarge">
							<option value="0">Select Parent</option>
							<?php foreach ($categories as $key => $category): ?>
								<option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
							<?php endforeach ?>
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

<div class="modal fade" id="cropper-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div id="cropper-example-2">
					<img src="" id="modal_picture" alt="Picture">
					<input type='hidden' id='hdn_image' value="" />  
				</div>
			</div>
			<div class="modal-footer">
				<button id="btn-modal-save-cropped-featured-image" class="btn btn-info" value="featured">Save Thumbnails</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-post-comment" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Edit Comment
				</div>
			</div>
			<div class="modal-body">
				<div class="form">
					<div class="hide" style="display : none;">
						<input type="text" id="modal-post-comment-id">
					</div>
					<div class="control-group">
						<label class="control-label">Author Name</label>
						<div class="controls">
							<input type="text" id="modal-post-comment-author-name" class="input" style="width: 500px;">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Author E-mail</label>
						<div class="controls">
							<input type="text" id="modal-post-comment-author-email" class="input" style="width: 500px;">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Author URL</label>
						<div class="controls">
							<input type="text" id="modal-post-comment-author-url" class="input" style="width: 500px;">
						</div>
					</div>
					<hr>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select id="modal-post-comment-status" class="input input-medium">
								<option value="approved">Approved</option>
								<option value="pending">Pending</option>
								<option value="trashed">Trashed</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Comment</label>
						<div class="controls">
							<textarea id="modal-post-comment-content" class="input" cols="30" rows="5" style="max-width: 500px; width: 500px;"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="btn-modal-save-post-comment" class="btn btn-success" value="featured"><i class="icon icon-save"></i> Save</button>
				<button class="btn btn-primary" value="close" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-post-comment-reply" style="display: none; width: 800px; margin-left: -400px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Reply Comment
				</div>
			</div>
			<div class="modal-body" style="min-height: 400px;">
				<div id="reply-container-loading" style="display: none;">
					<p class="alert alert-info">
						Please Wait While Loading Content...
					</p>
				</div>
				<div id="reply-container-loading-error" style="display: none;">
					<p class="alert alert-info">
						Unable to load content... Please call some help...
					</p>
				</div>
				<div id="reply-container">
					<div id="reply-subject" class="reply-subject">
						<div class="">
							<div class="">
								<br>
								<p class="content"></p>
							</div>
							<div class="row-fluid">
								<div class="span6">
									<p><small>Author: <a href="#" class="author"></a></small></p>
								</div>
								<div class="span6 text-right">
									<p class="mini-info"></p>
								</div>
							</div>
						</div>
					</div>

					<div id="reply-items-container">
						<hr>
						<div id="reply-items" class="reply-items"></div>
					</div>

					<hr>
					<div id="reply-field" class="reply-field">
						<div class="hide">
							<input type="hidden" id="reply-comment-id">
						</div>
						<div class="row-fluid">
							<div class="span6">
								<label><small>Author Name</small></label>
								<input type="text" id="reply-comment-author-name" style="width: 100%; max-width: 100%;">
								<label><small>Author E-mail</small></label>
								<input type="text" id="reply-comment-author-email" style="width: 100%; max-width: 100%;">
								<label><small>Author URL</small></label>
								<input type="text" id="reply-comment-author-url" style="width: 100%; max-width: 100%;">
								<label class="hide"><small>Rate</small></label>
								<input type="text" id="reply-commentrate" class="input input-mini hide">
							</div>
							<div class="span6">
								<label><small>Reply</small></label>
								<textarea id="reply-comment-content" cols="30" rows="7" style="width: 100%; max-width: 100%;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div id="reply-container-loading-overlay" class="widget-box-layer" style="z-index: 100; position: fixed; display: none;"><i class="icon-spinner icon-spin icon-2x white"></i></div>
			</div>
			<div class="modal-footer">
				<button id="btn-modal-reply-post-comment" class="btn btn-success"><i class="icon icon-reply"></i> Reply</button>
				<button id="btn-modal-reply-post-close" class="btn btn-primary">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="template-container">
	<script id="tmpl-comment-reply-item" type="text/x-tmpl">
		<div class="reply-item well well-small" style="margin-bottom: 5px;">
			<span class="reply-status"></span>
			<p>{{html content}}</p>
			<div class="row-fluid">
				<div class="span6">
					<p><small>Author: <a href="#">${author}</a><br><small> (${date_modified})</span></small></small></p>
				</div>
				<div class="span6 text-right">
					<a href="javascript:void(0)" class="reply-item-view-reply" data-value="${id}">{{if reply_count>0}}<b>(${reply_count})</b>{{/if}} Reply</a> | 
					<a href="javascript:void(0)" class="reply-item-view-edit" data-value="${id}">Edit</a>
				</div>
			</div>
		</div>
	</script>
</div>