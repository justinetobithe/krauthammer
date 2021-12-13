
<div class="main-content">
                

                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                Edit Appointment      
                            </h1>
                        </div><!-- /.page-header -->
                        <input type="hidden" id="action" value="edit">
                        <input type="hidden" id="hidden_id" value="<?php echo $id; ?>">
                        <input type="hidden" id="hidden_apps_id" value="<?php echo $apps['product_appointment_id']; ?>">
                        <div class="row-fluid">

                             <form class="form-horizontal" action="<?php echo URL;?>" enctype="multipart/form-data" method="post">
                        
                                <div class="widget-box span9">
                                            <div class="widget-header header-color-blue">
                                                <h5 class="bigger lighter">
                                                   
                                                    Appointment Details
                                                </h5>
	
                                              
                                            </div>

                                            <div class="widget-body">

                                                <div class="widget-main">
                                                    <div id="alert_appointments"></div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="products">Product:</label>
                                                            <div class="controls">
                                                               <select id="products" class="form_select">
                                                               		<?php if(!empty($products)){ 
                                                               				foreach ($products as $key => $product) { ?>
                                                               		<option value="<?php echo $product['id']; ?>"><?php echo $product['product_name']; ?></option>
                                                               		<?php  }
                                                               		}else{ ?>
																	<option>There are no Products Added.</option>
                                                               		<?php } ?>
                                                               </select>           
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="time_period" for="name">Name:</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-xlarge" id="name">       
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label"></label>
                                                            <div class="controls">
                                                                <img id="product_image" style="width:234px; height:155px; border:1px grey solid;"/>          
                                                            </div>
                                                    </div>
                                                    
                                                    <div class="control-group">
                                                        <label class="control-label" for="trip_period">Trip Period</label>
                                                            <div class="controls" id="trip_period_div">
                                                                <select id="trip_period" class="input span6">
                   
                                                                </select> 
                                                                 <span id="spots">0</span> Spots Left      
                                                            </div>
                                                            <div class="controls hide" id="trip_period_warning">
                                                                <span>Please Add Trip Period for this Product in Product Module.</span>
                                                            </div>

                                                    </div>
                                                     <div class="control-group">
                                                        <label class="control-label" for="full_name"> Special Request:</label>
                                                            <div class="controls">
                                                                <textarea rows="6" class="span9" id="request"></textarea>           
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="email"> Email:</label>
                                                            <div class="controls">
                                                                <input type="text" id="email" class="input-xxlarge " >           
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="contact_number"> Contact Number:</label>
                                                            <div class="controls">
                                                                <input type="text" id="contact_number"  class="input" >           
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                       
                                                            <div class="controls">
                                                              <input type="submit" value="Update" id="save_orders" onclick="save_appointment(); return false;" class="btn btn-success">
                                                              &nbsp;
                                                              <button class="btn" id="btn_reset" onclick="back_to_appointments(); return false;">Cancel</button>
                                                            </div>
                                                    </div>   
                                                </div>

                                            </div>
                                </div>

                            </form>
                        </div><!--PAGE Row END-->
                    </div><!--PAGE CONTENT END-->
</div><!--MAIN CONTENT END-->
<!--MODAL-->

