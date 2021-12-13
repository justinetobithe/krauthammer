var email_arr = [];
var global_error = false;
var check_action = 0;
$(document).ready(function(){

	var action = $('#action').val();

	if(action == 'add'){
		load_actions();
		load_keyup();
	}else if(action == 'manage'){
		/*load_customers();*/
		$("#customer_table").dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": CONFIG.get('URL') + "customers/table_processor/",
			"aoColumns": [
			{"sWidth": 20, "bSortable": false},
			{"sWidth": 200},
			null,
			{"sWidth": 250},
			{"sWidth": 250},
			{"sWidth": 60},
			{"sWidth": 20, "bSortable": false},
			], "fnDrawCallback": function(oSettings) {
				$(this).find('tbody tr').each(function(){
					var tr = $(this);
					tr.click(function(){
						tr.find('td:first-child input[type=checkbox]').trigger('click');
					});

					tr.find('.btn-trash').click(function(){
						var id = $(this).attr('data-value');
						delete_customer(id);
					});
				});
			}, "fnServerParams": function(aoData) {
				/*aoData.push({"name": "variable_name", "value": "variable_value"});*/
			}
		});
	}else if(action == 'edit'){
		check_action = 1;
		set_dropdown();
		load_actions();
	}

	$("#shipping_country").chosen({width: '300px'});
	$("#billing_country").chosen({width: '300px'});

});

/*Functions*/
function set_dropdown(){
	$('#shipping_country').val($('#shipping_country_val').val()).trigger("chosen:updated");
	$('#shipping_city').val($('#shipping_city_val').val()).trigger("chosen:updated");
	$('#billing_city').val($('#billing_city_val').val()).trigger("chosen:updated");
	$('#billing_country').val($('#billing_country_val').val()).trigger("chosen:updated");
}
function load_customers(){
	$("#customer_table").dataTable().fnDraw();
}
function delete_customer(id){
	$('#hidden_customer_id').val(id);
	$('#delete').modal('show');
}
function __delete_customer(){
	var id = $('#hidden_customer_id').val();
	jQuery.post(CONFIG.get('URL')+'customers/action',{action:'delete', id:id}, function(response,status){
		if(status == 'success'){
			if(JSON.parse(response) == '1'){
				load_customers();
				$('#delete').modal('hide');
			}else{
				$('#delete_msg h5').text('Error while deleting. Please Try Again.');
			}
		}
		else{
			$('#delete_msg h5').text('Unable to connect network');
		}
	});
}
function load_actions(){
	var checked = false;
	jQuery.post(CONFIG.get('URL')+'customers/load', {action: 'customer'}, function(response, status){
		$.each(JSON.parse(response), function(i, field){
			email_arr.push(field['email']);
		});
	});

	$('#customer_form').ajaxForm({
		beforeSubmit: function(arr, $form, options){
			var custom_fields = {};
			$.each($("#custom-fields-container").find('.customer-custom-fields'), function(){
				custom_fields[$(this).attr('id')] = $(this).val();
			});

			arr.push({'name' : 'custom_fields', 'value' : JSON.stringify(custom_fields)});


			$("#loading").modal({
				backdrop : 'static',
				keyboard: false
			});
		},
		complete: function(xhr){
			var result = xhr.responseText;

			if ($('#action').val() == 'add' && $('#action').val() != '0') {
					window.location = CONFIG.get('URL')+'customers/edit/'+result+"/"; 
			}else{
				if(result == '1')
					window.location = CONFIG.get('URL')+'customers/edit/' + $("#customer_form").find('input[name=id]').val() + "/";
				else{
					jQuery('#alert_customer_details').append(alertMessage('Error while saving please try again.','error','error_adding'));
					$("#loading").modal('hide');
				}
			}
		}
	});
	$('#different_shipping_address').change(function() {

		if($(this).is(":checked")) {
			enabled_billing();
			$("#shipping-info-container").show();
		}
		else{
			set_billing_address();
			disable_billing();
			$("#shipping-info-container").hide();
		}

	}).trigger('change');

	$('#customer_email').keyup(function(){
		$('div').remove('#error_email');
		if($(this).val() == $('#hdn_customer_email').val())
			check_action = 1;
		else
			check_action = 0;
		if(check_action == 0){
			if($.inArray($(this).val(), email_arr)>=0){
				jQuery('#alert_customer_details').append(alertMessage('Duplicate Email.','error','error_email'));
				global_error = true;
			}else
			global_error = false;
		}
	});
}
function load_keyup(){
	$('#billing_address').keyup(function(){
		$('#shipping_address').val($(this).val());
	}).change(function(){$(this).trigger("keyup")});

	$('#billing_address_2').keyup(function(){
		$('#shipping_address_2').val($(this).val());
	}).change(function(){$(this).trigger("keyup")});

	$('#billing_city').keyup(function(){
		$("#shipping_city").val($(this).val()).trigger("chosen:updated");
	}).change(function(){$(this).trigger("keyup")});

	$('#billing_country').change(function(){
		$("#shipping_country").val($(this).val()).trigger("chosen:updated");
	});

	$('#billing_postal_code').keyup(function(){
		$('#shipping_postal_code').val($(this).val());
	}).change(function(){$(this).trigger("keyup")});

	$('#billing_state_region').keyup(function(){
		$('#shipping_state_region').val($(this).val());
	}).change(function(){$(this).trigger("keyup")})

	$('#billing_email').keyup(function(){
		$('#shipping_email').val($(this).val());
	}).change(function(){$(this).trigger("keyup")});

	$('#billing_phone').keyup(function(){
		$('#shipping_phone').val($(this).val());
	}).change(function(){$(this).trigger("keyup")});
}
function disable_billing(){
	$("#shipping_state_region").attr("disabled", "disabled");
	$('#shipping_city').attr("disabled", "disabled").trigger("chosen:updated");
	$('#shipping_country').attr("disabled", "disabled").trigger("chosen:updated");
	$('#shipping_email').attr("disabled", "disabled").trigger("chosen:updated");
	$('#shipping_phone').attr("disabled", "disabled").trigger("chosen:updated");
	$('#shipping_postal_code').attr("disabled", "disabled");;
	$('#shipping_address').attr("disabled", "disabled");
	$('#shipping_address_2').attr("disabled", "disabled");
}
function set_billing_address(){
	$("#shipping_state_region").val($('#billing_state_region').val());
	$('#shipping_postal_code').val($('#billing_postal_code').val());
	$('#shipping_address').val($('#billing_address').val());
	$('#shipping_address_2').val($('#billing_address_2').val());
	$("#shipping_country").val($('#billing_country').val()).trigger("chosen:updated");
	$("#shipping_city").val($('#billing_city').val()).trigger("chosen:updated");
	$("#shipping_email").val($('#billing_email').val()).trigger("chosen:updated");
	$("#shipping_phone").val($('#billing_phone').val()).trigger("chosen:updated");
}
function enabled_billing(){
	$('#shipping_state_region').removeAttr('disabled');
	$('#shipping_city').removeAttr('disabled').trigger("chosen:updated");
	$('#shipping_email').removeAttr('disabled').trigger("chosen:updated");
	$('#shipping_phone').removeAttr('disabled').trigger("chosen:updated");
	$('#shipping_country').removeAttr('disabled').trigger("chosen:updated");
	$('#shipping_postal_code').removeAttr('disabled');
	$('#shipping_address').removeAttr('disabled');
	$('#shipping_address_2').removeAttr('disabled');
}
function validate(){
	var err = false;
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var email = $('#customer_email').val();
	var password = $('#customer_password').val();
	var con_pass = $('#con_password').val();
	jQuery('#alert_customer_details').empty();
	if(email == ''){
		jQuery('#alert_customer_details').append(alertMessage('Please fill Email.','error','error_email'));
		err = true;
	}else{
		if(!regex.test(email)){
			jQuery('#alert_customer_details').append(alertMessage('Invalid Email.','error','error_email'));
			err = true;
		}else
		if(check_action = 0){
			if($.inArray(email, email_arr) >= 0){
				jQuery('#alert_customer_details').append(alertMessage('Duplicate Email.','error','error_email'));
				err = true;
			}
			else{
				if(email != $('#hdn_customer_email').val()){
					if($.inArray(email, email_arr) >= 0){
						jQuery('#alert_customer_details').append(alertMessage('Duplicate Email.','error','error_email'));
						err = true;
					}
				}
			}
		}
	}

	if(password ==''){
		jQuery('#alert_customer_details').append(alertMessage('Please fill Password.','error','error_password'));
		err = true;
	}else{
		if(con_pass!=password){
			jQuery('#alert_customer_details').append(alertMessage('Password Mismatch.','error','error_password'));
			err = true;
		}
	}

	if(err || global_error){
		return false;
	}

	enabled_billing();
	return true;
}
function back(){
	window.location.href = CONFIG.get('URL')+'customers/';
}