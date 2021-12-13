<?php $system_type = check_system('system_type'); ?>
<div class="main-content">
    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                Add EShop Product       
                            </h1>
                        </div><!-- /.page-header -->
                        <input type="hidden" id="action" value="add_product">
                        <div class="row-fluid">
                            <form class="form-horizontal" id="add_product_form" action="<?php echo URL;?>eproducts/addProduct" enctype="multipart/form-data" method="post" onsubmit="return validateForm()">
                            
                            <div class="span8">
                                <div class="widget-box" style="margin-bottom:10px;  border:none;">
                                    <div id="alertProductName"></div>
                                    <input type="text" name ="product_name" class="input-xxlarge" id="txt_product_name"  style="width:98%;" placeholder = "Enter Product Name Here">
                                    <p>Permalink: <a id="permalink"></a></p>
                                    <div style="margin-bottom:5px;">
                                        <input type="text" name ="url_slug" class="input-large" id="txt_url_slug" placeholder = "URL Slug">
                                    </div>
                                </div><!-- PAGE CONTENT BEGINS -->

                                <div class="widget-box">
                                     <div class="widget-body">
                                        <input type="hidden" id="hidden_product_description" name="product_description" />
                                        <textarea id="product_description" style="width:100%"></textarea>
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
                                                                                    <input type="text" id="txt_price" class="input-xlarge" placeholder="0" name="product_price">
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
                                                                                    <input type="text" id="txt_sku" class="input-xlarge" name="product_sku"/>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="control-group">
                                                                    <label class="control-label" for="txt_quantity">Quantity (0 =  Out of Stock):</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                    <input type="text" id="txt_quantity" class="input-xlarge" name="product_qty"/>
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
                                                                                <input type="hidden" name="track_inventory" id="track_inventory"/>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <?php } ?>
                                                                <div class="control-group">
                                                                    <label class="control-label" for="txt_out_of_stock">Out of Stock Message:</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                    <input type="text" id="txt_out_of_stock" class="input-xlarge" value="Out of Stock" name="product_stock"/>
                                                                            </div>
                                                                        </div>
                                                                </div>    
                                                                <div class="control-group">
                                                                    <label class="control-label" for="txt_min_order_qty">Min. Order Qty:</label>
                                                                        <div class="controls">
                                                                            <div class="span7 input-group">
                                                                                    <input type="text" id="txt_min_order_qty" class="input-xlarge" name="product_min_order_qty"/>
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
                                                         
                                                    <div id="accordion_products_attributes" class="accordion-style2 ">
                                                        <div id="accordion" class="group accordion_group products_attr">
                                                            <h3 class="accordion-header"><input type="text" class="input span8 textbox attr_label"> <div class="pull-right"><span onclick="delete_div">x</span></div></h3>

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
                                                </div>

                                                <div id="profile3" class="tab-pane">
                                                    <div class="row-fluid"> 
                                                       <!-- // <button class="btn btn-success" id="add_gallery_photos">Add Photos</button> -->
                                                         <div id="fileupload" action="<?php echo URL; ?>products/upload_gallery" method="POST" enctype="multipart/form-data" class="">
                                                           
                                                           
                                                            <input type="hidden" id="hidden_image_name" name="image_name"/>
                                                            <input type="hidden" id="hidden_product_id_for_gallery" name="product_id_for_gallery">
                                                            <div class="span1">
                                                            </div>
                                                            <div class="row fileupload-buttonbar">
                                                                <div class="col-lg-7">
                                                                  
                                                                    <span class="btn btn-success fileinput-button">
                                                                        <i class="glyphicon glyphicon-plus"></i>
                                                                        <span>Add Photos</span>
                                                                        <input multiple="multiple" type="file" name="files[]" accept="image/*" id="add_images_input"/>
                                                                        <input type="hidden" id="product_id" name="image_name" value="0"/>
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
                                                        <!--  <input multiple="multiple" type="file" name="gallery_images[]" accept="image/*" id="id-input-file-2" /> -->
                                
                                                    </div>
                                                </div>

                                                <div id="profile4" class="tab-pane">
                                                    <input type="hidden" name="product_tabs" id="hdn_products_tab">
                                                    <div class="span11">
                                                            <div class="control-group">
                                                                 <button class="btn btn-info" id="product_tab_add_button" onclick="return false;">Add Product Tab</button>  
                                                            </div>
                                                         
                                                            <div class="widgets_tabs">
                                                                <div class="widget-box product_tabs"><div class="widget-header"><div><input type="text" class="input-xlarge title" placeholder="Tab Title"><div class="widget-toolbar"><a href="#" data-action="" onclick="collapse_tab(0); return false;" style="color: #C7C5D1;"><i class="icon-chevron-up collapse"></i></a></div></div> <input type="hidden" class="id_for_collapse" value=0>  </div><div class="widget-body"><div class="widget-main"><textarea class="textarea_product_tabs" id="tab_desc0"></textarea></div></div></div>
                                                             <input type="hidden" id="hdn_index_tab" value=0>
                                                            </div>
                                                            
                                                    </div>
                                                    
                                                </div>
                                                <div id="appointments" class="tab-pane">
                                                    <input type="hidden" id="hidden_product_appointments" name="hidden_product_appointments"/>
                                                    <button class="btn btn-info" onclick="add_new_appointments(); return false;">Add New Trip Period</button>
                                                    </br>
                                                    </br>
                                                    <div class="row-fluid">
                                                        <div class="accordion-style2" id="accordion_products_appointments">          
                                                            <div id="accordion" class="group appointments">
                                                                <h3 class="accordion-header-new">Trip Period</h3>
                                                                <div>
                                                                    <span>Date From: </span><input type="text" class="input-small datepicker">
                                                                    <span> Date To: </span><input type="text" class="input-small datepicker_to" disabled>
                                                                    <span> Spots: </span><input type="text" class="input-small spot">
                                                                    <button class="btn btn-mini btn-danger pull-right" onclick="delete_appointments(this); return false;"><i class="icon-trash bigger-120"></i></button>
                                                                </div>
                                                            </div>
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
                                                                                    <input type="text" id="seo_title" class="input-xlarge" name="seo_title" style="width: 100%;"/>
                                                                                    <br>
                                                                                    <p><strong id="title_char">0</strong> characters. Most search engines use a maximum of 60 chars for the title.</p>
                                                                            </div>
                                                                        </div>
                                                                </div>

                                                                 <div class="control-group">
                                                                    <label class="control-label" for="seo_description">Description:</label>
                                                                        <div class="controls">
                                                                            <div class="span11 input-group">
                                                                                    <textarea id="seo_description" name="seo_description" style="width:100%;" rows="6"></textarea>
                                                                                    <br>
                                                                                    <p><strong id="desc_char">0</strong> characters. Most search engines use a maximum 1of 60 chars for the description.</p>
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
                                                                                    <input type="hidden" id="hdn_no_index"  name="seo_no_index">
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
                                                    </br>
                                                    
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
                                    <!-- <div>
                                        <input type="submit" class="btn btn-small btn-success" style="width:47%;" name="submit" id="btn_save_product" onclick="addData();" value="Save" />
                                         &nbsp;
                                        <input type="submit" class="btn btn-small" style="width:47%;" onclick = "addData(); return false;" name="submit" id="btn_draft_product" value="Draft" />                              
                                    </div> -->
                                    <div class="widget-box" style="width:100%;">
                                        <div class="widget-header header-color-blue2">
                                            <h4>Featured Image</h4>
                                        </div>

                                        <div class="widget-body">
                                            
                                            <div class="widget-main">
                                                <div id="messageAlertForProductImage">
                                                </div>
                                                <input type="file" id="id-input-file-3" name="image_file"  accept="image/*" onchange="changeImage(this);"/>
                    
                                                     <div class="no-padding" style="width: 234px; height: 155px;">
                                                         <img src="<?php echo FRONTEND_URL;?>/thumbnails/200x120/uploads/default.png" id="product_image" alt="" style="width: 234px; height: 155px;"/>
                                                     </div>
                                                     Link: <a href="#" id="link_image" target="_blank"></a>
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
                    
                        </div><!--ROW END-->
                        <!-- <form id="fileupload" action="<?php echo URL; ?>products/upload_gallery" method="POST" enctype="multipart/form-data" class="">
                                                           
                            <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
                            <input type="hidden" id="hidden_image_name" name="image_name"/>
                            <div class="span1">
                            </div>
                            <div class="row fileupload-buttonbar">
                                <div class="col-lg-7">
                                  
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Add Photos</span>
                                        <input multiple="multiple" type="file" name="files[]" accept="image/*" id="add_images_input"/>
                                    </span>
                                    <button type="submit" class="btn btn-primary start" id="start_aawwad">
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
                        </form>  -->
    </div><!-- /.page-content -->
</div>
<div id= "content">

</div>
                         
                            <!--PAGE SPAN END-->
<div id="modal_container" class="hide">
<div id="delete_appointments" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Delete Time Period
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>
            
            <div class="modal-body">
               <p>Are you sure to delete this time Period?</p>
            </div><!-- /.modal-content -->
            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm btn-danger pull-right" onclick="delete_appointment(); return false;">
                    <i class="icon-trash"></i>
                    Delete
                </button>
            </div>
               
        </div>
    </div>
</div>                      
<div id="loading" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header no-padding">
                            <div class="table-header">
                                Wait for a moment adding product
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
</div><!--MAIN CONTENT END-->
<div id="dialog-add" class="modal fade modal-xlarge">
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
                 <div id="messageAlertP_Category">
                 </div>
                
                <input type="text"  id="category_name" class="input-xlarge" style="width: 98%" placeholder="Category name" />
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
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
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