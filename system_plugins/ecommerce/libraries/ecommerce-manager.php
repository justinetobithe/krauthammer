<?php

class EcommerceManager{
	private static $ecommerce_directory = __DIR__;

	public static function shop_page( $data = array() ){
		$theme_payment_page = ROOT . "views/themes/". ACTIVE_THEME . "/page-product-homepage.php";
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$product_payment_page = $frontend_directory . "frontend/views/page-product-homepage.php";

		EcommerceManager::load_page( array($theme_payment_page, $product_payment_page),  "", $data);
	}
	
	public static function add_to_cart($product_id = 0, $product_qty = 0){
		$product = ecatalog_get_product(array('id' => $product_id));

		ecatalog_cart_add_product($product, $product_qty);
	}
	public static function view_to_cart(){
		return ecatalog_cart_get_products();
	}
	public static function view_cart(){
		if(isset($_SESSION['cart'])) 
			return $_SESSION['cart']; 
		else 
			return $_SESSION['cart'] = array();
	}
	public static function cart_product_details(){
		// $cart_products = EcommerceManager::view_cart();
		$cart_products = ecatalog_cart_get_products();
		$cart_products_detail = array();
		
		foreach ($cart_products as $key => $value) {
			$cart_products_detail[] = $value;
		}

		return $cart_products_detail;
	}
	public static function view_cart_total_items(){
		$products = EcommerceManager::view_cart();
		$total_items = 0;
		foreach ($products as $key => $value) {
			$total_items += $value;
		}
		return $total_items;
	}
	public static function clear_cart(){
		ecatalog_cart_clear_products();
		if (isset($_SESSION['paypal_payment_info'])) {
			unset($_SESSION['paypal_payment_info']);
		}
	}

	public static function has_current_user(){
		$hasCurrentUser = true;
		if (isset($_SESSION['product_current_user_detail'])) {
			if (isset($_SESSION['product_current_user_detail']['firstname']) && $_SESSION['product_current_user_detail']['firstname'] != '') {
				return true;
			}
		}
		return $hasCurrentUser;
	}
	public static function get_current_user(){
		if (isset($_SESSION['product_current_user_detail'])) {
			return $_SESSION['product_current_user_detail'];
		}else{
			return null;
		}
	}
	public static function get_current_user_detail($key = ''){
		if (isset($_SESSION['product_current_user_detail']) && $key != "") {
			if (isset($_SESSION['product_current_user_detail'][$key])) {
				return $_SESSION['product_current_user_detail'][$key];
			}
		}
		return "";
	}
	public static function save_current_user($_data = array()){
		$d = array();
		$d['firstname'] = isset($_data['firstname']) ? $_data['firstname'] : "";
		$d['lastname'] = isset($_data['lastname']) ? $_data['lastname'] : "";
		$d['company_name'] = isset($_data['company_name']) ? $_data['company_name'] : "";
		$d['email'] = isset($_data['email']) ? $_data['email'] : "";
		$d['phone'] = isset($_data['phone']) ? $_data['phone'] : "";

		$d['country'] = isset($_data['country']) ? $_data['country'] : "";
		$d['address'] = isset($_data['address']) ? $_data['address'] : "";
		$d['address2'] = isset($_data['address2']) ? $_data['address2'] : "";
		$d['town'] = isset($_data['town']) ? $_data['town'] : "";
		$d['country2'] = isset($_data['country2']) ? $_data['country2'] : "";
		$d['post_code'] = isset($_data['post_code']) ? $_data['post_code'] : "";

		$d['shipping_country'] = isset($_data['shipping_country']) ? $_data['shipping_country'] : "";
		$d['shipping_address'] = isset($_data['shipping_address']) ? $_data['shipping_address'] : "";
		$d['shipping_address2'] = isset($_data['shipping_address2']) ? $_data['shipping_address2'] : "";
		$d['shipping_town'] = isset($_data['shipping_town']) ? $_data['shipping_town'] : "";
		$d['shipping_country2'] = isset($_data['shipping_country2']) ? $_data['shipping_country2'] : "";
		$d['shipping_post_code'] = isset($_data['shipping_post_code']) ? $_data['shipping_post_code'] : "";

		$d['notes'] = isset($_data['notes']) ? $_data['notes'] : "";

		$_SESSION['product_current_user_detail'] = $d;
	}

	public static function product_category_page($data = array()){
		$theme_checkout_page = ROOT . "views/themes/". ACTIVE_THEME . "/product-categories.php"; 
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$product_page = $frontend_directory . "frontend/views/product-categories.php";

		EcommerceManager::load_page( array($theme_checkout_page, $product_page),  "No Product Category Page Found", array('data' => $data));
	}
	public static function product_page($data = array()){
		$theme_checkout_page = ROOT . "views/themes/". ACTIVE_THEME . "/product.php"; 
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$product_page = $frontend_directory . "frontend/views/product.php";

		EcommerceManager::load_page( array($theme_checkout_page, $product_page),  "No Product Page Found", array('data' => $data));
	}

	public static function cart_page(){
		$theme_checkout_page = ROOT . "views/themes/". ACTIVE_THEME . "/page-cart.php"; 
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$product_cart_page = $frontend_directory . "frontend/views/page-cart.php";
		EcommerceManager::load_page( array($theme_checkout_page, $product_cart_page),  "No Cart Page");
	}
	public static function cart_layout(){
		$products = EcommerceManager::cart_product_details();
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$cart_template = $frontend_directory . "frontend/views/fragments/cart.php";

		EcommerceManager::load_page( array($cart_template),  "No Cart Layout", array("products" => $products) );
	}
	public static function cart_action(){
		if (isGet('action')) {
			if (get('action') == 'remove') {
				if (!isGet('product')) return;
				$product = ecatalog_get_products(array("url_slug"=>get('product')));
				$product = count($product) ? $product[0] : array();
				if (count($product)) {
					ecatalog_cart_delete_product( $product['id'] );
				}
			}
		}
	}

	public static function checkout_page(){
		$theme_checkout_page = ROOT . "views/themes/". ACTIVE_THEME . "/page-checkout.php";
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$product_checkout_page = $frontend_directory . "frontend/views/page-checkout.php";

		EcommerceManager::load_page( array($theme_checkout_page, $product_checkout_page),  "No Checkout Layout");
	}
	public static function checkout_layout(){
		$products = EcommerceManager::cart_product_details();
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$cart_template = $frontend_directory . "frontend/views/fragments/checkout.php";

		EcommerceManager::load_page( array($cart_template), "No Checkout Form Found", array("products" => $products) );
	}
	public static function checkout_check_data(){
		if (isPost('checkout-action') && post('checkout-action') == 'checkout-submit') {
			//SET CHECKOUT INFO
			$billing_name = (isset($_POST['firstname']) && isset($_POST['lastname'])) ? "{$_POST['firstname']} {$_POST['lastname']}" : "";

			cms_product_set_user_detail(array(
		    "company"         => isset($_POST['company-name']) ? $_POST['company-name'] : "",
		    "email"           => isset($_POST['email']) ? $_POST['email'] : "",
		    "first_name"      => isset($_POST['firstname']) ? $_POST['firstname'] : "",
		    "last_name"       => isset($_POST['lastname']) ? $_POST['lastname'] : "",
		    "phone"           => isset($_POST['phone']) ? $_POST['phone'] : "",

		    "billing_name"    => $billing_name,
		    "billing_address" => isset($_POST['address']) ? $_POST['address'] : '',
		    "billing_address_line_2" => isset($_POST['address-2']) ? $_POST['address-2'] : '',
		    "billing_city"    => isset($_POST['town']) ? $_POST['town'] : '',
		    "billing_postal"  => isset($_POST['post-code']) ? $_POST['post-code'] : '',
		    "billing_state"   => isset($_POST['state']) ? $_POST['state'] : '',
		    "billing_country" => isset($_POST['country']) ? $_POST['country'] : "",
		    "billing_email"   => isset($_POST['email']) ? $_POST['email'] : "",
		    "billing_phone"   => isset($_POST['phone']) ? $_POST['phone'] : "",

		    "shipping_name"   => $billing_name,
		    "shipping_address" => isset($_POST['shipping-address']) ? $_POST['shipping-address'] : '',
		    "shipping_address_line_2" => isset($_POST['shipping-address-2']) ? $_POST['shipping-address-2'] : '',
		    "shipping_city"   => isset($_POST['shipping-town']) ? $_POST['shipping-town'] : '',
		    "shipping_postal" => isset($_POST['shipping-post-code']) ? $_POST['shipping-post-code'] : '',
		    "shipping_state"  => isset($_POST['shipping-state']) ? $_POST['shipping-state'] : '',
		    "shipping_country"=> isset($_POST['shipping-country']) ? $_POST['shipping-country'] : '',
		    "shipping_email"  => isset($user['shipping_email']) ? $user['shipping_email'] : (isset($_POST['email']) ? $_POST['email'] : ""),
		    "shipping_phone"  => isset($user['shipping_phone']) ? $user['shipping_phone'] : (isset($_POST['phone']) ? $_POST['phone'] : ""),
		    "meta_data"       => isset($_POST['meta_data']) ? $_POST['meta_data'] : '',
		    
		    "payment_method_id" => cms_product_get_payment_method(),
		    "message"         => isset($_POST['other-notes']) ? $_POST['other-notes'] : "",
		    "invoice_number"  => isset($_POST['invoice_number']) ? $_POST['invoice_number'] : "",
		    "order_status"    => "active",
		  ));

			return true;
		}else{
			return false;
		}
	}

	public static function payment_page( $data = array() ){
		$theme_payment_page = ROOT . "views/themes/". ACTIVE_THEME . "/page-payment.php";
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$product_payment_page = $frontend_directory . "frontend/views/page-payment.php";

		EcommerceManager::load_page( array($theme_payment_page, $product_payment_page),  "No Payment Layout", $data);
	}
	public static function payment_layout( $data = array() ){
		$products = EcommerceManager::cart_product_details();
		$data['products'] = $products;
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$payment_template = $frontend_directory . "frontend/views/fragments/payment.php";

		EcommerceManager::load_page( array($payment_template), "No Checkout Form Found", $data );
	}

	public static function payment_process_save_method(){
		$_SESSION['product_current_user_detail_method'] = $_POST['selected-method'];
	}
	public static function payment_process_get_method(){
		return isset($_SESSION['product_current_user_detail_method']) ? $_SESSION['product_current_user_detail_method'] : "";
	}
	public static function payment_process_order(){
		$current_user_data = EcommerceManager::get_current_user();
		$current_cart_data = EcommerceManager::view_to_cart();

		if (count($current_cart_data) <= 0) {
			redirect(bloginfo('baseurl') . "product/");
			exit();
		}

		$selected_method_id = EcommerceManager::payment_process_get_method();
		$selected_method = EcommerceManager::get_payment_getways( $selected_method_id );
		$selected_method = array_pop($selected_method);

		$db = new Database();


		$db->table = 'orders';
		$order = array(
			"first_name" => $current_user_data['firstname'],
			"last_name" => $current_user_data['lastname'],
			"company" => $current_user_data['company_name'],
			"phone" => $current_user_data['phone'],
			"email" => $current_user_data['email'],
			"billing_name" => $current_user_data['firstname'] . " " . $current_user_data['lastname'],
			"billing_address" => $current_user_data['address'],
			"billing_address_line_2" => $current_user_data['address2'],
			"billing_city" => $current_user_data['town'],
			"billing_postal" => $current_user_data['post_code'],
			"billing_state" => $current_user_data['country'],
			"billing_country" => $current_user_data['country'],
			"billing_email" => $current_user_data['email'],
			"billing_phone" => $current_user_data['phone'],
			"shipping_name" => $current_user_data['firstname'] . " " . $current_user_data['lastname'],
			"shipping_address" => $current_user_data['shipping_address'],
			"shipping_address_line_2" => $current_user_data['shipping_address2'],
			"shipping_city" => $current_user_data['shipping_town'],
			"shipping_postal" => $current_user_data['post_code'],
			"shipping_state" => $current_user_data['shipping_country2'],
			"shipping_country" => $current_user_data['shipping_country2'],
			"shipping_email" => $current_user_data['email'],
			"shipping_phone" => $current_user_data['phone'],
			"message" => $current_user_data['notes'],
			"invoice_number" => '00000000',
			"order_status" => 'active',
			"payment_method_id" => $selected_method_id,
		);

		$db->data = $order;
		$order_id = $db->insertGetID();

		$order_detail_ids = array();
		$product_to_deduct = array();

		$db->table = 'order_details';
		foreach ($current_cart_data as $key => $value) {
			$order_detail = array(
				"order_id" => $order_id,
				"product_id" => $value['product']['id'],
				"image_url" => $value['product']['featured_image_url'],
				"item_name" => $value['product']['product_name'],
				"quantity" => $value['quantity'],
				"price" => $value['product']['price'],
			);
			$product_to_deduct[] = array(
				"id" => $value['product']['id'],
				"quantity" => $value['quantity'],
			);

			$db->data = $order_detail;
			$order_detail_ids[] = $db->insertGetID();

			$mysql_product_deduction_sql = "Update `products` Set `quantity` = (`quantity` - Cast('{$value['quantity']}' as DECIMAL)) Where `id` = '{$value['product']['id']}' and `quantity` > 0";
			$db->query( $mysql_product_deduction_sql );
		}

		$output = array(
			"id" => $order_id,
			"details" => $order_detail_ids,
		);

		if (isset($_SESSION['paypal_payment_info'])) {
			$paypal_payment_info = $_SESSION['paypal_payment_info'];

			$order_payment = array(
				'order_id' => $order_id,
				'payment_issue_date' => date("Y-m-d H:i:s"),
				'payment_ref_number' => $paypal_payment_info['PAYMENTINFO_0_TRANSACTIONID'],
				'payment_mode_id' => $selected_method_id,
				'payment_amount' => $paypal_payment_info['PAYMENTINFO_0_AMT'],
				'payment_gst' => "",
				'payment_total_amount' => $paypal_payment_info['PAYMENTINFO_0_AMT'],
				'payment_status' => isset($paypal_payment_info['PAYMENTINFO_0_ACK']) && $paypal_payment_info['PAYMENTINFO_0_ACK'] == 'Success' ? 1 : 0,
				'payment_description' => "Payment via Paypal Express",
				'voucher_number' => "",
				'recieved_by' => "",
			);


			$db->table = 'order_payments';
			$db->data = $order_payment;
			$order_payment_id = $db->insertGetID();

			$output['payment_id'] = $order_payment_id;

			ecatalog_send_order_details_to_client($order_id);
		}

		EcommerceManager::clear_cart();
    redirect(cms_get_confirmed_url()); 
	}
	public static function payment_page_success( $data = array() ){
		$theme_payment_page = ROOT . "views/themes/". ACTIVE_THEME . "/page-order-success.php";
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$product_payment_page = $frontend_directory . "frontend/views/page-order-success.php";

		EcommerceManager::load_page( array($theme_payment_page, $product_payment_page),  "", $data);
	}
	public static function confirmed_page( $data = array() ){
		$theme_payment_page = ROOT . "views/themes/". ACTIVE_THEME . "/page-confirmed.php";
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$product_payment_page = $frontend_directory . "frontend/views/page-confirmed.php";
		
		EcommerceManager::load_page( array($theme_payment_page, $product_payment_page),  "", $data);
	}
	public static function order_confirmation_layout( $data = array() ){
		$products = EcommerceManager::cart_product_details();
		$data['products'] = $products;
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$payment_template = $frontend_directory . "frontend/views/page-confirmation.php";

		EcommerceManager::load_page( array($payment_template), "No Order Confirmation Layout", $data );
	}
	public static function order_paypal_express_confirmation_layout( $data = array() ){
		$products = EcommerceManager::cart_product_details();
		$data['products'] = $products;
		$frontend_directory = EcommerceManager::$ecommerce_directory . '/../';
		$payment_template = $frontend_directory . "frontend/views/page-confirmation-paypal.php";

		EcommerceManager::load_page( array($payment_template), "No Order Confirmation Layout", $data );
	}

	public static function load_page($pages = array(), $default_message="No Page Found", $_data = array()){
		foreach ($pages as $key => $value) {
			if (is_file($value)) {
				extract($_data, EXTR_PREFIX_SAME, "em_");
				require ($value); return;
			}
		}
		echo $default_message;
	}

	public static function get_payment_getways( $selected_method_id = 0 ){
		$db = new Database();
		$sql = "SELECT * FROM `payment_gateway` Where gateway_type <> 'PAYPAL_STANDARD' and gateway_type <> 'PAYPAL_EXPRESS'";

		if (isset($selected_method_id) && $selected_method_id != 0) $sql .= " and `id` = '{$selected_method_id}'";

		$qry = $db->select( $sql );
		$payment_getways = array();

		foreach ($qry as $key => $value) {
			$payment_getways[$value->id]['detail'] = $value;

			$payment_getways_options = $db->select("SELECT * FROM `payment_gateway_options` Where `payment_gateway_id` = '{$value->id}'");

			$_o = array();

			foreach ($payment_getways_options as $payment_getways_options_key => $payment_getways_options_value) {
				$_o[$payment_getways_options_value->option_name] = $payment_getways_options_value;
			}
			$payment_getways[$value->id]['options'] = $_o;
		}
		return $payment_getways;
	}
}