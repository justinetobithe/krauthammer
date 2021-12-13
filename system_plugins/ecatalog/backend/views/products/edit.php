<?php $system_type = check_system('system_type');?>
<div class="main-content">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Edit Product       
			</h1>
		</div><!-- /.page-header -->
		<input type="hidden" id="action" value="edit_product">
		<input type="hidden" id="selected_categories" value="<?php echo $selected_categories;?>">
		<input type="hidden" id="hdn_featured" value="<?php echo $products['featured_product'];?>">
		<input type="hidden" id="hdn_recommended" value="<?php echo $products['recommended_for_checkout'];?>">

		<form class="form-horizontal" id="add_product_form" action="<?php echo URL;?>products/updateProduct/" enctype="multipart/form-data" method="post" onsubmit="return validateForm()">
			<div class="row-fluid">
				<div class="span9">
					<div class="widget-box" style="margin-bottom:10px; border:none;">
						<div id="alertProductName"></div>
						<input type="hidden" name="product_id" id="product_id" value="<?php echo $products['id'];?>">
						<input type="hidden" id="old_slug" name="old_slug" value="<?php echo $products['url_slug']; ?>" />
						<input type="text" name ="product_name" class="input-xxlarge" id="txt_product_name"  style="width:98%;" value="<?php //echo $products['product_name'];?>">
						<p><i>Permalink:</i> <a target="_blank" id="permalink" href="<?php //echo FRONTEND_URL."/products/".$products['url_slug'];?>/"><?php //echo FRONTEND_URL."/products/".$products['url_slug'];?>/</a></p>
						<div style="margin-bottom:5px;">
							<input type="text" name ="url_slug" class="input-xxlarge" id="txt_url_slug" placeholder = "URL Slug" value="<?php //echo $products['url_slug'];?>" style="width:98%;">
							<input type="hidden" value="<?php echo $products['url_slug'];?>" id="hidden_slug"/>
						</div>
					</div><!-- PAGE CONTENT BEGINS -->

					<div class="widget-box transparent">
						<div class="widget-body">
							<input type="hidden" id="hidden_product_description" name="product_description" />
							<textarea id="product_description" class="main-page-content hide" style="width:100%;"></textarea>
						</div>
					</div>

					<div class="tabbable tabs-left" id="product-main-settings" >
						<ul class="nav nav-tabs" id="myTab3">
							<li>
								<a data-toggle="tab" href="#home3">
									<i class="green icon-barcode bigger-110"></i>
									Details
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#profile3">
									<i class="blue icon-picture bigger-110"></i>
									Product Gallery
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#profile4">
									<i class="red icon-group bigger-110"></i>
									Product Tabs
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#appointments">
									<i class="icon-check bigger-110"></i>
									Appointments
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#seo_settings">
									<i class="icon-asterisk bigger-110"></i>
									SEO Settings
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#additional_files">
									<i class="icon-download bigger-110"></i>
									Additional Files
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#product_options">
									<i class="icon-bar-chart bigger-110"></i>
									Product Option
								</a>
							</li>
							<li class="active">
								<a data-toggle="tab" href="#product_billing_period">
									<i class="icon-bar-chart bigger-110"></i>
									Billing Period
								</a>
							</li>
							<li>
								<a data-toggle="tab" href="#product_reviews">
									<i class="icon-comment bigger-110"></i>
									Reviews
								</a>
							</li>
						</ul>

						<div class="tab-content" style="min-height: 300px;">
							<div id="home3" class="tab-pane" style="overflow-x: hidden;">

								<div class="widget-box">
                  <div class="widget-header header-color-blue2">
                    <h4 class="lighter smaller">Product Details</h4>
                  </div>
                  <div class="widget-body">
                    <div class="widget-main">
											<div id='messageAlertDetails'></div>
											<div class="control-group">
												<label class="control-label" for="featured_product"><small>Featured Product?</small></label>
												<div class="controls">
													<div class="span4">
														<select name="featured_product" id="featured_product" class="full-chosen">
															<option value="no">No</option>
															<option value="yes">Yes</option>
														</select>
													</div>
													<div class="span8">
														<p class="grey"><small><i>If you set this option to yes, this product will be included in the featured product section.</i></small></p>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="recommended_checkout"><small>Recommended for Checkout</small></label>
												<div class="controls">
													<div class="span4">
														<select name="recommended_checkout" id="recommended_checkout" class="full-chosen">
															<option value="NO">No</option>
															<option value="YES">Yes</option>
														</select>
													</div>
													<div class="span8">
														<p class="grey"><small><i>If you set this option to yes, the customer will be recommended to buy this product before they proceed to checkout page.</i></small></p>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="txt_price"><small>Price</small></label>
												<div class="controls">
													<div class="span4 input-group">
														<span class="input-icon input-icon-left">
															<input type="text" id="txt_price" class="input span12" name="product_price" value="<?php if($products['price'] > 0 && $products['price'] != 0.00){echo $products['price'];} ?>">
															<i class="icon-dollar green"></i>
														</span>
													</div>
													<div class="span8">
														<p class="grey"><i><small>if no input, price will not appear on the front-end (Catalogue)</small></i></p>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="txt_sku"><small>SKU</small></label>
												<div class="controls">
													<div class="span12 input-group">
														<input type="text" id="txt_sku" class="input span12" name="product_sku" value="<?php echo $products['sku'];?>"/>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="txt_quantity"><small>Quantity (0 =  Out of Stock)</small></label>
												<div class="controls">
													<div class="span4">
														<input type="text" id="txt_quantity" class="input span12" name="product_qty" value="<?php if($products['quantity'] > 0){echo $products['quantity'];} ?>"/>
													</div>
													<div class="span7">
														<p class="grey"><i><small>if no input, means no recording of quantity needed.</small></i></p>
													</div>
												</div>
											</div>
											<?php if($system_type['option_value'] == 'ECOMMERCE'){ ?>
											<div class="control-group">
												<label class="control-label" for="txt_quantity"><small>Track Inventory</small></label>
												<div class="controls">
													<div class="span7 input-group">
														<label>
															<input id="switch_track_inventory" class="ace ace-switch ace-switch-7" type="checkbox" onchange="change_value_track_inventory();">
															<span class="lbl"></span>
														</label>

													</div>
												</div>
											</div>
											<?php } ?>
											<input type="hidden" name="track_inventory" id="track_inventory" value="<?php echo $products['track_inventory']; ?>"/>
											<div class="control-group">
												<label class="control-label" for="txt_out_of_stock"><small>Out of Stock Message</small></label>
												<div class="controls">
													<div class="span12">
														<input type="text" id="txt_out_of_stock" class="input span12" name="product_stock" value="<?php echo $products['out_of_stock_status'];?>"/>
													</div>
												</div>
											</div> 
											<div class="control-group">
												<label class="control-label" for="txt_min_order_qty"><small>Min. Order Qty</small></label>
												<div class="controls">
													<div class="span12">
														<input type="text" id="txt_min_order_qty" class="input span12" name="product_min_order_qty" value="<?php if($products['min_order_qty'] > 0){echo $products['min_order_qty'];} ?>"/>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="txt_max_order_qty"><small>Max. Order Qty</small></label>
												<div class="controls">
													<div class="span12">
														<input type="text" id="txt_max_order_qty" class="input span12" name="product_max_order_qty" value="<?php if($products['max_order_qty'] > 0){echo $products['max_order_qty'];} ?>"/>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="txt_interval"><small>Qty Interval</small></label>
												<div class="controls">
													<div class="span12">
														<input type="text" id="txt_interval" class="input span12" name="product_interval" value="<?php if($products['qty_interval'] > 0){echo $products['qty_interval'];} ?>"/>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="txt_qty_label"><small>Qty Label</small></label>
												<div class="controls">
													<div class="span12">
														<input type="text" id="txt_qty_label" class="input span12" name="product_qty_label" value="<?php echo $products['qty_label']; ?>"/>
													</div>
												</div>
											</div>
                    </div>
                  </div>
                </div>
							</div>
							<div id="product_attributes" class="tab-pane">
								<input type="hidden" id="hidden_product_attribute"  name="product_attributes"/>

								<div class="control-group">
									<button class="btn btn-info" id="add_new_products_attributes">Add New Product Option</button>  
								</div>
								<?php if(empty($products_attributes)){?>    
								<div id="accordion_products_attributes" class="accordion-style2 ">
									<div id="accordion" class="group accordion_group products_attr">
										<h3 class="accordion-header"><input type="text" class="input span8 textbox attr_label"></h3>

										<div>
											<div class="hr"></div>
											<div class="row-fluid">
												<button class="btn btn-info add_new_selection_values btn-small" onclick="add_new_attributes_inside(1); return false;">Add New Product Option Selection </button>
											</div>
											<div class="hr"></div>
											<div class="row-fluid">
												<div  class="accordion-style2 accordion_products_attributes_inside" id="new_attributes_accordion_1">

													<div id="accordion" class="group group_1 products_attr_inside">
														<h3 class="accordion-header-new"><input type="text" class="textbox label_selection" class="label"></h3>
														<div>
															<div class="row">
																<select class="pull-right delivery_method">
																	<option value="Shipped">Shipped</option><option value="Virtual">Virtual</option><option value="Download">Download</option><option value="Donation">Donation</option><option value="Subscription">Subscription</option><option value="N/A">Disabled</option>
																</select>
															</div>
															<br>
															<div class="table-responsive">
																<table id="sample-table-1" class="table table-striped table-bordered table-hover">
																	<thead>
																		<tr>
																			<th>
																				Price
																			</th>
																			<th>
																				<label>
																					<input type="checkbox" class="ace sale_price" value="1,0" />
																					<span class="lbl"> Sale Price</span>
																				</label>
																			</th>
																			<th>
																				<label>
																					<input type="checkbox" class="ace shipping" value="1,0"/>
																					<span class="lbl"> Shipping</span>
																				</label>
																			</th>
																			<th>
																				<label>
																					<input type="checkbox" class="ace inventory" value="1,0"/>
																					<span class="lbl"> Inventory</span>
																				</label>
																			</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>
																				<input type="text" class=" number input-small price" placeholder="$">
																				<br>
																				<label>
																					<input type="checkbox" class="ace" />
																					<span class="lbl"> Not Taxed</span>
																				</label>
																			</td>
																			<td class="sale_1_0"></td>
																			<td class="shipping_1_0"></td>
																			<td class="inventory_1_0"></td>
																		</tr>
																	</tbody>
																</table>
															</div><!-- /.table-responsive -->
														</div>

													</div>

												</div>
											</div>
											<div class="hr"></div>
											<div class="row-fluid">
												<input type="text" class="span3" placeholder="Minimum Quantity">
												<input type="text" class="span3" placeholder="Quantity Increment">
												<input type="text" class="span3" placeholder="Maximum Quantity">
												<input type="text" class="span3" placeholder="Product Unit">
											</div>
											<div class="row-fluid">
												<label>
													<input name="form-field-checkbox" type="checkbox" class="ace checkbox colorpicker_attributtes color" />
													<span class="lbl"> Tick if this is a color section.</span>
												</label>
											</div>
											<div class="row-fluid">
												<label>
													<input name="form-field-checkbox" type="checkbox" class="ace checkbox required">
													<span class="lbl"> Required?</span>
												</label>
											</div>
										</div>
									</div>
								</div><!-- #accordion -->
								<?php }
								else{ ?>
								<div id="accordion_products_attributes" class="accordion-style2 ">
									<?php foreach ($products_attributes as $key => $p_attr) { 
										$index = $key + 1;
										?>
										<div id="accordion" class="group accordion_group products_attr">
											<h3 class="accordion-header"><input type="text" class="input span8 textbox attr_label" value="<?php echo $p_attr['label'];?>"></h3>

											<div>
												<div class="hr"></div>
												<div class="row-fluid">
													<button class="btn btn-info add_new_selection_values btn-small" onclick="add_new_attributes_inside(<?php echo $index; ?>); return false;">Add New Product Option Selection </button>
												</div>
												<div class="hr"></div>
												<div class="row-fluid">
													<div  class="accordion-style2 accordion_products_attributes_inside" id="new_attributes_accordion_<?php echo $index; ?>">
														<?php if(empty($p_attr['product_attributes_selection'])){ ?>
														<div id="accordion" class="group group_1 products_attr_inside">
															<h3 class="accordion-header-new"><input type="text" class="textbox label_selection" class="label"></h3>
															<div>
																<div class="row">
																	<select class="pull-right delivery_method">
																		<option value="Shipped">Shipped</option><option value="Virtual">Virtual</option><option value="Download">Download</option><option value="Donation">Donation</option><option value="Subscription">Subscription</option><option value="N/A">Disabled</option>
																	</select>
																</div>
																<br>
																<div class="table-responsive">
																	<table id="sample-table-1" class="table table-striped table-bordered table-hover">
																		<thead>
																			<tr>

																				<th>
																					Price
																				</th>
																				<th>
																					<label>
																						<input type="checkbox" class="ace sale_price" value="1,0" />
																						<span class="lbl"> Sale Price</span>
																					</label>
																				</th>
																				<th>
																					<label>
																						<input type="checkbox" class="ace shipping" value="1,0"/>
																						<span class="lbl"> Shipping</span>
																					</label>
																				</th>
																				<th>
																					<label>
																						<input type="checkbox" class="ace inventory" value="1,0"/>
																						<span class="lbl"> Inventory</span>
																					</label>
																				</th>

																			</tr>
																		</thead>

																		<tbody>
																			<tr>


																				<td>
																					<input type="text" class=" number input-small price" placeholder="$">
																					<br>
																					<label>
																						<input type="checkbox" class="ace" />
																						<span class="lbl"> Not Taxed</span>
																					</label>
																				</td>
																				<td class="sale_1_0"></td>
																				<td class="shipping_1_0"></td>
																				<td class="inventory_1_0"></td>


																			</tr>


																		</table>
																	</div><!-- /.table-responsive -->
																</div>

															</div>
															<?php }else{?>
															<?php foreach ($p_attr['product_attributes_selection'] as $key => $p_slc) {
																$index_i = $key;
																?>
																<div id="accordion" class="group group_<?php echo $index; ?> products_attr_inside">
																	<h3 class="accordion-header-new"><input type="text" class="textbox label_selection" class="label" value="<?php echo $p_slc['label']; ?>"></h3>
																	<div>
																		<div class="row">
																			<select class="pull-right delivery_method">
																				<option value="Shipped">Shipped</option><option value="Virtual">Virtual</option><option value="Download">Download</option><option value="Donation">Donation</option><option value="Subscription">Subscription</option><option value="N/A">Disabled</option>
																			</select>
																		</div>
																		<br>
																		<div class="table-responsive">
																			<table id="sample-table-1" class="table table-striped table-bordered table-hover">
																				<thead>
																					<tr>

																						<th>
																							Price
																						</th>
																						<th>
																							<label>
																								<input type="checkbox" class="ace sale_price" value="<?php echo $index; ?>,<?php echo $index_i; ?>" <?php echo $p_slc['item_on_sale'] == 'yes' ? 'checked' : '' ?> />
																								<span class="lbl"> Sale Price</span>
																							</label>
																						</th>
																						<th>
																							<label>
																								<input type="checkbox" class="ace shipping" value="<?php echo $index; ?>,<?php echo $index_i; ?>" <?php echo $p_slc['calculate_shipping_fee'] == 'yes' ? 'checked' : '' ?> />
																								<span class="lbl"> Shipping</span>
																							</label>
																						</th>
																						<th>
																							<label>
																								<input type="checkbox" class="ace inventory" value="<?php echo $index; ?>,<?php echo $index_i; ?>" <?php echo $p_slc['track_inventory'] == 'yes' ? 'checked' : '' ?> />
																								<span class="lbl"> Inventory</span>
																							</label>
																						</th>

																					</tr>
																				</thead>

																				<tbody>
																					<tr>


																						<td>
																							<input type="text" class=" number input-small price" value="<?php echo $p_slc['price']; ?>" placeholder="$">
																							<br>
																							<label>
																								<input type="checkbox" class="ace" />
																								<span class="lbl"> Not Taxed</span>
																							</label>
																						</td>
																						<td class="sale_<?php echo $index; ?>_<?php echo $index_i; ?>">
																							<?php if($p_slc['item_on_sale'] == 'yes'){ ?>
																							<input type="text" class=" number input-small sale_price_text" value="<?php echo $p_slc['sale_price']; ?>">
																							<?php }  ?>
																						</td>
																						<td class="shipping_<?php echo $index; ?>_<?php echo $index_i; ?>">
																							<?php if($p_slc['calculate_shipping_fee'] == 'yes'){ ?>
																							<input type="text" class=" number input-small shipping_fee_text" value="<?php echo $p_slc['shipping_fee']; ?>">
																							<?php }  ?>
																						</td>
																						<td class="inventory_<?php echo $index; ?>_<?php echo $index_i; ?>"></td>


																					</tr>


																				</table>
																			</div><!-- /.table-responsive -->
																		</div>

																	</div>
																	<?php } 

																}?>
															</div>
														</div>
														<div class="hr"></div>
														<div class="row-fluid">
															<input type="text" class="span3" placeholder="Minimum Quantity">
															<input type="text" class="span3" placeholder="Quantity Increment">
															<input type="text" class="span3" placeholder="Maximum Quantity">
															<input type="text" class="span3" placeholder="Product Unit">
														</div>
														<div class="row-fluid">
															<label>
																<input name="form-field-checkbox" type="checkbox" class="ace checkbox colorpicker_attributtes color" <?php echo $p_attr['is_color_selection'] == 'yes' ? 'checked' : '' ?>/>
																<span class="lbl"> Tick if this is a color section.</span>
															</label>
														</div>
														<div class="row-fluid">
															<label>
																<input name="form-field-checkbox" type="checkbox" class="ace checkbox required" <?php echo $p_attr['required'] == 'yes' ? 'checked' : '' ?>>
																<span class="lbl"> Required?</span>
															</label>
														</div>
													</div>
												</div>
												<?php } ?>
											</div><!-- #accordion -->
											<?php 
										} ?>
									</div>
									<div id="profile3" class="tab-pane">
										<div class="row-fluid" id="images">
											<div class="span12">
												<div class="widget-box">
                          <div class="widget-header header-color-blue2">
                            <h4 class="lighter smaller">Product Images</h4>
                          </div>
                          <div class="widget-body">
                            <div class="widget-main">
                              <div id="fileupload" action="<?php echo URL; ?>products/upload_gallery" method="POST" enctype="multipart/form-data" class="">
																<noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
																<input type="hidden" id="hidden_image_name" name="image_name"/>
																<input type="hidden" id="hidden_product_id_for_gallery" name="product_id_for_gallery" value="<?php echo $products['id']; ?>">
																<div class="row-fluid fileupload-buttonbar">
																	<div class="span7">
																		<span class="btn btn-success fileinput-button">
																			<i class="glyphicon glyphicon-plus"></i>
																			<span>Add Photos</span>
																			<input multiple="multiple" type="file" name="files[]" accept="image/*" id="add_images_input"/>
																			<input type="hidden" id="product_id_image" name="image_name" value="<?php echo $products['id']; ?>"/>
																		</span>
																		<button type="submit" class="btn btn-primary start hide" id="start_aawwad">
																			<i class="glyphicon glyphicon-upload"></i>
																			<span>Start upload</span>
																		</button>
																	</div>
																	<div class="span5 fileupload-progress fade">
																		<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
																			<div class="progress-bar progress-bar-success" style="width:0%;"></div>
																		</div>
																		<div class="progress-extended">&nbsp;</div>
																	</div>
																</div>
																<table role="presentation" class="table table-striped" id="table_to_be_clone"><tbody class="files"></tbody></table>
															</div> 
                            </div>
                          </div>
                        </div>
											</div>
										</div>
									</div>

									<div id="profile4" class="tab-pane">
										<div class="widget-box">
                      <div class="widget-header header-color-blue2">
                        <h4 class="lighter smaller">Product Tabs</h4>
                      </div>
                      <div class="widget-body">
                        <div class="widget-main">
                          <input type="hidden" name="product_tabs" id="hdn_products_tab">
													<div class="form-horizontal">
														<div class="control-group">
															<button class="btn btn-success" id="product_tab_add_button" onclick="return false;"><i class="icon icon-plus"></i> Add Product Tab</button>  
														</div>
														<div class="widgets_tabs">
														<?php $index=0; ?>
														<?php if (empty($product_tabs)): ?>
															<div class="widget-box product_tabs"><div class="widget-header" style="padding: 5px 7px;"><div><input type="text" class="input-xlarge title" placeholder="Tab Title"><div class="widget-toolbar"><a href="#" data-action="" onclick="collapse_tab(0); return false;" style="color: #C7C5D1;"><i class="icon-chevron-up collapse" style="background-color: rgba(0,0,0,0);"></i></a></div></div> <input type="hidden" class="id_for_collapse" value=0>  </div><div class="widget-body"><div class="widget-main"><div class="hide"><input type="hidden" value="<?php echo $tab['id']; ?>" class="tab-id"></div><textarea class="textarea_product_tabs" id="tab_desc0"></textarea></div></div></div>
														<?php else: ?>
														<?php foreach ($product_tabs as $key => $tab): ?>
														<?php $index =  $key; ?>
															<div class="widget-box product_tabs" id="widget_<?php echo $tab['id'];?>"><div class="widget-header header-color-blue2" style="padding: 5px 7px;"><h5><input type="text" class="input-xlarge title" placeholder="Tab Title" value="<?php echo htmlentities($tab['tab_title']);?>"></h5><div class="widget-toolbar"><input type="hidden" class="id_for_collapse" value=<?php echo $key; ?>><a href="#" data-action="" onclick="collapse_tab(<?php echo $key; ?>); return false;" style="color: #C7C5D1;"><i class="icon-chevron-up collapse" style="background-color: rgba(0,0,0,0);"></i></a> <a href="#" data-action="" style="color: #F79B94; "><i class="icon-remove" onclick="delete_tab(<?php echo $tab['id']; ?>);"></i></a></div></div><div class="widget-body"><div class="widget-main"><div class="hide"><input type="hidden" value="<?php echo $tab['id']; ?>" class="tab-id"></div><textarea class= "textarea_product_tabs" id="tab_desc<?php echo $key;?>"></textarea></div></div></div>
														<?php endforeach ?>
														<?php endif ?>
														</div>
														<input type="hidden" id="hdn_index_tab" value="<?php echo $index; ?>">
													</div>
                        </div>
                      </div>
                    </div>
									</div>

									<div id="appointments" class="tab-pane">
										<div class="widget-box">
                      <div class="widget-header header-color-blue2">
                        <h4 class="lighter smaller">Product Appointments</h4>
                      </div>
                      <div class="widget-body">
                        <div class="widget-main">
                          <input type="hidden" id="hidden_product_appointments" name="hidden_product_appointments"/>
													<button class="btn btn-success" onclick="add_new_appointments(); return false;"><i class="icon icon-plus"></i> Add New Trip Period</button>
													<br>
													<br>
													<div class="row-fluid">
														<div class="accordion-style2" id="accordion_products_appointments">
														<?php if (!empty($appointments)): ?>
														<?php foreach ($appointments as $key => $app): ?>
															<div id="accordion" class="group appointments">
																<h3 class="accordion-header-new">Trip Period</h3>
																<div>
																	<span>Date From: </span><input type="text" class="input-small datepicker" value="<?php echo $app['date_from']; ?>">
																	<span> Date To: </span><input type="text" class="input-small datepicker_to" value="<?php echo $app['date_to']; ?>">
																	<span> Spots: </span><input type="text" class="input-small spot" value="<?php echo $app['spot']; ?>">
																	<button class="btn btn-mini btn-danger pull-right" onclick="delete_appointments(this); return false;"><i class="icon-trash bigger-120"></i></button>
																</div>
															</div>
														<?php endforeach ?>
														<?php else: ?>
															<div id="accordion" class="group appointments">
																<h3 class="accordion-header-new">Trip Period</h3>
																<div>
																	<span>Date From: </span><input type="text" class="input-small datepicker">
																	<span> Date To: </span><input type="text" class="input-small datepicker_to">
																	<span> Spots: </span><input type="text" class="input-small spot">
																	<button class="btn btn-mini btn-danger pull-right" onclick="delete_appointments(this); return false;"><i class="icon-trash bigger-120"></i></button>
																</div>
															</div>
														<?php endif ?>
														</div>
													</div>
                        </div>
                      </div>
                    </div>
									</div>

									<div id="seo_settings" class="tab-pane" style="overflow-x: hidden;">
										<div class="widget-box">
                      <div class="widget-header header-color-blue2">
                        <h4 class="lighter smaller">Product SEO Settings</h4>
                      </div>
                      <div class="widget-body">
                        <div class="widget-main">
                          <div class="form-horizontal">
														<div id='alert_seo_settings'></div>
														<div class="control-group">
															<label class="control-label" for="seo_title"><small>Title:</small></label>
															<div class="controls">
																<div class="span11 input-group">
																	<input type="text" id="seo_title" class="input-xlarge" name="seo_title" style="width: 100%;" value="<?php echo $products['seo_title']; ?>"/>
																	<br>
																	<p><strong id="title_char">0</strong> <small><i>characters. Most search engines use a maximum of <strong>60</strong> chars for the title.</i></small></p>
																</div>
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="seo_description"><small>Description:</small></label>
															<div class="controls">
																<div class="span11 input-group">
																	<textarea id="seo_description" name="seo_description" style="width:100%;" rows="6"><?php echo $products['seo_description']; ?></textarea>
																	<br>
																	<p><strong id="desc_char">0</strong> <small><i>characters. Most search engines use a maximum 1 of 60 chars for the description.</i></small></p>
																</div>
															</div>
														</div>
														<div class="control-group">
															<label class="control-label" for="seo_no_index"><small>Robots Meta NOINDEX:</small></label>
															<div class="controls">
																<div class="span4 input-group">
																	<label>
																		<input id="seo_no_index" class="ace ace-switch ace-switch-7" type="checkbox" onchange="change_value();">
																		<span class="lbl"></span>
																	</label> 
																	<input type="hidden" id="hdn_no_index"  name="seo_no_index" value="<?php echo $products['seo_no_index']; ?>">
																</div>
															</div>
														</div>
													</div>
                        </div>
                      </div>
                    </div>
									</div>

									<div id="additional_files" class="tab-pane">
										<div class="widget-box">
                      <div class="widget-header header-color-blue2">
                        <h4 class="lighter smaller">Additional Files</h4>
                      </div>
                      <div class="widget-body">
                        <div class="widget-main">
                          <div class="form-horizontal">
														<div id='alert_seo_settings'></div>
														<input multiple="multiple" type="file" name="additional_files_input[]" id="additional_files_input" />
														<ul id="tasks" class="item-list">
															<?php foreach ($additional_files as $key => $files) { ?>     
															<li class="item-green clearfix">
																<input type="hidden" class="hidden_additinal_files_id" value="<?php echo $files['id']; ?>" />
																<label class="inline">
																	<span class="lbl"><?php echo $files['name']?></span>
																</label>

																<div class="pull-right action-buttons">
																	<a class="blue" href="<?php echo URL.'products/download_files/'.$files['id']; ?>">
																		<i class="icon-download bigger-130"></i>
																	</a>

																	<span class="vbar"></span>

																	<a href="#" class="red" onclick="return delete_additional_files(<?php echo $files['id']; ?>);">
																		<i class="icon-trash bigger-130"></i>
																	</a>
																</div>
															</li>
															<?php } ?>
														</ul>
													</div>
                        </div>
                      </div>
                    </div>
									</div>  

									<div id="product_options" class="tab-pane">
										<div class="widget-box">
											<div class="widget-header header-color-blue2">
												<h4 class="lighter smaller">Product Options</h4>
											</div>

											<div class="widget-body">
												<div class="well well-small">
													<a href="javascript:void(0)" class="btn btn-success btn-small" id="modal-btn-product-options"><i class="icon icon-plus"></i> Edit Option</a>
												</div>

												<div id="product-option-container" style="display: none; padding: 10px;">
													<div class="product-option-container-header">
														<div class="row-fluid">
															<div class="span1 text-center"></div>
															<div class="span5">
																<label><b>Product Options</b></label>
															</div>
															<div class="span2">
																<b>SKU</b>
															</div>
															<div class="span2">
																<b>Quantity</b>
															</div>
															<div class="span2">
																<b>Price</b>
															</div>
														</div>
													</div>
													<div id="product-option-container-content"></div>
													<hr>
													<div>
														<button class="btn btn-success btn-small" id="product-option-save" data-loading-text="Loading..." type="button"><i class="icon icon-save"></i> Save</button>
													</div>
												</div>
											</div>
										</div>

										<div class="widget-box">
											<div class="widget-header header-color-blue2">
												<h4 class="lighter smaller">Product Brands</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<?php
													$product_brand_id = isset($product_brand->id) ? $product_brand->id : "0";
													$product_brand_image = isset($product_brand->logo_main_url) ? $product_brand->logo_main_url : "";
													$product_brand_label = isset($product_brand->brand_name) ? $product_brand->brand_name : "";
													?>
													<input type="hidden" id="selected_brand_id" name="selected_brand_id" value="<?php echo $product_brand_id; ?>">
													<p class="text-center"><img src="<?php echo $product_brand_image; ?>" alt="<?php echo $product_brand_image; ?>" id="selected_product_image" style="max-height: 300px; max-width: 100%;"></p>
													<h5 id="selected_product_label" class="text-center"><?php echo $product_brand_label; ?></h5>
													<button class="btn btn-small btn-info" style="width: 100%;" onclick="return false;" id="btn_select_brand">Select Brand</button>
												</div>
											</div>
										</div>
									</div>

									<div id="product_billing_period" class="tab-pane in active">
									<?php if ($subscription_setting['paypal_subscription_enabled']): ?>
										<!-- Display the Subscription Fields -->
										<div class="widget-box">
											<div class="widget-header header-color-blue2">
												<h4 class="lighter smaller">Billing Period</h4>
											</div>
											<div class="widget-body">
												<div class="widget-main">
													<div class="form-horizontal">
														<div class="control-group">
															<label for="product-billing-period-type" class="control-label"><small>Payment Type: </small></label>
															<div class="controls" style="margin-top: 5px;">
																<select id="product-billing-period-type" class="full-chosen">
																	<option value="One-time">One-time</option>
																	<option value="Subscription">Subscription</option>
																	<option value="Global Subscription">Global Subscription</option>
																</select>
															</div>
														</div>
													</div>
													<hr>
													<div style="display: none;" id="content-one-time">
														<div class="text-center">
															Selected <strong>One-time</strong> Payment
														</div>
													</div>
													<div style="display: none;" id="content-subscription">
														<div class="form-horizontal hide">
															<div class="control-group content-subscription">
																<label for="product-billing-period-toggle" class="control-label"><small>Enable Subscription</small></label>
																<div class="controls" style="margin-top: 5px;">
																	<label>
																		<input type="checkbox" class="ace ace-switch ace-switch-3" id="product-billing-period-toggle">
																		<span class="lbl"></span>
																	</label>
																</div>
															</div>
														</div>
														<table id="table-billing-period" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Default</th>
																	<th>Enable</th>
																	<th>Name</th>
																	<th>Frequency</th>
																	<th><div class="text-center">Action</div></th>
																</tr>
															</thead>
															<tbody></tbody>
														</table>
													</div>
													<div style="display: none;" id="content-subscription-global">
														<p><em>The product will only be available on selected Subsription Plan. </em></p>
														<table id="table-billing-period-global-subscription" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th></th>
																	<th>Subscription Name</th>
																	<th>Summary</th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
													<div style="display: none;" id="content-subscibers">
														<br>
														<table id="table-billing-period-subscribers" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Name</th>
																	<th>Reference</th>
																	<th style="text-align: center;">Status</th>
																	<th>Action</th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										<div class="widget-box">
											<div class="widget-header header-color-blue2">
												<h4 class="lighter smaller">Product Type</h4>
											</div>
											<div class="widget-body">
												<div class="widget-main">
													<div class="form-horizontal">
														<div class="control-group">
															<label for="product-type" class="control-label"><small>Product Type: </small></label>
															<div class="controls" style="margin-top: 5px;">
																<select id="product-type" class="full-chosen">
																	<option value="Physical Goods">Physical Goods</option>
																	<option value="Downloadable">Downloadable</option>
																</select>
															</div>
														</div>
													</div>
													<hr>
													<div style="display: none;" id="content-downloadable-files">
														<div class="row-fluid">
															<div class="span8">
																<p><em>Select files from Media module to be included in Products downloads. <a href="<?php echo URL ?>media/" target="_blank">Upload Here</a></em></p>
															</div>
															<div class="span4">
																<button class="btn btn-small btn-success span12" id="billing-period-product-files-media"><i class="icon icon-film"></i> Select from Media</button>
															</div>
														</div>
														<div class="media-container">
															<table id="table-product-downloadable-files" class="table table-striped table-bordered table-hover">
																<thead>
																	<tr>
																		<th>File Name</th>
																		<th>Type</th>
																		<th>Action</th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table>
														</div>
													</div>
													<div style="display: none;" id="content-physical-goods">
														<div class="text-center">
															No options available for this Product Type
														</div>
													</div>
												</div>
											</div>
										</div>

										<hr>
										<div class="text-right">
											<button id="btn-open-modal-billing-period" class="btn btn-medium btn-primary hide"><i class="icon icon-plus"></i> Add Billing Period</button>
											<a href="<?php echo URL . "payment-gateways/subscription/" ?>" class="btn btn-medium btn-primary" target="_blank"><i class="icon icon-plus"></i> Add Billing Period</a>
											<button id="btn-save-billing-period" class="btn btn-medium btn-success"><i class="icon icon-save"></i> Save Billing Period</button>
										</div>

									<?php else: ?>
										<!-- Show Message for Subscription Inability -->
										<div class="text-center alert"><strong>Subscription is currently disabled. <br> Enable it <a href="<?php echo URL ?>payment-gateways/subscription/">Here</a></strong></div>
									<?php endif ?>
									</div>

									<div id="product_reviews" class="tab-pane">
										<div class="widget-box">
											<div class="widget-header header-color-blue2">
												<h4 class="lighter smaller">Product Reviews</h4>
											</div>
											<div class="widget-body">
												<div class="widget-main">
													
													<div class="table-responsive">
														<div id="product-review-manage-container" class="accordion">
															<div class="accordion-group">
																<div class="accordion-heading">
																	<a href="#product-review-tab" data-parent="#product-review-manage-container" data-toggle="collapse" class="accordion-toggle collapsed">
																		Product Review (Add/Edit)
																	</a>
																</div>

																<div class="accordion-body collapse" id="product-review-tab">
																	<div class="accordion-inner">
																		<div>
																			<p>
																				<i>Add a comment on this Product</i> 
																				<button id="product-review-add" onclick="return false;" class="btn btn-small btn-primary pull-right" style="margin-left: 20px;">
																					<i class="icon icon-save"></i> 
																					Save
																				</button>
																				<a href="javascript:void(0)" id="product-review-clear" class="pull-right" style="margin-top: 5px;" >Add New</a>
																			</p>
																		</div>
																		<hr>
																		<div class="form-horizontal well well-small">
																			<div class="hide">
																				<input type="hidden" id="review-id">
																			</div>
																			<div class="control-group">
																				<label class="control-label" for="form-field-1">Name</label>
																				<div class="controls">
																					<input type="text" id="review-name" class="span12" placeholder="Name">
																				</div>
																			</div>
																			<div class="control-group">
																				<label class="control-label" for="form-field-1">Email</label>
																				<div class="controls">
																					<input type="text" id="review-email" class="span12" placeholder="Email">
																				</div>
																			</div>
																			<div class="control-group">
																				<label class="control-label" for="form-field-1">Review</label>
																				<div class="controls">
																					<textarea id="review-content" class="span12" cols="30" rows="5"></textarea>
																				</div>
																			</div>
																			<div class="control-group">
																				<label class="control-label" for="form-field-1">Rating</label>
																				<div class="controls">
																					<input type="text" id="review-rate" class="span12" placeholder="Rating" value="4">
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<hr>
														<table id="table-review" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<th>Review</th>
																	<th>Author</th>
																	<th><div class="text-center">Action</div></th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="widget-box" id="product-page-options">
								<div class="widget-header header-color-blue2">
									<h5 id="cf-test-btn">Page Layout Options</h5>

									<div class="widget-toolbar no-border">
										<label>Layout Mode</label>
										<label>
											<input type="checkbox" class="ace ace-switch ace-switch-3" id="cms-custom-field-container-toggler">
											<span class="lbl"></span>
										</label>
									</div>
								</div>
								<div class="widget-body">
									<!-- Advance Custom Field -->
									<?php include __DIR__ . "/tabs/custom-fields-composer.php"; ?>
								</div>
							</div>
						</div>

						<div class="span3">
							<div class="widget-box" style="width:100%;">
								<div class="widget-header header-color-blue2">
									<h4 class="lighter smaller">Save Product</h4>
								</div>

								<div class="widget-body">
									<div class="widget-main">
										<select name="status">
											<option value="publish" <?php echo ($products['status'] == 'publish' ? "selected" : ""); ?>>Publish</option>
											<option value="draft" <?php echo ($products['status'] == 'draft' ? "selected" : ""); ?>>Draft</option>
										</select>
										<br>
										<br>
										<div>
											<input type="submit" class="btn btn-small btn-success" style="width:100%;" name="submit" id="btn_save_product" onclick="addData();" value="Save" />
										</div>
									</div>     
								</div>
							</div>
							<div class="widget-box" style="width:100%;">
								<div class="widget-header header-color-blue2">
									<h4 class="lighter smaller">Language</h4>
								</div>

								<div class="widget-body">
									<div class="widget-main">	
										<select name="language" id="language">
											<?php foreach ($languages as $key => $value): ?>
												<option value="<?php echo $value->slug; ?>"><?php echo $value->value; ?></option>
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
									<h4 class="lighter smaller">Featured Image</h4>
								</div>

								<div class="widget-body">

									<div class="widget-main">
										<div id="messageAlertForProductImage">
										</div>
										<div class="no-padding center" style="width:234px;">
											<?php if($products['featured_image_url'] == '')
											$image_path = FRONTEND_URL.'/images/uploads/default.png';
											else
												$image_path = FRONTEND_URL.$products['featured_image_url'];
											?>
											<img src="<?php echo $image_path;?>" id="product_image" style="width: 200px; height: auto;" alt="" />
										</div>
										<br>
										<input type="file" id="id-input-file-3" name="image_file"  accept="image/*" onchange="changeImage(this);"/>
										<div class="edit_photo_holder">
											<button class="btn btn-info btn-small"  onclick="show_cropper(); return false;" style="width: 100%;">Edit Thumbnails</button>
										</div>

									</div>
								</div>    
							</div>

							<div class="widget-box">
								<div class="widget-header header-color-blue2">
									<h4 class="lighter smaller">Product Categories</h4>
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
						</div>                 


					</div><!--PAGE Row END-->
					<!--PAGE SPAN END-->
				</form>

			</div><!--PAGE CONTENT END-->

		</div><!--MAIN CONTENT END-->
		<div id= "content"></div>
		<div id="modal_container" class="hide">
			<div id="loading" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header no-padding">
							<div class="table-header">
								Wait for a moment updating product
							</div>
						</div>

						<div class="modal-body">
							<div class="center">
								<div class="progress">
									<div class="bar">
										
									</div >
									<div class="percent">0%</div>
								</div>
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div>
			<div id="dialog-confirm" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header no-padding">
							<div class="table-header">
								This image will be deleted permanently
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									<span class="white">×</span>
								</button>
							</div>
						</div>

						<div class="modal-body">
							<input type="hidden" id="hdn_img_id"/>
							<div id="delete_msg">
								<h5 class="red"> Are you sure to delete this image?</h5>
							</div>
							<input type="hidden" id="hidden_product_id"/>
						</div><!-- /.modal-content -->

						<div class="modal-footer no-margin-top">
							<button class="btn btn-sm btn-danger pull-right" onclick="delete_image();">
								<i class="icon-trash"></i>
								Delete
							</button>
						</div>

					</div>
				</div>
			</div><!-- #dialog-confirm -->
			<div id="dialog-add" class="modal fade">
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
							<div id="messageAlertP_Category"></div>
							<input type="text"  id="category_name" class="input-xlarge" style="width: 98%" placeholder="Category name"/>
							<p><strong>Note:</strong> If there is no category parent selected, the new category will be automatically added as a parent.</p>

							<div id="tag1">
								<div id="parent_tree" class="tree"></div>
							</div>
						</div><!-- /.modal-content -->

						<div class="modal-footer no-margin-top">
							<button class="btn btn-sm btn-info pull-right" onclick="saveCategory()">
								<i class="icon-check"></i>
								Save
							</button>
						</div>
					</div>
				</div>
			</div><!-- #dialog-confirm -->
			<div id="delete_tab" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header no-padding">
							<div class="table-header error">
								Delete Product Tab
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									<span class="white">×</span>
								</button>
							</div>
						</div>

						<input type="hidden"  id="hddn_selected_product_tab">

						<div class="modal-body">
							<h5 class="warning">Are you sure you wish to remove this tab ?</h5>       
						</div><!-- /.modal-content -->

						<div class="modal-footer no-margin-top">
							<button class="btn btn-sm btn-info " data-dismiss="modal">Cancel</button>
							<button class="btn btn-sm btn-danger pull-right" onclick="delete_product_tab()">
								<i class="icon-trash"></i>
								Delete
							</button>
						</div>
					</div>
				</div>
			</div><!-- #dialog-confirm -->
			<div class="modal fade" id="cropper-modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<div id="cropper-example-2">
								<img src="<?php echo $image_path;?>" id="modal_picture" alt="Picture">
								<input type='hidden' id='hdn_image' value="<?php echo $image_path;?>" />  
							</div>
						</div>
						<div class="modal-footer">
							<button id="btn_get_canvass" class="btn btn-info" value="featured">Save Thumbnails</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="cropper-modal-gallery">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<div id="cropper-example-2-gallery">
								<img id="modal_picture_gallery" alt="Picture">
								<input type='hidden' id='hdn_image_gallery' />  
							</div>
						</div>
						<div class="modal-footer">
							<button id="btn_get_canvass_gallery" class="btn btn-info" value="gallery">Save Thumbnails</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-product-option">
				<div class="modal-dialog">
					<div class="modal-header no-padding">
						<div class="table-header">
							Product Option
						</div>
					</div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="hide">
								<input type="hidden" id="option-id">
							</div>
							<div class="row-fluid">
								<div class="span5">
									<label for="form-field-8">Option Name</label>
								</div>
								<div class="span7">
									<label for="form-field-8">Option Values</label>
								</div>
							</div>
							<div id="modal-product-options-content"></div>
						</div>

						<hr>
						<div class="text-left">
							<button class="btn btn-default btn-small" id="modal-btn-add-option-item"><i class="icon icon-plus"></i> Add Option Item</button>
						</div>
					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-info btn-small">Close</button>
						<button id="modal-btn-add-option" class="btn btn-success btn-small"><i class="icon icon-plus"></i> Confirm</button>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-product-brand-selection">
				<div class="modal-dialog">
					<div class="modal-header no-padding">
						<div class="table-header">
							Product Brand
						</div>
					</div>
					<div class="modal-body">
						<table id="product-brand-table" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th></th>
									<th>Brand</th>
									<th>
										<label>
											<input type="checkbox" class="ace shipping" value="1,0"/>
											<span class="lbl"> Shipping</span>
										</label>
									</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-info btn-small">Close</button>
					</div>
				</div>
			</div>
			<?php include __DIR__ . "/tabs/billing-period-layout.php" ?>
		</div>

		<script id="tmpl-product-option-item" type="text/x-tmpl">
			<br>
			<div class="row-fluid product-option-item">
				<div class="span5">
					<input type="text" class="input span12 product-option-name" placeholder="Enter Product Option Name" value="${option_item}">
				</div>
				<div class="span6">
					<input type="text" name="tags" class="product-option-values span12" value="" placeholder="Enter Values..." />
				</div>
				<div class="span1">
					<button class="btn btn-danger btn-small pull-right option-remove"><i class="icon icon-trash"></i></button>
				</div>
			</div>
		</script>
		<script id="tmpl-product-option-list-item" type="text/x-tmpl">
			<div class="product-option-container-item">
				<br>
				<div class="row-fluid">
					<div class="span1 text-center">
						<label>
							<input name="form-field-checkbox" type="checkbox" class="ace option_enable">
							<span class="lbl"></span>
						</label>
					</div>
					<div class="span5">
						<label>${option_name}</label>
					</div>
					<div class="span2">
						<input type="text" class="input span12 option_sku">
					</div>
					<div class="span2">
						<input type="text" class="input span12 option_quantity">
					</div>
					<div class="span2">
						<input type="text" class="input span12 option_price">
					</div>
				</div>
			</div>
		</script>
		<script id="template-upload" type="text/x-tmpl">
			{% for (var i=0, file; file=o.files[i]; i++) { %}
			<tr class="template-upload fade">
				<td width="25%">
					<span class="preview"></span>
				</td>
				<td>
					<p class="name">{%=file.name%}</p>
					<strong class="error text-danger"></strong>
				</td>
				<td class="hide">
					<p class="size">Processing...</p>
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
				</td>
				<td>
					{% if (!i && !o.options.autoUpload) { %}
					<button  class="btn hide btn-primary start" disabled>
						<i class="glyphicon glyphicon-upload"></i>
						<span>Start</span>
					</button>
					{% } %}
					{% if (!i) { %}
					<button class="btn btn-warning cancel">
						<i class="icon-trash"></i>
					</button>
					{% } %}
				</td>
			</tr>
			{% } %}
		</script>
		<!-- The template to display files available for download -->
		<script id="template-download" type="text/x-tmpl">
			{% for (var i=0, file; file=o.files[i]; i++) { %}
			<tr class="template-download fade">
				<input type="hidden" class="hidden_gallery_image_id" value="{%=file.id%}" />
				<td width="25%">
					<span class="preview">
						{% if (file.thumbnailUrl) { %}
						<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
						{% } %}
					</span>
				</td>
				<td>
					<p class="name">
						{% if (file.url) { %}
						<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
						{% } else { %}
						<span>{%=file.name%}</span>
						{% } %}
					</p>
					{% if (file.error) { %}
					<div><span class="label label-danger">Error</span> {%=file.error%}</div>
					{% } %}
				</td>
				<td class="hide">
					<span class="size">{%=o.formatFileSize(file.size)%}</span>
				</td>
				<td>
					{% if (file.deleteUrl) { %}
					<button class="btn btn-info" onclick="edit_thumbnails({%=file.id%}); return false;">
						<i class="icon-pencil"></i>
					</button>
					<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="<?php echo URL; ?>products/manage_event_file_upload?photo_id={%=file.id%}&photo_name={%=file.name%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
						<i class="icon-trash"></i>
					</button>
					<input type="checkbox" class="hide" name="delete" value="1" class="toggle">
					{% } else { %}
					<button class="btn btn-warning cancel">
						<i class="glyphicon glyphicon-ban-circle"></i>
						<span>Cancel</span>
					</button>
					{% } %}
				</td>
			</tr>
			{% } %}
		</script>

<div class="ace-settings-container" id="product-settings-container">
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

		<div>
			<input type="checkbox" class="ace ace-checkbox-2" id="toggle-page-layout" />
			<label class="lbl" for="toggle-page-layout"> Show Page Options</label>
		</div>
	</div>
</div><!--/#ace-settings-container-->