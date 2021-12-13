$(document).ready(function(){
	if($('#action').val() == 'index')
		index();
});
function index(){
	$("#modal-paypal-checkout-customize-button").modal({
		'show' : false,
	});
	$("#modal-paypal-subscription-customize-button").modal({
		'show' : false,
	});
	$("#modal-paypal-checkout-guide").modal({
		'show' : false,
	});
	$("#modal-paypal-subscription-guide").modal({
		'show' : false,
	});

	$(".chosen-select").chosen({ 'width' : '100%' });

	$('#paypal-checkout-button-color').ace_colorpicker();
	$("#paypal_checkout_manage_button").click(function(e){
		var dialog = $("#modal-paypal-checkout-customize-button");
		dialog.modal('show');

		$.post(CONFIG.get('URL')+'payment-gateways/save_gateways',{
			action 	: 'get_checkout_paypal_button', 
		}, function(response,status){
			var data = JSON.parse(response);

			var color = '';

			if (data.pp_chkout_btn_color == 'gold') {
				color = "#ffad46";
			}else if(data.pp_chkout_btn_color == 'blue'){
				color = "#4986e7";
			}else if(data.pp_chkout_btn_color == 'silver'){
				color = "#c2c2c2";
			}else if(data.pp_chkout_btn_color == 'black'){
				color = "#555555";
			}else{
				color = "#ffad46";
			}

			dialog.find(".paypal-button-label").val( data.pp_chkout_btn_label ? data.pp_chkout_btn_label : 'checkout' ).trigger('chosen:updated');
			dialog.find(".paypal-button-size").val( data.pp_chkout_btn_size ? data.pp_chkout_btn_size : 'small' );
			dialog.find(".paypal-button-shape").val( data.pp_chkout_btn_shape ? data.pp_chkout_btn_shape : 'pill' );
			dialog.find(".paypal-button-color").siblings('.dropdown-colorpicker').remove();
			dialog.find(".paypal-button-color").val(color)
			dialog.find(".paypal-button-color").ace_colorpicker();
			dialog.find(".paypal-button-tagline").prop("checked", (data.pp_chkout_btn_tag =='Y' ? true : false));

			reset_paypal_button_preview(dialog);
		});
	});
	$("#paypal_subscription_manage_button").click(function(e){
		var dialog = $("#modal-paypal-subscription-customize-button");
		dialog.modal('show');

		$.post(CONFIG.get('URL')+'payment-gateways/save_gateways',{
			action 	: 'get_subscription_paypal_button', 
		}, function(response,status){
			var data = cms_function.isJSON(response) ? JSON.parse(response) : {};

			var color = '';

			if (data.pp_subscription_btn_color == 'gold') {
				color = "#ffad46";
			}else if(data.pp_subscription_btn_color == 'blue'){
				color = "#4986e7";
			}else if(data.pp_subscription_btn_color == 'silver'){
				color = "#c2c2c2";
			}else if(data.pp_subscription_btn_color == 'black'){
				color = "#555555";
			}else{
				color = "#ffad46";
			}

			dialog.find(".paypal-button-label").val( data.pp_subscription_btn_label ? data.pp_subscription_btn_label : 'checkout' ).trigger('chosen:updated');
			dialog.find(".paypal-button-size").val( data.pp_subscription_btn_size ? data.pp_subscription_btn_size : 'small' );
			dialog.find(".paypal-button-shape").val( data.pp_subscription_btn_shape ? data.pp_subscription_btn_shape : 'pill' );
			dialog.find(".paypal-button-color").siblings('.dropdown-colorpicker').remove();
			dialog.find(".paypal-button-color").val(color)
			dialog.find(".paypal-button-color").ace_colorpicker();
			dialog.find(".paypal-button-tagline").prop("checked", (data.pp_subscription_btn_tag =='Y' ? true : false));

			reset_paypal_button_preview(dialog);
		});
	});

	$("#modal-paypal-checkout-customize-button").find('.paypal-button-label').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-checkout-customize-button") ); })
	$("#modal-paypal-checkout-customize-button").find('.paypal-button-size').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-checkout-customize-button") ); })
	$("#modal-paypal-checkout-customize-button").find('.paypal-button-shape').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-checkout-customize-button") ); })
	$("#modal-paypal-checkout-customize-button").find('.paypal-button-color').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-checkout-customize-button") ); })
	$("#modal-paypal-checkout-customize-button").find('.paypal-button-tagline').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-checkout-customize-button") ); })

	$("#modal-paypal-subscription-customize-button").find('.paypal-button-label').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-subscription-customize-button") ); })
	$("#modal-paypal-subscription-customize-button").find('.paypal-button-size').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-subscription-customize-button") ); })
	$("#modal-paypal-subscription-customize-button").find('.paypal-button-shape').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-subscription-customize-button") ); })
	$("#modal-paypal-subscription-customize-button").find('.paypal-button-color').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-subscription-customize-button") ); })
	$("#modal-paypal-subscription-customize-button").find('.paypal-button-tagline').change(function(e){ reset_paypal_button_preview( $("#modal-paypal-subscription-customize-button") ); })

	$(".toggler").click(function(){
		var target_elem = $("#" + $(this).data('toggle'));
		target_elem.slideToggle();
	});

	$("#paypal_checkout_manual_button").click(function(e){
		$("#modal-paypal-checkout-guide").modal('show');
	});
	$("#paypal_subscription_manual_button").click(function(e){
		$("#modal-paypal-subscription-guide").modal('show');
	});

	$("#paypal-checkout-button-save").click(function(e){
		var dialog = $("#modal-paypal-checkout-customize-button");
		var data = {
			label : dialog.find(".paypal-button-label").val(),
			size 	: dialog.find(".paypal-button-size").val(),
			shape : dialog.find(".paypal-button-shape").val(),
			color : dialog.find(".paypal-button-color option:selected").text(),
			tag 	: dialog.find(".paypal-button-tagline").is(":checked") ? 'Y' : 'N',
		};

		$.post(CONFIG.get('URL')+'payment-gateways/save_gateways',{
			action 	: 'save_checkout_paypal_button', 
			data 		: JSON.stringify(data),
		}, function(response,status){
			if(response == 1){
			  notification("Payment Gateway", "Successfully Saved Gateway Settings.", "gritter-success");

			  dialog.modal('hide');
			}else{
				notification("Payment Gateway", response, "gritter-error");
			}
		});
	});
	$("#paypal-subscription-button-save").click(function(e){
		var dialog = $("#modal-paypal-subscription-customize-button");
		var data = {
			label : dialog.find(".paypal-button-label").val(),
			size 	:  dialog.find(".paypal-button-size").val(),
			shape : dialog.find(".paypal-button-shape").val(),
			color : dialog.find(".paypal-button-color option:selected").text(),
			tag 	: dialog.find(".paypal-button-tagline").is(":checked") ? 'Y' : 'N',
		};

		$.post(CONFIG.get('URL')+'payment-gateways/save_gateways',{
			action 	: 'save_subscription_paypal_button', 
			data 		: JSON.stringify(data),
		}, function(response,status){
			if(response == 1){
			  notification("Payment Gateway", "Successfully Saved Gateway Settings.", "gritter-success");

			  dialog.modal('hide');
			}else{
				notification("Payment Gateway", response, "gritter-error");
			}
		});
	});

	$('#save_settings').click(function(e){
		e.preventDefault();
		$('#save_settings').prop('disabled', true);

		/* Saving Process Part 1 */
		var setting_data = {};
		setting_data['invoice_currency_name'] 	= $('#company_name').val();
		setting_data['invoice_company_address'] = $('#company_address').val();
		setting_data['invoice_number_prefix'] 	= $('#invoice_number_prefix').val();
		setting_data['invoice_next_number'] 		= $('#next_invoice_number').val();
		setting_data['currency_symbol'] 				= $('#currency_symbol').val();
		setting_data['currency_code'] 					= $('#currency_code').val();
		save_settings(setting_data);
		/* Saving Process Part 2 */
		/* moved the save_gateways after save_settings() */
	});
}

function save_settings(data){
	$.post(CONFIG.get('URL')+'payment-gateways/save_currency_settings', {
		action: 'save_settings', 
		data:data 
	}, function(response, status){
	
		$('#result').empty();
		
		var result = JSON.parse(response);
		
		if(result.length != 0){
			for(var i=0; result.length <= 0; i++ ){
				notification("Payment Gateway", result[0], "gritter-error");
			}
		}else{
			notification("Payment Gateway", "Successfully Saved Currency Settings.", "gritter-success");
		}

		save_gateways();
	});		
}

function save_gateways(){
	var data_options = [];
	var data_gateways = [];

	// data_options.push($('#paypal_email').val().trim());
	data_options.push($('#offline_cash').val());
	data_options.push($('#offline_check').val());
	data_options.push($('#offine_transfer').val());
	// data_options.push($('#paypal_currency').val().trim());
	// data_options.push($('#paypal_language').val().trim());
	// data_options.push($('#express_username').val().trim());
	// data_options.push($('#express_password').val().trim());
	// data_options.push($('#express_signature').val().trim());
	// data_options.push($('input[name=recurring_payments]:checked').val());

	// data_gateways.push([$('#paypal_display_name').val(), $('input[name=gateway_enabled_paypal]:checked').val()]);
	// data_gateways.push([$('#display_name_express').val(), $('input[name=gateway_enabled_express]:checked').val()]);
	data_gateways.push([$('#display_name_cash').val(), $('input[name=gateway_enabled_cash]:checked').val()]);
	data_gateways.push([$('#display_name_cheque').val(), $('input[name=gateway_enabled_cheque]:checked').val()]);
	data_gateways.push([$('#display_name_transfer').val(), $('input[name=gateway_enabled_transfer]:checked').val()]);
	data_gateways.push([$('#paypal_checkout_display_name').val(), ( $('#paypal_checkout_enable').is(":checked")?'Y':'N')]);

	var payment_method = [];
	$("#payment-method-container").find(".payment-method").each(function(){
	  var method_detail = {
	  	"id" : $(this).find(".method_id").val(),
	  	"display_name" : $(this).find(".method_display_name").val(),
	  	"gateway_type" : $(this).find(".method_gateway_type").val(),
	  	"tax" : $(this).find('.method_tax').val(),
	  	"enabled" : $(this).find('.method_gateway_enabled:checked').is(":checked") ? "Y": "N",
	  }

	  var method_options = {};
	  $(this).find(".method_option").each(function(){
	  	if (typeof $(this).attr('data-name') == 'undefined') { return; }

	  	var v = "";
	  	var n = $(this).attr('data-name');

	  	if ( $(this).attr('type') == 'checkbox' ) {
	  		if ($(this).is(":checked")) {
	  			v = 'Y';
	  		}else{
	  			v = 'N';
	  		}
	  	}else if( $(this).attr('type') == 'radio' ){
	  		var r_name = $(this).attr('name');
	  		v = $("input[name='"+ r_name +"']:checked").length > 0 ? $("input[name='"+ r_name +"']:checked").val() : 'N';
	  	}else{
	  		v = $(this).val();
	  	}
	  	if (n == 'paypal_subscription_frequency_trial') {
	  		console.log(n + " : " + v);
	  	}
	  	method_options[n] = v;
	  });

	  payment_method.push({
	  	'detail' : method_detail,
	  	'options' : method_options,
	  });
	});

	$('#result').empty();

	$.post(CONFIG.get('URL')+'payment-gateways/save_gateways',{
		action					: 'save_gateways', 
		data_options		:data_options, 
		data_gateways		:data_gateways, 
		payment_method 	: payment_method
	}, function(response,status){
		var result = JSON.parse(response);

		if(result.length != 0){
			for(var i=0; result.length <= 0; i++ ){
				notification("Payment Gateway", result[0], "gritter-error");
			}
		}else
		  notification("Payment Gateway", "Successfully Saved Gateway Settings.", "gritter-success");

		$('#save_settings').prop('disabled', false);
	});
}

function save_settings_invoice(data){
}

function reset_paypal_button_preview(dialog){
	dialog.find('.paypal-container').html("");

	var label = dialog.find(".paypal-button-label").val();
	var size 	= dialog.find(".paypal-button-size").val();
	var shape = dialog.find(".paypal-button-shape").val();
	var color = dialog.find(".paypal-button-color option:selected").text();
	var tag 	= dialog.find(".paypal-button-tagline").is(":checked") ? true : false;

	paypal.Button.render({
    // Set your environment
    env: 'sandbox', // sandbox | production
    // Specify the style of the button
    style: {
  		label 	: label,
      size 		: size, 	// small | medium | large | responsive
      shape 	: shape, 	// pill | rect
      color 	: color, 	// gold | blue | silver | black
      tagline : tag,
     },

    // PayPal Client IDs - replace with your own
    // Create a PayPal app: https://developer.paypal.com/developer/applications/create

    client: {
    	sandbox:    'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
    },

    payment: function(data, actions) {
    	return actions.payment.create({
    		payment: {
    			transactions: [{amount: {total: '0.01', currency:'USD'}}]
    		}
    	});
    },

    onAuthorize: function(data, actions) {
    	return actions.payment.execute().then(function() {
    		window.alert('Test: Payment Complete!');
    	});
    }

  }, "#" + dialog.find('.paypal-container').attr('id'));
}