var addional_action = 'add';
var additional_order_id = 0;
var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var regexContactNumber = /^[0-9-+]+$/;
var _price = 0;
var new_price = 0;
var price = 0;
$(document).ready(function(){

	var action = $('#action').val();

	if(action == 'view_orders')
		load_products_table_details();
	else if(action == 'manage_order')
		loadOrderTable();

	$('#save_order').click(function(e){
		e.preventDefault();
		if(validate())
			save_order();
	});


	$('#print_invoice').click(function(e){

		e.preventDefault();
		window.location.href = CONFIG.get('URL')+'orders/print_invoice/'+$('#hidden_id').val();
	});

	$('#print_order').click(function(e){
		e.preventDefault();
		window.location.href = CONFIG.get('URL')+'orders/print_order/'+$('#hidden_id').val();
	});

	$('#cancel_order').click(function(e){
		e.preventDefault();
		$('#delete_order').modal('show');
	});

	if($('#products_hidden').length > 0)
		var products = JSON.parse(atob($('#products_hidden').val()));

	$('#product_price').keyup(function(){

		grand_total = parseFloat($('#total_price').text()) - _price;
		$('#total_price').text(grand_total.toFixed(2));
		_price = 0;

		if($.isNumeric($('#product_price').val())){
			price = $('#product_price').val();
		}
		quantity = $('#product_quantity').val();
		_price =  parseFloat(price) * quantity;
		grand_total = parseFloat($('#total_price').text()) + _price;

		$('#total_price').text(grand_total.toFixed(2));

	});

	$("#product_name").chosen({
		width : "100%"
	}).change(function(e){

		$('#product_quantity').val('1');
		$('#product_image').attr('src', '');

		grand_total = parseFloat($('#total_price').text()) - _price;
		$('#total_price').text(grand_total.toFixed(2));
		_price = 0;

		$.each(products, function(index, value){
			if(value['id'] == $('#product_name').val())
			{
				var iamge = '';
				if(value['featured_image_url'] == '')
					image = CONFIG.get('FRONTEND_URL')+'/images/uploads/default.png';
				else
					image = CONFIG.get('FRONTEND_URL')+value['featured_image_url'];

				image = image.replace('/images/', '/thumbnails/200x120/');

				$('#product_image').attr('src', image);

				$('#product_price').val(value['price'])


				price = parseFloat($('#product_price').val());

				_price =  price * $('#product_quantity').val();
				grand_total = parseFloat($('#total_price').text()) + _price;
				$('#total_price').text(grand_total.toFixed(2));
				$('#product_price_hidden').val(_price);
			}
		});
	});

	$('#product_quantity').keyup(function(e){

		var quantity = 0
		if($.isNumeric($('#product_quantity').val())){
			quantity = $('#product_quantity').val();
		}


		grand_total = parseFloat($('#total_price').text()) - _price;
		$('#total_price').text(grand_total.toFixed(2));
		_price = 0; 

		_price =  parseFloat(price) * quantity;
		grand_total = parseFloat($('#total_price').text()) + _price;
		$('#total_price').text(grand_total.toFixed(2));
		if(quantity == 0)
			_price = 0;

		$('#product_price_hidden').val(_price);
	});

	$('#new_product_quantity').keyup(function(e){
		var new_price_add = 0;
		var new_quantity_add = 0;
		if($.isNumeric($('#new_product_quantity').val())){
			new_quantity_add = $('#new_product_quantity').val();
		}

		grand_total = parseFloat($('#total_price').text()) - new_price;
		$('#total_price').text(grand_total.toFixed(2));
		new_price = 0;

		if($('#new_product_name').val() != ''){

			if($.isNumeric($('#new_product_price').val())){
				new_price_add = parseFloat($('#new_product_price').val());
			}


			new_price = new_price_add * new_quantity_add;

			grand_total = parseFloat($('#total_price').text()) + new_price;

			$('#total_price').text(grand_total.toFixed(2));

			$('#new_product_price_hidden').val(new_price);
		}
	});

	$('#new_product_price').keyup(function(e){
		var new_price_add = 0;
		var new_quantity_add = 0;
		if($.isNumeric($('#new_product_price').val())){
			new_price_add = parseFloat($('#new_product_price').val());
		}

		grand_total = parseFloat($('#total_price').text()) - new_price;
		$('#total_price').text(grand_total.toFixed(2));
		new_price = 0;

		if($('#new_product_name').val() != ''){
			if($.isNumeric($('#new_product_quantity').val())){
				new_quantity_add = $('#new_product_quantity').val();
			}
			new_price = new_price_add * new_quantity_add;
			grand_total = parseFloat($('#total_price').text()) + new_price;

			$('#total_price').text(grand_total.toFixed(2));
			$('#new_product_price_hidden').val(new_price);
		}
	});

	$("#modal_btn_current_customer").click(function(){
		$("#modal-current-customer").modal( "show" );
		$("#customer-table").dataTable().fnDraw();
	});

	$("#modal-current-customer").modal({ backdrop : 'static',keyboard : false, show : false });
	$("#modal-current-customer-loading").modal({ backdrop : 'static',keyboard : false, show : false });

	$("#customer-table").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "orders/table_processor_customer",
		"aoColumns": [
		{"sWidth": "30%"},
		{"sWidth": "20%"},
		{"sWidth": "20%"},
		{"sWidth": "20%"},
		{"sWidth": "10%"},
		],
		"fnDrawCallback": function(oSettings) {
			$(".btn-order-select-customer").click(function(){
				$("#modal-current-customer").modal( "hide" );
				$("#modal-current-customer-loading").modal( "show" );

				var data = {
					"id" : $(this).attr("data-value")
				};
				$.post(CONFIG.get('URL')+'orders/get_customer_detail',{oper:'get', data:JSON.stringify(data)},function(response,status){
					if (typeof response['firstname'] != '') {
						$("#first_name").val(response['firstname']);
						$("#last_name").val(response['lastname']);
						$("#email").val(response['email']);
						$("#phone").val(response['contact_number']);

						$("#shipping_name").val(response['firstname'] + " " + response['lastname']);
						$("#shipping_address").val(response['shipping_address']);
						$("#shipping_address_line_2").val(response['shipping_address_2']);
						$("#shipping_city").val(response['shipping_city']);
						$("#shipping_postal").val(response['shipping_postal_code']);
						$("#shipping_state").val(response['shipping_state']);
						$("#shipping_country").val(response['shipping_country']).trigger('chosen:updated');;
						$("#shipping_email").val(response['shipping_email']);
						$("#shipping_phone").val(response['shipping_phone']);

						$("#billing_name").val(response['firstname'] + " " + response['lastname']);
						$("#billing_address").val(response['billing_address']);
						$("#billing_address_line_2").val(response['billing_address_2']);
						$("#billing_city").val(response['billing_city']);
						$("#billing_postal").val(response['billing_postal_code']);
						$("#billing_state").val(response['billing_state']);
						$("#billing_country").val(response['billing_country']).trigger('chosen:updated');
						$("#billing_email").val(response['billing_email']);
						$("#billing_phone").val(response['billing_phone']);

					}

					$("#modal-current-customer-loading").modal( "hide" );
				});
			});
		}, "fnServerParams": function(aoData) {

		},
	});

	var countries = [];
	$("#shipping_country_2").find("option").each(function(){
		var country_group = [];
		countries.push({
			"name" : $(this).text(),
			"value" : $(this).val(),
			"code" : $(this).attr("data-countryCode"),
			"group" : $(this).attr("data-countryCode"),
		});
	});

	$('.timepicker').timepicker({
		minuteStep: 1,
		showSeconds: false,
		showMeridian: true
	});
	$('.datepicker').datepicker().next().on(ace.click_event, function() {
		$(this).prev().focus();
	});

	$("input[name=e-form-order-detail-mode]").change(function(){
		if ($(this).val() == "self-collection" && $(this).is(":checked")) {
			$("#container-order-detail-self-collection").slideDown();
			$("#container-order-detail-home-delivery").slideUp();
		}
		if ($(this).val() == "delivery-to-home" && $(this).is(":checked")) {
			$("#container-order-detail-self-collection").slideUp();
			$("#container-order-detail-home-delivery").slideDown();
		}
	}).trigger('change');

	$("input[name=e-form-order-detail-delivery-type]").change(function(){
		if ($(this).val() == "normal" && $(this).is(":checked")) {
			$("#container-order-detail-delivery-time-1").slideDown();
			$("#container-order-detail-delivery-time-2").slideUp();
		}
		if ($(this).val() == "express" && $(this).is(":checked")) {
			$("#container-order-detail-delivery-time-1").slideUp();
			$("#container-order-detail-delivery-time-2").slideDown();
		}
	}).trigger('change');

	$("#modal-edit-product").chosen({
		width : "100%"
	}).on('change', function(){
		get_product_info($(this).val());
	});

	$("#modal-edit-order-item-qty, #modal-edit-order-item-price").change(function(){
		var num1 = parseFloat($("#modal-edit-order-item-qty").val());
		var num2 = parseFloat($("#modal-edit-order-item-price").val());
		$("#modal-edit-order-total-price").val( num1 * num2);
	});

	$("#modal-btn-save-product-item-ordered").click(function(){
		productQuantitySave();
	});

	$("#modal-edit-order-item").modal({
		backdrop : 'static',
		keyboard : false,
		show : false,
	});
});

function format_date_invoice(date){
	var unformatdate = date.split(' ');
	var rawdate = unformatdate[0].split('-');

	return rawdate[1]+'-'+rawdate[2]+'-'+rawdate[0];
}
function edit_additional_order(id, product_id,quantity,grand_total){
	clear_modal();
	$('#add_product_title').attr('data-toggle', '');
	$('#add_product_title').attr('onclick', 'return false;');

	$('#product_name').val(product_id);
	$('#product_name').trigger("chosen:updated");
	$('#product_name').trigger('change');

	$('#product_quantity').val(quantity);
	$('#total_price').text(grand_total.toFixed(2));
	$('.modal_header').text('Edit Additional Product');

	$('#button_additional_order').html('<i class="icon-check"></i> Update Order');

	addional_action = 'edit';
	additional_order_id = id;
	_price = parseFloat(_price);

	$('#add_additional_order').modal('show');
}
function delete_additional_order(id, product_id,quantity,grand_total){
	clear_modal();
	addional_action = 'delete';
	additional_order_id = id;

	$('#add_product_title').attr('data-toggle', '');
	$('#add_product_title').attr('onclick', 'return false;');

	$('#product_name').val(product_id);
	$('#product_name').trigger("chosen:updated");
	$('#product_name').trigger('change');

	$('#product_quantity').val(quantity);
	$('#total_price').text(grand_total.toFixed(2));
	$('.modal_header').text('Edit Additional Product');

	$('.modal_header').text('Delete Additional Product');

	$('#button_additional_order').html('<i class="icon-trash"></i> Delete Order');
	$('#add_additional_order').modal('show');
}
function click_additional_product(id){
	if(validate_additional_order()){
		var url = '';
		if(addional_action == 'add')
			url = 'add_additional_order';
		else if(addional_action == 'edit')
			url = 'save_additional_order';
		else
			url = 'delete_additional_order';

		var data = {};
		data['id'] = additional_order_id;
		data['order_id'] = id;
		data['product_id'] = $('#product_name').val();
		data['quantity'] = $('#product_quantity').val();
		data['price'] =  $('#product_price').val();

		data['new_product_name'] = $('#new_product_name').val();
		data['new_product_quantity'] = $('#new_product_quantity').val();
		data['new_product_price'] = $('#new_product_price_hidden').val();

		$.post(CONFIG.get('URL')+'orders/'+url,{action:'save', data:data},function(response,status){
			addional_action = 'add';
			if(JSON.parse(response))
				window.location.reload(true);
		});

	}
}
function validate_additional_order(){
	var error = false;
	if($('#product_name').val() == '-1')
		error = true;
	if($.isNumeric($('#product_quantity').val()) == false) 
		error = true;
	if($('#new_product_name').val() != '')
		error = false;

	if(error)
		return false;

	return true;
}
function save_order(){
	var data = {};
	data['id'] = $('#hidden_id').val();
	data['first_name'] = $('#first_name').val();
	data['last_name'] = $('#last_name').val();
	data['company'] = $('#company').val();
	data['phone'] = $('#phone').val();
	data['email'] = $('#email').val();
	data['billing_name'] = $('#billing_name').val();
	data['billing_address'] = $('#billing_address').val();
	data['billing_address_line_2'] = $('#billing_address_line_2').val();
	data['billing_city'] = $('#billing_city').val();
	data['billing_postal'] = $('#billing_postal').val();
	data['billing_state'] = $('#billing_state').val();
	data['billing_country'] = $('#billing_country').val();
	data['billing_email'] = $('#billing_email').val();
	data['billing_phone'] = $('#billing_phone').val();
	data['shipping_name'] = $('#shipping_name').val();
	data['shipping_address'] = $('#shipping_address').val();
	data['shipping_address_line_2'] =$('#shipping_address_line_2').val();
	data['shipping_city'] = $('#shipping_city').val();
	data['shipping_postal'] = $('#shipping_postal').val();
	data['shipping_state'] = $('#shipping_state').val();
	data['shipping_country'] = $('#shipping_country').val();
	data['shipping_email'] = $('#shipping_email').val();
	data['message'] = $('#message').val();
	data['invoice_number'] = $('#invoice_number').val();
	data['order_status'] = 'active';
	data['order_timestamp'] = $('#order_timestamp').val();

	var delivery_data = get_data_delivery_detail();

	var send_email = $('#checkbox-send-email').is(":checked") ? 'Y' : 'N';
	var other_fields = [];

	$.each($("#other-fields").find('.control-group'), function(k, v){
		var _id = "";
		var _value = "";

		if ($(this).find('.input').length) {
			_id = $(this).find('.input').attr('id');
			_value = $(this).find('.input').val();
		}else if($(this).find('.other-field-link')){
			_id = $(this).find('.other-field-link').attr('id');
			_value = $(this).find('.other-field-link').attr('href');
		}

		var field = {
			"id" : _id,
			"value" : _value,
		}
		other_fields.push( field );
	});

	$.post(CONFIG.get('URL')+'orders/save_order/',{action:'save', data:data, other_fields:other_fields, delivery_detail: delivery_data, send_email: send_email},function(response,status){
		// return;
		var result = JSON.parse(response);
		if(status == 'success'){
			if(result['id'] >= 0 ){
				if (result['type'] == "edit") {
					notification("Order", "Successfully Updated Order.", "gritter-success");
				}else{
					window.location.href = CONFIG.get('URL')+'orders/edit/'+result['id'];
				}
				/*if(result['id'] == 0)
					// window.location.href = CONFIG.get('URL')+'orders/';
				else{
					window.location.href = CONFIG.get('URL')+'orders/edit/'+result['id'];
				}*/
			}else{
				notification("Order", "Error: Unable to save Order. Please try again.", "gritter-error");
				// $('#result').append(alertMessage('Error: Unable to save Order. Please try again.','error','error_saving'));
			}
		}else{
			notification("Order", "Error: 404-No Network Fount.", "gritter-error");
			// $('#result').append(alertMessage('Error: 404-No Network Fount.','error','error_network'));
		}
	});
}

function validate(){
	return true;
}

function load_products_table_details(){
	if($('#orders_details').length > 0){
		// $('#orders_details').dataTable().fnDestroy();
		// $('#orders_details').dataTable({
		// 	"bPaginate": false,
		// 	"aoColumns": [
		// 	{ "bSortable": false },
		// 	null,
		// 	null,
		// 	null,
		// 	null,
		// 	null,
		// 	{ "bSortable": false },
		// 	],
		// 	"fnDrawCallback" : function(oSettings){
		// 		$(".btn-delete-order").each(function(){
		// 			$(this).click(function(){
		// 				var order_item_id = $(this).attr('data-value');
		// 				bootbox.confirm("Are you sure you want to delete selected item?", function(result){
		// 					if (result) { 
		// 						productQuantityDelete(order_item_id);
		// 					}
		// 				});
		// 			});
		// 		});
		// 		$(".btn-edit-order").each(function(){
		// 			$(this).click(function(){
		// 				var order_item_id = $(this).attr('data-value');
		// 				var order_product_id = $(this).attr('data-product');
		// 				var order_item_qty = $(this).attr('value');
		// 				var order_item_price = $(this).parents('tr').find('.product-item-price').text();

		// 				$("#modal-edit-order-item-id").val(order_item_id);
		// 				$("#modal-edit-order-item-qty").val(order_item_qty);
		// 				$("#modal-edit-order-item-selected-product").val(order_product_id);
		// 				$("#modal-edit-product").val(order_product_id).trigger("chosen:updated");

		// 				$("#modal-edit-order-item").modal({
		// 					backdrop : 'static',
		// 					keyboard : 'false',
		// 				});

		// 				productItemCheck(order_product_id, order_item_qty, order_item_price)
		// 			});
		// 		});
		// 	}
		// });

		// $.each($("#orders_details tbody").find('.product-description-container'), function(k, v){
		// 	var container = $(this);
		// 	container.find(".product-description-toggle").click(function(){
		// 		container.find(".product-item-description").toggle();
		// 	});
		// });

		$.each($("#orders_details tbody").find('tr'), function(){
			$(this).find('.btn-delete-order').click(function(){
				var order_item_id = $(this).attr('data-value');
				bootbox.confirm("Are you sure you want to delete selected item?", function(result){
					if (result) { 
						productQuantityDelete(order_item_id);
					}
				});
			});
			$(this).find('.btn-edit-order').click(function(){
				var order_item_id = $(this).attr('data-value');
				var order_product_id = $(this).attr('data-product');
				var order_item_qty = $(this).attr('value');
				var order_item_price = $(this).parents('tr').find('.product-item-price').text();

				$("#modal-edit-order-item-id").val(order_item_id);
				$("#modal-edit-order-item-qty").val(order_item_qty);
				$("#modal-edit-order-item-selected-product").val(order_product_id);
				$("#modal-edit-product").val(order_product_id).trigger("chosen:updated");

				$("#modal-edit-order-item").modal({
					backdrop : 'static',
					keyboard : 'false',
				});

				productItemCheck(order_product_id, order_item_qty, order_item_price)
			});

			var container = $(this).find('.product-description-container');
			container.find(".product-description-toggle").click(function(){
				container.find(".product-item-description").toggle();
			});
		})
	}
}
function productItemCheck(product_id, quantity, price){
	if (typeof product_id == 'undefined') {
		return; 
	}

	var data = { id:product_id };

	$.post(CONFIG.get('URL')+'orders/product_detail_processor/',{
		oper : 'get-product',
		data : JSON.stringify( data ),
	},function(response) {
		if (typeof response['product_options'] != "undefined") {
			$("#modal-edit-product-options").find('.controls').html("");

			if (response['product_options'].length > 0) {
				$("#modal-edit-product-options").show();
				var _isChecked = false;
				$.each(response['product_options'], function(k, v){
					var isChecked = "";
					if ($("#product-option-item-" + product_id).html() == v['product_option_value_labes']) {
						var isChecked = 'checked="checked"';
						_isChecked = true;
					}

					var lbl = $('<label></label>');
					lbl.html('<input name="modal-edit-product-options" type="radio" class="ace" value="'+ v['id'] +'" '+ isChecked +'> <span class="lbl">'+ v['product_option_value_labes'] +'</span>');
					$("#modal-edit-product-options").find('.controls').append(lbl);
				});

				if (!_isChecked){
					$("#modal-edit-product-options").find('.controls').find("label:first-child input[name=modal-edit-product-options]").attr('checked', 'checked');
				}
			}else{
				$("#modal-edit-product-options").hide();
			}
		}

		if (typeof response['product'] != "undefined") {
			$("#modal-edit-item-remaining").text(parseFloat(response['product']['quantity']) + parseFloat(quantity));
			$("#modal-edit-order-item-price").val(parseFloat(price));
			$("#modal-edit-order-item-track-inventory").val(response['product']['track_inventory']);

			var temp_image = new Image();
			var featured_image_url = typeof response['product']['featured_image_url'] != "undefined" ? response['product']['featured_image_url'] : "";
			var img_src = CONFIG.get('FRONTEND_URL') + featured_image_url.replace(/\/~+$/,'');
			temp_image.onload = function() { 
				$("#modal-edit-product-image").attr('src', temp_image.src);
				$("#modal-edit-product-image-container").show();
			}
			temp_image.onerror = function() { 
				$("#modal-edit-product-image-container").hide();
			}
			temp_image.src = img_src;

			if (response['product']['track_inventory'] == "YES") {
				$("#modal-edit-order-item-qty-container").show();
			}else{
				$("#modal-edit-order-item-qty-container").hide();
			}
		} 

		$("#modal-edit-order-item-qty").trigger('change');
	});
}
function productQuantitySave(){
	var data = {
		"order_item_id" : $("#modal-edit-order-item-id").val(),
		"product_id" : $("#modal-edit-product").val(),
		"product_option" : $("input[name=modal-edit-product-options]:checked").siblings('span').text(),
		"qty" : parseFloat($("#modal-edit-order-item-qty").val()),
		"price" : parseFloat($("#modal-edit-order-item-price").val()),
	}
	var product_item_limit = parseFloat($("#modal-edit-item-remaining").text());

	if (product_item_limit < data['qty'] && $("#modal-edit-order-item-track-inventory").val() == 'YES') {
		notification("Product Order Error", "Item quantity should not exceed the current product quantity", "gritter-error");
		return;
	}

	$.post(CONFIG.get('URL')+'orders/product_detail_processor/',{
		oper : 'save',
		data : JSON.stringify(data),
	},
	function(response) {
		if (typeof response['success'] != 'undefined') {
			// alert($("#table-ordered-item-quantity-"+ data['order_item_id']).html());
			$item_price = $("#modal-edit-order-item-price").val();
			$("#table-ordered-item-sub-total-"+ data['order_item_id']).html((parseFloat($item_price) * parseFloat(data['qty'])).toFixed(2));
			$("#table-ordered-item-quantity-"+ data['order_item_id']).html(data['qty']);
			$("#table-ordered-item-quantity-"+ data['order_item_id']).closest("tr").find('.product-item-price').html(response['data']['price']);
			$(".btn-edit-order[data-value="+ data['order_item_id'] +"]").attr('value', response['data']['qty']);

			var edit_btn = $(".btn-edit-order[data-product="+ $("#modal-edit-order-item-selected-product").val() +"]");
			var row = edit_btn.parents("tr");

			get_product_detail($("#modal-edit-product").val(), function(response){
				if (response['product']) {
					var fimage = CONFIG.get('FRONTEND_URL') + response['product']['featured_image_url'].replace(/\/~+$/,'');
					var temp_image = new Image();
					temp_image.onload = function() { 
						row.find('.product-item-feature-image').attr('src', temp_image.src);
					}
					temp_image.onerror = function() { 
						row.find('.product-item-feature-image').attr('src', CONFIG.get("FRONTEND_URL") + "/thumbnails/78x66/uploads/default.png");
					}
					temp_image.src = fimage;				
				}
				row.find('.product-item-description').html(response['product']['product_description']);
				row.find('.product-item-name').html(response['product']['product_name']);
				row.find('.product-option').html($("input[name=modal-edit-product-options]:checked").siblings('.lbl').text());
				edit_btn.attr("data-product", $("#modal-edit-product").val());
			})
		}
	});
}
function productQuantityDelete(product_id){
	jQuery.post(CONFIG.get('URL')+'orders/product_detail_processor/', {
		oper: 'delete', data:JSON.stringify(product_id)
	}, function(response){
		if (typeof response['success'] != 'undefined') {
			$("#table-ordered-item-quantity-"+product_id).parents('tr').remove();
		}
		notification("Deleted Ordered Product", response['message'], "gritter-info");
	})
}
function get_product_info($product_id = 0){
	$.post(CONFIG.get('URL')+'orders/product_detail_processor/',{
		oper : 'get-product',
		data : JSON.stringify( {id:$product_id} ),
	},function(response) {
		if (typeof response['product_options'] != "undefined") {
			$("#modal-edit-product-options").find('.controls').html("");

			if (response['product_options'].length > 0) {
				$("#modal-edit-product-options").show();
				$.each(response['product_options'], function(k, v){
					var lbl = $('<label></label>');
					lbl.html('<input name="modal-edit-product-options" type="radio" class="ace" value="'+ v['id'] +'"> <span class="lbl">'+ v['product_option_value_labes'] +'</span>');
					$("#modal-edit-product-options").find('.controls').append(lbl);
				});
			}else{
				$("#modal-edit-product-options").hide();
			}

			$("#modal-edit-product-options").find('.controls').find("label:first-child input[name=modal-edit-product-options]").attr('checked', 'checked');
		} 
		if (typeof response['product'] != "undefined"){
			var temp_image = new Image();
			var img_src = CONFIG.get('FRONTEND_URL') + response['product']['featured_image_url'].replace(/\/~+$/,'');
			temp_image.onload = function() { 
				$("#modal-edit-product-image").attr('src', temp_image.src);
				$("#modal-edit-product-image-container").show();
			}
			temp_image.onerror = function() { 
				$("#modal-edit-product-image-container").hide();
			}
			$("#modal-edit-item-remaining").text(parseFloat(response['product']['quantity']) + parseFloat($("#modal-edit-order-item-qty").val()));
			temp_image.src = img_src;
		}
	});
}
function get_product_detail($product_id = 0, fnCallback){
	$.post(CONFIG.get('URL')+'orders/product_detail_processor/',{
		oper : 'get-product',
		data : JSON.stringify( {id:$product_id} ),
	},function(response) {
		if (typeof fnCallback != "undefined") {
			fnCallback(response)
		}
	});
}

//LOAD ORDER DATATABLE
function loadOrderTable(){
	$("#ordersTable").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": CONFIG.get('URL') + "orders/table_processor_orders/",
		"iDisplayLength" : 25,
		"aaSorting": [[3, 'desc']],
		"fnDrawCallback": function(oSettings) {
			/*$(this).find(".btn-order-view").click(function(){ viewOrder( $(this).attr('data-value') ); });*/
			/*$(this).find(".btn-order-edit").click(function(){ editOrder( $(this).attr('data-value') ); });*/
			$(this).find(".btn-order-trash").click(function(){ deleteOrder( $(this).attr('data-value') ); });
			$(this).find(".btn-open-message").click(function(){ 
				$("#modal-view-message").modal({
					'backdrop' : 'static',
					'keyboard' : false,
				}); 
				$("#modal-view-message-item").html("Loading Message...");
				$.post(CONFIG.get('URL')+'orders/processor_orders_detail/',{
					action : "get-message",
					order_id : $(this).attr('data-id'),
				},
				function(response) {
					response = JSON.parse(response);
					$("#modal-view-message-item").html(response['data']);
				});
			});
			/*$(this).find(".btn-view-email").click(function(){ 
				$("#modal-view-email").modal({
					'backdrop' : 'static',
					'keyboard' : false,
				}); 
				$("#modal-view-email-item").val($(this).attr('data-email'));
			});*/
		}, "fnServerParams": function(aoData) {

		},
		"aoColumns": [
		{"sWidth": 50},
		null,
		{"sWidth": 100},
		{"sWidth": 100},
		null,
		null,
		null,
		null,
		{"bSortable": false},
		]
	});
}

function formatDate(input) {
	var datePart = input.match(/\d+/g),
	year = datePart[0], 
	month = datePart[1], day = datePart[2];

	var hour = datePart[3];
	var minutes = datePart[4];
	return day+'/'+month+'/'+year + ' ' + hour + ':' + minutes;
}
function clear_modal(){
	$('#product_name').val(-1);
	$('#product_name').trigger("chosen:updated");
	$('#product_name').trigger('change');
	$('#product_quantity').val(1);
	$('#total_price').text('0');

	$('#add_order_tab').addClass('active');
	$('#add_order').addClass('active');
	$('#add_new_product_tab').removeClass('active');
	$('#add_new_product_modal').removeClass('active');
	$('#add_order_title').attr('data-toggle', 'tab');
	$('#add_order_title').attr('onclick', '');
	$('#add_product_title').attr('data-toggle', 'tab');
	$('#add_product_title').attr('onclick', '');

	$('#new_product_name').val('');
	$('#new_product_quantity').val('');
	$('#new_product_price').val('');
}
function add_additional_order(id){
	clear_modal();
	$('.modal_header').text('Add Additional Product');
	$('#button_additional_order').html('<i class="icon-plus"></i> Add to Order');

	$('#add_additional_order').modal('show');
}
//TABLE FUNCTIONS
function deleteOrder(id){
	$('#hidden_order_id').val(id);
	$('#delete_msg h5').text("Are you sure to delete this order?");
	$('#delete').modal('show');
}
function editOrder(id)
{
	window.location = CONFIG.get('URL')+'orders/edit/'+id;
}
function viewOrder(id)
{
	window.location = CONFIG.get('URL')+'orders/view/'+id;
}
//MODAL FUNCTIONS
function deleteOrderModal(){
	var id = $('#hidden_order_id').val();
	jQuery.post(CONFIG.get('URL')+'orders/deleteOrder', {action: 'delete', id:id},function(response,status){
		var result = JSON.parse(response);
		if(result == '1')
			window.location = CONFIG.get('URL')+'orders';
		else
		{
			$('#delete_msg h5').text("Unable to Delete the Order");
		}
	});
}
function closeModal()
{
	window.location = CONFIG.get('URL')+'orders';
}

function backView()
{
	window.location = CONFIG.get('URL')+'orders/';
}
function deleteView(){
	var id = $('#hidden_id').val();
	deleteOrder(id);
}

function go_to_edit(id){
	window.location.href=CONFIG.get('URL')+'orders/edit/'+id;
}

function new_edit_additional_order(id, product_name, quantity, price){
	clear_modal();
	$('#add_order_tab').removeClass('active');
	$('#add_order').removeClass('active');
	$('#add_order_title').attr('data-toggle', '');
	$('#add_order_title').attr('onclick', 'return false;');

	$('#add_new_product_tab').addClass('active');
	$('#add_new_product_modal').addClass('active');

	$('#new_product_name').val(product_name);
	$('#new_product_quantity').val(quantity);
	$('#new_product_price').val(parseFloat(price) / parseFloat(quantity));

	$('#total_price').text(price.toFixed(2));
	$('.modal_header').text('Edit Additional Product');

	$('#button_additional_order').html('<i class="icon-check"></i> Update Order');

	addional_action = 'edit';
	additional_order_id = id;
	new_price = parseFloat(price);
	$('#add_additional_order').modal('show');
}

function new_delete_additional_order(id, product_name,quantity,price){
	clear_modal();
	addional_action = 'delete';
	additional_order_id = id;

	$('#add_order_tab').removeClass('active');
	$('#add_order').removeClass('active');
	$('#add_order_title').attr('data-toggle', '');
	$('#add_order_title').attr('onclick', 'return false;');

	$('#add_new_product_tab').addClass('active');
	$('#add_new_product_modal').addClass('active');

	$('#new_product_name').val(product_name);
	$('#new_product_quantity').val(quantity);
	$('#new_product_price').val(parseFloat(price) / parseFloat(quantity));

	$('#total_price').text(price.toFixed(2));
	$('.modal_header').text('Delete Additional Product');

	$('#button_additional_order').html('<i class="icon-trash"></i> Delete Order');

	$('#add_additional_order').modal('show');
}

function cancel_order(){
	var id = $('#hidden_id').val();
	jQuery.post(CONFIG.get('URL')+'orders/cancel_order/', {action: 'cancel', id:id},function(response,status){
		var result = JSON.parse(response);

		if(result)
			window.location = CONFIG.get('URL')+'orders/';
	});
}

function get_data_delivery_detail(){
	var mode = $("input[name=e-form-order-detail-mode]:checked").val();
	var delivery_type = $("input[name=e-form-order-detail-delivery-type]:checked").val();
	var delivery_date = mode == "self-collection" ? $("#e-form-order-detail-collection-date").val() : $("#e-form-order-detail-delivery-date").val();
	var delivery_time = mode == "delivery-to-home" ? $("#e-form-order-detail-collection-time").val() : $("#e-form-order-detail-delivery-time").val();

	if (mode == "self-collection") {
		delivery_date = $("#e-form-order-detail-collection-date").val();
		delivery_time = $("#e-form-order-detail-collection-time").val();
	}

	if (mode == "delivery-to-home") {
		delivery_date = $("#e-form-order-detail-delivery-date").val();

		if (delivery_type == "normal") {
			delivery_time = $("#e-form-order-detail-delivery-time-1").val();
		}
		if (delivery_type == "express") {
			delivery_time = $("#e-form-order-detail-delivery-time-2").val();
		}
	}

	var data = {
		"delivery-mode" : $("input[name=e-form-order-detail-mode]:checked").val(),
		"delivery-time" : delivery_time,
		"delivery-date" : delivery_date,
		"delivery-type" : delivery_type,
		"delivery-address" : $("#e-form-order-detail-delivery-address").val(),
		"delivery-postal" : $("#e-form-order-detail-postal-code").val(),
	}

	return data;
}