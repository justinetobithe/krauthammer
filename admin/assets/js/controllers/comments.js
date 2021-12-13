$(document).ready(function(){
  var Post = {
    table : $("#table-comments").dataTable({
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": CONFIG.get('URL') + "comments/table_processor/",
      "aoColumns": [
      {"sWidth": "5%","bSortable": false},
      {"sWidth": "40%", "bSortable": false},
      {"sWidth": "10%", "bSortable": false},
      {"sWidth": "25%", "bSortable": false},
      {"sWidth": "10%", "bSortable": false},
      {"sWidth": "10%", "bSortable": false},
      ], "fnDrawCallback": function(oSettings) {
        $("[data-rel=tooltip]").tooltip();
        Post.fn.btn_edit($(this))
        Post.fn.btn_delete($(this));
        Post.fn.comment_collapse($(this));
      }, "fnServerParams": function(aoData) {
        /*aoData.push({"name": "variable_name", "value": "variable_value"});*/
      }
    }),
    dialog : $("#modal-post-comment").modal({
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
      'modal_btn_save' : $("#btn-modal-save-post-comment"),
    },
    fn : 
    {
      btn_edit : function (btn){
        btn.find('.btn-post-comment-edit').each(function(e){
          $(this).click(function(){
            Post.fn.get($(this).attr('data-value'));
          });
        });
      },
      btn_delete : function (btn){
        btn.find('.btn-post-comment-delete').each(function(e){
          var item = $(this);
          $(this).click(function(){
            bootbox.confirm("Do you continue deleting selected item?", function(result){
              if (result) {
                Post.fn.delete(item.attr('data-value'));
              }
            });
          });
        });
      },
      comment_collapse : function (c){
        c.find(".comment-toggle").click(function(){
          $(this).siblings('.shorten-content').slideToggle();
          $(this).siblings('.main-comment').slideToggle();
        })
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
      save : function(){
        var comment_data = {
          'id' : Post.field.comment_id.val(),
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

  Post.control.modal_btn_save.click(function(){
    Post.fn.save();
  });
});