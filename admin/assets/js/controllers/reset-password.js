
$(document).ready(function(){
  reset_code = $('#reset_code').val();
  //alert(reset_code);
  $('#loading_reset').hide();
  $('#btn_reset_password').click(function(){
    resetPassword($('#txt_password').val(),$('#txt_confirm_password').val());
  });
});

function resetPassword(password,conpass)
{
  if(password==conpass)
  {
    $('#btn_reset_password').attr('disabled','disabled');
    $('#key_reset').hide();
    $('#loading_reset').show();
    jQuery.post(CONFIG.get('URL')+'reset-password/resetPassword', {action:'reset_password',password:password, reset_code: reset_code},function(response,status){
        
           if(status=='success'){
              $('#loading_reset').hide();
              $('#key_reset').show();
              $('#btn_reset_password').removeAttr('disabled');
              eval(response); 
             }

        
       });
  }
  else
    jQuery('#messageAlert').append(alertMessage('Check your password','error','error1'));
}



