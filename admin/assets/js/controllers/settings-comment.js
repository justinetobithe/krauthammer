$(document).ready(function(){
	$("#btn-save-comment-setting").click(function(){
	  var data = {
	  	"comments-allow-on-article" : $("#comments-allow-on-article:checked").length ? 'Y' : 'N',
	  	"comments-article-comment-auto-close" : $("#comments-article-comment-auto-close:checked").length ? 'Y' : 'N',
	  	"comments-article-comment-days-old" : $("#comments-article-comment-days-old").val() || 0,
	  	"comments-author-previously-approved" : $("#comments-author-previously-approved:checked").length ? 'Y' : 'N',
	  	"comments-email-me-on-comment" : $("#comments-email-me-on-comment:checked").length ? 'Y' : 'N',
	  	"comments-email-me-on-moderate" : $("#comments-email-me-on-moderate:checked").length ? 'Y' : 'N',
	  	"comments-enable-hold" : $("#comments-enable-hold:checked").length ? 'Y' : 'N',
	  	"comments-enable-nesting" : $("#comments-enable-nesting:checked").length ? 'Y' : 'N',
	  	"comments-hold-count-trigger" : $("#comments-hold-count-trigger").val()||1,
	  	"comments-list-blacklisted-words" : $("#comments-list-blacklisted-words").val(),
	  	"comments-list-moderated-words" : $("#comments-list-moderated-words").val(),
	  	"comments-manual-approve" : $("#comments-manual-approve:checked").length ? 'Y' : 'N',
	  	"comments-nesting-level" : $("#comments-nesting-level").val()||1,
	  	"comments-require-email-name" : $("#comments-require-email-name:checked:checked").length ? 'Y' : 'N',
	  	"comments-required-registration" : $("#comments-required-registration:checked").length ? 'Y' : 'N',
	  }

	  $.post(CONFIG.get('URL')+'settings/comments_processor/',{
	  	action : "save",
	  	data : JSON.stringify(data),
	  },
	  function(response) {
	  	console.log(response);
	  	response = JSON.parse(response);
	  	if (response['status']||'error'=='ok') {
	  		notification("Comment", "Comment Setting Saved", "gritter-success");
	  	}else{
	  		notification("Comment", "Comment Setting Saved", "gritter-error");
	  	}
	  });
	});
});