<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1>Subscribers</h1>
        </div><!-- /.page-header -->
        <input type="hidden" id="action" value="manage">
        <div class="row-fluid">
            <div class="span12">
                <div class="table-header">
                    All Post
                    <button class="btn btn-small btn-success pull-right" style="margin-top: 3px; margin-right: 3px;" id="btn-add-subscriber"><i class="icon icon-plus"></i> Add Subscriber</button>
                </div>

                <div class="table-responsive">
                    <table id="table-newsletter" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="center" style="width:5%;">
                                    <label>
                                        <input type="checkbox" class="ace" />
                                        <span class="lbl"></span>
                                    </label>
                                </th>
                                <th>Email</th>
                                <th style="width:10%;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>      
                </div>
            </div>
        </div><!--PAGE SPAN END-->
    </div><!--PAGE Row END-->
</div><!--MAIN CONTENT END-->
<div id="modal-add-newsletter" class="modal fade">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Add New Email Address
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>

            <div class="modal-body">
                <div id="delete_msg">
                    <h5 class="">Enter Email Address</h5>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <input type="hidden" id="modal-input-email-address-id" class="hide"/>
                        <input type="text" id="modal-input-email-address" class="span12"/>
                    </div>
                </div>
            </div><!-- /.modal-content -->
            <div class="modal-footer no-margin-top">
                <button class="btn btn-primary pull-right" id="modal-btn-add-email">
                    <i class="icon icon-save"></i>
                    Save
                </button>
            </div>
        </div><!-- /.modal-dialog -->
    </div>
</div>