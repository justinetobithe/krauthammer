<div id="page-setting" class="tab-pane">
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Page Settings</h4>
			<div class="widget-toolbar no-border">
				<button class="btn btn-mini btn-success" id="btn-save-page-setting">
					<i class="icon-save"></i>
					Save
				</button>
			</div>
		</div>
		<div class="widget-body">
			<div class="widget-main">
				<?php 
				$selected_homepage = get_system_option(array('option_name' =>'homepage')); 
				$selected_blogpage = get_system_option(array('option_name' =>'blog_page')); 
				?>
				<div class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="blog-post-count"><small>Set a page as Homepage</small></label>
						<div class="controls">
							<select name="selected-homepage" id="selected-homepage">
								<option value="" >- Select a Page -</option>
								<?php foreach ($pages as $key => $page): ?>
									<option value="<?php echo $page->id; ?>" <?php echo $selected_homepage == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="blog-post-count"><small>Set a page as Blog Page</small></label>
						<div class="controls">
							<select name="selected-blog-page" id="selected-blog-page">
								<option value="" >- Select a Page -</option>
								<?php foreach ($pages as $key => $page): ?>
									<option value="<?php echo $page->id; ?>"  <?php echo $selected_blogpage == $page->id ? 'selected' : '' ?> ><?php echo $page->post_title ?></option>
								<?php endforeach ?> 
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>