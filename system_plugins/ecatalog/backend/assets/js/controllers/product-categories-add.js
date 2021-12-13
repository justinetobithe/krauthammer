$(document).ready(function(){
	initialize();
});

function initialize(){
  load_html_functions();
  initialize_tiny();
  load_parents(0,0);

  $("#parent").change(function(){
    $("#url_slug").trigger('blur');
  });

  ready();
}