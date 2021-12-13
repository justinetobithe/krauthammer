<div class="main-content">

	<div class="page-content">
		<div class="page-header">
			<h1>
				Add Menu  
			</h1>
		</div><!-- /.page-header -->

		<div class="row-fluid">
			<div class="span12">
				<div class="tabbable">
					<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="menus_tab">
						<li class="active">
							<a data-toggle="tab" href="#home">
								Edit Menus
							</a>
						</li>
					</ul>

					<div class="tab-content">
						<div id="home" class="tab-pane active">
							<div class="row-fluid">
								<div class="span4">
									<div id="accordion" class="accordion-style2">

										<div class="group">
											<h3 class="accordion-header" >Pages</h3>

											<div class="tabbable">
												<ul class="nav nav-tabs" id="pages_tab">
													<li class="active">
														<a data-toggle="tab" href="#most_recent_page">
															Most Recent
														</a>
													</li>
													<li>
														<a data-toggle="tab" href="#view_all_page">
															View All
														</a>
													</li>
													<li>
														<a data-toggle="tab" href="#search_page">
															Dropdown Search
														</a>
													</li>
												</ul>

												<div class="tab-content">
													<div id="most_recent_page" class="tab-pane in active">
														<div class="slim-scroll" data-height="200">
															<?php if (!empty($pages)): ?>
																<?php foreach ($pages as $key => $page): ?>
																	<div>
																		<input name="pages_checkbox" type="checkbox" class="ace page_checkbox" value="<?php echo $page['post_title'];?>">
																		<span class="lbl"> <?php echo $page['post_title'];?></span>
																		<input type="hidden" class="id" value="<?php echo $page['id']?>">
																	</div>
																<?php endforeach ?>
															<?php endif ?>
														</div>
													</div>

													<div id="view_all_page" class="tab-pane">
														<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
													</div>
													<div id="search_page" class="tab-pane">
														<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
													</div>

													<a href="#" class="select_all_pages">Select All</a>
													<button class="btn btn-info btn-small pull-right disabled" onclick="add_page_ui()"> Add to Menu</button>
												</div>
											</div>
										</div>

										<div class="group">
											<h3 class="accordion-header" >Links</h3>
											<div class="control-group">
												<div id="alert_link">
												</div>
												<label class="control-label" >URL:</label>
												<div class="controls">
													<div class="input-group">
														<input type="text"  class="input span12" id="url_link_ui" placeholder="Eg. http://www.google.com" />
													</div>
												</div>
												<label class="control-label" >Link Text:</label>
												<div class="controls">
													<div class="input-group">
														<input type="text"  class="input span12" id="link_text_ui" placeholder="Menu Item" />
													</div>
												</div>
												<div class="controls">
													<button class="btn btn-info btn-small pull-right disabled" id="btn_link" onclick="add_to_menu_link()"> Add to Menu</button>
												</div>
											</div>
										</div>

										<div class="group">
											<h3 class="accordion-header" >Categories</h3>
											<div class="row-fluid">
												<div class="span12">
													<div class="tabbable">
														<ul class="nav nav-tabs" id="categories_tab">
															<li class="active">
																<a data-toggle="tab" href="#most_recent">View All</a>
															</li>

														</ul>

														<div class="tab-content">
															<div id="most_recent" class="tab-pane in active"></div>

															<div id="view_all" class="tab-pane">
																<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
															</div>
															<div id="search" class="tab-pane">
																<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
															</div>

															<br>
															<br>

															<a href="#">Select All</a>
															<button class="btn btn-info btn-small pull-right disabled"> Add to Menu</button>
														</div>
													</div>
												</div>
											</div>	
										</div>

									</div>

								</div>

								<div class="span8">
									<div id="alert_menu"></div>

									<div class="widget-box">
										<div class="widget-header">
											<h5><span class="pull-left" style="margin-right: 10px;"><small>Menu Name</small></span><input type="text" class="input input-xlarge pull-left" placeholder="Menu Name" id="name" style="margin-top:0.5%;"></h5>
											<div class="widget-toolbar">
												<button class="btn btn-info btn-small" onclick="add_menu();" id="btn-add-menu">Create Menu</button>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main">
												<p>
													Give your menu a name above, then click Create Menu.
												</p>
												<div id="accordion_new" class="accordion-style2"></div>
											</div>
										</div>

									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<div id="delete" class="modal fade">
	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					Delete Page
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">×</span>
					</button>
				</div>
			</div>

			<div class="modal-body">

				<div id="delete_msg">
					<h5>Are you sure to delete this link?</h5>
				</div>
				<input type="hidden" id="hdn_div_id"/>
			</div><!-- /.modal-content -->
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-danger pull-right" onclick="delete_menu_ui();">
					<i class="icon-trash"></i>
					Delete
				</button>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</div>

<script id="menu-item-template" type="text/x-jquery-tmpl"> 
	<li class="dd-item" data-id="${menu_item_id}">
		<div class="dd-handle dd2-handle">
			<i class="normal-icon icon-move blue bigger-130"></i>
			<i class="drag-icon icon-move bigger-125"></i>
		</div>
		<div class="dd2-content">
			<div class="accordion">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a href="#collapse-${menu_group_id}" data-parent="#accordion_new_2" data-toggle="collapse" class="accordion-toggle collapsed">
							${menu_label}
						</a>
					</div>
					<div class="accordion-body collapse" id="collapse-${menu_group_id}">
						<div class="accordion-inner"></div>
					</div>
				</div>
			</div>
		</div>
	</li>
</script>

<script id="menu-item-field-template-page" type="text/x-jquery-tmpl">  
	<div class="group" id="group-${menu_group_id}">
		<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" aria-labelledby="ui-accordion-accordion_new-header-0" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">

			<div class="group-hidden-fields hide">
				<input type="hidden" class="menu_id" value="${menu_id}">
				<input type="hidden" class="menu_guid" value="${menu_sub_id}">
				<input type="hidden" class="menu_type" value="${menu_type}">
				<input type="hidden" class="menu_target" value="${menu_tag_target}">
				<input type="hidden" class="menu_return_id" value="${menu_group_id}">
			</div>

			<div class="control-group">
				<div class="controls">
					<div class="input-group"></div>
				</div>
				<label class="control-label">Text Label</label>
				<div class="controls">
					<div class="input-group">
						<input type="text" class="input-xlarge menu_text" value="${menu_label}">
					</div>
				</div>

				<label class="control-label">Title Attribute</label>

				<div class="controls">
					<div class="input-group">
						<input type="text" class="input-xlarge menu_title" value="${menu_title}">
					</div>
				</div>

				<div class="alert alert-info">
					Original:<a> ${menu_label}</a>
					<br>
				</div>

				<br><br>

				<p>
					<a href="javascript:void(0)" style="color: red;" class="delete_node_a"> Remove </a> | 
					<a href="javascript:void(0)"> Cancel</a>
				</p>
			</div>
		</div>
	</div>
</script>

<script id="menu-item-field-template-link" type="text/x-jquery-tmpl">  
	<div class="group" id="group-${menu_group_id}">
		<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" aria-labelledby="ui-accordion-accordion_new-header-0" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">

			<div class="group-hidden-fields hide">
				<input type="hidden" class="menu_id" value="${menu_id}">
				<input type="hidden" class="menu_guid" value="${menu_sub_id}">
				<input type="hidden" class="menu_type" value="${menu_type}">
				<input type="hidden" class="menu_target" value="${menu_tag_target}">
				<input type="hidden" class="menu_return_id" value="${menu_group_id}">
			</div>

			<div class="control-group">  
				<label class="control-label" >URL:</label>
				<div class="controls">
					<div class="input-group">
						<input type="text" class="input span12 menu_url" placeholder="Eg. http://www.exmple.com" value="${menu_url}"/>
					</div>
				</div>
				<label class="control-label" >Text Label:</label>
				<div class="controls">
					<div class="input-group">
						<input type="text" class="input input-xlarge menu_text " value="${menu_label}">
					</div>
				</div>
				<label class="control-label" >Title Attribute: </label>
				<div class="controls">
					<div class="input-group">
						<input type="text" class="input input-xlarge menu_title" value="${menu_title}">
					</div>
				</div>

				<br><br>

				<p>
					<a href="javascript:void(0)" style="color: red;" class="delete_node_a"> Remove </a> | 
					<a href="javascript:void(0)"> Cancel</a>
				</p>
			</div>
		</div> 
	</div>
</script>
