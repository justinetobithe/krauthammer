<?php
if (!function_exists('get_system_option') || get_system_option('system_type') != 'ECATALOG') {
	return; // Skip this plugin function of the system is not an ECATALOG TYPE
}
if (!function_exists("product_homepage")) {
	require_once(__DIR__."/../../../libraries/ecommerce-manager.php");

	function get_product_pages($page_id){
		$s_o_product_page_keys = array(
				"'product_home_page'",
				"'product_cart_page'",
				"'product_checkout_page'",
				"'product_enquire_page'",
				"'product_confirmation_page'",
				"'product_payment_method_page'",
				"'product_confirmed_page'",
			);
		$s_o_condition = implode(',', $s_o_product_page_keys);

		$db = new Database();
		$s_o_product_pages = $db->select("Select * From `system_options` Where `option_name` IN ({$s_o_condition}) and `option_value` = '{$page_id}'");

		return $s_o_product_pages;
	}
	function is_product_page($page_id){
		$s_o_product_pages = get_product_pages($page_id);

		if (count($s_o_product_pages)) {
			$s_o_product_page = $s_o_product_pages[0];
			return true;
		}
		return false;
	}
	function product_page($page_id, &$the_controller){
		$the_controller->view->alt_location = ROOT . "system_plugins/ecommerce/frontend/views/";
		$payment_methods = EcommerceManager::get_payment_getways();
		$the_controller->view->set("payment_methods",$payment_methods);

		$s_o_product_pages = get_product_pages($page_id);

		if (count($s_o_product_pages)) {
			$s_o_product_page = $s_o_product_pages[0];

			if (isset($s_o_product_page->option_name)) {
				switch ($s_o_product_page->option_name) {
					case "product_cart_page":
						product_cart();
						break;
					case "product_checkout_page":
						product_checkout();
						break;
					case "product_payment_method_page":
						product_payment_method();
						break;
					case "product_confirmation_page":
						product_confirmation();
						break;
					case "product_confirmed_page":
						// product_confirmed();
						break;
					
					default:
						# code...
						break;
				}
			}
		}
		// header_json(); print_r( $s_o_product_pages ); exit();
	}
	function product_homepage(){
		Session::set('ispage', 'product-home');
	}
	function product_cart(){
		Session::set('ispage', 'product-cart');
		EcommerceManager::cart_action();
		// EcommerceManager::cart_page();
	}
	function product_checkout(){
		Session::set('ispage', 'product-checkout');

		if (EcommerceManager::checkout_check_data()) {
			redirect(cms_get_payment_url());
		}
		
		// EcommerceManager::checkout_page();
	}
	function product_payment_method(){
		if (EcommerceManager::has_current_user()) {
			if (isPost('product-customer-detail-submit') && post('product-customer-detail-submit')) {
				EcommerceManager::payment_process_save_method();
				redirect(cms_get_confirmation_url());
			}else if (isPost('proceed-paypal') && post('proceed-paypal')) {
				include ROOT . "includes/paypal/express/process.php";
			}else{
				$payment_methods = EcommerceManager::get_payment_getways();
				// EcommerceManager::payment_page( array( "payment_methods" => $payment_methods ) );
			}
		}else{
			redirect(get_bloginfo('baseurl'));
		}
	}
	function product_confirmation(){
		$selected_method_id = EcommerceManager::payment_process_get_method();
		if ($selected_method_id == "") {
			exit();
		}
		$selected_method = EcommerceManager::get_payment_getways( $selected_method_id );
		$selected_method = array_pop($selected_method);

		if (isPost('submit') && post('submit')) {
			EcommerceManager::payment_process_order();
		}else{
			// if ($selected_method['detail']->gateway_type == 'PAYPAL_EXPRESS') {
			// 	$_SESSION['paypal'] = true;
			// 	EcommerceManager::order_paypal_express_confirmation_layout();
			// }else{
			// 	EcommerceManager::order_confirmation_layout();
			// }

			if ($selected_method['detail']->gateway_type == 'PAYPAL_EXPRESS' || $selected_method['detail']->gateway_type == 'PAYPAL_CHECKOUT') {
				$_SESSION['paypal'] = true;
			}
			// EcommerceManager::order_confirmation_layout();
		}
	}
	function product_confirmed(){
		// $the_controller->view->alt_location = ROOT . "system_plugins/ecommerce/frontend/views/";
		
		// if (isset($_GET['token']) && isset($_GET['PayerID'])) {
		// 	// include ROOT . "includes/paypal/express/process.php";
		// 	// EcommerceManager::payment_process_order();
		//    redirect( cms_get_confirmed_url() );
		// }else{
		// 	EcommerceManager::confirmed_page();
		// }
		// EcommerceManager::confirmed_page();
	}

	function cms_get_cart_url(){
		return cms_product_get_page( "product_cart_page" );
	}
	function cms_get_checkout_url(){
		return cms_product_get_page( "product_checkout_page" );
	}
	function cms_get_payment_url(){
		return cms_product_get_page( "product_payment_method_page" );
	}
	function cms_get_confirmation_url(){
		return cms_product_get_page( "product_confirmation_page" );
	}
	function cms_get_confirmed_url(){
		return cms_product_get_page( "product_confirmed_page" );
	}
	function cms_get_product_url(){
		return get_bloginfo( "baseurl" )."products";
	}

	function cms_product_get_page($system_option_name = ""){
		$db = new Database();
		// $result = $db->select("SELECT * FROM `cms_posts` Where `post_content` Like '%{$product_short_code}%'");
		$result = $db->select("SELECT `cms_posts`.* FROM `cms_posts` Inner Join `system_options` ON `system_options`.`option_value` = `cms_posts`.`id` Where `system_options`.`option_name` = '{$system_option_name}'");
		$result = count($result) ? $result[0] : array();

		$url = get_current_url();

		$uc = new UC();
		if (isset($result->url_slug)) {
			$url = $uc->uc_get_final_url( get_bloginfo("baseurl") . $result->url_slug);
		}

		return $url;
	}
}