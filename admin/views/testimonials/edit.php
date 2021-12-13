
<div class="main-content">
    <div class="page-content">

        <div class="page-header">
            <h1>
               Edit Testimonials
            </h1>
        </div><!-- /.page-header -->

        <input type="hidden" id="action" value="edit">

        <div class="row-fluid">

        <form class="form-horizontal" id="product_category_form" action="<?php echo URL;?>testimonials/update_testimonial" enctype="multipart/form-data" method="post" onsubmit="return validate_form()">
               
                <div class="widget-box span9">
                            <input type="hidden" name="id" id="hdn_id" value="<?php echo $id; ?>" >
                            <div class="widget-header header-color-blue">
                                <h5 class="bigger lighter">
                                   
                                    Testimonial Details
                                </h5>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main">

                                    <div id="alertProductCategory">
                                    </div>

                                        <div class="control-group">
                                        <label class="control-label" for="image">Image:</label>
                                            <div class="controls span5">
                                                <input type="file" id="image" name="image" accept="image/*" onchange='change_view_image(this);'>
                                                <input type='hidden' id='hdn_image' />   
                                                        
                                                <img id="image_view" src="<?php echo FRONTEND_URL;?>/images/uploads/default.png" height="200" width="200" alt=""/>
                                               
                                            </div>
                                    </div>


                                    <div class="control-group">
                                        <label class="control-label" for="name">Name:</label>
                                            <div class="controls">
                                                <input type="text" id="name" name="name" class="input-xxlarge" />           
                                            </div>
                                    </div>

                                

                                    <div class="control-group">
                                        <label class="control-label" for="description_tiny">Testimonial:</label>
                                            <div class="controls">
                                               <textarea id="description_tiny"> </textarea>     
                                            </div>
                                            <input type="hidden" name="description" id="description">
                                    </div>

                                    <div class="control-group">
                                       
                                            <div class="controls">
                                              <input type="submit" value="Save" class="btn btn-info" name="submit">
                                              &nbsp;
                                              <input type="submit" value="Save and Add New" id="save_orders" class="btn btn-success" name="submit">
                                              &nbsp;
                                               <input type="submit" value= "Cancel" class="btn btn-danger" onclick="reset_form(); return false;" />
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
<div id="loads" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
             
                </div>
            </div>

            <div class="modal-body">
                <div style="text-align:center;">
                    <i class="icon-spinner icon-spin blue bigger-300 hide"></i>
                    Please wait for a moment..
                </div>
            </div>
        </div>
   </div>
</div>