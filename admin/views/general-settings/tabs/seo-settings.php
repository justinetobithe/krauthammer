<div id="seo-setting" class="tab-pane">
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Site URL</h4>

			<div class="widget-toolbar no-border">
				
			</div>
		</div>
		<div class="widget-body">
			<div class="widget-main">
				<div class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="blog-post-count"><small>Site URL</small></label>
						<div class="controls">
							<input type="text" id="site_url" class="input input-small span12" placeholder="Enter Site URL Format" value="<?php echo isset($site_url) ? $site_url : ""; ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Post URL Format</h4>
		</div>
		<div class="widget-body">
			<div class="widget-main">
				<div class="form-horizontal" id="post-format-container">
					<?php global $post_link_format; ?>
					<?php foreach ($post_link_format as $key => $value): ?>
						<?php 
						$checked = isset($post_format) ? $post_format : "";
						$checked = $checked == $value['value'] ? "checked" : "";
						?>
						<div class="control-group">
							<label class="control-label" for="blog-post-count"><small><?php echo $value['label']; ?></small></label>
							<div class="controls">
								<label>
									<input name="post_url_format" type="radio" class="ace" value="<?php echo $value['value']; ?>" <?php echo $checked; ?> > &nbsp;
									<span class="lbl"><small><?php echo $value['format']; ?></small></span>
								</label>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</div>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Indexing</h4>
		</div>

		<div class="widget-body">
			<div class="widget-main">
				<div>
					<label class="span6">Disallow Indexing:</label>
					<input type="hidden" name="indexing" id="indexing" value="OFF" />
					<input id="switch_indexing" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox" onchange="change_index();" />
					<span class="lbl"></span>
				</div>  
			</div>
		</div>
	</div>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Blog Index Settings</h4>
		</div>

		<div class="widget-body">
			<div class="widget-main">
				<div>
					<label class="span6">Disallow Indexing Posts:</label>
					<input type="hidden" name="indexing" id="blog-post-indexing" value="OFF" class="blog_indexing_switch" />
					<input id="blog_post_indexing" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox"  value="OFF" />
					<span class="lbl"></span>
				</div>  
				<br>

				<div>
					<label class="span6">Disallow Indexing Blog:</label>
					<input type="hidden" name="indexing" id="blog-indexing" value="OFF" class="blog_indexing_switch" />
					<input id="blog_indexing" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox"  value="OFF" />
					<span class="lbl"></span>
				</div>  
				<br>

				<div>
					<label class="span6">Disallow Indexing Blog Pagination:</label>
					<input type="hidden" name="indexing" id="blog-pagination-indexing" value="OFF" class="blog_indexing_switch" />
					<input id="blog_pagination_indexing" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox"  value="OFF" />
					<span class="lbl"></span>
				</div>  
				<br>

				<div>
					<label class="span6">Disallow Indexing Blog Search:</label>
					<input type="hidden" name="indexing" id="blog-search-indexing" value="OFF" class="blog_indexing_switch" />
					<input id="blog_search_indexing" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox"  value="OFF" />
					<span class="lbl"></span>
				</div>  
				<br>

				<div>
					<label class="span6">Disallow Indexing Blog Categories:</label>
					<input type="hidden" name="indexing" id="blog-category-indexing" value="OFF" class="blog_indexing_switch"  />
					<input id="blog_category_indexing" name="switch_indexing" class="ace ace-switch ace-switch-7" type="checkbox"  value="OFF" />
					<span class="lbl"></span>
				</div>  
				<br>

			</div>
		</div>
	</div>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Robots.txt</h4>
		</div>

		<div class="widget-body">
			<div class="widget-main">
				<div class="form-horizontal">
					<div class="row-fluid">
						<div class="span12">
							<div class="control-group">
								<label class="control-label" for="copyright_text"></label>
								<div class="controls">
									<p>This setting will append on current robots.txt</p>
								</div>
							</div>
							<hr>
							<div class="control-group">
								<label class="control-label" for="copyright_text">robots.txt:</label>
								<div class="controls">
									<textarea name="robottxt" id="robottxt" class="span12" cols="30" rows="10" placeholder="enter robots.txt content here"><?php echo get_system_option('system_robot_txt') ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Additional Pages to Noindex</h4>
		</div>

		<div class="widget-body">
			<div class="widget-main">
				<div class="form-horizontal">
					<div class="row-fluid">
						<div class="span12">
							<div class="control-group">
								<label class="control-label" for="copyright_text"></label>
								<div class="controls">
									<p>This setting contains list of url slug to be restricted in the frontend. URL slugs should have a leading "/". Each url slug are separated by a newline.</p>
									<p>Example: </p>
									<ul class="list-unstyled">
										<li>/slug-1</li>
										<li>/slug-2</li>
										<li>/slug-3</li>
									</ul>
								</div>
							</div>
							<hr>
							<div class="control-group">
								<label class="control-label" for="copyright_text">List of Noindex Pages:</label>
								<div class="controls">
									<textarea name="blacklisted_url" id="blacklisted_url" class="span12" cols="30" rows="10" placeholder="/do-not-404-me/"><?php echo get_system_option('blacklisted_url') ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">301 Permanent Redirect</h4>
		</div>

		<div class="widget-body">
			<div class="widget-main">
				<div>
					<label class="span6">301 Permanent Redirect to SSL (https) sitewide</label>
					<input id="https_redirect" name="https_redirect" class="ace ace-switch ace-switch-7" type="checkbox" />
					<span class="lbl"></span>
				</div>
			</div>
		</div>
	</div>


	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Structured Data (JSON-LD)</h4>
		</div>

		<div class="widget-body">
			<div class="widget-main">
				
				<div class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="structured_data_enable"><small>Enable Structured Data</small></label>
						<div class="controls">
							<input id="structured_data_enable" name="structured_data_enable" class="ace ace-switch ace-switch-7" type="checkbox" />
							<span class="lbl"></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="structured_data_company_name"><small>Company Name</small></label>
						<div class="controls">
							<input type="text" id="structured_data_company_name" class="input input-small span12" placeholder="Enter Company Name" value="">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="structured_data_office_address"><small>Office Address</small></label>
						<div class="controls">
							<textarea name="" id="structured_data_office_address" class="input input-small span12" placeholder="Enter Office Address" value="" cols="30" rows="5" style="resize: none;"></textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="structured_data_telephone"><small>Telephone Number</small></label>
						<div class="controls">
							<input type="text" id="structured_data_telephone" class="input input-small span12" placeholder="Enter Telephone Number" value="">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="structured_data_email"><small>Email Address</small></label>
						<div class="controls">
							<input type="text" id="structured_data_email" class="input input-small span12" placeholder="Enter Email Address" value="">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="structured_data_price_range"><small>Price Range</small></label>
						<div class="controls">
							<input type="text" id="structured_data_price_range" class="input input-small span12" placeholder="Enter Price Range" value="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<hr>
	<div class="text-right">
		<button class="btn btn-success" id="btn-save-seo-setting"> <i class="icon-save"></i> Save </button>
	</div>
</div>