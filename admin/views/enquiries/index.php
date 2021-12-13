<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1>
               Enquiries
           </h1>
       </div><!-- /.page-header -->
       <input type="hidden" id="action" value="manage_enquiries">
       <div class="row-fluid">
        <div class="span12">
            <div class="table-header">
                Enquiries
            </div>

            <div class="table-responsive">
                <table id="ordersTable" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="center" style="width:5%;">
                                <label>
                                    <input type="checkbox" class="ace" />
                                    <span class="lbl"></span>
                                </label>
                            </th>
                            <th style="width:10%;"><small>Order Date/Time</small></th>
                            <th><small>Name</small></th>
                            <th><small>Email</small></th>
                            <th style="width:12%;"><small>Contact Number</small></th>
                            <th style="width:10%;"><small>Message</small></th>
                            <th style="width:10%;"><small>Actions</small></th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
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
                    Delete Order
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>

            <div class="modal-body">

                <div id="delete_msg">
                    <h5 class="red"></h5>
                </div>
                <input type="hidden" id="hidden_order_id"/>
            </div><!-- /.modal-content -->
            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm btn-danger pull-right" onclick="deleteOrderModal();">
                    <i class="icon-trash"></i>
                    Delete
                </button>
            </div>
        </div><!-- /.modal-dialog -->
    </div>
</div>
<div id="full-message" class="modal fade">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Message
                </div>
            </div>

            <div class="modal-body">
                <p class="modal-full-message-content"></p>
            </div><!-- /.modal-content -->
            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm btn-primary btn-mini pull-right" data-dismiss="modal">
                    <i class="icon-trash"></i>
                    Close
                </button>
            </div>
        </div><!-- /.modal-dialog -->
    </div>
</div>