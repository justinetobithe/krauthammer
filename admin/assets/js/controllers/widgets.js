var widget_components_info = {};

$(document).ready(function(){
	get_widget_elements(function(){
		initialize_draggable();
		initialize_sortable();

		var arr_var = [];
		var arr_spam = [];
		var arr_widgets = ['archives','calendar','categories','custom_menu','meta','pages','recent_comments','recent_posts','search','text'];

		$(".droppable").droppable({
			drop: function( event, ui ) {
				event.preventDefault();

				var ctr = $(this).find('.external-event').length;
				var class_check = $(this).attr('class');

				if (!$(this).hasClass('widget-sidebar-item')) {
					if (ui.draggable.hasClass('ui-draggable')) {
						var droppable_id = $(this).attr('id');
						var item_type = ui.draggable.attr('id');
						var item_title = ui.draggable.text();
						var item_unique_id = droppable_id + "-" + item_type + '-' + (++ctr); /*html id*/

						if(item_type!=null){
							insert_sidebar_element(droppable_id, item_type, item_title);
							arr_var.push(item_unique_id);
							arr_spam.push(droppable_id+'-'+item_unique_id);
						}
					}
				}else{
					var id = ui.draggable.attr('id');

					if($.inArray(id, arr_widgets) < 0){
						jQuery('div').remove('#'+ id);
						arr_var.pop(id);
					}
				}
			}
		});
	});

	$("#widget-loading").modal({
		backdrop 	: 'static',
		keyboard 	: false,
		show 			: false,
	});
});

function widget_loading_modal(display, message, append){
	if (append === true) {
		$("#widget-loading-container").append(message);
	}else{
		$("#widget-loading-container").html(message);
	}
	if (display == 'show') {
		$("#widget-loading").modal('show');
	}else{
		$("#widget-loading").modal('hide');
	}
}

function initialize_draggable(){
	$('.widget-droppable-element .external-event').draggable({
		zIndex					: 999,
		revert 					: true,		/* will cause the event to go back to its */
		revertDuration	: 300,		/*original position after the drag*/
	});
}
function initialize_sortable(){
	$('.droppable').sortable({
		connectWith	: '.external-event',
		handle			: '.icon-move',	
		update			: function( event, ui ) {
			var order_ctr = 0;
			var data = [];
			$.each($(this).find('.external-event'), function(k, v){
				$(this).find('.widget-hidden-values .widget-order').val(++order_ctr);
				data.push($(this).find('.widget-hidden-values .widget-id').val())
			});

			$.post(CONFIG.get('URL')+'widgets/widget_reorder_elements/',{
				"action"	: 'reorder',
				"data"		: data
			},
			function(response) {
				response = JSON.parse(response);

				if (typeof response['status'] !=' undefined' && response['status'] == 'success') {
					notification("Widget Item", "Widget Item Sorted", "gritter-success");
				}else{
					notification("Widget Item", "Sort Error: There's a problem during sorting, changes might not be saved.", "gritter-error");
				}
			})
		}
	}).disableSelection();
}
function insert_sidebar_element(sidebar_container, type, title){
	var droppable_container = $("#" + sidebar_container);
	var sidebar_id = droppable_container.siblings('.widget-sidebar-hidden-fields').find('.widget-sidebar-id').val();
	var widget_type = type;
	var element_order = droppable_container.find(".external-event").length + 1;

	widget_loading_modal('show', "Adding New Widget...")
	$.post(CONFIG.get('URL')+'widgets/widget_add_elements/',{
		"sidebar_id"	: sidebar_id,
		"widget_id"	: widget_type,
		"element_order"	: element_order,
	},
	function(response) {
		response = JSON.parse(response);

		var item_unique_id = sidebar_container + "-" + type + "-" + element_order;
		var item_id = response['item_id'];
		var item_name = title;
		var item_type = type;

		var ncf = $("#element-item").tmpl({
			'element_name'			: item_name,
			'element_type'			: item_type,
			'element_id'				: item_id,
			'element_order'			: element_order,
			'element_data_id'		: item_id,
			'element_unique_id'	: item_unique_id,
		}).appendTo(droppable_container);

		if (typeof widget_components_info[item_type] !='undefined') {
			var widget_info = widget_components_info[item_type];

			var field_counter = 0;
			$.each(widget_info['widget_fields'], function(k, v){
				if ($("#cms-widget-layout-field-" + v['type']).length>0 && ncf.find('.widget-field-container').length>0) {
					var field_data = {
						'widget_title' 			: v['label'],
						'widget_unique_id' 	: item_unique_id + "-" + v['type'] + "-" + (field_counter+1),
					};

					if (v['type']=="dropdown") {
						$d = '<select class="ace widget-field widget-field-dropdown">';
						if (typeof v['values'] != "undefined" && v['values'].length > 0) {
							$.each(v['values'], function(kkk, vvv){
								$d += '<option value="'+vvv[0]+'">'+vvv[1]+'</option>';
							});
						}
						$d += "</select>";
						field_data['widget_data'] = $d;
					}

					if (typeof v['sub-title'] != 'undefined') {
						field_data['widget_sub_title'] = v['sub-title'];
					}

					var _f = $("#cms-widget-layout-field-" + v['type']).tmpl(field_data).appendTo(ncf.find('.widget-field-container'));

					if (v['type']=="textarea") {
						tinyMCE.init({selector:'.widget-field-group textarea',
							menubar: "",
							height : 100,
							oninit : "setPlainText",
							toolbar:["bold italic alignleft aligncenter alignright alignjustify link formatselect code"],
							plugins: ["code"],
							external_plugins:{'imageuploader':'plugins/imageuploader/editor_plugin_src.js'},
							relative_urls: false,
							convert_urls: false,
							nonbreaking_force_tab:true,
							tools: "inserttable",
							verify_html: false
						});
					}else if(v['type']=="dropdown"){
						_f.find('.widget-field-dropdown').chosen({'width':'100%'})
					}
				}
				field_counter++;
			});
		}
		initialize_widget_sidebar_item_controls(ncf)

		widget_loading_modal('hide')
	});
}
function initialize_widget_sidebar_item_controls(ncf){
	/*Initializing Actions*/
	ncf.find('.widget-remove-button').click(function(){
		bootbox.confirm("Remove This Widget?", function(result){
			if (result) {
				widget_loading_modal('show', "Removing widgets")
				var widget_id = ncf.find('.widget-hidden-values .widget-id').val();
				$.post(CONFIG.get('URL')+'widgets/widget_delete_elements/',{
					action 	: 'delete',
					data 		: widget_id,
				},
				function(response) {
					response = JSON.parse(response);
					if (response['status'] == 'success') {
						notification("Widget Item", "Widget Item has beed Removed...", "gritter-warning");
					}else{
						notification("Widget Item", "Widget Item has beed Removed...", "gritter-error");
					}
					ncf.remove()
					widget_loading_modal('hide')
				});
			}
		});
	});
	ncf.find('.widget-save-button').click(function(){
		save_widget(ncf);
	});
}

function get_widget_elements( callback_function ){
	$.post(CONFIG.get('URL')+'widgets/widget_get_elements/',{
	},
	function(response) {
		
		var ctr = 0;

		if (typeof response['widgets'] != "undefined") {
			var widgets = response['widgets'];

			$.each(widgets, function(k, v){
				if (ctr==0) {
					$("#cms-widget-element-container").append('<div class="row-fluid"></div>')
				}

				widget_components_info[v['widget_id']] = v;

				$("#cms-widget-layout-element").tmpl({
					'widget_element_id'						: v['widget_id'],
					'widget_element_name'					: v['widget_name'],
					'widget_element_description'	: v['widget_description'],
				}).appendTo($("#cms-widget-element-container .row-fluid:last-child"));

				ctr = ++ctr>1?0:ctr;
			});

		}

		if (typeof callback_function != 'undefined') {
			get_widget_sidebars(callback_function);
		}
	});
}
function get_widget_sidebars( callback_function ){
	$.post(CONFIG.get('URL')+'widgets/widget_get_sidebars/',{

	},
	function(response) {
		if (typeof response['sidebars'] != "undefined") {
			var sidebars = response['sidebars'];

			$.each(sidebars, function(k, v){
				$("#cms-widget-layout-sidebar").tmpl({
					'widget_sidebar_id'						: v['sidebar_id'],
					'widget_sidebar_name'					: v['sidebar_name'],
				}).appendTo($("#cms-widget-sidebar-container"));

				get_widget_sidebars_set_items( v );
			});
		}

		if (typeof callback_function != 'undefined') {
			callback_function();
			$("#cms-widget-element-container").fadeIn(300);
			$("#cms-widget-sidebar-container").fadeIn(300);
		}

	});
}
function get_widget_sidebars_set_items( item ){
	var container = $("#drop_container_"+item['sidebar_id']);
	if (typeof item['sidebar_items'] != 'undefined') {
		var ctr = 0;
		$.each(item['sidebar_items'], function(k, v){
			/* Adding of fields*/
			if (typeof widget_components_info[v['widget_id']] !='undefined') {
				var widget_info = widget_components_info[v['widget_id']];
				/* Display added items */
				var type 							= widget_info['widget_id'];
				var name							= widget_info['widget_name'];
				var element_id				= v['item_id'];
				var element_order			= container.find(".external-event").length;
				var element_data_id		= widget_info['widget_id'];
				var element_unique_id	= "drop_container_" + item['sidebar_id'] + "-" + type + "-" + (++ctr);

				var ncf = $("#element-created").tmpl({
					'element_id'				: element_id,
					'element_name'			: name,
					'element_type'			: type,
					'element_order'			: element_order + 1,
					'element_data_id'		: element_data_id,
					'element_unique_id'	: element_unique_id,
				}).appendTo($("#drop_container_"+item['sidebar_id']));

				var field_counter = 0;
				$.each(widget_info['widget_fields'], function(kk, vv){
					if ($("#cms-widget-layout-field-" + vv['type']).length>0 && ncf.find('.widget-field-container').length>0) {
						var field_id = element_unique_id + "-" + vv['type'] + "-" + (field_counter+1);
						var field_data = {
							'widget_title' 			: vv['label'],
							'widget_unique_id' 	: field_id,
						};

						if (vv['type']=="dropdown") {
							$d = '<select class="ace widget-field widget-field-dropdown">';
							if (typeof vv['values'] != "undefined" && vv['values'].length > 0) {
								$.each(vv['values'], function(kkk, vvv){
									$d += '<option value="'+vvv[0]+'">'+vvv[1]+'</option>';
								});
							}
							$d += "</select>";
							field_data['widget_data'] = $d;
						}


						if (typeof vv['sub-title'] != 'undefined') {
							field_data['widget_sub_title'] = vv['sub-title'];
						}

						var _f = $("#cms-widget-layout-field-" + vv['type']).tmpl(field_data).appendTo(ncf.find('.widget-field-container'));

						if (vv['type']=="textarea") {
							/*
							Create a copy of the field_counter; tinymce will do a separate process which cause a bug if use the field_counter variable. 
							It will get the latest value if the variable after the tinymce is fully loaded
							*/
							var tactr 			= field_counter;
							var tinymce_id 	= "#"+field_id;

							tinyMCE.init({selector: tinymce_id,
								menubar: "",
								height : 100,
								oninit : "setPlainText",
								toolbar:["bold italic alignleft aligncenter alignright alignjustify link formatselect code"],
								plugins: ["code"],
								external_plugins:{'imageuploader':'plugins/imageuploader/editor_plugin_src.js'},
								relative_urls: false,
								convert_urls: false,
								nonbreaking_force_tab:true,
								tools: "inserttable",
								verify_html: false,
								init_instance_callback : function(editor) {
									if (typeof v['fields'] !='undefined') {
										var field_item = v['fields'];
										editor.setContent(field_item[tactr]['val'])
									}
								},
							});
						}else if(vv['type']=="dropdown"){
							if (typeof v['fields'] !='undefined') {
								var field_item = v['fields'];
								_f.find('.widget-field-dropdown').val(field_item[field_counter]['val']).chosen({'width':'100%'})
							}
						}else{
							if (typeof v['fields'] !='undefined') {
								var field_item = v['fields'];
								if (typeof field_item[field_counter] != 'undefined' && typeof field_item[field_counter]['type'] != 'undefined') {
									set_widget_item_value(_f, field_item[field_counter]['type'], field_item[field_counter]['val']);
								}
							}
						}
					}

					field_counter++;
				});

				initialize_widget_sidebar_item_controls(ncf)
			}
		});
	}
}

function save_widget(sidebar_widget_item){
	var sidebar_id = sidebar_widget_item.attr('id');
	var widget_data = {
		item_id : sidebar_widget_item.find('.widget-id').val(),
		fields : [],
	};

	var field_counter = 0;
	$.each(sidebar_widget_item.find('.widget-field-group'), function(k, v){
		var type = $(this).find('.widget-field').attr('type');
		var val = "";

		if (type == 'checkbox'){
			val 	= $(this).find('input[type=checkbox]').is(':checked') ? 'Y':'N'
		}else if( $(this).find('.widget-field').is('select') ){
			val 	= $(this).find('.widget-field').val();
			type 	= 'dropdown';
		}else if( $(this).find('.widget-field').hasClass('widget-field-textarea') ){
			var tinymceID = sidebar_id + "-textarea-" + (field_counter+1);
			val 	= tinyMCE.get(tinymceID).getContent();
			type 	= 'textarea';
		}else if( $(this).find('.widget-field').hasClass('widget-field-textarea-plain') ){
			type 	= 'textarea-plain';
			val 	= $(this).find('textarea').val()
		}else{
			val = $(this).find('input,textarea').val()
		}

		widget_data['fields'].push({
			'type' : type,
			'val' : val,
		});

		field_counter++;
	});

	widget_loading_modal('show', "Saving Widget Sidebar Item")
	$.post(CONFIG.get('URL')+'widgets/widget_save_elements/',{
		action 	: 'save',
		data 		: widget_data,
	},
	function(response) {
		response = JSON.parse(response);
		if (response['status'] == 'success') {
			notification("Widget Item", "Widget Item Saved...", "gritter-success");
		}

		widget_loading_modal('hide')
	});
}

function set_widget_item_value(the_item, the_type, the_value){
	if (the_type == 'text') {
		the_item.find('input').val(the_value)
	}else if (the_type == 'textarea-plain'){
		the_item.find('textarea').val(the_value)
	}else if (the_type == 'checkbox'){
		if (the_value == 'Y'){
			the_item.find('input[type=checkbox]').trigger('click');
		}
	}
}