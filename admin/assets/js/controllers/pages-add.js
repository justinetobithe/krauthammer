$(function(){
  $('#parent_id').change(function(e){
  	generate_permalink();
	});

	setForm();
  load_blur_functions();
  proccess_slug("");
  initilize_editor();

  $("#language").change(function(e){
    generate_permalink();
  });
});


function load_blur_functions(text){
  $('#page_template').change(function(){
    if ($(this).val().toLowerCase().includes("blog")) {
      $("#page-category-tree-container").show()
    }else{
      $("#page-category-tree-container").hide();
      hideBlogCategories();
    }
  }).trigger('change');

  $('#txt_post_title').blur(function(){
    if($(this).val()!=''){
      var slug = convertToSlug($(this).val());
      $('#txt_url_slug').val(slug);
      generate_permalink();
    }
  });

  $('#txt_url_slug').blur(function(){
    if($(this).val()==''){
      var slug = convertToSlug($('#txt_post_title').val());
      $('#txt_url_slug').val(slug);
    }
    generate_permalink();
  });

  $('#txt_post_title').keyup(function(){
    $('#btn_save_product').attr('disabled', 'disabled');
  });

  $('#txt_url_slug').keyup(function(){
    $('#btn_save_product').attr('disabled', 'disabled');
  });    
}

function generate_permalink(){
	var url_slug = $('#txt_url_slug').val();
	var parent_id = $('#parent_id').val();

	proccess_slug(url_slug, parent_id);
}

//ajaxFORM
function setForm(){ 
  $('#page_form').ajaxForm({
    beforeSubmit: function(arr, $form, options){
      var custom_fields = {};
      $.each($("#custom-fields-container").find('.page-custom-fields'), function(){
        if (typeof custom_fields[$(this).find('.custom-field-name').val()] != 'undefined') {
          custom_fields[$(this).find('.custom-field-name').val()].push($(this).find('.custom-field-value').val());
        }else{
          custom_fields[$(this).find('.custom-field-name').val()] =  [$(this).find('.custom-field-value').val()];
        }
      });
      
      arr.push({'name' : 'custom_fields', 'value' : JSON.stringify(custom_fields)});
      arr.push({'name' : 'language', 'value' : $("#language").val()});
    },
    beforeSend: function(){
      $("#loading").modal({
        backdrop : 'static',
        keyboard: false
      });

      $("#load-msg").removeClass();
    },
    complete: function(xhr) {
      respond = JSON.parse(xhr.responseText);

      if(respond > 0){
        $("#loading").modal('hide');
        fn_save_albums(respond, function(id){
          window.location.href = CONFIG.get('URL')+"pages/edit/"+id+"/";
        });
      }
      else{
        jQuery('#alertPage').append(alertMessage('Unable to Save Page','error','errorsavepage'));
        notification("Page", "Unable to Save Page", "gritter-error")

        $("#loading").modal('hide');

      }
    },
    error:  function(xhr, desc, err) { 
      console.debug(xhr); 
      console.log("Desc: " + desc + "\nErr:" + err); 
    } 
  });
}

function initilize_editor(){
  tinyMCE.init({
    selector:'#content',
    menubar: " view edit format table tools",
    height : 300,
    oninit : "setPlainText",
    toolbar:[
      "nonbreaking forecolor backcolor undo redo bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent indent link image imageuploader cmsmedia ",
      " formatselect fontselect fontsizeselect "
    ],
    plugins: [
      "paste advlist autolink link image lists charmap print preview hr anchor pagebreak ",
      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
      "imageuploader cmsmedia ",
      "textcolor colorpicker","nonbreaking table code"
    ],
    external_plugins:{
      'imageuploader':'plugins/imageuploader/editor_plugin_src.js',
      'cmsmedia':'plugins/cmsmedia/editor_plugin_src.js',
    },
    relative_urls: false,
    convert_urls: false,
    nonbreaking_force_tab:true,
    tools: "inserttable",
    verify_html: false
  });
}