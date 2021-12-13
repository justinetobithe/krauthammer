<?php $system_type = check_system('system_type');?>
<div class="main-content">
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                Edit EShop Product       
                            </h1>
                        </div><!-- /.page-header -->
                        <input type="hidden" id="action" value="edit_product">
                        <input type="hidden" id="selected_categories" value="<?php echo $selected_categories;?>">
                        <input type="hidden" id="hdn_featured" value="<?php echo $products['featured_product'];?>">
                        <input type="hidden" id="hdn_recommended" value="<?php echo $products['recommended_for_checkout'];?>">
                        <div class="row-fluid">
                            
                            <form class="form-horizontal" id="add_product_form" action="<?php echo URL;?>eproducts/updateProduct" enctype="multipart/form-data" method="post" onsubmit="return validateForm()">
                           
                            <div class="span8">
                                <div class="widget-box" style="margin-bottom:10px; border:none;">
                                    <div id="alertProductName"></div>
                                    <input type="hidden" name="product_id" id="product_id" value="<?php echo $products['id'];?>">
                                    <input type="hidden" id="old_slug" name="old_slug" value="<?php echo $products['url_slug']; ?>" />
                                    <input type="text" name ="product_name" class="input-xxlarge" id="txt_product_name"  style="width:98%;" value="<?php echo $products['product_name'];?>">
                                    <p>Permalink: <a target="_blank" id="permalink" href="<?php echo FRONTEND_URL."/products/".$products['url_slug'];?>/"><?php echo FRONTEND_URL."/products/".$products['url_slug'];?>/</a></p>
                                    <div style="margin-bottom:5px;">
                                        <input type="text" name ="url_slug" class="input-large" id="txt_url_slug" placeholder = "URL Slug" value="<?php echo $products['url_slug'];?>">
                                        <input type="hidden" value="<?php echo $products['url_slug'];?>" id="hidden_slug"/>
                                    </div>
                                </div><!-- PAGE CONTENT BEGINS -->

                                <div class="widget-box">
                                     <div class="widget-body">
                                        <input type="hidden" id="hidden_product_description" name="product_description" />
                                        <textarea id="product_description" style="width:100%;"><?php echo $products['product_description'];?></textarea>
                                     </div>
                                </div>
                                
                                <div class="tabbable tabs-left" >
                                            <ul class="nav nav-tabs" id="myTab3">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#home3">
                                                        <i class="green icon-barcode bigger-110"></i>
                                                        Details
                                                    </a>
                                                </li>
                                                <!-- <li>
                                                    <a data-toggle="tab" href="#product_attributes">
                                                        <i class="green icon-bar-chart bigger-110"></i>
                                                        Attributes
                                                    </a>
                                                </li> -->
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
                                             
                                            </ul>

                                            <div class="tab-content">
                                                <div id="home3" class="tab-pane in active" style="overflow-x: hidden;">
                                                    <div>
                                                         <div id='messageAlertDetails'></div>
                                                           
                                                             <div class="hr"></div>
                                                                
                                                                <div class="control-group">
                                                                    <label class="control-label" for="featured_product">Featured Product?:</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                <select name="featured_product" id="featured_product">
                                                                                    <option value="no">No</option>
                                                                                    <option value="yes">Yes</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="control-group">
                                                                    <label class="control-label" for="recommended_checkout">Recommended for Checkout:</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                 <select name="recommended_checkout" id="recommended_checkout">
                                                                                    <option value="NO">No</option>
                                                                                    <option value="YES">Yes</option>
                                                                                </select>
                                                                                <p class="grey">If you set this option to yes, the customer will be recommended to buy this product before they proceed to checkout page.</p>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="control-group">
                                                                    <label class="control-label" for="txt_price">Price:</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                <span class="input-icon input-icon-left">
                                                                                    <input type="text" id="txt_price" class="input-xlarge" name="product_price" value="<?php if($products['price'] > 0 && $products['price'] != 0.00){echo $products['price'];} ?>">
                                                                                    <i class="icon-dollar green"></i>
                                                                                </span>
                                                                                <p class="grey">if no input, price will not appear on the front-end (Catalogue)</p>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="control-group">
                                                                    <label class="control-label" for="txt_sku">SKU:</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                    <input type="text" id="txt_sku" class="input-xlarge" name="product_sku" value="<?php echo $products['sku'];?>"/>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="control-group">
                                                                    <label class="control-label" for="txt_quantity">Quantity (0 =  Out of Stock):</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                    <input type="text" id="txt_quantity" class="input-xlarge" name="product_qty" value="<?php if($products['quantity'] > 0){echo $products['quantity'];} ?>"/>
                                                                                    <p class="grey">if no input, means no recording of quantity needed.</p>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <?php if($system_type['option_value'] == 'ECOMMERCE'){ ?>
                                                                <div class="control-group">
                                                                    <label class="control-label" for="txt_quantity">Track Inventory:</label>
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
                                                                    <label class="control-label" for="txt_out_of_stock">Out of Stock Message:</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                    <input type="text" id="txt_out_of_stock" class="input-xlarge" name="product_stock" value="<?php echo $products['out_of_stock_status'];?>"/>
                                                                            </div>
                                                                        </div>
                                                                </div> 
                                                                <div class="control-group">
                                                                    <label class="control-label" for="txt_min_order_qty">Min. Order Qty:</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                    <input type="text" id="txt_min_order_qty" class="input-xlarge" name="product_min_order_qty" value="<?php if($products['min_order_qty'] > 0){echo $products['min_order_qty'];} ?>"/>
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
                                                        <h3 class="lighter block green"> Product Images </h3>
                                                         <div id="fileupload" action="<?php echo URL; ?>products/upload_gallery" method="POST" enctype="multipart/form-data" class="">
                                                           
                                                            <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
                                                            <input type="hidden" id="hidden_image_name" name="image_name"/>
                                                            <input type="hidden" id="hidden_product_id_for_gallery" name="product_id_for_gallery" value="<?php echo $products['id']; ?>">
                                                            <div class="span1">
                                                            </div>
                                                            <div class="row fileupload-buttonbar">
                                                                <div class="col-lg-7">
                                                                  
                                                                    <span class="btn btn-success fileinput-button">
                                                                        <i class="glyphicon glyphicon-plus"></i>
                                                                        <span>Add Photos</span>
                                                                        <input multiple="multiple" type="file" name="files[]" accept="image/*" id="add_images_input"/>
                                                                        <input type="hidden" id="product_id" name="image_name" value="<?php echo $products['id']; ?>"/>
                                                                    </span>
                                                                    <button type="submit" class="btn btn-primary start hide" id="start_aawwad">
                                                                        <i class="glyphicon glyphicon-upload"></i>
                                                                        <span>Start upload</span>
                                                                    </button>
                                                                  
                                                                </div>
                                                               
                                                                <div class="col-lg-5 fileupload-progress fade">
                                                                   
                                                                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                                                    </div>
                                                                   
                                                                    <div class="progress-extended">&nbsp;</div>
                                                                </div>
                                                            </div>
                                                            
                                                            <table role="presentation" class="table table-striped" id="table_to_be_clone"><tbody class="files"></tbody></table>
                                                        </div> 
                                                        <!-- <input multiple="" type="file" name="gallery_images[]" accept="image/*" id="id-input-file-2" /> 
                                                            <ul class="ace-thumbnails">
                                                            
                                                            </ul> -->
                                                        
                                                    </div>
                                                </div>

                                                <div id="profile4" class="tab-pane">
                                                    <input type="hidden" name="product_tabs" id="hdn_products_tab">
                                                    <div class="span11">

                                                            <div class="control-group">
                                                                 <button class="btn btn-info" id="product_tab_add_button" onclick="return false;">Add Product Tab</button>  
                                                            </div>
                                                            <div class="widgets_tabs">
                                                            <?php 
                                                            $index=0;
                                                          
                                                            if(empty($product_tabs)){
                                                            ?>
  
                                                                <div class="widget-box product_tabs"><div class="widget-header"><div><input type="text" class="input-xlarge title" placeholder="Tab Title"><div class="widget-toolbar"><a href="#" data-action="" onclick="collapse_tab(0); return false;" style="color: #C7C5D1;"><i class="icon-chevron-up collapse"></i></a></div></div> <input type="hidden" class="id_for_collapse" value=0>  </div><div class="widget-body"><div class="widget-main"><textarea class="textarea_product_tabs" id="tab_desc0"></textarea></div></div></div>
                                                         
                                                            <?php
                                                            }
                                                            else{
                                                            foreach ($product_tabs as $key => $tab) {
                                                               $index =  $key;
                                                            ?>
                                                                <div class="widget-box product_tabs" id="widget_<?php echo $tab['id'];?>"><div class="widget-header"><h5><input type="text" class="input-xlarge title" placeholder="Tab Title" value="<?php echo $tab['tab_title'];?>"></h5><div class="widget-toolbar"><input type="hidden" class="id_for_collapse" value=<?php echo $key; ?>><a href="#" data-action="" onclick="collapse_tab(<?php echo $key; ?>); return false;" style="color: #C7C5D1;"><i class="icon-chevron-up collapse"></i></a><a href="#" data-action="" style="color: #F79B94; "><i class="icon-remove" onclick="delete_tab(<?php echo $tab['id']; ?>);"></i></a></div></div><div class="widget-body"><div class="widget-main"><textarea class= "textarea_product_tabs" id="tab_desc<?php echo $key;?>"><?php echo $tab['tab_content'];?></textarea></div></div></div>
                                                           
                                                            <?php } 
                                                             }
                                                            ?>
                                                             </div>
                                                            <input type="hidden" id="hdn_index_tab" value="<?php echo $index; ?>">
                                                        
                                                    </div>
                                                </div>

                                                <div id="appointments" class="tab-pane">
                                                    <input type="hidden" id="hidden_product_appointments" name="hidden_product_appointments"/>
                                                    <button class="btn btn-info" onclick="add_new_appointments(); return false;">Add New Trip Period</button>
                                                    </br>
                                                    </br>
                                                    <div class="row-fluid">
                                                        <div class="accordion-style2" id="accordion_products_appointments">
                                                        <?php
                                                         if(!empty($appointments))
                                                             foreach ($appointments as $key => $app) {
                                                            ?>          
                                                            <div id="accordion" class="group appointments">
                                                                <h3 class="accordion-header-new">Trip Period</h3>
                                                                <div>
                                                                    <span>Date From: </span><input type="text" class="input-small datepicker" value="<?php echo $app['date_from']; ?>">
                                                                    <span> Date To: </span><input type="text" class="input-small datepicker_to" value="<?php echo $app['date_to']; ?>">
                                                                    <span> Spots: </span><input type="text" class="input-small spot" value="<?php echo $app['spot']; ?>">
                                                                    <button class="btn btn-mini btn-danger pull-right" onclick="delete_appointments(this); return false;"><i class="icon-trash bigger-120"></i></button>
                                                                </div>
                                                            </div>
                                                            <?php }else{ ?>
                                                                <div id="accordion" class="group appointments">
                                                                <h3 class="accordion-header-new">Trip Period</h3>
                                                                <div>
                                                                    <span>Date From: </span><input type="text" class="input-small datepicker">
                                                                    <span> Date To: </span><input type="text" class="input-small datepicker_to">
                                                                    <span> Spots: </span><input type="text" class="input-small spot">
                                                                    <button class="btn btn-mini btn-danger pull-right" onclick="delete_appointments(this); return false;"><i class="icon-trash bigger-120"></i></button>
                                                                </div>
                                                            </div>
                                                           <?php } ?>
                                                       </div>
                                                   </div>
                                                </div>

                                                <div id="seo_settings" class="tab-pane" style="overflow-x: hidden;">
                                                    <div>
                                                         <div id='alert_seo_settings'></div>
                                                           
                                                             <div class="hr"></div>
                                                            
                                                                <div class="control-group">
                                                                    <label class="control-label" for="seo_title">Title:</label>
                                                                        <div class="controls">
                                                                            <div class="span11 input-group">
                                                                                    <input type="text" id="seo_title" class="input-xlarge" name="seo_title" style="width: 100%;" value="<?php echo $products['seo_title']; ?>"/>
                                                                                    <br>
                                                                                    <p><strong id="title_char">0</strong> characters. Most search engines use a maximum of 60 chars for the title.</p>
                                                                            </div>
                                                                        </div>
                                                                </div>

                                                                 <div class="control-group">
                                                                    <label class="control-label" for="seo_description">Description:</label>
                                                                        <div class="controls">
                                                                            <div class="span11 input-group">
                                                                                    <textarea id="seo_description" name="seo_description" style="width:100%;" rows="6"><?php echo $products['seo_description']; ?></textarea>
                                                                                    <br>
                                                                                    <p><strong id="desc_char">0</strong> characters. Most search engines use a maximum 1 of 60 chars for the description.</p>
                                                                            </div>
                                                                        </div>
                                                                 </div>

                                                                 <div class="control-group">
                                                                    <label class="control-label" for="seo_no_index">Robots Meta NOINDEX:</label>
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
                                                <div id="additional_files" class="tab-pane">
                                                    <div>
                                                        <div id='alert_seo_settings'></div>
                                                           
                                                        <div class="hr"></div>
                                                        <input multiple="multiple" type="file" name="additional_files_input[]" id="additional_files_input" />
                                                        <h3 class="lighter block green"> Additional Files </h3>
                                                        <ul id="tasks" class="item-list">
                                                            <?php foreach ($additional_files as $key => $files) {
                                                                # code...
                                                            ?>     
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

                            <div class="span3">
                                <div class="widget-box" style="width:100%;">
                                        <div class="widget-header header-color-blue2">
                                            <h4>Save Product</h4>
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
                                                    <input type="submit" class="btn btn-small btn-success" style="width:47%;" name="submit" id="btn_save_product" onclick="addData();" value="Save" />
                                                </div>
                                            </div>     
                                        </div>

                                   </div>
                                <!-- <div>
                                    <input type="submit" class="btn btn-small btn-success" style="width:47%;" name="submit" id="btn_save_product" onclick = "addData();" value="Save" />
                                    &nbsp;
                                    <input type="submit" class="btn btn-small" style="width:47%;" onclick = "addData();" name="submit" id="btn_draft_product" value="Draft" />   
                                </div> -->
                                
                                <div class="widget-box">
                                    <div class="widget-header header-color-blue2">
                                        <h4>Featured Image</h4>
                                    </div>

                                    <div class="widget-body">
                                        
                                        <div class="widget-main">
                                            <div id="messageAlertForProductImage">
                                            </div>
                                            <input type="file" id="id-input-file-3" name="image_file"  accept="image/*" onchange="changeImage(this);"/>
                
                                                 <div class="no-padding center" style="width:234px; height: 155px;">
                                                    <?php if($products['featured_image_url'] == '')
                                                            $image_path = FRONTEND_URL.'/images/uploads/default.png';
                                                           else
                                                            $image_path = FRONTEND_URL.$products['featured_image_url'];
                                                    ?>
                                                     <img src="<?php echo str_replace('/images/', '/thumbnails/176x167/', $image_path);?>" id="product_image" style="width: 234px; height: 155px;" alt="" />
                                                 </div>
                                                 <br>
                                                 <div class="edit_photo_holder">
                                                    <button class="btn btn-info btn-small"  onclick="show_cropper(); return false;">Edit Thumbnails</button>
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

                            </form>
                            
                            <!--PAGE SPAN END-->
                        </div><!--PAGE Row END-->

                    </div><!--PAGE CONTENT END-->
                    
</div><!--MAIN CONTENT END-->
<div id= "content">
</div>
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
                        <div class="bar"></div >
                        <div class="percent">0%</div >
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
                    <div id="parent_tree" class="tree">
                    </div>
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
                <button class="btn btn-sm btn-info " data-dismiss="modal">Cancel</buttton>
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
</div>
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