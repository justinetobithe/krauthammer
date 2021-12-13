var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var regexContactNumber = /^[0-9-+]+$/;
var checkbox = 0;
var url = '';
var parent = '';
var seo_title_limit = 200;
var seo_description_limit = 260;

$(document).ready(function(){
  var action = $('#action').val();

  $("#seo_title_limit_label").html(seo_title_limit);
  $("#seo_description_limit_label").html(seo_description_limit);
  
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
      if ($.inArray(e.keyCode, ingnore_key_codes) <= 0)
        $(this).val($(this).val().substring(0,seo_description_limit));
    }
  });
  $("#btn_save_product").click(function(e){
    e.preventDefault();

    addData();

    if ($("#page_template").val().toLowerCase().includes("blog")) {
      var arr = [];
      $('#page-category-tree .tree-selected').each(function(){
        var item = $(this).find('span').attr('data-value');
        if ( item != 'all' ) arr.push( item );
      });
      $('#page_blog_categories').val(arr.join());
    }else{
      hideBlogCategories();
    }

    $('#page_form').trigger("submit");
  });
  
  $("#seo_no_index").change(function(){
      $("#hdn_no_index").val( $(this).is(":checked") ? 'Y' : 'N');
  });

  setPageCategory();
  $('.dropdown-select').chosen().siblings('.chosen-container').find('.chosen-search').hide();

  $("#author-field").chosen({width:"100%"});

  $("#pagesTable").find("th input[type=checkbox]").change(function(e){
    if ($(this).is(":checked")) {
      $("#pagesTable").find("tb input[type=checkbox]").each(function(k, v){
        
      });
    }else{

    }
  });

  // Setting Toggler
  if (cms_cookie.is('page-main-content')) {
    $("#toggle-main-page-content").removeProp('checked');
  }else{
    $("#toggle-main-page-content").prop('checked', 'checked');
  }
  $("#toggle-main-page-content").change(function(e){
    if ($(this).is(":checked")) {
      cms_cookie.delete('page-main-content');
    }else{
      cms_cookie.set('page-main-content', 'hidden', 30);
    }

    if (cms_cookie.is('page-main-content')) {
      $("#content").closest('.widget-box').hide();
    }else{
      $("#content").closest('.widget-box').show();
    }
  }).trigger('change');

  if (cms_cookie.is('page-settings')) {
    $("#toggle-other-settings").removeProp('checked');
  }else{
    $("#toggle-other-settings").prop('checked', 'checked');
  }
  $("#toggle-other-settings").change(function(e){
    if ($(this).is(":checked")) {
      cms_cookie.delete('page-settings');
    }else{
      cms_cookie.set('page-settings', 'hidden', 30);
    }

    if (cms_cookie.is('page-settings')) {
      $("#myTab3").closest('.tabbable').hide();
    }else{
      $("#myTab3").closest('.tabbable').show();
    }
  }).trigger('change');
});

function load_archived(){
  $('#newer').removeClass('btn-info');
  $('#older').removeClass('btn-info');

  $('#newer').unbind('click');
  $('#older').unbind('click');


  $.post(CONFIG.get('URL')+'pages/get_archiveds',{action:'get', id:$('#hdn_page_id').val()}, function(response,status){
    var obj = JSON.parse(response);
    var count = 0;

    if($.isEmptyObject(obj) == false){
      count = $.map(obj['archived'], function(n, i) { return i; }).length;
      $('#older').addClass('btn-info');
    }else{
      $('#older').removeClass('btn-info');
    }

    $('#older').find('span').text(count);

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
        change_content(obj['archived'][i],'no');
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
          change_content(obj['archived'][ct],'no');
        }
        else
          change_content(obj['default'][0],'yes');
        if(ct >= count){
          $('#newer').removeClass('btn-info');
          $('#older').addClass('btn-info');
        }
      }

      e.preventDefault();
    });
  });
}

function change_content(data, last){
  $('#page_template').val(data['page_template']);
  $('#parent_id').val(data['parent_id']);
  $('#parent_id').trigger('change');
  // tinyMCE.activeEditor.setContent(data['post_content']);
  $('#txt_post_title').val(data['post_title']);
  $('#txt_url_slug').val(data['url_slug']);
  $('#seo_title').val(data['seo_title']);
  $('#seo_description').val(data['seo_description']);
  $('#status').val(data['status']);

  tinyMCE.activeEditor.setContent(data['post_content']);
}

//VALIDATE FORM
function validateForm(){
  var error = false;

  jQuery('div').remove('#errorPageTitle');

  if($('#txt_post_title').val() == ''){
    /*jQuery('#alertPage').append(alertMessage('Please Enter Page Title','error','errorPageTitle'));*/
    notification("Page", "Error: Missing Page Title", "gritter-error");
    return false;
  }

  if(checkbox == 0)
    $('#hdn_no_index').val('N');
  else
    $('#hdn_no_index').val('Y');

  return true;
}

function loadPageTable(){
  $('#pagesTable').dataTable().fnDestroy();

  jQuery.post(CONFIG.get('URL')+'pages/get',{action:'getPages'}, function(response,status){
    var result = JSON.parse(response);

    $.each(result, function(i, field){
      var status = '';
      var admin_editor_link = typeof field['admin_editor_link'] != 'undefined' ? field['admin_editor_link'] : "";

      if(field['post_status'] == 'draft')
        status = " - <strong>DRAFT</strong>";

      var edit_btn = '<a href="'+ admin_editor_link +'" target="_blank" class="btn btn-minier btn-info" data-rel="tooltip" data-placement="top" title="Edit"><i class="icon-edit bigger-120"></i></a>';

      $('#pagesTable tbody').append('<tr><td class="center"><label><input type="checkbox" class="ace"/><span class="lbl"></span></label></td><td>'+field['post_title']+status+' </td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group">'+ edit_btn +'<button class="btn btn-minier btn-danger " data-rel="tooltip" data-placement="top" title="Delete" onclick="deletePage('+field['id']+')"><i class="icon-trash bigger-120"></i></button></div></td></tr>');
    });

    $('[data-rel=tooltip]').tooltip();
    $('#pagesTable').dataTable({
      "aoColumns": [
      { "bSortable": false },
      null,
      { "bSortable": false}
      ]
    });
  });
}
function closeModal(id){
  window.location.href = CONFIG.get('URL')+"pages/edit/"+id;
}
function goToPages(){
  /*jQuery('#alertPage').empty();*/
  /*jQuery('#alertPage').append(alertMessage('Successfully Save Page','success','Success1'));*/
  notification("Page", "Successfully Save PagePage", "gritter-success")
  $("#loading").modal('hide');
  $('html, body').animate({
    scrollTop: $('#alertPage').offset().top - 20
  }, 'slow');
  load_archived();
}

function addData(){
  $('#hdn_content').val(tinyMCE.get('content').getContent());
}
function editPage(id){
  window.location.href = CONFIG.get('URL')+"pages/edit/"+id;
}
function change_value(){
  if(checkbox == 0)
    checkbox = 1;
  else
    checkbox = 0;
}
function deletePage(id){
  $('#hidden_id').val(id);
  $('#delete_msg h5').text("Are you sure to delete this Page?");
  $('#delete').modal('show');
}
function deletePageModal(){
  var id = $('#hidden_id').val();
  jQuery.post(CONFIG.get('URL')+'pages/deletePage', {action: 'delete', id:id},function(response,status){
    var result = JSON.parse(response);
    if(result == '1')
      window.location.href=CONFIG.get('URL')+"pages";
    else
    {
      $('#delete_msg h5').text("Unable to Delete Page");
    }
  });
}

function proccess_slug(slug, parent_id, page_id, lang = $("#language").val()){
  $('#btn_save_product').attr('disabled', 'disabled');
  var parent_id = typeof parent_id != 'undefined' ? parent_id : 0;
  var page_id = typeof page_id != 'undefined' ? page_id : 0;
  // var parent_id = $("#parent_id").length > 0 ? $("#parent_id").val() : 0;
  $("#permalink").hide();
  $.post(CONFIG.get('URL')+'pages/get_available_slug/', {action: 'get', slug:slug, parent_id:parent_id, page_id:page_id, lang : lang},function(response, status){
    var result      = JSON.parse(response);
    var url_slug    = result['slug'];
    var site_url    = result['siteurl'];
    var parent_url  = result['parent_slug'];
    var trail_slash = result['trail_slash'];
    var is_home     = result['is_home'];

    $('#txt_url_slug').val(url_slug);

    set_permalink(url_slug, parent_url, trail_slash, is_home);

    $('#btn_save_product').removeAttr('disabled');

    $("#permalink").show();
  });
}

function show_category_modal(){
  $('#categories_model').modal('show');
}

function setPageCategory(){
  $("#page_template").trigger('change');
  var id = $("#hdn_page_id").length > 0 ? $("#hdn_page_id").val() : 0;
  jQuery.post(CONFIG.get('URL')+'pages/get_blog_post_categories/', {action: 'get', id : id},function(response,status){
    var sourceData = new DataSourceTree({data: response});

    $('#page-category-tree').ace_tree({
      dataSource: sourceData ,
      loadingHTML:'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
      'open-icon' : 'icon-minus',
      'close-icon' : 'icon-plus',
      'selectable' : true,
      'multiSelect' : true,
      'selected-icon' : 'icon-ok',
      'unselected-icon' : 'icon-remove'
    });

    $('#page-category-tree .tree-item').each(function(){
      if ($(this).find('span').attr('data-value') == 'all') {
        $(this).click(function(){
          if (!$(this).hasClass('tree-selected')) {
            $(this).nextAll(".tree-item").addClass('tree-selected').find("i").removeClass("icon-remove").addClass("icon-ok");
          }else{
            $(this).nextAll(".tree-item").removeClass('tree-selected').find("i").removeClass("icon-ok").addClass("icon-remove");
          }
        });
      }else{
        $(this).click(function(){
          var all_category = $('#page-category-tree').find('span[data-value="all"]').closest('.tree-item');
          all_category.removeClass('tree-selected').find("i").removeClass("icon-ok").addClass("icon-remove");
        });
      }
    });
  });
}
function hideBlogCategories(){
  $('#page_blog_categories').val("");
  $('#page-category-tree .tree-item').each(function(){
    $(this).nextAll(".tree-item").removeClass('tree-selected').find("i").removeClass("icon-ok").addClass("icon-remove");
  });
}

function set_permalink(url_slug, url_parent, trail_slash, is_home){
  url_slug    = typeof url_slug == 'undefined' ? "" : (url_slug == "" ? "" : "/" + convertToSlug(url_slug));
  url_parent  = typeof url_slug == 'undefined' ? "" : url_parent;
  trail_slash = typeof trail_slash == 'undefined' ? "" : trail_slash;

  var new_permalink = url_parent + (!is_home ? url_slug+trail_slash : '');

  $('#permalink').text(new_permalink);
  $('#permalink').attr('href', new_permalink);
  $('#link').val( new_permalink );
}