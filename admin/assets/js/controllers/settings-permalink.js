$(document).ready(function(){
	$("#btn-save-seo-setting").click(function(e){
		var post_url_format = $("#post-format-container").find("input[type='radio'][name='post_url_format']:checked");
		var page_setting_data = {
			siteurl : $("#site_url").val(),
			post_url_format : typeof post_url_format.val() != 'undefined' ? post_url_format.val() : "",
			robots_txt : typeof $("#robottxt").length ? $("#robottxt").val() : "",
			blacklisted_url : typeof $("#blacklisted_url").length ? $("#blacklisted_url").val() : "",
			indexing : $("#indexing").val(),
			blog_indexing : $("#blog_indexing").val(),
			blog_post_indexing : $("#blog_post_indexing").val(),
			blog_pagination_indexing : $("#blog_pagination_indexing").val(),
			blog_search_indexing : $("#blog_search_indexing").val(),
			blog_category_indexing : $("#blog_category_indexing").val(),
      https_redirect : $("#https_redirect").is(":checked") ? "ON" : "OFF",

      structured_data_enable          : $("#structured_data_enable").is(":checked") ? "ON" : "OFF",
      structured_data_company_name    : $("#structured_data_company_name").val(),
      structured_data_office_address  : $("#structured_data_office_address").val(),
      structured_data_telephone       : $("#structured_data_telephone").val(),
      structured_data_email           : $("#structured_data_email").val(),
      structured_data_price_range     : $("#structured_data_price_range").val(),
		};

		var robot_data = $("#robottxt").val();

	  $.post(CONFIG.get('URL')+'settings/permalink_processor/',{
	  	action:'save', data:JSON.stringify(page_setting_data)
	  },
	  function(response) {
	  	if (response == "Saved") {
	  		notification("Page Settings", "Setting Saved", 'gritter-success');
	  	}else{
	  		notification("Page Settings", "Problem encountered upon saving: <br /><b>"+ response +"</b>", "gritter-error");
	  	}
	  });
	});

	
  $("#blog_indexing, #blog_post_indexing, #blog_search_indexing, #blog_category_indexing, #blog_pagination_indexing").change(function(e){
    if ($(this).is(":checked")) {
      $(this).val("ON");
    }else{
      $(this).val("OFF");
    }

    $(this).siblings('.blog_indexing_switch').val($(this).val());
  });

  get_structured_data();
});

function get_structured_data(){
  $.post(CONFIG.get('URL')+'settings/permalink_processor/',{
    action:'get-structured-data'
  },
  function(response) {
    console.log(response);
    if (response != undefined) {
      if (response.enable != undefined) {
        $("#structured_data_enable").prop( 'checked', response.enable == 'ON' ? true : false );
      }
      $("#structured_data_company_name").val( response.name != undefined ?response.name : '' );
      $("#structured_data_office_address").val( response.address != undefined ?response.address : '' );
      $("#structured_data_telephone").val( response.telephone != undefined ?response.telephone : '' );
      $("#structured_data_email").val( response.email != undefined ?response.email : '' );
    }
  });
}