$(document).ready(function(){
	$("#btn-save-product-setting").click(function(e){
		var product_setting_data = {
			product_no_index_ecommerce_ecatalog : $("#product_no_index_ecommerce_ecatalog").is(":checked") ? 'Y' : 'N',
			product_no_index_category_page 			: $("#product_no_index_category_page").is(":checked") ? 'Y' : 'N',
			product_no_index_detail_page 				: $("#product_no_index_detail_page").is(":checked") ? 'Y' : 'N',
			product_no_index_cart 							: $("#product_no_index_cart").is(":checked") ? 'Y' : 'N',
			product_no_index_checkout 					: $("#product_no_index_checkout").is(":checked") ? 'Y' : 'N',
			product_no_index_order_enquiry 			: $("#product_no_index_order_enquiry").is(":checked") ? 'Y' : 'N',

			product_category_format_url 				: $("input[name=product_category_format_url]:checked").val(),

			product_home_page 					: $("#selected-product-home-page").val(),
			product_cart_page 					: $("#selected-product-cart-page").val(),
			product_checkout_page 			: $("#selected-product-checkout-page").val(),
			product_enquire_page 				: $("#selected-product-enquire-page").val(),
			product_confirmation_page 	: $("#selected-product-confirmation-page").val(),
			product_payment_method_page : $("#selected-product-payment-method-page").val(),
			product_confirmed_page 			: $("#selected-product-confirmed-page").val(),
		}

		var pdu 	= "product_url" + ($("#product-language").val() != '' ? "_" + $("#product-language").val() : '');
		var pcdu 	= "product_category_url" + ($("#product-language").val() ? "_" + $("#product-language").val() : '');
		product_setting_data[pdu]  		= $("#product-base-url").val();
		product_setting_data[pcdu] 		= $("#product-categories-base-url").val();

		var page_data = {
			site_homepage 							: $("#selected-homepage").val(),
			site_blogpage 							: $("#selected-blog-page").val(),
			product_home_page 					: $("#selected-product-home-page").val(),
			product_cart_page 					: $("#selected-product-cart-page").val(),
			product_checkout_page 			: $("#selected-product-checkout-page").val(),
			product_enquire_page 				: $("#selected-product-enquire-page").val(),
			product_payment_method_page : $("#selected-product-payment-method-page").val(),
			product_confirmation_page 	: $("#selected-product-confirmation-page").val(),
			product_confirmed_page 			: $("#selected-product-confirmed-page").val(),
		}

		var is_page_clear = true;
		$.each(page_data, function(k, v){
			$.each(page_data, function(kk, vv){
				if (typeof v !='undefined' && typeof vv !='undefined' && v == vv && k != kk && vv != '' ) {
					is_page_clear = false;
				}
			});
		});

		if (!is_page_clear) {
			notification("Duplicate Page", "A page is already used in.", "gritter-warning");
			return;
		}

		$("#modal-product-loading").modal('show');
	  $.post(CONFIG.get('URL')+'settings/product_setting_processor/',{
	  	action:'save', 
	  	data:JSON.stringify(product_setting_data),
	  },
	  function(response) {
	  	$("#modal-product-loading").modal('hide');
	  	if (response == "Saved") {
	  		notification("Product Settings", "Setting Saved", 'gritter-success');
	  	}else{
	  		notification("Product Settings", "Problem encountered upon saving: <br /><b>"+ response +"</b>", "gritter-error");
	  	}

	  	$("#product-language").trigger('change');	
	  });
	});

	$("#selected-homepage, #selected-blog-page").chosen({
		width: '100%'
	});
	$("#product-language").chosen({
		width: '100%'
	}).change(function(){
		$("#product-base-url-flag").html('');
		$("#product-categories-base-url-flag").html('');
		$.post(CONFIG.get('URL')+'settings/product_setting_processor/',{
			action:'get-dir',
			lang:$("#product-language").val(),
		},function(response) {
			if (cms_function.isJSON(response)) {
				res = JSON.parse(response);
				$("#product-base-url").val(res['product_url']);
				$("#product-categories-base-url").val(res['product_category_url']);

				$l = $("#product-language").find('option[value='+$("#product-language").val()+']').text();

				if (res['product_url_flag'] != 'set') {
					$("#product-base-url-flag").html('<small class="text-error"><em><strong>Product Base URL</strong> for selected Language (<strong>'+$l+'</strong>) is not yet Set</em></small>');
				}
				if (res['product_category_url_flag'] != 'set') {
					$("#product-categories-base-url-flag").html('<small class="text-error"><em><strong>Product Cateogry Base URL</strong> for selected Language (<strong>'+$l+'</strong>) is not yet Set</em></small>');
				}
			}
		});
	}).trigger('change');

	$("#product-base-url, #product-categories-base-url").blur(function(){
		$(this).val(slugify($(this).val()));
	});


	$("#page-setting").css('min-height', '450px');
	$("#modal-product-loading").modal({
		backdrop : 'static',
		keyboard : false,
		show : false,
	});
	$("#btn-product-custom-field-selection-delete").click(function(e){
		bootbox.confirm("Do you continue deleting selected template?", function(result){
		  if (result) {
		    $.post(CONFIG.get('URL')+'products/product_custom_fields/',{
			    action    	: 'delete_custom_field_template',
			    template_id : $("#product-custom-field-selection").val(),
			  }, function(response){
			    var response_data = JSON.parse(response);

			    if (response_data == "1") {
			    	notification("Product Custom Field", "Successfully Deleted Custom Field template", "gritter-success");
			    	getCustomFieldTempaltes();
			    }else{
			    	notification("Product Custom Field", "Unable to Deleted Custom Field template", "gritter-error");
			    }
			  });
		  }
		});
	});
	getCustomFieldTempaltes();
});

function getCustomFieldTempaltes(){
	$.post(CONFIG.get('URL')+'products/product_custom_fields/',{
    action    : 'get_custom_field_template',
  }, function(response){
    var response_data = JSON.parse(response);

    $("#product-custom-field-selection").html("");
    custom_field_template = {};

    $.each(response_data, function(k, v){
      $("#product-custom-field-selection").append($("<option value='"+ v.id +"'>"+ v.value +"</option>"));
      custom_field_template[v.id] = {
        "name"  : v.value,
        "meta"  : JSON.parse(v.meta),
      };
    });
  });
}