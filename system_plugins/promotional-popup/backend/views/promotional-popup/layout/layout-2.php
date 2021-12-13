<?php 
/* 
Layout Name : Floating Sidebar 
Key : layout-custom-2
Description : Default Promotional Popup Layout No. 2
Preview : layout-002.png

*/
?>

<style>
  .promotional-banner-container{
    position: fixed;
    right: 0;
    top: 0;
    z-index: 99999;
    background-color: rgba(0,0,0,0.6);
    font-family: 'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;
  }
  .promotional-main-container{
    position: absolute;
    background-color: rgba(0,0,0,0.5);
    width: 400px;
    top: 50%;
    left: 50%;
    margin-left: -410px;
    margin-top: 10px;
    padding: 10px;
    border-radius: 20px;
  }
  .promotional-banner-content{
    background-color: #ffffff;
    width: 100%;
    height: 100%;
    padding: 30px;
    border-radius: 15px;
    box-sizing: border-box;
  }
  .promotional-banner-content .cms-form-control-wrap{
    margin: 10px 0;
  }
  .promotional-banner-container h4{
    font-size: 20px;
    margin: 10px 0;
  }
  .promotional-banner-container h4>strong{
    color: #1fa200;
    font-size: 30px;
    width: 100%;
  }
  .promotional-banner-container p{
    font-size: 13px;
    line-height: initial;
    margin: 10px 0;
    
  }
  .promotional-banner-container .input{
    font-size: 15px;
    padding: 5px;
  }
  .promotional-banner-container button{
    padding: 10px;
    width: 200px;
    border-radius: 5px;
    
    background: -webkit-gradient(linear, left bottom, left top, from(#32bee3), to(#2fa3dc));
    background: -webkit-linear-gradient(bottom, #32bee3, #2fa3dc);
    background: -o-linear-gradient(bottom, #32bee3, #2fa3dc);
    background: linear-gradient(0deg, #32bee3, #2fa3dc);
  }
  .promotional-popup-close{
    position: absolute;
    right: 0;
    top: 0;
    margin-right: 30px;
    margin-top: 25px;
  }
</style>

<form action="<?php echo URL . "promotional-popup/save_form/" ?>" method="post" class="promotional-banner-container style-1" style="display: none;">
  <div class="promotional-main-container">
    <div class="promotional-banner-content" id="cms-promotional-popup">
      <a href="javascript:void(0)" data-dismiss="promotional-popup" class="promotional-popup-close"><i class="fa fa-close"></i></a>
      <h4><?php echo $promo_settings->content->headline ?></h4>
      <?php echo $promo_settings->content->body ?>

      <div class="cms-form-control-wrap">
        <input type="text" name="firstname" placeholder="Firstname" class="cms-form-control cms-text cms-validates-as-required input input-m">
      </div>
      <div class="cms-form-control-wrap">
        <input type="text" name="lastname" placeholder="Lastname" class="cms-form-control cms-text cms-validates-as-required input input-m">
      </div>
      <div class="cms-form-control-wrap">
        <input type="text" name="email" placeholder="Email" class="cms-form-control cms-text cms-validates-as-required input input-m">
      </div>
      <div class="cms-form-control-wrap">
        <input type="text" name="contact" placeholder="Contact" class="cms-form-control cms-text cms-validates-as-required input input-m">
      </div>
      <hr>
      <div class="cms-form-control-wrap text-center">
        <button class="btn btn-primary" type="submit">Send</button>
      </div>
    </div>
  </div>
</form>

<?php include ROOT . "system_plugins/promotional-popup/backend/views/promotional-popup/layout/promo-layout-script.php"; ?>