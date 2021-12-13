global_error = false;
arr_pages_name = [];
var can_add = true;
$(document).ready(function(){
  $(".delete_node_a").click(function(e){
    e.preventDefault();
    var id = $(this).find('.hdn_node_div_id').val();
    delete_menu(id);
  });
  selected = false
  $('.select_all_pages').click(function(e){
    e.preventDefault();
    if(!selected)
      $('.page_checkbox').each(function() 
      {
       $(this).prop('checked',true);
       $('.select_all_pages').text('Unselect All');
       selected = true;
     });
    else
      $('.page_checkbox').each(function() 
      {    
       $(this).prop('checked',false);
       $('.select_all_pages').text('Select All');
       selected = false;
     });
  });
  $( "#accordion" ).accordion({
    collapsible: true ,
    heightStyle: "content",
    animate: 250,
    header: ".accordion-header"
  });
  $('.slim-scroll').each(function () {
    var $this = $(this);
    $this.slimScroll({
      height: $this.data('height') || 100,
      railVisible:true
    });
  });
  $( "#accordion_new_2" ).nestable();
  
  $( "#accordion_new" ).accordion({
    collapsible: true ,
    heightStyle: "content",
    animate: 250,
    active: false,
    header: ".accordion-header"
  }).sortable({
    axis: "y",
    handle: ".accordion-header",
    stop: function( event, ui ) {
      ui.item.children( ".accordion-header" ).triggerHandler( "focusout" );
    }
  });

  var arr = [];
  jQuery.post(CONFIG.get('URL')+'menus/get_names',{action: 'get'}, function(response,status){
    var result = JSON.parse(response);
    $.each(result, function(i, field){
      arr.push(field['name']);
    });
  });

  var name_timeout = setTimeout(function(){}, 1000);
  $('#name').keyup(function(){
    global_error = false;
    jQuery('div').remove('#errorMenuTitleExist');

    clearTimeout(name_timeout)
    $("#btn-add-menu").addClass('disabled');
    can_add = false;
    name_timeout = setTimeout(function(){
      $.post(CONFIG.get('URL')+'menus/menu_name_available', {
        action: 'check', 
        menu_id: $("#menu_select").val(), 
        menu_name: $("#name").val(), 
      }, function(response, status){
        if (!response) {
          jQuery('#alert_menu').append(alertMessage('Menu name already exist','error','errorMenuTitleExist'));
          global_error = true;
        }else{
          $("#btn-add-menu").removeClass('disabled');
          can_add = true;
        }
      })
    }, 1000);

    return;

    if($(this).val() != '')
      if(jQuery.inArray($(this).val(), arr ) >= 0){
        jQuery('#alert_menu').append(alertMessage('Menu name already exist','error','errorMenuTitleExist'));
        global_error = true;
      }
  });
  $('#menu_select').val($('#hdn_current_edited_menu').val()).trigger('chosen:updated');

  $('#menu_select').change(function(){
    // window.location.href=CONFIG.get('URL')+"menus/edit/"+$(this).val();
    get_menu_items($(this).val());
  }).find('option:first-child').attr('selected', 'selected');
  $("#menu_select").trigger('chosen:updated').trigger('change');

  $("#btn-delete-menu").click(function(){
    bootbox.confirm("Are you sure you want to delete selected menu?", function(result){
      if (result) {
        var menu_id = $("#menu_select").val();

        delete_menu( menu_id );
      }
    });
  });

  $("#language").change(function(){
    get_menu_items( $('#menu_select').val() );
  });
});

function add_page_ui(){
  $('.page_checkbox:checked').each(function(){
    var random_id = stringGen(5);
    var text = $(this).val();
    var post_id = $(this).siblings('input.id').val();  

    var data = [{
      'detail' : {
          id : 0,
          label : text,
          guid : post_id,
          url : "",
          title : "",
          tag_target : "_self",
          description : "",
          type : "page",
          type_label : "type_label",
          css : "",
          parent : 0,
          sort_order : 0,
          group_id : ++field_counter,
        },
        'children' : {}
      }
    ];

    if (!in_menu_item( post_id )) {
      var field_forms = generate_menu_item( data );
      var m = $(field_forms.html());
      m.find(".delete_node_a").click(function(e){
        e.preventDefault();
        bootbox.confirm("Are you sure you want to delete selected menu item?", function(result){
          console.log(field_forms.html());
          if (result) {
            m.remove();
          }
        });
      });
      $("#accordion_new_2 >ol.dd-list").append( m );
    }else{
      notification("Menu Item", "Selected page is already added!", "gritter-error")
    }
  });

  return;
}
function add_to_menu_link(){
  var reg_ex = /^HTTP|HTTP|http(s)?:\/\/(www\.)?[A-Za-z0-9]+([\-\.]{1}[A-Za-z0-9]+)*\.[A-Za-z]{2,40}(:[0-9]{1,40})?(\/.*)?$/;
  var err = false;
  jQuery('div').remove('#errorUrl');
  jQuery('div').remove('#errorText');

  var url = $('#url_link_ui').val();
  var text = $('#link_text_ui').val();

  if(url == ''){
    jQuery('#alert_link').append(alertMessage('Please fill url.','error','errorUrl'));
    err = true;
  }
  else{
    if(!reg_ex.test(url)){
      jQuery('#alert_link').append(alertMessage('Invalid url.','error','errorUrl'));
      err = true;
    }
  }
  if(text == '' && url != ''){
    jQuery('#alert_link').append(alertMessage('Please fill link text.','error','errorText'));
    err = true;
  }

  // var text = "";//$(this).val();
  var post_id = 0; //$(this).siblings('input.id').val();  

  var data = [{
    'detail' : {
        id : 0,
        label : text,
        guid : post_id,
        url : url,
        title : "",
        tag_target : "_self",
        description : "",
        type : "link",
        type_label : "type_label",
        css : "",
        parent : 0,
        sort_order : 0,
        group_id : ++field_counter,
      },
      'children' : {}
    }
  ];

  if(!err){
    var field_forms = generate_menu_item( data );
    $("#accordion_new_2 >ol.dd-list").append( field_forms.html() );
  }
}

function in_menu_item($id=0){
  var inMenuItem = false;

  $("#accordion_new_2 .group").each(function(){
    var group = $(this);
    if ($(this).find(".group-hidden-fields>.menu_guid").length) {
      var post_page_id = $(this).find(".group-hidden-fields>.menu_guid").val();

      if ($id != 0 && post_page_id == $id) {
        inMenuItem = true;

        var accordion_group = group.closest('.accordion-group');

        if (!accordion_group.find('.accordion-body').hasClass('in')) {
          accordion_group.find('.accordion-toggle').trigger('click');
        }

        accordion_group.find('input[type="text"]').each(function(){
          $(this).focus();
          return false;
        });

        return;
      }
    }
  });

  return inMenuItem;
}
//FUNCTION THAT WILL GIVE RANDOM NUMBER 
function stringGen(len){
  var text ='';

  var charset = "0123456789";

  for( var i=0; i < len; i++ )
    text += charset.charAt(Math.floor(Math.random() * charset.length));

  return text;
}
//ADDING MENU IT WILL GET ALL THE NODES AND ADDED IT TO THE ARRAY
function add_menu(){
  if (can_add) {
    var name = $('#name').val();
    var nodes = [];
    $('#accordion_new_2 .group').each(function(index){
      var kind = $(this).find('.kind').val();
      var data = {};

      data["label"] = $(this).find('.link_text').val();
      data["guid"] = $(this).find('.post_id').val();
      data["url"] = $(this).find('.link_url').val();
      data["title"] = $(this).find('.link_title').val();
      data["target"] = "";
      data["description"] = "";
      data["type"] = kind;
      data["css"] = "";
      data["parent"] = "";
      data["sort_order"] = "";
      data["return_id"] = $(this).attr('id');;

      nodes.push(data);
    });

    if(!global_error && !validate_menu()){
      jQuery.post(CONFIG.get('URL')+'menus/add_new_menu', {action: 'save', name:name, nodes:nodes}, function(response, status){
        var result = JSON.parse(response);
        jQuery('div').remove('#errorMenuTitle');

        if (typeof result['menu_id'] != 'undefined') {
          jQuery('#alert_menu').append(alertMessage('Sucessfully Added','success','errorMenuTitle'));

          setTimeout(function(){
            window.location.href=CONFIG.get('URL')+"menus/?menu_id=" + result['menu_id'];
          }, 1000);
        }else{
          jQuery('#alert_menu').append(alertMessage('Error while adding. Please try again','error','errorMenuTitle'));
        }

        return;
        if(result == 1){
          jQuery('#alert_menu').append(alertMessage('Sucessfully Added','success','errorMenuTitle'));
          setTimeout(function(){
           window.location.href=CONFIG.get('URL')+"menus/";
         }, 1000);
          
        }
        else{
          jQuery('#alert_menu').append(alertMessage('Error while adding. Please try again','error','errorMenuTitle'));
        }
      });
    }
  }else{
    notification("Menu", "Unable to add menu. Checking for menu name availability...", "gritter-error");
  }
}

//UPDATE MENU IT WILL UPDATE MENU BY ITS NAME AND NODES
function update_menu(){
  var name = $('#name').val();
  var id = $('#menu_select').val();
  var nodes = get_menu_data( $("#accordion_new_2").find('>ol.dd-list') );

  if(!global_error && !validate_menu()){
    jQuery.post(CONFIG.get('URL')+'menus/update_new_menu/', {
      action: 'save', 
      name:name, 
      id:id, 
      nodes:nodes,
      lang: $("#language").length>0 ? $("#language").val() : '',
    }, function(response, status){
      get_menu_items( id );
      var old_menu_text = $('#menu_select').find("option[value='"+ id +"']").text();
      var new_menu_text = $("#name").val();

      $('#menu_select').find("option[value='"+ id +"']").text( new_menu_text ).trigger('chosen:updated');
    });
    
  }
}

var update_data_note_counter = 0;
function get_menu_data(item_container, menu_parent){
  menu_parent = typeof menu_parent != "undefined" ? menu_parent : 0;

  var return_data = [];
  $.each(item_container.find(">li.dd-item"), function(k, v){
    var detail_container = $(this).find(">.dd2-content").find('.group');
    var detail_id = detail_container.find('.menu_id').val();
    var chind_container = $(this).find(">ol.dd-list");
    var n_detail = {
        "id" : detail_id,
        "label" : detail_container.find('.menu_text').val(),
        "guid" : detail_container.find('.menu_guid').val(),
        "url" : detail_container.find('.menu_url').val(),
        "title" : detail_container.find('.menu_title').val(),
        "target" : detail_container.find('.menu_target').val(),
        "description" : detail_container.find('.menu_description').val(),
        "type" : detail_container.find('.menu_type').val(),
        "css" : "",
        "parent" : menu_parent,
        "sort_order" : ++update_data_note_counter,
        "return_id" : detail_container.find('.menu_return_id').val(),
        "meta" : detail_container.find('.menu_enable').is(":checked") ? 'Y' : 'N',
    }

    var children = [];

    if (chind_container != 'undefined' && chind_container.length > 0 && chind_container.find('li').length > 0) {
      children = get_menu_data(chind_container, detail_id);
    }

    return_data.push({
      'detail' : n_detail,
      'children' : children,
    })
  });

  return return_data;
}
//VALIDATE MENU TITLE 
function validate_menu(){

  error = false;
  var name = $('#name').val();

  jQuery('div').remove('#errorMenuTitle');

  if(name == '')
  {
    jQuery('#alert_menu').append(alertMessage('Please Enter Menu Name','error','errorMenuTitle'));
    error = true;
  }

  return error;
}

function get_menu_items(selected_menu_id = ""){
  if (typeof selected_menu_id == "undefined") { return null;}
  if (selected_menu_id == "") { return null;}

  $("#name").val($("#menu_select").find("option[value='"+ $("#menu_select").val() +"']").text());


  $("#accordion_new_2").html( '<div class="alert alert-info">Loading Menu Items... Please Wait...</div>' ); 

  jQuery.post(CONFIG.get('URL')+'menus/menu_processor/',{
    action: 'get-menu-items',
    id: selected_menu_id,
    lang: $("#language").val(),
  }, function(response,status){
    data = JSON.parse(response);
    field_counter = 0;
    var field_forms = generate_menu_item(data);

    $("#accordion_new_2").html( field_forms );
    $("#accordion_new_2").find('.group').each(function(){
      initDeleteBtn( $(this).find('.delete_node_a') );
    })
  });
}

var field_counter = 0;
function generate_menu_item( $data = [] ){
  var ol = $('<ol class="dd-list"></ol>');
  $.each($data, function(k, v){
    var type_label = "Custom";
    
    switch(v['detail']['type']) {
      case "page":
        type_label = "Page";
        break;
      case "post":
        type_label = "Post";
        break;
      case "product":
        type_label = "Product";
        break;
      default:
        type_label = "Custom Link";
    }

    $translation = typeof v['detail']['translation'] !='undefined' && cms_function.isJSON(v['detail']['translation']) && v['detail']['translation'] !='' ? JSON.parse(v['detail']['translation']) : {};

    var meta     = v['detail']['meta'];
    var enabled  = meta != "" ? (meta == "Y" ? true : false) : true;

    var detail = {
      "menu_id" : v['detail']['id'],
      "menu_original" : v['detail']['label'],
      "menu_label" : typeof $translation['label'] != 'undefined' ? $translation['label'] : v['detail']['label'],
      "menu_label_trimmed" : strip_tag( typeof $translation['label'] != 'undefined' ? $translation['label'] : v['detail']['label'] ),
      "menu_sub_id" : v['detail']['guid'],
      "menu_url" : v['detail']['url'],
      "menu_title" : typeof $translation['title'] != 'undefined' ? $translation['title'] : v['detail']['title'],
      "menu_target" : v['detail']['tag_target'],
      "menu_description" : v['detail']['description'],
      "menu_type" : v['detail']['type'],
      "menu_type_label" : type_label,
      "menu_css" : v['detail']['css'],
      "menu_parent" : v['detail']['parent'],
      "menu_sort_order" : v['detail']['sort_order'],
      "menu_item_id" : ++field_counter,
      "menu_group_id" : v['detail']['group_id'],
    }

    var item = $('#menu-item-template').tmpl(detail);

    var fields = "";
    if (detail['menu_type'] == 'page') {
      fields = $('#menu-item-field-template-page').tmpl(detail)
    }else if (detail['menu_type'] == 'link') {
      fields = $('#menu-item-field-template-link').tmpl(detail)
    }else{
      fields = $('#menu-item-field-template-page').tmpl(detail)
    }
    item.find(".accordion-inner").append(fields);
    item.find(".menu_enable").prop('checked', enabled);


    if (v['children'].length > 0) {
      var child_ol = generate_menu_item( v['children'] );
      item.append( child_ol );
    }

    ol.append(item);

  });

  return ol;
}

function initDeleteBtn(this_btn){
  this_btn.click(function(){
    var the_delete_btn = $(this);
    bootbox.confirm("Are you sure you want to delete selected menu?", function(result){
      if (result) {
        the_delete_btn.closest(".dd-item").remove();
      }
    });
  });
}

function delete_menu( menu_id ){
  if ( menu_id != "" ) {
    jQuery.post(CONFIG.get('URL')+'menus/delete_menu',{action: 'delete', id : menu_id}, function(response,status){
      var result = JSON.parse(response);
      $("#menu_select").find('option[value="'+ menu_id +'"]').remove();
      $("#menu_select").trigger('chosen:updated').trigger('change');
    });
  }else{
    notification("Menu", "No selected Menu", "gritter-error");
  }
}