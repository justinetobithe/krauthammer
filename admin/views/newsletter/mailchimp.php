<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1>MailChimp</h1>
        </div><!-- /.page-header -->
        <input type="hidden" id="action" value="mailchimp">

        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-header widget-header-small header-color-blue2">
                        <h6>Settings</h6>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label" for="mailchimp-api-key">API Key</label>
                                    <div class="controls">
                                        <input type="text" id="mailchimp-api-key" placeholder="Enter API Key" class="input input-xxlarge">
                                        <button class="btn btn-small btn-success" id="btn-save-mailchimp-api-key"><i class="icon icon-save"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>
                <div class="widget-box">
                    <div class="widget-header widget-header-small header-color-blue2">
                        <h6>MailChimp Newletter Lists</h6>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="row-fluid">
                                <div class="span12">
                                    <p>Select the list(s) to which people who check the checkbox should be subscribed.</p>
                                    <p>The table below shows your MailChimp lists and their details. If made changes to your MailChimp lists, you can use the button below to refresh the CMS mailchimp cache.</p>
                                    <button class="btn btn-small btn-primary" id="btn-mainchimp-refresh-list"><i class="icon-refresh"></i> Refresh List</button>
                                </div>
                            </div>
                            <div class="space"></div>

                            <div class="table-responsive">
                                <table  id="table-mailchimp-list" class="table table-striped table-bordered table-hover full-width">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:5px;"></th>
                                            <th>List Name</th>
                                            <th>ID</th>
                                            <th style="width: 30px;">Subscriber</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>      
                            </div>
                        </div>     
                    </div>
                </div>
                <div class="widget-box">
                    <div class="widget-header widget-header-small header-color-blue2">
                        <h6>Integration</h6>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="form-horizontal">
                                        <div class="control-group">
                                            <label class="control-label" for="mailchimp-api-key">MailChimp Form Code</label>
                                            <div class="controls">
                                                <p>copy and paste to the contact form include the mailchimp subscribe checkbox on contact form</p>
                                                <p><b>[mailchimp_subscription_checkbox]</b></p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mailchimp-note-container">
                                        <p>This Mailhchimp module will automatically get necessary fields from a contact form if following fields the following attribute: </p>
                                        <ul style="padding-left: 20px;">
                                            <li>
                                                <p><b>name ="mailchimp_email"</b> - <span class="red"><b>required*</b></span></p>
                                                <p>- Mailchimp requires email address when adding new subscriber.</p>
                                            </li>
                                            <li>
                                                <p><b>name ="mailchimp_firstname"</b></p>
                                                <p>- Subscriber's first name.</p>
                                            </li>
                                            <li>
                                                <p><b>name ="mailchimp_lastname"</b></p>
                                                <p>- Subscriber's last name.</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <div class="form-horizontal">
                                        <div class="control-group">
                                            <label class="control-label" for="mailchimp-api-key">Checkbox Label</label>
                                            <div class="controls">
                                                <input type="text" id="mailchimp-contact-form-checkbox-label" placeholder="Label to appear on Contact Form Subscription Checkbox" class="input input-xxlarge" value="Subscribe for newsletter!">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="mailchimp-api-key">Checked as Default</label>
                                            <div class="controls">
                                                <label style="display: inline;">
                                                    <input name="mailchimp-precheck-radio" type="radio" value="Yes" class="ace mailchimp-precheck-radio" checked="checked">
                                                    <span class="lbl"> Yes</span>
                                                </label>
                                                |
                                                <label style="display: inline;">
                                                    <input name="mailchimp-precheck-radio" type="radio" value="No" class="ace mailchimp-precheck-radio">
                                                    <span class="lbl"> No</span>
                                                </label>

                                                <p>If (Yes), the subscriptopn checkbox of the contact for will be checked as its default value.</p>
                                            </div>
                                        </div>
                                        <div class="control-group hide">
                                            <label class="control-label" for="mailchimp-api-key">Auto Update Subscribers</label>
                                            <div class="controls">
                                                <label style="display: inline;">
                                                    <input name="mailchimp-auto-update-radio" type="radio" value="Yes" class="ace mailchimp-auto-update-radio" checked="checked">
                                                    <span class="lbl"> Yes</span>
                                                </label>
                                                |
                                                <label style="display: inline;">
                                                    <input name="mailchimp-auto-update-radio" type="radio" value="No" class="ace mailchimp-auto-update-radio">
                                                    <span class="lbl"> No</span>
                                                </label>

                                                <p>If (Yes), the newlly subscriber will automatically send and added to mailchimp.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>

                <div class="well well-small text-right">
                    <button class="btn btn-success" id="btn-mailchimp-save-changes"><i class="icon icon-save"></i> Save Changes </button>
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

<div id="modal-add-newsletter-loading" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Newsletter Info
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <p>Loading Content...</p>
            </div>
        </div>
    </div>
</div>