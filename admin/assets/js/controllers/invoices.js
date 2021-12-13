var addional_action = 'add';
var additional_order_id = 0;
var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var regexContactNumber = /^[0-9-+]+$/;
var _price = 0;
var new_price = 0;
var price = 0;

$(document).ready(function(){
  var action = $('#action').val();

  $('.modal').on('show', function(e){
    $('#modal_container').removeClass('hide');
  });
  
  $('.modal').on('hidden', function(e){
    $('#modal_container').addClass('hide');
  });

  if(action == 'view_orders'){
    load_products_table_details();
  }
  else if(action == 'manage_order'){
    loadOrderTable();
  }

  $('#save_order').click(function(e){
    e.preventDefault();
    if(validate()){
      save_order();
    }
  });

  var url = window.location.href.split('/');
  $('#manage_invoices').dataTable( {
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": CONFIG.get('URL')+"invoices/all_invoices",
    "aoColumns": [
    { "bSortable": false },
    null, 
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    { "bSortable": false}
    ],
    "fnServerParams": function ( aoData ) {
      aoData.push( { "name": "method", "value": url[6] } );
    },
    "fnDrawCallback": function( oSettings ) {

      $.each($('.item-checkbox'), function(){
        $(this).parents('td').addClass('center');
        $(this).addClass('ace');
      });

    }
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

  if($('#products_hidden').length > 0){
    var products = JSON.parse(atob($('#products_hidden').val()));
  }

  $('#product_price').keyup(function(){
    grand_total = parseInt($('#total_price').text()) - _price;
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

  $('#product_name').change(function(e){
    $('#product_quantity').val('1');
    $('#product_image').attr('src', '');

    grand_total = parseInt($('#total_price').text()) - _price;
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
    if(quantity == 0){
      _price = 0;
    }

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

  $("input[name=e-form-order-detail-mode]").change(function(){
    if ($(this).val() == "self-collection" && $(this).is(":checked")) {
      $("#container-order-detail-self-collection").slideDown();
      $("#container-order-detail-home-delivery").slideUp();
    }
    if ($(this).val() == "delivery-to-home" && $(this).is(":checked")) {
      $("#container-order-detail-self-collection").slideUp();
      $("#container-order-detail-home-delivery").slideDown();
    }
  }).trigger('change').unbind("change");

  $("input[name=e-form-order-detail-delivery-type]").change(function(){
    if ($(this).val() == "normal" && $(this).is(":checked")) {
      $("#container-order-detail-delivery-time-1").slideDown();
      $("#container-order-detail-delivery-time-2").slideUp();
    }
    if ($(this).val() == "express" && $(this).is(":checked")) {
      $("#container-order-detail-delivery-time-1").slideUp();
      $("#container-order-detail-delivery-time-2").slideDown();
    }
  }).trigger('change').unbind("change");
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
    data['price'] =  $('#product_price_hidden').val();

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
  if($('#product_name').val() == '-1'){
    error = true;
  }
  if($.isNumeric($('#product_quantity').val()) == false) {
    error = true;
  }
  if($('#new_product_name').val() != ''){
    error = false;
  }

  if(error){
    return false;
  }

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
  data['shipping_name'] = $('#shipping_name').val();
  data['shipping_address'] = $('#shipping_address').val();
  data['shipping_address_line_2'] =$('#shipping_address_line_2').val();
  data['shipping_city'] = $('#shipping_city').val();
  data['shipping_postal'] = $('#shipping_postal').val();
  data['shipping_state'] = $('#shipping_state').val();
  data['shipping_country'] = $('#shipping_country').val();
  data['message'] = $('#message').val();
  data['invoice_number'] = $('#invoice_number').val();
  data['order_status'] = 'active';
  data['order_timestamp'] = $('#order_timestamp').val();

  $.post(CONFIG.get('URL')+'orders/save_order',{action:'save', data:data},function(response,status){
    var result = JSON.parse(response);
    if(status == 'success'){
      if(result['id'] >= 0 ){
        if(result['id'] == 0){
          window.location.href = CONFIG.get('URL')+'invoices/';
        }
        else{
          window.location.href = CONFIG.get('URL')+'orders/invoices/'+result['id'];
        }
      }
      else{
        $('#result').append(alertMessage('Error: Unable to save Order. Please try again.','error','error_saving'));
      }
    }
    else{
      $('#result').append(alertMessage('Error: 404-No Network Fount.','error','error_network'));
    }
  });
}

function validate(){
  return true;
}

function load_products_table_details(){
  if($('#orders_details').length > 0){
    $('#orders_details').dataTable().fnDestroy();
    $('#orders_details').dataTable( {
      "bPaginate": false,
      "aoColumns": [
      null,
      null,
      null,
      null,
      null
      ],"fnDrawCallback": function(oSettings) {
        $("#orders_details tbody").find('tr').each(function(){
          image_loader($(this).find('img'));

          var container = $(this).find('.product-description-container');
          container.find(".product-description-toggle").click(function(){
            container.find(".product-item-description").toggle();
          });
        });
      }});
  }
  if($('#additional_order_details').length > 0){
    $('#additional_order_details').dataTable().fnDestroy();
    $('#additional_order_details').dataTable( {
      "bPaginate": false,
      "aoColumns": [
      { "bSortable": false },
      null,
      null,
      null,
      null,
      null,
      { "bSortable": false }
      ]});
  }
}

/*LOAD ORDER DATATABLE*/
function loadOrderTable(){
  $('#ordersTable').dataTable().fnDestroy();
  jQuery.post(CONFIG.get('URL')+'orders/get',{action:'getOrders'}, function(response,status){
    var result = JSON.parse(response);
    $.each(result, function(i, field){

      $('#ordersTable tbody').append('<tr><td class="center"><label><input type="checkbox" class="ace"/><span class="lbl"></span></label></td><td>'+formatDate(field['order_timestamp'])+'</td><td>'+field['first_name']+' '+field['last_name']+'</td><td>'+field['email']+'</td><td>'+field['phone']+'</td><td>'+field['message']+'</td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group"><button class="btn btn-minier btn-warning" data-rel="tooltip" data-placement="top" title="View" onclick="viewOrder('+field['id']+');"><i class="icon-info-sign bigger-120"></i></button><button class="btn btn-minier btn-info" data-rel="tooltip" data-placement="top" title="Edit" onclick="editOrder('+field['id']+')"><i class="icon-edit bigger-120"></i></button><button class="btn btn-minier btn-danger" data-rel="tooltip" data-placement="top" title="Delete" onclick="deleteOrder('+field['id']+')"><i class="icon-trash bigger-120"></i></button></div></td></tr>');
    });
    $('[data-rel=tooltip]').tooltip();
    $('#ordersTable').dataTable( {
      "aoColumns": [
      { "bSortable": false },
      null,
      null,
      null,
      null,
      null,
      { "bSortable": false}
      ]});
  });
}

function formatDate(input) {
  var datePart = input.match(/\d+/g),
  year = datePart[0], /* get only two digits */
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

/*TABLE FUNCTIONS*/
function deleteOrder(id)
{
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
/*MODAL FUNCTIONS*/
function deleteOrderModal()
{
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
function deleteView()
{
  var id = $('#hidden_id').val();
  deleteOrder(id);
}

function go_to_edit(id){
  window.location.href=CONFIG.get('URL')+'invoices/edit/'+id;
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
  jQuery.post(CONFIG.get('URL')+'orders/cancel_order', {action: 'cancel', id:id},function(response,status){
    var result = JSON.parse(response);

    if(result){
      window.location = CONFIG.get('URL')+'orders/';
    }
  });
}
function modal_new_payment(){
  clear_modal_payment_form();
  $('#modal_new_payment').modal('show');
}
function modal_edit_payment(id, payment_ref_number, payment_type,payment_total_amount, payment_status){
  clear_modal_payment_form();
  $('#hidden_payment_id').val(id);
  $('#new_payment_referral_num').val(payment_ref_number);
  $('#new_payment_type').val(payment_type);
  $('#new_amount').val(payment_total_amount); 
  $('#new_payment_status').val(payment_status);
  $('#modal_new_payment').modal('show');
}
function add_new_payment(){
  var data = {};

  data['order_id'] = $('#hidden_id').val();
  data['payment_ref_number'] = $('#new_payment_referral_num').val();
  data['payment_mode_id'] = $('#new_payment_type').val();
  data['payment_total_amount'] = $('#new_amount').val();
  data['payment_status'] = $('#new_payment_status').val();
  if($('#hidden_payment_id').val() == 0){
    $.post(CONFIG.get('URL')+'invoices/add_new_order_payment',{action:'add', data:data},function(response,status){
      location.reload();
    });
  }
  else{
    $.post(CONFIG.get('URL')+'invoices/update_order_payment',{action:'save', data:data, id:$('#hidden_payment_id').val() },function(response,status){
      location.reload();
    });
  }
}
function clear_modal_payment_form(){
  $('#hidden_payment_id').val(0);
  $('#new_payment_referral_num').val('');
  $('#new_payment_type').val(1);
  $('#new_amount').val(''); 
  $('#new_payment_status').val(1);
}
function delete_payment_modal(id){
  $('#delete_hidden_payment_id').val(id);
  $('#modal_delete_payment').modal('show')
}
function delete_payment(){
  $.post(CONFIG.get('URL')+'invoices/delete_order_payment',{action:'delete', id:$('#delete_hidden_payment_id').val() },function(response,status){
    location.reload();
  });
}
