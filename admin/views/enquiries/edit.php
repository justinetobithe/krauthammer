
<div class="main-content">
                

                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                Edit Enquiries       
                            </h1>
                        </div><!-- /.page-header -->
                        <div class="table-responsive span9">
                            <table id="orders_details" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center" style="width:5%;">
                                            <label>
                                                <input type="checkbox" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th style="width:15%;">Product Image</th>
                                        <th>Product Name</th>
                                        <th style="width:10%;" >Quanity</th>
                                        <th style="width:11%;">Price</th>
                                    </tr>
                                </thead>
                                 <tbody>
                                    <?php 
                                    //print_r($products_ordered);
                                    if(!empty($products_ordered))
                                            foreach ($products_ordered as $key => $product) { 
                                              $image = FRONTEND_URL . '/images/uploads/default.png';
                                               if ($product['image_url']!= '')
                                                    $image = FRONTEND_URL . $product['image_url'];
                                            ?>
                                            <tr><td class="center"><label>
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label></td><td><img src="<?php echo str_replace('/images/', '/thumbnails/78x66/', $image); ?>" width="72" height="52" alt=""></td><td><?php echo $product['item_name'];?></td><td><?php echo $product['quantity']; ?></td><td><?php echo $product['price']; ?></td></tr>
    
                                    <?php   }?>
                               
                                </tbody>
                            </table>      
                        
                            <input type="hidden" id="action" value="edit_order">
                            <div class="row-fluid">

                                 <form class="form-horizontal" id="edit_order_form" action="<?php echo URL;?>orders/editOrder" enctype="multipart/form-data" method="post" onsubmit="return false;">
                                    <input type="hidden" value = "<?php echo $order['id'];?>" id="hidden_id" name="id"/>
                                    &nbsp;
                                    <div class="widget-box">
                                                <div class="widget-header header-color-blue">
                                                    <h5 class="bigger lighter">
                
                                                        Order Details
                                                    </h5>

                                                  
                                                </div>

                                                <div class="widget-body">

                                                    <div class="widget-main">
                                                        <div id="alertOrder"></div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="order_name">First Name:</label>
                                                                <div class="controls">
                                                                    <input type="text" id="order_name" name="name" class="input-xxlarge" value="<?php echo $order['first_name']; ?>">           
                                                                </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="order_name">Last Name:</label>
                                                                <div class="controls">
                                                                    <input type="text" id="order_last_name" name="name" class="input-xxlarge" value="<?php echo $order['last_name']; ?>">           
                                                                </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="order_email">Email:</label>
                                                                <div class="controls">
                                                                    <input type="text" id="order_email" name="email" class="input-xxlarge" value="<?php echo $order['email'];?>">           
                                                                </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="order_contact_number">Contact Number:</label>
                                                                <div class="controls">
                                                                    <input type="text" id="order_contact_number" name="contact_num" class="input-xxlarge" value="<?php echo $order['phone'];?>">           
                                                                </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="order_message">Message:</label>
                                                                <div class="controls">
                                                                   <textarea id="order_message" rows="8" name="message" style="width:77%;"><?php echo $order['message'];?></textarea>     
                                                                </div>
                                                        </div>
                                                        
                                                        <?php if (isset( $cf ) && count($cf)): ?>
                                                        <h3 class="text-center">Other Fields</h3>
                                                        <div id="other-fields">
                                                        <?php foreach ($cf as $key => $value): ?>
                                                        <?php echo $value; ?>
                                                        <?php endforeach ?>
                                                        </div>
                                                        <?php endif ?>

                                                        <hr>
                                                        <div class="control-group">
                                                           
                                                                <div class="controls">
                                                                  <input type="submit" value="Update" id="save_orders" class="btn btn-success">
                                                                  &nbsp;
                                                                  <a href="<?php echo URL . "enquiries/view/{$order['id']}" ?>" class="btn btn-primary">Cancel</a>
                                                                </div>
                                                        </div>   
                                                    </div>

                                                </div>
                                    </div>
                                    
                                       
                                  
                        

                                </form>
                            </div><!--PAGE Row END-->
                        </div>
                    </div><!--PAGE CONTENT END-->
</div><!--MAIN CONTENT END-->
<!--MODAL-->
<div id="loading" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Wait for a moment updating orders
                </div>
            </div>

            <div class="modal-body">

                
                <div class="center">
                    <i id="product_loading" class="icon-spinner icon-spin blue bigger-300 hide"></i>
                </div>
                <div id="loading_msg_success" class="center hide">
                    <h4 class="green">Order Successfully Updated</h4>
                </div>
                <div id="loading_msg_error" class="center hide">
                    <h4 class="red">Order Unsuccessfully Updated</h4>
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
