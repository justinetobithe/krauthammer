<div id="custom_fields" class="tab-pane" style="display: none;">
  <div class="widget-box">
    <div class="widget-header header-color-blue2">
      <h4 class="lighter smaller">Custom Fields</h4>
    </div>
    <div class="widget-body">
      <div class="widget-main">
        
        <div class="form-horizontal">
          <div class="control-group">
            <label for="modal-type-custom-field" class="control-label">Select Field</label>
            <div class="controls">
              <select id="modal-type-custom-field"></select>
              <button class="btn btn-small btn-primary" id="btn-add-custom-field"><i class="icon icon-plus"></i> Add Field</button>
              <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-small btn-success dropdown-toggle" id="btn-advance-custom-field" data-rel="tooltip" title="Other Options">
                  <i class="icon icon-cog"></i>
                </button>

                <ul class="dropdown-menu dropdown-success pull-right">
                  <li>
                    <a href="javascript:void(0)" id="btn-import-custom-field">Use Existing Template</a>
                  </li>
                  <li>
                    <a href="javascript:void(0)" id="btn-export-custom-field">Save as New Template</a>
                  </li>
                  <li>
                    <a href="<?php echo URL ?>settings/?tab=product&section=product-custom-field" id="btn-export-custom-field" target="_blank">Manage Templates</a>
                  </li>
                </ul>
              </div><!--/btn-group-->
            </div>
          </div>
        </div>
        <div class="form-horizontal">
          <div class="control-group">
          </div>
        </div>
        <div class="well well-small" id="cf-guide-container" style="display: none;">
          <dl>
            <dt>ecatalog_get_product_custom_fields()</dt>
            <dd>@Array</dd>
            <dt>ecatalog_get_product_custom_field( [ field_key ] )</dt>
            <dd>@String - Textarea, Simple Textarea, Text, Switch, Dropdown</dd>
            <dd>@Array - Address, Tags, Gallery</dd>
          </dl>
        </div>
        <hr>
        <div class="field-container accordion"></div>
      </div>
    </div>
  </div>
</div>

<script class="custom-field-template" type="text/x-jquery-tmpl" data-title="Text" id="tag-text" >
  <div class="accordion-group cf-item" id="custom-field-id-${field_id}" data-type="text" data-id="${field_id}">
    <div class="accordion-heading">
      <a href="#custom-collapse-${field_id}" data-toggle="collapse" class="accordion-toggle collapsed">
        Text: <b>${field_name}</b>
      </a>
    </div>

    <div class="accordion-body collapse" id="custom-collapse-${field_id}">
      <div class="accordion-inner">
        <div class="detail">
          <label><small>Name &nbsp; <span class="custom-field-name-warning text-error"></span> &nbsp;</small></label>
          <input type="text" class="title span12" value="${field_name}" placeholder="Enter Field Name">

          <label><small>Key</small></label>
          <input type="text" class="key span12" value="${field_key}" placeholder="Enter Field Name">
          
          <label><small>Value</small></label>
        </div>

        <label class="active-title">${field_name}</label>
        <input type="text" class="value span12" value="" placeholder="Enter Field Value">

        <div class="detail">
          <hr>
          <a href="javascript:void(0)" class="remove-custom-field btn btn-danger btn-small">remove</a>
        </div>
      </div>
    </div>
  </div>
</script>
<script class="custom-field-template" type="text/x-jquery-tmpl" data-title="Textarea" id="tag-textarea" >  
  <div class="accordion-group cf-item" id="custom-field-id-${field_id}" data-type="textarea" data-id="${field_id}">
    <div class="accordion-heading">
      <a href="#custom-collapse-${field_id}" data-toggle="collapse" class="accordion-toggle collapsed">
        Textarea: <b>${field_name}</b>
      </a>
    </div>

    <div class="accordion-body collapse" id="custom-collapse-${field_id}">
      <div class="accordion-inner">
        <div class="detail">
          <label>
            <small>
              Name &nbsp; 
              <span class="custom-field-name-warning text-error"></span>
            </small>
          </label>
          <input type="text" class="title span12" value="${field_name}" placeholder="Enter Field Name">

          <label><small>Key &nbsp;</small></label>
          <input type="text" class="key span12" value="${field_key}" placeholder="Enter Field Name">

          <label><small>Value</small></label>
        </div>

        <label class="active-title">${field_name}</label>
        <textarea class="custom-field-textarea value span12" rows="5" id="custom-field-textarea-${field_id}"></textarea>

        <div class="detail">
          <hr>
          <a href="javascript:void(0)" class="remove-custom-field detail btn btn-danger btn-small">remove</a>
        </div>
      </div>
    </div>
  </div>
</script>
<script class="custom-field-template" type="text/x-jquery-tmpl" data-title="Simple Textarea" id="tag-textarea-simple" >  
  <div class="accordion-group cf-item" id="custom-field-id-${field_id}" data-type="textarea-simple" data-id="${field_id}">
    <div class="accordion-heading">
      <a href="#custom-collapse-${field_id}" data-toggle="collapse" class="accordion-toggle collapsed">
        Simple Textarea: <b>${field_name}</b>
      </a>
    </div>

    <div class="accordion-body collapse" id="custom-collapse-${field_id}">
      <div class="accordion-inner">
        <div class="detail">
          <label><small>Name &nbsp; <span class="custom-field-name-warning text-error"></span></small></label>
          <input type="text" class="title span12" value="${field_name}" placeholder="Enter Field Name">

          <label><small>Key &nbsp;</small></label>
          <input type="text" class="key span12" value="${field_key}" placeholder="Enter Field Name">

          <label><small>Value</small></label>
        </div>

        <label class="active-title">${field_name}</label>
        <textarea class="custom-field-textarea value span12" rows="5" id="custom-field-textarea-${field_id}"></textarea>

        <div class="detail">
          <hr>
          <a href="javascript:void(0)" class="remove-custom-field text-error btn btn-danger btn-small">remove</a>
        </div>
      </div>
    </div>
  </div>
</script>
<script class="custom-field-template" type="text/x-jquery-tmpl" data-title="Dropdown" id="tag-dropdown" >  
  <div class="accordion-group cf-item" id="custom-field-id-${field_id}" data-type="dropdown" data-id="${field_id}">
    <div class="accordion-heading">
      <a href="#custom-collapse-${field_id}" data-toggle="collapse" class="accordion-toggle collapsed">
        Dropdown: <b>${field_name}</b>
      </a>
    </div>

    <div class="accordion-body collapse" id="custom-collapse-${field_id}">
      <div class="accordion-inner">
        <div class="detail">
          <label><small>Name &nbsp; <span class="custom-field-name-warning text-error"></span></small></label>
          <input type="text" class="title span12" value="${field_name}" placeholder="Enter Field Name">

          <label><small>Key &nbsp;</small></label>
          <input type="text" class="key span12" value="${field_key}" placeholder="Enter Field Name">
        </div>

        <div>
          <div class="detail">
            <label><small>Value</small></label>
          </div>

          <label class="active-title">${field_name}</label>
          <select id="custom-field-select-${field_id}" class="value custom-field-select">
          {{each field_opt}}
            <option value="${$value}">${$value}</option>
          {{/each}}
          </select>

          <a href="javascript:void(0)" class="custom-field-select-add-item detail">Edit Items</a>
        </div>

        <div class="detail">
          <hr>
          <a href="javascript:void(0)" class="remove-custom-field btn btn-danger btn-small">remove</a>
        </div>
      </div>
    </div>
  </div>
</script>
<script class="custom-field-template" type="text/x-jquery-tmpl" data-title="Tags" id="tag-tags" >  
  <div class="accordion-group cf-item" id="custom-field-id-${field_id}" data-type="tags" data-id="${field_id}">
    <div class="accordion-heading">
      <a href="#custom-collapse-${field_id}" data-toggle="collapse" class="accordion-toggle collapsed">
        Tags: <b>${field_name}</b>
      </a>
    </div>

    <div class="accordion-body collapse" id="custom-collapse-${field_id}">
      <div class="accordion-inner">
        <div class="detail">
          <label><small>Name &nbsp; <span class="custom-field-name-warning text-error"></span></small></label>
          <input type="text" class="title span12" value="${field_name}" placeholder="Enter Field Name">

          <label><small>Key &nbsp;</small></label>
          <input type="text" class="key span12" value="${field_key}" placeholder="Enter Field Name">
        </div>

        <label class="detail"><small>Value</small></label>

        <label class="active-title">${field_name}</label>
        <select id="custom-field-select-${field_id}" class="value custom-field-select tag-input-style" multiple="multiple">
        {{each field_opt}}
          <option value="${$value}">${$value}</option>
        {{/each}}
        </select>
        <a href="javascript:void(0)" class="custom-field-select-add-item detail">Edit Items</a>

        <div class="detail">
          <hr>
          <a href="javascript:void(0)" class="remove-custom-field btn btn-danger btn-small">remove</a>
        </div>
      </div>
    </div>
  </div>
</script>
<script class="custom-field-template" type="text/x-jquery-tmpl" data-title="Switch" id="tag-switch" >  
  <div class="accordion-group cf-item" id="custom-field-id-${field_id}" data-type="switch" data-id="${field_id}">
    <div class="accordion-heading">
      <a href="#custom-collapse-${field_id}" data-toggle="collapse" class="accordion-toggle collapsed">
        Switch: <b>${field_name}</b>
      </a>
    </div>

    <div class="accordion-body collapse" id="custom-collapse-${field_id}">
      <div class="accordion-inner">
        <div class="detail">
          <label><small>Name &nbsp; <span class="custom-field-name-warning text-error"></span></small></label>
          <input type="text" class="title span12" value="${field_name}" placeholder="Enter Field Name">

          <label><small>Key &nbsp;</small></label>
          <input type="text" class="key span12" value="${field_key}" placeholder="Enter Field Key">
        </div>

        <br class="detail">

        <div>
          <label class="active-title">${field_name}</label>
          <label>
            <input  id="custom-field-switch-${field_id}" class="ace ace-switch ace-switch-6 value custom-field-switch" type="checkbox" />
            <span class="lbl"></span>
          </label>
        </div>

        <div class="detail">
          <hr>
          <a href="javascript:void(0)" class="remove-custom-field btn btn-danger btn-small">remove</a>
        </div>
      </div>
    </div>
  </div>
</script>
<script class="custom-field-template" type="text/x-jquery-tmpl" data-title="Gallery" id="tag-gallery" >  
  <div class="accordion-group cf-item" id="custom-field-id-${field_id}" data-type="gallery" data-id="${field_id}">
    <div class="accordion-heading">
      <a href="#custom-collapse-${field_id}" data-toggle="collapse" class="accordion-toggle collapsed">
        Gallery: <b>${field_name}</b>
      </a>
    </div>
    <div class="accordion-body collapse" id="custom-collapse-${field_id}">
      <div class="accordion-inner">
        <div class="detail">
          <label><small>Name &nbsp; <span class="custom-field-name-warning text-error"></span></small></label>
          <input type="text" class="title span12" value="${field_name}" placeholder="Enter Field Name">
          <label><small>Key &nbsp;</small></label>
          <input type="text" class="key span12" value="${field_key}" placeholder="Enter Field Key">
        </div>

        <br class="detail">

        <div style="display: table; width: 100%;">
          <label class="active-title">${field_name}</label>
          <div class="gallery-container">
            <span class="text-small">You can drag files to the section below to start uploading.</span>
            <ul class="ace-thumbnails galley-list" id="custom-field-id-${field_id}-gallery-list">
              <li class="gallery-list-btn-container">
                <a href="jvascript:void(0)" data-rel="colorbox" class="btn-gallery-add-item">
                  <i class="icon icon-plus"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="file-drop-zone" style="display: none;">
          <span>Drop File Here</span>
          <input type="file" class="gallery-file-input hide" multiple="multiple">
        </div>

        <div class="detail">
          <hr>
          <a href="javascript:void(0)" class="remove-custom-field btn btn-danger btn-small">remove</a>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/x-jquery-tmpl" id="tag-gallery-item" >  
  <li style="display: none;" class="tag-gallery-item">
    <a href="jvascript:void(0)" data-lightbox="gallery-image">
      <img alt="150x150" class="item-image" src=""/>
    </a>
    <div class="tools">
      <a href="javascript:void(0)" class="tag-gallery-item-btn-edit" data-value="">
        <i class="icon-pencil"></i>
      </a>
      <a href="javascript:void(0)" class="tag-gallery-item-btn-remove" data-value="">
        <i class="icon-remove red"></i>
      </a>
    </div>
    <div class="item-info hide" style="display: none;">
      <input type="hidden" class="item-id">
      <input type="hidden" class="item-name">
      <textarea rows="5" class="item-desc"></textarea>
    </div>
  </li>
</script>
<script class="custom-field-template" type="text/x-jquery-tmpl" data-title="Address" id="tag-address" > 
  <div class="accordion-group cf-item" id="custom-field-id-${field_id}" data-type="address" data-id="${field_id}">
    <div class="accordion-heading">
      <a href="#custom-collapse-${field_id}" data-toggle="collapse" class="accordion-toggle collapsed">
        Address: <b>${field_name}</b>
      </a>
    </div>

    <div class="accordion-body collapse" id="custom-collapse-${field_id}">
      <div class="accordion-inner">
        <div class="detail">
          <label><small>Name &nbsp; <span class="custom-field-name-warning text-error"></span></small></label>
          <input type="text" class="title span12" value="${field_name}" placeholder="Enter Field Name">
          <label><small>Key &nbsp; </small></label>
          <input type="text" class="key span12" value="${field_key}" placeholder="Enter Field Key">

          <label><small>Value</small></label>
        </div>


        <label class="active-title">${field_name}</label>
        <textarea class="custom-field-textarea value span12" rows="5" id="custom-field-textarea-${field_id}"></textarea>

        <a href="javascript:void(0)" class="btn-check-map">Check Map</a>
        <p><span class="map-loading" style="display: none;">Retrieving Coordinate</span></p>
        <input type="hidden" class="map-lat input-small span6">
        <input type="hidden" class="map-lng input-small span6">

        <div class="detail">
          <hr>
          <a href="javascript:void(0)" class="remove-custom-field btn btn-danger btn-small">remove</a>
        </div>
      </div>
    </div>
  </div>
</script>

<div id="modal-custom-fields-save-template" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          Save Custom Field Set
        </div>
      </div>
      <div class="modal-body">
        <div class="row-fluid">
          <div class="span12">
            <label for="modal-name-custom-field">Name</label>
            <input id="modal-custom-field-template-name" type="text" class="form-control span12" style="width: 100%;" placeholder="Enter custom field template name.">
            <p><i>This will save the structure of current Custom Fields that can be used in other product.</i></p>
            <hr>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button id="modal-btn-confirm-custom-field" class="btn btn-success btn-small"><i class="icon icon-plus"></i> Confirm</button>
        <button data-dismiss="modal" class="btn btn-info btn-small">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<div id="modal-custom-fields-select-template" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          Save Custom Field Template
        </div>
      </div>

      <div class="modal-body">
        <div class="row-fluid">
          <div class="span12">
            <label for="modal-name-custom-field">Select Template</label>
            <select id="modal-custom-field-template-select" class="input span12"></select>
          </div>
        </div>

        <div class="row-fluid">
          <div class="span12">
            <div class="custom-field-preview"></div>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button id="modal-btn-confirm-custom-field-template" class="btn btn-success btn-small"><i class="icon icon-plus"></i> Use Template</button>
        <button data-dismiss="modal" class="btn btn-info btn-small">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<div id="modal-custom-field-select-add-item" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          Edit Options
        </div>
      </div>

      <div class="modal-body">
        <div class="row-fluid">
          <div class="span12">
            <label for="modal-name-custom-field">Option List</label>
            <input type="hidden" id="modal-custom-field-list-currrent-group">
            <textarea id="modal-custom-field-list-item" cols="30" rows="5" class="span12"></textarea>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button id="modal-btn-confirm-select-items" class="btn btn-success btn-small"><i class="icon icon-list"></i> Confirm List</button>
        <button data-dismiss="modal" class="btn btn-info btn-small">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<div id="modal-custom-field-gallery-editor" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          Gallery
        </div>
      </div>

      <div class="modal-body">
        <div class="row-fluid">
          <div class="span12">
            <div class="tabbable">
              <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="modal-custom-field-gallery-tab">
                <li class="active">
                  <a data-toggle="tab" href="#gallery-tab-detail">Detail</a>
                </li>
                <li >
                  <a data-toggle="tab" href="#gallery-tab-image">Image</a>
                </li>
              </ul>

              <div class="tab-content">

                <div id="gallery-tab-detail" class="tab-pane in active">
                  <div class="form">
                    <div class="control-group">
                      <label for="gallery-item-name" class="control-label"><small>Item Name</small></label>
                      <div class="controls">
                        <input type="text" class="span12" id="gallery-item-name">
                      </div>
                    </div>
                    <div class="control-group">
                      <label for="gallery-item-desc" class="control-label"><small>Item Description</small></label>
                      <div class="controls">
                        <textarea id="gallery-item-desc" class="span12" style="max-width: 100%; min-width: 100%;" rows="5"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="gallery-tab-image" class="tab-pane">
                  <div class="gallery-edit-image text-center">
                    <img src="" alt="" id="gallery-image-previewer" style="max-wwidth: 200px; max-height: 300px;">
                  </div>

                  <div class="hide">
                    <p class="text-center">
                      <a href="javascript:void(0)" target="_blank" id="btn-gallery-upload"><i class="icon icon-upload"></i> Upload Image</a> | 
                      <a href="javascript:void(0)" target="_blank" id="btn-gallery-import"><i class="icon icon-download"></i> Import from Media</a>
                    </p>
                    <input type="hidden" id="current-gallery-item-id">
                    <input type="hidden" id="gallery-item-id">
                    <input type="hidden" id="gallery-item-url">
                    <ul id="gallery-list-media-container" class="ace-thumbnails galley-list"></ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button id="modal-btn-confirm-gallery-item" class="btn btn-success btn-small"><i class="icon icon-list"></i> Edit</button>
        <button data-dismiss="modal" class="btn btn-info btn-small">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<script type="text/x-jquery-tmpl" id="modal-gallery-items" >  
  <li>
    <a href="javascript:void(0)" data-value="${id}" data-rel="colorbox" class="gallery-item" >
      <img alt="100x100" src="${url}" />
    </a>
  </li>
</script>
<div id="modal-custom-field-address" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          Address
        </div>
      </div>

      <div class="modal-body">
        <div class="row-fluid">
          <div class="span12">
            <div class="tabbable">
              <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="modal-custom-field-map-tab">
                <li class="active">
                  <a data-toggle="tab" href="#map-tab-view">Map</a>
                </li>
                <li >
                  <a data-toggle="tab" href="#map-tab-detail">Detail</a>
                </li>
              </ul>

              <div class="tab-content">
                <div id="map-tab-view" class="tab-pane in active">
                  <div id="map_canvas" style="height:300px; width:100%;"></div>
                </div>
                <div id="map-tab-detail" class="tab-pane">
                  <div class="form">
                    <div class="control-group">
                      <label for="map-item-address" class="control-label"><small>Address</small></label>
                      <div class="controls">
                        <textarea id="map-item-address" class="span12" style="max-width: 100%; min-width: 100%;" rows="3" readonly="readonly"></textarea>
                      </div>
                    </div>
                    <div class="control-group">
                      <label for="map-item-coordinate" class="control-label"><small>Coordinate</small></label>
                      <div class="controls">
                        <input type="hidden" id="current-map-id">
                        <input type="text" class="span6" id="map-item-coordinate-lat" readonly="readonly">
                        <input type="text" class="span6" id="map-item-coordinate-lng" readonly="readonly">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button id="modal-btn-confirm-map" class="btn btn-success btn-small"><i class="icon icon-check"></i> Confirm Location</button>
        <button data-dismiss="modal" class="btn btn-info btn-small">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>