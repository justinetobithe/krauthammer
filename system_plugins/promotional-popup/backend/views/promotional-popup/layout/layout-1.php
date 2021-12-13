<?php 
/* 
Layout Name : Floating Footer 
Key : layout-custom-1
Description : Default Promotional Popup Layout No. 1
Preview : layout-001.png

*/
?>

<style>
  .promotional-banner-container{
    position: fixed;
    width: 100%;
    left: 0;
    bottom: 0;
    margin-bottom: 10px;
    z-index: 99999;
    background-color: rgba(0,0,0,0.6);
    font-family: 'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;
  }
  .promotional-main-container{
    position: absolute;
    background-color: rgba(0,0,0,0.5);
    width: 1000px;
    left: 50%;
    bottom: 0;
    margin-left: -500px;
    margin-top: -230px;
    padding: 20px;
    border-radius: 10px;
  }
  .promotional-banner-content{
    background-color: #ffffff;
    width: 100%;
    height: 100%;
    padding: 20px;
    border-radius: 5px;
    box-sizing: border-box;
  }
  .promotional-banner-container h4{
    font-size: 20px;
    margin: 10px 0;
  }
  .promotional-banner-container h4>strong{
    color: #1fa200;
    font-size: 30px;
  }
  .promotional-banner-container p{
    font-size: 13px;
    line-height: initial;
    margin: 10px 0;
    
  }
  .promotional-banner-container .input{
    font-size: 15px;
    padding: 10px;
  }
  .promotional-banner-container button{
    padding: 10px;
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
      <div class="row">
        <div class="col-sm-12">
          <input type="text" name="popip-firstname" placeholder="Firstname" class="input">
          <input type="text" name="popip-email" placeholder="Email" class="input">
          <input type="text" name="popip-contact" placeholder="Contact Number (Required)" class="input">
          <input type="text" name="popip-company" placeholder="Company Name" class="input">
          <button class="btn btn-primary pull-right" type="submit">Submit</button>
        </div>
      </div>
    </div>
  </div>
</form>
<?php 
/*
Required class/elements:
-promotional-banner-container
-promotional-banner-container > button[type=submit]
*/
?>
<?php //cms_popup_script(); ?>
<?php include __DIR__ . "/promo-layout-script.php"; ?>