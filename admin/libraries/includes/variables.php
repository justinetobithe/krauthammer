<?php
//ORDER/ENQUIRIES COSTUME FIELDS
$custom_fields = array(
  array(
    "type" => 'text',
    "id" => 'customer_name_2',
    "name" => 'customer_name_2',
    "label" => 'Customer Name 2',
    "placeholder" => 'Enter Secondary Customer Name',
    "class" => 'input input-xxlarge',
    "value" => '',
    "readonly" => false,
    ),
  array(
    "type" => 'text',
    "id" => 'customer_phone_2',
    "name" => 'customer_phone_2',
    "label" => 'Phone 2',
    "placeholder" => 'Enter Secondary Phone Number',
    "class" => 'input input-xxlarge',
    "value" => '',
    ),
  array(
    "type" => 'select',
    "id" => 'delivery_type',
    "name" => 'delivery_type',
    "label" => 'Delivery Type',
    "placeholder" => 'Select Delivery Type',
    "class" => 'input form_select',
    "value" => array(
      "self-collect"=>"Self Collection",
      "deliver-to-home"=>"Deliver to Home",
      "others"=>"Others",
      ),
    "selected" => '',
    ),
  array(
    "type" => 'datepicker',
    "id" => 'delivery_date_2',
    "name" => 'delivery_date_2',
    "label" => 'Delivery Date',
    "placeholder" => 'Enter Delivery Date',
    "class" => 'datepicker input input-sm',
    "value" => '',
    ),
  array(
    "type" => 'timepicker',
    "id" => 'delivery_time_2',
    "name" => 'delivery_time_2',
    "label" => 'Delivery Time',
    "placeholder" => 'Enter Delivery Time',
    "class" => 'timepicker input input-sm',
    "value" => '',
    ),
  array(
    "type" => 'datepicker',
    "id" => 'self_collect_date_2',
    "name" => 'self_collect_date_2',
    "label" => 'Self Collection Date',
    "placeholder" => 'Enter Date to Collect',
    "class" => 'datepicker input input-sm',
    "value" => '',
    ),
  array(
    "type" => 'timepicker',
    "id" => 'self_collect_time_2',
    "name" => 'self_collect_time_2',
    "label" => 'Self Collection Time',
    "placeholder" => 'Enter Time to Collect',
    "class" => 'timepicker input input-sm',
    "value" => '',
    ),
  array(
    "type" => 'link',
    "id" => 'sample_link',
    "name" => 'sample_link',
    "label" => 'Sample Link',
    "link_label" => 'Go to Link Page',
    "placeholder" => '',
    "class" => '',
    "value" => URL,
    "widget_setting" => array(
        "is_widget_nav" => true,
        "widget_nav_label" => "Widget Menu",
        "widget_nav_icon" => "icon-download", 
        "widget_nav_value" => URL . "print/", 
        ),
    ),
);

/*removed the 'name' data*/
$customer_custom_fields = array(
  array(
    "type" => 'text',
    "id" => 'customer_address_2',
    "label" => 'Secodary Address',
    "placeholder" => 'Enter Customer Secondary Address',
    "class" => 'input span12',
    "value" => '',
    "readonly" => false,
    ),
  array(
    "type" => 'select',
    "id" => 'customer_type',
    "label" => 'Customer Type',
    "placeholder" => 'Select Customer Type',
    "class" => 'input form_select span12',
    "value" => array(
      "self-collect"=>"Self Collection",
      "deliver-to-home"=>"Deliver to Home",
      "others"=>"Others",
      ),
    "selected" => '',
    ),
  array(
    "type" => 'datepicker',
    "id" => 'customer_birthdate',
    "label" => 'Self Collection Date',
    "placeholder" => 'Enter Date to Collect',
    "class" => 'datepicker input input-sm',
    "value" => '',
    ),
);

$page_link_format = array(
    "/[url_slug]/",
    "[parent_slug]/[url_slug]/",
);

$post_link_format = array(
    array(
        "value" => "category-postname",
        "label" => "Category and Name",
        "format" => "/[post-category]/[post-name]/",
    ),
    array(
        "value" => "postname",
        "label" => "Name",
        "format" => "/[post-name]/",
    ),
);

/*
* Get the variable file from the selected theme and overrides the variebles above if defined.
*/
$frontend_theme = ACTIVE_THEME;
if (is_file(__DIR__."/../../../views/themes/".$frontend_theme."/variables.php")) {
    include(__DIR__."/../../../views/themes/".$frontend_theme."/variables.php");
}