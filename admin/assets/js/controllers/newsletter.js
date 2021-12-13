$(function(){
  $('#table-newsletter').dataTable({
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": CONFIG.get('URL')+"newsletter/processor/",
    "aoColumns": [
      { "bSortable": false },
      null,
      { "bSortable": false}
    ],
    "fnDrawCallback": function( oSettings ) {
      $(this).find('tbody').each(function(){
        $(this).find('.table-btn-edit-email').click(function(){
          $("#modal-input-email-address-id").val($(this).attr('data-value'));
          $("#modal-input-email-address").val($(this).attr('data-email'));
          $("#modal-add-newsletter").modal('show');
        });
        $(this).find('.table-btn-delete-email').click(function(){
          var item_id = $(this).attr('data-value');
          bootbox.confirm("Are you sure you want to delete selected item?", function(result){
            if (result) {
              $.post(CONFIG.get('URL')+'newsletter/processor/',{
                action : 'delete-email',
                id : item_id
              },
              function(response) {
                if (response) {
                  notification("Newslettter", "Successfully Deleted Email Address.", "gritter-success")
                }else{
                  notification("Newslettter", "Unable to Delete Email Address.", "gritter-error")
                }
                $('#table-newsletter').dataTable().fnDraw();
              });
            }
          });
        });
      });
    },
    "fnServerParams": function( aoData ) {
      aoData.push({"name": "action", "value": 'load-subscribers'});
    },
  });

  $("#modal-add-newsletter").modal({
    backdrop : 'static',
    keyboard : false,
    show : false,
  }).on("shown.bs.modal", function(e){
    
  });
  $("#btn-add-subscriber").click(function(){
    $("#modal-add-newsletter").modal('show');
    $("#modal-input-email-address").val('');
    $("#modal-input-email-address-id").val('0');
  });
  $("#modal-btn-add-email").click(function(){
    $.post(CONFIG.get('URL')+'newsletter/processor/',{
      action : 'add-email',
      id : $("#modal-input-email-address-id").val(),
      email : $("#modal-input-email-address").val(),
    },
    function(response) {
      $json_response = JSON.parse(response);

      if (typeof $json_response['status'] != "undefined") {
        if ($json_response['status'] == 'success') {
          notification("Newslettter", "Successfully Saved Email Address.", "gritter-success")
        }else{
          notification("Newslettter", "Unable to Save Email Address.", "gritter-error")
        }
      }else{
        notification("Newslettter", "Unknown Error Found.", "gritter-error")
      }
      $("#modal-add-newsletter").modal('hide');
      $('#table-newsletter').dataTable().fnDraw();
    });
  });
});