 

var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var regexContactNumber = /^[0-9-+]+$/;
$(document).ready(function(){
    
  var action = $('#action').val();
  if(action == 'manage_enquiries'){
    // loadOrderTable();
  }
  else{
    load_products_table_details();
  }

  $("#save_orders").click(function(){
    save_enquiries();
  });

  $('.timepicker').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false
  });

  $('.datepicker').datepicker().next().on(ace.click_event, function() {
    $(this).prev().focus();
  });

  $('#ordersTable').dataTable({
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": CONFIG.get('URL')+"enquiries/get_enquiries",
    "aoColumns": [
      { "bSortable": false },
      { "bSortable": false }, 
      null,
      null,
      null,
      { "bSortable": false },
      { "bSortable": false },
    ],
    "fnDrawCallback": function( oSettings ) {
      // var row = $(this).closest(".dataTables_wrapper").find(".row-fluid:first-child");
      // var col_1 = row.find(".span6:nth-child(1)");
      // var col_2 = row.find(".span6:nth-child(2)");

      // col_1.removeClass('span6').addClass('span4');
      // col_2.removeClass('span6').addClass('span8');

      // var label = row.find(".dataTables_filter").prepend($(".datatable-add-ons"));

      $(".enquiry-message-toggle").click(function(){
        var full_message = $(this).siblings('.enquiry-full-message').html();
        $("#full-message").find(".modal-full-message-content").html( full_message );
        $("#full-message").modal({ backdrop : 'static', keyboard : false });
      });
    }
  });
});

 //LOAD ORDER DATATABLE
function loadOrderTable(){
  $('#ordersTable').dataTable().fnDraw();

  // $('#ordersTable').dataTable().fnDestroy();

  // jQuery.post(CONFIG.get('URL')+'enquiries/get_enquiries',{action:'get'}, function(response,status){
  //   var result = JSON.parse(response);
  //   $.each(result, function(i, field){

  //     $('#ordersTable tbody').append('<tr><td class="center"><label><input type="checkbox" class="ace"/><span class="lbl"></span></label></td><td>'+field['order_timestamp']+'</td><td>'+field['first_name']+' '+field['last_name']+'</td><td>'+field['email']+'</td><td>'+field['phone']+'</td><td>'+field['message']+'</td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group"><button class="btn btn-minier btn-warning" data-rel="tooltip" data-placement="top" title="View" onclick="view_enquiries('+field['id']+');"><i class="icon-info-sign bigger-120"></i></button><button class="btn btn-minier btn-info" data-rel="tooltip" data-placement="top" title="Edit" onclick="edit_enquiry('+field['id']+')"><i class="icon-edit bigger-120"></i></button><button class="btn btn-minier btn-danger" data-rel="tooltip" data-placement="top" title="Delete" onclick="deleteOrder('+field['id']+')"><i class="icon-trash bigger-120"></i></button></div></td></tr>');
  //   });
  //   $('[data-rel=tooltip]').tooltip();
  //    $('#ordersTable').dataTable( {
  //     "aoColumns": [
  //      { "bSortable": false },
  //      null,
  //      null,
  //      null,
  //      null,
  //      null,
  //      { "bSortable": false}
  //     ],
  //     "fnDrawCallback": function( oSettings ) {
  //       var row = $(this).closest(".dataTables_wrapper").find(".row-fluid:first-child");
  //       var col_1 = row.find(".span6:nth-child(1)");
  //       var col_2 = row.find(".span6:nth-child(2)");

  //       col_1.removeClass('span6').addClass('span4');
  //       col_2.removeClass('span6').addClass('span8');

  //       var label = row.find(".dataTables_filter").prepend($(".datatable-add-ons"));
  //     }
  //   });
  // });
}

function load_products_table_details(){
  $('#orders_details').dataTable().fnDestroy();
  $('#orders_details').dataTable( {
    "bPaginate": false,
      "aoColumns": [
       { "bSortable": false },
       null,
       null,
       null,
       null
      ]});
}

function view_enquiries(id){
  window.location.href = CONFIG.get('URL')+'enquiries/view/'+id;
}

function edit_enquiry(id){
  window.location.href = CONFIG.get('URL')+'enquiries/edit/'+id;
}

function save_enquiries(){
  var data = {};
  data['id'] = $('#hidden_id').val();
  data['first_name'] = $('#order_name').val();
  data['last_name'] = $('#last_name').val();
  // data['company'] = $('#company').val();
  data['phone'] = $('#order_contact_number').val();
  data['email'] = $('#order_email').val();

  data['message'] = $('#order_message').val();
  data['order_status'] = 'active';

  var other_fields = [];

  $.each($("#other-fields").find('.control-group'), function(k, v){
    var _input = null; 

    if ($(this).find('.input').length) {
      _input = $(this).find('.input');
    }

    if (_input == null) { return; }

    var field = {
      "id" : _input.attr('id'),
      "value" : _input.val(),
    }
    other_fields.push( field );
  });

  $.post(CONFIG.get('URL')+'enquiries/save_order',{action:'save', data:data, other_fields:other_fields},function(response,status){
    var result = JSON.parse(response);
    if(status == 'success'){
      if(result['id'] >= 0 ){
        if(result['id'] == 0){
          window.location.href = CONFIG.get('URL')+'enquiries/';
        }else{
          window.location.href = CONFIG.get('URL')+'enquiries/edit/'+result['id'];
        }
      }else{
        $('#result').append(alertMessage('Error: Unable to save Order. Please try again.','error','error_saving'));
      }
    }else{
      $('#result').append(alertMessage('Error: 404-No Network Fount.','error','error_network'));
    }
  });
}
function deleteOrder(id){
  var id = id;
  delete_enquiries( id );
}
function deleteView(){
  var id = $('#hidden_id').val();
  delete_enquiries( id );
}
function delete_enquiries( id ){
  bootbox.confirm("Are you sure you want to delete selected item?", function(result){
    if (result) {
      jQuery.post(CONFIG.get('URL')+'enquiries/delete_enquiry/', {action: 'delete', id:id},function(response,status){
        var result = JSON.parse(response);
        if(result == '1'){
          window.location = CONFIG.get('URL')+'enquiries/';
        }else{
          notification("Enquiry", "Unable to Delete the Order", "gritter-error");
        }
      });
    }
  });
}