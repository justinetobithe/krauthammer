<?php


class Shipping extends Controller{
  public $message = array();

  function __construct(){
    parent::__construct();
    Session::handleLogin();


    $this->message = array(
      "Successfully updated Shipping Default Rate",
      "Unable to update Shipping Default Rate",
      "Unable Save",
    );
  }

  function index(){
    $countries = $this->model->get_countries();

    $this->view->set('countries', $countries);
    $this->view->render('header');
    $this->view->render('shipping/index');
    $this->view->render('footer');
  }

  function area($para1 = 0, $para2 = 0){
    if ((string)$para1 == "add") {
      $countries = $this->model->get_countries();
      $all_selected_countries = $this->model->get_countries_with_assigned_area();
      $disabled_countries = array();
      foreach ($all_selected_countries as $key => $value) {
        $disabled_countries[] = $value->country_id;
      }

      $this->view->set('countries', $countries);
      $this->view->set('disabled_countries', $disabled_countries);
      $this->view->render('header');
      $this->view->render('shipping/shipping-area');
      $this->view->render('footer');
    }else if ((string)$para1 == "edit") {
      $id = $para2;

      $shipping_area_detail = $this->model->get_shipping_area($id);
      if (count($shipping_area_detail) <= 0) { $this->view->errorPage(); exit(); }

      $all_selected_countries = $this->model->get_countries_with_assigned_area( $id );
      $countries = $this->model->get_countries();

      $selected_countries = array();
      foreach ($shipping_area_detail['countries'] as $key => $value) {
        $selected_countries[] = $value->country_id;
      }
      $disabled_countries = array();
      foreach ($all_selected_countries as $key => $value) {
        $disabled_countries[] = $value->country_id;
      }

      $this->view->set('shipping_area_detail', $shipping_area_detail);
      $this->view->set('selected_countries', implode(",", $selected_countries));
      $this->view->set('disabled_countries', $disabled_countries);
      $this->view->set('countries', $countries);

      $this->view->render('header');
      $this->view->render('shipping/shipping-area-edit');
      $this->view->render('footer');
    }else{
      header("Location: " . URL . "shipping/");
    }
  }

  function ajax_shipping_origin_processor(){
    if (isPost('operation')) {
      if (post('operation')=='update') {
        $shipping_data = array(
          "shipping_origin_name" => isPost('shipping_origin_name') ? post('shipping_origin_name') : "",
          "shipping_origin_address_1" => isPost('shipping_origin_address_1') ? post('shipping_origin_address_1') : "",
          "shipping_origin_address_2" => isPost('shipping_origin_address_2') ? post('shipping_origin_address_2') : "",
          "shipping_origin_city" => isPost('shipping_origin_city') ? post('shipping_origin_city') : "",
          "shipping_origin_postal" => isPost('shipping_origin_postal') ? post('shipping_origin_postal') : "",
          "shipping_origin_country" => isPost('shipping_origin_country') ? post('shipping_origin_country') : "",
          "shipping_origin_phone" => isPost('shipping_origin_phone') ? post('shipping_origin_phone') : "",
          );
        if ($this->model->updateShippingOriginAddress($shipping_data)){
          echo json_encode(array("status"=>"saved", 'message'=>$this->message[0]));
        }else{
          echo json_encode(array("status"=>"error", 'message'=>$this->message[1]));
        }
      }elseif (post('operation')=='save-default-rate') {
        $current_rate = $this->model->db->select("Select * From `shipping_rates` Where `rate_type` = 'default'");
        $json_data  = json_decode(post('data'));
        $output  = array(
          "status" => 'error',
          "message" => $this->message[2],
        );

        if ( count($current_rate) > 0 ) {
          $current_rate = $current_rate[0];
          $data = array(
            "id" => $current_rate->id,
            "area_id" => "0",
            "rate_name" => $json_data->rate_name,
            "rate_type" => "default",
            "rate_amount" => $json_data->rate_amount,
          );
          $this->model->db->data = $data;
          $this->model->db->table = 'shipping_rates';
          if ($this->model->db->update()) {
            $output = array(
              "status" => 'saved',
              "message" => $this->message[0],
            );
          }
        }else{
          $data = array(
            "area_id" => "0",
            "rate_name" => $json_data->rate_name,
            "rate_type" => "default",
            "rate_amount" => $json_data->rate_amount,
          );
          
          $this->model->db->data  = $data;
          $this->model->db->table = 'shipping_rates';
          if ($this->model->db->insertGetId()) {
            $output = array(
              "status" => 'saved',
              "message" => $this->message[0],
            );
          }
        }

        echo json_encode($output);
      }elseif (post('operation')=='get-default-rate') {
        $rate = $this->model->db->select("Select * From `shipping_rates` Where `rate_type` = 'default'");
        $rate_output = array(
          "rate_name"   => "",
          "rate_amount" => 0,
        );

        if (count($rate) > 0) {
          $rate = $rate[0];

          $rate_output = array(
            "rate_name"   => $rate->rate_name,
            "rate_amount" => $rate->rate_amount,
          );
        }

        header_json(); echo json_encode($rate_output);
      }elseif (post('operation')=='get') {
        $shipping_origin_result = $this->model->getShippingOriginAddress();
        $shipping_area = array();
        foreach ($shipping_origin_result as $key => $value) {
          $shipping_area[$value->option_name] = $value->option_value;
          if ( $value->option_name == 'shipping_origin_country') {
            $country_result = $this->model->db->select("Select * From `countries` Where `value` = '{$value->option_value}'");
            $country_result = count($country_result) ? $country_result[0] : array("id"=>0, "name"=>"Unknown", "value"=>0, "code"=>"unknown", "meta"=>"");
            $shipping_area["shipping_origin_country_detail"] = $country_result;
          }
        }

        header_json(); echo json_encode($shipping_area);
      }
    }
  }
  function ajax_shipping_rate_processor(){
    if (isPost('operation')) {
      if (post('operation')=='get') {
        $rates = $this->model->getShippingRates();
        $options = $this->model->getShippingRateOptions();
        $shipping_default = $this->model->getDefaultShippingRate();

        $output = array(
          'default' => $shipping_default,
          'rates' => $rates,
          'options' => $options,
        );

        header_json(); echo json_encode($output);
      }
    }
  }
  function ajax_shipping_area_processor(){
    if (isPost('operation')) {
      if (post('operation')=='add') {
        if (isPost('data')) {
          $json_data = json_decode(post('data'));

          $area_id = isset($json_data->area_id) && $json_data->area_id != '0' ? $json_data->area_id : 0;
          $result_shipping_countries = array();
          $result_shipping_rates = array();

          $result_shipping_area = $this->model->updateShippingArea($area_id, $json_data->area_name); //Insert/Save Shipping Name
          if ($area_id != 0) { $result_shipping_area = $area_id; }

          if ($result_shipping_area != 0) {
            $this->model->db->query("DELETE FROM `shipping_rates` WHERE `area_id` = '{$result_shipping_area}'; ");

            $result_shipping_countries = $this->model->updateShippingAreaCountries($json_data->area_countries, $result_shipping_area); //Countries
            $result_shipping_rates_price = $this->model->updateShippingAreaRates($json_data->area_price_base, $result_shipping_area); //Price Base Rate
            $result_shipping_rates_weight = $this->model->updateShippingAreaRates($json_data->area_weight_base, $result_shipping_area); //Weight Base Rate
            $result_shipping_other_method = $this->model->updateShippingAreaRates($json_data->area_other_method, $result_shipping_area); //Weight Base Rate
          }

          header_json(); echo json_encode($result_shipping_area);
        };
      }
    }
  }
  function ajax_shipping_option_processor(){
    if (isPost('operation')) {
      if (post('operation')=='set') {
        $data = post('data');
        $accepted_option = array('enable');

        /* filter out option */
        foreach ($data as $key => $value) {
          if (!in_array($key, $accepted_option)) {
            unset($data[$key]);
          }
        }

        $s_ctr = 0;
        $f_ctr = 0;
        foreach ($data as $key => $value) {
          $qry_str = "INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('shipping_rate_{$key}', '{$value}', '', 'yes') ON DUPLICATE KEY UPDATE `option_value`='{$value}'";

          if ($this->model->db->query( $qry_str )) {
            $s_ctr++;
          }else{
            $f_ctr++;
          }
        }

        $output = array(
          'success' =>  $s_ctr, 
          'fail' =>  $f_ctr, 
        );

        header_json(); echo json_encode($output);
      }
    }
  }
}