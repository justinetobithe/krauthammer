var is_searching = false;
$(document).ready(function(){
  init_pager($(".guide-pager"));
});

function init_pager(pager){
  pager.click(function(e){
    e.preventDefault();

    if (!is_searching) {
      var page = $(this).data('page');
      $(".guide-pager").removeClass('active')
      $(this).addClass('active');

      $("#main-container-guide").html('<div class="well text-center"><strong>Loading Content...</strong></div>');

      is_searching = true;
      $.post(CONFIG.get('URL')+'/settings/guide/' + page,{
        operation : 'page-request',
      },function(response) {
        $("#main-container-guide").html(response);
        init_pager($(".guide-pager"));
        is_searching = false;
      }).fail(function(e){
        notification("Guide", "Unable to load page", "gritter-error");
        is_searching = false;
      });
    }
  });
}