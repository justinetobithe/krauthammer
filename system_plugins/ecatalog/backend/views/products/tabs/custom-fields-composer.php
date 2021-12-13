<div id="cms-custom-field-container" class="cms-custom-field-main-container cms-custom-field-container active">
  <div class="cms-cf-tools well well-small">
    <div class="hide">
      <input type="hidden" id="custom-field-id">
    </div>
    <div class="row-fluid">
      <div class="span6">
        <button href="javascript:void(0)" id="cms-cf-btn-import" class="btn btn-success full-width"><i class="icon icon-download"></i> Import Layout</button>
      </div>
      <div class="span6">
        <button href="javascript:void(0)" id="cms-cf-btn-export" class="btn btn-primary full-width"><i class="icon icon-save"></i> Export Layout</button>
      </div>
    </div>
    <a href="javascript:void(0)" id="btn-toggle-guide"><small>Toggle Guide</small></a>
    <div class="well well-small" id="cf-guide-container" style="display: none;">
      <p>Use the following functions below in the frontend to get the product custom field.</p>
      <dl>
        <dt>ecatalog_get_product_custom_fields()</dt>
        <dd>@Array</dd>
        <dt>ecatalog_get_product_custom_field( [ field_key ] )</dt>
        <dd>@String - Textarea, Simple Textarea, Text, Switch, Dropdown</dd>
        <dd>@Array - Address, Tags, Gallery</dd>
      </dl>
    </div>
  </div>

  <div class="row-fluid cf-main-item-container" style="box-sizing: border-box;">
    <div class="span12">
      <div class="cms-content"></div>
      <div class="cms-controls">
        <a href="javascript:void(0)" class="container-add-btn"><i class="icon icon-plus"></i></a>
      </div>
    </div>
  </div>
</div>

<div id="modal-cms-custom-field-container" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          Add Item
        </div>
      </div>
      <div class="modal-body">
        <div id="cms-custom-field-container-options" class="cms-cf-container form-horizontal">
          <button class="btn btn-success" id="cms-cf-show-add-row"><i class="icon icon-table"></i> Add Row</button>
          <button class="btn btn-success" id="cms-cf-show-add-element"><i class="icon icon-cogs"></i> Add Field</button>
          <button class="btn btn-primary pull-right" data-dismiss="modal"> Close</button>
        </div>

        <div id="cms-cf-container-add-row" class="cms-cf-container form-horizontal" style="display: none;">
          <div class="control-group">
            <label class="control-label"><small>Columns Count: </small></label>
            <div class="controls">
              <select name="" class="cms-cf-value">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <div class="controls">
              <button class="btn btn-success" id="cms-cf-confirm-add-row"><i class="icon icon-plus"></i> Add</button>
              <button class="btn btn-primary cms-cf-confirm-dismiss">Cancel</button>
            </div>
          </div>
        </div>

        <div id="cms-cf-container-add-element" class="cms-cf-container form-horizontal" style="display: none;">
          <div class="cf-item-options"></div>
          <hr>
          <button class="btn btn-primary cms-cf-confirm-dismiss" style="margin-left: 5px;">Close</button>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script class="cf-template" type="text/x-jquery-tmpl" data-title="Text" id="tmpl-cms-cf-controls" >
  <div class="cms-content"></div>
  <div class="cms-controls">
    <a href="javascript:void(0)" class="container-add-btn"><i class="icon icon-plus"></i></a>
  </div>
</script>

<script class="cf-template" type="text/x-jquery-tmpl" data-title="Text" id="tmpl-cms-cf-row" >
  <div class="cms-content"></div>
  <div class="cms-controls">
    <a href="javascript:void(0)" class="container-add-btn"><i class="icon icon-plus"></i></a>
  </div>
</script>

<script class="cf-template" type="text/x-jquery-tmpl" data-title="Text" id="cf-element-column" >
  <div class="accordion-group cf-column" data-type="column">
    Select Number of Columns
    <div class="form-horizontal">
      <div class="control-label">Select Number of Columns</div>
      <div class="controls">
        <select name="" class="cf-column-value">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
        </select>
      </div>
    </div>
  </div>
</script>

<?php include __DIR__ . "/custom-fields.php"; ?>