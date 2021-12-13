var product_billing_vars = {
	'current_item' 					: 0,
	'current_product' 			: $("#product_id").length > 0 ? $("#product_id").val() : '',
	'current_product_files' : [],
	'current_has_changes' 	: false,
};

$(document).ready(function(){
	$("#modal-product-billing-period").modal({ keyboard : false, show : false }).on('hidden',function(e){
		product_billing_vars.current_item = 0;
	});
	$("#modal-product-billing-period-global-subscription-detail").modal({ keyboard : false, show : false });
	$("#modal-product-downloadable-files").modal({ keyboard : false, show : false, backdrop: 'static' });
	$("#modal-reactivate-user-loading").modal({ keyboard : false, show : false, backdrop: 'static' });
	$("#modal-suspend-user-loading").modal({ keyboard : false, show : false, backdrop: 'static' });

	$(".method_option.chosen-select").chosen({ width:'100%' });

	$("#paypal_subscription_enable_trial").change(function(e){
		if ($(this).is(":checked")) {
			$("#container-billing-period-trial").show();
		}else{
			$("#container-billing-period-trial").hide();
		}
	});
	$("#btn-open-modal-billing-period").click(function(e){
		e.preventDefault();
		$("#modal-product-billing-period").modal('show');
	});


	$("#table-billing-period").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "products/billing_period_table_processor/",
		"aoColumns": [
		{"sWidth": 50, "bSortable": false},
		{"sWidth": 50, "bSortable": false},
		null,
		{"sWidth": 200, "bSortable": false},
		{"sWidth": 50, "bSortable": false},
		], "fnDrawCallback": function(oSettings) {

		}, "fnServerParams": function(aoData) {
			aoData.push({"name": "product_id", "value": product_billing_vars.current_product});
		}
	});
	$("#table-billing-period").on('click', '.billing-period-default', function(e){
		$(this).closest('tr').siblings('tr').find('.billing-period-default').prop('checked', false);
	});
	$("#table-billing-period").on('click', '.btn-billing-period-edit', function(e){
		var selected_item_id  = $(this).data('value');
		product_billing_vars.current_item = selected_item_id;
		product_billing_get_billing_period(selected_item_id);
	});
	$("#btn-save-billing-period").click(function(e){
		e.preventDefault();
		if (check_subscription_fields()) {
			product_billing_save();
		}
	});
	$("#paypal_subscription_plan_type").change(function(e){
		if ($(this).val() == 'INFINITE') {
			$("#paypal_subscription_cycle").attr('min', 0).val('0').prop('disabled', true);
		}else{
			$("#paypal_subscription_cycle").attr('min', 1).val('1').prop('disabled', false);
		}
	});
	$("#paypal_subscription_frequency").change(function(e){
		if ($(this).val() == 'MONTH') {
			$("#paypal_subscription_frequency_interval").attr('max', 12).val('1');
		}else{
			$("#paypal_subscription_frequency_interval").removeAttr('max').val('1');
		}
	});
	$("#paypal_subscription_frequency_trial").change(function(e){
		if ($(this).val() == 'MONTH') {
			$("#paypal_subscription_frequency_interval_trial").attr('max', 12).val('1');
		}else{
			$("#paypal_subscription_frequency_interval_trial").removeAttr('max').val('1');
		}
	});
	$("#paypal_subscription_frequency_interval, #paypal_subscription_frequency_interval_trial").blur(function(e){
		var freq_int = $(this).val();
		var freq_max = $(this).attr('max');

		if (parseInt(freq_int) > parseInt(freq_max)) {
			$(this).val(freq_max);
		}
	});
	$("#paypal_subscription_cycle, #paypal_subscription_cycle_trial").blur(function(e){
		var freq_int = $(this).val();
		var freq_min = $(this).attr('min');

		if (parseInt(freq_int) < parseInt(freq_min)) {
			$(this).val(freq_min);
		}
	});

	$("#btn-save-paypal-subscription-plan").click(function(e){
		save_selected_billing_period_settings();
	});

	$("#product-billing-period-type").change(function(e){
		if ($(this).val() == 'Subscription') {
			$("#content-one-time").hide();
			$("#content-subscription-global").hide();
			$("#content-subscription").show();
			$("#content-subscibers").show();

			$("#table-billing-period-subscribers").dataTable().fnDraw();
		}else if($(this).val() == 'Global Subscription'){
			$("#content-one-time").hide();
			$("#content-subscription").hide();
			$("#content-subscription-global").show();
			$("#content-subscibers").show();

			$("#table-billing-period-global-subscription").dataTable().fnDraw();
			$("#table-billing-period-subscribers").dataTable().fnDraw();
		}else{
			$("#content-subscription").hide();
			$("#content-subscription-global").hide();
			$("#content-one-time").show();
			$("#content-subscibers").hide();
		}
	});
	$("#product-type").change(function(e){
		if ($(this).val() == 'Downloadable') {
			$("#content-downloadable-files").show();
			$("#content-physical-goods").hide();
		}else{
			$("#content-downloadable-files").hide();
			$("#content-physical-goods").show();
		}
	}).trigger('change');

	$("#table-billing-period-global-subscription").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "products/billing_period_global_subscription_table_processor/",
		"aoColumns": [
			{"sWidth": 30, "bSortable": false},
			null,
			{"sWidth": 250, "bSortable": false},
		], "fnDrawCallback": function(oSettings) {
			
		}, "fnServerParams": function(aoData) {
			aoData.push({"name": "product_id", "value": product_billing_vars.current_product});
		}
	}).on('click', '.btn-view-global-subscription-detail', function(e){
		product_billing_get_global_subscription($(this).data('value'));
	});
	$("#table-billing-period-subscribers").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "products/billing_period_subscriber_processor/",
		"aoColumns": [
			null,
			{"sWidth": 130, "bSortable": false},
			{"sWidth": 80, "bSortable": false},
			{"sWidth": 50, "bSortable": false},
		], 
		"fnDrawCallback": function(oSettings) {
			$(".btn-subscriber-cancel").click(function(e){
				var id = $(this).data('value');
				bootbox.confirm("Do you want to suspend the selected subscriber?", function(result){
				  if (result) {
				  	$("#modal-suspend-user-loading").modal('show');
				    $.post(CONFIG.get('URL') + "products/billing_period_processor/",{
				    	action 	: 'suspend',
				    	id 			: id,
				    },function(response) {
				    	$("#table-billing-period-subscribers").dataTable().fnDraw();
				    	$("#modal-suspend-user-loading").modal('hide');
				    });
				  }
				});
			});
			$(".btn-subscriber-reactivate").click(function(e){
				var id = $(this).data('value');
				bootbox.confirm("Reactivate this subscriber?", function(result){
				  if (result) {
				  	$("#modal-reactivate-user-loading").modal('show');
				    $.post(CONFIG.get('URL') + "products/billing_period_processor/",{
				    	action 	: 'reactivate',
				    	id 			: id,
				    },function(response) {
				    	$("#table-billing-period-subscribers").dataTable().fnDraw();
				    	$("#modal-reactivate-user-loading").modal('hide');
				    });
				  }
				});
			});
		}, 
		"fnServerParams": function(aoData) {
			aoData.push({"name": "product_id", "value": product_billing_vars.current_product});
			aoData.push({"name": "payment_type", "value": $("#product-billing-period-type").val()});
		}
	}).on('click', '.btn-view-global-subscription-detail', function(e){
		product_billing_get_global_subscription($(this).data('value'));
	});

	$("#table-product-downloadable-files").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "products/product_files_table_processor/",
		"aoColumns": [
			null,
			{"sWidth": 200},
			{"sWidth": 50, "bSortable": false},
		], "fnDrawCallback": function(oSettings) {
			
		}, "fnServerParams": function(aoData) {
			aoData.push({"name": "product_id", "value": product_billing_vars.current_product});
			aoData.push({"name": "product_files", "value": product_billing_vars.current_product_files});
		}
	}).on('click', '.btn-view-file', function(e){
		var selected_file = $(this).data('value');
		bootbox.confirm("Do you continue deleting selected item?", function(result){
		  if (result) {
		    remove_downloadable_files(selected_file)
		  }
		});
	});

	$("#billing-period-product-files-media").click(function(e){
		e.preventDefault();
		$("#modal-product-downloadable-files").modal('show');
		$("#table-billing-period-media").dataTable().fnDraw();
	});

	$("#table-billing-period-media").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "products/product_media_table_processor/",
		"iDisplayLength": 5,
		"aoColumns": [
			{"sWidth": 30, "bSortable": false},
			null,
			{"sWidth": 100},
		], "fnDrawCallback": function(oSettings) {
			$("#table-billing-period-media-check-all").prop("checked", false);
		}, "fnServerParams": function(aoData) {
			aoData.push({"name": "product_id", "value": product_billing_vars.current_product});
			aoData.push({"name": "product_files", "value": product_billing_vars.current_product_files});
		}
	}).on('click','.item-checkbox', function(e){
		if (!$(this).is(":checked")) {
			$("#table-billing-period-media-check-all").prop("checked", false);
		}
	});
	$("#table-billing-period-media-check-all").click(function(e){
		if ($(this).is(":checked")) {
			$("#table-billing-period-media tbody input[type=checkbox]").prop('checked', true);
		}else{
			$("#table-billing-period-media tbody input[type=checkbox]").prop('checked', false);
		}
	});
	$("#btn-downloadable-add-selected-files").click(function(e){
		var selected_files = [];
		$.each($("#table-billing-period-media tbody .item-checkbox:checked"), function(k, v){
			// selected_files.push($(this).data('value'));
			if (product_billing_vars.current_product_files.indexOf($(this).data('value'))<0) {
				product_billing_vars.current_product_files.push($(this).data('value'));
			}
		});

		// product_billing_vars.current_product_files = selected_files;

		notification("Product Files", "Files has been selected", "gritter-success");

		$("#table-product-downloadable-files").dataTable().fnDraw();
		$("#modal-product-downloadable-files").modal('hide');

		// return;

		// $.post(CONFIG.get('URL')+'products/billing_period_processor/', {
		// 	action 	: 'add-billing-period-file', 
		// 	product : product_billing_vars.current_product,
		// 	files 	: selected_files,
		// }, function(response, status){
		// 	if (cms_function.isJSON(response)) {
		// 		var r = JSON.parse(response);
		// 		if (r.status) {
		// 			$("#table-product-downloadable-files").dataTable().fnDraw();
		// 			$("#modal-product-downloadable-files").modal('hide');

		// 			notification("Billing Period", r.message, "gritter-success");
		// 		}else{
		// 			notification("Billing Period", r.message, "gritter-error");
		// 		}
		// 	}else{
		// 		notification("Billing Period", "Unknown Response", "gritter-error");
		// 	}
		// }).fail(function(e) {
		// 	notification("Payment Gateway", e.message, "gritter-error");
		// });
	});

	/* Initialize */
	product_billing_get_settings();
});

function product_billing_save(){
	var selected_data = {};
	var selected_global_subscription = [];
	var billing_period_setting = {
		'enable' 				: $("#product-billing-period-toggle").is(":checked") ? 'YES' : 'NO',
		'type' 					: $("#product-billing-period-type").val(),
		'product_type' 	: $("#product-type").val(),
	};

	$.each($("#table-billing-period tbody tr"), function(k, v){
		var item = {
			'item_id' : $(this).find('.billing-period-default').data('value'),
			'default' : $(this).find('.billing-period-default').is(':checked') ? 'YES' : 'NO',
			'enable'  : $(this).find('.billing-period-enable').is(':checked') ? 'YES' : 'NO',
		}
		selected_data[item['item_id']] = item;
	});
	$.each($("#table-billing-period-global-subscription tbody tr"), function(k, v){
		var chkbox = $(this).find('.billing-period-global-subs');
		if (chkbox.is(':checked') && $("#product-billing-period-type").val() == 'Global Subscription') {
			selected_global_subscription.push( chkbox.data('value') );
		}
	});

	$.post(CONFIG.get('URL')+'products/billing_period_processor/', {
		action 	: 'save-billing-period', 
		prod_id : product_billing_vars.current_product,
		data 		: selected_data,
		data_sub: selected_global_subscription,
		setting : billing_period_setting,
		files 	: product_billing_vars.current_product_files,
	}, function(response, status){
		if (cms_function.isJSON(response)) {
			var r = JSON.parse(response);
			if (r.status) {
				notification("Billing Period", r.message, "gritter-success");
				$("#table-billing-period").dataTable().fnDraw();
				$("#table-billing-period-global-subscription").dataTable().fnDraw();
				$("#table-billing-period-subscribers").dataTable().fnDraw();
			}else{
				notification("Billing Period", r.message, "gritter-error");
			}
		}else{
			notification("Billing Period", "Unknown Response", "gritter-error");
		}

		$("#modal-product-billing-period").modal('hide');
	}).fail(function(e) {
		notification("Payment Gateway", e.message, "gritter-error");
	});
}
function product_billing_set_modal_fields(product_billing_set_modal_fields){
	product_billing_set_modal_fields != undefined && product_billing_set_modal_fields.length > 0 ? product_billing_set_modal_fields : {};
	product_billing_set_modal_fields['plan_name'] = product_billing_set_modal_fields['plan_name'] != undefined ? product_billing_set_modal_fields['plan_name'] : '';; 
	product_billing_set_modal_fields['plan_description'] = product_billing_set_modal_fields['plan_description'] != undefined ? product_billing_set_modal_fields['plan_description'] : ''; 
	product_billing_set_modal_fields['plan_url_return'] = product_billing_set_modal_fields['plan_url_return'] != undefined ? product_billing_set_modal_fields['plan_url_return'] : ''; 
	product_billing_set_modal_fields['plan_url_cancel'] = product_billing_set_modal_fields['plan_url_cancel'] != undefined ? product_billing_set_modal_fields['plan_url_cancel'] : ''; 
	product_billing_set_modal_fields['plan_type'] = product_billing_set_modal_fields['plan_type'] != undefined ? product_billing_set_modal_fields['plan_type'] : 'INFINITE'; 
	product_billing_set_modal_fields['plan_auto_billing'] = product_billing_set_modal_fields['plan_auto_billing'] != undefined ? product_billing_set_modal_fields['plan_auto_billing'] : 'YES'; 
	product_billing_set_modal_fields['plan_initial_fail_action'] = product_billing_set_modal_fields['plan_initial_fail_action'] != undefined ? product_billing_set_modal_fields['plan_initial_fail_action'] : 'CONTINUE'; 
	product_billing_set_modal_fields['plan_max_fail_attempts'] = product_billing_set_modal_fields['plan_max_fail_attempts'] != undefined ? product_billing_set_modal_fields['plan_max_fail_attempts'] : '1'; 
	product_billing_set_modal_fields['title_trial'] = product_billing_set_modal_fields['title_trial'] != undefined ? product_billing_set_modal_fields['title_trial'] : 'TRIAL'; 
	product_billing_set_modal_fields['amount_trial'] = product_billing_set_modal_fields['amount_trial'] != undefined ? product_billing_set_modal_fields['amount_trial'] : '0'; 
	product_billing_set_modal_fields['frequency_trial'] = product_billing_set_modal_fields['frequency_trial'] != undefined ? product_billing_set_modal_fields['frequency_trial'] : 'MONTH'; 
	product_billing_set_modal_fields['frequency_interval_trial'] = product_billing_set_modal_fields['frequency_interval_trial'] != undefined ? product_billing_set_modal_fields['frequency_interval_trial'] : '1'; 
	product_billing_set_modal_fields['cycle_trial'] = product_billing_set_modal_fields['cycle_trial'] != undefined ? product_billing_set_modal_fields['cycle_trial'] : '1'; 
	product_billing_set_modal_fields['title'] = product_billing_set_modal_fields['title'] != undefined ? product_billing_set_modal_fields['title'] : 'REGULAR'; 
	product_billing_set_modal_fields['amount'] = product_billing_set_modal_fields['amount'] != undefined ? product_billing_set_modal_fields['amount'] : '0'; 
	product_billing_set_modal_fields['frequency'] = product_billing_set_modal_fields['frequency'] != undefined ? product_billing_set_modal_fields['frequency'] : 'MONTH'; 
	product_billing_set_modal_fields['frequency_interval'] = product_billing_set_modal_fields['frequency_interval'] != undefined ? product_billing_set_modal_fields['frequency_interval'] : '1'; 
	product_billing_set_modal_fields['cycle'] = product_billing_set_modal_fields['cycle'] != undefined ? product_billing_set_modal_fields['cycle'] : '0'; 
	product_billing_set_modal_fields['agreement_name'] = product_billing_set_modal_fields['agreement_name'] != undefined ? product_billing_set_modal_fields['agreement_name'] : ''; 
	product_billing_set_modal_fields['agreement_description'] = product_billing_set_modal_fields['agreement_description'] != undefined ? product_billing_set_modal_fields['agreement_description'] : ''; 

	$("#paypal_subscription_plan_name").val( product_billing_set_modal_fields['plan_name'] );
	$("#paypal_subscription_plan_description").val( product_billing_set_modal_fields['plan_description'] );
	$("#paypal_subscription_url_confirmed").val( product_billing_set_modal_fields['plan_url_return'] );
	$("#paypal_subscription_url_cancelled").val( product_billing_set_modal_fields['plan_url_cancel'] );
	$("#paypal_subscription_plan_type").val( product_billing_set_modal_fields['plan_type'] ).trigger('change').trigger('chosen:updated');
	$("#paypal_subscription_plan_auto_billing").val( product_billing_set_modal_fields['plan_auto_billing'] ).trigger('chosen:updated');
	$("#paypal_subscription_plan_initial_fail_action").val( product_billing_set_modal_fields['plan_initial_fail_action'] ).trigger('chosen:updated');
	$("#paypal_subscription_plan_max_fail_attempts").val( product_billing_set_modal_fields['plan_max_fail_attempts'] );
	$("#paypal_subscription_title_trial").val( product_billing_set_modal_fields['title_trial'] );
	$("#paypal_subscription_amount_trial").val( product_billing_set_modal_fields['amount_trial'] );
	$("#paypal_subscription_frequency_trial").val( product_billing_set_modal_fields['frequency_trial'] ).trigger('change').trigger('chosen:updated');
	$("#paypal_subscription_frequency_interval_trial").val( product_billing_set_modal_fields['frequency_interval_trial'] );
	$("#paypal_subscription_cycle_trial").val( product_billing_set_modal_fields['cycle_trial'] );
	$("#paypal_subscription_title").val( product_billing_set_modal_fields['title'] );
	$("#paypal_subscription_amount").val( product_billing_set_modal_fields['amount'] );
	$("#paypal_subscription_frequency").val( product_billing_set_modal_fields['frequency'] ).trigger('change').trigger('chosen:updated');
	$("#paypal_subscription_frequency_interval").val( product_billing_set_modal_fields['frequency_interval'] );
	$("#paypal_subscription_cycle").val( product_billing_set_modal_fields['cycle'] );
	$("#paypal_subscription_agreement_name").val( product_billing_set_modal_fields['agreement_name'] );
	$("#paypal_subscription_agreement_description").val( product_billing_set_modal_fields['agreement_desc'] );

	$("#paypal_subscription_enable_trial").prop( 'checked', (product_billing_set_modal_fields['enable_trial'] == 'YES') ).trigger('change');

	$("#modal-product-billing-period").modal('show');
}
function product_billing_get_billing_period(billing_period_id){
	$.post(CONFIG.get('URL')+'products/billing_period_processor/', {
		action      : 'get-billing-period', 
		product_id  : $("#product_id").val().length > 0 ? $("#product_id").val() : '', 
		period_id   : billing_period_id != undefined ? billing_period_id : '', 
	}, function(response, status){
		if (cms_function.isJSON(response)) {
			var r = JSON.parse(response);
			product_billing_set_modal_fields(r.product_billing_period);
		}else{
			notification("Billing Period", "Unknown Response", "gritter-error");
		}
	}).fail(function(e) {
		notification("Payment Gateway", e.message, "gritter-error");
	});
}
function product_billing_get_settings(){
	$.post(CONFIG.get('URL')+'products/billing_period_processor/', {
		action      : 'get-billing-period-setting', 
		product_id  : product_billing_vars.current_product, 
	}, function(response, status){
		if (cms_function.isJSON(response)) {
			var r = JSON.parse(response);

			$("#product-billing-period-toggle").prop('checked', (r.enable == 'YES'));
			$("#product-billing-period-type").val(r.type).trigger('chosen:updated').trigger('change');
			$("#product-type").val(r.product_type).trigger('chosen:updated').trigger('change');
			product_billing_vars.current_product_files = typeof r.product_files == "array" || typeof r.product_files == "object" ? r.product_files : [];
			$("#table-product-downloadable-files").dataTable().fnDraw();
		}else{
			notification("Billing Period", "Unknown Billing Period Response", "gritter-error");
		}
	}).fail(function(e) {
		notification("Billing Period", e.message, "gritter-error");
	});
}
function product_billing_get_global_subscription( subscription_id ){
	$("#modal-product-billing-period-global-subscription-detail").modal('show');

	$.post(CONFIG.get('URL')+'products/billing_period_processor/', {
		action 	: 'get-global-subscription', 
		subs_id : subscription_id, 
	}, function(response, status){
		if (cms_function.isJSON(response)) {
			var r = JSON.parse(response);

			$("#container-global-susbcription-detail").html('');
			
			$.each(r.response, function(k, v){
				var plan = '';

				plan += generate_horizontal_field("Name", v.plan.name, true);
				plan += generate_horizontal_field("Description", v.plan.description, true);
				plan += generate_horizontal_field("Type", v.plan.type, true, true);
				plan += generate_horizontal_field("State", v.plan.state, true, true);
				plan += "<br /><br />";
				plan += generate_horizontal_field("", "Payment Definition", true, true);
				$.each(v.plan.payment_definitions, function(kk, vv){
					plan += generate_horizontal_field("Name", vv.name, true);
					plan += generate_horizontal_field("Type", vv.type, true, true);
					plan += generate_horizontal_field("Frequency", vv.frequency, true, true);
					plan += generate_horizontal_field("Interval", vv.frequency_interval, true, true);
					plan += generate_horizontal_field("Amount", vv.amount.value + " " + vv.amount.currency, true, true);
					plan += generate_horizontal_field("Cycle", vv.cycles, true, true);
					plan += "<br />";
				});

				$("#container-global-susbcription-detail").html( plan );
			});
		}else{
			notification("Billing Period", "Unknown Response", "gritter-error");
		}
	}).fail(function(e) {
		notification("Billing Period", e.message, "gritter-error");
		$("#modal-product-billing-period-global-subscription-detail").modal('hide');
	});
}
function generate_horizontal_field(label, input, html, highlight){
	var output = $('<div class="control-group"></div>');

	label = (label != '') ? label + ": " : '';
	input = highlight ? '<strong>'+ input +'</strong>' : input;

	output.append('<label class="control-label"><small>'+ label +'</small></label>')
	output.append($('<div class="controls input-label"></div>').html($('<label>'+ input +'</label>')));

	if (html) {
		return output.wrap('<p/>').parent().html();
	}else{
		return output;
	}
}

function save_selected_billing_period_settings(){
	var data = {
		'product_id' 								: product_billing_vars.current_product,
		'current_item' 							: product_billing_vars.current_item,

		'plan_name' 								: $("#paypal_subscription_plan_name").val(),
		'plan_description' 					: $("#paypal_subscription_plan_description").val(),
		'url_return' 								: $("#paypal_subscription_url_confirmed").val(),
		'url_cancel' 								: $("#paypal_subscription_url_cancelled").val(),
		'type' 											: $("#paypal_subscription_plan_type").val(),
		'auto_billing' 							: $("#paypal_subscription_plan_auto_billing").val(),
		'initial_fail_action' 			: $("#paypal_subscription_plan_initial_fail_action").val(),
		'max_fail_attempts' 				: $("#paypal_subscription_plan_max_fail_attempts").val(),
		'enable_trial' 							: $("#paypal_subscription_enable_trial").is(":checked") ? 'YES' : 'NO',
		'title_trial' 							: $("#paypal_subscription_title_trial").val(),
		'amount_trial' 							: $("#paypal_subscription_amount_trial").val(),
		'frequency_trial' 					: $("#paypal_subscription_frequency_trial").val(),
		'frequency_interval_trial' 	: $("#paypal_subscription_frequency_interval_trial").val(),
		'cycle_trial' 							: $("#paypal_subscription_cycle_trial").val(),
		'title' 										: $("#paypal_subscription_title").val(),
		'amount' 										: $("#paypal_subscription_amount").val(),
		'frequency' 								: $("#paypal_subscription_frequency").val(),
		'frequency_interval' 				: $("#paypal_subscription_frequency_interval").val(),
		'cycle' 										: $("#paypal_subscription_cycle").val(),
		'agreement_name' 						: $("#paypal_subscription_agreement_name").val(),
		'agreement_description' 		: $("#paypal_subscription_agreement_description").val(),
	};

	$.post(CONFIG.get('URL')+'products/billing_period_processor/', {
		action 	: 'save-billing-period-item', 
		data 		: data,
	}, function(response, status){
		if (cms_function.isJSON(response)) {
			var r = JSON.parse(response);
			if (r.status) {
				notification("Billing Period", r.message, "gritter-success");
				$("#table-billing-period").dataTable().fnDraw();
			}else{
				notification("Billing Period", r.message, "gritter-error");
			}
		}else{
			notification("Billing Period", "Unknown Response", "gritter-error");
		}

		$("#modal-product-billing-period").modal('hide');
	}).fail(function(e) {
		notification("Payment Gateway", e.message, "gritter-error");
	});
}

function remove_downloadable_files(selected_file_id){
	var selected_file_id = selected_file_id != undefined ? selected_file_id : 0;

	for (var i = 0; i < product_billing_vars.current_product_files.length; i++) {
		if (product_billing_vars.current_product_files[i] == selected_file_id) {
			product_billing_vars.current_product_files.splice(i,1);
			break;
		}
	}
	$("#table-product-downloadable-files").dataTable().fnDraw();
	return;

	$.post(CONFIG.get('URL')+'products/billing_period_processor/', {
		action 	: 'remove-billing-period-file', 
		file 		: selected_file_id,
		product : product_billing_vars.current_product,
	}, function(response, status){
		if (cms_function.isJSON(response)) {
			var r = JSON.parse(response);
			if (r.status) {
				$("#table-product-downloadable-files").dataTable().fnDraw();
			}else{
				notification("Billing Period", r.message, "gritter-error");
			}
		}else{
			notification("Billing Period", "Unknown Response", "gritter-error");
		}
	}).fail(function(e) {
		notification("Payment Gateway", e.message, "gritter-error");
	});
}

function check_subscription_fields(){
	var warning_list	= [];
	var c 						= cms_function;
	var billing_type	= $("#product-billing-period-type").val();
	var product_type	= $("#product-type").val();

	if (billing_type == 'Subscription') {
		if ($("#table-billing-period tbody").find("tr").length <= 0) {
			warning_list.push({
				priority 	: 1,
				message 	: 'Empty Product Susbcription Plan Item',
			});
		}else{
			var enabled_product_subscription = 0;
			$.each($("#table-billing-period tbody").find("tr"), function(k, v){
				if ($(this).find(".billing-period-enable").is(":checked")) {
					enabled_product_subscription++;
				}
			});

			if (!enabled_product_subscription) {
				warning_list.push({
					priority 	: 1,
					message 	: 'No Enabled <strong> Billing Period Subscription</strong>',
				});
			}
		}
	}else if(billing_type == 'Global Subscription'){
		if ($("#table-billing-period-global-subscription tbody").find("tr").length <= 0) {
			warning_list.push({
				priority 	: 1,
				message 	: 'Empty Product Susbcription Plan Item',
			});
		}else{
			var enabled_product_subscription = 0;
			$.each($("#table-billing-period-global-subscription tbody").find("tr"), function(k, v){
				if ($(this).find(".billing-period-global-subs").is(":checked")) {
					enabled_product_subscription++;
				}
			});

			if (!enabled_product_subscription) {
				warning_list.push({
					priority 	: 1,
					message 	: 'No Select <strong>Global Subscription Plan</strong>',
				});
			}
		}
	}

	if (product_type == 'Downloadable') {
		if ($("#table-product-downloadable-files tbody").find("tr .btn-view-file").length <= 0) {
			warning_list.push({
				priority 	: 2,
				message 	: 'No <strong>File</strong> associated to this product.',
			});
		}
	}

	if (warning_list.length > 0) {
		var temp = $('<div></div>');
		$.each(warning_list, function(k, v){
			var t = $('<div class="alert"></div>')
			t.html(v['message']);
			temp.append(t);
		});

		notification("Billing Period", temp.html(), "gritter-error")

		return false;
	}else{
		return true;
	}
}