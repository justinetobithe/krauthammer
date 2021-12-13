<div class="main-content">

	<div class="page-content">
		<div class="page-header">
			<h1>
				Menu  
			</h1>
		</div><!-- /.page-header -->

		<div class="row-fluid">
			<div class="span12">
				<input type="hidden" id="hdn_current_edited_menu" value="<?php echo $current_edited_menu['id'];?>">

				<div class="tabbable">
					<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="menus_tab">
						<li class="active">
							<a data-toggle="tab" href="#home">
								Edit Menus
							</a>
						</li>
						<button class="pull-right btn btn-danger btn-mini" style="margin-right: 10px;" id="btn-delete-menu">
							<i class="icon-trash"></i> Delete Menu
						</button>
					</ul>

					<div class="tab-content">
						<div id="home" class="tab-pane active">
							<div class="row-fluid">
								<div class="span12">
									<?php if (empty($menus)): ?>
										<div class="well well-small">
											Edit your menu below, or <a href="<?php echo URL;?>menus/new/"><strong>create a new menu.</strong></a>
											<br>
										</div>
									<?php else: ?>
										<div class="well well-small">
											Select a menu to edit: 
											<select id="menu_select" class="form_select">
											<?php foreach ($menus as $key => $menu): ?>
												<option value=<?php echo $menu['id']; ?>><?php echo $menu['name']; ?></option>
											<?php endforeach ?>
											</select> &nbsp; or
											<a href="<?php echo URL;?>menus/new/">
												<strong>create a new menu.</strong>
											</a>
											<br>
										</div>
									<?php endif ?>
								</div>
							</div>

							<div class="row-fluid">
								<div class="span12">
									<div class="well well-small">
										<h4>Language Settings:</h4>
										Select Language : 
										<select name="language" id="language" class="form_select">
											<?php foreach ($languages as $key => $value): ?>
											<option value="<?php echo $value->slug ?>"><?php echo $value->value . ($value->selected == 'selected' ? " [default]" : ""); ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>

							<div class="row-fluid">
								<div class="span4">
									<div id="accordion" class="accordion-style2">
										<div class="group">
											<h3 class="accordion-header" >Pages</h3>

											<div class="tabbable">
												<ul class="nav nav-tabs" id="pages_tab">
													<li class="active">
														<a data-toggle="tab" href="#most_recent_page">
															View All
														</a>
													</li>
												</ul>

												<div class="tab-content">
													<div id="most_recent_pages" class="tab-pane in active">
														<div style="overflow:true; height:200px;">
															<div>
															<?php if (!empty($pages)): ?>
															<?php foreach ($pages as $key => $page): ?>
																<div>
																	<input name="pages_checkbox" type="checkbox" class="ace page_checkbox" value="<?php echo $page['post_title'];?>">
																	<span class="lbl"> <?php echo $page['post_title'];?></span>
																	<input type="hidden" class="id" value="<?php echo $page['id']; ?>">
																</div>
															<?php endforeach ?>
															<?php endif ?>

															</div>
														</div>
													</div>
												</div>
												<br>

												<a href="#" class="select_all_pages">Select All</a>
												<button class="btn btn-info btn-small pull-right" onclick="add_page_ui()"> Add to Menu</button>
											</div>
										</div>

										<div class="group">
											<h3 class="accordion-header" >Links</h3>

											<div class="control-group">
												<div id="alert_link"></div>

												<label class="control-label" >URL:</label>
												<div class="controls">
													<div class="input-group">
														<input type="text"  class="input span12" id="url_link_ui" placeholder="Eg. http://www.google.com" />
													</div>
												</div>
												<label class="control-label" >Link Text:</label>
												<div class="controls">
													<div class="input-group">
														<input type="text"  class="input span12" placeholder="Menu Item" id="link_text_ui" />
													</div>
												</div>
												<div class="controls">
													<button class="btn btn-info btn-small pull-right" id="btn_link" onclick="add_to_menu_link()"> Add to Menu</button>
												</div>
											</div>
										</div>

										<div class="group">
											<h3 class="accordion-header" >Categories</h3>
											
											<div class="tabbable">
												<ul class="nav nav-tabs" id="myTab">
													<li class="active">
														<a data-toggle="tab" href="#most_recent">
															<small>Most Recent</small>
														</a>
													</li>

													<li>
														<a data-toggle="tab" href="#view_all">
															<small>View All</small>
														</a>
													</li>

													<li>
														<a data-toggle="tab" href="#search">
															<small>Dropdown Search</small>
														</a>
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

													<a href="#">Select All</a>
													<button class="btn btn-info btn-small pull-right"> Add to Menu</button>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="span8">
									<div id="alert_menu"></div>

									<div class="widget-box">
										<div class="widget-header">
											<h5><span class="pull-left" style="margin-right: 10px;"><small>Menu Name</small></span><input type="text" class="input input-xlarge pull-left" id="name" style="margin-top:0.5%;" value="<?php echo $current_edited_menu['name'];?>"></h5>
											<div class="widget-toolbar">
												<button class="btn btn-info btn-small" onclick="update_menu()">Save Menu</button>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main">
												<h5>
													<strong>Menu Structure</strong>
												</h5>

												<p>Drag Each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</p>

												<div class="row-fluid">
													<div class="span12">
														<div id="accordion_new_2" class="dd"></div>
													</div>
												</div>

												<div id="accordion_new" class="accordion-style2">
												<?php if (!empty($nodes) && false): ?>
												<?php foreach ($nodes as $key => $node): ?>
													<?php if (array_key_exists('link_url', $node)): ?>
													<?php
														echo '<div class="group" id="link_'. $node['id']. '"><div class="accordion-header">'.$node['link_text'].'<input type="hidden" value="'.$node['link_text'].'"><input type="hidden" class="" value="'.$node['link_url'].'"><div class="pull-right action-buttons">Custom<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-caret-down bigger-110 width-auto"></i></a><ul class="dropdown-menu dropdown-info"><li><a data-toggle="tab" href="#dropdown1">Custom</a></li><li><a data-toggle="tab" href="#dropdown2">Page</a></li></ul></div></div><div><div class="control-group">  <label class="control-label" >URL:</label><div class="controls"><div class="input-group"><input type="text"  class="input-xxlarge link_url" placeholder="Eg. http://www.google.com" value="'.$node['link_url'].'"/></div></div><label class="control-label" >Navigation Label:</label><div class="controls"><div class="input-group"><input type="text" class="input-xlarge link_text" id="url_text" value="'.$node['link_text'].'"/></div></div><label class="control-label" >Title Attribute:</label><div class="controls"><div class="input-group"><input type="text"  class="input-xlarge"/></div></div>
			<div class="controls">
				<div class="input-group">
					<label>
            <input name="form-field-checkbox" type="checkbox menu_enable" class="ace">
            <span class="lbl"> Enable</span>
          </label>
				</div>
			</div><p><a href="#" style="color: red;" class="delete_node_a"> Remove <input type="hidden" class="hdn_node_div_id" value="'.$node['id'].'"><input type="hidden" class="kind" value="link"></a> | <a href="#"> Cancel</a></p></div></div> </div>';
													?>
													<?php else: ?>
													<?php 
														echo '<div class="group" id="page_'.$node['id'].'"><div class="accordion-header">'.$node['link_text'].'<input type="hidden" class="post_id" value="'.$node['post_id'].'"><div class="pull-right action-buttons">Page<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-caret-down bigger-110 width-auto"></i></a><ul class="dropdown-menu dropdown-info"><li><a data-toggle="tab" href="#dropdown1">Page</a></li><li><a data-toggle="tab" href="#dropdown2">Custom</a></li></ul></div></div><div><div class="control-group">  <div class="controls"><div class="input-group"></div></div><label class="control-label" >Navigation Label:</label><div class="controls"><div class="input-group"><input type="text"  class="input-xlarge link_text" value="'.$node['link_text'].'"/></div></div><label class="control-label" >Title Attribute:</label><div class="controls"><div class="input-group"><input type="text"  class="input-xlarge" /></div></div>
			<div class="controls">
				<div class="input-group">
					<label>
            <input name="form-field-checkbox" type="checkbox" class="ace menu_enable" >
            <span class="lbl"> Enable</span>
          </label>
				</div>
			</div><div class="alert alert-info">Original:<a> '.$node['link_text'].'</a><br></div><p><a href="#" style="color: red;" class="delete_node_a"> Remove <input type="hidden" class="hdn_node_div_id" value="'.$node['id'].'"><input type="hidden" class="kind" value="page"></a> | <a href="#"> Cancel</a></p></div></div> </div>';
													?>
													<?php endif ?>
												<?php endforeach ?>
												<?php endif ?>
												</div><!-- #accordion -->
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
						${menu_label_trimmed}
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

			<div class="controls">
				<div class="input-group">
					<label>
            <input name="form-field-checkbox" type="checkbox" class="ace menu_enable" >
            <span class="lbl"> Enable Link</span>
          </label>
				</div>
			</div>

			<div class="alert alert-info">
				Original:<a> ${menu_original}</a>
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

			<div class="controls">
				<div class="input-group">
					<label>
            <input name="form-field-checkbox" type="checkbox" class="ace menu_enable" >
            <span class="lbl"> Enable Link</span>
          </label>
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
