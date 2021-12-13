
<div class="main-content">
	<div class="page-content">
        <div class="page-header">
            <h1>
            Upload
            </h1>
        </div><!-- /.page-header -->
        <input type="hidden" id="action" value="slide_slider" />

        <div id="result">
        </div>
        <div class="row-fluid">
            <div class="span12">
              <form class="form-horizontal" id="slides_form" action="<?php echo URL;?>sliders/add_slides" enctype="multipart/form-data" method="post" onsubmit="return validate_image()">
                  <input type="hidden" id="slide_id" name="slider_id" value="<?php echo $slider_id; ?>" />
                  <input type="file" multiple id="upload_slides" name="banner[]" accept="image/*"  />
                  <div  class="align-right">
                      <button class="btn btn-success ">Upload</button>
                  </div>
            
            <div>
              <div><h3 class="header smaller lighter green">Slide List</h3></div>
              <!--Banners and Photos-->
              <div id="banner_holder">
                  <!-- <div class="slide_widget">
                    <div class="well">
                        <h4 class="green smaller lighter">Normal Well</h4>
                        <table>
                            <tbody>
                                <tr><td width="15%" rowspan="5"><div style="width:100%;"><button class="btn btn-success"><i class="icon-edit align-top bigger-125"></i>Edit Slide</button></div></td><td width="25%"><ul class="ace-thumbnails"><li><a href="<?php echo FRONTEND_URL;?>/images/uploads/default.png" data-rel="colorbox"><img src="<?php echo FRONTEND_URL;?>/images/uploads/default.png" alt="234x155" style="height:155px; width:234px;" /><div class="text"><div class="inner">Click to see full image</div></div></a></li></ul></td><td width="15%"><button class="btn btn-danger"><i class="icon-trash align-top bigger-125"></i>Delete&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> <button class="btn btn-warning"><i class="icon-copy align-top bigger-125"></i>Duplicate&nbsp;&nbsp;&nbsp;</button><button class="btn btn-info"><i class="icon-copy align-top bigger-125"></i>Copy/Move</button></td><td></td></tr>
                            </tbody>
                        </table>
                      
                     </div>
                  </div> -->
              </div>
              
            </div>
        </div>
        <input type="hidden" id="hidden_well_datas" name="datas">
        </form>
        </div>
</div><!--PAGE Row END-->
</div><!--MAIN CONTENT END-->
<div id="delete" class="modal fade">
    <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header no-padding">
                    <div class="table-header">
                        Delete Slider
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <span class="white">×</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body">

                    <div id="delete_msg">
                        <h5 class="red"> Are you sure to delete this Banner/Slide?</h5>
                    </div>
                    <input type="hidden" id="hidden_slide_id"/>
                </div><!-- /.modal-content -->
                <div class="modal-footer no-margin-top">
                    <button class="btn btn-sm btn-danger pull-right" onclick="delete_slide_modal();">
                        <i class="icon-trash"></i>
                        Delete
                    </button>
                </div>
            </div><!-- /.modal-dialog -->
    </div>
</div>