<?php

/* CMS ACTIVE FUNTIONS */
function cms_promotional_popup(){
  $db = new Database();
  $ps = $db->select("SELECT * FROM `system_options` WHERE `system_options`.`option_name` = 'promotional-popup-settings'");
  $ps = count($ps) ? $ps : null;

  $active_menu_list = array();

  /* Retrieving Template Start */
  $theme = ACTIVE_THEME;
  $templates = array();
  $directory = ROOT . "system_plugins/promotional-popup/backend/views/promotional-popup/layout/";
  if (is_dir($directory)) {
    $files = scandir($directory);

    foreach ($files as $key => $file) {
      $path = "{$directory}{$file}";
      if (is_file($path) && strpos($path,'.php')) {

        if (strpos(file_get_contents($path),'Layout Name') !== false) {
          $file_content = file_get_contents($path);
          preg_match_all("/(.*):(.*)/", $file_content, $info);

          $tmp = array(
            "name"  => '',
            "desc"  => '',
            "prev"  => '',
            "key"   => '',
            "path"  => '',
          );

          for ($i=0; $i < count($info[0]); $i++) { 
            $lbl = trim($info[1][$i]);
            $val = trim($info[2][$i]);

            if ($lbl == 'Layout Name') {
              $tmp['name'] = $val;
              $tmp['path'] = $path;
            }
            if ($lbl == 'Description') {
              $tmp['desc'] = $val;
            }
            if ($lbl == 'Preview') {
              $tmp['prev'] = URL . "system_plugins/promotional-popup/backend/assets/image/" . $val;
            }
            if ($lbl == 'Key') {
              $tmp['key'] = $val;
            }
          }
          
          $templates[]  = $tmp;
          $active_menu_list[$tmp['key']] = $tmp['path'];
        }
      }
    }
  }

  $directory = ROOT . "views/themes/{$theme}/promotional-popup/layout/";
  if (is_dir($directory)) {
    $files = scandir($directory);
    foreach ($files as $key => $file) {
      $path = "{$directory}{$file}";
      if (is_file($path) && strpos($path,'.php')) {

        if (strpos(file_get_contents($path),'Layout Name') !== false) {
          $file_content = file_get_contents($path);
          preg_match_all("/(.*):(.*)/", $file_content, $info);

          $tmp = array(
            "name"  => '',
            "desc"  => '',
            "prev"  => '',
            "key"   => '',
            "path"  => '',
          );

          for ($i=0; $i < count($info[0]); $i++) { 
            $lbl = trim($info[1][$i]);
            $val = trim($info[2][$i]);

            if ($lbl == 'Layout Name') {
              $tmp['name'] = $val;
              $tmp['path'] = $path;
            }
            if ($lbl == 'Description') {
              $tmp['desc'] = $val;
            }
            if ($lbl == 'Preview') {
              $tmp['prev'] = $directory . "img/" . $val;
            }
            if ($lbl == 'Key') {
              $tmp['key'] = $val;
            }
          }
          
          $templates[]  = $tmp;
          $active_menu_list[$tmp['key']] = $tmp['path'];
        }
      }
    }
  }
  
  /* Retrieving Template End*/

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

    $promo_settings->shown = $shown = isset($_COOKIE['promotional-popup-shown']) ? $_COOKIE['promotional-popup-shown'] : false;;


    $layout = $promo_settings->layout->template;

    if (isset($active_menu_list[$layout]) && is_file($active_menu_list[$layout])) {
      require_once $active_menu_list[$layout];
    }


    if (isset($promo_settings->layout->enables) && $promo_settings->layout->enable == "Y") {
      if (isset($_SESSION['user_role'])) {
        /* Edit Mode */
      }

      if (isset($_GET['layout']) && isset($active_menu_list[$_GET['layout']])) {
        include $active_menu_list[$_GET['layout']];
      }elseif (isset( $active_menu_list[$promo_settings->layout->template] )) {
        include $active_menu_list[$promo_settings->layout->template];
      }
    };
  }
}