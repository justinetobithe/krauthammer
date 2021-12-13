

$(document).ready(function(){
  $('#loading_forgot_password').hide();
	$('#btn_forgot_password').click(function(){sendResetPassword($('#txt_email').val());});
});

function sendResetPassword(email){

	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var error = false;
	if(!regex.test(email))
	{
	   jQuery('div').remove('#error1');
       jQuery('#messageAlert').append(alertMessage('Invalid Email Address','error','error1'));
       error = true;
	}
	
    if(email == '')
    {
       jQuery('div').remove('#error1');
       jQuery('#messageAlert').append(alertMessage('Empty Email Address','error','error1'));
       error = true;
    }

    if(!error)
    {
    	jQuery.post(CONFIG.get('URL')+'forgot-password/sendResetPassword',{action:'forgot_password',email:email},function(response,status){
           $('#btn_forgot_password').attr('disabled','disabled');
           $('#icon_forgot_password').hide();
           $('#loading_forgot_password').show();
           if(status=='success'){
              //alert(response); 
              $('#loading_forgot_password').hide();
              $('#icon_forgot_password').show();
              $('#btn_forgot_password').removeAttr('disabled');
              alert(response);
              eval(response);
             }
           else
           {
              $('#loading_forgot_password').hide();
              $('#icon_forgot_password').show();
              $('#btn_forgot_password').removeAttr('disabled');
           	  jQuery('div').remove('#error1');
       		    jQuery('#messageAlert').append(alertMessage('Invalid Email Address','error','error1'));
       	   }
        
       });
    }

}


