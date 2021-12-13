$(document).ready(function(){
  $("#btn-save-contact-form-setting").click(function(){
    var data = {
      "key"     : $("#contact-form-key").val(),
      "secret"  : $("#contact-form-secret").val(),
    }

    $.post(CONFIG.get('URL')+'settings/contact_form_processor/',{
      action : "save",
      data : JSON.stringify(data),
    },
    function(response) {
      response = JSON.parse(response);
      if (response['status']||'error'=='ok') {
        notification("Contact Form", "Contact Form Setting Saved", "gritter-success");
      }else{
        notification("Contact Form", "Contact Form Setting Saved", "gritter-error");
      }
    });
  });
});