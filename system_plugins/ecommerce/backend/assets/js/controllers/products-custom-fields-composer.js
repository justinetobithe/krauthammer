var cms_cf_current_item = null;
var custom_field_counter = 1;
var custom_field_template = [];

var selected_item_element = null;

$(document).ready(function(){
  $("#modal-cms-custom-field-container").modal({
    show : false,
  })
  .on("show.bs.modal", function(e){
    cms_cf_current_item = null;
    cms_cf_show_container($("#cms-custom-field-container-options"));
  })
  .appendTo('body');

  /* Initialize modal and move the element at the end of the <body> */
  $("#modal-custom-fields-save-template").modal({
    keyboard  : false,
    backdrop  : 'static',
    show      : false,
  })
  .on("shown.fn.modal", function(e){
  })
  .appendTo('body');
  
  $("#modal-custom-fields-select-template").modal({
    keyboard  : false,
    backdrop  : 'static',
    show      : false,
  })
  .on("shown.fn.modal", function(e){
  })
  .appendTo('body');

  $("#modal-custom-field-select-add-item").modal({
    keyboard  : false,
    backdrop  : 'static',
    show      : false,
  })
  .on("shown.fn.modal", function(e){
  })
  .appendTo('body');

  $("#modal-custom-field-gallery-editor").modal({
    keyboard  : false,
    backdrop  : 'static',
    show      : false,
  })
  .on("shown.fn.modal", function(e){
  })
  .on("hidden.fn.modal", function(e){
    selected_item_element = null
  })
  .appendTo('body');

  $("#modal-custom-field-address").modal({
    keyboard  : false,
    backdrop  : 'static',
    show      : false,
  })
  .on("shown.fn.modal", function(e){
    // setTimeout(function(){ initializeMap(); }, 1000); 
  })
  .appendTo('body');

  initializeMap();

  var cf_item_ctr = 0;
  var cf_item_row = null;
  $.each($(".custom-field-template"), function(){
    var t = $(this).data('title');
    var v = $(this).attr('id');

    if (cf_item_ctr++%3==0) {
      cf_item_row = $('<div class="row-fluid"></div>');
      cf_item_row.appendTo($("#cms-cf-container-add-element .cf-item-options"));
    }

    var span = $('<div class="span4"></div>');
    span.appendTo(cf_item_row);

    var btn = $('<button class="btn btn-success cms-cf-item">'+ t +'</button>');
    btn.data('type', v);
    btn.appendTo(span);
    btn.click(function(){
      var item_type = $(this).data('type');

      if ($('#' + item_type).length > 0 ) {
        // var item = $('#' + item_type).tmpl({ field_id : cf_item_ctr++ }).appendTo( cms_cf_current_item );
        cms_cf_add_field(item_type, "", "", "");
      }else{
        notification("Contact Form Item", "Missing \"" + item_type + "\" template.", "gritter-error");
      }

      $("#modal-cms-custom-field-container").modal('hide');
    });

    // $("#modal-type-custom-field").append('<option value="'+ v +'">'+ t +'</option>');
  });

  $("#cms-custom-field-container-toggler").change(function(e){
    if ( $(this).is(":checked") ) {
      $("#cms-custom-field-container").removeClass('active');
    }else{
      $("#cms-custom-field-container").addClass('active');
    }
  });

  cms_cf_initialize_add_btn($(".cms-custom-field-container .container-add-btn"));
  cms_cf_current_item = $("#cms-custom-field-container > .row-fluid > .span12 > .cms-content")[0]

  $(".cms-cf-confirm-dismiss").click(function(e){
    cms_cf_show_container($("#cms-custom-field-container-options"))
  });
  $("#cms-cf-show-add-row").click(function(e){
    cms_cf_show_container($("#cms-cf-container-add-row"))
  });
  $("#cms-cf-show-add-element").click(function(e){
    cms_cf_show_container($("#cms-cf-container-add-element"))
  });

  $("#cms-cf-confirm-add-row").click(function(e){
    var row_count = $("#cms-cf-container-add-row").find(".cms-cf-value").val();
    var cic = $('<div class="cf-item-container cf-item"></div>').appendTo(cms_cf_current_item)

    var btn_del = $('<div class="cms-content-remove"><a href="javascript:void(0)" class="container-remove-btn text-error"><i class="icon icon-trash"></i></a></div>').appendTo(cic);
    var row = $('<div class="row-fluid"></div>').appendTo(cic);

    btn_del.find('.container-remove-btn').click(function(e){
      var container = $(this).closest('.cf-item-container');
      bootbox.confirm("Do you want to delete selected row?", function(result){
        if (result) {
          container.remove();
          notification("Custom Field Row", "A row has beed removed from the custom field layout", "gritter-warning");
        }
      });
    })

    for (var i = 0; i < row_count; i++) {
      var span    = $('<div class="span'+ (12/row_count) +'"></div>');
      var item    = $('#tmpl-cms-cf-controls').tmpl({}).appendTo( span );

      span.appendTo(row);

      cms_cf_initialize_add_btn(item.find(".container-add-btn"));
    }

    $("#modal-cms-custom-field-container").modal('hide');
  });

  cms_cf_set_custom_fields();

  $("#cf-test-btn").click(function(e){
    var data = cms_cf_recur_get_data($("#cms-custom-field-container > .row-fluid > div > .cms-content"));
  });

  /* Buttons */
  $("#cms-cf-btn-export").click(function(e){
    e.preventDefault();
    $("#modal-custom-fields-save-template").modal('show');
  });

  $("#cms-cf-btn-import").click(function(e){
    e.preventDefault();
    
    jQuery.post(CONFIG.get('URL')+'products/product_custom_fields/',{
      action    : 'get_custom_field_template',
    }, function(response){
      var response_data = JSON.parse(response);

      $("#modal-custom-field-template-select").html("");
      custom_field_template = {};

      $.each(response_data, function(k, v){
        $("#modal-custom-field-template-select").append($("<option value='"+ v.id +"'>"+ v.value +"</option>"));
        custom_field_template[v.id] = {
          "name"  : v.value,
          "meta"  : JSON.parse(v.meta),
        };
      });
    });

    $("#modal-custom-fields-select-template").modal('show');
  });

  /* Process: save custom field  */
  $("#modal-btn-confirm-custom-field").click(function(e){
    var template_name = $("#modal-custom-field-template-name").val();
    var field_data    = [];

    field_data = cms_cf_recur_get_layout_data( $("#cms-custom-field-container > .row-fluid > div > .cms-content") );

    jQuery.post(CONFIG.get('URL')+'products/product_custom_fields/',{
      action    : 'save_custom_field_template',
      name      : $("#modal-custom-field-template-name").val(),
      template  : JSON.stringify(field_data),
    }, function(response){
      var response_data = JSON.parse(response);

      if (response_data.success !== undefined && response_data.success == true) {
        notification("Custom Field Template", "Successfully saved Custom Field Template", "gritter-success");
      }else{
        if (response_data.message !== undefined) {
          notification("Custom Field Template", response_data.message, "gritter-error");
        }else{
          notification("Custom Field Template", "Unknown error occur while saving template", "gritter-error");
        }
      }

      $("#modal-custom-fields-save-template").modal('hide');
    });
  });
  /* Process: display selected custom field template */
  $("#modal-btn-confirm-custom-field-template").click(function(e){
    var selected_template = $("#modal-custom-field-template-select").val();
    var cf    = custom_field_template[selected_template];
    var meta  = cf.meta;
    var curr  = cms_cf_get_data();

    /* @template_select */
    $("#custom_fields .field-container").html("");
    var contianer = $("#cms-custom-field-container > .row-fluid > .span12 > .cms-content");
    contianer.html(""); /* Clear current section */
    console.log(meta)
    cms_cf_recur_items_template(meta, curr, contianer);

    $("#modal-custom-fields-select-template").modal('hide');
  });

  $("#modal-custom-field-template-select").change(function(e){
    console.log(custom_field_template[$(this).val()]);
  });

  lightbox.option({
      'resizeDuration': 400,
      'fadeDuration': 400,
      'imageFadeDuration  ': 200,
      'wrapAround': true
    })
});


function cms_cf_recur_items_template(data, curr, container){
  var holder = container;

  $.each(data, function(k, v){
    if (v.type !== undefined) {
      v.name  = curr[v.key] !== undefined ? curr[v.key].name : v.name;
      v.value = curr[v.key] !== undefined ? curr[v.key].value : v.value;

      var t = v.type;

      cms_cf_add_field("tag-" + t, v.key, v.name, v.value, v.option !== undefined ? v.option : '', false, holder)
    }else if (v.length > 0){
      var row_count = (12 / v.length);

      var cic = $('<div class="cf-item cf-item-container"></div>').appendTo(container);

      var btn_del = $('<div class="cms-content-remove"><a href="javascript:void(0)" class="container-remove-btn text-error"><i class="icon icon-trash"></i></a></div>').appendTo(cic);
      btn_del.find('.container-remove-btn').click(function(e){
        var c = $(this).closest('.cf-item-container');
        bootbox.confirm("Do you want to delete selected row?", function(result){
          if (result) {
            c.remove();
            notification("Custom Field Row", "A row has beed removed from the custom field layout", "gritter-warning");
          }
        });
      });

      var row = $('<div class="row-fluid"></div>');
      row.appendTo(cic);

      $.each(v, function(kk, vv){
        var span    = $('<div class="span'+ (row_count) +'"></div>');
        span.appendTo(row);

        var item    = $('#tmpl-cms-cf-controls').tmpl({}).appendTo( span );
        var content = span.find(".cms-content");

        cms_cf_recur_items_template(vv, curr, content)
        cms_cf_initialize_add_btn(item.find(".container-add-btn"));
      });
    }
  });

  return holder;
}

function cms_cf_initialize_add_btn(the_add_btn){
  the_add_btn.click(function(e){
    var content = $(this).closest('.cms-controls').siblings(".cms-content")[0];
    $("#modal-cms-custom-field-container").modal('show');
    cms_cf_current_item = content;
  });
}

function cms_cf_show_container(container){
  $(".cms-cf-container").hide();
  container.show();
}

var cf_queue = {};
function cms_cf_set_custom_fields(){
  /* Clear custom fields */
  var pid   = $("#product_id").val();

  jQuery.post(CONFIG.get('URL')+'products/product_custom_fields/',{
    action      : 'get_custom_field',
    product_id  : pid,
    language    : $("#language").val(),
  }, function(response){
    var response = JSON.parse(response);
    var data = response.cf_data;

    $("#custom-field-id").val(response.cf_id);

    cms_cf_recur_items(data, cms_cf_current_item);
  });
}
function cms_cf_recur_items(data, container){
  var holder = container;

  $.each(data, function(k, v){
    if (v.type !== undefined) {
      var t = v.type; //.replace('tag-','');

      cms_cf_add_field("tag-" + t, v.key, v.name, v.value, v.option !== undefined ? v.option : '', false, holder)
    }else if (v.length > 0){
      var row_count = (12 / v.length);

      var cic = $('<div class="cf-item cf-item-container"></div>').appendTo(container);

      var btn_del = $('<div class="cms-content-remove"><a href="javascript:void(0)" class="container-remove-btn text-error"><i class="icon icon-trash"></i></a></div>').appendTo(cic);
      btn_del.find('.container-remove-btn').click(function(e){
        var c = $(this).closest('.cf-item-container');
        bootbox.confirm("Do you want to delete selected row?", function(result){
          if (result) {
            c.remove();
            notification("Custom Field Row", "A row has beed removed from the custom field layout", "gritter-warning");
          }
        });
      });

      var row = $('<div class="row-fluid"></div>');
      row.appendTo(cic);

      $.each(v, function(kk, vv){
        var span    = $('<div class="span'+ (row_count) +'"></div>');
        span.appendTo(row);

        var item    = $('#tmpl-cms-cf-controls').tmpl({}).appendTo( span );
        var content = span.find(".cms-content");

        cms_cf_recur_items(vv, content)
        cms_cf_initialize_add_btn(item.find(".container-add-btn"));
      });
    }
  });

  return holder;
}
function saveCustomFields(product_id, fn_callback){
  if (cms_cf_validate_custom_fields()) {
    var data  = [];
    var pid   = product_id;
    var cfid  = $("#custom-field-id").val();

    data = cms_cf_recur_get_data($("#cms-custom-field-container > .row-fluid > div > .cms-content"));

    jQuery.post(CONFIG.get('URL')+'products/product_custom_fields/',{
      action        : 'save_custom_field',
      product_id    : pid,
      product_cf_id : cfid,
      language      : $("#language").val(),
      data          : JSON.stringify(data),
    }, function(response){
      notification("Product Custom Fields", "Save Successfully Custom Fields", "gritter-success");
      fn_callback();
    });
  }else{
    notification("Product Custom Fields", "Error found on custom fields...", "gritter-error");
    fn_callback();
  }
}
function cms_cf_validate_custom_fields(){
  var isValid = true;

  $.each($("#cms-custom-field-container .accordion-group").find(' > .accordion-body .title'), function(){
    var s = $(this).val();

    if (s == '') {
      isValid = false
    }else{
      if (existFieldName($(this))) {
        isValid = false;
      }
    }
  });

  return isValid;
}
function existFieldName(title_field){
  var isExist = false;
  
  $.each(title_field.closest(".accordion-group").siblings(".accordion-group"), function(e){
    if ($(this).find('.key').val() == title_field.val()) {
      isExist = true;
    };
  });

  return isExist;
}

function cms_cf_get_data(){
  var data = {};
  var container = $("#cms-custom-field-container");

  $.each(container.find('.accordion-group'), function(e){
    var type    = $(this).data('type');
    var name    = $(this).find('.title').val();
    var key     = $(this).find('.key').val();
    var id      = $(this).data('id');
    var value   = "";
    var option  = [];

    if (type == 'text'|| type == 'textarea-simple') {
      value = $(this).find('.value').val();
    }else if (type == 'switch') {
      value = $(this).find('.value').is(":checked") ? "Y" : "N";
    }else if (type == 'tags') {
      value = $(this).find('.value').val();
      option = [];
      $.each($(this).find("option"), function(){
        option.push($(this).attr('value'));
      });
    }else if (type == 'dropdown') {
      value = $(this).find('.value').val();
      option = [];
      $.each($(this).find("option"), function(){
        option.push($(this).attr('value'));
      });
    }else if (type == 'textarea') {
      value = tinyMCE.get("custom-field-textarea-" + id).getContent();
    }else if (type == 'gallery') {
      value = [];
      $.each($(this).find(".galley-list").find(".tag-gallery-item"), function(){
        var x = {
          id    : $(this).find(".item-id").val(),
          name  : $(this).find(".item-name").val(),
          desc  : $(this).find(".item-desc").val(),
          img   : $(this).find(".item-image").attr('src'),
        }
        value.push(x);
      });
    }else if (type == 'address') {
      value = {
        addr  : $(this).find(".value").val(),
        lat   : $(this).find(".map-lat").val(),
        lng   : $(this).find(".map-lng").val(),
      }
    }

    data[key] = {
      type    : type,
      key     : key,
      name    : name,
      value   : value,
      option  : option,
    };
  });

  return data;
}
function cms_cf_recur_get_data(container){
  var data = [];

  // console.log($(this).find(' > .cf-item').length);

  $.each(container.find(' > .cf-item'), function(e){
    if ($(this).hasClass('accordion-group')) {
      var type    = $(this).data('type');
      var name    = $(this).find('.title').val();
      var key     = $(this).find('.key').val();
      var value   = "";
      var id      = $(this).data('id');
      var option  = [];

      if (type == 'text'|| type == 'textarea-simple') {
        value = $(this).find('.value').val();
      }else if (type == 'switch') {
        value = $(this).find('.value').is(":checked") ? "Y" : "N";
      }else if (type == 'tags') {
        value = $(this).find('.value').val();
        option = [];
        $.each($(this).find("option"), function(){
          option.push($(this).attr('value'));
        });
      }else if (type == 'dropdown') {
        value = $(this).find('.value').val();
        option = [];
        $.each($(this).find("option"), function(){
          option.push($(this).attr('value'));
        });
      }else if (type == 'textarea') {
        value = tinyMCE.get("custom-field-textarea-" + id).getContent();
      }else if (type == 'gallery') {
        value = [];
        $.each($(this).find(".galley-list").find(".tag-gallery-item"), function(){
          var x = {
            id    : $(this).find(".item-id").val(),
            name  : $(this).find(".item-name").val(),
            desc  : $(this).find(".item-desc").val(),
            img   : $(this).find(".item-image").attr('src'),
          }
          value.push(x);
        });
      }else if (type == 'address') {
        value = {
          addr  : $(this).find(".value").val(),
          lat   : $(this).find(".map-lat").val(),
          lng   : $(this).find(".map-lng").val(),
        }
      }

      data.push({
        type    : type,
        key     : key,
        name    : name,
        value   : value,
        option  : option,
      });
    }else if ($(this).hasClass('cf-item-container')) {
      var d = [];
      $.each($(this).find(" > .row-fluid > div "), function(){
        d.push(cms_cf_recur_get_data($(this).find(' > .cms-content')));
      });
      data.push(d);
    }
  });

  return data;
}
function cms_cf_recur_get_layout_data(container){
  var data = [];

  $.each(container.find(' > .cf-item'), function(e){
    if ($(this).hasClass('accordion-group')) {
      var type    = $(this).data('type');
      var name    = $(this).find('.title').val();
      var key     = $(this).find('.key').val();
      var value   = "";
      var id      = $(this).data('id');
      var option  = [];

      if (type == 'tags') {
        $.each($(this).find('.value').find('option'), function(e){
          option.push($(this).attr('value'));
        });
      }

      data.push({
        type    : type,
        key     : key,
        name    : name,
        value   : value,
        option  : option,
      });
    }else if ($(this).hasClass('cf-item-container')) {
      var d = [];
      $.each($(this).find(" > .row-fluid > div "), function(){
        d.push(cms_cf_recur_get_data($(this).find(' > .cms-content')));
      });
      data.push(d);
    }
  });

  return data;
}
function cms_cf_generate_field(selector, data, container){
  /*  */
  return $(selector).tmpl(data).appendTo( container );
}
function cms_cf_add_field(type, key, name, value, option, collapsed, container){
  type = type;
  var id = custom_field_counter++;
  var c = container !== undefined ? container : cms_cf_current_item;

  var test_field = cms_cf_generate_field('#' + type, { 
    field_id    : id ,
    field_key   : key, 
    field_name  : name, 
    field_val   : value !== undefined ? value : '', 
    field_opt   : option !== undefined ? option : ''
  }, c);

  test_field.find('.title').val(name !== undefined ? name : "");
  test_field.find('.value').val(value !== undefined ? value : "");
  test_field.find('.key').val(key !== undefined ? key : "");

  test_field.find('.key').val(key !== undefined ? key : "");

  test_field.find('.title')
  .blur(function(){
    var t = $(this).val();
    var n = slugify(t);

    if (n == '') {
      $(this).closest(".accordion-group").find('.custom-field-name-warning').text("[ Missing field name ]");
    }else{

    }

    $(this).closest(".accordion-group").find(".key").val(n);
    $(this).closest(".accordion-group").find('.accordion-heading>a>b').text(t);
    $(this).closest(".accordion-group").find('.active-title').text(t);

    cms_cf_reset_warning();
  })
  .focus(function(){
    $(this).closest(".accordion-group").find('.custom-field-name-warning').text("");
  })
  .focus();

  test_field.find(".key")
  .blur(function(){
    cms_cf_reset_warning();
  });

  if (type == 'tag-text') {
    test_field.find('.value').val( value );
  }else if (type == 'tag-textarea-simple'){
    test_field.find('.value').val( value );
  }else if (type == 'tag-textarea'){
    var i = test_field.find('.custom-field-textarea').attr('id');
    if ($("#"+ i).length > 0) {
      tinyMCE.init({
        selector  : "#"+ i,
        menubar   : " view edit format table tools",
        width     : '100%',
        height    : 200,
        init_instance_callback : function(editor) {
          tinyMCE.get("custom-field-textarea-"+ id).setContent(value);
        },
        toolbar:[
        "formatselect undo redo alignleft aligncenter alignright alignjustify ",
        "bullist numlist outdent indent cmsmedia link "
        ],
        plugins: [
          "paste advlist autolink link image lists charmap print preview hr anchor pagebreak ",
          "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
          "imageuploader cmsmedia",
          "textcolor colorpicker","nonbreaking table code"
        ],
        external_plugins:{
          'imageuploader' :'plugins/imageuploader/editor_plugin_src.js',
          'cmsmedia'      :'plugins/cmsmedia/editor_plugin_src.js',
        },
        relative_urls: false,
        convert_urls: false,
        nonbreaking_force_tab:true,
        tools: "inserttable",
        verify_html: false
      });
    }
  }else if (type == 'tag-dropdown'){
    test_field.find('.custom-field-select').val(value);
    test_field.find('.custom-field-select-add-item').click(function(e){
      var curr_id = $(this).closest(".accordion-group").attr("id");
      var curr_selection = $("#" + curr_id).find(".custom-field-select");
      var curr = [];

      $("#modal-custom-field-list-currrent-group").val(curr_id);

      $.each(curr_selection.find("option"), function(k, v){
        curr.push($(this).attr("value"))
      });

      $("#modal-custom-field-list-item").val(curr.join("\n"));
      $("#modal-custom-field-select-add-item").modal('show');
      $("#modal-btn-confirm-select-items").click(function(e){
        var items = $("#modal-custom-field-list-item").val();
        var curr_id = $("#modal-custom-field-list-currrent-group").val();
        var curr_selection = $("#" + curr_id).find(".custom-field-select");
        curr_selection.html("");

        $.each((items.trim()).split("\n"), function(k, v){
          curr_selection.append('<option value="'+ v +'">'+ v +'</option>')
        });

        $("#modal-custom-field-select-add-item").modal('hide');
      });
    });
  }else if (type == 'tag-tags'){
    var item = test_field.find(".value");

    item.chosen({
      width : "100%"
    })
    .siblings('.chosen-container').
    find(".chosen-choices input")
    .focus(function(){
      $(this).closest(".accordion-body").css("overflow", "unset")
    })

    test_field.find(".accordion-toggle").click(function(){
      $(this).closest(".accordion-group").find(".accordion-body").css("overflow", "hidden")
    });

    test_field.find('.custom-field-select').val(value).trigger("chosen:updated");
    test_field.find('.custom-field-select-add-item').click(function(e){
      var curr_id = $(this).closest(".accordion-group").attr("id");
      var curr_selection = $("#" + curr_id).find(".custom-field-select");
      var curr = [];

      $("#modal-custom-field-list-currrent-group").val(curr_id);

      $.each(curr_selection.find("option"), function(k, v){
        curr.push($(this).attr("value"))
      });

      $("#modal-custom-field-list-item").val(curr.join("\n"));
      $("#modal-custom-field-select-add-item").modal('show');
      $("#modal-btn-confirm-select-items").click(function(e){
        var items = $("#modal-custom-field-list-item").val();
        var curr_id = $("#modal-custom-field-list-currrent-group").val();
        var curr_selection = $("#" + curr_id).find(".custom-field-select");
        curr_selection.html("");

        $.each((items.trim()).split("\n"), function(k, v){
          curr_selection.append('<option value="'+ v +'">'+ v +'</option>')
        });

        curr_selection.trigger("chosen:updated")

        $("#modal-custom-field-select-add-item").modal('hide');
      });
    });
  }else if (type == 'tag-switch'){
    if (value == "Y") {
      var item = test_field.find(".value");
      if (!item.is(":checked")) {
        item.trigger("click");
      }
    }
  }else if (type == 'tag-gallery'){
    cms_cf_init_gallery(test_field);

    $.each(value, function(k, v){
      var item_name = v.name;
      var item_desc = v.desc;
      var item_id   = v.id;
      var item_url  = v.img;
      var curr_item = "#" + test_field.attr('id') + "-gallery-list";

      var item = $('#tag-gallery-item').tmpl({}).appendTo( curr_item )

      item.find(".tag-gallery-item-btn-edit").data('value', item_id).click(function(e){
        var _item = $(this).closest('li');
        selected_item_element = _item;
        var id    = _item.find('.item-id').val();
        var url   = _item.find('.item-image').attr('src');
        var name  = _item.find('.item-name').val();
        var desc  = _item.find('.item-desc').val();

        $("#gallery-image-previewer").attr('src', url);

        $("#modal-custom-field-gallery-editor").modal('show');
        $("#current-gallery-item-id").val(_item.closest('.accordion-group').attr('id'));
        $("#gallery-item-id").val(id);
        $("#gallery-item-name").val(name);
        $("#gallery-item-desc").val(desc);

        // $.post(CONFIG.get('URL') + "media/get_files/",{
        //   type : 'image'
        // },function(response) {
        //   var data = JSON.parse(response);
        //   $("#gallery-tab-image .galley-list").html("");
        //   $.each(data, function(k, v){
        //     var item = $('#modal-gallery-items').tmpl({
        //       id  : v.id, 
        //       url : v.url, 
        //     }).prependTo( "#gallery-tab-image .galley-list" );

        //     item.find(".gallery-item").click(function(e){
        //       $(this).closest('li').addClass('active').siblings("li").removeClass('active');
        //       $("#gallery-item-id").val($(this).data('value'));
        //       $("#gallery-item-url").val($(this).find("img").attr('src'));
        //     });

        //     if (v.id == id) {
        //       item.find(".gallery-item").trigger('click')
        //     }
        //   });
        // });
      });
      item.find(".tag-gallery-item-btn-remove").data('value', item_id).click(function(e){
        var _item = $(this).closest("li");
        bootbox.confirm("Do you continue deleting selected item?", function(result){
          if (result) {
            _item.fadeOut(1000, function(){
              _item.remove();
            });
          }
        });
      });

      item.find(".item-name").val(item_name);
      item.find(".item-desc").val(item_desc);
      item.find(".item-id").val(item_id);

      var img = new Image();
      img.onload = function(){
        if (this.width > this.height) {
          item.find(' > a').addClass('layout-2');
        }

        item.find(".item-image").attr('src', this.src);
        item.find(" > a").attr('href', this.src);
      }
      img.src = item_url;

      item.fadeIn(1000);
    });
  }else if (type == 'tag-address'){
    test_field.find('.value').val( value.addr );
    test_field.find('.map-lng').val( value.lng );
    test_field.find('.map-lat').val( value.lat );
    test_field.find('.value').change( function(){
      var item = $(this).closest(".accordion-group");
      var addr = $(this).val();

      item.find(".map-lat").val("");
      item.find(".map-lng").val("");

      item.find(".map-loading").show();

      geocoder.geocode({
        'address': addr
      }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
            item.find(".map-lat").val(results[0].geometry.location.lat());
            item.find(".map-lng").val(results[0].geometry.location.lng());
          } else {
            notification("Map", "No results found", "gritter-error");
          }
        } else {
          notification("Map", "Geocode was not successful for the following reason: " + status, "gritter-error");
        }

        item.find(".map-loading").hide()
      });
    });

    test_field.find('.btn-check-map').click(function(e){
      var item  = $(this).closest(".accordion-group");
      var addr  = item.find(".value").val();
      var lat   = item.find(".map-lat").val();
      var lng   = item.find(".map-lng").val();

      $("#current-map-id").val( item.attr('id') );
      $("#map-item-address").val( addr );
      $("#map-item-coordinate-lat").val( lat );
      $("#map-item-coordinate-lng").val( lng );
      navigateMap(addr, lat, lng);

      $("#modal-custom-field-address").modal('show');
    });
  }

  test_field.find(".accordion-toggle").addClass('collapsed');
  test_field.find(".accordion-body").removeClass('in');

  test_field.find('.remove-custom-field').click(function(){
    bootbox.confirm("Do you continue deleting selected item? <br /><i>Action will take effect after saving</i>.", function(result){
      if (result) {
        test_field.remove();
      }
    });
  });

  return test_field;
}
function cms_cf_reset_warning(){
  $("#cms-custom-field-container .accordion-group").find('.custom-field-name-warning').text("")

  $.each($("#cms-custom-field-container .accordion-group"), function(){
    var key   = $(this).find(".key");
    var title = $(this).find(".title").val();

    var s = key.val();
    var heading = key.closest(".accordion-group").find('.accordion-heading>a>b');
    heading.html(title);

    if (s != '') {
      if (cms_cf_exist_field_name(key)) {
        heading.html(title + ' <i class="icon icon-ban-circle text-error" data-rel="tooltip" title="[ Already used in other field ]"></i>');
        heading.find('i[data-rel=tooltip]').tooltip();
      }
    }else{
      heading.html(title + ' <i class="icon icon-ban-circle text-error" data-rel="tooltip" title="[ Missing field name ]"></i>');
        heading.find('i[data-rel=tooltip]').tooltip();
    }
  });
}
function cms_cf_exist_field_name(title_field){
  var isExist = false;
  var current_container_id = title_field.closest(".accordion-group").attr('id');
  
  $.each($("#cms-custom-field-container").find(".accordion-group"), function(e){
    if (current_container_id != $(this).attr('id') && $(this).find('.key').val() == title_field.val()) {
      isExist = true;
    };
  });

  return isExist;
}
function cms_cf_init_gallery(field_group){
  if (field_group.find('.galley-list')) {
    var dropzone = field_group.find(".file-drop-zone");

    field_group.on("dragenter",function(e){
      dropzone.stop().fadeIn(200);
    });

    dropzone.on("dragleave", function(e){
      dropzone.stop().fadeOut(200);
    })
    .on("drop", function(e){
      e.stopPropagation()
      e.preventDefault()
      dropzone.stop().fadeOut(200);

      var f = e.originalEvent.dataTransfer.files;
      var c = field_group.find(".galley-list");

      var cms_uploader = new CMSMediaUploader();
      cms_uploader.files = f;
      cms_uploader.container = c;
      cms_uploader.upload_files();
    });

    var add_btn = field_group.find(".btn-gallery-add-item");
    add_btn.click(function(){
      $(this).closest('.accordion-group').find('.gallery-file-input').trigger('click');
    });

    field_group.find(".gallery-file-input").change(function(e){
      var f = $(this)[0].files;
      var c = field_group.find(".galley-list");

      var cms_uploader = new CMSMediaUploader();
      cms_uploader.files = f;
      cms_uploader.container = c;
      cms_uploader.upload_files();
    });
  }
}

/* Objects */
/* Gallery: Upload image Start */
function CMSMediaUploader(){
  this.files = [];
  this.upload_queue = [];
  this.upload_files = function(){
    if (this.files.length > 0) {
      var upload_queue = this.upload_queue;

      $.each(this.files, function(k, file){
        var file_info = {
          name : file['name'],
          size : cms_function.fn.bytesToSize(file['size']),
          type : file['type'].split('/')[0],
        }

        /* add to overall queue */
        upload_queue.push({
          file    : file,
          data    : file_info,
          // element : item,
        });
      });

      this.start_upload();
    }
  }
  this.start_upload = function(){
    if (this.upload_queue.length > 0) {
      // var file_limit = upload_setting['actual_max_upload_size'];
      var uploader = this;
      var file = this.upload_queue.shift();
      var fd = new FormData();    
      fd.append( 'file', file.file );
      fd.append( 'get_id', true );

      if (file.file.size) {
        // file.element.find('.file-size').addClass('text-success');
        $.ajax({
          url: CONFIG.get('URL')+'media/upload/',
          data : fd,
          processData: false,
          contentType: false,
          type: 'POST',
          success: function(response) {
            uploader.success( response );
            uploader.start_upload();
          },
          xhr: function () {
            var xhr = new window.XMLHttpRequest();

            xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                percentComplete = parseInt(percentComplete * 100);
                // console.log(percentComplete);
                // file.element.find('.progress .bar').css('width', percentComplete + "%")
              /*progressWrap.attr('data-percent', percentComplete)
              progressBar.css('width', percentComplete + '%')*/
            }
          }, false);

            return xhr;
          },
          error: function() { },
          progress: function(e) {
            /*make sure we can compute the length*/
            if(e.lengthComputable) {
              /*calculate the percentage loaded*/
              var pct = (e.loaded / e.total) * 100;
              /*log percentage loaded*/
              // console.log(pct);
            }
            /*this usually happens when Content-Length isn't set*/
            else {
              console.warn('Content Length not reported!');
            }
          },
        });
      }else{
        this.start_upload();
      }
    }
  }
  this.success = function(file){
    var file = JSON.parse(file);
    var item = $('#tag-gallery-item').tmpl({}).appendTo( this.container );
    item.fadeIn(200);
    item_id = file.id;

    var img = new Image();
    img.onload = function(){
      if (this.width > this.height) {
        item.find(' > a').addClass('layout-2');
      }

      item.find('img').attr('src', this.src);
      item.find(" > a").attr('href', this.src);
    }
    img.src = file.url;

    item.find(".tag-gallery-item-btn-edit").data('value', item_id).click(function(e){
      var _item = $(this).closest('li');
      selected_item_element = _item;
      var id    = _item.find('.item-id').val();
      var url   = _item.find('.item-image').attr('src');
      var name  = _item.find('.item-name').val();
      var desc  = _item.find('.item-desc').val();

      $("#gallery-image-previewer").attr('src', url);

      $("#modal-custom-field-gallery-editor").modal('show');
      $("#current-gallery-item-id").val(_item.closest('.accordion-group').attr('id'));
      $("#gallery-item-id").val(id);
      $("#gallery-item-name").val(name);
      $("#gallery-item-desc").val(desc);
    });
    item.find(".tag-gallery-item-btn-remove").data('value', item_id).click(function(e){
      var _item = $(this).closest("li");
      bootbox.confirm("Do you continue deleting selected item?", function(result){
        if (result) {
          _item.fadeOut(1000, function(){
            _item.remove();
          });
        }
      });
    });
  }
}
