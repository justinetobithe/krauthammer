var globalError = false;
var edit_username = '';
var edit_email = '';

var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
$(document).ready(function(){
  var action = $('#action').val();

  if(action == 'add_user'){
    addForm();
  }
  else if(action == "manage_user"){
    loadUsers();
  }
  else if(action == "edit_user"){
    edit_username = $('#user_username').val();
    edit_email = $('#user_email').val();
    var role = $('#hdn_role').val();
    $('#role').val(role);
    editForm();
  }
  $('#username').keyup(function(){
    if(edit_username!=$(this).val()){
      checkUsername($(this).val());
    }
  });
  $('#email').keyup(function(){

    if(edit_email != $(this).val()){
      checkEmail($(this).val());
    }
  });
  $("#password-container-toggle-on").click(function(){
    $("#password-container").slideDown('fast');
    $("#password-container-toggle-off").show();
    $(this).hide();
  });
  $("#password-container-toggle-off").click(function(){
    $("#password-container").slideUp('fast');
    $("#password-container-toggle-on").show();
    $("#password").val("password!");
    $("#con_password").val("password!");
    $(this).hide();
  });
  $("#role").chosen()
  .siblings(".chosen-container")
  .find(".chosen-search")
  .remove();


  $('#user-profile-picture').ace_file_input({
    no_file: 'No File ...',
    btn_choose: 'Choose',
    btn_change: 'Change',
    droppable: false,
    onchange: null,
    thumbnail: false, //| true | large
    before_remove:function() {
      //if(upload_in_progress) return false;//don't allow resetting the file input while upload in progress
      $("#user-profile-pic-preview").attr('src', $("#user-profile-pic-preview").attr('alt'));
      return true;
    }
    //whitelist:'gif|png|jpg|jpeg'
    //blacklist:'exe|php'
    //onchange:''
    //
  })
  .change(function(e){
    var f = $(this)[0];
    if (f.files.length == 1) {
      var img_url = URL.createObjectURL(f.files[0]);
      cms_function.fn.image_loader($("#user-profile-pic-preview"), img_url)
      $("#user-profile-pic-preview").attr('src', img_url);
    }
  });
});

function editForm(){
  $('#edit_user_form').ajaxForm({
    beforeSend: function(){
      $('#close_button').addClass('hide');
      $('#continue_button').addClass('hide');
      $('#product_loading').removeClass('hide');
      $('#loading_msg_error').addClass('hide');
      $('#loading_msg_success').addClass('hide');
      $('#modal_footer').addClass('hide');
      $("#loading").modal({
        backdrop : 'static',
        keyboard: false
      });
    },
    complete: function(xhr){

      var result = xhr.responseText;

      if(result == "0"){
        $('#close_button').removeClass('hide');
        $('#modal_footer').removeClass('hide');
        $('#loading_msg_error').removeClass('hide');
        $('#product_loading').addClass('hide');
        $('#close_button').addClass('btn-danger');
        setTimeout(function(){
          $("#loading").modal('hide');
        },5000);
      }
      else{
        $('#modal_footer').removeClass('hide');
        $('#continue_button').removeClass('hide');
        $('#loading_msg_success').removeClass('hide');
        $('#product_loading').addClass('hide');
        $('#close_button').addClass('btn-success');

        if ($("input[name=user_id]").length <= 0 ) {
          setTimeout(function(){
            window.location.href = CONFIG.get('URL')+'users/';
          }, 3000);
        }else{
          
          setTimeout(function(){
            window.location.reload();
            $("#loading").modal('hide');
          },1000);
        }
      }
    }
  });
}
function loadUsers(){
  $('#userTable').dataTable( {
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": CONFIG.get('URL')+"users/table_processor",
    "aoColumns": [
      { "bSortable": false }, 
      null,
      null,
      null,
      null,
      { "bSortable": false},
    ], "fnDrawCallback": function( oSettings ) {
      $(this).find(".btn-edit").click(function(){
        editUser( $(this).attr("data-value") );
      });
      $(this).find(".btn-delete").click(function(){
        deleteUser( $(this).attr("data-value") );
      });
    }, "fnServerParams": function(aoData) {
      // aoData.push({"name": "product_category", "value": $("#product-category_filter").val()});
    }
  });
}
function deleteUserModal(){
  var id = $('#hidden_user_id').val();

  jQuery.post(CONFIG.get('URL')+'users/delete',{action:'user', id:id}, function(response, status){
    var result = JSON.parse(response)
    if(result == '1')
      window.location.href = CONFIG.get('URL')+'users/';
    else
    {
      $('#delete_msg h5').text("Unable to Delete User");
      $('#model_footer').hide();
      setTimeout(function(){
        $('#delete').modal('hide');
        window.location.href = CONFIG.get('URL')+'users/';
      },5000);
    }
  });
}
function deleteUser(id){
  $('#hidden_user_id').val(id);
  $('#delete_msg h5').text("Are you sure to delete this user?");
  $('#delete').modal('show');
}
function editUser(id){
  window.location.href=CONFIG.get('URL')+"users/edit/"+id;
}
function checkUsername(i){
  jQuery('div').remove('#errorUsername');
  jQuery.post(CONFIG.get('URL')+'users/get',{action:'username', i:i}, function(response, status){
    result = JSON.parse(response);

    if(result > 0){
      jQuery('#alertUser').append(alertMessage('Username is already in the database','error','errorUsername'));
      globalError = true;
    }
    else{
      globalError = false;
    }
  });
}
function checkEmail(i){
  jQuery('div').remove('#errorEmail');
  jQuery.post(CONFIG.get('URL')+'users/get',{action:'email', i:i}, function(response, status){
    result = JSON.parse(response);
    if(result > 0)
    {
      jQuery('#alertUser').append(alertMessage('Email is already in the database','error','errorEmail'));
      globalError = true;
    }
    else
      globalError = false;
  });
}
function addForm(){
  $('#add_user_form').ajaxForm({
    beforeSend: function(){
      $('#close_button').addClass('hide');
      $('#continue_button').addClass('hide');
      $('#product_loading').removeClass('hide');
      $('#loading_msg_error').addClass('hide');
      $('#loading_msg_success').addClass('hide');
      $('#modal_footer').addClass('hide');
      $("#loading").modal({
        backdrop : 'static',
        keyboard: false
      });
    },
    complete: function(xhr){
      var result = xhr.responseText;
      if(result == '1'){
        $('#modal_footer').removeClass('hide');
        $('#continue_button').removeClass('hide');
        $('#loading_msg_success').removeClass('hide');
        $('#product_loading').addClass('hide');
        $('#close_button').addClass('btn-success');

        setTimeout(function(){
          window.location.href = CONFIG.get('URL')+'users/';
        },5000);

      }
      else{
        $('#close_button').removeClass('hide');
        $('#modal_footer').removeClass('hide');
        $('#loading_msg_error').removeClass('hide');
        $('#product_loading').addClass('hide');
        $('#close_button').addClass('btn-danger');
        setTimeout(function(){
          $("#loading").modal('hide');
        },5000);
      }
    }
  });
}
function validateFormUser(){

  var error = false;

  var email = $('#email').val();
  var full_name = $('#full_name').val();
  var con_password = $('#con_password').val();
  var password = $('#password').val();
  var username = $('#username').val();
  var role = $('#role').val();

  jQuery('div').remove('#errorEmail');
  jQuery('div').remove('#errorFull_Name');
  jQuery('div').remove('#errorPassword');
  jQuery('div').remove('#errorUsername');
  jQuery('div').remove('#errorRole');

  if(email == ''){
    jQuery('#alertUser').append(alertMessage('Please Fill Email','error','errorEmail'));
    error = true;
  }
  else{
    if(!regexEmail.test(email)){
      jQuery('#alertUser').append(alertMessage('Invalid Email','error','errorEmail'));
      error = true;
    }
  }
  if(full_name == ''){
    jQuery('#alertUser').append(alertMessage('Please Fill Full Name','error','errorFull_Name'));
    error = true;
  }
  if(password == ''){
    jQuery('#alertUser').append(alertMessage('Please Fill Password','error','errorPassword'));
    error = true;
  }
  if(username == ''){
    jQuery('#alertUser').append(alertMessage('Please Fill Username','error','errorUsername'));
    error = true;
  }
  if(role == 'none'){
    jQuery('#alertUser').append(alertMessage('Please Select User Role','error','errorRole'));
    error = true;
  }
  if(password != con_password){
    jQuery('#alertUser').append(alertMessage('Wrong Password','error','errorPassword'));
    error = true;
  }

  if(error || globalError)
    return false;

  return true;
}
function closeModal(){
  window.location = CONFIG.get('URL')+'users';
}