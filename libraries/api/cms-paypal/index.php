<?php 
require_once ROOT . "admin/libraries/plugins/cms-api/cms-paypal.php";

class API_CmsPaypal{
  private $paypal_checkout_options = array();
  private $paypal_api_url = array();
  private $username = "";
  private $password = "";
  public $options = array();
  public $paypal_base_url = "";
  public $db;
  public $paypal;

  function __construct(){
    $this->db = new Database();
    $this->paypal = new CMS_Paypal();
  }

  public function ipn(){
    $res = array('data'=>array());
    
    echo json_encode($res);
  }
  public function paypal_checkout_create_payment(){
    $this->initialize();
    $output = "";

    if (isset($_SESSION['ecatalog_product_enquiry']) && count($_SESSION['ecatalog_product_enquiry'])) {
      /* API CALL: CREATE PAYMENT */
      $get_payment = array( 'status' => false, 'value' => null, 'message' => '');

      $payment_data = $this->get_payment_data();

      /* USING SDK */
      $get_payment = $this->paypal->create_payment($payment_data);

      header_json(); 
      if ($get_payment['value'] != "") {
        $payment = array(
          "id" => $get_payment['value'],
        );
        $output = $payment;
      }else{
        $output = $get_payment['message'];
      }
      /* USING SDK END */
    }else{
      $output = "No found items";
    }

    echo json_encode($output);
  }
  public function paypal_execute_payment(){
    $output = "";
    /* USING SDK */
    $this->initialize();

    $payment_id = $_SESSION['paypal-checkout']['payment']['id'];

    $res = $this->paypal->execute_payment($payment_id);

    header_json(); echo json_encode($res);
    /* USING SDK END */
    echo $output;

    exit();

    $access_token = $this->paypal_token();

    if (isset($access_token['value'])) {
      $access_token = $access_token['value'];
    }else{
      $access_token = "";
    }

    $output = array(
      'status'  => false,
      'value'   => '',
      'message'   => '',
    );

    if (isset($_SESSION['paypal-checkout']['payment']->id)) {
      $payer_id = isset($_REQUEST['payerID']) ? $_REQUEST['payerID'] : '';
      $payment_id = isset($_REQUEST['paymentID']) ? $_REQUEST['paymentID'] : '';
      $execute_url = str_replace("PAY-XXX", $payment_id, $this->paypal_api_url['payment_execute']);
      $data = array(
        "payer_id" => $payer_id,
      );

      $curl = new Curl();
      $curl->setHeader('Content-Type', 'application/json');
      $curl->setHeader('Authorization', 'Bearer ' . $access_token);
      $curl->post( $execute_url, $data);

      if (!$curl->error) {
        if (isset($curl->response->id)) {
          $_SESSION['paypal-checkout']['execute'] = $curl->response; /* Store create payment transaction to a session */

          $output['status']   = true;
          $output['value'] = $curl->response->id;

          /* Save transaction */
          $order = $this->save_order();
          $order_items = $this->save_order_items($order);
          $order_additional = $this->save_order_additional_items($order);
          $order_delivery = $this->save_order_delivery_detail($order);
          $order_payment = $this->save_order_payment($order, $curl->response);

          /* Remove Transactions */
          unset($_SESSION['ecatalog_product_enquiry']);
          unset($_SESSION['product_current_user_detail_method']);
          unset($_SESSION['paypal']);
          unset($_SESSION['paypal-checkout']);

          cms_set_last_order_id($order);
        }
      }else{
        $output['message']  = $curl->errorMessage;
      }
    }else{
      $output['message'] = "No found items";
    }

    return $output;
  }

  public function paypal_create_subscription($subscriptio_plan_id=""){
    unset($_SESSION['agreement']); // Refresh Session for Subscription

    $global_subscriptions = get_global_subscriptions();
    $selected_plan = array();

    foreach ($global_subscriptions as $key => $value) {
      if ($value['plan']['id'] == $subscriptio_plan_id) {
        $selected_plan = $value;
      }
    }

    $plan = array(
      'id' => $selected_plan['plan']['id'],
      'name' => isset($selected_plan['agreement']['name']) ? $selected_plan['agreement']['name'] : "",
      'description' => isset($selected_plan['agreement']['description']) ? $selected_plan['agreement']['description'] : "",
    );

    $created_agreement = $this->paypal->billing_agreement_create( $plan );
    

    if (!$created_agreement['status']) {
      print_r($created_agreement['value']->getMessage()); exit();
    }

    $_SESSION['paypal_agreement'] = $created_agreement;
    $_SESSION['paypal_selected_subscription'] = $selected_plan['plan']['id'];

    header("Location: " . $created_agreement['approval']);
  }
  public function paypal_create_product_subscription($product_id=""){
    $subscription_period = $_REQUEST['subscription-period'];

    $currency_code = get_system_option('currency_code');

    ecatalog_query_products("id={$product_id}");

    if (ecatalog_have_products()) {
      ecatalog_the_product();

      $product_billing_period = ecatalog_get_product_billing_period();

      $prod_default = $this->get_gateway_options('prod_subs_default_%');

      $selected_plan = null;

      foreach ($product_billing_period['subscriptions'] as $key => $value) {
        if ($value['plan_name'] == $subscription_period ) {
          $selected_plan = $value;
          break;
        }
      }

      if ($selected_plan != null) {
        $billing_plan = (object) array();
        $billing_plan->name = $selected_plan['plan_name'];
        $billing_plan->description = $selected_plan['plan_description'];
        $billing_plan->type = $selected_plan['plan_type'];
        $billing_plan->payment_definitions = array();


        $payment_def_1 = (object) array();
        $payment_def_1->name = $selected_plan["title_trial"];
        $payment_def_1->type = "TRIAL";
        $payment_def_1->frequency = $selected_plan["frequency_trial"];
        $payment_def_1->frequency_interval = $selected_plan["frequency_interval_trial"];
        $payment_def_1->cycles = $selected_plan["cycle_trial"];
        $payment_def_1->amount = (object) array();
        $payment_def_1->amount->value = $selected_plan["amount_trial"];
        $payment_def_1->amount->currency = $currency_code;
        $payment_def_2 = (object) array();
        $payment_def_2->name = $selected_plan["title"];
        $payment_def_2->type = "REGULAR";
        $payment_def_2->frequency = $selected_plan["frequency"];
        $payment_def_2->frequency_interval = $selected_plan["frequency_interval"];
        $payment_def_2->cycles = $selected_plan["cycle"];
        $payment_def_2->amount = (object) array();
        $payment_def_2->amount->value = $selected_plan["amount"];
        $payment_def_2->amount->currency = $currency_code;
        $billing_plan->payment_definitions[] = $payment_def_1;
        $billing_plan->payment_definitions[] = $payment_def_2;

        $return = isset($prod_default['prod_subs_default_return']) && $prod_default['prod_subs_default_return'] != "" ? $prod_default['prod_subs_default_return'] : URL . "api/cms-paypal/paypal_accept_product_subscription/{$product_id}/";
        $cancel = isset($prod_default['prod_subs_default_cancel']) && $prod_default['prod_subs_default_cancel'] != "" ? $prod_default['prod_subs_default_cancel'] : URL . "api/cms-paypal/paypal_cancel_product_subscription/{$product_id}/";;


        $billing_plan->merchant_preferences = (object) array();
        $billing_plan->merchant_preferences->return_url = $return;
        $billing_plan->merchant_preferences->cancel_url = $cancel;
        $billing_plan->merchant_preferences->auto_bill_amount = $selected_plan['plan_auto_billing'];
        $billing_plan->merchant_preferences->initial_fail_amount_action = $selected_plan['plan_initial_fail_action'];
        $billing_plan->merchant_preferences->max_fail_attempts = $selected_plan['plan_max_fail_attempts'];

        /* Create new plan */
        $created_plan = $this->paypal->create_billing_plan($billing_plan);
        $plan_id = $created_plan['value']['id'];
        /* Activate new plan */
        $actived_plan = $this->paypal->billing_plan_activate($plan_id);
        $_SESSION['paypal_plan'] = $actived_plan['value'];
        /* Create agreement */
        $plan = array(
          'id'          => $plan_id,
          'name'        => $selected_plan['agreement_name'],
          'description' => $selected_plan['agreement_desc'],
        );
        $created_agreement = $this->paypal->billing_agreement_create( $plan );

        if (!$created_agreement['status']) {
          print_r($created_agreement['value']->getMessage()); exit();
        }

        $_SESSION['paypal_agreement'] = $created_agreement;
        $_SESSION['paypal_selected_subscription'] = $actived_plan['id'];
        $_SESSION['subscribed_products'] = array($product_id);

        header("Location: " . $created_agreement['approval']);
      }else{
        echo "Unable to retrieve plan data.";
      }
    }else{
      echo "No found product with id {$product_id}";
    }
  }
  public function paypal_accept_product_subscription(){
    // header_json(); print_r($_SESSION); exit();
    $result = cms_subscription_confirmed();
    echo $result;
  }
  public function paypal_cancel_product_subscription(){
    echo "Cancel";
  }

  public function download_files($product_id = 0){
    ecatalog_query_products("id={$product_id}");
    ecatalog_have_products();
    ecatalog_the_product();

    if (!class_exists('ZipArchive')) {
      echo "Unable to proceed to files"; exit();
    }

    if (customer_validate_payment($product_id)) {
      $billing_period = ecatalog_get_product_billing_period();

      $files = implode(',', $billing_period['billing_info']['product_files']);

      $res = $this->db->select("SELECT * FROM `cms_files` WHERE id IN ({$files})");

      if (!is_dir(rtrim(ROOT ,'/') . "/temp")) {
        @mkdir(rtrim(ROOT ,'/') . "/temp");
      }

      $file_name = "{$product_id}-" . date("Y-m-dH-i-s") . "-files.zip";
      $file_location = rtrim(ROOT ,'/') . "/temp/{$file_name}";

      if (count($res) > 1) {
        $zip = new ZipArchive;
        $zip->open($file_location, ZipArchive::CREATE);

        $added_file = array();
        foreach ($res as $key => $value) {
          $f = str_replace(URL, ROOT, $value->url);

          $ftemp = explode('.', $value->url);
          $ext = end($ftemp);
          $fname = explode('.', $value->filename);
          if ($ext == end($fname) && count($fname) > 1) { array_pop($fname); }
          $fname = implode('.', $fname);

          $dest_fold = rtrim($value->mime,'/');
          $dest_name = "{$fname}." . ($ext);

          $ctr = 1;
          while (in_array($dest_name, $added_file)) {
            $ctr++;
            $dest_name = "{$fname} ({$ctr})." . ($ext);
          }
          $added_file[] = $dest_name;

          $zip->addFile($f, str_replace(ROOT, '', "{$dest_fold}/{$dest_name}"));
        }

        $zip->close();
      }else{
        foreach ($res as $key => $value) {
          $f = str_replace(URL, ROOT, $value->url);
          $file_location = $f;
          $basename = basename($file_location);
          $temp = explode('.', $basename);
          $file_name = "{$product_id}-" . date("Y-m-d-H-i-s") . "." . end($temp);
        }
      }
      

      if (file_exists($file_location)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$file_name.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_location));
        readfile($file_location);

        unlink($file_location);
        exit();
      }
    }

    header_404();
  }

  private function initialize($paypal_base_url = "https://api.sandbox.paypal.com"){
    $this->paypal_base_url = $paypal_base_url;
    $this->paypal_api_url = array(
      "base"                => $this->paypal_base_url,
      "token"               => $this->paypal_base_url . "/v1/oauth2/token",
      "payment"             => $this->paypal_base_url . "/v1/payments/payment/",
      "payment_execute"     => $this->paypal_base_url . "/v1/payments/payment/PAY-XXX/execute",
      "plan"                => $this->paypal_base_url . "/v1/payments/billing-plans/",
      "plan_list"           => $this->paypal_base_url . "/v1/payments/billing-plans/",
      "plan_activate"       => $this->paypal_base_url . "/v1/payments/billing-plans/PAY-XXX/",
      "agreement"           => $this->paypal_base_url . "/v1/payments/billing-agreements/",
      "agreement_execute"   => $this->paypal_base_url . "/v1/payments/billing-agreements/PAY-XXX/agreement-execute/",
    );

    $o = $this->get_gateway_options('paypal_checkout%');
    $this->options = $o;
    $this->username = $o['paypal_checkout_sandbox_client_id'] ? $o['paypal_checkout_sandbox_client_id'] : '';
    $this->password = $o['paypal_checkout_sandbox_secret']    ? $o['paypal_checkout_sandbox_secret'] : '';
  }
  public function get_gateway_options($option_name_like = ""){
    $o = array();
    $temp = $this->db->select("SELECT * FROM `payment_gateway_options` WHERE option_name like '{$option_name_like}'");
    foreach ($temp as $key => $value) {
      $o[$value->option_name] = $value->option_value;
    }

    return $o;
  }

  // public function ipn(){
  //   require('paypal-ipn/ipn.php');
  // }

  /* Data Generators */
  public function get_payment_data(){
    unset($_SESSION['paypal-checkout']); /* to reset paypal-checkout session*/

    if (isset($_SESSION['ecatalog_product_enquiry'])) {
      $options        = $this->get_gateway_options('paypal_checkout%');
      $currency_code  = get_system_option("currency_code");
      $currency_code  = $currency_code != "" ? $currency_code : "USD";
      $other_fees     = isset($_SESSION['product_other_fees']) ? $_SESSION['product_other_fees'] : array();
      $current_user   = $this->get_user_detail();

      $items      = array();
      $total      = 0;
      $sub_total  = 0;

      foreach ($_SESSION['ecatalog_product_enquiry'] as $key => $value) {
        $tax = isset($value['product']['tax']) ? $value['product']['tax'] : 0;
        $sku = $value['product']['sku'] != '' ? $value['product']['sku'] : "000";

        $items[] = array(
          "name"        => $value['product']['product_name'],
          "description" => $value['product']['product_description'],
          "quantity"    => $value['quantity'],
          "price"       => $value['product']['price'],
          "tax"         => $tax,
          "sku"         => $sku,
          "currency"    => $currency_code,
        );
        $sub_total += (($value['product']['price'] + $tax) * $value['quantity']);
      }


      $d_tax      = isset($other_fees['tax']) ? $other_fees['tax'] : 0;
      $d_shipping = isset($other_fees['shipping']) ? $other_fees['shipping'] : 0;
      $d_handling = isset($other_fees['handling']) ? $other_fees['handling'] : 0;
      $d_discount = isset($other_fees['shipping_discount']) ? $other_fees['shipping_discount'] : 0;
      $d_insurance= isset($other_fees['insurance']) ? $other_fees['insurance'] : 0;

      $total = floatval($sub_total) + floatval($d_tax) + floatval($d_shipping) + floatval($d_handling) + floatval($d_discount) + floatval($d_insurance);

      $amount = array(
        "total"     => $total,
        "currency"  => $currency_code,
        "details"   => array(
          "subtotal"    => $sub_total,
          "tax"         => $d_tax,
          "handling_fee"=> $d_handling,
          "insurance"   => $d_insurance,
          "shipping"    => $d_shipping,
          "shipping_discount" => $d_discount,
        ),
      );

      $item_list = array( 'items' => $items );
      
      $transaction = array(
        array(
          'amount' => $amount,
          'item_list' => $item_list,
        ),
      );

      $page_confirmed = isset($options['paypal_checkout_url_confirmed']) ? $options['paypal_checkout_url_confirmed'] : "";
      $page_cancelled = isset($options['paypal_checkout_url_cancelled']) ? $options['paypal_checkout_url_cancelled'] : "";

      $payer_address = array();
      if (isset($current_user['billing_address']) && $current_user['billing_address'] != "" &&
          isset($current_user['billing_country']) && $current_user['billing_country'] != "") {
        if ( isset($current_user['billing_address']) && $current_user['billing_address'] != "" ) { $payer_address['line1'] = $current_user['billing_address']; } //required
        if ( isset($current_user['billing_address_line_2']) && $current_user['billing_address_line_2'] != "" ) { $payer_address['line2'] = $current_user['billing_address_line_2']; }
        if ( isset($current_user['billing_city']) && $current_user['billing_city'] != "" ) { $payer_address['city'] = $current_user['billing_city']; }
        if ( isset($current_user['billing_country']) && $current_user['billing_country'] != "" ) { $payer_address['country_code'] = $this->get_country_code($current_user['billing_country']); } //required
        if ( isset($current_user['billing_postal']) && $current_user['billing_postal'] != "" ) { $payer_address['postal_code'] = $current_user['billing_postal']; }
        if ( isset($current_user['billing_state']) && $current_user['billing_state'] != "" ) { $payer_address['state'] = $current_user['billing_state']; }
        if ( isset($current_user['billing_phone']) && $current_user['billing_phone'] != "" ) { $payer_address['phone'] = $current_user['billing_phone']; }
      }

      $payer_info = array();
      if ( isset($current_user['email']) ) { $payer_info['email'] = $current_user['email']; }
      if ( isset($current_user['birthday']) ) { $payer_info['birthday'] = $current_user['birthday']; }
      if ( isset($current_user['tax_id']) ) { $payer_info['tax_id'] = $current_user['tax_id']; }
      if ( isset($current_user['tax_id_type']) ) { $payer_info['tax_id_type'] = $current_user['tax_id_type']; }
      if ( count($payer_address) > 0 ) { $payer_info['billing_address'] = $payer_address; }

      $data = array(
        "intent" => "sale",
        "note_to_payer" => isset($options['paypal_checkout_note_to_payer']) ? $options['paypal_checkout_note_to_payer'] : "",
        "redirect_urls" => array(
          "return_url" => $page_confirmed,
          "cancel_url" => $page_cancelled,
        ),
        "payer" => array(
          "payment_method" => "paypal",
          "payer_info" => $payer_info,
        ),
        "transactions" => $transaction,
      );

      return $data;
    }else{
      return null;
    }
  }

  /* Other functions */
  public function get_country_code($country_name = ""){
    $country = $this->db->select("SELECT * FROM `countries` Where name = '{$country_name}'");
    if (count($country) > 0) {
      $country = $country[0];
      return $country->code;
    }

    return "";
  }
  public function get_user_detail(){
    if ($_SESSION['product_current_user_detail']) {
      $user = $_SESSION['product_current_user_detail'];
      $data = array(
        "first_name"      => $user['first_name'],
        "last_name"       => $user['last_name'],
        "company"         => $user['company'],
        "phone"           => $user['phone'],
        "email"           => $user['email'],
        "billing_name"    => "{$user['first_name']} {$user['last_name']}",
        "billing_address" => $user['billing_address'],
        "billing_address_line_2" => $user['billing_address_line_2'],
        "billing_city"    => $user['billing_city'],
        "billing_postal"  => $user['billing_postal'],
        "billing_state"   => isset($user['billing_state']) ? $user['billing_state'] : '',
        "billing_country" => $user['billing_country'],
        "billing_email"   => $user['billing_email'],
        "billing_phone"   => $user['billing_phone'],
        "shipping_name"   => isset($user['shipping_name']) ? $user['shipping_name'] : '',
        "shipping_address" => isset($user['shipping_address']) ? $user['shipping_address'] : '',
        "shipping_address_line_2" => isset($user['shipping_address_line_2']) ? $user['shipping_address_line_2'] : '',
        "shipping_city"   => isset($user['shipping_city']) ? $user['shipping_city'] : '',
        "shipping_postal" => isset($user['shipping_postal']) ? $user['shipping_postal'] : '',
        "shipping_state"  => isset($user['shipping_state']) ? $user['shipping_state'] : '',
        "shipping_country"=> isset($user['shipping_country']) ? $user['shipping_country'] : '',
        "shipping_email"  => isset($user['shipping_email']) ? $user['shipping_email'] : '',
        "shipping_phone"  => isset($user['shipping_phone']) ? $user['shipping_phone'] : '',
        "meta_data"       => isset($user['meta_data']) ? $user['meta_data'] : '',
        "payment_method_id" => cms_product_get_payment_method(),
        "message"         => isset($user['message']) ? $user['message'] : "",
        "invoice_number"  => isset($user['invoice_number']) ? $user['invoice_number'] : "",
        "order_status"    => isset($user['order_status']) ? $user['order_status'] : "active",
      );

      return $data;
    }else{
      return array();
    }
  }
}