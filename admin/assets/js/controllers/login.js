
$('#loading_login').hide();
// inline scripts related to this page

        
jQuery('#btnLogin').click(function(e){
    e.preventDefault();
    var error = 0;
    
      if(empty('username')){
       jQuery('div').remove('#error1');
       jQuery('#messageAlert').append(alertMessage('The username field is empty.','error','error1'));
           error+=1;
     }else{
       jQuery('div').remove('#error1');
    }
    
     if(empty('password')){
       jQuery('div').remove('#error2');
       jQuery('#messageAlert').append(alertMessage('The password field is empty.','error','error2'));
           error+=1;
     }else{
       jQuery('div').remove('#error2');
     } 
   
    if(error==0){
      $(this).attr('disabled','disabled');
      $('#key_login').hide();
      $('#loading_login').show();
       jQuery.post(CONFIG.get('URL')+'login/checkLogin',{action:'login',username:getVal('username'),password:getVal('password')},function(response,status){
          $('#loading_login').hide();
          $('#key_login').show();
          $('#btnLogin').removeAttr('disabled');
           if(status=='success'){
             // alert(response);
              eval(response); 
             }
        
       });
    }else{
     setVal('password','');   
    }
    
    
});



