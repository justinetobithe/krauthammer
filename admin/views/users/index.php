
<div class="main-content">
                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                Add New User      
                            </h1>
                        </div><!-- /.page-header -->
                        <input type="hidden" id="action" value="manage_user">
                        <div class="row-fluid">

                             <div class="span12">
                                <div class="table-header">
                                            Users
                                </div>
                               
                                <div class="table-responsive">
                                    <table id="userTable" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="center" style="width:5%;">
                                                            <label>
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </th>
                                                        <th style="width:10%;">Role</th>
                                                        <th style="width:25%;">Username</th>
                                                        <th style="width:25%;">Full Name</th>
                                                        <th>Email</th>
                                                        <th style="width:5%;">Actions</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                </tbody>
                                    </table>      
                                </div>
                            </div>
                        </div><!--PAGE Row END-->
                    </div><!--PAGE CONTENT END-->
</div><!--MAIN CONTENT END-->
<!--MODAL-->
<div id="delete" class="modal fade">
    <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header no-padding">
                    <div class="table-header">
                        Delete User
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <span class="white">×</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body">

                    <div id="delete_msg">
                        <h5 class="red"></h5>
                    </div>
                    <input type="hidden" id="hidden_user_id"/>
                </div><!-- /.modal-content -->
                <div class="modal-footer no-margin-top" id="model_footer">
                    <button class="btn btn-sm btn-danger pull-right" onclick="deleteUserModal();">
                        <i class="icon-trash"></i>
                        Delete
                    </button>
                </div>
            </div><!-- /.modal-dialog -->
    </div>
</div>