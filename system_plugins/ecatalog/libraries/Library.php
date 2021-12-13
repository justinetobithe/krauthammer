<?php 
/* 
Location: /system_plugins/ecommerce/libraries/Library.php
*/
$_global_customer_var = array();
function customer_get_info($selected_key = ""){
  global $_global_customer_var;
  global $db;
  $user = null;

  if (customer_is_login_enable() && customer_is_login()) {
    if (isset($_SESSION['customer_id'])) {
      $user = $db->select("Select * From `customers` Where `id` = '{$_SESSION['customer_id']}'");
      $user = count($user)  ? $user[0] : null;
      $_global_customer_var = array();
      foreach ($user as $key => $value) {
        $_global_customer_var[$key] = $key=='meta' ? json_decode($value, true) : $value;
      }
    }
  }

  if ($selected_key != "") {
    return isset($_global_customer_var[$selected_key]) ? $_global_customer_var[$selected_key] : null;
  }else{
    return $_global_customer_var;
  }
}
function customer_the_info($key = ""){
  global $_global_customer_var;

  if ($key != "") {
    return isset($_global_customer_var[$key]) ? $_global_customer_var[$key] : null;
  }else{
    return $_global_customer_var;
  }
};
function customer_is_login(){
  return isset($_SESSION['customer_id']) && isset($_SESSION['customer']);
}
function customer_is_login_enable(){
  $cms_login_enable = get_system_option(array('option_name' => "customer_login_enable"));
  return $cms_login_enable == 'Y';
}
function customer_validate_payment($id=null, $cid=null){
  global $db;
  $pid = $id  == null ? ecatalog_get_product_detail('id') : $id;
  $cid = $cid == null ? customer_get_info('id') : $cid;

  $prod = ecatalog_get_product_billing_period();

  if ($cid != null && $cid != "") {
    if ($prod['billing_info']['type']=="Global Subscription" && $cid != null) {
      $has_susbcription = false;

      $gateway_subscription = $db->select( "SELECT * FROM payment_gateway WHERE gateway_type = 'PAYPAL_SUBSCRIPTION'" );
      $gateway_subs_id = 0;
      if (count($gateway_subscription)) {
        $gateway_subscription = $gateway_subscription[0];
        $gateway_subs_id = $gateway_subscription->id;
      }

      foreach ($prod['global_subscriptions'] as $key => $value) {
        $plan_id = $value['plan']['id'];

        $sql = "SELECT o.id o_id, op.id op_id, op.* FROM orders o 
                INNER JOIN order_payments op ON o.id = op.order_id 
                INNER JOIN order_details od ON o.id = od.order_id and od.item_name = '{$plan_id}' and status = 'active'
                WHERE o.cid = {$cid} and o.payment_method_id = {$gateway_subs_id}";

        $subs = $db->select( $sql );

        if (count($subs)) {
          $has_susbcription   = true;
          $payment_ref_number = $subs[0]->payment_ref_number;
          $has_susbcription   = customer_has_subscription_for_plan($plan_id);
          // $has_susbcription   = cms_validate_paypal_subscription($payment_ref_number);
        }
      }

      return $has_susbcription;
    }elseif($prod['billing_info']['type']=="Subscription"){
      $has_susbcription = false;

      $gateway_subscription = $db->select( "SELECT * FROM payment_gateway WHERE gateway_type = 'PAYPAL_SUBSCRIPTION'" );
      $gateway_subs_id = 0;
      if (count($gateway_subscription)) {
        $gateway_subscription = $gateway_subscription[0];
        $gateway_subs_id = $gateway_subscription->id;
      }

      $sql = "SELECT * FROM orders o 
              INNER JOIN order_details od ON o.id = od.order_id and od.status = 'active' and od.product_id = '{$pid}' 
              INNER JOIN order_payments op ON o.id = op.order_id and op.payment_mode_id = '{$gateway_subs_id}' 
              WHERE o.cid = '{$cid}' and o.payment_method_id = '{$gateway_subs_id}'";

      $order_detail = $db->select( $sql );

      if (count($order_detail)) {
        $payment_ref_number = $order_detail[0]->payment_ref_number;
        $has_susbcription = cms_validate_paypal_subscription($payment_ref_number);
      }

      return $has_susbcription;
    }else{
      $has_susbcription = false;

      $sql = "SELECT o.id o_id, op.id op_id, op.* FROM orders o 
              LEFT JOIN order_details od on od.order_id = o.id 
              LEFT JOIN order_payments op on op.order_id = o.id 
              WHERE od.product_id = '{$pid}' and o.cid = '{$cid}'";
      $subs = $db->select( $sql );

      if (count($subs)) { $has_susbcription = true; }

      return $has_susbcription;
    }
  }else{
    return false;
  }
}
function cms_validate_paypal_subscription($payment_ref_number){
  require_once ROOT . "admin/libraries/plugins/cms-api/cms-paypal.php";
  $cms_paypal = new CMS_Paypal();
  $result = $cms_paypal->billing_agreement_get($payment_ref_number);

  if (doubleval($result["value"]["agreement_details"]["outstanding_balance"]["value"]) > 0) {
    return false;
  }else{
    return true;
  }
}
function get_global_subscriptions(){
  global $db;

  $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : 0;

  $sql = "SELECT c.*, od.order_id, op.payment_ref_number FROM cms_items c
          LEFT JOIN order_details od ON c.value = od.item_name and od.status='active'
          LEFT JOIN orders o ON o.id = od.order_id and o.order_status = 'active' and o.cid = '{$customer_id}'
          LEFT JOIN order_payments op ON o.id = op.order_id
          WHERE c.type='paypal-subscription-plan' and c.status='active'
          ORDER BY c.id ASC";

  $res    = $db->select( $sql );
  $items  = array();

  foreach ($res as $key => $value) {
    $meta = json_decode($value->meta, true);
    if (isset($meta['plan']) && isset($meta['plan']['state']) && $meta['plan']['state'] == 'ACTIVE') {
      $items[$value->id] = isset($items[$value->id]) ? $items[$value->id] : $meta;
      $items[$value->id]['subscriptions'] = isset($items[$value->id]['subscriptions']) ? $items[$value->id]['subscriptions'] : array();

      if (!empty($value->payment_ref_number)) {
        $items[$value->id]['subscriptions'][] = $value->payment_ref_number;
      }
    }
  }

  return $items;
}
function cms_payment_paypal_subscription(){
  $path = get_active_theme_location();
  if (is_file("{$path}product-global-subscription-item.php")) {
    include "{$path}product-global-subscription-item.php";
  }else{
    include __DIR__ . "/../frontend/views/fragments/product-global-subscription-item.php";
  }
}
function customer_has_subscription_for_plan($plan_id=""){
  if (isset($_SESSION['customer']['subscription_info'])) {
    if (isset($_SESSION['customer']['subscription_info'][$plan_id]) && $_SESSION['customer']['subscription_info'][$plan_id] == 'Active') {
      return true;
    }
    return false;
  }else{
    return false;
  }
}

/*
Tag: [cms_subscription_confirmed]
Usage: Add the tag inside the content section of a Page.
Description:
This function will handle the incoming Paypal Confirmation
*/
function cms_subscription_confirmed(){
  require_once ROOT . "admin/libraries/plugins/cms-api/cms-paypal.php";

  if (isset($_GET['token'])) {
    $db = new Database();
    $cms_paypal = new CMS_Paypal();
    $token = isset($_GET['token']) ? $_GET['token'] : "";
    $agreement = $cms_paypal->billing_agreement_execute( $token );
    $billing_info = $agreement['value'];

    /* Get if payment_reference_id exist */
    $existing_agreement = $db->select("SELECT * FROM `order_payments` Where payment_ref_number = '{$billing_info['id']}'");

    if (count($existing_agreement) <= 0) {
      /* Start Saving to Order Table */
      $customer = $billing_info['payer'];//$_SESSION['customer'];

      $country = $db->select("SELECT * FROM `countries` Where code = '{$customer['payer_info']['shipping_address']['country_code']}'");
      if (count($country) > 0) {
        $country = $country[0]->name;
      }else{
        $country = '';
      }

      $customer_data = array(
        'id'    => isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : 0, // 0 if there is no account in the system
        'phone' => $_SESSION['customer'],
        'email' => $customer['payer_info']['email'],
        'first_name'  => $customer['payer_info']['first_name'],
        'last_name'   => $customer['payer_info']['last_name'],
        'billing_country' => $country,
        'billing_address' => $customer['payer_info']['shipping_address']['line1'],
        'billing_address_line_2' => '',
        'billing_city'    => $customer['payer_info']['shipping_address']['city'],
        'billing_postal'  => $customer['payer_info']['shipping_address']['postal_code'],
      );

      $invoice_number = doubleval(get_system_option('invoice_next_number'));
      $order_meta = array(
        'plan_id'           => $_SESSION['paypal_selected_subscription'],
        'payer'             => $billing_info['payer'],
        'shipping_address'  => $billing_info['shipping_address'],
        'plan'              => $billing_info['plan'],
        'agreement_details' => $billing_info['agreement_details'],
      );
      $order = array(
        'cid'                     => $_SESSION['customer_id'],
        'first_name'              => $customer['payer_info']['first_name'],
        'last_name'               => $customer['payer_info']['last_name'],
        'company'                 => "",
        'phone'                   => $_SESSION['customer']['phone'],
        'email'                   => $customer['payer_info']['email'],
        'billing_name'            => "",
        'billing_address'         => $customer['payer_info']['shipping_address']['line1'],
        'billing_address_line_2'  => "",
        'billing_city'            => $customer['payer_info']['shipping_address']['city'],
        'billing_postal'          => $customer['payer_info']['shipping_address']['postal_code'],
        'billing_state'           => $customer['payer_info']['shipping_address']['state'],
        'billing_country'         => $country,
        'billing_email'           => $customer['payer_info']['email'],
        'billing_phone'           => $_SESSION['customer']['phone'],
        'shipping_name'           => "",
        'shipping_address'        => $customer['payer_info']['shipping_address']['line1'],
        'shipping_address_line_2' => "",
        'shipping_city'           => $customer['payer_info']['shipping_address']['city'],
        'shipping_postal'         => $customer['payer_info']['shipping_address']['postal_code'],
        'shipping_state'          => $customer['payer_info']['shipping_address']['state'],
        'shipping_country'        => $country,
        'shipping_email'          => $customer['payer_info']['email'],
        'shipping_phone'          => $_SESSION['customer']['phone'],
        'payment_method_id'       => "7",
        'meta_data'               => json_encode($order_meta),
        'message'                 => "",
        'invoice_number'          => $invoice_number,
        'order_status'            => 'active',
      );
      $db->table  = 'orders';
      $db->data   = $order;
      $order_id = $db->insertGetID();

      $payment_date = strtotime($billing_info['start_date']);
      $order_payments = array(
        'order_id' => $order_id,
        'payment_issue_date' => date('Y-m-d H:i:s', $payment_date),
        'payment_ref_number' => $billing_info['id'],
        'payment_mode_id' => '7',
        'payment_amount' => 0,
        'payment_gst' => 'Yes',
        'payment_total_amount' => 0,
        'payment_status' => '1',
        'payment_description' => 'Paypal Subscription',
        'voucher_number' => $invoice_number,
        'recieved_by' => '0',
      );
      $db->table  = 'order_payments';
      $db->data   = $order_payments;
      $order_payment_id = $db->insertGetID();

      $db->table  = 'order_details';
      $order_detail_id = array();
      if (isset($_SESSION['subscribed_products'])) {
        foreach ($_SESSION['subscribed_products'] as $key => $value) {
          $order_detail = array(
            'order_id' => $order_id,
            'product_id' => $value,
            'product_option_id' => '',
            'product_option' => 'subscription',
            'image_url' => '',
            'item_name' => $_SESSION['paypal_selected_subscription'],
            'quantity' => 1,
            'price' => 0,
            'status' => 'active',
          );
          $db->data   = $order_detail;
          $order_detail_id[] = $db->insertGetID();
        }
      }else{
        $order_detail = array(
          'order_id' => $order_id,
          'product_id' => '0',
          'product_option_id' => '',
          'product_option' => 'subscription',
          'image_url' => '',
          'item_name' => $_SESSION['paypal_selected_subscription'],
          'quantity' => 1,
          'price' => 0,
          'status' => 'active',
        );
        $db->data   = $order_detail;
        $order_detail_id[] = $db->insertGetID();
      }

      /* Update Next Invoice Number */
      update_invoice_number();

      $res = array(
        'order_id' => $order_id,
        'order_payment_id' => $order_payment_id,
        'order_detail' => array( $order_detail_id ),
      );

      return "Thank you. Your subscription has been confirmed.";
    }else{
      return "Duplicate Entry";
    }
  }else{
    return "No transaction found";
  }
}