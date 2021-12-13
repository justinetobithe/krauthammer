$(document).ready(function(){
	$("#btn-save-page-setting").click(function(e){
		var page_setting_data = {
			homepage : $("#selected-homepage").val(),
			blogpage : $("#selected-blog-page").val(),
		}

	  $.post(CONFIG.get('URL')+'settings/page_setting_processor',{
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

	$("#selected-homepage, #selected-blog-page").chosen({
		width: '100%'
	});
	$("#page-setting").css('min-height', '450px');
});

function blog_input(element, crement){
	console.log(element.val());
	var current_val = parseInt(element.val() ? element.val() : 0);
	current_val += crement;

	var new_val = current_val >=0 ? current_val : 0

	element.val(new_val);

	return new_val;
}