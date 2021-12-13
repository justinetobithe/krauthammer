<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1> Products Brand Management </h1>
        </div><!-- /.page-header -->
        <input type="hidden" id="action" value="manage_product_brands">
        <div class="row-fluid">
            <div class="span12">
                <div class="well well-small">
                    <div class="btn btn-medium btn-primary" id="btn-product-brand-add"><i class="icon icon-plus"></i> Add Brand</div>
                </div>
                <div class="table-header">
                    Brands
                </div>
                <div class="table-responsive">
                    <table id="product-brands" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="center" style="width:30px;">
                                    <label>
                                        <input type="checkbox" class="ace" />
                                        <span class="lbl"></span>
                                    </label>
                                </th>
                                <th style="width:40%;">Name</th>
                                <th>Mail Logo</th>
                                <th>Alt Logo</th>
                                <th style="width:70px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>      
                </div>
            </div>
        </div>
        <!--PAGE SPAN END-->
    </div><!--PAGE Row END-->
</div><!--MAIN CONTENT END-->

<div id="modal-product-brand-add" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Add Product Brand
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <form id="form-add-brand" enctype="multipart/form-data">
                    <div class="widget-main">
                        <div class="hide">
                            <input type="hidden" class="hide" id="brand-id">
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label" for="name">Name:</label>
                                    <div class="controls">
                                        <input type="text" id="brand-name" name="brand_name" class="input input-small span12">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tabbable">
                            <ul class="nav nav-tabs" id="tab-product-brand">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab1">
                                        <i class="green icon-camera-retro bigger-110"></i>
                                        Main Image
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#tab2">
                                        <i class="gray icon-camera-retro bigger-110"></i>
                                        Alt Image
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div id="tab1" class="tab-pane in active">
                                    <div class="control-group">
                                        <label class="control-label" for="image">Main Logo:</label>
                                        <div class="row-fluid">
                                            <div class="controls span12">
                                                <input type="file" id="logo_main_url" name="logo_main_url" accept="image/*" class="hide">
                                            </div>
                                            <div class="controls span12 image-progress" style="display: none;">
                                                <div class="progress progress-mini progress-primary">
                                                    <div style="width:0%" class="bar"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab2" class="tab-pane">
                                    <div class="control-group">
                                        <label class="control-label" for="image">Alternative Logo:</label>
                                        <div class="row-fluid">
                                            <div class="controls span12">
                                                <input type="file" id="logo_alt_url" name="logo_alt_url" accept="image/*" class="hide">
                                            </div>
                                            <div class="controls span12 image-progress" style="display: none;">
                                                <div class="progress progress-mini progress-primary">
                                                    <div style="width:0%" class="bar"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label" for="name">Description:</label>
                                    <div class="controls">
                                        <textarea name="" id="brand-description" name="brand_desc" class="input input-small span12" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- /.modal-content -->
            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm btn-primary" id="modal-btn-product-brand-add">
                    Submit
                </button> 
                <button class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="icon-trash"></i>
                    Cancel
                </button>
            </div>
        </div><!-- /.modal-dialog -->
    </div>
</div>