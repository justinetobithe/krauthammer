
<div class="main-content">
                

                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                             Invoices
                            </h1>
                        </div><!-- /.page-header -->
                        <input type="hidden" id="action" value="manage_invoices">
                          <div class="row-fluid">
                            <div class="span12">
                                <div class="table-header">
                                      All Invoices
                                </div>
                               
                                <div class="table-responsive">
                                    <table id="manage_invoices" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                   <tr>
                                                        <th class="center" style="width:5%;">
                                                            <label>
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </th>
                                                        <th width="13%">Invoice Number</th>
                                                        <th width="10%" >Invoice Date/Time</th>
                                                        <th width="10%">Payment Issue Dates</th>
                                                        <th>Payment Reference Numbers</th>
                                                        <th>Payment Mode</th>
                                                        <th>Payment Amounts</th>
                                                        <th>Total Amount Paid</th>
                                                        <th width="10%" >Due Date</th>
                                                        <th>Order Status</th>
                                                        <th style="width:10%;">Actions</th>
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