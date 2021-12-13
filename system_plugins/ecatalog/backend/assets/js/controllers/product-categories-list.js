$(document).ready(function(){
  initialize();
});

function initialize(){
  load_category_table();
  ready();
}


function load_category_table(){
  $('#productCategoriesTable').dataTable().fnDestroy();
  jQuery.post(CONFIG.get('URL')+'product-categories/load_categories',{action:'get'}, function(response,status){
    $('#productCategoriesTable tbody').html('');

    var result = JSON.parse(response);
    $.each(result, function(i, field){
      var cat_name    = field['category_name'].substring(0,38);
      var link_edit   = CONFIG.get('URL')+'products/categories/edit/'+field['id'];
      var btn_edit    = '<a href="'+ link_edit +'" data-rel="tooltip" title="Edit: '+ cat_name +'" class="btn btn-minier btn-info"><i class="icon-edit bigger-120"></i></a>';
      var btn_delete  = '<button class="btn btn-minier btn-danger" data-rel="tooltip" title="Delete: '+ cat_name +'" onclick="show_delete_category('+field['id']+')"><i class="icon-trash bigger-120"></i></button>';


      if(field['category_parent'] == ' '){
        $('#productCategoriesTable tbody').append('<tr><td class="center"><label><input type="checkbox" class="ace"/><span class="lbl"></span></label></td><td>'+ cat_name +'</td><td>'+field['category_description']+'</td><td></td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group">'+ btn_edit +' '+ btn_delete +'</div></td></tr>');
      }else{
        $('#productCategoriesTable tbody').append('<tr><td class="center"><label><input type="checkbox" class="ace"/><span class="lbl"></span></label></td><td>'+ cat_name +'<br><i>Parent: '+field['category_parent'].substring(0,30)+'</i></td><td>'+field['category_description']+'</td><td></td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group">'+ btn_edit +' '+ btn_delete +'</div></td></tr>');
      }
    });

    $('#productCategoriesTable').dataTable({
      "bPaginate": false,
      "aoColumns": [
      { "sWidth": 50, "bSortable": false },
      { "sWidth": 200,},
      null,
      { "sWidth": 200,},
      { "sWidth": 50, "bSortable": false}
      ],
      "fnDrawCallback": function(oSettings) {
        $("[data-rel='tooltip']").tooltip();
      },
    });
    /* $('#productCategoriesTable_filter').hide(); */
    $('#delete').modal('hide');
  });
}
function show_delete_category(id){
  $('#hdn_id').val(id);
  $('#delete').modal('show');
}
function delete_category(){
  var id = $('#hdn_id').val();
  jQuery.post(CONFIG.get('URL')+'product-categories/delete_categories',{action:'delete', id:id}, function(response,status){

    result = JSON.parse(response);
    if(result == 1)
    {
      load_category_table();
    }
    $('#hdn_id').val(0);
  });
}