<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>Add Page</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="add_page">
		<input type="hidden" id="hdn_page_id" />
			<form class="form-horizontal" id="page_form" action="<?php echo URL;?>pages/addPage/" enctype="multipart/form-data" method="post" onsubmit="return validateForm()">
				<div class="row-fluid">
					<div class="span9">
						<div class="widget-box" style="margin-bottom:10px; border:none;">
							<div id="alertPage"></div>
							<input type="text" name ="title" class="input-xxlarge" id="txt_post_title" style="width:98%;" placeholder = "Enter Page Title Here">
							<input type="hidden" name="link" id="link" />
							<p><i>Permalink:</i> <a target="_blank" id="permalink"></a></p>
							<div style="margin-bottom:5px;">
								<input type="text" name ="url_slug" class="input-large" id="txt_url_slug" placeholder="URL Slug" style="width:98%;" >
							</div>
						</div><!-- PAGE CONTENT BEGINS -->
						<div class="widget-box">
							<div class="widget-body">
								<input type="hidden" id="hdn_content" name="content">
								<textarea id="content"></textarea>
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
									<select name="status" class="dropdown-select" style="width:100%; " >
										<option value="publish">Publish</option>
										<option value="draft">Draft</option>
									</select>
									<br>
									<br>
									<div>
										<input type="submit" class="btn btn-small btn-success" style="width:47%;" name="submit" id="btn_save_product" onclick="addData();" value="Save" />
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
								<h5>Page Template</h5>
							</div>
							<div class="widget-body">
								<div class="widget-main">
									<select name="page_template" id="page_template" style="width:100%;" class="dropdown-select">
										<?php if(!empty($templates))
										foreach ($templates as $key => $template) { ?>
										<option value="<?php echo $template['value']; ?>"><?php echo $template['name']; ?></option>
										<?php }?>
									</select>

									<div id="page-category-tree-container">
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

										if(!empty($pages))
											foreach ($pages as $key => $page) { ?>
										<option value="<?php echo $page['id']; ?>" data-foo="<?php echo $page['url_slug']; ?>"><?php echo $page['post_title']; ?></option>
										<?php }?>
									</select>
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
					Wait for a moment posting page
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
<div id="categories_model" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Post Categories
				</div>
			</div>
			<div class="modal-body"></div>
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