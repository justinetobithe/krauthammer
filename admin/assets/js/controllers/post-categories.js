var link = '';
var slug = '';

var PC = {
	controls : {
		action : $('#action').val(),
	},
	dialog : $("#loads").modal({
		backdrop 	: 'static',
		keyboard 	: false,
		show 			: false,
	}),
}

$(document).ready(function(){
	var action = $('#action').val();

	if(action == 'manage_categories'){
		initialize_table();
	}else if(action == 'add'){
		initialize_add();
	}else if(action == 'edit'){
		initialize_edit();
	}

	$("#parent").chosen().siblings('.chosen-container').find('.chosen-search').remove();
	$("#language").chosen().siblings('.chosen-container').find('.chosen-search').remove();
});

function initialize_table(){
	$('#categories_table').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL')+"post-categories/get_categories/",
		"aoColumns": [
		{ "bSortable": false },
		null, 
		null,
		null,
		{ "bSortable": false }
		],
		"fnDrawCallback": function( oSettings ) {
			$.each($('.item-checkbox'), function(){
				$(this).parents('td').addClass('center');
				$(this).addClass('ace');
			});
			$.each($('.featured_product'), function(){
				$(this).parents('td').addClass('center');
			});
			$('[data-rel=tooltip]').tooltip();
		}
	});
}
function initialize_add(){
	/*PC.dialog.modal('show');*/
	tinyMCE.init({selector:'#description_tiny',
		menubar: " view edit format table tools",
		height : 200,
		toolbar:[
			"nonbreaking forecolor backcolor  undo redo styleselect bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent  ", 
			" indent link image imageuploader cmsmedia "
		],
		oninit : "setPlainText",
		plugins: [
		"paste link image table code",
		"imageuploader cmsmedia",
		"textcolor",
		"colorpicker wordcount nonbreaking"
		],
		external_plugins:{
			'imageuploader'	:'plugins/imageuploader/editor_plugin_src.js',
      'cmsmedia'      :'plugins/cmsmedia/editor_plugin_src.js',
		},
		relative_urls: false,
		convert_urls: false,
		nonbreaking_force_tab: true,
		tools: "inserttable",
		init_instance_callback : function(editor) {
			load_parent(function(){
				PC.dialog.modal('hide');
			})
		},
	});
	$('#category_name').change(function(){
		$('#url_slug').val(convertToSlug($('#category_name').val()));
		process_slug();
	});
	$('#url_slug').change(function(){
		process_slug();
	});
	$('#parent').change(function(e){
		process_slug();
	});
	$('#save_category').click(function(e){
		if(validate()){
			add_category();
		}
	});
}
function initialize_edit(){
	/*PC.dialog.modal('show');*/
	tinyMCE.init({selector:'#description_tiny',
		menubar: " view edit format table tools",
		height : 200,
		toolbar:[
			"nonbreaking forecolor backcolor  undo redo styleselect bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent  ", 
			" indent link image imageuploader cmsmedia",
		],
		oninit : "setPlainText",
		plugins: [
		"paste link image table code",
		"imageuploader cmsmedia",
		"textcolor",
		"colorpicker wordcount nonbreaking"
		],
		external_plugins:{
			'imageuploader'	:'plugins/imageuploader/editor_plugin_src.js',
      'cmsmedia'      :'plugins/cmsmedia/editor_plugin_src.js',
		},
		relative_urls: false,
		convert_urls: false,
		nonbreaking_force_tab: true,
		tools: "inserttable",
		init_instance_callback : function(editor) {
			/*execute the loading of data after the tinymce is initialized*/
			load_form(function(){
				PC.dialog.modal('hide');
			});
		}
	});
	$('#url_slug').change(function(){
		process_slug();
	});

	/*TO REMOVE: the process will be included in the process_url function*/
	$('#parent').change(function(e){
		process_slug();
	});
	$('#save_category').click(function(e){
		if(validate()){
			update_category();
		}
	});
	$("#language").change(function(e){
		// process_slug();
		load_form();
	});

	$("#btn-delete-category-translation").click(function(e){
		bootbox.confirm("Do you want to continue deleting selected item?", function(result){
			if (result) {
				PC.dialog.modal('show');
				$.post(CONFIG.get('URL')+'post-categories/delete_translation/',{
					id : $("#hidden_id").val(),
					lang : $("#language").val(),
				},function(response) {
					response = JSON.parse(response);

					if (typeof response['status'] != 'undefined' && typeof response['message'] != 'undefined') {
						notification("Post Category", response['message'], "gritter-" + (response['status']? 'success' : 'error'));
					}else{
						notification("Post Category", "Unrecognized response...", "gritter-error");
					}

					load_form(function(){
						PC.dialog.modal('hide');
					});
				});
			}
		});
	});
}

function load_form(fn){
	$.post(CONFIG.get('URL')+'post-categories/get_post_category_data_by_id/',{action:'get', id:$('#hidden_id').val(), language:$('#language').val()}, function(response, status){
		var data = JSON.parse(response);
		if($.isEmptyObject(data) == false){
			$('#category_name').val(data['category_name']);
			$('#url_slug').val(data['url_slug']);
			slug = data['url_slug'];
			// $('#permalink').text(data['url_slug']);
			$('#sort_index').val(data['sort_order']);
			tinyMCE.activeEditor.setContent(data['category_description']);
			// load_parent(data['category_parent']);
			load_parent();
		}else{
			window.location.href = CONFIG.get('URL')+'posts/categories/';
		}

		if (typeof fn != 'undefined') {
			fn();
		}
	});
}
function load_parent(fn){
	var id = $('#hidden_id').length > 0 ? $('#hidden_id').val() : 0;

	$('#parent').html(''); /* Clear items */

	$.post(CONFIG.get('URL')+'post-categories/get_parent/',{action:'get', id: id, language: $("#language").val()}, function(response, status){
		var result  = JSON.parse(response);

		$('#parent').append('<option value="0">No Parent</option>');
		$.each(result, function(i, field){
			$('#parent').append('<option value="'+field['id']+'" '+ (field['is_selected'] != '' ? ' selected="'+ field['is_selected'] +'"' : '') +'>'+field['category_name']+'</option>');
		});
		$('#parent').trigger('chosen:updated');

		process_slug();

		if (typeof fn != 'undefined') {
			fn();
		}
	});
}

function process_slug(){
	if ($('#url_slug').val() =='') {
		$('#url_slug').val(convertToSlug($('#category_name').val()));
	}

	var data = {
		id : $("#hidden_id").length ? $("#hidden_id").val() : 0,
		val : $('#url_slug').val(),
		lang : $('#language').val(),
		parent : $('#parent').val(),
	};

	$("#is-translated").text('Identifying...').removeClass('badge-success badge-important badge-inverse').addClass('badge-info');
	$("#btn-delete-category-translation").hide();

	$.post(CONFIG.get('URL')+'post-categories/get_url_slug/',{action:'get', data: JSON.stringify(data)}, function(response, status){
		var result = JSON.parse(response);

		slug = result['slug'];
		$('#url_slug').val(result['slug']);
		$('#permalink').text(result['permalink']);
		$('#permalink').attr('href', result['permalink']);
		$('#link').val(result['permalink']);

		if (typeof result['translate'] != 'undefined') {
			if (typeof result['translate']['data']['category_description'] != 'undefined') {
				tinyMCE.activeEditor.setContent(result['translate']['data']['category_description']);
			}
			if (typeof result['translate']['data']['category_description'] != 'undefined') {
				$("#category_name").val(result['translate']['data']['category_name']);
			}

			if (result['translate']['type'] == 'main') {
				$("#is-translated").text('Main Language').removeClass('badge-info').addClass('badge-success');
			}
			else if (result['translate']['type'] == 'default') {
				$("#is-translated").text('Default').removeClass('badge-info').addClass('badge-inverse');
			}
			else if (result['translate']['type'] == 'not translated') {
				$("#is-translated").text('No Translation').removeClass('badge-info').addClass('badge-important');
			}
			else if (result['translate']['type'] == 'translated') {
				$("#is-translated").text('Translated').removeClass('badge-info').addClass('badge-success');
				$("#btn-delete-category-translation").show();
			}
			else{
				$("#is-translated").text('Undefined').removeClass('badge-info').addClass('badge-important');
			}
		}
	});
}

function add_category(){
	var data = {};
	PC.dialog.modal('show');
	data['name'] = $('#category_name').val();
	data['description'] = tinyMCE.get('description_tiny').getContent();
	data['parent'] = $('#parent').val();
	data['url_slug'] = $('#url_slug').val();
	data['link'] = $('#link').val();
	data['language'] = $('#language').val();

	$.post(CONFIG.get('URL')+'post-categories/add_category',{action:'add', data:data}, function(response, status){
		var result = JSON.parse(response);
		if(result > 0){
			window.location.href = CONFIG.get('URL')+'post/categories/edit/'+result;
		}
		else
			$('#alert_post_category').append(alertMessage('Unable to Add Category','error','error1'));

		$('#loads').modal('hide');
	});
}
function update_category(){
	var data = {};
	$('#loads').modal('show');
	data['id'] = $('#hidden_id').val();
	data['name'] = $('#category_name').val();
	data['description'] = tinyMCE.get('description_tiny').getContent();
	data['parent'] = $('#parent').val();
	data['url_slug'] = $('#url_slug').val();
	data['link'] = $('#link').val();
	data['sort_index'] = $('#sort_index').val();
	data['language'] = $('#language').val();

	$.post(CONFIG.get('URL')+'post-categories/update_category/',{action:'update', data:data}, function(response, status){

		var result = JSON.parse(response);

		if(result == 1){
			/* $('#alert_post_category').append(alertMessage('Successfully Saved Category','success','success')); */
			notification("Post Category", "Successfully Saved Category", "gritter-success");
		}
		else{
			/* $('#alert_post_category').append(alertMessage('Unable to Update Category','error','error1')); */
			notification("Post Category", "Unable to Update Category", "gritter-error");
		}

		process_slug();

		$('#loads').modal('hide');
	});
}
function delete_category(id){
	$('#hidden_category_id').val(id);
	$('#delete').modal('show');
}
function delete_category_modal(){
	$.post(CONFIG.get('URL')+'post-categories/delete_category',{action:'delete', id:$('#hidden_category_id').val()}, function(response, status){
		var result = JSON.parse(response);
		if(result == 1)
			location.reload();
	});
}

function validate(){

	var error = false;
	$('#alert_post_category').empty();

	if($('#category_name').val() == ''){
		$('#alert_post_category').append(alertMessage('Please Enter Category Name','error','error1'));
		error = true;
	}

	if(error)
		return false;

	return true;
}