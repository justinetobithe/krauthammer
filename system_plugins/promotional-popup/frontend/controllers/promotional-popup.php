<?php
/* PromotioanPopup class: will handle the events of frontend promotional popup */
/* user for ajax processess */
/* url: /promotional-popup/ */
class PromotionalPopup extends Controller{
  function __construct(){
    parent::__construct();
  }

  function index(){
    
  }
  function getpromotionalpopupsettings(){
    $db = new Database();
    $ps = $db->select("SELECT * FROM `system_options` WHERE `system_options`.`option_name` = 'promotional-popup-settings'");
    $ps = count($ps) ? $ps : null;

    $active_menu_list = array(
      "layout-custom-1" => ROOT . "system_plugins/promotional-popup/backend/views/promotional-popup/layout/layout-1.php",
      "layout-custom-2" => ROOT . "system_plugins/promotional-popup/backend/views/promotional-popup/layout/layout-2.php",
    );

    if ($ps) {
      $promo_settings = json_decode($ps[0]->meta_data);
      
      $promo_settings->layout->enable   = isset($promo_settings->layout->enable)   ? $promo_settings->layout->enable   : "Y"; //Enable default
      $promo_settings->layout->template = isset($promo_settings->layout->template) ? $promo_settings->layout->template : "";

      $promo_settings->timing->type    = isset($promo_settings->timing->type)    ? $promo_settings->timing->type    : "page-first";
      $promo_settings->timing->pages   = isset($promo_settings->timing->pages)   ? $promo_settings->timing->pages   : null;
      $promo_settings->timing->trigger = isset($promo_settings->timing->trigger) ? $promo_settings->timing->trigger : "timing-time";
      $promo_settings->timing->freq    = isset($promo_settings->timing->freq)    ? $promo_settings->timing->freq    : "frequency-day-next";
      $promo_settings->timing->signup  = isset($promo_settings->timing->signup)  ? $promo_settings->timing->signup  : "Y";
      $promo_settings->timing->mobile  = isset($promo_settings->timing->mobile)  ? $promo_settings->timing->mobile  : "Y";
      $promo_settings->timing->time    = isset($promo_settings->timing->time)    ? ((int) $promo_settings->timing->time * 1000)    : "0"; //Immediate show popup
      $promo_settings->timing->scroll  = isset($promo_settings->timing->scroll)  ? $promo_settings->timing->scroll  : "25"; //default scroll percentage
      $promo_settings->date_modified = isset($promo_settings->date_modified)  ? $promo_settings->date_modified  : date("Y-m-d H:i:s"); //default scroll percentage

      $layout = $promo_settings->layout->template;
    }

    return $promo_settings;
  }
  function save_form(){
    if ($_POST) {
      $form_data = array();
      foreach ($_POST as $key => $value) {
        $form_data[$key] = $value;
      }

      $data = array(
        "form_id" => 0, 
        "type" => "promotional", 
        "form_data" => json_encode($form_data), 
      );

      $db = new Database();
      $db->table = "contact_form_7_forms_collected_data";
      $db->data = $data;

      if ($db->insertGetID()) {
        echo "1";
      }else{
        echo "0";
      }
    }
  }
}