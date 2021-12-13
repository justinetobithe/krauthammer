$(function(){
	var Post = {
		post_id : $("#hidden_id").val(),
		last_state : 'none',
		table : $("#table-post-comments").dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": CONFIG.get('URL') + "post/post_comment_table_processor",
			"aoColumns": [
			{"bSortable": false},
			{"sWidth": "100", "bSortable": false},
			{"sWidth": "100", "bSortable": false},
			], "fnDrawCallback": function(oSettings) {
				$("[data-rel=tooltip]").tooltip();

				Post.fn.initialize_table_controls($(this));
			}, "fnServerParams": function(aoData) {
				aoData.push({"name": "post_id", "value": $("#hidden_id").val()});
			}
		}),
		dialog : $("#modal-post-comment").modal({
			keyboard : false,
			backdrop : 'static',
			show : false,
		}).on('hidden', function(){
			if (Post.last_state == 'reply') {
				Post.last_state = 'none';
				Post.dialog_reply.modal('show');
				Post.fn.get_reply($("#reply-comment-id").val());
			}
		}),
		dialog_reply : $("#modal-post-comment-reply").modal({
			keyboard : false,
			backdrop : 'static',
			show : false,
		}),
		field : {
			comment_id : $('#modal-post-comment-id'),
			author_name : $('#modal-post-comment-author-name'),
			author_email : $('#modal-post-comment-author-email'),
			author_url : $('#modal-post-comment-author-url'),
			status : $('#modal-post-comment-status'),
			content : $('#modal-post-comment-content'),
		},
		control : {
			'post_comment_add' : $("#post-comment-add").click(function(e){
			  Post.field.comment_id.val('0'),
				Post.field.author_name.val(''),
				Post.field.author_email.val(''),
				Post.field.author_url.val(''),
				Post.field.status.val('approved'),
				Post.field.content.val(''),

				Post.dialog.modal('show');
			}),
			'modal_btn_save' : $("#btn-modal-save-post-comment").click(function(){
				Post.fn.save();
			}),
			'modal_btn_reply' : $("#btn-modal-reply-post-comment").click(function(){
				Post.fn.send_reply();
			}),
			'modal_btn_reply_close' : $("#btn-modal-reply-post-close").click(function(){
				Post.reply_history.pop();

				if (Post.reply_history.length > 0) {
					var r = Post.reply_history[Post.reply_history.length-1];
					Post.fn.load_reply(r)
				}else{
					Post.dialog_reply.modal('hide');
				}

				if (Post.reply_history.length==1) {
					$(this).text("Close");
				}
			}),
		},
		reply_history : [],
		fn : 
		{
			initialize_table_controls : function(t){
				t.find('.btn-post-comment-reply').each(function(e){
					$(this).click(function(){
						Post.fn.get_reply($(this).attr('data-value'));
						Post.dialog_reply.modal('show');
					});
				});
				t.find('.btn-post-comment-edit').each(function(e){
					$(this).click(function(){
						Post.fn.get($(this).attr('data-value'));
					});
				});

				t.find('.btn-post-comment-delete').each(function(e){
					var item = $(this);
					$(this).click(function(){
						bootbox.confirm("Do you continue deleting selected item?", function(result){
							if (result) {
								Post.fn.delete(item.attr('data-value'));
							}
						});
					});
				});

				t.find(".comment-toggle").click(function(){
					var p = $(this).parents('.comment-controls');
					p.siblings('.shorten-content').slideToggle();
					p.siblings('.main-comment').slideToggle();
				})
			},
			nl2br : function(str){
			  return str.replace(/(?:\r\n|\r|\n)/g, '<br>');
			},
			get : function(comment_id){
				$.post(CONFIG.get('URL')+'comments/comment_processor/',{
					action: 'get-comment',
					id: comment_id,
				},
				function(response) {
					if (!cms_function.isJSON(response)) {
						notification("Comment", "Invalid Response", "gritter-error");
						return;
					}

					var post_response = JSON.parse(response);

					if (typeof post_response['id'] =='undefined' || typeof post_response['author_name'] =='undefined' || typeof post_response['author_email'] =='undefined' || typeof post_response['author_url'] =='undefined' || typeof post_response['status'] =='undefined' || typeof post_response['content'] =='undefined') {
						notification("Comment", "Incomplete retrieved Data", "gritter-error");
						return;
					}

					Post.field.comment_id.val(post_response['id']);
					Post.field.author_name.val(post_response['author_name']);
					Post.field.author_email.val(post_response['author_email']);
					Post.field.author_url.val(post_response['author_url']);
					Post.field.status.val(post_response['status']);
					Post.field.content.val(post_response['content']);

					Post.dialog.modal('show');
				}).fail(function(e){
					notification("Comment", "Something went wrong while retrieving comment detail", "gritter-error");
				});
			},
			get_reply : function(comment_id){
				Post.reply_history.push(comment_id);
				Post.fn.load_reply(comment_id)
			},
			load_reply : function(comment_id){

				if (Post.reply_history.length==1) {
					Post.control.modal_btn_reply_close.text('Close')
				}else{
					Post.control.modal_btn_reply_close.text('Back')
				}

				$("#reply-comment-id").val(comment_id);
				$("#reply-container-loading").show();
				$("#reply-container-loading-overlay").show();
				$("#reply-items-container").hide();
				$("#reply-subject").hide();
				$("#reply-container-loading-error").hide();
				$.post(CONFIG.get('URL')+'comments/comment_processor/',{
					action: 'get-comment-reply',
					id: comment_id,
				},
				function(response) {
					$("#reply-container-loading").hide();
					$("#reply-container-loading-overlay").hide();

					if (!cms_function.isJSON(response)) {
						notification("Comment", "Invalid Response", "gritter-error");
						$("#reply-container-loading-error").slideDown();
						return;
					}

					var post_response = JSON.parse(response);
					if (typeof post_response['detail'] =='undefined' || typeof post_response['children'] =='undefined') {
						notification("Comment", "Incomplete retrieved Data", "gritter-error");
						$("#reply-container-loading-error").slideDown();
						return;
					}

					var repsub = $("#reply-subject");
					repsub.find('.content').html(Post.fn.nl2br(post_response['detail']['content']||''));
					repsub.find('.author').html(post_response['detail']['author_name']);
					var repsubitem = repsub.find('.mini-info').html('<p><small class="modified-date">'+"("+ post_response['detail']['date_modified'] +")"+'</small><br /><a href="javascript:void(0)" class="reply-item-view-edit" data-value="'+post_response['detail']['id']+'">Edit</a></p>');
					repsubitem.find('.reply-item-view-edit').click(function(e){
						Post.last_state = 'reply';
						Post.dialog_reply.modal('hide');
						Post.fn.get($(this).attr('data-value'));
					});

					var repitems = $("#reply-items");
					repitems.html('');

					if (post_response['children'].length > 0) {
						$.each(post_response['children'], function(k, v){
							var item = $('#tmpl-comment-reply-item').tmpl({
								'id': v['id'],
								'content': Post.fn.nl2br(v['content']||''),
								'author': v['author_name'],
								'date_modified': v['date_modified'],
								'reply_count': v['children_count'],
							}).appendTo(repitems);

							item.find('.reply-item-view-reply').click(function(e){
								Post.fn.get_reply($(this).attr('data-value'))
							});

							item.find('.reply-item-view-edit').click(function(e){
								Post.last_state = 'reply';
								Post.dialog_reply.modal('hide');
								Post.fn.get($(this).attr('data-value'));
							});
						});
					}else{
						repitems.html('<div class="alert alert-info">No Reply</div>');
					}

					$("#reply-items-container").slideDown();
					$("#reply-subject").slideDown();
				}).fail(function(e){
					notification("Comment", "Something went wrong while retrieving comment detail", "gritter-error");
				});
			},
			save : function(){
				var comment_data = {
					'id' : Post.field.comment_id.val(),
					'post_id' : Post.post_id,
					'author_name' : Post.field.author_name.val(),
					'author_email' : Post.field.author_email.val(),
					'author_url' : Post.field.author_url.val(),
					'status' : Post.field.status.val(),
					'content' : Post.field.content.val(),
				}

				$.post(CONFIG.get('URL')+'comments/comment_processor/',{
					action : 'save',
					data : JSON.stringify(comment_data),
				},
				function(response) {
					if (!cms_function.isJSON(response)) {
						notification("Comment", "Invalid Response. Please contact the administrator to resolve such problem.", "gritter-error");
						return;
					}

					var post_response = JSON.parse(response);

					if (typeof post_response['status'] != 'undefined') {
						if (post_response['status'] == 'success') {
							notification("Comment", post_response['message'], "gritter-success");
							Post.dialog.modal('hide');
							Post.table.dataTable().fnDraw()
						}else{
							notification("Comment", post_response['message'], "gritter-warning");
						}
					}else{
						notification("Comment", "Something went wrong", "gritter-error");
					}
				});
			},
			send_reply : function(){
				$("#reply-container-loading-overlay").show();
				var reply_data = {
					'id' : $("#reply-comment-id").val(),
					'author_name' : $("#reply-comment-author-name").val(),
					'author_email' : $("#reply-comment-author-email").val(),
					'author_url' : $("#reply-comment-author-url").val(),
					'content' : $("#reply-comment-content").val(),
				}

				$.post(CONFIG.get('URL')+'comments/comment_processor/',{
					action : 'reply',
					data : JSON.stringify(reply_data),
				},
				function(response) {
					$("#reply-container-loading-overlay").hide();
					if (!cms_function.isJSON(response)) {
						notification("Comment", "Invalid Response. Please contact the administrator to resolve such problem.", "gritter-error");
						return;
					}

					var post_response = JSON.parse(response);

					if (typeof post_response['status'] != 'undefined') {
						if (post_response['status'] == 'success') {
							notification("Comment", post_response['message'], "gritter-success");
							Post.dialog.modal('hide');
							Post.table.dataTable().fnDraw()

							$("#reply-comment-author-name").val('');
							$("#reply-comment-author-email").val('');
							$("#reply-comment-author-url").val('');
							$("#reply-comment-content").val('');

							Post.fn.load_reply($("#reply-comment-id").val())
						}else{
							notification("Comment", post_response['message'], "gritter-warning");
						}
					}else{
						notification("Comment", "Something went wrong", "gritter-error");
					}
				});
			},
			delete : function(comment_id){
				$.post(CONFIG.get('URL')+'comments/comment_processor/',{
					action: 'delete-comment',
					id: comment_id,
				},
				function(response) {
					if (!cms_function.isJSON(response)) {
						notification("Comment", "Invalid Response", "gritter-error");
						return;
					}

					var post_response = JSON.parse(response);

					if (typeof post_response['status'] =='undefined' || typeof post_response['message'] =='undefined') {
						notification("Comment", "Incomplete retrieved Data", "gritter-error");
						return;
					}
					Post.dialog.modal('hide');
					Post.table.fnDraw();
				}).fail(function(e){
					notification("Comment", "Something went wrong while retrieving comment detail", "gritter-error");
				});
			},
		}
	}
});