$(document).ready(function(){
	$("#btn-save-blog-setting").click(function(e){
		var blog_setting_data = {
			blog_count : blog_input($("#blog-post-count"), 0)
		}

	  $.post(CONFIG.get('URL')+'settings/blog_setting_processor',{
	  	action:'save', data:JSON.stringify(blog_setting_data)
	  },
	  function(response) {
	  	if (response == "Saved") {
	  		notification("Blog Setting", "Setting Saved", 'gritter-success');
	  	}else{
	  		notification("Blog Setting", "Problem encountered upon saving", "gritter-error");
	  	}
	  });
	});
	$("#btn-save-customer-registration").click(function(e){
		var blog_setting_data = {
			enable_customer_registration : $("#enable_customer_registration").is(":checked") ? "ON" : "OFF"
		}

	  $.post(CONFIG.get('URL')+'settings/blog_setting_processor/',{
	  	action:'save-customer-registration', data:JSON.stringify(blog_setting_data)
	  },
	  function(response) {
	  	if (response == "Saved") {
	  		notification("Blog Setting", "Setting Saved", 'gritter-success');
	  	}else{
	  		notification("Blog Setting", "Problem encountered upon saving", "gritter-error");
	  	}
	  });
	});
	blog_input($("#blog-post-count"), 0);

  $("#blog-post-count-container").find(".icon-chevron-up").closest("button").click(function(e){
    blog_input($("#blog-post-count"), 1);
  });
  $("#blog-post-count-container").find(".icon-chevron-down").closest("button").click(function(e){
    blog_input($("#blog-post-count"), -1);
  });
});

function blog_input(element, crement){
	console.log(element.val());
	var current_val = parseInt(element.val() ? element.val() : 0);
	current_val += crement;

	var new_val = current_val >=0 ? current_val : 0

	element.val(new_val);

	return new_val;
}