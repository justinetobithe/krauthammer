
<div class="main-content">
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                             E Shop Product Management
                            </h1>
                        </div><!-- /.page-header -->
                        <input type="hidden" id="action" value="mange_product">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table-header">
                                    EShop Products
                                </div>
                               
                                <div class="table-responsive">
                                    <table id="productTable" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center" style="width:5%;">
                                                    <label>
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </th>
                                                <th style="width:10%;">Image</th>
                                                <th style="width:20%;">Title</th>
                                                <th style="width:7%;">Price</th>
                                                <th style="width:10%;">Stock Qty</th>
                                                <th>SKU</th>
                                                <th style="width:7%;">Featured</th>
                                                <th>Category</th>
                                            </tr>
                                        </thead>
                                    </table>      
                                </div>
                            </div>
                            
                      </div>
                            <!--PAGE SPAN END-->
                    </div><!--PAGE Row END-->
</div><!--MAIN CONTENT END-->
<div id="delete" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Delete Product
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>

            <div class="modal-body">

                <div id="delete_msg">
                    <h5 class="red"> </h5>
                </div>
                <input type="hidden" id="hidden_product_id"/>
            </div><!-- /.modal-content -->
            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm btn-danger pull-right" onclick="deleteProduct();">
                    <i class="icon-trash"></i>
                    Delete
                </button>
            </div>
    </div><!-- /.modal-dialog -->
</div>
</div>

<label for="product-category_filter" class="datatable-add-ons">
    Categories:
    <select name="product_category" id="product-category_filter">
        <option value="all" >All Categories</option>
    <?php foreach ($product_categories as $key => $value): ?>
        <option value="<?php echo $value['id'] ?>" ><?php echo $value['product_name'] ?></option>
    <?php endforeach ?>
    </select>
</label>