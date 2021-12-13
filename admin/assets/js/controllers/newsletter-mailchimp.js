$(function(){
	$("#modal-add-newsletter-loading").modal({
		backdrop : 'static',
		keyboard : false,
		show : false,
	});

	$("#btn-save-mailchimp-api-key").click(function(e){
		fn_save_api_key( $("#mailchimp-api-key").val() )
	});

	$("#table-mailchimp-list").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "newsletter/processor/",
		"aoColumns": [
		{"sWidth": "5%", "bSortable": false},
		{"sWidth": "45%"},
		{"sWidth": "40%", "bSortable": false},
		{"sWidth": "10%", "bSortable": false},
		],"fnDrawCallback": function(oSettings) {
			$(this).find('.item-checkbox').each(function(){
			  $(this).change(function(){
			    var is_enabled = $(this).is(":checked") ? "Yes" : "No";
			    var newsletter_id = $(this).attr("data-value");
			    $.post(CONFIG.get('URL')+'newsletter/processor/',{
			    	action : "save-newsletter-enable-status",
			    	id : newsletter_id,
			    	status : is_enabled,
			    },
			    function(response) {
			    	notification("Newsletter", "Newsletter status saved", "gritter-info");
			    });
			  });
			});
		}, "fnServerParams": function(aoData) {
			aoData.push({"name": "action", "value": "load-mailchimp-list"})
		},
	});

	$("#btn-mainchimp-refresh-list").click(function(){
	  fn_refresh_list();
	});

	$("#btn-mailchimp-save-changes").click(function(){
	  fn_save_settings();
	});

	// fn_get_api_key();
	fn_load_settings();
});

function fn_save_api_key(api_key){
	if (typeof api_key == 'undefined') {
		notification("Newsletter", "No API Key Entered", "gritter-error");
		return;
	}

	$("#modal-add-newsletter-loading").modal('show');
	$.post(CONFIG.get('URL')+'newsletter/processor/',{
		action : 'save-api-key',
		key : api_key,
	},
	function(response) {
		console.log(response);
		if (response) {
			notification("Newsletter", "API Key Saved", "gritter-success");
		}else{
			notification("Newsletter", "Unable to save API Key", "gritter-error");
		}
		$("#table-mailchimp-list").dataTable().fnDraw();
		$("#modal-add-newsletter-loading").modal('hide');
	});
}
function fn_save_settings(){
	var data = {
		subscription_label : $("#mailchimp-contact-form-checkbox-label").val(),
		precheck : $(".mailchimp-precheck-radio:checked").val(),
		autoupdate : $(".mailchimp-auto-update-radio:checked").val(),
	};
	var api_key = $("#mailchimp-api-key").val();

	$("#modal-add-newsletter-loading").modal('show');

	$.post(CONFIG.get('URL')+'newsletter/processor/',{
		action : 'save-mailchimp-settings',
		key : api_key,
		data : JSON.stringify(data),
	},function(response) {
		console.log(response);
		if (response) {
			notification("MailChimp", "Mailchimp Setting Saved", "gritter-success");
		}else{
			notification("MailChimp", "Unable to save settings", "gritter-error");
		}
		$("#table-mailchimp-list").dataTable().fnDraw();
		$("#modal-add-newsletter-loading").modal('hide');
	});
}
function fn_get_api_key(){
	$("#modal-add-newsletter-loading").modal('show');
	$.post(CONFIG.get('URL')+'newsletter/processor/',{
		action : 'load-api-key',
	},
	function(response) {
		console.log(response);
		$("#mailchimp-api-key").val( response );
		$("#modal-add-newsletter-loading").modal('hide');
	});
}
function fn_load_settings(){
	$("#modal-add-newsletter-loading").modal('show');

	$.post(CONFIG.get('URL')+'newsletter/processor/',{
		action : 'load-settings',
	},
	function(response) {
		console.log(response);

		$json_response = JSON.parse( response );

		$("#mailchimp-api-key").val($json_response['api_key']);
		$("#mailchimp-contact-form-checkbox-label").val($json_response['checkbox_label']);

		$(".mailchimp-precheck-radio[value='" + $json_response['default_value'] + "']").attr("checked", "checked");
		$(".mailchimp-auto-update-radio[value='" + $json_response['checkbox_label'] + "']").attr("checked", "checked");

		$("#modal-add-newsletter-loading").modal('hide');
	});
}
function fn_refresh_list(){
	$("#modal-add-newsletter-loading").modal('show');
	$.post(CONFIG.get('URL')+'newsletter/processor/',{
		action : 'fresh-mailchimp-list',
	},
	function(response) {
		$("#table-mailchimp-list").dataTable().fnDraw();
		$("#modal-add-newsletter-loading").modal('hide');
		notification("Newsletter", "Newsletter List has been refreshed", "gritter-success");
	});
}
