var can_edit_photo = true;
var error = false;
var current_id = 0; /* for edit  */
// $(document).ready(function(){
//   ready();
// });


function ready(){
  var action = $('#action').val();

  if(action == 'sort'){
    load_sort();
  }

  $('.remove').click(function(){
    if(action == 'edit'){
      if(can_edit_photo){
        $('#btn_edit_photo').removeClass('hide');
      }
    }
  });

  $('#product_category_form').ajaxForm({
    beforeSend: function(){
      $('#loads').modal('show');
    },
    complete: function(xhr) {
      console.log(xhr);
      $('#loads').modal('hide');
      respond = JSON.parse(xhr.responseText);
      if(respond > 0){
        jQuery('div').remove('#save_error');
        jQuery('#alertProductCategory').append(alertMessage('Sucessfully Added','success','save_error'));
        setTimeout(function(){window.location = CONFIG.get('URL')+'products/categories/edit/'+respond;}, 2000);
      }
      else if(respond == 'ok'){
        jQuery('div').remove('#save_error');
        jQuery('#alertProductCategory').append(alertMessage('Sucessfully Updated','success','save_error'));
        setTimeout(function(){window.location = CONFIG.get('URL')+'products/categories/add';}, 2000);
      }
      else if(respond == 'ok-save'){
        jQuery('div').remove('#save_error');
        jQuery('#alertProductCategory').append(alertMessage('Sucessfully Updated','success','save_error'));
        /*setTimeout(function(){window.location = CONFIG.get('URL')+'products/categories/';}, 2000);*/
      }
      else{
        jQuery('div').remove('#save_error');
        jQuery('#alertProductCategory').append(alertMessage('Error while saving','error','save_error'));
      }
    },   
  });

  $('#parent').chosen().siblings(".chosen-container").find(".chosen-search").remove();
  $('#hide').chosen().siblings(".chosen-container").find(".chosen-search").remove();
  $('#languages').chosen().siblings(".chosen-container").find(".chosen-search").remove();
}

function initialize_tiny(){
  tinyMCE.init({
    selector:'#description_tiny',
    menubar: " view edit format table tools",
    height : 200,
    toolbar:[
      "nonbreaking forecolor backcolor  undo redo styleselect bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent  ", 
      " indent link image imageuploader cmsmedia"
    ],
    oninit : "setPlainText",
    plugins: [
      "paste link image table code",
      "imageuploader cmsmedia",
      "textcolor",
      "colorpicker wordcount nonbreaking"
    ],
    external_plugins:{
      'imageuploader' :'plugins/imageuploader/editor_plugin_src.js',
      'cmsmedia'      :'plugins/cmsmedia/editor_plugin_src.js',
    },
    relative_urls: false,
    convert_urls: false,
    nonbreaking_force_tab: true,
    tools: "inserttable",
    init_instance_callback : function(ed){
      /*$("#url_slug").trigger('blur');*/
    }
  });
}

function load_sort(){
  jQuery.post(CONFIG.get('URL')+'product-categories/get_categories_parent_zero',{action:'get'}, function(response,status){
    var result = JSON.parse(response);
    $( "#accordion" ).html('');
    $.each(result, function(i, field){
      $( "#accordion" ).append('<div class="group"><h3 class="accordion-header" >'+field['category_name']+'</h3><input type="hidden" class="value" value='+field['id']+'></div>');
    });
    $( "#accordion" ).accordion({
      collapsible: true ,
      heightStyle: "content",
      animate: 250,
      active: false,
      header: ".accordion-header"
    }).sortable({
      axis: "y",
      handle: ".accordion-header",
      stop: function( event, ui ) {
        /*  IE doesn't register the blur when sorting */
        /*  so trigger focusout handlers to remove .ui-state-focus */
        ui.item.children( ".accordion-header" ).triggerHandler( "focusout" );
      }
    });
  });
}
function go_to_sort(){
  window.location = CONFIG.get('URL')+'products/categories/sorting/';
}
function save_sort(){
  var arr = [];
  $(".value").each(function(){
    arr.push($(this).val());
  });
  jQuery.post(CONFIG.get('URL')+'product-categories/sort_category',{action:'save', arr:arr}, function(response,status){
    var result = JSON.parse(response)
    if(result == 1){
      jQuery('div').remove('#save_success');
      jQuery('#alertSort').append(alertMessage('Sucessfully sorted','success','save_success'));
    }
    else{
      jQuery('div').remove('#save_error');
      jQuery('#alertSort').append(alertMessage('Unsucessfully sorted','error','save_error'));
    }

  });
}
function load_category(id, fn){
  jQuery.post(CONFIG.get('URL')+'product-categories/get_category/',{action:'get', id:id}, function(response,status){
    var result = JSON.parse(response);
    var url = window.location.href.split('/');
    var image_url = result['image_url'] ? result['image_url'].split('/') : "";
    /*var image = result['image_url'].replace('/images/','/thumbnails/205x154/');*/
    var image = result['image_url'];

    var permalink = typeof result['permalink'] != "undefined" ? result['permalink'] : CONFIG.get('FRONTEND_URL')+'/product-categories/' + result['url_slug'];

    $('#name').val(result['category_name']);
    $('#description_tiny').val(result['category_description']);
    initialize_tiny();

    $('#modal_picture').attr('src',result['image_url']);
    $('#hdn_image').val(result['image_url']);
    $('#hide').val(result['hide_category']);
    $('#url_slug').val(result['url_slug']);
    $('#old_slug').val(result['url_slug']);
    $('#permalink').text(permalink);
    $('#permalink').attr('href',permalink);
    load_parents($('#hdn_id').val(), result['category_parent']);

    if(result['image_url'] != ''){
      $('#image_view').attr('src',image);

      if(url[2] != image_url[2]){
        can_edit_photo = false;
        $('#image_error_msg').append('Please Upload a new picture for the site');
      }
      else{
        $('#btn_edit_photo').removeClass('hide');
      }
    }

    if (result['translate'] == 'main') {
        $("#is-translated").text('Main Language').removeClass('badge-info').addClass('badge-success');
    }
    
    if (typeof fn == 'function') {
      fn();
    }
  });
}
function load_parents(id,select){
  jQuery.post(CONFIG.get('URL')+'product-categories/load_parents/',{action:'get', current_id : $("#hdn_id").val()}, function(response,status){
    var result = JSON.parse(response);

    $.each(result, function(i, field){
      if(field['id'] != current_id)
        if(field['id'] == id && id > 0)
          var option = $('<option></option>').attr("value", field['id']).text(field['level'] + field['category_name']).attr('selected','selected');
        else
          var option = $('<option></option>').attr("value", field['id']).text(field['level'] + field['category_name']);
        $("#parent").append(option);
      });

    $('#parent').val(select).trigger('chosen:updated');
    $('#url_slug').trigger('blur')
  });
}

function edit_category(id){
  window.location = CONFIG.get('URL')+'products/categories/edit/'+id;
}
function load_html_functions(){
  $('#image').ace_file_input({
    no_file:'No File ...',
    btn_choose:'Choose',
    btn_change:'Change',
    droppable:false,
    onchange:null,

  }).on('change', function(){
    validate_image($(this).val());
  });

  var $image = $('#cropper-example-2 > img'),
  canvasData,
  cropBoxData;

  $('#cropper-modal').on('shown.bs.modal', function () {
    $image.cropper({
      autoCropArea: 0.8,
      aspectRatio: 16 / 9,
      built: function () {
        $image.cropper('setCanvasData', canvasData);
        $image.cropper('setCropBoxData', cropBoxData);
      }
    });
  }).on('hidden.bs.modal', function () {
    canvasData = $image.cropper('getCanvasData');
    cropBoxData = $image.cropper('getCropBoxData');
    $image.cropper('destroy');
  });

  if ($("#action").val() == 'add') {
    $('#name').blur(function(e){
      if($(this).val()!=''){
        var slug = slugify($(this).val());
        process_slug(slug);
      }
    });
  }else{
    $('#name').blur(function(e){
      if($("#url_slug").val()==''){
        var slug = slugify($(this).val());
        process_slug(slug);
      }
    });
  }

  $('#url_slug').blur(function(){
    if($(this).val()!=''){
      var slug = slugify($(this).val());
      process_slug(slug);
    }
  });
}
function process_slug(slug){
  var current_id = $("#hdn_id").val();
  var parent_id = $("#parent").val();
  var lang = $("#languages").val();

  $("#is-translated").text('Identifying...').removeClass('badge-success badge-important badge-inverse').addClass('badge-info');
  $.post(CONFIG.get('URL')+'product-categories/get_available_slug/', {
    action: 'get', 
    slug:slug, 
    current_id: current_id, 
    parent_id: parent_id,
    lang: lang,
  },function(response, status){
    var result = JSON.parse(response);

    $('#url_slug').val(result['slug']);
    $('#permalink').text(result['permalink']);
    $('#permalink').attr("href", result['permalink']);

    if ($("#action").val() == 'add') { return; }

    if (typeof result['detail'] != 'undefined') {
      if (typeof result['detail']['category_name']) {
        $("#name").val(result['detail']['category_name']);
      }
      if (typeof result['detail']['category_description']) {
        tinyMCE.get('description_tiny').setContent(result['detail']['category_description'])
        $("#description").val(result['detail']['category_description']);
      }
    }

    if (result['translate'] == 'main') {
      $("#is-translated").text('Main Language').removeClass('badge-info').addClass('badge-success');
    }
    else if (result['translate'] == 'default') {
      $("#is-translated").text('Default').removeClass('badge-info').addClass('badge-inverse');
    }
    else if (result['translate'] == 'no translation') {
      $("#is-translated").text('No Translation').removeClass('badge-info').addClass('badge-important');
    }
    else if (result['translate'] == 'translated') {
      $("#is-translated").text('Translated').removeClass('badge-info').addClass('badge-success');
      $("#btn-delete-category-translation").show();
    }
    else{
      $("#is-translated").text('Undefined').removeClass('badge-info').addClass('badge-important');
    }
  });
}
function validate_image(image){
  jQuery('div').remove('#error_image');

  if (!image.match(/(?:gif|jpg|png|bmp)$/)) {
    jQuery('#alertProductCategory').append(alertMessage('Invalid Image','error','error_image'));
    error = true;
  }
  else
    error = false;
}

function validate_form(){
  var err = false;

  jQuery('div').remove('#error_name');

  if($('#name').val() == ''){
    jQuery('#alertProductCategory').append(alertMessage('Please Fill Category Name','error','error_name'));
    err = true;
  }

  if(error || err)
    return false;
  $('#description').val(tinyMCE.get('description_tiny').getContent());
  return true;
}

function reset_form(){
  $('#name').val('');
  $('#slug').val('');
  $('#image').val('');
  $('#parent').val(0).trigger('chosen:updated');
  $('#description').val('');
  $('.remove').trigger('click');

  window.location = CONFIG.get('URL')+'products/categories';
}

function go_to_add(){
  window.location = CONFIG.get('URL')+'products/categories/add';
}

function change_view_image(input){
  $('#btn_edit_photo').addClass('hide');
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#image_view')
      .attr('src', e.target.result)
      .width(205)
      .height(154);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function reset_edit_form(){
  window.location = CONFIG.get('URL')+'products/categories/edit'+$('#hdn_id').val();
}