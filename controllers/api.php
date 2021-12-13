<?php

include ROOT . "admin/libraries/plugins/cms-api/cms-paypal.php";

class Api extends Controller {
  private $api_directory;
  function __construct() {
    parent::__construct();
    $this->api_directory = ROOT . "libraries/api/";
  } 

  function index(){
    $this->model->paypal_base_url = "https://api.sandbox.paypal.com";
  }

  function __other($others = ""){
    $url = $others;
    $controller = array_shift($url);
    $api_plugin = array_shift($url);
    $func_name  = array_shift($url);

    $parameter  = array_map(function($s){
      return "'". trim($s) ."'";
    }, $url);
    $param_string = implode(', ', $parameter);

    $t = explode('-', $api_plugin);
    $t = array_map(function($s){
      return ucfirst(trim($s));
    }, $t);
    $api_class = "API_" . implode('', $t);

    if ($api_plugin && is_dir("{$this->api_directory}{$api_plugin}/")) {
      if (is_file("{$this->api_directory}{$api_plugin}/index.php")) {
        include "{$this->api_directory}{$api_plugin}/index.php";
        $api = new $api_class();
        $string_function_call = "\$api->index({$param_string});";

        if ($func_name && $func_name != "") {
          $fn = str_replace('-', '_', $func_name);
          $string_function_call = "\$api->{$fn}({$param_string});";
        }

        eval( $string_function_call );
      }else{
        echo "No Default file";
      }
    }else{
      echo "Invalid API";
    }
  }

  function session($encode=""){
    /* This is to get the current session values */
    header_json();
    if ($encode == 'encode') {
      print_r(json_encode($_SESSION));
    }else{
      print_r($_SESSION);
    }
  }
  private function add_order(){}
}