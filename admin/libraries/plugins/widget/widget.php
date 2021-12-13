<?php

$cms_widget_global_variable = array();
$cms_widget_global_sidebar_variable = array();

function cms_widget_get_components(){
  global $cms_widget_global_variable;
  return $cms_widget_global_variable;
}
function cms_widget_get_sidebars(){
  global $cms_widget_global_sidebar_variable;
  return $cms_widget_global_sidebar_variable;
}

function cms_widget_add_component($components=array()){
  global $cms_widget_global_variable;

  if (count($components)>0) {
    if (isset($components['widget_id'])) {
      $cms_widget_global_variable[$components['widget_id']] = $components;
    }else{
      foreach ($components as $key => $value) {
        if (isset($value['widget_id'])) {
          $cms_widget_global_variable[$value['widget_id']] = $value;
        }
      }
    }
  }
}
function cms_widget_add_sidebar($sidebar=array()){
  global $cms_widget_global_sidebar_variable;
  foreach ($sidebar as $key => $value) {
    if (isset($value['sidebar_id'])) {
      $cms_widget_global_sidebar_variable[$value['sidebar_id']] = $value;
    }
  }
}
function cms_widget_set_sidebar($sidebar=array()){
  global $cms_widget_global_sidebar_variable;
  $cms_widget_global_sidebar_variable = array();
  foreach ($sidebar as $key => $value) {
    if (isset($value['sidebar_id'])) {
      $cms_widget_global_sidebar_variable[$value['sidebar_id']] = $value;
    }
  }
}


/* Predefined Function */
function initialize_widget(){
  $active_theme = get_system_option("frontend_theme");

  /* Gather Defined Widgets */
  /* -Get defiend default widgets*/
  if (is_file(__DIR__ . "/widget-defaults.php")) {
    include __DIR__ . "/widget-defaults.php";
  }
  /* -Get defiend widget from CMS theme*/
  if (is_file(__DIR__ . "/../../../../views/themes/{$active_theme}/cms-widget.php")) {
    include __DIR__ . "/../../../../views/themes/{$active_theme}/cms-widget.php";
  }
}

function get_widget_sidebar($sidebar_id=''){
  global $cms_widget_global_sidebar_variable;
  global $cms_widget_global_variable;

  if ($sidebar_id != '' && check_widget_sidebar($sidebar_id)) {
    $sidebar_info = get_widget_sidebar_info($sidebar_id);

    $widget_output  = '';
    $widget_output .= '<div class="widget">';
    foreach ($sidebar_info['sidebar_items'] as $key => $value) {
      if (isset($value->fields)) {
        $widget_output .= get_widget_sidebar_item_values($value);
      }
    }
    $widget_output .= '</div>';
  }

  print_r($widget_output);
}
function get_widget_sidebar_item_values($fields_value){
  global $cms_widget_global_variable;
  $widget_output  = '';

  if (isset($fields_value->widget_id) && isset($cms_widget_global_variable[$fields_value->widget_id]['widget_function'])) {
    $widget_function_name_trimmed = str_replace("-", "_", $cms_widget_global_variable[$fields_value->widget_id]['widget_function']);

    if (function_exists("widget_processor_" . $widget_function_name_trimmed)) {
      $_result = call_user_func("widget_processor_" . $widget_function_name_trimmed, $fields_value->fields);
      $widget_output .= $_result!='' ? '<div class="post-preview">' . $_result . '</div><hr>' : '';
    }
  }
  return $widget_output;
}

function check_widget_sidebar($sidebar_id=''){
  global $cms_widget_global_sidebar_variable;
  return isset($cms_widget_global_sidebar_variable[$sidebar_id]);
}
function get_widget_sidebar_info($sidebar_id=''){
  global $cms_widget_global_sidebar_variable;
  global $cms_widget_global_variable;
  global $db;

  if ($sidebar_id != '') {
    if (isset($cms_widget_global_sidebar_variable[$sidebar_id])) {
      $value = $cms_widget_global_sidebar_variable[$sidebar_id];
      $sidebar_items = $db->select("Select * From cms_items Where value = '{$value['sidebar_id']}'");
      $sidebar_item_temp = array();
      foreach ($sidebar_items as $sidebar_items_key => $sidebar_items_value) {
        $_temp = json_decode($sidebar_items_value->meta);
        $_temp->item_id = $sidebar_items_value->id;

        if (isset($cms_widget_global_variable[$_temp->widget_id]['widget_name'])) {
          $_temp->item_name = $cms_widget_global_variable[$_temp->widget_id]['widget_name'];
        }


        $sidebar_item_temp[intval($_temp->order)] = $_temp; /* Used the order value as key to sort the widget items per Sidebar */
      }

      ksort($sidebar_item_temp);

      $value['sidebar_items'] = $sidebar_item_temp;
      return $value;
    }
  }

  return array();
}