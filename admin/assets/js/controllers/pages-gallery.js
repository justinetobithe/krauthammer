$(function(){
	$('#btn_add_new_album').on('click',function(e){
		e.preventDefault();
		bootbox.prompt("Enter Album Name?", function(result) {
			if (result === null) {
			} else {
				if (result == "") {
					result = "Untitled Album";
				}
				var album = $('#template-gallery-album').tmpl({album_title : result, id_counter : variable_gallery_album_counter++}).appendTo( "#accordion-gallery" );
				fn_init_album_components(album);

				$( "#accordion-gallery" ).find(".accordion-group").each(function(){
				  $(this).find(".accordion-toggle").addClass('collapsed');
				  $(this).find(".accordion-body").removeClass('in').height(0);
				});

				album.find(".accordion-toggle").removeClass('collapsed');
				album.find(".accordion-body").addClass('in').height("auto")
				album.find('.album_name').focus();
				$("html, body").animate({ scrollTop: $(document).height() }, 1000); /* scroll to the bottom part of the page*/
			}
		});
	});
	$("#btn_add_hide_all").click(function(){
	  $( "#accordion-gallery" ).find(".accordion-group").each(function(){
		  $(this).find(".accordion-toggle").addClass('collapsed');
		  $(this).find(".accordion-body").removeClass('in').height(0);
		});
	});
	$("#btn_add_show_all").click(function(){
	  $( "#accordion-gallery" ).find(".accordion-group").each(function(){
		  $(this).find(".accordion-toggle").removeClass('collapsed');
		  $(this).find(".accordion-body").addClass('in').height("auto");
		});
	});

	$( "#accordion-gallery" ).accordion({
		header: '> div > h3',
		autoHeight: false,
		active: false,
		heightStyle: "content",
		animate: 250,
		collapsible: true,
	}).sortable({
		axis: 'y',
		handle: 'h3',
		stop: function( event, ui ) {
			ui.item.children( 'h3' ).triggerHandler( 'focusout' );
		}
	});
	$("#modal-gallery-photo-edit").modal({
		backdrop : 'static',
		keyboard : false,
		show : false,
	}).on("show.bs.modal", function(){
	  $("#modal-gallery-photo-edit-loading").show();
		$.post(CONFIG.get('URL')+'pages/process_album/',{
			action : 'load-photo-info',
			photo_id : $("#modal-photo-id").val(),
		},
		function(response) {
			$json_response = JSON.parse(response);

			if (typeof $json_response != "undefined") {
				if (typeof $json_response['name'] != "undefined") {
					$("#modal-photo-name").val($json_response['name']);
				}
				if (typeof $json_response['description'] != "undefined") {
					$("#modal-photo-description").val($json_response['description']);
				}
			}

	  	$("#modal-gallery-photo-edit-loading").hide();
	  	$("#modal-gallery-photo-edit-field-container").show();
		});
	}).on("hidden.bs.modal", function(){
	  $("#modal-photo-id").val('0');
	  $("#modal-photo-name").val('');
	  $("#modal-photo-description").val('');
	  $("#modal-gallery-photo-edit-field-container").hide();
	  $("#modal-gallery-photo-edit-loading").hide();
	});

	$("#modal-save-photo-name").click(function(){
	  fn_save_photo();
	});
});

var variable_gallery_album_counter = 0;
var the_page_id = 0;
function fn_save_albums( page_id, callback_fn ){
	if (typeof page_id == 'undefined') {
		notification ("Album", "Unable to save album without page ID", 'gritter-warning');
	}
	the_page_id = page_id;
	var album_data = [];
	$.each($("#accordion-gallery").find('.accordion-group'), function(k, v){
		var container = $(this);
		var featured = $(this).find(".album-photo-item-featured:checked").attr('data-value') || ($(this).find(".album-photo-item-featured:first-child").attr('data-value')||0);

		var temp = {
			"album_id" : container.find(".album-id").val(),
			"album_label" : container.find(".album_name").val(),
			"album_return_id" : container.find(".album-return-id").val(),
			"album_featured_image" : featured,
		};
		album_data.push( temp );
	});

	if (album_data.length) {
		$.post(CONFIG.get('URL')+'pages/process_album/', {
			action:'save', 
			album_data:album_data, 
			page_id:page_id
		},function(response, status){
			var respose_data = JSON.parse(response);
			$.each(respose_data, function(k, v){
				$("#"+v['return_id']).find('.album-id').val(v['new_id']);
			});

			fn_upload_start( callback_fn );
	  });
	}else{
		fn_upload_start( callback_fn );
	}
}
function fn_get_album( page_id ){
	if (typeof page_id == 'undefined' || page_id == 0) {
		notification ("Page Gallery", "Warning, Unable to load album gallery.", "gritter-warning");
	}

	$("#accordion-gallery").html('<div class="well text-center"><p><b>Loading Album Photos...</b></p></div>');
	$.post(CONFIG.get('URL')+'pages/process_album/', {
		action:'load', 
		page_id:page_id
	},function(response, status){
		var respose_data = JSON.parse(response);
		var album = {};

		$("#accordion-gallery").html('<div class="well text-center"><p><b>Loading Album Photos...</b></p></div>');

		if (typeof respose_data['data'] != "undefined") {

			$("#accordion-gallery").html('');

			$.each(respose_data['data'], function(k, photo){
				if (typeof album[photo['album_id']] == 'undefined') {
					album[photo['album_id']] = {};
				}

				if (typeof album[photo['album_id']]['info'] == 'undefined') {
					album[photo['album_id']]['info'] = [];
				}
				album[photo['album_id']]['info'] = {
					id : photo['album_id'],
					name : photo['album_name'],
					sort_order : photo['sort_order'],
				}

				if (typeof album[photo['album_id']]['photos'] == 'undefined') {
					album[photo['album_id']]['photos'] = [];
				}
				if (typeof photo['photo_id'] != 'undefined' && photo['photo_id'] != "" && photo['photo_id'] != 0 && photo['photo_id'] != null) {
					album[photo['album_id']]['photos'].push({
						'id' : photo['photo_id'],
						'name' : photo['name'],
						'url' : photo['url'],
						'description' : photo['photo_description'],
						'featured_image' : photo['featured_image'],
					})
				}
			});
		}

		fn_populate_album( album );
  });
}
function fn_populate_album( album ){
	$.each(album, function(k, v){
		var album = $('#template-gallery-album').tmpl({album_title : v['info']['name'], id_counter : variable_gallery_album_counter++, id : v['info']['id']}).appendTo( "#accordion-gallery" );

		$.each(v['photos'], function(k, vv){
			var item = $('#template-gallery-album-item').tmpl({album_id: v['info']['id'], name : vv['name'], url : vv['url'], id : vv['id'], description : vv['description'], featured : vv['featured_image']==true?'checked="checked"':''}).appendTo( album.find('.uploaded-images') );
			fn_init_album_photo(item);
		});

		fn_init_album_components(album);
	});
}
function fn_init_album_components(album){
	album.find(".album_name").change(function(){
		var title = $(this).val();
		album.find('.accordion-toggle').text(title);
	});

	album.find('.upload_images').ace_file_input({
		style:'well',
		btn_choose:'Drop Images here or click to choose',
		btn_change:null,
		no_icon:'icon-cloud-upload',
		droppable:true,
		thumbnail:'small',
		preview_error : function(filename, error_code) {

		}
	}).on('change', function(){
		global_errors = false;
		jQuery($(this).next().find('span')).each(function(){
			var ext = $(this).attr('data-title').split('.').pop().toLowerCase();
			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1){
			}
		});
		if(global_errors){
		}
	});

	if ($("#action").val() == 'add_page') {
		album.find('.btn-gallery-remove-album').click(function(e){
			bootbox.confirm("Do you want to continue delete this album?", function(result){
		  	if (result) {
		  		album.remove();
		  	}
		  });
		});
	}else{
		album.find('.btn-gallery-remove-album').click(function(e){
			e.preventDefault();
		  bootbox.confirm("Do you want to continue delete this album?", function(result){
		  	if (result) {
		  		var album_id = album.find(".album-id").val();

		  		if (album_id == '') {
		  			album.remove();
		  		}else{
					  var data = {
					  	id : album_id,
					  	token : $("#gallery-token").val(),
					  };

					  $.post(CONFIG.get('URL')+'pages/process_album/', {
							action:'delete-album', 
							data: data,
						},function(response, status){
							if (typeof response != 'undefined') {
								var response_data = JSON.parse(response);
								album.remove();
								var noti_type = response_data['success'] == true ? "gritter-error" : "gritter-warning";
								notification("Album", response_data['message'], noti_type);
							}
					  });
		  		}
		  	}
		  });
		});
		album.find('.btn-gallery-remove-album').tooltip();
	}
}
function fn_init_album_photo(photo){
	photo.find('.btn-gallery-remove-item').click(function(e){
		e.preventDefault();
		var selected_photo = $(this);
		var selected_id = selected_photo.attr('data-value');

		bootbox.confirm("Do you want to continue delete this photo?", function(result) {
			if (result) {
			  var data = {
			  	id : selected_id,
			  	token : $("#gallery-token").val(),
			  };

			  $.post(CONFIG.get('URL')+'pages/process_album/', {
					action:'delete-photo', 
					data: data,
				},function(response, status){
					if (typeof response != 'undefined') {
						var response_data = JSON.parse(response);
						photo.remove();
						var noti_type = response_data['success'] == true ? "gritter-error" : "gritter-warning";
						notification("Photo", response_data['message'], noti_type);
					}
			  });
			}
		});
	});
	photo.find('.btn-gallery-edit-item').click(function(e){
		e.preventDefault();

		var current_photo_name = $(this).closest('.album-photo-item').find('.span6 p').html();
		$("#modal-photo-name").val( current_photo_name );
		$("#modal-photo-id").val( $(this).attr('data-value') );
		$("#modal-gallery-photo-edit").modal('show');
	});
}
function fn_upload_start( callback_fn ){
	var images = [];
	
	$.each($("#accordion-gallery").find(".accordion-group"), function(){
		var image_sets = $(this).find(".upload_images").data('ace_input_files');
		var album_id = $(this).find('.album-id').val();

		if (image_sets) {
			$.each(image_sets, function(k, v){
			  var img_info = {
					album_id : album_id,
					image : v,
				}
				images.push(img_info);
			})
		}

		/*
		Replace "image_set.get(0).files" with ".data('ace_input_files')" to collect the selected files
		
		var image_set = container.find(".upload_images");
		$.each(image_set.get(0).files, function(kk, vv){
			var img_info = {
				album_id : container.find('.album-id').val(),
				image : vv,
			}
			images.push(img_info);
		});*/
	});

	$("#modal-gallery-loading-completed").html("");

	if (images.length > 0) {
		$("#modal-gallery-loading").modal({
			backdrop : 'static',
			keyboard : false,
		});
		fn_upload( images, callback_fn );
	}else{
		if (typeof callback_fn != "undefined") {
			callback_fn( the_page_id );
		}
	}
}
function fn_upload(images, callback_fn){
	$("#modal-gallery-loading").find('.modal-footer').hide();
	if (typeof images == 'undefined') { return; }
	if (images.length <= 0) {
		$("#modal-gallery-loading-completed").append('<br /><p>Upload Completed');
		$("#modal-gallery-loading").find('.modal-footer').show();

		if (typeof callback_fn != "undefined") {
			callback_fn( the_page_id );
		}
		return;
	}

	var image = images.pop();
	var formData = new FormData();
	formData.append("action", "page-gallery");
	formData.append("upload_token", $("#gallery-token").val());
	formData.append("album_id", image['album_id']);
	formData.append("file", image['image']);
	var image_name = typeof image['image'].name != "" ? image['image'].name : "[unknown file]";

	if (image_name.length > 80) {
		var temp1 = image_name.substr(0, 33);
		var temp2 = image_name.substr(image_name.length - 33, image_name.length);
		image_name = temp1 + "..." + temp2;
	}

	
  fn_set_gallery_loading(0);
	$("#modal-gallery-loading-progress").show();

	$.ajax({
    type:'POST',
	  url: CONFIG.get('URL')+'pages/upload/',
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
    	$("#modal-gallery-loading-completed").append('<p><i class="icon icon-check"></i> '+ image_name +'</p>');
			fn_upload(images, callback_fn);
    },
	}).done(function() {
		$("#modal-gallery-loading-progress").hide();
	});

	return;
}
function fn_set_gallery_loading(percentage){
	if (typeof percentage == 'undefined') {
		percentage = 0
	}
	var perc = percentage + "%";

	$("#modal-gallery-loading-progress").attr("data-percent", perc);
	$("#modal-gallery-loading-progress").find(".bar").css('width', perc);
}
function fn_save_photo(){
	var data = {
		'id' : $('#modal-photo-id').val(),
		'name' : $('#modal-photo-name').val(),
		'description' : $('#modal-photo-description').val(),
	}

	$.post(CONFIG.get('URL')+'pages/process_album/',{
		action : 'save-photo',
		data : JSON.stringify( data ),
	},
	function(response) {
		if (response) {
			var result = JSON.parse(response);
			if (result['success']) {
				$("#modal-gallery-photo-edit").modal('hide');
				notification("Page Gallery", response['message'], "gritter-success");
				if (result['data']['id']) {
					$("#album-photo-item-"+result['data']['id']).find('.album-photo-name').html('<b>'+result['data']['name']+'</b>');
					$("#album-photo-item-"+result['data']['id']).find('.album-photo-description').html($('<em></em>').text(result['data']['description']));
				}
			}else{
				notification("Page Gallery", response['message'], "gritter-success");

			}
		}
		/*
		to be removed
		fn_get_album($("#hdn_page_id").val());
		*/
	});
}