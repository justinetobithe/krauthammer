<div class="main-content">
    <div class="page-content">

        <div class="page-header">
            <h1>
                Product Categories      
            </h1>
        </div>

        <input type="hidden" id="action" value="manage"/>

        <div class="row-fluid">
        	
        	<div class="span12">

				<div class="table-header">
                    <div class="row-fluid">
                        <div class="span9">

                            <button class="btn btn-success btn-small" onclick="go_to_add();">Add New Category</button>
                            &nbsp;
                            <button class="btn btn-success btn-small" onclick="go_to_sort();">Sort Categories</button>
                        </div>
                    </div>
                </div>
               
                <div class="table-responsive">
                    <table id="productCategoriesTable" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="center" style="width:5%;">
                                    <label>
                                        <input type="checkbox" class="ace" />
                                        <span class="lbl"></span>
                                    </label>
                                </th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Post</th>
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
</div>

<div id="delete" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Delete Product Category
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>

            <div class="modal-body">
                <input type="hidden" id="hdn_id"/>
                <div id="delete_msg">
                    <h5 class="red">Are you sure to delete this product category?</h5>
                </div>
            </div><!-- /.modal-content -->

            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm btn-danger pull-right" onclick="delete_category();">
                    <i class="icon-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div><!-- /.modal-dialog -->
</div>
