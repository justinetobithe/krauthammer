$(document).ready(function(){
	var init_modal = { keyboard : false, backdrop : 'static', show : false };
	$("#modal-paypal-subscription-customize-button").modal(init_modal);
	$("#modal-paypal-subscription-doc").modal(init_modal);
	$("#modal-paypal-subscription-detail").modal(init_modal);
	$("#modal-documentation").modal(init_modal);
	$("#modal-paypal-subscription-plan-detail").modal(init_modal).on("hidden", function(){
		$("#subscription-plan-item-id").val("");
	});

	$("#table-subscribers").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "payment-gateways/subscriber_table_processor/",
		"aoColumns": [
			null,
			{"sWidth": 150},
			{"sWidth": 150},
			{"sWidth": 50, "bSortable": false},
		], "fnDrawCallback": function(oSettings) {
			$(this).find("[data-rel=tooltip]").tooltip();
			$.each($(this).find(".btn-refresh"), function(k, v){
				$(this).click(function(){
					var btn = $(this);
					var id 	= btn.data('value');

					$.post(CONFIG.get('URL')+'payment-gateways/gateway_processor/', {
						action 	: "refresh-global-subscriber-status", 
						id 			: id,
					}, function(response, status){
						var r = JSON.parse(response);
					}).fail(function(e) {
				    notification("Global Subscription Status", e.message, "gritter-error");
				  });
				});
			});
		}, "fnServerParams": function(aoData) {
			aoData.push({"name": "subscription", "value": $("#subscription-plan-item-id").val()});
		}
	});
	$("#table-billing-period").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "payment-gateways/billing_period_table_processor/",
		"aoColumns": [
			{"sWidth": 50, "bSortable": false},
			{"sWidth": 50, "bSortable": false},
			null,
			{"sWidth": 300, "bSortable": false},
			{"sWidth": 50, "bSortable": false},
		], "fnDrawCallback": function(oSettings) {
			check_subscription_fields();
		}, "fnServerParams": function(aoData) { /*aoData.push({"name": "variable_name", "value": "variable_value"});*/ }
	});
	$("#table-billing-period").on('change', '.billing-period-default', function(e){
		var current_item = $(this).val();
		save_billing_period_column(current_item);
	});
	$("#table-billing-period").on('change', '.billing-period-enable', function(e){
		var current_item = $(this).val();
		save_billing_period_column(current_item)
	});
	$("#table-billing-period").on('click', '.btn-billing-period-edit', function(e){
		var selected_item_id = $(this).data('value');
		load_billing_period(selected_item_id);
	});
	$("#table-billing-period").on('click', '.btn-billing-period-delete', function(e){
		var selected_item_id = $(this).data('value');
		bootbox.confirm("Do you continue deleting Billing Period?", function(result){
		  if (result) {
		    delete_billing_period(selected_item_id);
		  }
		});
	});

	$("#btn_paypal_subscription_manage").click(function(e){
		$("#modal-paypal-subscription-customize-button").modal('show');
	});

	$("#paypal_subscription_plan_type").change(function(e){
		if ($(this).val() == 'INFINITE') {
			$("#paypal_subscription_cycle").val(0).attr("disabled", 'true').attr('min', 0);
		}else{
			$("#paypal_subscription_cycle").removeAttr("disabled").attr('min', 1).trigger('blur');
		}
	}).trigger('change');
	$("#paypal_subscription_cycle").on('blur', function(e){
		if ($(this).attr('min') != undefined && $(this).val() < $(this).attr('min')) {
			$(this).val($(this).attr('min'));
		}
	});
	$("#paypal_subscription_cycle_trial").on('blur', function(e){
		if ($(this).attr('min') != undefined && $(this).val() < $(this).attr('min')) {
			$(this).val($(this).attr('min'));
		}
	});
	$("#paypal_subscription_frequency_trial").change(function(e){
		if ($(this).val() == 'MONTH') {
			$("#paypal_subscription_frequency_interval_trial").attr("max", 12);
			if ($("#paypal_subscription_frequency_interval_trial").val() > 12) {
				$("#paypal_subscription_frequency_interval_trial").val(1);
			}
		}else{
			$("#paypal_subscription_frequency_interval_trial").removeAttr("max");
		}
	}).trigger('change');

	$("#save_subscription").click(function(){
		$(this).prop('disabled', true); 
		if (check_subscription_fields()) {
			save_subscription();
		}else{
			$(this).prop('disabled', false); 
			notification("Error", "Clear first the error before saving", "gritter-error");
		}
	});
	$("#btn-activate-paypal-subscription-plan-info").click(function(e){
		$("#btn-activate-paypal-subscription-plan-info").prop('disabled', true); 
		$("#btn-refresh-paypal-subscription-plan-info").prop('disabled', true);
		$("#btn-delete-paypal-subscription-plan").prop('disabled', true);

		var operation = 'activate-paypal-billing-plan';
		$.post(CONFIG.get('URL')+'payment-gateways/gateway_processor/', {
			action: operation, 
		}, function(response, status){
			var r = JSON.parse(response);
			notification("Billing Plan", "Billing Plan is now Activated.", "gritter-success");
			$('#btn-activate-paypal-subscription-plan-info').prop('disabled', false);
			$("#button-open-modal-subscription-detail").trigger('click');
			$("#btn-delete-paypal-subscription-plan").prop('disabled', false);
		}).fail(function(e) {
			$('#btn-activate-paypal-subscription-plan-info').prop('disabled', false);
	    notification("Payment Gateway", e.message, "gritter-error");
	  });
	});
	$("#btn-activate-paypal-subscription-plan").click(function(e){
		$("#btn-activate-paypal-subscription-plan").prop('disabled', true); 
		$("#btn-save-paypal-subscription-plan").prop('disabled', true);
		$("#btn-delete-paypal-subscription-plan").prop('disabled', true);

		$.post(CONFIG.get('URL')+'payment-gateways/gateway_processor/', {
			action: 'activate-paypal-billing-plan', 
			id 		: $("#subscription-plan-item-id").val(), 
		}, function(response, status){
			var r = JSON.parse(response);
			notification("Billing Plan", "Billing Plan is now Activated.", "gritter-success");
			$("#btn-activate-paypal-subscription-plan").prop('disabled', true); 
			$("#btn-save-paypal-subscription-plan").prop('disabled', true);
			$("#btn-delete-paypal-subscription-plan").prop('disabled', false);
			$("#modal-paypal-subscription-plan-detail").modal('hide');

			load_subscription_plans();
		}).fail(function(e) {
	    notification("Payment Gateway", e.message, "gritter-error");
			$("#btn-activate-paypal-subscription-plan").prop('disabled', true); 
			$("#btn-save-paypal-subscription-plan").prop('disabled', true);
			$("#btn-delete-paypal-subscription-plan").prop('disabled', true);
			$("#modal-paypal-subscription-plan-detail").modal('hide');
	  });
	});
	$("#btn-refresh-subscribers").click(function(e){
		refresh_subscribers_table();
	});
	$("#btn-delete-paypal-subscription-plan").click(function(e){
		bootbox.confirm("Do you delete selected item?", function(result){
		  if (result) {
		    /* Start Delete Process */
		    var data = gather_selected_subscription();

		    $("#btn-delete-paypal-subscription-plan").prop('disabled', true);
		    $("#btn-save-paypal-subscription-plan").prop('disabled', true);

		    $.post(CONFIG.get('URL')+'payment-gateways/gateway_processor/',{
					action 	: 'delete-subscription-plan', 
					id 			: data['id'],
				}, function(response,status){
					var result = JSON.parse(response);

					if (result.status !== undefined && result.status) {
					  notification("Payment Gateway", result.message, "gritter-success");
					}else{
						notification("Payment Gateway", "Invalid Action", "gritter-success");
					}

					load_subscription_plans();

					$("#modal-paypal-subscription-plan-detail").modal('hide');

			    $("#btn-delete-paypal-subscription-plan").prop('disabled', false);
			    $("#btn-save-paypal-subscription-plan").prop('disabled', false);
				});
		  }
		});
	});

	$("select").trigger('chosen:updated');

	$("#row-item-container").on('click', ".btn-load-billing-plan", function(){
		var plan_id = $(this).data('id');
		$("#subscription-plan-item-id").val(plan_id);
		$("#modal-paypal-subscription-plan-detail").modal('show');

		load_subscription_plan(plan_id);
	});
	$("#btn-add-subscription-plan").click(function(e){
		$("#subscription-plan-item-id").val("");
		$("#modal-paypal-subscription-plan-detail").modal('show');

		clear_subscription_plan_form();
	});

	$("#btn-save-paypal-subscription-plan").click(function(e){
		save_subscription_plan()
	});

	/* Billing Period */
	$("#modal-product-default-billing-period").modal(init_modal);
	$("#btn-add-billimg-subscription-plan").click(function(e){
		$("#modal-product-default-billing-period").modal();

		load_billing_period();

		$('[href=#tab-billing-period-1]').trigger('click');
	});
	$("#billing_period_enable").change(function(e){
		if ($(this).is(":checked")) {
			$("#container-billing-period-trial").show();
		}else{
			$("#container-billing-period-trial").hide();
		}
	});	
	$("#btn-save-billing-period").click(function(e){
		save_billing_period();
	});

	$("#btn-read-documentation").click(function(e){
		$("#modal-documentation").modal('show');
	});

	$("#subscription-plan-show-deleted").change(function(e){
		load_subscription_plans();
	});

	load_subscription_plans();
});

function save_subscription(){
	/* Function:  save_subscription*/

	var payment_method 	= [];
	var product_defaults = {
		confirmed 					: $("#product_subscription_default_confirmed").val(),
		cancelled 					: $("#product_subscription_default_cancelled").val(),
		type 								: $("#product_subscription_default_type").val(),
		auto_billing 				: $("#product_subscription_default_auto_billing").val(),
		initial_fail_action : $("#product_subscription_default_initial_fail_action").val(),
		max_fail_attempts 	: $("#product_subscription_default_max_fail_attempts").val(),
		agreement_name 			: $("#product_subscription_default_agreement_name").val(),
		agreement_desc 			: $("#product_subscription_default_agreement_description").val(),
  }

	$(".payment-method").each(function(){
	  var method_detail = {
	  	"id" 						: $(this).find(".method_id").val(),
	  	"tax" 					: $(this).find('.method_tax').val(),
	  	"enabled" 			: $(this).find('.method_gateway_enabled:checked').is(":checked") ? "Y": "N",
	  	"display_name"	: $(this).find(".method_display_name").val(),
	  	"gateway_type"	: $(this).find(".method_gateway_type").val(),
	  }

	  var method_options = {};
	  $(this).find(".method_option").each(function(){
	  	if (typeof $(this).attr('data-name') == 'undefined') { return; }

	  	var v = "";
	  	var n = $(this).attr('data-name');

	  	if ( $(this).attr('type') == 'checkbox' ) {
	  		v = $(this).is(":checked") ? 'Y' : 'N';
	  	}else if( $(this).attr('type') == 'radio' ){
	  		var r_name = $(this).attr('name');
	  		v = $("input[name='"+ r_name +"']:checked").length > 0 ? $("input[name='"+ r_name +"']:checked").val() : 'N';
	  	}else{
	  		v = $(this).val();
	  	}

	  	method_options[n] = v;
	  });

	  payment_method.push({
	  	'detail' 	: method_detail, 
	  	'options' : method_options, 
	  });
	});

	$('#result').empty();

	$.post(CONFIG.get('URL')+'payment-gateways/save_gateways/',{
		action					: 'save_gateways', 
		payment_method	: payment_method,
	  product 				: product_defaults, 
	}, function(response,status){
		var result = JSON.parse(response);

		if(result.length != 0){
			for(var i=0; result.length <= 0; i++ ){
				notification("Payment Gateway", result[i], "gritter-error");
			}
		}else
		  notification("Payment Gateway", "Successfully Saved Subscription Settings.", "gritter-success");

		$('#save_subscription').prop('disabled', false);
	});
}
function refresh_subscribers_table(){
	/* Function:  refresh_subscribers_table*/
	$("#table-subscribers").dataTable().fnDraw();
}
function load_subscription_plans(){
	/* Function: load_subscription_plans */
	$("#row-item-container").html('<p class="text-center"><strong>Loading Subscription Plans....</strong></p>');
	$.post(CONFIG.get('URL')+'payment-gateways/gateway_processor/',{
		action : 'load-subscription-plans', 
		show_deleted : $("#subscription-plan-show-deleted").is(":checked") ? 'Y' : 'N' ,
	}, function(response,status){
		var result = JSON.parse(response);

		$("#row-item-container").html("");

		$.each(result, function(k,v){
			var name = v.meta.plan.name != undefined && v.meta.plan.name != "" ? v.meta.plan.name : "";
			var tran = v.value != undefined && v.value != "" ? v.value : "[ Not Set ]";
			var id 	 = v.id != undefined ? v.id : "";

			var plan = $('#tmpl-subscription-plan-item').tmpl({id: id, transaction_id: tran, title: name}).appendTo('#row-item-container');

			if (v.status == 'active') {
				plan.find('.widget-header').removeClass('header-color-blue').addClass('header-color-green');
				plan.find('.pricing-box').addClass('active-plan');
				plan.find('.btn-load-billing-plan').removeClass('btn-primary btn-danger').addClass('btn-success');
			}else if (v.status == 'deleted') {
				plan.find('.widget-header').removeClass('header-color-blue').addClass('header-color-red');
				plan.find('.btn-load-billing-plan').removeClass('btn-primary btn-success').addClass('btn-danger');
			}
		});

		check_subscription_fields();
	});
}
function clear_subscription_plan_form(){
	/* Function: clear_subscription_plan_form */

	$("#subscription-plan-item-id").val("");
	$("#paypal_subscription_plan_id").val("");

	$("#btn-activate-paypal-subscription-plan").attr('disabled', true);
	$("#btn-delete-paypal-subscription-plan").attr('disabled', true);
	$("#btn-save-paypal-subscription-plan").attr('disabled', false);
	$(".subscription_field").attr('disabled', false);

	$("[data-name=paypal_subscription_plan_name]").val( "Subscription name" );
	$("[data-name=paypal_subscription_plan_description]").val( "Subscription description" );
	$("[data-name=paypal_subscription_plan_type]").val( "INFINITE" ).trigger('change');
	$("[data-name=paypal_subscription_url_confirmed]").val( "http://pvs-cms.com/return-1/" );
	$("[data-name=paypal_subscription_url_cancelled]").val( "http://pvs-cms.com/cancel-1/" );
	$("[data-name=paypal_subscription_plan_auto_billing]").val( "YES" ).trigger('chosen:updated');
	$("[data-name=paypal_subscription_plan_initial_fail_action]").val( "CONTINUE" ).trigger('chosen:updated');
	$("[data-name=paypal_subscription_plan_max_fail_attempts]").val( '1' );
	$("[data-name=paypal_subscription_cycle_trial]").val( '1' );
	$("[data-name=paypal_subscription_title_trial]").val( "Free Trial" );
	$("[data-name=paypal_subscription_amount_trial]").val( '0' );
	$("[data-name=paypal_subscription_frequency_trial]").val( 'MONTH' ).trigger('chosen:updated');
	$("[data-name=paypal_subscription_frequency_interval_trial]").val( '1' );
	$("[data-name=paypal_subscription_cycle]").val( '0' );
	$("[data-name=paypal_subscription_title]").val( "Monthly Subscription" );
	$("[data-name=paypal_subscription_amount]").val( '1' );
	$("[data-name=paypal_subscription_frequency]").val( "MONTH" ).trigger('chosen:updated');;
	$("[data-name=paypal_subscription_frequency_interval]").val( '1' );
	$("[data-name=paypal_subscription_agreement_name]").val( "Subscription Agreement Title" );
	$("[data-name=paypal_subscription_agreement_description]").val( "Subscription Agreement Description" );
}
function load_subscription_plan(item_id){
	/* Function: load_subscription_plan */

	$("#subscription-plan-options").hide();
	$("#subscription-plan-options-loading").show();

	$.post(CONFIG.get('URL')+'payment-gateways/gateway_processor/',{
		action : 'load-subscription-plan', 
		id 		 : item_id, 
	}, function(response,status){
		var result = JSON.parse(response);
		var plan = result.meta.plan;
		var agreement = result.meta.agreement != undefined ? result.meta.agreement : {
			name : '',
			description : '',
		};

		$("#subscription-plan-item-id").val(result.id);

		$("#paypal_subscription_plan_id").val( plan.id );
		$("[data-name=paypal_subscription_plan_name]").val( plan.name );
		$("[data-name=paypal_subscription_plan_type]").val( plan.type );

		$("[data-name=paypal_subscription_plan_description]").val( plan.description );
		$("[data-name=paypal_subscription_url_confirmed]").val( plan.merchant_preferences.return_url );
		$("[data-name=paypal_subscription_url_cancelled]").val( plan.merchant_preferences.cancel_url );
		$("[data-name=paypal_subscription_plan_auto_billing]").val( plan.merchant_preferences.auto_bill_amount );
		$("[data-name=paypal_subscription_plan_initial_fail_action]").val( plan.merchant_preferences.initial_fail_amount_action ).trigger('chosen:updated');
		$("[data-name=paypal_subscription_plan_max_fail_attempts]").val( plan.merchant_preferences.max_fail_attempts );

		$.each(plan.payment_definitions, function(k, v){
			if (v.type == "TRIAL") {
				$("[data-name=paypal_subscription_cycle_trial]").val( v.cycles );
				$("[data-name=paypal_subscription_title_trial]").val( v.name );
				$("[data-name=paypal_subscription_amount_trial]").val( v.amount.value );
				$("[data-name=paypal_subscription_frequency_trial]").val( v.frequency.toUpperCase() ).trigger('chosen:updated');
				$("[data-name=paypal_subscription_frequency_interval_trial]").val( v.frequency_interval );
			}else{
				$("[data-name=paypal_subscription_cycle]").val( v.cycles );
				$("[data-name=paypal_subscription_title]").val( v.name );
				$("[data-name=paypal_subscription_amount]").val( v.amount.value );
				$("[data-name=paypal_subscription_frequency]").val( v.frequency.toUpperCase() ).trigger('chosen:updated');;
				$("[data-name=paypal_subscription_frequency_interval]").val( v.frequency_interval );
			}
		});

		if (result.status == 'active') {
			$("#btn-activate-paypal-subscription-plan").attr('disabled', true);
			$(".subscription_field").attr('disabled', true);
			$("select.subscription_field").trigger('chosen:updated');
		}else if (result.status == 'deleted') {
			$("#btn-activate-paypal-subscription-plan").attr('disabled', true);
			$(".subscription_field").attr('disabled', true);
			$("select.subscription_field").trigger('chosen:updated');
		}else{
			$("#btn-activate-paypal-subscription-plan").attr('disabled', false);
			$(".subscription_field").attr('disabled', false);
			$("select.subscription_field").trigger('chosen:updated');
		}

		if (result.status == 'deleted') {
			$("#btn-save-paypal-subscription-plan").attr('disabled', true);
			$("#btn-delete-paypal-subscription-plan").attr('disabled', true);
			$("[data-name=paypal_subscription_agreement_name]").val( agreement.name ).attr('disabled', true);
			$("[data-name=paypal_subscription_agreement_description]").val( agreement.description ).attr('disabled', true);
		}else{
			$("#btn-save-paypal-subscription-plan").attr('disabled', false);
			$("#btn-delete-paypal-subscription-plan").attr('disabled', false);
			$("[data-name=paypal_subscription_agreement_name]").val( agreement.name ).attr('disabled', false);
			$("[data-name=paypal_subscription_agreement_description]").val( agreement.description ).attr('disabled', false);
		}

		$("#subscription-plan-options").show();
		$("#subscription-plan-options-loading").hide();

		$("#table-subscribers").dataTable().fnDraw();
	}).fail(function(e){
		notification("Subscription Plan Error", "Something went wrong while retrieving subscription plan detail", "gritter-error");

		$("#subscription-plan-options").show();
		$("#subscription-plan-options-loading").hide();
	});
}
function save_subscription_plan(){
	/* Function: save_subscription_plan */

	$("#btn-activate-paypal-subscription-plan").prop('disabled', true);
	$("#btn-save-paypal-subscription-plan").prop('disabled', true);
	$("#btn-delete-paypal-subscription-plan").prop('disabled', true);

	var item_id = $("#subscription-plan-item-id").val();
  var method_options = {};
  $("#subscription-plan-options").find(".method_option").each(function(){
  	if (typeof $(this).attr('data-name') == 'undefined') { return; }

  	var v = "";
  	var n = $(this).attr('data-name');

  	if ( $(this).attr('type') == 'checkbox' ) {
  		v = $(this).is(":checked") ? 'Y' : 'N';
  	}else if( $(this).attr('type') == 'radio' ){
  		var r_name = $(this).attr('name');
  		v = $("input[name='"+ r_name +"']:checked").length > 0 ? $("input[name='"+ r_name +"']:checked").val() : 'N';
  	}else{
  		v = $(this).val();
  	}

  	method_options[n] = v;
  });

	$.post(CONFIG.get('URL')+'payment-gateways/save_gateways',{
		action 	: 'save_subscription_plan', 
		id			: item_id, 
		options : method_options
	}, function(response,status){
		notification("Subscription Plan", response, "gritter-success");

		$('#save_subscription').prop('disabled', false);
		load_subscription_plans();

		$("#modal-paypal-subscription-plan-detail").modal('hide');

		$("#btn-activate-paypal-subscription-plan").prop('disabled', false);
		$("#btn-save-paypal-subscription-plan").prop('disabled', false);
		$("#btn-delete-paypal-subscription-plan").prop('disabled', true);
	}).fail(function(e){
		notification("Payment Gateway", e.responseText, "gritter-error");

		$("#btn-activate-paypal-subscription-plan").prop('disabled', false);
		$("#btn-save-paypal-subscription-plan").prop('disabled', false);
		$("#btn-delete-paypal-subscription-plan").prop('disabled', false);
	});
}
function gather_selected_subscription(){
	/* Function: gather_selected_subscription */

	var fields = {
		'id' 												: $("#subscription-plan-item-id").val(),		
		'plan_name' 								: $("[data-name=paypal_subscription_plan_name]").val(),
		'plan_type' 								: $("[data-name=paypal_subscription_plan_type]").val(),
		'plan_description' 					: $("[data-name=paypal_subscription_plan_description]").val(),
		'url_confirmed' 						: $("[data-name=paypal_subscription_url_confirmed]").val(),
		'url_cancelled' 						: $("[data-name=paypal_subscription_url_cancelled]").val(),
		'plan_auto_billing' 				: $("[data-name=paypal_subscription_plan_auto_billing]").val(),
		'plan_initial_fail_action' 	: $("[data-name=paypal_subscription_plan_initial_fail_action]").val(),
		'plan_max_fail_attempts' 		: $("[data-name=paypal_subscription_plan_max_fail_attempts]").val(),
		'cycle_trial' 							: $("[data-name=paypal_subscription_cycle_trial]").val(),
		'title_trial' 							: $("[data-name=paypal_subscription_title_trial]").val(),
		'amount_trial' 							: $("[data-name=paypal_subscription_amount_trial]").val(),
		'frequency_trial' 					: $("[data-name=paypal_subscription_frequency_trial]").val(),
		'frequency_interval_trial' 	: $("[data-name=paypal_subscription_frequency_interval_trial]").val(),
		'cycle' 										: $("[data-name=paypal_subscription_cycle]").val(),
		'title' 										: $("[data-name=paypal_subscription_title]").val(),
		'amount' 										: $("[data-name=paypal_subscription_amount]").val(),
		'frequency' 								: $("[data-name=paypal_subscription_frequency]").val(),
		'frequency_interval' 				: $("[data-name=paypal_subscription_frequency_interval]").val(),
		'agreement_name' 						: $("[data-name=paypal_subscription_agreement_name]").val(),
		'agreement_description' 		: $("[data-name=paypal_subscription_agreement_description]").val(),
	};

	return fields;
}

function save_billing_period(){
	/* Function: save_billing_period */

	$("#btn-save-billing-period").prop('disabled', true);
	if ($("#billing_period_name").val() == "") {
		notification("Billing Period", "Empty Billing Period", "gritter-error"); return;
		$("#btn-save-billing-period").prop('disabled', false);
	}

	var billing_period_values = {
		current_item_id						: $("#default-billing-period-item-id").val(),
		billing_period_title 			: $("#billing_period_name").val(),
		title 										: $(".billing_period_field[data-name=billing_period_title]").val(),
		frequency 								: $(".billing_period_field[data-name=billing_period_frequency]").val(),
		frequency_interval 				: $(".billing_period_field[data-name=billing_period_frequency_interval]").val(),
		cycle 										: $(".billing_period_field[data-name=billing_period_cycle]").val(),
		enable_trial 							: $("#billing_period_enable").is(":checked") ? 'YES' : 'NO',
		title_trial 							: $(".billing_period_field[data-name=billing_period_title_trial]").val(),
		amount_trial 							: $(".billing_period_field[data-name=billing_period_amount_trial]").val(),
		frequency_trial 					: $(".billing_period_field[data-name=billing_period_frequency_trial]").val(),
		frequency_interval_trial 	: $(".billing_period_field[data-name=billing_period_frequency_interval_trial]").val(),
		cycle_trial 							: $(".billing_period_field[data-name=billing_period_cycle_trial]").val(),
	}

	$.post(CONFIG.get('URL')+'payment-gateways/save_gateways/',{
		action 	: 'save_billing_period', 
		data 		: billing_period_values, 
	}, function(response,status){
		var result = JSON.parse(response);

		if(result.status){
		  notification("Default Billing Period Item", "Successfully Saved Billing Period Item.", "gritter-success");
		  $('#modal-product-default-billing-period').modal('hide');
		}else{
		  notification("Default Billing Period Item", "Unable to Saved Billing Period Item.", "gritter-success");
		}

		$("#table-billing-period").dataTable().fnDraw();

		$("#btn-save-billing-period").prop('disabled', false);
	});
}
function save_billing_period_column(current_item){
	/* Function: save_billing_period_column */

	var col_data = {
		id 			: current_item,
		default : $("#billing-period-col-default-" + current_item).length > 0 && $("#billing-period-col-default-" + current_item).is(":checked") ? "YES" : "NO",
		enable 	: $("#billing-period-col-enable-" + current_item).length > 0 && $("#billing-period-col-enable-" + current_item).is(":checked") ? "YES" : "NO",
	};

	$.post(CONFIG.get('URL')+'payment-gateways/save_gateways/',{
		action 	: 'save_billing_period_column', 
		data 		: col_data, 
	}, function(response,status){
		var result = JSON.parse(response);

		if(result.status){
		  notification("Default Billing Period Item", "Successfully Saved Billing Period Item.", "gritter-success");
		  $('#modal-product-default-billing-period').modal('hide');
		}else{
		  notification("Default Billing Period Item", "Unable to Saved Billing Period Item.", "gritter-success");
		}

		$("#table-billing-period").dataTable().fnDraw();
	}).fail(function(e){
		notification("Billing Period", "Encountered problem while retrieving data", "gritter-error");
	});
}
function load_billing_period(selected_item_id){
	/* Function: load_billing_period */

	$("#modal-product-default-billing-period").modal('show');

	var data = {
		item_id 									: "",
		billing_period_name 			: "",
		enable_trial 							: 'NO',
		title_trial 							: 'Trial',
		amount_trial 							: '0',
		frequency_trial 					: 'MONTH',
		frequency_interval_trial 	: '1',
		cycle_trial 							: '1',
		title 										: 'Regular',
		amount 										: 'will',
		frequency 								: 'MONTH',
		frequency_interval 				: '1',
		cycle 										: '0',
	};

	if (selected_item_id != undefined) {
		$.post(CONFIG.get('URL')+'payment-gateways/gateway_processor/',{
			action : 'load-billing-period', 
			id : selected_item_id, 
		}, function(response,status){
			var result = JSON.parse(response);

			if (result) {
				var data = {
					item_id 									: result.id,
					billing_period_name 			: result.value,
					enable_trial 							: result.meta.enable_trial,
					title_trial 							: result.meta.title_trial,
					amount_trial 							: result.meta.amount_trial,
					frequency_trial 					: result.meta.frequency_trial,
					frequency_interval_trial 	: result.meta.frequency_interval_trial,
					cycle_trial 							: result.meta.cycle_trial,
					title 										: result.meta.title,
					frequency 								: result.meta.frequency,
					frequency_interval 				: result.meta.frequency_interval,
					cycle 										: result.meta.cycle,
				};

				set_billing_period_field(data);
			}else{
				notification("Billing Period", "Entered a problem while retrieving data", "gritter-error");
			}

			$("#modal-billing-period-loading").hide();
			$("#modal-billing-period-item").show();
		}).fail(function(e){
			notification("Billing Period", "Entered a problem while retrieving data", "gritter-error");
		});
	}else{
		set_billing_period_field(data);
	}
}
function set_billing_period_field(data){
	/* Function: set_billing_period_field */

	$("#modal-product-default-billing-period").modal('show');

	/* clear current selected billing period item id */
	$("#default-billing-period-item-id").val( data['item_id'] );
	$("#billing_period_name").val( data['billing_period_name'] );

	/* select default values for billing period*/
	$(".billing_period_field[data-name=billing_period_title_trial]").val( data['title_trial'] );
	$(".billing_period_field[data-name=billing_period_amount_trial]").val( data['amount_trial'] );
	$(".billing_period_field[data-name=billing_period_frequency_trial]").val( data['frequency_trial'] ).trigger('chosen:updated');
	$(".billing_period_field[data-name=billing_period_frequency_interval_trial]").val( data['frequency_interval_trial'] );
	$(".billing_period_field[data-name=billing_period_cycle_trial]").val( data['cycle_trial'] ); //set to one month

	$(".billing_period_field[data-name=billing_period_title]").val( data['title'] );
	$(".billing_period_field[data-name=billing_period_frequency]").val( data['frequency'] ).trigger('chosen:updated');
	$(".billing_period_field[data-name=billing_period_frequency_interval]").val( data['frequency_interval'] );
	$(".billing_period_field[data-name=billing_period_cycle]").val( data['cycle'] ); //set to infinite

	$("#billing_period_enable").prop('checked', (data['enable_trial'] == 'YES')).trigger('change');

	$('[href=#tab-billing-period-1]').trigger('click');
}
function delete_billing_period(current_item){
	/* Function: delete_billing_period */
	
	if (current_item == undefined) {
		notification("Billing Period", "Missing Parameter", "gritter-error");
		return false;
	}
	var col_data = {
		id : current_item,
	};

	$.post(CONFIG.get('URL')+'payment-gateways/save_gateways/',{
		action 	: 'delete_billing_period_column', 
		data 		: col_data, 
	}, function(response,status){
		var result = JSON.parse(response);

		if(result.status){
		  notification("Default Billing Period Item", "Successfully Saved Billing Period Item.", "gritter-success");
		  $('#modal-product-default-billing-period').modal('hide');
		}else{
		  notification("Default Billing Period Item", "Unable to Saved Billing Period Item.", "gritter-success");
		}

		$("#table-billing-period").dataTable().fnDraw();
	}).fail(function(e){
		notification("Billing Period", "Encountered problem while retrieving data", "gritter-error");
	});
}

function check_subscription_fields(){
	/* Function: check_subscription_fields */

	var warning_container = $("#subscription-error-warning");
	var warning_list 			= [];
	var c = cms_function;
	var on= $("#paypal_subscription_enable").is(":checked");

	// General Settings  

	if (on) {
		if ( !c.form.validate( $("#paypal_subscription_display_name") ) ) {
			warning_list.push({
				priority 	: 3,
				message 	: '<strong>Display name</strong> is empty',
			});
		}
		if ( !c.form.validate( $(".method_option[data-name=paypal_subscription_client_id]") ) ) {
			warning_list.push({
				priority 	: 1,
				message 	: 'Paypal <strong>Client ID</strong> is required',
			});
		}
		if ( !c.form.validate( $(".method_option[data-name=paypal_subscription_secret]") ) ) {
			warning_list.push({
				priority 	: 1,
				message 	: 'Paypal <strong>Secret</strong> is required',
			});
		}
	}


	// Subscription Plan Items

	/*
	-If subscription is enable, add warning if no subscription added 
	*/
	if (on) {
		if ($("#row-item-container").find(".pricing-box.active-plan").length <= 0) {
			warning_list.push({
				priority 	: 1,
				message 	: 'No <strong>Active</strong> Subscription Plan',
			});
		}
	}


	// Products Default Subscription Settings 

	/*
	-should not have empty values
	*/
	if ( !c.form.validate( $("#product_subscription_default_confirmed") ) ) {
		warning_list.push({
			priority 	: 1,
			message 	: 'Default URL for <strong>[Paypal Confirmed]</strong> transaction is not set',
		});
	}
	if ( !c.form.validate( $("#product_subscription_default_cancelled") ) ) {
		warning_list.push({
			priority 	: 1,
			message 	: 'Default URL for <strong>[Paypal Cancelled]</strong> transaction is not set',
		});
	}
	if ( !c.form.validate( $("#product_subscription_default_type") ) ) {
		warning_list.push({
			priority 	: 1,
			message 	: '<strong>[Default Plan Type]</strong> is not set',
		});
	}
	if ( !c.form.validate( $("#product_subscription_default_auto_billing") ) ) {
		warning_list.push({
			priority 	: 1,
			message 	: '<strong>[Default Auto Billing]</strong> is not set',
		});
	}
	if ( !c.form.validate( $("#product_subscription_default_initial_fail_action") ) ) {
		warning_list.push({
			priority 	: 1,
			message 	: '<strong>[Default Initial Fail Action]</strong> is not set',
		});
	}
	if ( !c.form.validate( $("#product_subscription_default_max_fail_attempts") ) ) {
		warning_list.push({
			priority 	: 1,
			message 	: '<strong>[Default Max Fail Attempts]</strong> is not set',
		});
	}

	/*
	-should not be empty
	-observe character limit
	*/

	if ( !c.form.validate( $("#product_subscription_default_agreement_name") ) ) {
		warning_list.push({
			priority 	: 1,
			message 	: '<strong>Important: [Default Agreement Name]</strong> is not set',
		});
	}
	if ( !c.form.validate( $("#product_subscription_default_agreement_description") ) ) {
		warning_list.push({
			priority 	: 1,
			message 	: '<strong>Important: [Default Agreement Description]</strong> is not set',
		});
	}

	// Products Default Billing Period
	/* 
	-If subscription is enable, add warning if no subscription added 
	*/
	if (on) {
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
					message 	: 'No <strong>Enabled Product Subscription</strong>',
				});
			}
		}
	}

	/*
	-display warnings into the warning container
	*/
	warning_container.html(""); // clearing warning box
	$.each(warning_list, function(k, v){
		var temp = $('<div class="alert"></div>');

		warning_container.append(temp)
		temp.html(v['message']);

		switch (v['priority']) {
			case 1:
				temp.addClass('alert-error');
				break;
			case 2:
				temp.addClass('alert-warning');
				break;
			case 3:
				temp.addClass('alert-info');
				break;
			default:
				temp.addClass('alert-default');
		}
	});

	if (warning_list.length > 0) {
		warning_container.show();
		return false;
	}else{
		warning_container.hide();
		return true;
	}
}