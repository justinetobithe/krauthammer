$(function(){
	var Testimonial = {
		items : {
			table : $("#table-testimonials").dataTable({
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": CONFIG.get('URL') + "testimonials/table_processor/",
				"aoColumns": [
				{"sWidth": "15%", "bSortable": false},
				{"sWidth": "15%"},
				{"sWidth": "50%"},
				{"sWidth": "10%"},
				{"sWidth": "10%"},
				], "fnDrawCallback": function(oSettings) {
					$(".btn-testimonial-edit").each(function(k, v){
						$(this).click(function(e){
							var id = $(this).attr('data-value');
							$("#current-selected-testimonial").val(id);

							Testimonial.modal.edit.modal('show');
							Testimonial.fn.get_testimonial_detail(id);
						});
					});
					$(".btn-testimonial-delete").each(function(k, v){
						$(this).click(function(){
							var selected_testimonial = $(this).attr('data-value');
							bootbox.confirm("Are you sure you want to delete selected item?", function(result){
								if (result) {
									Testimonial.fn.delete_testimonial(selected_testimonial);
								}
							})
						});
					});
				}, "fnServerParams": function(aoData) {
				},
			}),
			testimonial_image : $("#current-selected-testimonial-image").ace_file_input({
				no_file: 'No File ...',
				btn_choose: 'Choose',
				btn_change: 'Change',
				droppable: false,
				onchange: null,
				thumbnail: false, /*| true | large*/
				whitelist:'gif|png|jpg|jpeg',
				blacklist:'exe|php',
				before_change : function(f, d){
					var file = f[0];
					if(typeof file == "string") {
						/*file is just a file name here (in browsers that don't support FileReader API such as IE8)*/
						if(! (/\.(jpe?g|png|gif)$/i).test(file) ) {
							/*not an image extension?*/
							/*alert user*/
							notification("Testimonial Image", "Invalid file", "gritter-error");
							return false;
						}
					}
					else {
						var type = $.trim(file.type);
						if(
							( type.length > 0 && ! (/^image\/(jpe?g|png|gif)$/i).test(type) )
							|| 
							/*for android's default browser!*/
							( type.length == 0 && ! (/\.(jpe?g|png|gif)$/i).test(file.name) )
							)
						{
							/*alert user*/
							notification("Testimonial Image", "Invalid file", "gritter-error");
							return false;
						}

						var file_limit = 3*1024*1024;
						if( file.size > file_limit ) {
							/*is the file size larger than 100KB?*/
							/*alert user*/
							notification("Testimonial Image", "Too large file("+file.size+"). (file size should not exceed "+file_limit+" byte)", "gritter-error");
							return false;
						}
					}


					return true;
				},
				before_remove:function() {
					/*don't allow resetting the file input while upload in progress*/
					/*if(upload_in_progress) return false;*/
					var original_image = $('#current-selected-testimonial-image-previewer').attr('alt');
					original_image = original_image != "" ? original_image : cms_function.default_image;
					$('#current-selected-testimonial-image-previewer').attr('src', original_image);
					return true;
				},
				/*onchange: ''*/
			}).on('change', function(e){
				var files = $(this).data('ace_input_files');

				if (typeof files != 'undefined') {
					var reader = new FileReader();
					reader.onload = function (e) {
						$('#current-selected-testimonial-image-previewer').attr('src', e.target.result);
					}
					reader.readAsDataURL(files[0]);
				}
			}),
		},
		modal : {
			edit : $("#modal-edit").modal({
				show : false,
				keyboard : false,
				backdrop : 'static',
			}),
		},
		controls : {
			add_testimonial : $("#btn-add-testimonial").click(function(e){
				Testimonial.fn.reset_testimonial_editor();
				$("#modal-edit").modal('show');
			}),
			save_testimonial : $("#btn-testimonial-save-changes").click(function(e){
				Testimonial.fn.save_testimonial()
			}),
		},
		fn : {
			save_testimonial : function(){
				var olc = cms_function.elements.overlay.clone();
				Testimonial.modal.edit.find('.modal-dialog').append(olc)

				var testi_data = {
					id : $("#current-selected-testimonial").val(),
					author : $("#current-selected-testimonial-name").val(),
					company : $("#current-selected-testimonial-company").val(),
					position : $("#current-selected-testimonial-position").val(),
					status : $("#current-selected-testimonial-status").val(),
					content : $("#current-selected-testimonial-content").val(),
				};

				$.post(CONFIG.get('URL')+'testimonials/processor/',{
					action : 'save',
					data : JSON.stringify(testi_data),
				},
				function(response) {
					if (!cms_function.isJSON(response)) { notification("Testimonial", "Unknown Response", "gritter-error"); return; }

					response = JSON.parse(response);

					if (typeof response['status'] != 'undefined') {
						if (response['status'] == 'success') {
							notification("Testimonial", response['message'], "gritter-success", false, 3000);

							if (typeof response['id'] != 'undefined') {
								$("#current-selected-testimonial").val(response['id']);
							}

							var files = $("#current-selected-testimonial-image").data('ace_input_files');

							if (Testimonial.items.testimonial_image.val()) {
								/*Uploading Starts Here*/
								var olc_loading = cms_function.elements.overlay_loading.clone();
								var files = $("#current-selected-testimonial-image").data('ace_input_files');

								var f = $('<form action="'+ CONFIG.get('URL') + "testimonials/processor/" +'" method="post" enctype="multipart/form-data"></form>')
								f.append($('<input type="hidden" name="action" value="upload" />'));
								f.append($('<input type="hidden" name="testimonial_id" value="'+ $("#current-selected-testimonial").val() +'" />'));
								Testimonial.items.testimonial_image.clone().appendTo(f);

								f.ajaxForm({
									beforeSend: function(e){
										olc.append(olc_loading);
									},
									uploadProgress : function (event, position, total, percent){
										olc_loading.find('.bar').width(percent + '%');
									},
									complete: function(xhr){
										/*remove overlay element after saving*/
										Testimonial.fn.reset_testimonial_editor();
										notification("Testimonial", "Upload Complete", "gritter-success", false, 3000);
										olc.remove();
										Testimonial.items.table.dataTable().fnDraw();
										Testimonial.modal.edit.modal('hide');
									}
								});

								f.trigger('submit')
							}else{
								olc.remove();
								Testimonial.items.table.dataTable().fnDraw();
								Testimonial.modal.edit.modal('hide');
							}
						}else{
							notification("Testimonial", response['message'] ? response['message'] : 'Something went wrong', "gritter-error");
						}
					}else{
						notification("Testimonial", "Unrecongize response", "gritter-error");
					}

					$("#table-testimonials").dataTable().fnDraw();
				});
			},
			delete_testimonial : function(testimonial_id){
				$.post(CONFIG.get('URL')+'testimonials/processor/',{
					action : 'delete',
					id : testimonial_id,
				},
				function(response) {
					if (!cms_function.isJSON(response)) { notification("Testimonial", "Unknown response", "gritter-error"); return; }
					response = JSON.parse(response);
					if (typeof response['status'] != 'undefined') {
						if (response['status'] == 'success') {
							notification("Testimonial", "Deleted a testimonials", "gritter-success");
						}else{
							notification("Testimonial", response['message'] ? response['message'] : 'Something went wrong', "gritter-error");
						}
					}else{
						notification("Testimonial", "Unrecongize response", "gritter-error");
					}

					$("#table-testimonials").dataTable().fnDraw();
				});
			},
			get_testimonial_detail : function(testimonial_id){
				Testimonial.fn.reset_testimonial_editor();

				$("#current-selected-testimonial-container").hide();
				$("#current-selected-testimonial-loader").show();

				$.post(CONFIG.get('URL')+'testimonials/processor/',{
					action : 'get',
					id : testimonial_id,
				},
				function(response) {
					$("#current-selected-testimonial-container").show();
					$("#current-selected-testimonial-loader").hide();

					if (!cms_function.isJSON(response)) { notification("Testimonial", "Unknown response", "gritter-error"); return; }

					response = JSON.parse(response);
					if (typeof response['data'] != 'undefined') {
						if (typeof response['data']['meta'] != "undefined" && cms_function.isJSON(response['data']['meta'])) {
							var meta = JSON.parse(response['data']['meta']);

							$("#current-selected-testimonial").val(response['data']['id']);
							$("#current-selected-testimonial-name").val(typeof meta['author'] != 'undefined' ? meta['author'] : '');
							$("#current-selected-testimonial-company").val(typeof meta['company'] != 'undefined' ? meta['company'] : '');
							$("#current-selected-testimonial-position").val(typeof meta['position'] != 'undefined' ? meta['position'] : '');
							$("#current-selected-testimonial-status").val(response['data']['status'] ? response['data']['status'] : 'pending');
							$("#current-selected-testimonial-content").val(response['data']['value'] ? response['data']['value'] : '');

							/*retriving meta profile image*/
							cms_function.fn.image_loader($("#current-selected-testimonial-image-previewer"), (meta['profile_picture'] != 'undefined' ? meta['profile_picture'] : cms_function.default_image), function(img, src){
								img.attr('alt', src);
							});
						}
					}else{
						notification("Testimonial", "Unrecongize response", "gritter-error");
					}
				});
			},
			reset_testimonial_editor : function(){
				$("#current-selected-testimonial").val('');
				$("#current-selected-testimonial-name").val('');
				$("#current-selected-testimonial-company").val('');
				$("#current-selected-testimonial-position").val('');
				$("#current-selected-testimonial-status").val('');
				$("#current-selected-testimonial-content").val('');
				$("#current-selected-testimonial-image").ace_file_input('reset_input');
				$("#current-selected-testimonial-image-previewer").attr('src', cms_function.default_image);
				$("#current-selected-testimonial-image-previewer").attr('alt', cms_function.default_image);
			}
		},
	};
});