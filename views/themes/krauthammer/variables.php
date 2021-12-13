<?php
$custom_fields = array(
    array(
      "type" => 'text',
      "id" => 'inp-second_customer_name',
      "name" => 'second_customer_name',
      "label" => '2nd Contact Name',
      "placeholder" => '',
      "class" => 'input input-xxlarge',
      "value" => '',
    ),
    array(
      "type" => 'text',
      "id" => 'inp-second_contact_no',
      "name" => 'second_contact_no',
      "label" => '2nd Contact Number',
      "placeholder" => '',
      "class" => 'input input-xxlarge',
      "value" => '',
    ),
    array(
      "type" => 'select',
      "id" => 'inp-delivery_mode',
      "name" => 'delivery_mode',
      "label" => 'Delivery Type',
      "placeholder" => '',
      "class" => 'input form-select',
      "value" => array(
        "self-collection" => 'Self-collection',
        "deliver" => 'Delivery to Home &amp; Others'),
      "selected" => ''
    ),
   array(
      "type" => 'datepicker',
      "id" => 'inp-collection_date',
      "name" => 'collection_date',
      "label" => 'Self-collection Date',
      "placeholder" => '',
      "class" => 'datepicker input input-sm',
      "value" => 'N/A',
    ),
   array(
      "type" => 'text',
      "id" => 'inp-collection_time',
      "name" => 'collection_time',
      "label" => 'Self-collection Time',
      "placeholder" => '',
      "class" => 'input input-large',
      "value" => 'N/A',
    ),
    array(
      "type" => 'datepicker',
      "id" => 'inp-delivery_date',
      "name" => 'delivery_date',
      "label" => 'Delivery Date',
      "placeholder" => '',
      "class" => 'datepicker input input-sm',
      "value" => 'N/A',
    ),
    array(
      "type" => 'select',
      "id" => 'inp-delivery_type',
      "name" => 'delivery_type',
      "label" => 'Type of Delivery',
      "placeholder" => '',
      "class" => 'input form-select',
      "value" => array(
        "" => 'N/A',
        "normal" => 'Normal',
        "express" => 'Express'),
      "selected" => ''
    ),
    array(
      "type" => 'text',
      "id" => 'inp-delivery_time',
      "name" => 'delivery_time',
      "label" => 'Delivery Time',
      "placeholder" => '',
      "class" => 'input input-large',
      "value" => 'N/A',
    ),
    array(
      "type" => 'link',
      "id" => 'print_url_noprice',
      "name" => '',
      "label" =>   'Print Invoice w/o Price URL',
      "placeholder" => '',
      "class" => 'control-link',
      "value" => '',
      "widget_setting" => array(
            "is_widget_nav" => true,
            "widget_nav_label" => "Print Invoice w/o Price ",
            "widget_nav_icon" => "icon-print", 
            "widget_nav_value" => '', 
        ),
    ),
    array(
      "type" => 'link',
      "id" => 'print_url',
      "name" => '',
      "label" => 'Print Invoice URL',
      "placeholder" => '',
      "class" => 'control-link',
      "value" => '',
      "widget_setting" => array(
            "is_widget_nav" => true,
            "widget_nav_label" => "Print Invoice ",
            "widget_nav_icon" => "icon-print", 
            "widget_nav_value" => '', 
        ),
    )
  );


$page_custom_fields = array(
  array(
    "type" => 'text',
    "id" => 'post_category_id',
    "label" => 'Post Category ID',
    "placeholder" => 'Enter Post Category ID',
    "class" => 'input',
    "value" => '',
    "readonly" => false,
    ),
  array(
    "type" => 'select',
    "id" => 'enable-awesome-style',
    "label" => 'Enable Awesome Style',
    "placeholder" => '',
    "class" => 'input form_select',
    "value" => array(
      "enable"=>"Enable",
      "disable"=>"Disable",
      ),
    "selected" => '',
    ),
);