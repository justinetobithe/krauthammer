<?php

class Shipping_Model extends Model{
	public function __construct(){
		parent::__construct();
		$this->db->table='';
	}

	function get_countries(){
		$sql = "SELECT * FROM `countries`";
		$result = $this->db->select( $sql );

		$options = array();

		foreach ($result as $key => $value) {
			$options[$value->meta][] = $value;
		}
		return $options;
	}
	function get_shipping_area($id=0){
		$shipping_area_detail = array();

		$sql_detail = "SELECT * FROM `shipping_areas` Where `id` = '{$id}'";
		$result_detail = $this->db->select( $sql_detail );

		if (count($result_detail) <= 0) { return array(); }

		$shipping_area_detail['detail'] = $result_detail[0];

		$sql_country = "SELECT * FROM `shipping_country` Where `area` = '{$id}'";
		$shipping_area_detail['countries'] = $this->db->select( $sql_country );

		$sql_rate = "SELECT * FROM `shipping_rates` Where `area_id` = '{$id}' and `rate_type` = 'price-base'";
		$shipping_area_detail['price-rates'] = $this->db->select( $sql_rate );

		$sql_rate = "SELECT * FROM `shipping_rates` Where `area_id` = '{$id}' and `rate_type` = 'weight-base'";
		$shipping_area_detail['weight-rates'] = $this->db->select( $sql_rate );

		$sql_rate = "SELECT * FROM `shipping_rates` Where `area_id` = '{$id}' and `rate_type` = 'other-method'";
		$shipping_area_detail['other-method'] = $this->db->select( $sql_rate );

		return $shipping_area_detail;
	}
	function get_countries_with_assigned_area($area_exeption=0){
		$sql_country = "SELECT * FROM `shipping_country`";
		if ($area_exeption!=0) {
			$sql_country = "SELECT * FROM `shipping_country` Where `area` <> '{$area_exeption}'";
		}
		$countries_with_area = $this->db->select( $sql_country );
		return $countries_with_area;
	}
	function updateShippingOriginAddress($shipping = array()){
		foreach ($shipping as $key => $value) {
			$this->db->query("INSERT INTO `system_options` (`option_name`, `option_value`) VALUES('{$key}', '{$value}') ON DUPLICATE KEY UPDATE `option_value`='{$value}'");
		}
		return true;
	}
	function updateShippingArea($id, $name){
		return $this->db->insert_returl_ID("INSERT INTO `shipping_areas` (`id`, `area_name`) VALUES('{$id}', '{$name}') ON DUPLICATE KEY UPDATE `area_name`='{$name}'");
	}
	function updateShippingAreaCountries($countries=array(), $area=0){
		$countries_result = array();
    if ($countries) {
      foreach ($countries as $key => $value) {
        $id= isset($value->id) ? $value->id : $value;
        $value = isset($value->country) ? $value->country : $value;
        $countries_result[] = $this->db->insert_returl_ID("INSERT INTO `shipping_country` (`area`, `country_id`) VALUES('{$area}','{$value}') ON DUPLICATE KEY UPDATE `area`='{$area}',`country_id`='{$value}'");
      }
    }
    return $countries_result;
	}
	function updateShippingAreaRates($rates=array(), $area=0){
		$rates_result = array();

    foreach ($rates as $key => $value) {
    	$id= isset($value->rate_id) ? $value->rate_id : 0;
    	$data_rate = array(
    		"'{$id}'",
    		"'{$area}'",
    		"'{$value->rate_name}'",
    		"'{$value->rate_description}'",
    		"'{$value->rate_type}'",
    		"'{$value->rate_min}'",
    		"'{$value->rate_max}'",
    		"'{$value->rate_free}'",
    		"'{$value->rate_amount}'",
    	);
    	$data_rate_update = array(
    		"`area_id` = '{$area}'",
    		"`rate_name` = '{$value->rate_name}'",
    		"`rate_name` = '{$value->rate_description}'",
    		"`rate_type` = '{$value->rate_type}'",
    		"`rate_min` = '{$value->rate_min}'",
    		"`rate_max` = '{$value->rate_max}'",
    		"`rate_free` = '{$value->rate_free}'",
    		"`rate_amount` = '{$value->rate_amount}'",
    	);
    	$data_rate_sql = implode(",", $data_rate);
    	$data_rate_update_sql = implode(",", $data_rate_update);

			$rates_result[] = $this->db->insert_returl_ID("INSERT INTO `shipping_rates` (`id`, `area_id`, `rate_name`, `rate_description`, `rate_type`, `rate_min`, `rate_max`, `rate_free`, `rate_amount`) VALUES(". $data_rate_sql .")");
    }
    return $rates_result;
	}
	function getShippingOriginAddress(){
		$shipping_data = array(
      "'shipping_origin_name'",
      "'shipping_origin_address_1'",
      "'shipping_origin_address_2'",
      "'shipping_origin_city'",
      "'shipping_origin_postal'",
      "'shipping_origin_country'",
      "'shipping_origin_phone'",
      );
		$shipping_origin_options = implode(",", $shipping_data);

		return $this->db->select("Select * From `system_options` Where `option_name` In ({$shipping_origin_options})");
	}
	function getShippingRates(){
		$area_result = $this->db->select("SELECT * FROM `shipping_areas` `s1`");

		$rates = array();

		foreach ($area_result as $key => $value) {
			$rates[$value->id]['detail'] = $value;
			$rates[$value->id]['countries'] = $this->db->select("SELECT `s2`.*, `s3`.`name` FROM `shipping_areas` `s1` Left Join `shipping_country` `s2` On `s1`.`id` = `s2`.`area` Left Join `countries` `s3` On `s3`.`value`=`s2`.`country_id` Where `s1`.`id` = '{$value->id}'");

			$rates_result = $this->db->select("SELECT `s2`.* FROM `shipping_areas` `s1` Left Join `shipping_rates` `s2` On `s1`.`id` = `s2`.`area_id` Where `s1`.`id` = '{$value->id}'");

			foreach ($rates_result as $k => $v) {
				$rates[$value->id]['rates'][$v->rate_type][] = $v;
			}
		}

		return $rates;
	}
  function getDefaultShippingRate(){
    $default_rate = $this->db->select("Select * From `shipping_rates` Where `rate_type` = 'default'");
    $default_rate_output = array(
      "rate_name"   => "",
      "rate_amount" => 0,
    );

    if (count($default_rate) > 0) {
      $default_rate = $default_rate[0];

      $default_rate_output = array(
        "rate_name"   => $default_rate->rate_name,
        "rate_amount" => $default_rate->rate_amount,
      );
    }

    return $default_rate_output;
  }
  function getShippingRateOptions(){
    $options = array(
      "enable" => get_system_option('shipping_rate_enable'),
    );

    return $options;
  }
}