$(function(){
  $("#contact_exporter").dataTable({
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": CONFIG.get('URL') + "contact-exporter/table/",
    "aoColumns": [
      {"sWidth": 30, "bSortable": false},
      {"sWidth": 200},
      {"sWidth": 200},
      {},
      {"sWidth": 100, "bSortable": false},
      {"sWidth": 50, "bSortable": false},
    ], "fnDrawCallback": function(oSettings) {
      $("[data-rel=tooltip]").tooltip();
    }, "fnServerParams": function(aoData) {
      /*aoData.push({"name": "variable_name", "value": "variable_value"});*/
    }
  });
});