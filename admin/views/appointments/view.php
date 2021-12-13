<?php 
$date_f = date_create($apps['date_from']);
$date_t = date_create($apps['date_to']);

$image = FRONTEND_URL . '/images/uploads/default.png';
if($apps['featured_image_url']!= '')
        $image = FRONTEND_URL . $apps['featured_image_url'];

?>
<div class="main-content">
                

                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                View Appointment    
                            </h1>
                        </div><!-- /.page-header -->
                        <input type="hidden" id="action" value="view">
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
                                                        <label class="control-label" for="products">Product Name:</label>
                                                            <div class="controls">
                                                              <input type="text" value="<?php echo $apps['product_name']; ?>" disabled>      
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="time_period" for="name">Name:</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-xlarge" id="name" value="<?php echo $apps['name']; ?>" disabled>       
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label"></label>
                                                            <div class="controls">

                                                                <img id="product_image" style="width:234px; height:155px; border:1px grey solid;" src="<?php echo str_replace('/images/', '/thumbnails/234x155/', $image);?>"/>          
                                                            </div>
                                                    </div>
                                                   
                                                     <div class="control-group">
                                                        <label class="control-label" for="time_period" id="time_period_label"> Trip Period</label>
                                                            <div class="controls">
                                                                <input type="text" id="date_from" value="<?php echo date_format($date_f,"d F Y"); ?>"  class="input" disabled> to <input type="text" id="date_to"  class="input date_picker" value="<?php echo date_format($date_t,"d F Y"); ?>" disabled>        
                                                            </div>
                                                    </div>
                                                     <div class="control-group">
                                                        <label class="control-label" for="full_name"> Special Request:</label>
                                                            <div class="controls">
                                                                <textarea rows="6" class="span9" id="request" disabled><?php echo $apps['special_request']; ?></textarea>           
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="email" > Email:</label>
                                                            <div class="controls">
                                                                <input type="text" id="email" class="input-xxlarge " value="<?php echo $apps['email']; ?>" disabled>           
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="contact_number"> Contact Number:</label>
                                                            <div class="controls">
                                                                <input type="text" id="contact_number"  class="input" value="<?php echo $apps['contact_no']; ?>" disabled>           
                                                            </div>
                                                    </div>
                                                    <div class="control-group">
                                                       
                                                            <div class="controls">
                                                              <input type="submit" value="Edit" id="save_orders" onclick="edit_appointments(<?php echo $apps['id']; ?>); return false;" class="btn btn-success">
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

