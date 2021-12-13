$(document).ready(function(){
  initialize();
});

function initialize(){
	current_id = $('#hdn_id').val();

  $('#btn_get_canvass').click(function(){
    var data = $('#cropper-example-2 > img').cropper('getData');
    var image = $('#hdn_image').val();
    $.post(CONFIG.get('URL')+'product-categories/crop',{action:'get', data:data, image:image}, function(response,status){
      if(JSON.parse(response) == ''){
        location.reload(true);
      }
    });
  });

  $("#parent").change(function(){
    $("#url_slug").trigger('blur'); 
  });
  $("#languages").change(function(){
    if($('#url_slug').val()!=''){
      var slug = slugify($('#url_slug').val());
      // process_slug(slug);

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

        $('#url_slug').val(result['detail']['url_slug']);
        $('#permalink').text(result['detail']['permalink']).attr("href", result['detail']['permalink']);

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
  });

  load_html_functions();
  load_category(current_id, function(){
    $.post(CONFIG.get('URL')+'product-categories/get_available_slug/', {
      action: 'get', 
      slug:$("#url_slug").val(), 
      current_id: current_id, 
      parent_id: $("#parent").val(),
      lang: $("#languages").val(),
    },function(response, status){
      var result = JSON.parse(response);
      $('#url_slug').val(result['detail']['url_slug']).trigger('blur');
    });
  });
  
  ready();
}