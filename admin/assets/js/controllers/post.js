var slug = '';
var meta = 'N';
var seo_title_limit = 200;
var seo_description_limit = 260;

$(document).ready(function(){
  var action = $('#action').val();
  if(action == 'add'){
    load_tinyMCE(0);
    $('#btn_save_post').click(function (){
      save_post('add');
    });
  }else if(action == "edit"){
    load_archived();
    var id = $('#hidden_id').val();
    load_tinyMCE(id);
    $('#btn_save_post').click(function (){
      save_post('save');
    });
  }else if(action == 'manage'){
    load_table();
  }

  $("#seo_title_limit_label").html(seo_title_limit);
  $("#seo_description_limit_label").html(seo_description_limit);

  $('#id-input-file-3').ace_file_input({
    no_file:'No File ...',
    btn_choose:'Choose',
    btn_change:'Change',
    droppable:false,
    onchange:null,
    before_remove : function() {
      fn_reset_feature_image_field();
      return true;
    },
  }).on('change', function(){
    var ext = $('#id-input-file-3').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1){
      globalerror = true;
      jQuery('#messageAlertForProductImage').append(alertMessage('Invalid Image File','error','errorImage'));
    }
    else
      globalerror = false;
  });

  $("#btn-cropper-edit-thumbnail").click(function(){
    fn_show_cropper();
  });

  $('#cropper-modal').modal({
    show : false,
  }).on('shown.bs.modal', function () {

    $.post(CONFIG.get('URL')+'post/upload_featured_image/',{
      action:'get-image-url-info', 
      post_id: $("#hidden_id").val()
    }, function(response, status){
      var json_response = JSON.parse(response);
      var image = $("#modal_picture");

      image.attr('src', $("#featured_image").attr('alt'));
      image.cropper({
        aspectRatio: 234 / 155,
        guides : false,
        viewMode: 3,
        responsive: false,
        minContainerHeight : 200,
        built: function () {
          var c_data = typeof json_response['featured_image_crop_data'] != "undefined" ? json_response['featured_image_crop_data'] : {};

          if (typeof c_data['canvasData'] != 'undefined') {
            var c = JSON.parse(c_data['canvasData']);
            image.cropper('setCanvasData', c);
          }
          if (typeof c_data['cropBoxData'] != 'undefined') {
            var c = JSON.parse(c_data['cropBoxData']);
            image.cropper('setCropBoxData', c);
          }
        }
      });
    });
  }).on('hidden.bs.modal', function () {
    var image = $("#modal_picture");
    // canvasData = image.cropper('getCanvasData');
    // cropBoxData = image.cropper('getCropBoxData');
    image.cropper('destroy');
  });

  $("#btn-modal-save-cropped-featured-image").click(function(){
    var image = $("#modal_picture");
    upload_cropped_featured_image( $("#hidden_id").val(), image );
  });
  $('#status').chosen({width : '100%'}).siblings('.chosen-container').find('.chosen-search').hide();

  $("#language").chosen();

  if ($("#action").val() != 'add') {
    $("#language").change(function(){
      load_data($('#hidden_id').val());
    });
  }

  $('.chosen-search').remove();

  $("#btn-delete-translation").click(function(e){
    var selected_translation = $("#language").find('option[value='+ $("#language").val() +']').text();
    bootbox.confirm("Are you sure you want to delete selected translation [<b>"+ selected_translation +"</b>]?", function(result){
      if (result) {
        jQuery.post(CONFIG.get('URL')+'post/remove_translation/',{
          page_id : $("#hidden_id").val(),
          lang : $("#language").val(),
        },
        function(response) {
          if (cms_function.isJSON(response)) {
            response = JSON.parse(response);

            if (typeof response['status'] != 'undefined' && response['status'] == 'success') {
              notification("Page Translation", response['message'], "gritter-success");
            }else{
              notification("Page Translation", response['message'], "gritter-error");
            }
            load_data($('#hidden_id').val());
          }else{
            notification("Page Translation", "Unable to read response", "gritter-error");
          }
        });
      }
    });
  });

  $("#loading-2").modal({
    'backdrop' : 'static',
    'keyboard' : false,
    'show' : false,
  });
  $("#field-chosen-author").chosen({
    width : '200px'
  });
});
function changeImage(input){
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#featured_image')
      .attr('src', e.target.result)
    };

    reader.readAsDataURL(input.files[0]);

    $("#edit_photo_holder").hide();
  }else{
    if ($('#featured_image').attr('src') != "") {
      $("#edit_photo_holder").show();
    }else{
      $("#edit_photo_holder").hide();
    }
  }
}
function load_archived(){
  $('#newer').removeClass('btn-info');
  $('#older').removeClass('btn-info');

  $('#newer').unbind('click');
  $('#older').unbind('click');

  $.post(CONFIG.get('URL')+'post/get_archiveds',{action:'get', id:$('#hidden_id').val()}, function(response,status){
    var obj = JSON.parse(response);
    var count = 0;
    if($.isEmptyObject(obj) == false){
      count = $.map(obj['archived'], function(n, i) { return i; }).length;

      $('#older').addClass('btn-info');
      $('#older').find('span').text(count);
      $('#newer').find('span').text(0);
    }else{
      $('#older').removeClass('btn-info');
    }

    var ct = count;
    $('#older').click(function(e){
      var text = $('#older').find('span');
      var ntext = $('#newer').find('span');
      var i =  text.text();

      var x = parseInt(ntext.text());
      if(i != 0){
        --i;
        ct--;
        text.text(i);
        ntext.text(++x);
        $('#newer').addClass('btn-info');
        change_content(obj['archived'][i]);
        if(i == 0){
          $('#older').removeClass('btn-info');
          $('#newer').addClass('btn-info');
        }
      }

      e.preventDefault();
    });
    $('#newer').click(function(e){
      ct++;

      var text = $('#older').find('span');
      var ntext = $('#newer').find('span');
      var i =  text.text();
      var x = ntext.text();
      if(x != 0){
        --x;
        text.text(++i);
        ntext.text(x);
        if(ct<count){
          change_content(obj['archived'][ct]);
        }
        else
          change_content(obj['default'][0]);
        if(ct >= count){
          $('#newer').removeClass('btn-info');
          $('#older').addClass('btn-info');
        }
      }

      e.preventDefault();
    });
  });
}
function change_content(data){

  update_new_tree(data['post_categories']);

  if(meta!='N')
    $('#seo_no_index').trigger('click');

  $('#parent_id').val(data['parent_id']);
  $('#parent_id').trigger('click');
  tinyMCE.activeEditor.setContent(data['post_content']);
  $('#txt_post_title').val(data['post_title']);
  $('#txt_url_slug').val(data['url_slug']);
  $('#txt_url_slug').trigger('blur');
  $('#seo_title').val(data['seo_title']);
  $('#seo_description').val(data['seo_description']);

  $('#page_template').val(data['page_template']);

  if(data['seo_no_index'] == 'Y')
    $('#seo_no_index').trigger('click');

  $('#status').val(data['status']);
}
function load_table(){
  $('#post_table').dataTable( {
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": CONFIG.get('URL')+"post/get_post",
    "aoColumns": [
    { "bSortable": false },
    null, 
    null,
    { "bSortable": false }
    ],

    "fnDrawCallback": function( oSettings ) {

      $.each($('.item-checkbox'), function(){
        $(this).parents('td').addClass('center');
        $(this).addClass('ace');
      });
      $.each($('.featured_product'), function(){
        $(this).parents('td').addClass('center');
      });


      $('[data-rel=tooltip]').tooltip();
    }
  } );
}
function load_data(id){
  $("#translation-status-container").html('<span class="badge badge-info">Identifying Translation Status</span>');
  $("#btn-delete-translation").hide();

  $.post(CONFIG.get('URL')+'post/get_data_by_id/',{action:'get', id: id, lang : $("#language").val() }, function(response, status){
    var result = JSON.parse(response);
    if($.isEmptyObject(result) == false){
      tinyMCE.activeEditor.setContent(result['post_content']);

      $('#txt_post_title').val(result['post_title']);
      $('#txt_url_slug').val(result['url_slug']);
      $('#status').val(result['status']);
      $('#seo_title').val(result['seo_title']);
      $('#seo_description').val(result['seo_description']);
      $('#title_char').text(result['seo_title'].length);
      $('#desc_char').text(result['seo_description'].length);

      $('#featured_image').attr('alt', result['featured_image_url']);
      $('#featured_image').attr('data-src', result['featured_image_crop']);

      if (typeof result['post_author'] != 'undefined') {
        $("#field-chosen-author").val(result['post_author']).trigger('chosen:updated');
      }else{
        $("#field-chosen-author").val(0).trigger('chosen:updated');
      }

      if(result['seo_no_index'] == 'Y' && !$('#seo_no_index').is(':checked')){
        $('#seo_no_index').trigger('click');
      }
      if(result['seo_no_index'] == 'N' && $('#seo_no_index').is(':checked')){
        $('#seo_no_index').trigger('click');
      }

      fn_set_feature_image();

      if (typeof result['translated'] != 'undefined') {
        if (result['translated'] == 'main') {
          $("#translation-status-container").html('<span class="badge badge-success">Main Language</span>');
        }else if (result['translated'] == 'translated') {
          $("#btn-delete-translation").show();
          $("#translation-status-container").html('<span class="badge badge-success">Translated</span>');
        }else if (result['translated'] == 'default') {
          $("#translation-status-container").html('<span class="badge badge-inverse">Default</span>');
        }else if (result['translated'] == 'not translated') {
          $("#translation-status-container").html('<span class="badge badge-important">No Translation</span>');
        }
      }else{
        $("#translation-status-container").html('<span class="badge badge-important">Error Identifying Language Status</span>');
      }

      if (result['featured_image_url'] != "") {
        $("#edit_photo_holder").show();
      }else{
        $("#edit_photo_holder").hide();
      }
      process_slug($('#txt_url_slug').val());
    }else{
      window.location.href = CONFIG.get('URL')+'post';
    }
  });
}
function change_value(){
  if(meta == 'N')
    meta = 'Y';
  else
    meta = 'N';
}
function save_post(val){
  $('#alert_page').empty();
  var data = {};
  if(validate()){
    var arr = [];
    $('.tree-selected').each(function(){
      arr.push($(this).find('.value').val());
    });
    data['id'] = $('#hidden_id').val();
    data['title'] = $('#txt_post_title').val();
    data['content'] = tinyMCE.get('content').getContent();
    data['url_slug'] = $('#txt_url_slug').val();
    data['status'] = $('#status').val();
    data['seo_title'] = $('#seo_title').val();
    data['seo_description'] = $('#seo_description').val();
    data['seo_no_meta'] = meta;
    data['categories'] = arr;
    data['author'] = $("#field-chosen-author").val();
    data['language'] = $("#language").val();

    $("#loading-2").modal('show');

    $.post(CONFIG.get('URL')+'post/'+val+'_post',{action:'save', data: data}, function(response, status){

      var result = JSON.parse(response);

      upload_featured_image( result, val, function(e, val){
        $("#modal-gallery-loading").modal('hide');

        if(val == 'add'){
          if(e > 0){
            window.location.href = CONFIG.get('URL')+'post/edit/'+e;
          }else{
            /*$('#alert_page').append(alertMessage('Unable to Add New Post','error','error1'));*/
            notification("Post", "Unable to Add New Post", "gritter-error");
          }
        }else{
          if(e > 0){
            /*$('#alert_page').append(alertMessage('Successfully Updated Post','success','success1'));*/
            load_archived();
            notification("Post", "Successfully Updated Post", "gritter-success");
          }else{
            /*$('#alert_page').append(alertMessage('Unable to Update Post','error','error1'));*/
            notification("Post", "Unable to Update Post", "gritter-error");
          }
        }
      });

      if (val = 'save') {
        load_data($('#hidden_id').val());
      }

      $("#loading-2").modal('hide');
    });
  }
}
function load_tinyMCE(id){
  tinyMCE.init({selector:'#content',
    menubar: " view edit format table tools",
    height : 300,
    oninit : "setPlainText",
    toolbar:[
      "nonbreaking forecolor backcolor undo redo styleselect bold italic alignleft aligncenter alignright alignjustify link image imageuploader cmsmedia",
      "bullist numlist outdent indent"
    ],
    plugins: [
    "paste advlist autolink link image lists charmap print preview hr anchor pagebreak",
    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
    "imageuploader cmsmedia",
    "textcolor colorpicker","nonbreaking table code "
    ],
    external_plugins:{
      'imageuploader' :'plugins/imageuploader/editor_plugin_src.js',
      'cmsmedia'      :'plugins/cmsmedia/editor_plugin_src.js',
    },
    relative_urls: false,
    convert_urls: false,
    nonbreaking_force_tab:true,
    tools: "inserttable",
    init_instance_callback : function(ed){
      if (id != 0) {
        load_data(id);
      }
    }
  });

  // $("#content_tree").load( CONFIG.get('URL')+'post/get_data/', function( response, status, xhr ) {
  $.post( CONFIG.get('URL')+'post/get_data/', function( response, status, xhr ) {
    if (cms_function.isJSON(response)) {
      newData = JSON.parse(response);

      if ( status == "success" ) {
        $('#tags').empty();
        $('#tags').append('<div id="product_category_tree" class="tree"></div>');
        update_tree(id);
        $('#dialog-add').modal('hide');
      }
      else{
        jQuery('div').remove('#errorCategory');
        /*jQuery('#alertProductCategory').append(alertMessage('404 Network error - Page will load soon','error','errorCategory'));*/
        notification("Post", "404 Network error - Page will load soon", "gritter-error");

        setTimeout(function(){location.reload(true);}, 3000);
      }
    }else{
      notification("Post Category", "Unable to load Categories", "gritter-error");
    }
  });

  $('#seo_title').keyup(function(e){
    var ingnore_key_codes = [8,32];
    if($(this).val().length <= seo_title_limit){
      $('#title_char').text($(this).val().length);
    }else{
      if ($.inArray(e.keyCode, ingnore_key_codes) <= 0)
        $(this).val($(this).val().substring(0,seo_title_limit));
    }
  });
  $('#seo_description').keyup(function(e){
    var ingnore_key_codes = [8,32];
    if($(this).val().length <= seo_description_limit){
      $('#desc_char').text($(this).val().length);
    }else{
      if ($.inArray(e.keyCode, ingnore_key_codes) <= 0){
        $(this).val($(this).val().substring(0,seo_description_limit));
      }
    }
  });

  if ($("#action").val() != 'edit') {
    $('#txt_post_title').change(function(){
      /*var val = $(this).val();*/

      if ($('#txt_url_slug').val() == '') {
        $('#txt_url_slug').val(convertToSlug($(this).val()))
      }

      var val = $('#txt_url_slug').val();

      process_slug(val);
    });
  }else{
    $('#txt_post_title').change(function(){
      /*var val = $(this).val();*/

      if ($('#txt_url_slug').val() == '' || $("#translation-status-container").find('.badge').text() == "No Translation") {
        $('#txt_url_slug').val(convertToSlug($(this).val()))
      }

      var val = $('#txt_url_slug').val();

      process_slug(val);
    });
  }

  $('#txt_url_slug').change(function(){
    var val = $(this).val();
    if(val!=''){
      process_slug(val);
    }
    else{
      val = convertToSlug($('#txt_post_title').val());
      process_slug(val != '' ? val : 'post');
      $(this).val(val)
    }
  });

  $('#btn_add_category').click(function(e){
    $('#categories_modal').modal('show');
  });
}
function process_slug(val){
  $.post(CONFIG.get('URL')+'post/get_url_slug/',{action:'get', data: val, post_id : $("#hidden_id").val(), lang: $("#language").val()}, function(response, status){
    var result = JSON.parse(response);
    slug = result['slug'];
    permalink = result['permalink'];
    $('#txt_url_slug').val(slug);
    $('#permalink').text(permalink);
    $('#permalink').attr('href', permalink);
  });
}
function update_tree(id){
  var productcategoriesparentDataSource;

  productcategoriesparentDataSource = new DataSourceTree({data: newData});

  $('#product_category_tree').ace_tree({
    dataSource: productcategoriesparentDataSource ,
    multiSelect:true,
    cacheItems: true,
    loadingHTML:'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
    'open-icon' : 'icon-minus',
    'close-icon' : 'icon-plus',
    'selectable' : true,
    'selected-icon' : 'icon-ok',
    'unselected-icon' : 'icon-remove'
  });
  $('[data-rel=tooltip]').tooltip();

  if(id>0){
    $.post(CONFIG.get('URL')+'post/get_categories_by_post_id',{action:'get', id: id}, function(response, status){
      var result = JSON.parse(response);
      update_new_tree(result);
    });
  }
}
function update_new_tree(ids){
  $('.tree-item').each(function(){
    $(this).removeClass('tree-selected');
    $(this).find('i').addClass('icon-remove');
  });
  $('.tree-item').each(function(index){
    var id = $(this).find('.value').val();

    if($.inArray(id,ids)>=0){
      $(this).addClass('tree-selected');
    }
  });

  $('.tree-selected i').each(function(){
    $(this).removeClass('icon-remove');
    $(this).addClass('icon-ok');
  });
}
function validate(){
  var error = false;

  if($('#txt_post_title').val() == ''){
    error = true;
    /*$('#alert_page').append(alertMessage('Please Enter Post Title','error','error1'));*/
    notification("Post", "Please Enter Post Title", "gritter-error");
  }
  var arr = [];
  $('.tree-selected').each(function(){
    arr.push($(this).find('.value').val());
  });

  if(arr.length  == 0)
  {
    $('div').remove('#errorCategory');
    /*$('#alertProductCategory').append(alertMessage('Please Select Category','error','errorCategory'));*/
    notification("Post", "Please Select Category", "gritter-error");
    error = true;
  }

  if(!error)
    return true;

  return false;
}
function upload_featured_image(result, val, callbacl_fn){
  var featured_image = $("#id-input-file-3").get(0).files[0];

  if (typeof featured_image != "undefined") {
    var formData = new FormData();

    formData.append("action", "post-featured-image");
    formData.append("post_id", result);
    formData.append("file", featured_image);

    var image_name = typeof featured_image.name != "" ? featured_image.name : "[unknown file]";

    if (image_name.length > 80) {
      var temp1 = image_name.substr(0, 33);
      var temp2 = image_name.substr(image_name.length - 33, image_name.length);
      image_name = temp1 + "..." + temp2;
    }

    $.ajax({
      type:'POST',
      url: CONFIG.get('URL')+'post/upload_featured_image/',
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      type: 'POST',
      beforeSend: function( xhr ) {
        $("#modal-gallery-loading").modal({
          backdrop : 'static',
          keyboard : false,
          shown : false
        });
        $("#modal-gallery-loading-progress").show();
      },
      'xhr': function(test) {  
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);

            fn_set_gallery_loading(percentComplete);
          }
        }, false);
        return xhr;
      },
      success: function(response) {  
        if (cms_function.isJSON(response)) {
          var json_data = JSON.parse( response );
          $("#featured_image").attr('src', json_data['url']);
          $("#featured_image").attr('alt', json_data['url']);
          $("#featured_image").attr('data-src', '');
          $("#modal-gallery-loading-completed").append('<p><i class="icon icon-check"></i> '+ image_name +'</p>');
          $('#id-input-file-3').ace_file_input('reset_input');
        }else{
          if (typeof response == 'String') {
            notification("Page Featured Image", response, "gritter-error");
          }else{
            notification("Page Featured Image", "Error on Featured Image", "gritter-error");
          }
        }
        fn_reset_feature_image_field();
        callbacl_fn( result, val );
      },
    }).done(function() {
      $("#modal-gallery-loading-progress").hide();
    });
  }else{
    if (typeof callbacl_fn != "undefined") {
      callbacl_fn( result, val );
    }
  }
}
function upload_cropped_featured_image(post_id, image){
  image.cropper('getCroppedCanvas').toBlob(function(blob){
    var formData = new FormData();
    var image = $("#modal_picture");
    formData.append("post_id", post_id);
    formData.append("action", 'post-cropped-featured-image');
    formData.append("canvasData", JSON.stringify(image.cropper('getCanvasData')));
    formData.append("cropBoxData", JSON.stringify(image.cropper('getCropBoxData')));
    formData.append("file", blob);
    
    $.ajax({
      type:'POST',
      url: CONFIG.get('URL')+'post/upload_featured_image/',
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      type: 'POST',
      beforeSend: function( xhr ) {

      },
      'xhr': function(test) {  
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);

            fn_set_gallery_loading(percentComplete);
          }
        }, false);
        return xhr;
      },
      success: function(response) {  
        notification("Cropped Featured Image", "Successfully Saved Cropped Image", "gritter-success");
        $('#cropper-modal').modal('hide');

        var json_data = JSON.parse( response );
        if (typeof json_data['url'] != "undefined") {
          $("#featured_image").attr('src', json_data['url']);
          $("#featured_image").attr('data-src', json_data['url']);
        }
      },
    }).done(function() {

    });
  }, 'image/jpeg'
  );
}
function fn_set_gallery_loading(percentage){
  if (typeof percentage == 'undefined') {
    percentage = 0
  }
  var perc = percentage + "%";

  $("#modal-gallery-loading-progress").attr("data-percent", perc);
  $("#modal-gallery-loading-progress").find(".bar").css('width', perc);
}
function fn_show_cropper(){
  $('#cropper-modal').modal('show');
}
function fn_set_feature_image(){
  var image = $("#featured_image");
  var selected_image = typeof image.attr('data-src') != "undefined" && image.attr('data-src') != "" ? image.attr('data-src') : image.attr('alt');
  image.attr('src', selected_image);
}
function fn_reset_feature_image_field(){
  $('#featured_image').attr('src', $('#featured_image').attr('alt'));
  if ($('#featured_image').attr('src')!="") {
    $("#edit_photo_holder").show();
  }else{
    $("#edit_photo_holder").hide();
  }
}

function delete_post(id){
  $('#hidden_post_id').val(id);
  $('#delete').modal('show');
}
function delete_post_modal(){
  var id = $('#hidden_post_id').val();
  $.post(CONFIG.get('URL')+'post/delete_post',{action:'delete', id: id}, function(response, status){
    var result = JSON.parse(response);

    if(result == 1)
      location.reload(true);
  });
}
function save_category(){
  var data = {};

  data['category_name'] = $('#category_name').val();
  data['category_parent'] = $('#category_parent').val();

  if(data['category_name'] != ''){
    $.post(CONFIG.get('URL')+'post/add_category',{action:'add', data: data}, function(response, status){
      var result = JSON.parse(response);

      if(result == 1)
        location.reload(true);
      else
        $('#alert_quick_add_modal').append(alertMessage('Unable to Add Category','error','errorCategory'));

    });
  }else
  $('#alert_quick_add_modal').append(alertMessage('Please Enter Category Name','error','errorCategory'));
}

