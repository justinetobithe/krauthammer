$(document).ready(function(){
	$("#btn-eCommerce-save").click(function(){
	  eCommerceSave();
	});

	eCommerceGet();
});

function eCommerceSave(){
  var data = {
  	"ecommerce-enable-delivery-detail" : $("#e-form-enable-delivery").is(":checked") ? "Y" : "N",
  	"ecommerce-self-collection-discount" : $("#e-form-self-collect-discount").val(),
  	"ecommerce-normal-delivery-charge" : $("#e-form-normal-charge").val(),
  	"ecommerce-normal-delivery-time" : $("#e-form-delivery-time").val(),
  	"ecommerce-express-delivery-surcharge" : $("#e-form-surcharge").val(),
  	"ecommerce-shipping-detail-enable" : $("#e-form-enable-shilling-detail").is(":checked") ? "Y" : "N",
  	"ecommerce-billing-detail-enable" : $("#e-form-enable-billing-detail").is(":checked") ? "Y" : "N",
  }

  $.post(CONFIG.get('URL')+'system-settings/ajax_module_processor/',{
  	action : "save-ecommerce",
  	data : JSON.stringify(data),
  },
  function(response) {
  	if (typeof response['success'] != "undefined" && response['success'] === true) {
  		notification("eCommerce", "Successfully updated eCommerce setting", "gritter-success");
  	}else{
  		notification("eCommerce", "Unable to update eCommerce setting", "gritter-error");
  	}
  });
}

function eCommerceGet(){
	$.post(CONFIG.get('URL')+'system-settings/ajax_module_processor/',{
		action : "get-ecommerce"
	},
	function(response) {
		if (response['ecommerce-enable-delivery-detail'] == "Y") {
			if (!$("#e-form-enable-delivery").is(":checked")) {
				$("#e-form-enable-delivery").trigger("click");
			}
		}else{
			if ($("#e-form-enable-delivery").is(":checked")) {
				$("#e-form-enable-delivery").trigger("click");
			}
		}

		if (response['ecommerce-shipping-detail-enable'] == "Y") {
			if (!$("#e-form-enable-shilling-detail").is(":checked")) {
				$("#e-form-enable-shilling-detail").trigger("click");
			}
		}else{
			if ($("#e-form-enable-shilling-detail").is(":checked")) {
				$("#e-form-enable-shilling-detail").trigger("click");
			}
		}

		if (response['ecommerce-billing-detail-enable'] == "Y") {
			if (!$("#e-form-enable-billing-detail").is(":checked")) {
				$("#e-form-enable-billing-detail").trigger("click");
			}
		}else{
			if ($("#e-form-enable-billing-detail").is(":checked")) {
				$("#e-form-enable-billing-detail").trigger("click");
			}
		}

		$("#e-form-self-collect-discount").val(response['ecommerce-self-collection-discount'])
		$("#e-form-normal-charge").val(response['ecommerce-normal-delivery-charge'])
		$("#e-form-delivery-time").val(response['ecommerce-normal-delivery-time'])
		$("#e-form-surcharge").val(response['ecommerce-express-delivery-surcharge'])
	});
}