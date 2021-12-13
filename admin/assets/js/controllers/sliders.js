var global_errors = false;
var global_no_validation = false;
$(document).ready(function(){
if($('#action').val() == 'manage_slider')
	load_slider();
else if($('#action').val() == 'slide_slider'){
	load_slides();
	load_slides_by_id();
}
else if($('#action').val() == 'edit_slides'){
	load_edit_slides();
}

$('#slider_name').keyup(function(e){

    var val = $(this).val().toLowerCase();
     val = val.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-')
	$('#slider_nick_name').val(val);
	$('#slider_nick_name').trigger('keyup');

	/*var val = $(this).val()

	$('#slider_nick_name').val($(this).val());
	$('#slider_nick_name').trigger('keyup');*/
});
$('#shadow_type').change(function(e){
	$('#shadow_type_holder').removeClass('hide');
  if($(this).val() == 0){
  	$('#shadow_type_holder').hide();
  }else{
  	$('#shadow_type_image').attr('src',CONFIG.get('FRONTEND_URL')+'/images/shadow/shadow'+$(this).val()+'.png');
  	$('#shadow_type_holder').show();
  }
});
$('.stop_slider').change(function(e){
	if($(this).val() == 'ON'){
		$('#start_after_loops').removeAttr('disabled');
		$('#stop_at_slide').removeAttr('disabled');
	}else{
		$('#start_after_loops').attr('disabled','disabled');
		$('#stop_at_slide').attr('disabled','disabled');
	}
});
$('.show_background_image').change(function(e){
	if($(this).val() == 'Y'){
		$('#background_image_url').removeAttr('disabled');
		$('#background_fit').removeAttr('disabled');
		$('#background_repeat').removeAttr('disabled');
		$('#background_position').removeAttr('disabled');
	}else{
		$('#background_image_url').attr('disabled','disabled');
		$('#background_fit').attr('disabled','disabled');
		$('#background_repeat').attr('disabled','disabled');
		$('#background_position').attr('disabled','disabled');
	}
});
$('.start_with_slide_enabled').change(function(e){
	if($(this).val() == 'ON'){
		$('#first_transition_type').removeAttr('disabled');
		$('#first_transition_duration').removeAttr('disabled');
		$('#first_transition_slot_amount').removeAttr('disabled');
	}else{
		$('#first_transition_type').attr('disabled','disabled');
		$('#first_transition_duration').attr('disabled','disabled');
		$('#first_transition_slot_amount').attr('disabled','disabled');
	}
});
$('#shadow_type').trigger('change');
$('.start_with_slide_enabled').trigger('change');
$('.stop_slider').trigger('change');
$('.show_background_image').trigger('change');
$('#first_transition_type').val($('#first_transtitiontion_type_hidden').val());
var font_div_id = 0;
$(".int_only").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
	    e.preventDefault();
	  }
   });
$('#add_new_font').click(function(e){
	e.preventDefault();
	$('#font_holder').append('<span id = '+font_div_id+' class="input-icon input-icon-right"><input type="text" name="google_fonts" class="input-xlarge"><i class="icon-trash red" onclick="delete_font('+font_div_id+')"></i></span>');
	font_div_id++;
});

$('#form_slider').submit(function(e){
	//e.preventDefault();
	return validate();
});
$('#form_slider').ajaxForm({
     beforeSend: function(){
     
     },
     complete: function(xhr) {

       $('#result').empty();
       if(xhr.responseText == 1){
       	//$('#result').append(alertMessage('Revolution Slider Successfully Saved','success','success1'));
       	window.location = CONFIG.get('URL')+'sliders/';
       }else if(xhr.responseText > 1){ 
       	window.location = CONFIG.get('URL')+'sliders/edit/'+xhr.responseText;
       }else
        $('#result').append(alertMessage('Error while saving','error','error1'));
  	}

});
$('.delete_font').click(function(e){
	e.preventDefault();
	if(confirm("Are you sure you want to delete this font?")){
		$(this).parent().remove();
	}
});
$('#slider_nick_name').keyup(function(e){
	if($(this).val() != ''){
		var alias = $(this).val();
		$('#slider_short_code').val('[rev_slider '+alias+']');
	}else
		$('#slider_short_code').val('');	
});
$( "#accordion" ).accordion({
					collapsible: true ,
					heightStyle: "content",
					animate: 250,
					header: ".accordion-header"
				});
$('#ui-accordion-accordion-header-0').trigger('click');
empty_holder();
change_color($('#spinner_color').val());

$('.color_picker').colorpicker();
$('#spinner_color').colorpicker().on('changeColor',function(e){ 
	empty_holder();
	$('#spinner_color').css('background-color',e.color.toHex());
	$('#hidden_spinner_color').val(e.color.toHex());
	change_color(e.color.toHex()); });
$('#background_color').colorpicker().on('changeColor',function(e){ 
	//empty_holder();
	$('#hidden_background_color').val(e.color.toHex()); });
//$('#spinner_color').trigger('change');
$('#background_color').colorpicker().on('changeColor',function(e){ 
	//empty_holder();
	$('#background_color').css('background-color',e.color.toHex())
	$('#hidden_background_color').val(e.color.toHex()); });
$('#slider').removeClass('hide');

$('#background_color').css('background-color',$('#background_color').val());
$('#spinner_color').css('background-color',$('#spinner_color').val());
});
function load_slides(){

	$('#slides_form').ajaxForm({
         beforeSend: function(){
          $('#result').empty();
         },
         complete: function(xhr) {
         	
         	global_no_validation = false;

            respond = JSON.parse(xhr.responseText);
      		

          if(respond == 1){
          	load_slides_by_id();
         	jQuery('#result').append(alertMessage('Successfully Saved Banners/Slides','success','success_1'));
         	$('.remove').trigger('click');
         	}
          else
            jQuery('#result').append(alertMessage('Error while saving Banners/Slides','error','error_upload'));
          	

        }
       
  });

$('#upload_slides').ace_file_input({
		style:'well',
		btn_choose:'Drop Banner or Image here or click to choose',
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
		jQuery('#result').empty();
		global_errors = false;
		jQuery($(this).next().find('span')).each(function(){
		var ext = $(this).attr('data-title').split('.').pop().toLowerCase();
            if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
            {
              global_errors = true;
            }
              
        });
  		if(global_errors){
  			jQuery('#result').append(alertMessage('Invalid Image File','error','error_image'));
  		}
	});

}
function load_edit_slides(){
	$('#add_slide').click(function(){
		jQuery('#result').empty();
		var slider_id = $('#slider_id').val();
		$.post(CONFIG.get('URL')+'sliders/add_new_slide',{action:'slider',id:slider_id}, function(response,status){
			if(status == 'success'){
				 if(JSON.parse(response) == '1')
					location.reload();
				else
					jQuery('#result').append(alertMessage('Error while adding slide','error','error_slide'));
			}else{
				jQuery('#result').append(alertMessage('404 - Error. No network.','error','error_slide'));
			}
		});
	});
	$('.datepicker').datepicker();
	$('.choose_image').click(function(e){
		$('#choose_image').modal('show');
	});
	$('.tab_header').click(function(e){
		var row = $(this).attr('class').split(' ');
		$.post(CONFIG.get('URL')+'sliders/set_tab_header',{action:'slider',id:row[0]}, function(response,status){
			
		});
	});
}
function empty_holder(){
	$('#spinner_holder').empty();
	$('#spinner_holder').append('<div id="spinner" style="margin: 60px 0 0 223px;">	</div>');
}
function empty_holder(){
	$('#spinner_holder').empty();
	$('#spinner_holder').append('<div id="spinner" style="margin: 62px 0 0 0;">	</div>');
}
function change_color(color){

	var spinner = new Spinner({
	lines: 12, // The number of lines to draw
	length: 5, // The length of each line
	width: 5, // The line thickness
	radius: 10, // The radius of the inner circle
	color: color, // #rbg or #rrggbb
	speed: 1, // Rounds per second
	trail: 100 // Afterglow percentage// Whether to render a shadow
}).spin(document.getElementById("spinner"));

}
function delete_font(id){
	
	if(confirm("Are you sure you want to delete this font?")){
		$('#'+id).remove();
	}
}
function validate(){
	var validate = true;
	$('#slider_short_code').removeAttr('disabled');
	$('#grid_height').removeAttr('disabled');
	$('#grid_width').removeAttr('disabled');
	$('#start_after_loops').removeAttr('disabled');
	$('#stop_at_slide').removeAttr('disabled');
	$('#background_image_url').removeAttr('disabled','disabled');
	$('#background_fit').removeAttr('disabled','disabled');
	$('#background_repeat').removeAttr('disabled','disabled');
	$('#background_position').removeAttr('disabled','disabled');
	$('#first_transition_type').removeAttr('disabled');
	$('#first_transition_duration').removeAttr('disabled');
	$('#first_transition_slot_amount').removeAttr('disabled');
	$('#result').empty();
	if($('#slider_name').val() == ''){
		validate = false;
		$('#result').append(alertMessage('Please fill Slider Name','error','error1'));
	}
	if($('#slider_nick_name').val() == ''){
		validate = false;
		$('#result').append(alertMessage('Please fill Slider Alias','error','error2'));
	}

	return validate;
}
function load_slider(){
	$('#slider_table').dataTable().fnDestroy();

  	$.post(CONFIG.get('URL')+'sliders/load_slider',{action:'slider'}, function(response,status){
    var result = JSON.parse(response);
  
    $.each(result, function(i, field){
      	$('#slider_table tbody').append('<tr><td class="center">'+field['id']+'</td><td><a href="'+CONFIG.get('URL')+'sliders/edit/'+field['id']+'">'+field['slider_name']+'</a></td><td>[rev_slider '+field['slider_alias']+']</td><td>'+field['source_type']+'</td><td></td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group"><button class="btn btn-minier btn-info" data-rel="tooltip" data-placement="top" title="Edit" onclick="edit_slider('+field['id']+')"><i class="icon-edit bigger-120"></i></button><button class="btn btn-minier btn-warning" data-rel="tooltip" data-placement="top" title="Slides" onclick="slides_slider('+field['id']+')"><i class="icon-check bigger-120"></i></button><button class="btn btn-minier btn-danger" data-rel="tooltip" data-placement="top" title="Delete" onclick="delete_slider('+field['id']+')"><i class="icon-trash bigger-120"></i></button></div></td></tr>');
    });
    $('[data-rel=tooltip]').tooltip();
    $('#slider_table').dataTable( {
      "aoColumns": [
       { "bSortable": false },
       null,
       null,
       { "bSortable": false },
       { "bSortable": false },
       { "bSortable": false }
      ]});
  });
}
function slides_slider(id){
	window.location = CONFIG.get('URL')+'sliders/slides/'+id;
}
function back_to_slider(){
	window.location = CONFIG.get('URL')+'sliders/';
}
function edit_slider(id){
	window.location = CONFIG.get('URL')+'sliders/edit/'+id;
}
function delete_slider(id){
	$('#hidden_slider_id').val(id);
	$('#delete').modal('show');
}
function delete_slider_modal(){
	$('#result').empty();
	$.post(CONFIG.get('URL')+'sliders/delete_slider',{action:'slider', id:$('#hidden_slider_id').val()}, function(response,status){
		if(status == 'success'){
			if(JSON.parse(response) > 0){
				window.location = CONFIG.get('URL')+'sliders/';
			}else{
				$('#result').append(alertMessage('Unidentified error while deleting the slider','error','error3'));
				$('#delete').modal('hide');
			}
		}
		else{
			$('#result').append(alertMessage('Unable to delete slider due to network error','error','error4'));
			$('#delete').modal('hide');
		}
	});
}
function validate_image(){
	var error = false;
	jQuery('#result').empty();
	if($('#upload_slides').val() == ''){
		jQuery('#result').append(alertMessage('Please Select Image File','error','error_image'));
		error = true;
	}
		
	if(global_errors || error && !global_no_validation)
		return false;
	
	return true;

}
function load_slides_by_id(){
	$('#banner_holder').empty();
	$.post(CONFIG.get('URL')+'sliders/load_slides_by_id',{action:'slider', id:$('#slide_id').val()}, function(response,status){
		if(status == 'success'){
			var result = JSON.parse(response);
			if(result.length > 0){
			$.each(result, function(i, field){
				$('#banner_holder').append('<div class="slide_widget"><div class="well" id="well_'+field['id']+'"><h4 class="green smaller lighter">'+field['title']+'</h4><input type="hidden" class="hidden_slide_id" value="'+field['id']+'"><table><tbody><tr><td width="15%" rowspan="5"><div style="width:100%;"><button class="btn btn-success" onclick="return go_to_edit('+field['id']+')"><i class="icon-edit align-top bigger-125"></i>Edit Slide</button></div></td><td width="25%"><ul class="ace-thumbnails"><li><a href="'+field['image_url']+'" data-rel="colorbox"><img src="'+field['image_url']+'" alt="234x155" style="height:155px; width:234px;" /><div class="text"><div class="inner">Click to see full image</div></div></a></li></ul></td><td width="15%"><button class="btn btn-danger" onclick="return delete_slide('+field['id']+')"><i class="icon-trash align-top bigger-140"></i>Delete&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> <button class="btn btn-warning"><i class="icon-copy align-top bigger-125"></i>Duplicate&nbsp;&nbsp;&nbsp;</button><button class="btn btn-info"><i class="icon-copy align-top bigger-125"></i>Copy/Move</button></td><td></td></tr></tbody></table></div></div>');
			});
				$('#banner_holder').append('<div class="align-right"><button class="btn btn-success " onclick="save_button_activate()">Save</button></div>');
			}else{
				$('#banner_holder').append('No Banners or Slides. Please upload');
			}
			
		}
		$('.slide_widget').sortable({
			        connectWith: '.slide_widget',
					items:'> .well',
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'widget-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer'
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
	});
}
function go_to_edit(id){

	window.location = CONFIG.get('URL')+'sliders/slides/'+$('#slide_id').val()+'/edit/'+id;
	return false;
}
function delete_slide(id){
	$('#delete').modal('show');
	$('#hidden_slide_id').val(id);
	return false;
}
function save_button_activate(){
	var datas = [];
	$('.well').each(function(){
		 datas.push($(this).find('.hidden_slide_id').val()); 
	});
	$('#hidden_well_datas').val(datas);
	global_no_validation = true;
}

function delete_slide_modal(){
	$('#result').empty();
	$.post(CONFIG.get('URL')+'sliders/delete_slides_by_id',{action: 'slider', id:$('#hidden_slide_id').val()}, function(response, status){
		if(status=='success'){
			var result = JSON.parse(response);
			if(result == 1){
				jQuery('#result').append(alertMessage('Successfully Deleted Banner/Slide','success','success_2'));
				$('#well_'+$('#hidden_slide_id').val()).remove();
				load_slides_by_id();
			}else{
				jQuery('#result').append(alertMessage('Error while deleting the Banner Please try again','error','error5'));
			}
				
		}else{
			jQuery('#result').append(alertMessage('404 Network Error - Please try again','error','error6'));
		}
		$('#delete').modal('hide');
	});
	
	
}