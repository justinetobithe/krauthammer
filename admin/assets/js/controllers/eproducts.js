


var intRegex = /^\d+$/;
var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
var globalerror =  false;
var action = '';
var checkbox = 0;
var track_inventory = 0;
var arr_sku = [];
var arr_products;
$(document).ready(function(){
  $('#add_gallery_photos').click(function(e){
    e.preventDefault();
    $('#add_images_input').trigger('click');
  });
  $('#add_images_input').change(function(e){

  });
  $('.modal').on('show', function(e){
   $(this).attr('style','z-index:999999');
   $('#modal_container').removeClass('hide');
 });
  $('.modal').on('hidden', function(e){
   $('#modal_container').addClass('hide');
   $(this).removeAttr('style','z-index');
 });
  load_keypress();
  load_accordion(); 
  load_accordion_inside();
  load_stop_propagation();

  $('#add_new_products_attributes').click(function(e){
    var index = 1;
    $('.accordion_group').each(function(e){
      index++;
    });

    $("#accordion_products_attributes").append('<div id="accordion" class="group accordion_group products_attr">'+
      '<h3 class="accordion-header"><input type="text" class="input span8 textbox attr_label"></h3>'+
      '<div>'+
      '<div class="hr"></div>'+
      '<div class="row-fluid">'+
      '<button class="btn btn-info add_new_selection_values btn-small" onclick="add_new_attributes_inside('+index+'); return false;"> Add New Product Option Selection </button>'+
      '</div>'+
      '<div class="hr"></div>'+
      '<div class="row-fluid">'+
      '<div  class="accordion-style2 accordion_products_attributes_inside products_attr_inside" id="new_attributes_accordion_'+index+'">'+
      '<div id="accordion" class="group_'+index+'">'+
      '<h3 class="accordion-header-new"><input type="text" class="textbox label_selection"></h3>'+
      '<div>'+
      '<div class="row">'+
      '<select class="pull-right delivery_method">'+
      '<option value="Shipped">Shipped</option><option value="Virtual">Virtual</option><option value="Download">Download</option><option value="Donation">Donation</option><option value="Subscription">Subscription</option><option value="N/A">Disabled</option>'+
      '</select>'+
      '</div>'+
      '<br>'+
      '<div class="table-responsive">'+
      '<table id="sample-table-1" class="table table-striped table-bordered table-hover">'+
      '<thead>'+
      '<tr>'+
      '<th>'+
      'Price'+
      '</th>'+
      '<th>'+
      '<label>'+
      '<input type="checkbox" class="ace sale_price"  value="'+index+',0" />'+
      '<span class="lbl"> Sale Price</span>'+
      '</label>'+
      '</th>'+
      '<th>'+
      '<label>'+
      '<input type="checkbox" class="ace shipping"  value="'+index+',0" />'+
      '<span class="lbl"> Shipping</span>'+
      '</label>'+
      '</th>'+
      '<th>'+
      '<label>'+
      '<input type="checkbox" class="ace inventory" value="'+index+',0" />'+
      '<span class="lbl"> Inventory</span>'+
      '</label>'+
      '</th>'+
      '</tr>'+
      '</thead>'+
      '<tbody>'+
      '<tr>'+
      '<td>'+
      '<input type="text" class="number input-small price" placeholder="$">'+
      '<br>'+
      '<label>'+
      '<input type="checkbox" class="ace" />'+
      '<span class="lbl"> Not Taxed</span>'+
      '</label>'+
      '</td>'+
      '<td class="sale_'+index+'_0"></td>'+
      '<td class="shipping_'+index+'_0"></td>'+
      '<td class="inventory_'+index+'_0"></td>'+
      '</tr>'+
      '</table>'+
      '</div>'+
      '</div>'+
      '</div>'+
      '</div>'+
      '</div>'+
      '<div class="hr"></div>'+
      '<div class="row-fluid">'+
      '<input type="text" class="span3" style="margin-right:3px;" placeholder="Minimum Quantity">'+
      '<input type="text" class="span3" style="margin-right:3px;" placeholder="Quantity Increment">'+
      '<input type="text" class="span3" style="margin-right:3px;" placeholder="Maximum Quantity">'+
      '<input type="text" class="span3" style="margin-right:3px;" placeholder="Product Unit">'+
      '</div>'+
      '<div class="row-fluid">'+
      '<label>'+
      '<input name="form-field-checkbox" type="checkbox" class="ace checkbox colorpicker_attributtes color">'+
      '<span class="lbl"> Tick if this is a color section.</span>'+
      '</label>'+
      '</div>'+
      '<div class="row-fluid">'+
      '<label>'+
      '<input name="form-field-checkbox" type="checkbox" class="ace checkbox required">'+
      '<span class="lbl"> Required?</span>'+
      '</label>'+
      '</div>'+
      '</div>'+
      '</div>');
    $('#accordion_products_attributes').accordion('refresh');
    load_accordion_inside();
    load_attributes();
    load_colorpicker();
    load_stop_propagation();
    e.preventDefault();

  });



  $('#seo_title').keyup(function(e){
    var ingnore_key_codes = [8,32];
    if($(this).val().length <= 60){
      $('#title_char').text($(this).val().length);
    }else{
     if ($.inArray(e.keyCode, ingnore_key_codes) <= 0)
      $(this).val($(this).val().substring(0,60));
  }
});
  $('#seo_description').keyup(function(e){
    var ingnore_key_codes = [8,32];
    if($(this).val().length <= 60){
      $('#desc_char').text($(this).val().length);
    }else{
     if ($.inArray(e.keyCode, ingnore_key_codes) <= 0)
      $(this).val($(this).val().substring(0,60));
  }
});
  var index = $('#hdn_index_tab').val();

  $('#txt_sku').keyup(function(){

    jQuery('div').remove('#error_sku');
    var sku = $(this).val();
    if(sku != '')
      check_sku(sku);
  });
  $('.widgets_tabs').sortable({
    start: function(e){
      $('.product_tabs textarea').each(function(){
        $(this).parent().hide();
        id = $(this).attr('id');
        tinyMCE.execCommand('mceRemoveEditor', true, id);
      });

    },
    connectWith: '.widget-main',
    items:'> .widget-box',
    opacity:0.8,
    revert:true,
    forceHelperSize:true,
    placeholder: 'widget-placeholder',
    forcePlaceholderSize:true,
    tolerance:'pointer',
    stop: function(e){
      $('.product_tabs textarea').each(function(){

        id = $(this).attr('id');
        tinyMCE.execCommand('mceAddEditor', true, id);
        var wasVisible = $(this).parent().parent().parent().find('.collapse').attr('class');
        if(wasVisible == 'icon-chevron-up collapse' || wasVisible == 'collapse icon-chevron-up')
         $(this).parent().show();

     });
    }
  });

  $('#product_tab_add_button').click(function(){
    index++;
    $('.widgets_tabs').append('<div class="widget-box product_tabs"><div class="widget-header"><h5><input type="text" class="input-xlarge title" placeholder="Tab Title"></h5><div class="widget-toolbar"><input type="hidden" class="id_for_collapse" value='+index+'><a href="#" onclick="collapse_tab('+index+'); return false;"style="color: #C7C5D1;"><i class="icon-chevron-up collapse"></i></a><a href="#" data-action="close" ><i class="icon-remove"></i></a></div></div><div class="widget-body"><div class="widget-main"><textarea class="textarea_product_tabs" id="tab_desc'+index+'"></textarea></div></div></div>');
    initilize_editor();
    $('.widgets_tabs').sortable("refresh");
  });
  $('#btn_add_category').click(function(){

   showAddCategoryModal();
 });
  $('#id-input-file-2').ace_file_input({
    style:'well',
    btn_choose:'Drop Photos here or click to choose',
    btn_change:null,
    no_icon:'icon-cloud-upload',
    droppable:true,
          thumbnail:'large'//large | fit
          //,icon_remove:null//set null, to hide remove/reset button
          /**,before_change:function(files, dropped) {
            //Check an example below
            //or examples/file-upload.html
            return true;
          }*/
          /**,before_remove : function() {
            return true;
          }*/
          ,
          preview_error : function(filename, error_code) {
            //name of the file that failed
            //error_code values
            //1 = 'FILE_LOAD_FAILED',
            //2 = 'IMAGE_LOAD_FAILED',
            //3 = 'THUMBNAIL_FAILED'
            //alert(error_code);
          }

        }).on('change', function(){

          //console.log($(this).data('ace_input_files'));
          //console.log($(this).data('ace_input_method'));
        });
        $('#txt_quantity').keyup(function(){ 

          var value = $(this).val();

          if(value != '')
          {
            if(intRegex.test(value))
            {
              var intvalue = parseInt(value);
              if(intvalue > 0)
                $('#txt_out_of_stock').val('');
              else
                $('#txt_out_of_stock').val('Out of Stock');
            }
            else
            {
      //jQuery('#messageAlertDetails').append(alertMessage('Invalid Quantity.','error'));
    }
  }
  else
   $('#txt_out_of_stock').val('Out of Stock');

});

        action = $('#action').val();
        if(action == 'add_product')
        {  

      //parent_category();
      load_blur_functions('add');
      load_appointments_accordion();
      load_arr_sku();
      image();
      load_product_desc();
      initilize_editor();
      loadProductCategories();
      load_attributes();
      load_colorpicker();

    }
    else if(action == 'mange_product')
    {
      var limit = 2;
      var lastScrollTop = 0;
      loadProductTable();

    }
    else if(action = 'edit_product')
    {

      $('#desc_char').text($('#seo_description').val().length);
      $('#title_char').text($('#seo_title').val().length);
      
      if($('#hdn_no_index').val() == 'Y'){
        $('#seo_no_index').trigger('click');
      }
      if($('#track_inventory').val() == 'YES'){
        $('#switch_track_inventory').trigger('click');
      }
      load_cropper();
      load_blur_functions('edit');
      load_appointments_accordion();
      image();
      load_arr_sku();
      initilize_editor();
      load_product_desc();
     // $('#txt_product_name').trigger('blur');
     $('#featured_product').val($('#hdn_featured').val());
     $('#recommended_checkout').val($('#hdn_recommended').val());
      //alert($('#hidden_product_description').val());
      //loadProductImages($('#product_id').val());
      loadProductCategories();
      load_attributes();
      load_colorpicker();
      $('.datepicker').trigger('change');
      /*setTimeout(function() {
           selectCategories($('#selected_categories').val());
         }, 2000);*/
         $('#tasks').sortable({
          opacity:0.8,
          revert:true,
          forceHelperSize:true,
          placeholder: 'draggable-placeholder',
          forcePlaceholderSize:true,
          tolerance:'pointer',
          stop: function( event, ui ) {//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
          $(ui.item).css('z-index', 'auto');
          sort_additional_files();
        }
      }
      );
       }
       $('.remove').click(function(){
        $('#product_image')
        .attr('src', CONFIG.get('FRONTEND_URL')+'/thumbnails/200x120/uploads/default.png')
        .width("200%")
        .height(130);
      })
       var bar = $('.bar');
       var percent = $('.percent');
       var status = $('#status');
       $('#add_product_form').ajaxForm({
         beforeSend: function(){
          //alert($('#table_to_be_clone tr').length);
          status.empty();
          var percentVal = '0%';
          bar.width(percentVal);
          percent.html(percentVal);

          $("#loading").modal({
            backdrop : 'static',
            keyboard: false
          });

          $("#load-msg").removeClass();
        },
        uploadProgress: function(event, position, total, percentComplete) {
          var percentVal = percentComplete + '%';
          bar.width(percentVal);
          percent.html(percentVal);
        },
        complete: function(xhr) {
          //alert(xhr.responseText);
          respond = JSON.parse(xhr.responseText);
          sort_gallery_image();
          if($('.template-upload').length > 0) {

            $('#hidden_image_name').val(respond['image_name']);
            $('#hidden_product_id_for_gallery').val(respond['product_id']);
          //alert( $('#hidden_product_id_for_gallery').val());
          //alert(respond['image_name']+' = '+respond['status']);
          
          $('#start_aawwad').trigger('click');

        }else{
          closeModal();
        }
      },
      error:  function(xhr, desc, err) { 
        console.debug(xhr); 
        console.log("Desc: " + desc + "\nErr:" + err); 
      } 


    });


     });
function show_cropper(){
  $('#cropper-modal').modal('show');
}
function load_cropper(){
 var $image = $('#cropper-example-2 > img'),
 canvasData,
 cropBoxData;

 $('#cropper-modal').on('shown.bs.modal', function () {
  $image.cropper({
    autoCropArea: 0.8,
    aspectRatio: 16 / 11,
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
$('#btn_get_canvass').click(function(){
 var data = $('#cropper-example-2 > img').cropper('getData');
 var image = $('#hdn_image').val();


 $.post(CONFIG.get('URL')+'eproducts/crop',{action:'crop', data:data, image:image}, function(response,status){

  if(JSON.parse(response) == '')
   location.reload(true);
 else
  alert(response);
});
});
}

function add_new_attributes_inside(index){

  var accordion = '#new_attributes_accordion_'+index;
  var _inside_index = 0;
  var outside_index = 0;
  $('.group_'+index).each(function(e){
    _inside_index++;
  });

  $('.accordion_group').each(function(e){
    outside_index++;
  });
  $(accordion).append('<div id="accordion" class="group_'+index+' products_attr_inside">'+
    '<h3 class="accordion-header-new"><input type="text" class="textbox label_selection"></h3>'+
    '<div>'+
    '<div class="row">'+
    '<select class="pull-right delivery_method" >'+
    '<option value="Shipped">Shipped</option><option value="Virtual">Virtual</option><option value="Download">Download</option><option value="Donation">Donation</option><option value="Subscription">Subscription</option><option value="N/A">Disabled</option>'+
    '</select>'+
    '</div>'+
    '<br>'+
    '<div class="table-responsive">'+
    '<table id="sample-table-1" class="table table-striped table-bordered table-hover">'+
    '<thead>'+
    '<tr>'+
    '<th>'+
    'Price'+
    '</th>'+
    '<th>'+
    '<label>'+
    '<input type="checkbox" class="ace sale_price" value="'+outside_index+','+_inside_index+'"/>'+
    '<span class="lbl"> Sale Price</span>'+
    '</label>'+
    '</th>'+
    '<th>'+
    '<label>'+
    '<input type="checkbox" class="ace shipping" value="'+outside_index+','+_inside_index+'" />'+
    '<span class="lbl"> Shipping</span>'+
    '</label>'+
    '</th>'+
    '<th>'+
    '<label>'+
    '<input type="checkbox" class="ace inventory" value="'+outside_index+','+_inside_index+'" />'+
    '<span class="lbl"> Inventory</span>'+
    '</label>'+
    '</th>'+
    '</tr>'+
    '</thead>'+
    '<tbody>'+
    '<tr>'+
    '<td>'+
    '<input type="text" class="number input-small price" placeholder="$">'+
    '<br>'+
    '<label>'+
    '<input type="checkbox" class="ace" />'+
    '<span class="lbl"> Not Taxed</span>'+
    '</label>'+
    '</td>'+
    '<td class="sale_'+outside_index+'_'+_inside_index+'"></td>'+
    '<td class="shipping_'+outside_index+'_'+_inside_index+'"></td>'+
    '<td class="inventory_'+outside_index+'_'+_inside_index+'"></td>'+
    '</tr>'+
    '</table>'+
    '</div>'+
    '</div>'+
    '</div>');

  $('.accordion_products_attributes_inside').accordion('refresh');

  load_attributes();
  load_stop_propagation();
}
function load_accordion_inside(){
 $(".accordion_products_attributes_inside").accordion({
  collapsible: true ,
  heightStyle: "content",
  animate: 250,
  header: ".accordion-header-new"
}).sortable({
  axis: "y",
  handle: ".accordion-header-new",
  stop: function( event, ui ) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children( ".accordion-header-new" ).triggerHandler( "focusout" );
          }
        });
$(".accordion_products_attributes_inside h3 input").on('keydown', function (e) {
  e.stopPropagation();
});

}
function load_accordion(){
 $("#accordion_products_attributes").accordion({
  collapsible: true ,
  heightStyle: "content",
  animate: 250,
  header: ".accordion-header"
}).sortable({
  axis: "y",
  handle: ".accordion-header",
  stop: function( event, ui ) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children( ".accordion-header" ).triggerHandler( "focusout" );
          }
        });

$("#accordion_products_attributes h3 input").on('keydown', function (e) {
  e.stopPropagation();
});

}
function load_product_desc(){
  tinyMCE.init({selector:'#product_description',
    menubar: " view edit format table tools",
    height : 300,
    toolbar:["nonbreaking forecolor backcolor undo redo styleselect bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent indent link image","imageuploader"],
    oninit : "setPlainText",
    plugins: [
    "paste link image",
    "colorpicker",
    "imageuploader",
    "textcolor wordcount code nonbreaking", "table"
    ],
    external_plugins:{'imageuploader':'plugins/imageuploader/editor_plugin_src.js'},
    relative_urls: false,
    convert_urls: false,
    nonbreaking_force_tab: true,
    tools: "inserttable"

  });
}
function change_value(){
  if(checkbox == 0)
    checkbox = 1;
  else
    checkbox = 0;
}
function check_sku(input){

  if($.inArray(input, arr_sku) >= 0){
    jQuery('#messageAlertDetails').append(alertMessage('Error: Duplicate SKU found, please try a different SKU.','error','error_sku'));
    globalerror = true;
  }else
  globalerror = false;
}
function load_arr_sku(){
  jQuery.post(CONFIG.get('URL')+'eproducts/get_product_sku',{action:'get'}, function(response, status){
    $.each(JSON.parse(response), function(i, field){
      if(field['sku'] != ' ')
        arr_sku.push(field['sku']);
    });
  });
}
function initilize_editor(){
 tinyMCE.init({selector:'.textarea_product_tabs',
  menubar: " view edit format table tools",
  height : 300,
  toolbar:["nonbreaking forecolor backcolor undo redo styleselect bold italic alignleft aligncenter alignright alignjustify ",
  "bullist numlist outdent indent link image imageuploader"],
  oninit : "setPlainText",
  plugins: [
  "paste link image table",
  "imageuploader",
  "textcolor",
  "colorpicker wordcount nonbreaking code",
  ],
  external_plugins:{'imageuploader':'plugins/imageuploader/editor_plugin_src.js'},
  relative_urls: false,
  convert_urls: false,
  nonbreaking_force_tab: true,
  tools: "inserttable"
});
}
function collapse_tab(input){
  //input

  $('.product_tabs').each(function(){
    var id = $(this).find('.id_for_collapse').val();

    if(id == input){
      var body = $(this).find('.widget-main');
      var icon_class = $(this).find('.collapse').attr('class');
      var icon = $(this).find('.collapse');
      if(icon_class == "icon-chevron-up collapse" || icon_class == "collapse icon-chevron-up"){
        icon.removeClass('icon-chevron-up');
        body.slideUp();
        icon.addClass('icon-chevron-down');
      }else{
        icon.removeClass('icon-chevron-down');
        body.slideDown();
        icon.addClass('icon-chevron-up');
      }
    }
  });

  return false;
}
function delete_tab(input){
  //alert(input);
  $('#hddn_selected_product_tab').val(input);
  $('#delete_tab').modal('show');
}
function delete_product_tab(){
  var id = $('#hddn_selected_product_tab').val();
  jQuery('div').remove('#widget_'+id);
  $('#delete_tab').modal('hide');
}
/*function addCategories(){
  alert("aw");
  $('#product_category_tree').append('<div class="tree-folder" style="display:none;"> <i class="icon-remove"></i><div class="tree-item-name">"JUST ADDED"</div></div>')
}*/
//DELETE GALLERY IMAGE
function delete_gallery_image(input)
{
  $('#hdn_img_id').val(input);
  $('#dialog-confirm').modal('show');
}
function delete_image()
{
  var img_id = $('#hdn_img_id').val();

  jQuery.post(CONFIG.get('URL')+'eproducts/deleteImages',{action:'delete_image', img_id:img_id }, function(response,status){
    var result = JSON.parse(response);

    if(result == 1){
      $('.ace-thumbnails').empty();
      loadProductImages($('#product_id').val());
      $('#dialog-confirm').modal('hide');
    }
  });

}
//LOADING GALLERY IMAGES
function loadProductImages(id)
{
  jQuery.post(CONFIG.get('URL')+'eproducts/loadImages',{action:'load_images', id:id }, function(response,status){
    var result = JSON.parse(response);
    $.each(result, function(i, field){
      var link = CONFIG.get('FRONTEND_URL')+field['image_url'];
      var thumbnail = link.replace('/images/', '/thumbnails/176x167/');
      $('.ace-thumbnails').append('<li><a href="'+link+'" data-rel="colorbox"><img alt="150x150" src="'+thumbnail+'" /><div class="text"><div class="inner">Click to show full image</div></div></a><div class="tools tools-bottom"><a href="#"><i class="icon-pencil" onclick="edit_thumbnails('+field['id']+'); return false;"></i></a><a href="#" onclick="delete_gallery_image('+field['id']+'); return false;"><i class="icon-remove red" ></i></a></div></li>');

    });

    var colorbox_params = {
      reposition:true,
      scalePhotos:true,
      scrolling:false,
      previous:'<i class="icon-arrow-left"></i>',
      next:'<i class="icon-arrow-right"></i>',
      close:'&times;',
      current:'{current} of {total}',
      maxWidth:'100%',
      maxHeight:'100%',
      onOpen:function(){
        document.body.style.overflow = 'hidden';
      },
      onClosed:function(){
        document.body.style.overflow = 'auto';
      },
      onComplete:function(){
        $.colorbox.resize();
      }
    };

    $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);

    $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");
  });
}

//EDIT PRODUCT FUNCTIONS =========================================================================================
function selectCategories(ids)
{
  var selected_ids = [];
  $.each(ids.split(",").slice(0,-1), function(index, item) {
    selected_ids.push(item); 
  });
  var ids = [];
  $('.tree-item').each(function(index){
    var id = $(this).find('.value').val();
   // alert(id);  

   if($.inArray(id,selected_ids)>=0)
     $(this).addClass('tree-selected');
 });

  $('.tree-selected i').each(function(){
    $(this).removeClass('icon-remove');
    $(this).addClass('icon-ok');
  });
}
//LOAD PRODUCT FUNCTIONS =========================================================================================
function loadProductTable()
{

  $('#productTable').dataTable( {
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": CONFIG.get('URL')+"eproducts/getProducts",
    "aoColumns": [
    { "bSortable": false },
    { "bSortable": false }, 
    null,
    null,
    null,
    null,
    { "bSortable": false},
    null
    ],

    "fnDrawCallback": function( oSettings ) {
      var row = $(this).closest(".dataTables_wrapper").find(".row-fluid:first-child");
      var col_1 = row.find(".span6:nth-child(1)");
      var col_2 = row.find(".span6:nth-child(2)");

      col_1.removeClass('span6').addClass('span4');
      col_2.removeClass('span6').addClass('span8');

      var label = row.find(".dataTables_filter").prepend($(".datatable-add-ons"));

      $.each($('.item-checkbox'), function(){
        $(this).parents('td').addClass('center');
        $(this).addClass('ace');
      });
      $.each($('.featured_product'), function(){
        $(this).parents('td').addClass('center');
      });
      var colorbox_params = {
        reposition:true,
        scalePhotos:true,
        scrolling:false,
        rel: 'nofollow',
        close:'&times;',
        maxWidth:'100%',
        maxHeight:'100%',
        onOpen:function(){
          document.body.style.overflow = 'hidden';
        },
        onClosed:function(){
          document.body.style.overflow = 'auto';
        },
        onComplete:function(){
          $.colorbox.resize();
        }
      };

      $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
      $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");
    }, "fnServerParams": function(aoData) {
        aoData.push({"name": "product_category", "value": $("#product-category_filter").val()});
    }
  });
  $("#product-category_filter").change(function(){
    $('#productTable').dataTable().fnDraw();
  });
}
function deleteProductModal(id)
{
  $('#hidden_product_id').val(0);
  $('#delete_msg h5').text('');
  $('#delete_msg h5').append('Are you sure you want to delete this product?');
  $('#hidden_product_id').val(id);
  $('#delete').modal('show');
}
function deleteProduct()
{


  var id = $('#hidden_product_id').val();

  jQuery.post(CONFIG.get('URL')+'eproducts/deleteProduct',{action:'deleteProduct', id:id}, function(response,status){

    var result = JSON.parse(response);

    if(result == "deleted")
    {

      setTimeout(function(){
        $('#delete_msg h5').append('Successfuly Deleted');
        $("#delete").modal('hide');
      },2000);
      window.location.href=CONFIG.get('URL')+'products/';
    }
  });
}
function getCategory(id)
{

  jQuery.post(CONFIG.get('URL')+'eproducts/getCategory',{action:'getCategory', id:id}, function(response,status){

    var result = JSON.parse(response);
    var option = '';
    $.each(result, function(i, field){

      option += field['category_name']+",";

    });
    $('.'+id).append(option);
  })


}
//ADD PRODUCT FUNCTIONS===========================================================================================
function addData()
{
  var arr = [];
  var arr_app = [];
  var attr_product_attributes = [];


  $('.products_attr').each(function(){
    var attr = {};
    var attr_arr = [];
    if($(this).find('.attr_label').val()!='')
    {
      attr['label'] = $(this).find('.attr_label').val();
      attr['is_color_selection'] ='no';

      attr['required'] = 'no';
      if($(this).find('.is_color_selection').is(':checked'))
        attr['is_color_selection'] = 'yes';
      if($(this).find('.required').is(':checked'))
        attr['required'] = 'yes';

      $(this).find('.products_attr_inside').each(function(){
        var attr_selection = {};
                //alert($(this).find('.label_selection').val());
                if($(this).find('.label_selection').val() != ''){
                  attr_selection['label'] = $(this).find('.label_selection').val();
                  attr_selection['price'] = '0';
                  if($(this).find('.price').val()!='')
                   attr_selection['price'] = $(this).find('.price').val();
                 attr_selection['item_on_sale'] = 'no';
                 attr_selection['sale_price'] = '0';
                 attr_selection['calculate_shipping_fee'] = 'no';
                 attr_selection['shipping_fee'] = '0';
                 attr_selection['track_inventory'] = 'no';
                 attr_selection['delivery_method'] = $(this).find('.delivery_method').val();
                  //alert($(this).find('.sale_price_text').val());
                  if($(this).find('.sale_price').is(':checked')){
                    attr_selection['item_on_sale'] = 'yes';
                    attr_selection['sale_price'] = $(this).find('.sale_price_text').val();
                  }
                  if($(this).find('.shipping').is(':checked')){
                    attr_selection['calculate_shipping_fee'] = 'yes';
                    attr_selection['shipping_fee'] = $(this).find('.shipping_fee_text').val();
                  }
                  if($(this).find('.inventory').is(':checked')){
                    attr_selection['track_inventory'] = 'yes';

                  }

                  attr_arr.push(attr_selection);
                }

              });
      attr['product_attribute_selection'] = attr_arr;

      attr_product_attributes.push(attr);
    }
  });
  
  $('#hidden_product_attribute').val(JSON.stringify(attr_product_attributes));

  $('.appointments').each(function(){
    var app = {};
    if($(this).find('.datepicker').val() != '' && $(this).find('.datepicker_to').val() != ''){
      app['date_from'] = $(this).find('.datepicker').val();
      app['date_to'] = $(this).find('.datepicker_to').val();
      app['spot'] = $(this).find('.spot').val();

      arr_app.push(app);
    }
  });

  $('#hidden_product_appointments').val(JSON.stringify(arr_app));

  $('.tree-selected').each(function(){
    arr.push($(this).find('.value').val());
  });
  var category_arr = $.unique(arr);
  $('#product_categories').val(category_arr);
    //alert(category_arr);

    var arr_tab = [];

    var len = 0;
    $('.product_tabs').each(function(index){
      var object_tab = {};
      var title = $.trim($(this).find('.title').val());
      var content = tinyMCE.get('tab_desc'+index).getContent();

    //alert(title);
   /* if($.trim(title) != ''){
      var tab = title +" !2/!/2!! "+ content +" !2/!/s202";
      arr_tab.push(tab);
    }*/
    if(title != ''){
      object_tab['title'] = title;
      object_tab['content'] = content;
      arr_tab.push(object_tab);
    }
  });
//ert(arr_tab);
   // alert(JSON.stringify(arr_tab));
   $('#hdn_products_tab').val(JSON.stringify(arr_tab));

   $('#hidden_product_description').val(tinyMCE.get('product_description').getContent());

 }
 function validateForm()
 {
   var arr = [];
   $('.tree-selected').each(function(){
    arr.push($(this).find('.value').val());
  });

   var error = false;
   if(arr.length  == 0)
   {
    jQuery('div').remove('#errorCategory');
    jQuery('#alertProductCategory').append(alertMessage('Please Select Category','error','errorCategory'));
    error = true;
  }
  else
    jQuery('div').remove('#errorCategory');

  if($('#txt_product_name').val() == '')
  {
    jQuery('div').remove('#errorProductName');
    jQuery('#alertProductName').append(alertMessage('Please Enter Product Name','error','errorProductName'));
    error = true;
  }
  else
   jQuery('div').remove('#errorProductName');

 if(!intRegex.test($('#txt_quantity').val()) && $('#txt_quantity').val() != '' )
 {
  jQuery('div').remove('#errorProductQty');
  jQuery('#messageAlertDetails').append(alertMessage('Invalid Quantity','error','errorProductQty'));
  error = true;
}
else
 jQuery('div').remove('#errorProductQty');

if(!floatRegex.test($('#txt_price').val()) && $('#txt_price').val() != '')
{
  jQuery('div').remove('#errorProductPrice');
  jQuery('#messageAlertDetails').append(alertMessage('Invalid Product Price','error','errorProductPrice'));
  error = true;
}
else
 jQuery('div').remove('#errorProductPrice');

if(!intRegex.test($('#txt_min_order_qty').val()) && $('#txt_min_order_qty').val() != '')
{
  jQuery('div').remove('#errorMinQty');
  jQuery('#messageAlertDetails').append(alertMessage('Invalid Minimun Order','error','errorMinQty'));
  error = true;
}
else
 jQuery('div').remove('#errorMinQty');  

if(error || globalerror)
  return false;

if(checkbox == 0)
  $('#hdn_no_index').val('N');
else
  $('#hdn_no_index').val('Y');

if(track_inventory == 0)
  $('#track_inventory').val('NO');
else
  $('#track_inventory').val('YES');
return true;
}
function closeModal()
{


  if(action == 'add_product')
    jQuery.post(CONFIG.get('URL')+'products/getLastID',{action:'getLastID'}, function(response,status){
      var id = JSON.parse(response);
      window.location = CONFIG.get('URL')+"eproducts/edit/"+id;
    });
  else{
    location.reload();
    //$('.fileupload-progress').remove();
    //$('#loading').modal('hide');
  }

}
function image()
{
  $('#id-input-file-3').ace_file_input({
    no_file:'No File ...',
    btn_choose:'Choose',
    btn_change:'Change',
    droppable:false,
    onchange:null,

  }).on('change', function(){
    jQuery('div').remove('#errorImage');
    var ext = $('#id-input-file-3').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
    {

      globalerror = true;
      jQuery('#messageAlertForProductImage').append(alertMessage('Invalid Image File','error','errorImage'));
    }
    else
      globalerror = false;

  });;
}
function loadProductCategories()
{
  updateTreeview();
  $('#additional_files_input').ace_file_input({
    style:'well',
    btn_choose:'Drop files here or click to choose',
    btn_change:null,
    no_icon:'icon-cloud-upload',
    droppable:true,
          thumbnail:'small'//large | fit
          //,icon_remove:null//set null, to hide remove/reset button
          /**,before_change:function(files, dropped) {
            //Check an example below
            //or examples/file-upload.html
            return true;
          }*/
          /**,before_remove : function() {
            return true;
          }*/
          ,
          preview_error : function(filename, error_code) {
            //name of the file that failed
            //error_code values
            //1 = 'FILE_LOAD_FAILED',
            //2 = 'IMAGE_LOAD_FAILED',
            //3 = 'THUMBNAIL_FAILED'
            //alert(error_code);
          }

        }).on('change', function(){
          //console.log($(this).data('ace_input_files'));
          //console.log($(this).data('ace_input_method'));
        });

      }
      function changeImage(input)
      {

        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            $('#product_image')
            .attr('src', e.target.result)
            .width(234)
            .height(155);
          };

          reader.readAsDataURL(input.files[0]);
        }
  //alert(image.val());
}

function parent_category()
{
  $('#tag1').empty();
  $('#tag1').append(' <div id="parent_tree" class="tree">');
  var productcategoriesparentDataSource;
  jQuery.post(CONFIG.get('URL')+'eproducts/load_parent_category',{action:'load'}, function(response,status){
    var result = JSON.parse(response);
    var newData = {};

    $.each(result, function(i, field){
      if(field['category_parent'] == 0)
        newData[field['id']] = {name: field['category_name']+'<input type="hidden" class="value_p" value="'+field['id']+'">', type: 'item'};
    });

    productcategoriesparentDataSource = new DataSourceTree({data: newData});

    $('#parent_tree').ace_tree({
      dataSource: productcategoriesparentDataSource ,
      multiSelect:false,
      cacheItems: true,
      loadingHTML:'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
      'open-icon' : 'icon-minus',
      'close-icon' : 'icon-plus',
      'selectable' : true,
      'selected-icon' : 'icon-ok',
      'unselected-icon' : 'icon-remove'
    });


  });


}

function showAddCategoryModal()
{
  parent_category();
  $('#dialog-add').modal('show');
}
function saveCategory()
{
  var err = false;
  var selected_id = 0;
  var current_id = 0;
  var arr = [];
  var c_name = $('#category_name').val();

  $('#parent_tree .tree-selected').each(function(){
   arr.push($(this).find('.value_p').val())
 });

  if(c_name == '')
  {
    jQuery('#messageAlertP_Category').append(alertMessage('Please Fill Category Name','error','errorAddCategory'));
    err = true;
  }

  if(!err)
  {
    if(arr.length === 0)
    {
      jQuery.post(CONFIG.get('URL')+'eproducts/addCategory',{action:'add_category_parent', c_name:c_name}, function(response,status){
        var result = JSON.parse(response);

        if(result == 1)
        {
          updateTreeview();
          $('#category_name').val('');
        }
      });
    }
    else
    {
      jQuery.post(CONFIG.get('URL')+'eproducts/addCategory',{action:'add_category_children', c_name:c_name, arr:arr}, function(response,status){

       var result = JSON.parse(response);

       if(result == 1)
       {
        updateTreeview();
        $('#category_name').val('');
      }
    });
    }
  }

}

function updateTreeview()
{
  //$('#content').load(CONFIG.get('URL')+'products/getData');
  $( "#content" ).load( CONFIG.get('URL')+'eproducts/getData', function( response, status, xhr ) {
    if ( status == "success" ) {
      $('#tags').empty();
      $('#tags').append('<div id="product_category_tree" class="tree"></div>');
      update_tree();
      $('#dialog-add').modal('hide');
    }
    else{
      jQuery('div').remove('#errorCategory');
      jQuery('#alertProductCategory').append(alertMessage('404 Network error - Page will load soon','error','errorCategory'));
      
      setTimeout(function(){location.reload(true);}, 3000);
    }

  });
  
}

function update_tree()
{
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
     /*   $.each($("#product_category_tree").find(".tree-folder"), function(k, v){
            $(this).find('.tree-folder-header').trigger('click');
          });*/

          if(action == 'edit_product'){
            selectCategories($('#selected_categories').val());
          }
        }


        function change_value_track_inventory(){
          if(track_inventory == 0)
            track_inventory = 1;
          else
            track_inventory = 0;
        }

        function load_attributes(){

          load_keypress();
          $('.sale_price').unbind('click');
          $('.shipping').unbind('click');
          $('.inventory').unbind('click');


          $('.sale_price').unbind('click').click(function(e){

            var index = $(this).val();
            var td = $('.sale_'+index[0]+'_'+index[2]);

            td.empty();

            if($(this).prop('checked'))
              td.append('<input type="text" class=" number input-small sale_price_text">');

            load_keypress();
          });
          $('.shipping').click(function(e){

            var index = $(this).val();
            var td = $('.shipping_'+index[0]+'_'+index[2]);

            td.empty();

            if($(this).prop('checked'))
              td.append('<input type="text" class=" number input-small shipping_fee_text">');

            load_keypress();

          });
          $('.inventory').click(function(e){

            var index = $(this).val();
            var td = $('.inventory_'+index[0]+'_'+index[2]);

            td.empty();

            if($(this).prop('checked'))
              td.append('<input type="text" class="input-small number_negative inventory_text">');

            load_negative_keypress();

          });



        }
        function load_keypress(){

         $(".number").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');

            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
              event.preventDefault();
            }
          });

       }
       function load_negative_keypress(){

        $(".number_negative").on("keypress keyup blur",function (e) {


          var charValue = String.fromCharCode(e.keyCode)
          if($(this).val().indexOf('.') != -1){
            charValue = '1';
          }
    //alert(charValue);
    var valid = /[0-9 -()+]+$/.test(charValue);
    if (!valid) {
      e.preventDefault();
    }
  }); 

      }
      function load_colorpicker(){

        $('.colorpicker_attributtes').click(function(e){
          if(!$(this).is(':checked'))
            $(this).next().html(' Tick if this is a color section.');
          else{
            $(this).next().html('  <input class="colorpicker_attr input-small" type="text">');
            $('.colorpicker_attr').colorpicker();
          }

        });
      }
      function load_stop_propagation(){
/*  $('.ui-accordion-header-icon').click(function (e) {
    //alert('ok');
      e.stopPropagation();
    });*/

    $('.textbox').click(function (e) {
      e.stopPropagation();
    });
  }

  function load_appointments_accordion(){
   $("#accordion_products_appointments").accordion({
    collapsible: true ,
    heightStyle: "content",
    animate: 250,
    header: ".accordion-header-new"
  }).sortable({
    axis: "y",
    handle: ".accordion-header-new",
    stop: function( event, ui ) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children( ".accordion-header-new" ).triggerHandler( "focusout" );
          }
        });

  load_datepicker();
}

function load_datepicker(){
  $('.datepicker').datepicker({dateFormat: 'dd/mm/yy'});
  $('.datepicker').on('change', function(e){

    var date_to = $(this).next().next();
    date_to.datepicker("destroy");

    if($(this).val() != ''){
      var date2 = $(this).datepicker('getDate', '+1d');
      date2.setDate(date2.getDate()+1);

      date_to.removeAttr('disabled');
      date_to.datepicker({dateFormat: 'dd/mm/yy', minDate:date2});

    }
    else{
     date_to.attr('disabled', 'true');
     date_to.val('');
   }
 });


  $('.delete_appointments').click(function(){
    alert('delete');
    if (confirm("Are you sure to Delete this Time Period?")== true) {
      $(this).parent().parent().remove();
      $('#accordion_products_appointments').accordion('refresh');
    }
    
  });
}
function add_new_appointments(){
  $('#accordion_products_appointments').append('<div id="accordion" class="group appointments">'+
    '<h3 class="accordion-header-new">Trip Period</h3>'+
    '<div>'+
    '<span>Date From: </span><input type="text" class="input-small datepicker">'+
    '<span> Date To: </span><input type="text" class="input-small datepicker_to" disabled>'+
    '<span> Spot: </span><input type="text" class="input-small spot">'+
    '<button class="btn btn-mini btn-danger pull-right" onclick="delete_appointments(this); return false;"><i class="icon-trash bigger-120"></i></button>'+
    '</div>'+
    '</div>');
  $('#accordion_products_appointments').accordion('refresh');
  load_datepicker();
}
function delete_appointments(obj){

  if (confirm("Are you sure to Delete this Time Period?")== true) {
    obj.parentNode.parentNode.remove();
    $('#accordion_products_appointments').accordion('refresh');
  }
}

function proccess_slug(slug){
  $.post(CONFIG.get('URL')+'eproducts/get_available_slug', {action: 'get', slug:slug},function(response, status){
    var result = JSON.parse(response);

    $('#txt_url_slug').val(result['slug']);
    $('#permalink').text(CONFIG.get('FRONTEND_URL')+'/eproducts/'+result['slug']+'/');
  });
}
function load_blur_functions(text){


  $('#txt_product_name').blur(function(){
        //alert($(this).val());
        if($(this).val()!=''){
          var slug = convertToSlug($(this).val());
          if(text == 'add'){
            $('#link_image').attr('href','https://www.google.com/search?tbs=sur:fmc&tbm=isch&q='+$(this).val());
            $('#link_image').text("Find images for "+$(this).val());
            proccess_slug(slug);
          }else{
            if(slug == $('#hidden_slug').val()){
              $('#txt_url_slug').val($('#hidden_slug').val());
              $('#permalink').text(CONFIG.get('FRONTEND_URL')+'/eproducts/'+$('#hidden_slug').val()+'/');
            }else
            proccess_slug(slug);
          }


          
        }
      });
  $('#txt_url_slug').blur(function(){
    if($(this).val()!=''){
      var slug = convertToSlug($(this).val());
      if(text == 'edit'){
        if($('#hidden_slug').val() == slug){
          $('#txt_url_slug').val($('#hidden_slug').val());
          $('#permalink').text(CONFIG.get('FRONTEND_URL')+'/eproducts/'+$('#hidden_slug').val()+'/');
        }else{
          proccess_slug(slug);
        }
      }else{

        proccess_slug(slug);
      }
    }else{
      var slug = convertToSlug($('#txt_product_name').val())
      proccess_slug(slug);
    }

  });  
}
function edit_thumbnails(id){
  $.post(CONFIG.get('URL')+'eproducts/get_product_gallery_image_by_id', {action:'get', id:id}, function(response, status){
      //alert(response);
      var obj = JSON.parse(response);
      var image = CONFIG.get('FRONTEND_URL')+obj['image_url'];
      $('#modal_picture_gallery').attr('src',image);
      $('#hdn_image_gallery').val(image);
      var $image = $('#cropper-example-2-gallery > img'),
      canvasData,
      cropBoxData;

      $('#cropper-modal-gallery').on('shown.bs.modal', function () {
        $image.cropper({
          autoCropArea: 0.8,
          aspectRatio: 16 / 11,
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
      $('#btn_get_canvass_gallery').click(function(){
       var data = $('#cropper-example-2-gallery > img').cropper('getData');
       var image = $('#hdn_image_gallery').val();

       $.post(CONFIG.get('URL')+'products/crop',{action:'crop', data:data, image:image}, function(response,status){

        if
          (JSON.parse(response) == '')
        location.reload(true);
        else
          alert(response);
      });
     });
      $('#cropper-modal-gallery').modal('show');
    });
}

function delete_additional_files(id){
 if(confirm("Are you sure to delete this file?"))
  $.post(CONFIG.get('URL')+'eproducts/delete_additional_files',{action:'delete', id:id}, function(response,status){
   location.reload(true);
 });
return false;
}
function sort_additional_files(){
  var data = [];
  $('.hidden_additinal_files_id').each(function(){
    //alert($(this).val());
    data.push($(this).val());
  });
  
  $.post(CONFIG.get('URL')+'eproducts/sort_additional_files',{action:'sort', data:data}, function(response,status){
  });
}