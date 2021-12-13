$(document).ready(function(){
	var Lang = {
		controls : {
			table : $("#language_table").dataTable({
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": CONFIG.get('URL') + "system-settings/language_table_processor/",
				"aoColumns": [
				{},
				{"sWidth": "250px"},
				{"sWidth": "75px", "bSortable": false},
				],
				"aaSorting": [],
				"fnDrawCallback": function(oSettings) {
					$(".ui-tooltip").remove();
					$(this).find(".btn").tooltip();
					$(this).find(".btn-edit-language").each(function(k, v){
						$(this).click(function(e){
							$id = $(this).attr('data-value');

							Lang.controls.modal.modal('show');
							Lang.controls.modal.find('.lang-loading').remove();
							$('#lang-form').hide();
							$('#lang-id').val('');
							$('#lang-form').before($('<p id="lang-loading" class="alert alert-info lang-loading">Please wait while retrieve data...</p>'));
							$("#modal-btn-save-language").hide();

							$.post(CONFIG.get('URL')+'system-settings/ajax_language_processor/',{
								action : 'get',
								data : JSON.stringify($id),
							},
							function(response) {
								Lang.controls.modal.find('.lang-loading').remove();
								if (cms_function.isJSON(response)) {
									response = JSON.parse(response);

									if (typeof response['status'] !='undefined') {
										$('#lang-form').show();

										$("#lang-id").val( response['data']['id'] );
										$("#lang-title").val( response['data']['title'] );
										$("#lang-slug").val( response['data']['slug'] );

										$("#modal-btn-save-language").show();
									}else{
										notification("Language", "Unable to retrieve Language Information", "gritter-error");
										Lang.controls.modal.modal('hide');
									}
								}else{
									$('#lang-form').before($('<p id="lang-loading" class="alert alert-danger lang-loading">Unable to parse server response</p>'));
								}
							});
						});
					});
					$(this).find(".btn-delete-language").each(function(k, v){
						$(this).click(function(e){
							$id = $(this).attr('data-value');
							bootbox.confirm('Do you want to move selected language to <b class="text-error">TRASHED</b>?', function(result){
								if (result) {
									$.post(CONFIG.get('URL')+'system-settings/ajax_language_processor/',{
										action : 'delete',
										data : JSON.stringify($id),
									},
									function(response) {
										Lang.fn.process_response( response );
									});
								}
							});
						});
					});
					$(this).find(".btn-delete-language-permanent").each(function(k, v){
						$(this).click(function(e){
							$id = $(this).attr('data-value');
							bootbox.confirm('Do you want to <b class="text-error">DELETE</b> selected language <b class="text-error">PERMANENTLY</b>?', function(result){
								if (result) {
									$.post(CONFIG.get('URL')+'system-settings/ajax_language_processor/',{
										action : 'delete-permanent',
										data : JSON.stringify($id),
									},
									function(response) {
										Lang.fn.process_response( response );
									});
								}
							});
						});
					});
					$(this).find(".btn-default-language").each(function(k, v){
						$(this).click(function(e){
							$id = $(this).attr('data-value');
							bootbox.confirm('Do you set deleted language as the <b class="text-info">DEFAULT</b> language?', function(result){
								if (result) {
									Lang.fn.loading(true);
									$.post(CONFIG.get('URL')+'system-settings/ajax_language_processor/',{
										action : 'set-default',
										data : JSON.stringify($id),
									},
									function(response) {
										Lang.fn.loading(false);
										Lang.fn.process_response( response );
									});
								}
							});
						});
					});
					$(this).find(".btn-restore-language").each(function(k, v){
						$(this).click(function(e){
							$id = $(this).attr('data-value');
							bootbox.confirm('Do you want to <b class="text-info">RESTORE</b> selected language?', function(result){
								if (result) {
									$.post(CONFIG.get('URL')+'system-settings/ajax_language_processor/',{
										action : 'restore',
										data : JSON.stringify($id),
									},
									function(response) {
										Lang.fn.process_response( response );
									});
								}
							});
						});
					});
				}, "fnServerParams": function(aoData) {
					/*aoData.push({"name": "variable_name", "value": "variable_value"});*/
				}
			}),
			modal : $("#modal-language").modal({
				backdrop : 'static',
				keyboard : false,
				show : false,
			}),
			btn : {
				open_modal : $("#btn-open-modal-language").click(function(e){
					$("#lang-id").val(-1);
					$("#lang-title").val('');
					$("#lang-slug").val('');

					$("#modal-language").modal('show');
					$("#modal-language").find('.lang-loading').remove();

					$("#lang-form").show();	
					$("#modal-btn-save-language").show();
				}),
				save : $("#modal-btn-save-language").click(function(e){
					Lang.controls.modal.modal('hide');
					Lang.fn.save();
				}),
			}
		},
		fn : {
			save : function(){
				var data = {
					id : $("#lang-id").val(),
					title : $("#lang-title").val(),
					slug : $("#lang-slug").val(),
				}

				Lang.fn.loading(true);
				$.post(CONFIG.get('URL')+'system-settings/ajax_language_processor/',{
					action : 'save',
					data : JSON.stringify(data),
				},
				function(response) {
					Lang.fn.loading(false);
					response = JSON.parse(response);

					if (typeof response != 'undefined') {
						if (typeof response['status'] && response['status'] == 'success') {
							notification("Language", response['message'], "gritter-success");
						}else{
							notification("Language", "Unable to save. " + response['message'], "gritter-error");
						}
					}else{
						notification("Language", "Unable to parse response", "gritter-error");
					}

					Lang.controls.modal.modal('hide');
					Lang.controls.table.dataTable().fnDraw();
					Lang.fn.load_default();
				});
			},
			load_default : function(){
				$.post(CONFIG.get('URL')+'system-settings/ajax_language_processor/',{
					action : 'get-all',
				},
				function(response) {
					response = JSON.parse(response);

					if (typeof response['status'] != 'undefined' && response['status'] == 'success') {
						var d = response['data'];

						$("#lang-default").html('');
						$.each(d, function(k, v){
							$("#lang-default").append($('<option value="'+v['id']+'" '+ (v['selected'] ? 'selected="selected"' : '') +' >'+v['value']+'</option>'));
						});
						$("#lang-default").trigger('chosen:updated').siblings('.chosen-container').find('.chosen-search').remove();
					}
				});
			},
			process_response : function( response ){
			  response = JSON.parse(response);
				if (typeof response['status'] !='undefined') {
					if (response['status']=='success') {
						notification("Language", response['message'], "gritter-success");
					}else{
						notification("Language", response['message'], "gritter-error");
					}
				}else{
					notification("Language", "Unable to retrieve Language Information", "gritter-error");
				}
				Lang.controls.table.dataTable().fnDraw();
				Lang.fn.load_default();
			},
			loading : function ( toggle_boolean ) {
				if (toggle_boolean) {
					if ($("#panel-language .overlay-container").length <= 0) {
						$("#panel-language").append('<div class="overlay-container"><div class="display-table full-width full-height"><div class="display-table-cell"><div class="overlay-content">Processing... Please Wait...</div></div></div></div>');
					}
				}else{
					$("#panel-language .overlay-container").remove();
				}
			}
		}
	}

	Lang.fn.load_default();
});