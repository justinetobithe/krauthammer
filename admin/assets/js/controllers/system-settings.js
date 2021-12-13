$(document).ready(function(){
	
	if($('#action').val() == 'index') index_page();

	/*Initialize Elements*/
	$(".chosen-select").chosen({ width : '100%'});
});

function index_page(){
	$('#users').change(function(e){
		e.preventDefault(e);
		$('.check_module:checked').each(function(i, obj) {
			$(this).prop('checked',false);
		});
		load_checkbox($(this).val());
	});
	$('#system_type').change(function(e){
		e.preventDefault(e);
		$('#system_type_modules').val([]).trigger('chosen:updated');
		load_module_for_cms_type();
	}).trigger("change");
	$('#save_system_settings').click(function(e){
		e.preventDefault();
		save_system_settings();
	});

	$('#save_module_priviles').click(function(e){
		$('#alert_for_assign_acess').empty();
		e.preventDefault();
		var modules = [];
		var user = $('#users').val();
		var data = {};
		
		if(user != 0){
			if($('.check_module:checked').length > 0){
				$('.check_module:checked').each(function(i, obj) {
				   modules.push($(this).val());
				});

				data['user_id'] = user;
				data['modules'] = modules;

				$.post(CONFIG.get('URL')+'system-settings/save_module_priviles', {action:'save', data:data}, function(response,status){
					// alert(response);
					notification('Module Management', 'User Assign Module has been Updated','gritter-success')
				});
			}
			else
				notification('Module Management', 'Please Add A Module For This Certain User','gritter-error')
				// jQuery('#alert_for_assign_acess').append(alertMessage('Please Add A Module For This Certain User','error','error_2'));
		}else{
			// jQuery('#alert_for_assign_acess').append(alertMessage('Please Select User','error','error_1'));
			notification('Module Management', 'Please Select User','gritter-error')
		}
	});
}

function save_system_settings(){
	var system_type = $('#system_type').val();
	var system_type_modules = $('#system_type_modules').val();
	$('#result').empty();
	$.post(CONFIG.get('URL')+'system-settings/save_system_settings',
	{
		action: 'system_settings', 
		value: system_type, 
		system_type_modules: system_type_modules, 
		system_option: 'system_type'
	},function(response,status){
		if(status == 'success'){
			if(JSON.parse(response) == 1){
				// jQuery('#result').append(alertMessage('Sucessfully Save System Settings','success','save_settings'));
				notification('System Setting!!!', 'Sucessfully Save System Settings','gritter-success');
				window.location.href= CONFIG.get('URL') + "system-settings/";
			}
			else
				// jQuery('#result').append(alertMessage('Unable to save system settings. Please Try Again','error','error_saving'));
				notification('System Setting!!!', 'Unable to save system settings. Please Try Again','gritter-error')
		}
		else
			// jQuery('#result').append(alertMessage('404- No Network Found','error','error_network'));
			notification('System Setting!!!', '404- No Network Found','gritter-warning')
	});
}

function load_checkbox(id){
	$.post(CONFIG.get('URL')+'system-settings/get_module_by_user',{action: 'get', user_id: id}, function(response, status){
		var obj = JSON.parse(response);

		$.each(obj,function(i, module){
			$('.check_module').each(function() {
				if($(this).val() == module['module_id'])
					$(this).prop('checked',true);
		});
		});
	});
}
function load_module_for_cms_type(id){
	$.post(CONFIG.get('URL')+'system-settings/ajax_module_processor',
	{action: 'get-cms-modules', 'type': $('#system_type').val()}, 
	function(response, status){
		if (response.length > 0) {
			$("#system_type_modules").val(response).trigger('chosen:updated');
		}
	});
}