$(function(){
	$('#product-brands').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL')+"product-brand/product_brand_processor/",
		"aoColumns": [
		{ "bSortable": false }, 
		null,
		null,
		null,
		{ "bSortable": false},
		], "fnDrawCallback": function( oSettings ) {
			fn_init_table_component();
		}, "fnServerParams": function(aoData) {
			aoData.push({"name": "action", "value": 'tabulate'});
		}
	});
	$("#btn-product-brand-add").click(function(){
		fn_clear_modal_fields();
		$("#modal-product-brand-add").modal({
			backdrop : 'static',
			keyboard : false,
		});
	});
	$('#logo_main_url, #logo_alt_url').ace_file_input({
		no_file:'No File',
		btn_choose:'Choose...',
		btn_change:'Change',
		no_icon:'icon-cloud-upload',
		droppable:false,
		thumbnail:'small',
		preview_error : function(filename, error_code) {

		}
	}).on('change', function(){
	});
	$("#modal-btn-product-brand-add").click(function(e){
		if ($("#brand-name").val() == "") {
			$("#brand-name").focus()
			notification("Error", "Brand name should not be empty.", "gritter-error");
			return;
		}

		var data = {
			"id" : $("#brand-id").val(),
			"name" : $("#brand-name").val(),
			"description" : $("#brand-description").val(),
		};

		fn_save_brand( data, function(result){
			if (typeof result['new_id'] == "undefined") {
				return;
			}
		  fn_upload_image_1( result['new_id'] );
		});
	});
});

function fn_save_brand( data, callback_fn ){
	if (typeof data == "undefined") {
		return;
	}

	$.post(CONFIG.get('URL')+'product-brand/product_brand_processor/',{
		action:'save-brand', 
		data:JSON.stringify(data),
	},function(response,status){
		var result = JSON.parse( response );

		if (typeof result != "undefined" && typeof result['new_id'] != "undefined") {
			notification("Brand Detail", "Successfully saved", "gritter-success");
			if (typeof callback_fn != 'undefined') {
				callback_fn( result );
			}
		}
	});
}
function fn_upload_image_1(brand_id){
	if (typeof brand_id == 'undefined') {
		fn_refresh_brand_table();
		return;
	}

	if ($("#logo_main_url").get(0).files.length > 0) {
		var formData = new FormData();
		formData.append("action", "save-brand-image-1");
		formData.append("brand_id", brand_id);
		formData.append("file", $("#logo_main_url").get(0).files[0]);

		$.ajax({
			type:'POST',
			url: CONFIG.get('URL')+'product-brand/product_brand_processor/',
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			type: 'POST',
			beforeSend: function( xhr ) {
				$("#tab1").find(".image-progress").show();
			},
			'xhr': function(test) {  
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);

						// fn_set_gallery_loading(percentComplete);
						$("#tab1").find(".image-progress").find(".bar").css('width', percentComplete + "%");
					}
				}, false);
				return xhr;
			},
			success: function(response) {  
				// $("#modal-gallery-loading-completed").append('<p><i class="icon icon-check"></i> '+ image_name +'</p>');
				// fn_upload(images, callback_fn);
				notification("Brand Image 1", "Main Image Successfully Uploaded", "gritter-success");
				$("#tab1").find(".image-progress").hide();

				fn_upload_image_2(brand_id);
			},
		}).done(function() {
			// $("#modal-gallery-loading-progress").hide();
		});
	}else{
		fn_upload_image_2(brand_id);
	}
}
function fn_upload_image_2(brand_id){
	if (typeof brand_id == 'undefined') {
		fn_refresh_brand_table();
		return;
	}

	if ($("#logo_alt_url").get(0).files.length > 0) {
		var formData = new FormData();
		formData.append("action", "save-brand-image-2");
		formData.append("brand_id", brand_id);
		formData.append("file", $("#logo_alt_url").get(0).files[0]);

		$.ajax({
			type:'POST',
			url: CONFIG.get('URL')+'product-brand/product_brand_processor/',
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			type: 'POST',
			beforeSend: function( xhr ) {
				$("#tab2").find(".image-progress").show();
			},
			'xhr': function(test) {  
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);

						// fn_set_gallery_loading(percentComplete);
						$("#tab2").find(".image-progress").find(".bar").css('width', percentComplete + "%");
					}
				}, false);
				return xhr;
			},
			success: function(response) {  
				// $("#modal-gallery-loading-completed").append('<p><i class="icon icon-check"></i> '+ image_name +'</p>');
				// fn_upload(images, callback_fn);
				notification("Brand Image 2", "Alt Image Successfully Uploaded", "gritter-success");
				$("#tab2").find(".image-progress").hide();
				fn_refresh_brand_table();
			},
		}).done(function() {
			// $("#modal-gallery-loading-progress").hide();
		});
	}else{
		fn_refresh_brand_table();
	}
}
function fn_refresh_brand_table(){
	$("#modal-product-brand-add").modal("hide");
	$('#product-brands').dataTable().fnDraw();
}
function fn_init_table_component(){
	$('#product-brands tbody').find("tr").each(function(){
		$(this).find('.btn-product-brand-edit').click(function(){
		  var id = $(this).attr('data-value');

		  fn_get_brand_info( id );
		});
		$(this).find('.btn-product-brand-delete').click(function(){
		  var id = $(this).attr('data-value');

		  fn_delete_brand_info( id );
		});
	});
}
function fn_clear_modal_fields(){
	$("#brand-id").val( "" );
	$("#brand-name").val( "" );
	$("#brand-description").val( "" );
	$("#logo_main_url").ace_file_input('reset_input');
	$("#logo_alt_url").ace_file_input('reset_input');
}
function fn_get_brand_info( brand_id ){
	if (typeof brand_id == 'undefined') {
		console.log('brand_id was not defined');
		return brand_id;
	}

	var data = { "id" : brand_id };

	$.post(CONFIG.get('URL')+'product-brand/product_brand_processor/',{
		action:'load-brand', 
		data:JSON.stringify(data),
	},function(response,status){
		var result = JSON.parse( response );

		if (typeof result != "undefined" && typeof result['data'] != "undefined") {
			$("#modal-product-brand-add").modal({
				backdrop : 'static',
				keyboard : false,
			});

			fn_clear_modal_fields();

			$("#brand-id").val( result['data']['id'] );
			$("#brand-name").val( result['data']['brand_name'] );
			$("#brand-description").val( result['data']['brand_desc'] );
		}
	});
}
function fn_delete_brand_info( brand_id ){
	bootbox.confirm("Do you confirm deleting selected item?", function(result){
    if (result) {
      if (typeof brand_id == 'undefined') {
				console.log('brand_id was not defined');
				notification("No brand selected.");
				return brand_id;
			}

			var data = { "id" : brand_id };

			$.post(CONFIG.get('URL')+'product-brand/product_brand_processor/',{
				action:'delete-brand', 
				data:JSON.stringify(data),
			},function(response,status){
				var result = JSON.parse( response );
				fn_clear_modal_fields();
				fn_refresh_brand_table();
			});
    }
  });
}