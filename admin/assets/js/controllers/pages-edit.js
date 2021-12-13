$(function(){
	$('#parent_id').change(function(e){
		generate_permalink();
	});

	$("#retrieve-loading").modal({
		keyboard 	: false,
		backdrop 	: 'static',
		show 			: false,
	});

	$("#loading").modal({
		keyboard	: false,
		backdrop 	: 'static',
		show 			: false,
	});

	$('#desc_char').text($('#seo_description').val().length);
	$('#title_char').text($('#seo_title').val().length);

	if($('#hdn_no_index').val() == 'Y'){
		$('#seo_no_index').trigger('click');
	}

	$("#language").change(function(e){
		// generate_permalink();
		loadPageContent();
	});

	$("#btn-delete-translation").click(function(e){
		var selected_translation = $("#language").find('option[value='+ $("#language").val() +']').text();
		bootbox.confirm("Are you sure you want to delete selected translation [<b>"+ selected_translation +"</b>]?", function(result){
			if (result) {
				jQuery.post(CONFIG.get('URL')+'pages/remove_translation/',{
					page_id : $("#hdn_page_id").val(),
					lang : $("#language").val(),
				},
				function(response) {
					if (cms_function.isJSON(response)) {
						response = JSON.parse(response);

						if (typeof response['status'] != 'undefined' && response['status'] == 'success') {
							notification("Page Translation", response['message'], "gritter-success");
						}else{
							notification("Page Translation", response['message'], "gritter-error");
						}
						loadPageContent();
					}else{
						notification("Page Translation", "Unable to read response", "gritter-error");
					}
				});
			}
		});
	});

	initilize_editor();
});

function initialize(){
	setForm();
	load_archived();
	load_blur_functions('edit');
	generate_permalink();

	loadPageContent();
	fn_get_album( $("#hdn_page_id").val() );
}

function load_blur_functions(text){
	$('#page_template').change(function(){
		if ($(this).val().toLowerCase().includes("blog")) {
			$("#page-category-tree-container").show()
		}else{
			$("#page-category-tree-container").hide();
			hideBlogCategories();
		}
	}).trigger('change');

	$('#txt_post_title, #txt_url_slug').blur(function(){
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
	var page_id = $("#hdn_page_id").length > 0 ? $("#hdn_page_id").val() : 0;

	if(url_slug == ''){
		url_slug = convertToSlug($('#txt_post_title').val());
		$('#txt_url_slug').val(url_slug);
	}

	proccess_slug(url_slug, parent_id, page_id);
}

function loadPageContent(){
	var page_id = $("#hdn_page_id").val();

	/*$("#retrieve-loading").modal('show');*/
	$("#translation-status-container").html('<span class="badge badge-info">Identifying Translation Status</span>');
	$("#btn-delete-translation").hide();

	jQuery.post(CONFIG.get('URL')+'pages/load_detail/',{page_id:page_id, 'language' : $("#language").val()}, function(response,status){
		if (typeof response['post_content'] != "undefined") {
			tinyMCE.get('content').setContent(response['post_content'] != null ? response['post_content'] : "");
			$("#txt_post_title").val(response['post_title']);
			$("#seo_title").val(response['seo_title']).trigger('keyup');
			$("#seo_description").val(response['seo_description']).trigger('keyup');
			$("#seo_canonical_url").val(response['seo_canonical_url']);
			$("#txt_url_slug").val(response['url_slug']);

			if (response['seo_no_index'] != 'N') {
				if ($("#seo_no_index:checked").length <= 0) {
					$("#seo_no_index").trigger('click');
				}
			}else{
				if ($("#seo_no_index:checked").length > 0) {
					$("#seo_no_index").trigger('click');
				}
			}

			if (typeof response['translated'] != 'undefined') {
				if (response['translated'] == 'main') {
					$("#translation-status-container").html('<span class="badge badge-success">Main Language</span>');
				}else if (response['translated'] == 'translated') {
					$("#btn-delete-translation").show();
					$("#translation-status-container").html('<span class="badge badge-success">Translated</span>');
				}else if (response['translated'] == 'default') {
					$("#translation-status-container").html('<span class="badge badge-inverse">Default</span>');
				}else if (response['translated'] == 'not translated') {
					$("#translation-status-container").html('<span class="badge badge-important">No Translation</span>');
				}
			}else{
				$("#translation-status-container").html('<span class="badge badge-important">Error Identifying Language Status</span>');
			}

			$("#custom-fields-container").html('');

			if (response['meta_data']) {
				var cf = JSON.parse(response['meta_data']);

				$.each(cf, function(k, v){
					if (v.length > 0) {
						$.each(v, function(kk, vv){
							var ncf = $('#template-custom-field').tmpl({'field_name': k, 'field_value' : vv}).appendTo('#custom-fields-container');

							ncf.find('.custom-field-btn-remove').click(function(e){
								bootbox.confirm("Are you sure you want to delete selected item?", function(result){
									if (result) {
										ncf.remove();
									}
								});
							});

							ncf.find('.custom-field-value').focus();
						});
					}
				});
			}
		}else{
			notification("Content Error", "Unable to retrieve content...", "gritter-error");
		}
		/*$("#retrieve-loading").modal('hide');*/

		generate_permalink();
	});
}

/*ajaxFORM*/
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

			if (validateForm()) {
				$("#loading").modal('show');
				$("#load-msg").removeClass();
				return true;
			}else{
				return false;
			}
		},
		complete: function(xhr) {
			respond = JSON.parse(xhr.responseText);

			if(respond > 0){
				$('#permalink').attr("href", $('#permalink').text());
				goToPages();

				$("#loading").modal('hide');
				fn_save_albums($("#hdn_page_id").val(), function(id){
					/*window.location.href = CONFIG.get('URL')+"pages/edit/"+id+"/";*/
					$("#accordion-gallery").html('');
					fn_get_album($("#hdn_page_id").val() );
				});

			}
			else{
				jQuery('#alertPage').append(alertMessage('Unable to Save Page','error','errorsavepage'));
				notification("Page", "Unable to Save Page", "gritter-error")

				$("#loading").modal('hide');
			}

			loadPageContent();
		},
		error:  function(xhr, desc, err) { 
			console.debug(xhr); 
			console.log("Desc: " + desc + "\nErr:" + err); 
		} 
	});
}

function initilize_editor(){
	tinyMCE.init({selector:'#content',
		menubar: " view edit format table tools",
		width : '100%',
		height : 300,
		init_instance_callback : function(editor) {
			initialize();
		},
		toolbar:["nonbreaking forecolor backcolor undo redo bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent indent link image imageuploader cmsmedia "," formatselect fontselect fontsizeselect "],
		plugins: [
			"paste advlist autolink link image lists charmap print preview hr anchor pagebreak ",
			"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			"imageuploader cmsmedia",
			"textcolor colorpicker","nonbreaking table code"
		],
		external_plugins:{
			'imageuploader'	:'plugins/imageuploader/editor_plugin_src.js',
			'cmsmedia'			:'plugins/cmsmedia/editor_plugin_src.js',
		},
		relative_urls: false,
		convert_urls: false,
		nonbreaking_force_tab:true,
		tools: "inserttable",
		verify_html: false
	});
}