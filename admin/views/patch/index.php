<div class="main-content">
  <div class="page-content">
    <div class="page-header">
      <h1>
       Updates    
     </h1>
   </div>
   <div class="row-fluid">
     <div class="span12">

      <div class="row-fluid">
        <div class="span12">
          <div class="tabbable tabs-left" >
            <ul class="nav nav-tabs" id="update-tabs">
              <li class="active">
                <a data-toggle="tab" href="#tab1">
                  <i class="red icon-exclamation-sign bigger-110"></i>
                  Updates
                </a>
              </li>
              <li>
                <a data-toggle="tab" href="#tab2">
                  <i class="icon-list bigger-110"></i>
                  Previews Updates
                </a>
              </li>

            </ul>

            <div class="tab-content">
              <div id="tab1" class="tab-pane in active" style="overflow-x: hidden;">
                <h3>New Updates</h3>
                <hr>
                <div id="alert-patch" class="alert alert-success" style="display: none;">System Version is Updated</div>
                <div id="loading-patch" class="alert alert-info" style="display: none;">Fetching Updates...</div>
                <div id="patch-action" class="well well-small" style="display: none;">
                  <span></span>
                  <button class="btn btn-mini btn-success pull-right" onClick="reload_patch();"><i class="icon icon-refresh"></i> Reload</button> 
                  <button class="btn btn-mini btn-primary pull-right" onClick="install_patch();"><i class="icon icon-download"></i> Install</button>
                </div>
                <div id="patch-container"></div>
              </div>
              <div id="tab2" class="tab-pane" style="overflow-x: hidden;">
                <h3>Updates Log</h3>
                <hr>
                <div id="alert-prev-patch" class="alert alert-info" style="display: none;">No Previous Updates</div>
                <div id="loading-prev-patch" class="alert alert-info" style="display: none;">Fetching Updates...</div>
                <div id="patch-prev-container"></div>
                <div class="pagination" id="patch-prev-container-pagination-container">
                  <ul id="patch-prev-container-pagination"></ul>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div id="patch-modal" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header error">
          Patching...
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">×</span>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <h5 class="warning"></h5>
        <p><b id="patch-modal-detail"></b></p>
        <div id="patch-progress-container"></div>
      </div><!-- /.modal-content -->

      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger" id="patch-progress-cancel">Cancel</button>
        <button class="btn btn-sm btn-primary" id="patch-progress-close" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div><!-- #dialog-confirm -->


<script id="tmpl-patch-item" type="text/x-tmpl">
  <p>${patch_detail}</p>
</script>

<script id="tmpl-patch-container" type="text/x-tmpl">
  <div>
    Update: <b>${version}</b>
    <ul class="update-list unstyled"></ul>
  </div>
</script>

<script id="tmpl-prev-patch-container" type="text/x-tmpl">
  <div class="update-log">
    <p>Update: <b>${version}</b> <small class="green">( ${update_type} )</small> <span class="pull-right"><small>${date_installed_formated}</small></span></p>
    <ul class="update-list unstyled"></ul>
  </div>
  <hr>
</script>

<script id="tmpl-patch-list-container" type="text/x-tmpl">
  <li>- ${description}</li>
</script>