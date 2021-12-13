<?php

class PaymentGateways_Model extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	function get_data(){
		$qry = $this->db->query("SELECT * FROM `system_options`");

		$rows = array();

		while($row = $this->db->fetch($qry,'array'))
			$rows[] = $row;

		return $rows;
	}
	function get_getway_options(){
		$qry = $this->db->select("SELECT * FROM `payment_gateway_options`");
		$rows = array();
		foreach ($qry as $key => $value) {
			$rows[$value->option_name] = $value->option_value;
		}
		return $rows;
	}
	function get_getway(){
		$qry = $this->db->select("SELECT * FROM `payment_gateway`");
		$rows = array();
		foreach ($qry as $key => $value) {
			$rows[$value->id] = $value;
		}
		return $rows;
	}


	function save_settings($settings,$value){
		return $this->db->query("UPDATE `system_options` SET `option_value` = '$value' WHERE `option_name` = '$settings'");
	}
	function save_gateways_options($id, $value){
		return $this->db->query("INSERT INTO `payment_gateway_options` (`id`, `option_value`) VALUES('{$id}', '{$value}') ON DUPLICATE KEY UPDATE `option_value`='{$value}'");
	}
	function save_gateways($id, $display_name,$enabled){
		return $this->db->query("INSERT INTO `payment_gateway` (`id`, `display_name`, `enabled`) VALUES('{$id}', '{$display_name}', '{$enabled}') ON DUPLICATE KEY UPDATE `display_name`='{$display_name}', `enabled`='{$enabled}'");
	}

	/* Paypal Subscription Plan */
	function get_subscription_plan($plan_id = ""){
		if ($plan_id != "") {
			$plans = $this->db->select("SELECT * FROM cms_items WHERE id = '{$plan_id}' and type = 'paypal-subscription-plan'");
			if (count($plans)) {
				return $plans[0];
			}else{
				return array();
			}
		}else{
			return array();
		}
	}
	
}