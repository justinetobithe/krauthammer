<?php

function cms_is_login(){
	$is_login = false;
	if (isset($_SESSION['customer_id']) && isset($_SESSION['customer'])) { $is_login = true; }
	return $is_login;
}
function cms_is_login_enable(){
	$cms_login_enable = get_system_option(array('option_name' => "customer_login_enable"));
	return $cms_login_enable == 'Y';
}
function cms_get_login_user_info($key = ""){
	global $db;
	$user = null;
	if (cms_is_login_enable() && cms_is_login()) {
		if (isset($_SESSION['customer_id'])) {
			$user = $db->select("Select * From `customers` Where `id` = '{$_SESSION['customer_id']}'");
			$user = count($user)  ? $user[0] : null;
		}
	}

	if ($key != "") {
		return isset($user->$key) ? $user->$key : null;
	}else{
		return $user;
	}
}

$cms_login_user_info_data = null;
function cms_set_logit_user_info_cache(){
	global $cms_login_user_info_data;
	$cms_login_user_info_data = cms_get_login_user_info();
}
function cms_get_logit_user_info_cache(){
	global $cms_login_user_info_data;
	return $cms_login_user_info_data;
}