var can_edit_photo = true;
var error = false;
var current_id = 0; //for edit 
$(document).ready(function(){
  ready();
});

/*function back_in_category(){
    
    $(document).keydown(function(e){
      
       if(e.keyCode == 8) {

         if(!$("#name").is(":focus") && !$('.chosen-drop .chosen-search input[type="text"]').is(':focus') && !$("#description").is(":focus") ){
            e.preventDefault();
            window.location.replace(CONFIG.get('URL')+'products/categories/');
          }
        }
    });
}
*/
function ready(){
  

  var action = $('#action').val();

  if(action == 'manage'){
      load_category_table();
      //history.pushState();
    }
  else if(action == 'add'){
      //back_in_category();
      
      load_html_functions();
      initialize_tiny();
      load_parents(0,0);
    }
  else if(action == 'edit'){
      

      current_id = $('#hdn_id').val();
      //back_in_category();

      load_html_functions();
      load_category(current_id);

      $('#btn_get_canvass').click(function(){
         var data = $('#cropper-example-2 > img').cropper('getData');
         var image = $('#hdn_image').val();
         //alert(image);
         //alert('ok');
          $.post(CONFIG.get('URL')+'product-categories/crop',{action:'get', data:data, image:image}, function(response,status){
         //  alert(response);
           if(JSON.parse(response) == '')
              location.reload(true);
          });
      });
      
    }
  else if(action == 'sort'){
   // back_in_category();
    load_sort();
  }

 $('.remove').click(function(){

  $('#image_view').attr('src',$('#hdn_image').val());
  
  if(action == 'edit')
  {
    if(can_edit_photo)
      $('#btn_edit_photo').removeClass('hide');
  }
 });

$('#product_category_form').ajaxForm({
       beforeSend: function(){
       $('#loads').modal('show');
       },
       complete: function(xhr) {
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
            setTimeout(function(){window.location = CONFIG.get('URL')+'products/categories/';}, 2000);

        }
        else{
           jQuery('div').remove('#save_error');
           jQuery('#alertProductCategory').append(alertMessage('Error while saving','error','save_error'));
        }
      },   
  });


}
function initialize_tiny(){
  tinyMCE.init({selector:'#description_tiny',
                    menubar: " view edit format table tools",
                    height : 200,
                    toolbar:["nonbreaking forecolor backcolor  undo redo styleselect bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent  ", " indent link image imageuploader"],
                    oninit : "setPlainText",
                    plugins: [
                        "paste link image table code",
                        "imageuploader",
                        "textcolor",
                        "colorpicker wordcount nonbreaking"
                            ],
                  external_plugins:{'imageuploader':'plugins/imageuploader/editor_plugin_src.js'},
                  relative_urls: false,
                  convert_urls: false,
                  nonbreaking_force_tab: true,
                   tools: "inserttable"
                 
  });
}
function load_sort()
{

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
              // IE doesn't register the blur when sorting
              // so trigger focusout handlers to remove .ui-state-focus
              ui.item.children( ".accordion-header" ).triggerHandler( "focusout" );
            }
          });
  });
  
}
function go_to_sort()
{
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
function load_category(id){
  
   jQuery.post(CONFIG.get('URL')+'product-categories/get_category',{action:'get', id:id}, function(response,status){

    var result = JSON.parse(response);
    var url = window.location.href.split('/');
    var image_url = result['image_url'].split('/');
    var image = result['image_url'].replace('/images/','/thumbnails/205x154/');
    $('#name').val(result['category_name']);
    $('#description_tiny').val(result['category_description']);
    initialize_tiny();
   // alert(result['image_url']);
    
    $('#modal_picture').attr('src',result['image_url']);
    $('#hdn_image').val(result['image_url']);
    $('#hide').val(result['hide_category']);
    $('#url_slug').val(result['url_slug']);
    $('#old_slug').val(result['url_slug']);
    $('#permalink').text(CONFIG.get('FRONTEND_URL')+'/categories/'+result['url_slug']);
    $('#permalink').attr('href',CONFIG.get('FRONTEND_URL')+'/categories/'+result['url_slug']);
    load_parents($('#hdn_id').val(), result['category_parent']);

  //  alert(result['image_url']);
    if(result['image_url'] != ''){
      $('#image_view').attr('src',image);
      //alert('ok');
      if(url[2] != image_url[2]){
          can_edit_photo = false;
          $('#image_error_msg').append('Please Upload a new picture for the site');
        }
      else
        $('#btn_edit_photo').removeClass('hide');
    }

  });
}
function load_parents(id,select){

   jQuery.post(CONFIG.get('URL')+'product-categories/load_parents',{action:'get'}, function(response,status){
    var result = JSON.parse(response);
     //$("#parent").append('<option value=0>None</option>');
    $.each(result, function(i, field){
      if(field['id'] != current_id)
        if(field['id'] == id && id > 0)
              var option = $('<option></option>').attr("value", field['id']).text(field['category_name']).attr('selected','selected');
          else
              var option = $('<option></option>').attr("value", field['id']).text(field['category_name']);
        $("#parent").append(option);
    });

    $('#parent').chosen();
    $('#parent').val(select).trigger('chosen:updated');

   });

}

function load_category_table(){

  $('#productCategoriesTable').dataTable().fnDestroy();
  jQuery.post(CONFIG.get('URL')+'product-categories/load_categories',{action:'get'}, function(response,status){
  $('#productCategoriesTable tbody').html('');

  var result = JSON.parse(response);
  $.each(result, function(i, field){
  if(field['category_parent'] == ' '){
    $('#productCategoriesTable tbody').append('<tr><td class="center"><label><input type="checkbox" class="ace"/><span class="lbl"></span></label></td><td>'+field['category_name'].substring(0,38)+'</td><td>'+field['category_description']+'</td><td></td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group"><button class="btn btn-minier btn-info" onclick="edit_category('+field['id']+')"><i class="icon-edit bigger-120"></i></button><button class="btn btn-minier btn-danger" onclick="show_delete_category('+field['id']+')"><i class="icon-trash bigger-120"></i></button></div></td></tr>');
  }else{
    $('#productCategoriesTable tbody').append('<tr><td class="center"><label><input type="checkbox" class="ace"/><span class="lbl"></span></label></td><td>'+field['category_name'].substring(0,38)+'<br><i>Parent: '+field['category_parent'].substring(0,30)+'</i></td><td>'+field['category_description']+'</td><td></td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group"><button class="btn btn-minier btn-info" onclick="edit_category('+field['id']+')"><i class="icon-edit bigger-120"></i></button><button class="btn btn-minier btn-danger" onclick="show_delete_category('+field['id']+')"><i class="icon-trash bigger-120"></i></button></div></td></tr>');
  }
  });
 
  $('#productCategoriesTable').dataTable( {
       "bPaginate": false,
      "aoColumns": [
       { "bSortable": false },
       null, 
       null,
       null,
       { "bSortable": false}
      ]});
  //$('#productCategoriesTable_filter').hide();
  $('#delete').modal('hide');
  });
  
}
function show_delete_category(id){
  $('#hdn_id').val(id);
  $('#delete').modal('show');
}
function delete_category()
{
  var id = $('#hdn_id').val();
  jQuery.post(CONFIG.get('URL')+'product-categories/delete_categories',{action:'delete', id:id}, function(response,status){

    result = JSON.parse(response);
    if(result == 1)
    {
      load_category_table();
    }
    $('#hdn_id').val(0);
  });
}
function edit_category(id){
   window.location = CONFIG.get('URL')+'products/categories/edit/'+id;
}
function load_html_functions()
{
  
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

    $('#name').blur(function(e){
       if($(this).val()!=''){
          var slug = convertToSlug($(this).val());
            proccess_slug(slug);
        }
    });
     $('#url_slug').blur(function(){
        if($(this).val()!=''){
          var slug = convertToSlug($(this).val());
          proccess_slug(slug);
        }

      });
  
}
function proccess_slug(slug){
  $.post(CONFIG.get('URL')+'product-categories/get_available_slug', {action: 'get', slug:slug},function(response, status){
        var result = JSON.parse(response);
       
        $('#url_slug').val(result['slug']);
        $('#permalink').text(CONFIG.get('FRONTEND_URL')+'/categories/'+result['slug']+'/');
  });
}
function validate_image(image)
{
    jQuery('div').remove('#error_image');

    if (!image.match(/(?:gif|jpg|png|bmp)$/)) {
         jQuery('#alertProductCategory').append(alertMessage('Invalid Image','error','error_image'));
         error = true;
    }
    else
         error = false;
}

function validate_form()
{
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

function reset_form()
{
  $('#name').val('');
  $('#slug').val('');
  $('#image').val('');
  $('#parent').val(0).trigger('chosen:updated');
  $('#description').val('');
  $('.remove').trigger('click');

  window.location = CONFIG.get('URL')+'products/categories';
}

function go_to_add()
{
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

function reset_edit_form()
{
   window.location = CONFIG.get('URL')+'products/categories/edit'+$('#hdn_id').val();
}