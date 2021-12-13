<?php
/*
Title: Gallery
Order: 2
ID: gallery
*/ 
?>
<div id="gallery" class="tab-pane" style="overflow-x: hidden; min-height: 350px;">
    <div class="gallery-container">
        <button class="btn btn-primary btn-small" id="btn_add_new_album">Add New Album</button>
    </div>

    <hr>

    <div id="accordion-gallery" class="accordion"></div>
</div>

<script id="template-gallery-album" type="text/x-tmpl">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a href="#collapseOne" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle">${album_title}</a>
        </div>
        <div class="accordion-body in collapse" id="collapseOne" style="height: auto;">
            <div class="accordion-inner">
                <div class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label control-label-sidebar">Album Name:</label> 
                        <div class="controls">
                            <input type="text" class="input-xlarge album_name" value="${album_title}">
                        </div>
                    </div>
                </div>

                <div class="album-uploader-container">
                    <input type="file" multiple="" class="upload_images" name="album[]" accept="image/*">
                </div>
                <hr>

                <table data-table="6" role="presentation" class="table table-striped" id="table_to_be_clone">
                    <tbody class="files ui-sortable">

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</script>
<script id="template-gallery-album-item" type="text/x-tmpl">
    <tr data-tr="54" class="fade in row-54" style="background-color: rgb(253, 253, 253);">
        <td width="25%">
            <img src="http://www.myhairdobar.com.sg/images/uploads/2016/09/22/galleries/57e347405bb0b3.jpg/57e347405bb0b3.jpg" alt="" width="80" height="80">
        </td>
        <td>
            <p class="name">3.jpg</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <button class="btn btn-danger" onclick="gallery_delete(54); return false;">
                <i class="icon-trash"></i>
            </button>
        </td>
    </tr>
</script>
<script id="template-gallery-album-extra" type="text/x-tmpl">
    <div data-gid="6" class="accordion-group group-update group group-0" data-id="9"> 
        <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" role="tab" id="ui-accordion-accordion-gallery-header-0" aria-controls="ui-accordion-accordion-gallery-panel-0" aria-selected="false" tabindex="0">
            <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
            <span class="album-title">Hair</span>   
            <div class="pull-right remove-menu-parent" data-rel="tooltip" data-placement="top" title="Remove" style="position:relative;top:-3px;">
                <div class="ui-pg-div">
                    <span class="ui-icon icon-remove red"></span>
                </div>
            </div>
        </h3>
        <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" id="ui-accordion-accordion-gallery-panel-0" aria-labelledby="ui-accordion-accordion-gallery-header-0" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">
            <div class="form-horizontal">
                <div class="control-group">
                    <label class="control-label control-label-sidebar">Album Category Name:</label> 
                    <div class="controls">
                        <input type="text" class="input-xlarge album_name" value="Hair">
                    </div>
                </div>
            </div> 
            <hr> 

            <div class="ace-file-input ace-file-multiple">
                <input type="file" multiple="" class="upload_images" name="album6[]" accept="image/*">
                <label data-title="Drop Images here or click to choose">
                    <span data-title="No File ..."><i class="icon-cloud-upload"></i></span>
                </label>
                <a class="remove" href="#"><i class="icon-remove"></i></a>
            </div> 
            <hr>

            <table data-table="6" role="presentation" class="table table-striped" id="table_to_be_clone">
                <tbody class="files ui-sortable">
                    <tr data-tr="54" class="fade in row-54" style="background-color: rgb(253, 253, 253);">
                        <td width="25%">
                            <img src="http://www.myhairdobar.com.sg/images/uploads/2016/09/22/galleries/57e347405bb0b3.jpg/57e347405bb0b3.jpg" alt="" width="80" height="80">
                        </td>
                        <td>
                            <p class="name">3.jpg</p>
                            <strong class="error text-danger"></strong>
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="gallery_delete(54); return false;">
                                <i class="icon-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div> 
    </div>
</script>