<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>Edit Page</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="edit_page">

		<form class="form-horizontal" id="page_form" action="<?php echo URL;?>pages/updatePage/" enctype="multipart/form-data" method="post">
			<input type="hidden" id="hdn_page_id" name = "id" value="<?php echo $post['id']; ?>"/>
			<input type="hidden" id="hdn_page_lang" name = "lang" value="en"/>
			
			<div class="row-fluid">
				<div class="span9">
					<div class="widget-box" style="margin-bottom:10px; border:none;">
						<div id="alertPage"></div>
						<input type="text" name ="title" class="input-xxlarge" id="txt_post_title" style="width:98%;" placeholder = "Enter Page Title Here" value="<?php echo $post['post_title']; ?>">
						<p><i>Permalink:</i> <a target="_blank" href="" id="permalink"></a></p>
						<div style="margin-bottom:5px;">
							<input type="text" name ="url_slug" class="input-xxlarge" id="txt_url_slug" style="width:98%;" placeholder = "URL Slug" value="<?php echo $post['url_slug'];?>">
							<input type="hidden" value="<?php echo $post['url_slug'];?>" id="hidden_slug"/>
							<input type="hidden" name="link" id="link" />
						</div>
					</div><!-- PAGE CONTENT BEGINS -->
					<div class="widget-box transparent">
						<div class="widget-body">
							<input type="hidden" id="hdn_content" name="content">
							<textarea id="content" style="width: 98%;"></textarea>
						</div>
					</div>

					<hr>
					<?php 
						$_d = array( 'cms_tab_id'=>"myTab3" );
						if (isset($authors)) { $_d['authors'] = $authors; }
						$this->include_layout('tabs/tab-container', $_d); 
					?>
				</div>
				<div class="span3">
					<div class="widget-box" style="width:100%;">
						<div class="widget-header header-color-blue2">
							<h5>Save Page</h5>
						</div>

						<div class="widget-body">

							<div class="widget-main">
								<select name="status" id="status" style="width:100%; " class="dropdown-select">
									<option value="publish" <?php echo ($post['status'] == 'publish'?'selected':''); ?>>Publish</option>
									<option value="draft" <?php echo ($post['status'] == 'draft'?'selected':''); ?>>Draft</option>
								</select>
								<br><br>
								<div class="">
									<button class="btn btn-small btn-success" style="width:47%;" name="submit" id="btn_save_product">Save</button>
								</div>
							</div>     
						</div>
					</div>
					<div class="widget-box" style="width:100%;">
						<div class="widget-header header-color-blue2">
							<h5>Language</h5>
						</div>

						<div class="widget-body">

							<div class="widget-main">
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
							<h5>Page Template</h5>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<select name="page_template" id="page_template" style="width:100%;" class="dropdown-select">
									<?php if(!empty($templates))
									foreach ($templates as $key => $template) { 
										if($template['value'] == $post['page_template']){?>
										<option value="<?php echo $template['value']; ?>" selected><?php echo $template['name']; ?></option>
										<?php }else { ?>
										<option value="<?php echo $template['value']; ?>"><?php echo $template['name']; ?></option>
										<?php      }
									}?>
								</select>

								<div id="page-category-tree-container" style="display: none;">
									<hr>
									<h5>Post Categories</h5>
									<input type="hidden" name="page_blog_categories" id="page_blog_categories">
									<div id="page-category-tree" class="tree"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="widget-box">
						<div class="widget-header header-color-blue2">
							<h5>Page Parent</h5>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<select name="parent_id" id="parent_id" style="width:100%;" class="dropdown-select">
									<option value="0">Select Pages Parent</option>
									<?php 
									if(!empty($pages)){
										foreach ($pages as $key => $page) { 
											if($page['id'] == $post['parent_id']){ ?>
											<option value="<?php echo $page['id']; ?>"  data-foo="<?php echo $page['url_slug']; ?>" selected><?php echo $page['post_title']; ?></option>
											<?php }else{?>
											<option value="<?php echo $page['id']; ?>"  data-foo="<?php echo $page['url_slug']; ?>" ><?php echo $page['post_title']; ?></option>
											<?php }
										}
									}
									?>
								</select>
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
			</div>
		</form><!--PAGE SPAN END-->
	</div><!--PAGE CONTENT END-->

</div><!--MAIN CONTENT END-->

<div id="loading" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Wait for a moment updating post
				</div>
			</div>

			<div class="modal-body">


				<div class="center">
					<i id="product_loading" class="icon-spinner icon-spin blue bigger-300 hide"></i>
				</div>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div id="retrieve-loading" class="modal fade" style="display: none;'">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Retrieving Content
				</div>
			</div>
			<div class="modal-body">
				<p>Loading Content... Please Wait...</p>

				<div class="center">
					<i id="product_loading" class="icon-spinner icon-spin blue bigger-300 hide"></i>
				</div>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="modal-gallery-loading" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Uploading Photo <span id="modal-gallery-loading"></span>
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

<div id="modal-gallery-photo-edit" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Uploading Photo
				</div>
			</div>
			<div class="modal-body" style="max-height: 100%; overflow-y: visible">
				<div id="modal-gallery-photo-edit-loading" style="padding: 10px; display: none;">
					<p class="text-center">Loading Photo Info... Please Wait...</p>
				</div>
				<div class="form-horizontal" id="modal-gallery-photo-edit-field-container" style="display: none;">
					<div class="control-group">
						<label class="control-label control-label-sidebar" for="modal-photo-name">Photo Name:</label> 
						<div class="controls">
							<input type="hidden" class="hide" id="modal-photo-id" value="">
							<input type="text" class="input-xlarge" id="modal-photo-name" value="">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label control-label-sidebar" for="modal-photo-name">Photo Description:</label> 
						<div class="controls">
							<textarea name="modal-photo-description" id="modal-photo-description" cols="30" rows="10" class="input-xlarge"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer no-margin-top">
				<button class="btn btn-small btn-success" data-dismiss="modal" id="modal-save-photo-name">
					<i class="icon-save"></i>
					Save
				</button>
				<button class="btn btn-small btn-primary" data-dismiss="modal">
					<i class="icon-close"></i>
					Close
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div class="ace-settings-container" id="page-settings-container">
	<div class="btn btn-app btn-mini btn-warning ace-settings-btn" id="ace-settings-btn">
		<i class="icon-cog bigger-150"></i>
	</div>

	<div class="ace-settings-box" id="ace-settings-box">
		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="toggle-main-page-content" />
			<label class="lbl" for="toggle-main-page-content"> Show Main Content</label>
		</div>

		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="toggle-other-settings" />
			<label class="lbl" for="toggle-other-settings"> Show Settings</label>
		</div>
	</div>
</div><!--/#ace-settings-container-->