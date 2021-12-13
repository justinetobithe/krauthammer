var addional_action = 'add';
var additional_order_id = 0;
var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var regexContactNumber = /^[0-9-+]+$/;

$(document).ready(function(){
	var current_selected_rate = null;

	$("#shipping-origin-modal").modal({ backdrop : 'static', show : false, });
	$("#shipping-default-rate-modal").modal({ backdrop : 'static', show : false, });
	$("#shipping-guide-modal").modal({ backdrop : 'static', show : false, });
	$("#shipping-area-rate-modal").modal({ 
		backdrop : 'static', 
		show : false, 
	}).on("shown", function(){

	}).on("hidden", function(){
		$("#shipping_rate_name").val("");
		$("#shipping_rate_type").val("");
		$("#shipping_rate_min").val("");
		$("#shipping_rate_max").val("");
		$("#shipping_rate_amount").val("");
		$("#shipping_rate_amount_hdn").val("");
		current_selected_rate = null;
	});

	$(".chosen_top").chosen({ width : '100%' });

	$('#shipping-origin-form').ajaxForm({
		beforeSend: function(){
			$("#loading").modal({
				backdrop : 'static',
				keyboard: false
			});

			$("#shipping-origin-modal").find(".modal-footer a").hide();
			$("#shipping-origin-modal").find(".modal-footer").append("<p class='modal-loading'>Loading...</p>");
			$("#shipping-origin-form").find('.alert_customer_details').html('');
		},
		complete: function(xhr){
			var result = xhr.responseText;

			if(result == '1')
				$("#shipping-origin-form").find('.alert_customer_details').append(alertMessage('Successfully saved Shipping Origin Detail.','success','shipping-option-save-result'));
			else{
				$("#shipping-origin-form").find('.alert_customer_details').append(alertMessage('Error while saving please try again.','error','error_adding'));
			}

			$("#shipping-origin-modal").find(".modal-footer a").show();
			$("#shipping-origin-modal").find(".modal-footer").find(".modal-loading").remove();

	    get_shipping_origin_info();
	    $("#shipping-origin-modal").modal("hide");
		}
  });

	$("#btn-edit-shipping-origin-address").click(function(){
		$("#shipping-origin-modal").modal("show");
		$("#shipping-origin-form").find('.alert_customer_details').html('');

		jQuery.post(CONFIG.get('URL')+'shipping/ajax_shipping_origin_processor',{operation:'get'}, function(response,status){
	    if(response != 'undefined'){
	      if (typeof response['shipping_origin_name'] != "undefined") { 
	      	$("#shipping_origin_name").val(response['shipping_origin_name']); 
	      }
	      if (typeof response['shipping_origin_address_1'] != "undefined") { 
	      	$("#shipping_origin_address_1").val(response['shipping_origin_address_1']); 
	      }
	      if (typeof response['shipping_origin_address_2'] != "undefined") { 
	      	$("#shipping_origin_address_2").val(response['shipping_origin_address_2']); 
	      }
	      if (typeof response['shipping_origin_city'] != "undefined") { 
	      	$("#shipping_origin_city").val(response['shipping_origin_city']); 
	      }
	      if (typeof response['shipping_origin_postal'] != "undefined") { 
	      	$("#shipping_origin_postal").val(response['shipping_origin_postal']); 
	      }
	      if (typeof response['shipping_origin_country'] != "undefined") { 
	      	$("#shipping_origin_country").val(response['shipping_origin_country']).trigger('chosen:updated'); 
	      }
	      if (typeof response['shipping_origin_phone'] != "undefined") { 
	      	$("#shipping_origin_phone").val(response['shipping_origin_phone']); 
	      }
	    }
	    else{
	      // $('#delete_msg h5').text('Unable to connect network');
	    }
		});
	});
	$("#shipping_rate_free").change(function(){
		if ($(this).is(":checked")) {
			var shipping_amount = $("#shipping_rate_amount").val();
			$("#shipping_rate_amount").attr("disabled", "disabled").val( toCurrency(0) );
			$("#shipping_rate_amount_hdn").val( shipping_amount )
		}else{
			$("#shipping_rate_amount").removeAttr("disabled").val( toCurrency($("#shipping_rate_amount_hdn").val()) );
		}
	});
	$("#shipping_rate_amount").keyup(function(){
		$("#shipping_rate_amount_hdn").val($(this).val());
	}).change(function(){ $(this).trigger('keyup'); });

	$("#btn-save-shipping-origin-address").click(function(){ $('#shipping-origin-form').trigger('submit'); });
	$("#btn-add-shipping-area-link").click(function(){
		window.location.href = $(this).attr("data-href");
	});
	$("#btn-add-shipping-area-rate").click(function(){
		clear_shipping_rate_message();
		if (!validate_shipping_rate_fields()) { return; }

		//Process
		var shipping_rate_tmpl = $($("#shipping-rate-tmpl").html());

		var selected_type = $("#shipping_rate_type").val();
		var min_val = $("#shipping_rate_min").val() != "" ? $("#shipping_rate_min").val() : toCurrency(0);
		var max_val = $("#shipping_rate_max").val() != "" ? $("#shipping_rate_max").val() : toCurrency(0);
		var amount = $("#shipping_rate_amount").val() != "" ? $("#shipping_rate_amount").val() : toCurrency(0);

		if (current_selected_rate != null) { 
			shipping_rate_tmpl = current_selected_rate; 
		}else{
			if (selected_type == 'price-base') {
				var s = $("#price-base-container").append(shipping_rate_tmpl);
				check_price_base();
			}else if (selected_type == 'weight-base'){
				var s = $("#weight-base-container").append(shipping_rate_tmpl);
				check_weight_base();
			}else if (selected_type == 'other-method'){
				var s = $("#other-method-container").append(shipping_rate_tmpl);
				check_other_base();
			}
		}

		shipping_rate_tmpl.find(".rate-name").val($("#shipping_rate_name").val()); 
		shipping_rate_tmpl.find(".rate-description").val($("#shipping_rate_description").val()); 
		shipping_rate_tmpl.find(".rate-type").val($("#shipping_rate_type").val());
		shipping_rate_tmpl.find(".rate-min").val(accounting.unformat($("#shipping_rate_min").val()));
		shipping_rate_tmpl.find(".rate-max").val(accounting.unformat($("#shipping_rate_max").val()));
		shipping_rate_tmpl.find(".rate-free").val($("#shipping_rate_free").is(":checked") ? "Y" : "N");
		shipping_rate_tmpl.find(".rate-amount").val(accounting.unformat($("#shipping_rate_amount").val()));

		if ($("#shipping_rate_free").is(":checked")) { amount = "Free"; }

		shipping_rate_tmpl.find(".title b").html($("#shipping_rate_name").val());
		shipping_rate_tmpl.find(".detail").html( min_val + " - " + max_val + '<span class="pull-right">'+ amount +'</span>');

		initialize_rate_button_edit(shipping_rate_tmpl.find(".btn-rate-edit"));

		$("#shipping-area-rate-modal").modal('hide');
	});
	$("#btn-add-shipping-default-rate").click(function(){
		get_default_rate();
	});
	$("#btn-save-shipping-default-rate").click(function(){
		var data = {
			'rate_name' 	: $("#shipping_default_rate_name").val(),
			'rate_amount' : $("#shipping_default_rate").val(),
		};

		jQuery.post(CONFIG.get('URL')+'shipping/ajax_shipping_origin_processor',{
			operation:'save-default-rate',
			data : JSON.stringify(data),
		}, function(response,status){
			response = cms_function.isJSON(response) ? JSON.parse(response) : response;

	    if(response['status'] != undefined){
	    	if (response['status'] == 'saved') {
	    		$("#shipping_default_rate_name").val('');
	    		$("#shipping_default_rate").val('');
	    		notification("Shipping Default Rate", response['message'], "gritter-success");

	    		jQuery.post(CONFIG.get('URL')+'shipping/ajax_shipping_origin_processor',{
						operation:'get-default-rate'
					}, function(response, status){
				    if(response != undefined){
				      if (response.rate_name != undefined) {
				      	$("#shipping-default-rate-name").text(response.rate_name);
				      }
				      if (response.rate_amount != undefined) {
				      	$("#shipping-default-rate-amount").text(toCurrency(response.rate_amount));
				      }
				    }
					});
	    	}else{
	    		notification("Shipping Default Rate", response['message'], "gritter-error");
	    	}
	    }else{
	      notification("Shipping Default Rate", "Default Shipping Rate is not set", "gritter-error");
	    }

	    $("#shipping-default-rate-modal").modal('hide');
		});
	});
	$("#btn-open-shipping-rate-form").click(function(){ 
		open_shipping_rate_modal('price-base', 'Price Base Rate');
	});
	$("#btn-open-shipping-weight-form").click(function(){ 
		open_shipping_rate_modal('weight-base', 'Weight Rate');
	});
	$("#btn-open-shipping-other-method").click(function(){ 
		open_shipping_rate_modal('other-method', 'Other Method');
	});

	$("#btn-save-shipping-area").click(function(){
		save_shipping_area();
	});
	currency_field($("#shipping_rate_min"));

	currency_field($("#shipping_rate_max"));
	currency_field($("#shipping_rate_amount"));

	if ($("#selected-countries").length > 0) {
		var selected_countries = $("#selected-countries").val();
		var selected_countries_list = selected_countries.split(",");

		$("#shipping_origin_country").val( selected_countries_list ).trigger("chosen:updated");
	}

	$(".btn-rate-edit").each(function(){
		initialize_rate_button_edit($(this));
	});

	$("#btn-toggle-shipping-guide").click(function(e){
		$("#shipping-guide-modal").modal('show');
	});

	$("#btn-shipping-option-save").click(function(e){
		var options = {
			enable : $("#shipping_option_enable").is(":checked") ? "Y" : "N",
		};

		$.post(CONFIG.get('URL')+'shipping/ajax_shipping_option_processor',{
			operation : 'set',
			data : options,
		},function(response) {
			if (response.success != undefined) {
				if (response.fail > 0 ) {
					notification("Shipping Option", response.fail + " of the items are not able save.", "gritter-error");
				}else{
					notification("Shipping Option", "Successfully saved all options.", "gritter-success");
				}
			}else{
				notification("Shipping Option", "Unknown response.", "gritter-error");
			}
		}).fail(function(e){
			notification("Shipping Option", "Error found while saving shipping options.", "gritter-error");
		});
	});

	get_shipping_origin_info();
	get_shipping_rates_info();

	check_price_base();
	check_weight_base();

	//FUNTIONS
	function initialize_rate_button_edit(btn){
		btn.click(function(){
			var shipping_rate = $(this).parents('.shipping-rate');
			current_selected_rate = shipping_rate;

			$("#shipping_rate_type").val( shipping_rate.find(".rate-type").val() );
			$("#shipping_rate_name").val( shipping_rate.find(".rate-name").val() );
			$("#shipping_rate_description").val( shipping_rate.find(".rate-description").val() );
			$("#shipping_rate_min").val( formatNumber( shipping_rate.find(".rate-type").val(), shipping_rate.find(".rate-min").val() ) );
			$("#shipping_rate_max").val( formatNumber( shipping_rate.find(".rate-type").val(), shipping_rate.find(".rate-max").val() ) );

			if (shipping_rate.find(".rate-free").val() == 'Y') {
				if (!$("#shipping_rate_free").is(":checked")) {
					$("#shipping_rate_free").attr("checked", true);
				}
			}else{
				if ($("#shipping_rate_free").is(":checked")) {
					$("#shipping_rate_free").attr("checked", false);
				}
			}
			$("#shipping_rate_free").trigger("change")

			$("#shipping_rate_amount").val( toCurrency(shipping_rate.find(".rate-amount").val()) );

			$("#shipping-area-rate-modal").modal('show');
		});
	}
});

function get_default_rate(){
	$("#shipping-default-rate-modal").modal('show');

	jQuery.post(CONFIG.get('URL')+'shipping/ajax_shipping_origin_processor',{
		operation:'get-default-rate'
	}, function(response, status){
    if(response != undefined){
      if (response.rate_name != undefined) {
      	$("#shipping_default_rate_name").val(response.rate_name);
      }
      if (response.rate_amount != undefined) {
      	$("#shipping_default_rate").val(response.rate_amount);
      }
    }
    else{
      notification("Shipping Default Rate", "Default Shipping Rate is not set", "gritter-error");
    }
	});
}

function get_shipping_origin_info(){
	$("#shipping-origin-container").find("address").hide().after('<span class="loading-origin-address">Loading Shipping Origin Address...</span>');
	jQuery.post(CONFIG.get('URL')+'shipping/ajax_shipping_origin_processor',{operation:'get'}, function(response,status){
    if(response != 'undefined'){
    	$("#shipping-origin-container").find(".shipping-origin-title").text(response['shipping_origin_name']);
      $("#shipping-origin-container").find(".shipping-origin-address-1").text(response['shipping_origin_address_1'] + ", " + response['shipping_origin_address_2']);
      $("#shipping-origin-container").find(".shipping-origin-postal-city").text(response['shipping_origin_postal'] + " " + response['shipping_origin_city']);
      $("#shipping-origin-container").find(".shipping-origin-country").text(response['shipping_origin_country_detail']['name']);
      $("#shipping-origin-container").find(".shipping-origin-phone").text(response['shipping_origin_phone']);
    }
    else{
      // $('#delete_msg h5').text('Unable to connect network');
    }

    $("#shipping-origin-container").find("address").show().siblings('.loading-origin-address').remove();
  });
}
function get_shipping_rates_info(){
	$("#shipping-rates-container").find(".shipping-areas-container").html("").hide().after('<span class="loading-origin-address">Loading Shipping Rates...</span>');

	jQuery.post(CONFIG.get('URL')+'shipping/ajax_shipping_rate_processor',{operation:'get'}, function(response,status){
    if(response != 'undefined'){
    	var shipping_area_container = $("#shipping-area-container");
    	var counter = 0;
    	$.each(response.rates, function(k, v){
	    	var countries = [];
    		var tmpl = $('#tmpl-shipping-area').tmpl({id:++counter}).appendTo(shipping_area_container);

	    	$.each(v['countries'], function(kk, vv){ countries.push(vv['name']); });

	    	tmpl.find(".shipping-area-name-lbl").find('strong').html(v['detail']['area_name']);
	    	tmpl.find(".country-container .country-list").html(countries.join(", "));
	    	tmpl.find(".btn-edit-shipping-area").attr("href", CONFIG.get('URL') + "shipping/area/edit/" + v['detail']['id'] + "/");

	    	$.each(v['rates'], function(kk, vv){
    			$.each(vv, function(rate_items, item){
    				var tmpl_rate = $("#tmpl-shipping-area-rate").tmpl({}).appendTo(tmpl.find(".country-container .rates-list"));
    				var rate_min 	= item['rate_type'] =="price-base" ? toCurrency(item['rate_min']) : (item['rate_type'] =="weight-base" ? item['rate_min'] + "kg" : "$" + item['rate_min'] );
    				var rate_max 	= item['rate_type'] =="price-base" ? toCurrency(item['rate_max']) : (item['rate_type'] =="weight-base" ? item['rate_max'] + "kg" : "$" + item['rate_max'] );
    				var amount 		= item['rate_free'] == 'Y' ? "Free" : toCurrency( item['rate_amount'] );

	    			tmpl_rate.find(".rate-name").text(item['rate_name']);
	    			tmpl_rate.find(".rate-range").text(rate_min +' – '+ rate_max);
	    			tmpl_rate.find(".rate-amount").text(amount);

    				tmpl_rate = $(tmpl_rate);
		    		tmpl.find(".rate-list").append( tmpl_rate );
    			});
	    	});
    	});

    	if (response.default != undefined) {
    		var default_name = response.default.rate_name != undefined ? response.default.rate_name : "[untitled]";
    		var default_amount = response.default.rate_amount != undefined ? response.default.rate_amount : "00.00";
    		$("#shipping-default-rate-name").text(default_name);
    		$("#shipping-default-rate-amount").text(toCurrency(default_amount));
    	}

    	/* Options*/
    	if (response.options != undefined) {
    		if (response.options.enable != undefined) {
    			$("#shipping_option_enable").prop("checked", response.options.enable == 'Y')
    		}
    	}else{

    	}
    }
    else{
      notification("Shipping Rate", "Unable to save Shipping Area", "gritter-error");
    }

    $("#shipping-rates-container").find(".shipping-areas-container").show().siblings('.loading-origin-address').remove();

    check_shipping_area();
  });
}

function validate_shipping_rate_fields(){
	//validation 
	if ( $("#shipping_rate_name").val() == "" ) {
		$("#shipping-area-rate-modal").find(".message").html('<p class="alert alert-danger">Shipping Name is Empty</p>')
		return false;
	}
	return true;
}
function clear_shipping_rate_message(){
	$("#shipping-area-rate-modal").find(".message").html('')
}
function clear_rate_fields(){
	$("#shipping_rate_name").val("");
	$("#shipping_rate_min").val("");
	$("#shipping_rate_max").val("");
	$("#shipping_rate_amount_hdn").val("");
}
function check_price_base(){
	if ($("#price-base-container").find(".shipping-rate").length > 0) {
		$("#price-base-container").find(">p").remove();
	}else{
		$("#price-base-container").html("<p>Add rates based on the price of a customer's order.</p>");
	}
}
function check_weight_base(){
	if ($("#weight-base-container").find(".shipping-rate").length > 0) {
		$("#weight-base-container").find(">p").remove();
	}else{
		$("#weight-base-container").html("<p>Add rates based on the weight of a customer's order.</p>");
	}
}
function check_other_base(){
	if ($("#other-method-container").find(".shipping-rate").length > 0) {
		$("#other-method-container").find(">p").remove();
	}else{
		$("#other-method-container").html("<p>Add other rate method.</p>");
	}
}
function check_shipping_area(){
	// if ($("#shipping-rates-container .shipping-areas-container").find(".shipping-area").length > 0) {
	// 	$("#shipping-rates-container .shipping-areas-container").find(">p").remove();
	// }else{
	// 	$("#shipping-rates-container .shipping-areas-container").html("<p>Add rates based on the weight of a customer's order.</p>");
	// }
}

function open_shipping_rate_modal(type, text_label){
	$('#shipping-area-rate-modal').modal("show"); 
	$('#shipping-area-rate-modal').find("#shipping_rate_type").val(type); 
	$('#shipping-area-rate-modal').find(".header").html(text_label); 
	initialize_shipping_rate_range();
}
function initialize_shipping_rate_range(){
	if ( $("#shipping_rate_type").val() == "price-base" ) {
		currency_field( $("#shipping_rate_min") );
		currency_field( $("#shipping_rate_max") );
	}else if( $("#shipping_rate_type").val() == "weight-base" ){
		unit_field($("#shipping_rate_min"), "kg");
		unit_field($("#shipping_rate_max"), "kg");
	}else{
		currency_field( $("#shipping_rate_min") );
		currency_field( $("#shipping_rate_max") );
	}
}

function save_shipping_area(){
	$("#btn-save-shipping-area").html('<i class="icon icon-save"></i> Loading...').attr('disabled', 'disabled');
	if ($("#shipping_area_name").val() == "") {
		notification("Missing Title", "Shipping Area Name should not be empty", "gritter-error");
		return;
	}

	var data = {
		area_id : $("#shipping_area_id").length > 0 ? $("#shipping_area_id").val() : 0,
		area_name : $("#shipping_area_name").val(),
		area_countries : $("#shipping_origin_country").val(),
		area_price_base : [],
		area_weight_base : [],
		area_other_method : [],
	};

	var has_rate = false;
	//Getting price base rate
	$("#price-base-container").find(".shipping-rate").each(function(){
		data['area_price_base'].push( extract_shipping_rate( $(this) ) );
		has_rate = true;
	});
	$("#weight-base-container").find(".shipping-rate").each(function(){
		data['area_weight_base'].push( extract_shipping_rate( $(this) ) );
		has_rate = true;
	});
	$("#other-method-container").find(".shipping-rate").each(function(){
		data['area_other_method'].push( extract_shipping_rate( $(this) ) );
		has_rate = true;
	});

	jQuery.post(CONFIG.get('URL')+'shipping/ajax_shipping_area_processor',{
		operation:'add', 
		data:JSON.stringify(data)
	}, function(response,status){
		if (response) {
			if (data['area_id'] == '' || data['area_id'] == 0) {
				window.location.href = CONFIG.get('URL')+'shipping/area/edit/' + response;
			}else{
				notification("Successfully Save", '"Shipping Area Information" has beed Successfully Saved.', "gritter-success")
			}
		}

		$("#btn-save-shipping-area").html('<i class="icon icon-save"></i> Save').removeAttr('disabled');
	});
}
function extract_shipping_rate(shipping_rate_container){
	var data_container = shipping_rate_container.find(".data-container");
	var data = {
		rate_id : data_container.find(".rate-id").val(),
		rate_name : data_container.find(".rate-name").val(),
		rate_description : data_container.find(".rate-description").val(),
		rate_type : data_container.find(".rate-type").val(),
		rate_min : data_container.find(".rate-min").val(),
		rate_max : data_container.find(".rate-max").val(),
		rate_free : data_container.find(".rate-free").val(),
		rate_amount : data_container.find(".rate-amount").val(),
	}

	return data;
}

function formatNumber(rate_type, rate_value){
	if (rate_type == 'price-base') {
		return toCurrency( rate_value );
	}else if(rate_type == 'weight-base'){
		return rate_value + "kg";
	}else{
		return rate_value;
	}
}