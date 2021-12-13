<?php

include __DIR__ . "/../PayPal-PHP-SDK/autoload.php";

use Curl\Curl;

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

use PayPal\Api\Agreement;
use PayPal\Api\Agreement\AgreementStateDescriptor;
use PayPal\Api\ShippingAddress;

class CMS_Paypal extends Model{

  public  $paypal_base_url = "";
  public  $options = array();
  private $paypal_api_url = array();
  private $username = "";
  private $password = "";

  public function __construct($initialize = true){
    parent::__construct();
    if ($initialize) {
      $this->initialize();
    }
  }
  public function initialize($paypal_base_url = "https://api.sandbox.paypal.com"){
    $this->paypal_base_url = $paypal_base_url;
    $this->paypal_api_url = array(
      "base"                => $this->paypal_base_url,
      "token"               => $this->paypal_base_url . "/v1/oauth2/token",
      "payment"             => $this->paypal_base_url . "/v1/payments/payment/",
      "payment_execute"     => $this->paypal_base_url . "/v1/payments/payment/PAY-XXX/execute",
      "plan"                => $this->paypal_base_url . "/v1/payments/billing-plans/",
      "plan_list"           => $this->paypal_base_url . "/v1/payments/billing-plans/",
      "plan_update"         => $this->paypal_base_url . "/v1/payments/billing-plans/PAY-XXX/",
      "plan_detail"         => $this->paypal_base_url . "/v1/payments/billing-plans/PAY-XXX/",
      "agreement"           => $this->paypal_base_url . "/v1/payments/billing-agreements/",
      "agreement_execute"   => $this->paypal_base_url . "/v1/payments/billing-agreements/PAY-XXX/agreement-execute/",
    );

    $this->reset_gateway_options();
  }
  public function get_gateway_options($option_name = ""){
    $o = array();
    $temp = $this->db->select("SELECT * FROM `payment_gateway_options` WHERE option_name like '{$option_name}'");
    foreach ($temp as $key => $value) {
      $o[$value->option_name] = $value->option_value;
    }

    return $o;
  }
  public function cms_error($message = ""){
    $my_file = ROOT . '/pvs-error-log.txt';
    $handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
    fwrite($handle, $message . "\n");
    fclose($handle);
  }

  /* Set */
  public function reset_gateway_options(){
    $o = $this->get_gateway_options('paypal_checkout%');
    $s = $this->get_gateway_options('paypal_subscription%');

    $this->options = array_merge($o, $s);
    $this->set_credentials( $o['paypal_checkout_sandbox_client_id'], $o['paypal_checkout_sandbox_secret'] );
  }
  public function set_credentials($id = "", $secret = ""){
    $this->username = $id;
    $this->password = $secret;
  }
  public function test_connection(){
    $curl = new Curl();
    $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
    $curl->setBasicAuthentication($this->username, $this->password);
    $curl->post($this->paypal_api_url['token'] , array('grant_type' => 'client_credentials'));

    $access_token = false;

    if (!$curl->error) {
      if (isset($curl->response->access_token)) {
        $access_token = true;
      }
    }

      return $access_token;
  }

  /* Final API Calls */
  /* API Context */
  public function paypal_context(){
    $apiContext = new \PayPal\Rest\ApiContext(
                    new \PayPal\Auth\OAuthTokenCredential(
                      $this->options['paypal_subscription_client_id'], 
                      $this->options['paypal_subscription_secret']
                    ));
    return $apiContext;
  }
  /* Checkout Functions */
  public function create_payment($data = array()){
    $output = array( 'status' => false, 'value' => null, 'message' => '' );

    /* USING SDK */
    $transaction_amount = $data['transactions'][0]['amount'];
    $transactions       = $data['transactions'][0]['item_list']['items'];

    $items = array();
    if ( $transactions ) {
      foreach ($transactions as $key => $value) {
        $item = new Item();
        $item->setName($value['name'])
          ->setDescription($value['description'])
          ->setCurrency($value['currency'])
          ->setQuantity($value['quantity'])
          ->setSku($value['sku'])
          ->setTax($value['tax'])
          ->setPrice($value['price']);
        $items[] = $item;
      }
    }

    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $itemList = new ItemList();
    $itemList->setItems($items);

    $details = new Details();
    $details->setShipping($transaction_amount['details']['shipping'])
      ->setTax($transaction_amount['details']['tax'])
      ->setHandlingFee($transaction_amount['details']['handling_fee'])
      ->setInsurance($transaction_amount['details']['insurance'])
      ->setSubtotal($transaction_amount['details']['subtotal']);

    $amount = new Amount();
    $amount->setCurrency($transaction_amount['currency'])
      ->setTotal($transaction_amount['total'])
      ->setDetails($details);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
      ->setItemList($itemList);
      // ->setDescription("Payment description")
      // ->setInvoiceNumber(uniqid());

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($data['redirect_urls']['return_url'])
        ->setCancelUrl($data['redirect_urls']['cancel_url']);

    $payment = new Payment();
    $payment->setIntent($data['intent'])
      ->setPayer($payer)
      ->setRedirectUrls($redirectUrls)
      ->setNoteToPayer($data['note_to_payer'])
      ->setTransactions(array($transaction));

    $request = clone $payment;

    try {
      $payment->create($this->paypal_context());
    } catch (Exception $ex) {
      $output['message'] = $ex;
      return $output;
    }

    $approvalUrl = $payment->getApprovalLink();

    $_SESSION['paypal-checkout']['payment'] = $payment->toArray();

    $output['value'] = true;
    $output['value'] = $payment->getId();

    return $output;

    /* USING SDK START */

    // if (isset($_SESSION['ecatalog_product_enquiry'])) {

    //   $payment_data = $this->get_payment_data();

    //   $access_token = $this->paypal_token();
    //   $access_token = isset($access_token['value']) ? $access_token['value'] : "";

    //   $curl = new Curl();
    //   $curl->setHeader('Content-Type', 'application/json');
    //   $curl->setHeader('Authorization', 'Bearer ' . $access_token);
    //   $curl->post( $this->paypal_api_url['payment'] , $payment_data);

    //   if (!$curl->error) {
    //     if (isset($curl->response->id)) {
    //       $_SESSION['paypal-checkout']['payment'] = $curl->response; /* Store create payment transaction to a session */

    //       $output['status']   = true;
    //       $output['value'] = $curl->response->id;
    //     }
    //   }else{
    //     $output['message']  = $curl->errorMessage;
    //   }
    // }else{
    //   $output['message'] = "No found items";
    // }

    // return $output;
  }
  public function execute_payment($paymentId = ""){
    $output = array( 'status' => false, 'value' => null, 'message' => '' );

    /* USING SDK */
    if ($paymentId != "") {
      $payer_id   = isset($_REQUEST['payerID']) ? $_REQUEST['payerID'] : '';
      $payment_id = isset($_SESSION['paypal']['payment']['id']) ? $_SESSION['paypal']['payment']['id'] : '';

      $payment = Payment::get($paymentId, $this->paypal_context());

      $execution = new PaymentExecution();
      $execution->setPayerId($payer_id);

      try {
        $result = $payment->execute($execution, $this->paypal_context());
        $payment_id = $payment->getId();

        try {
          $payment = Payment::get($paymentId, $this->paypal_context());

          $_SESSION['paypal-checkout']['execute'] = $payment->toArray();

          /* Save transaction */
          $order = $this->save_order();
          $order_items = $this->save_order_items($order);
          $order_additional = $this->save_order_additional_items($order);
          $order_delivery = $this->save_order_delivery_detail($order);
          $order_payment = $this->save_order_payment($order, $payment->toArray());

          /* Remove Transactions */
          unset($_SESSION['ecatalog_product_enquiry']);
          unset($_SESSION['product_current_user_detail_method']);
          unset($_SESSION['paypal']);
          unset($_SESSION['paypal-checkout']);

          $output['status'] = true;
          $output['value']  = $paymentId;

          cms_set_last_order_id($order);
        } catch (Exception $ex) {
          $output['message'] = $ex;
        }
      } catch (Exception $ex) {
        $output['message'] = $ex;
      }
    }

    return $output;
  }
  /* Billing/Subscription Functions */
  public function create_billing_plan($billing_data = array()){
    $output = array( 'status'  => false, 'value'   => null, 'message' => '' );
    $this->reset_gateway_options();

    /* Paypal SDK */
    $apiContext = $this->paypal_context();

    $plan = new Plan();
    $plan->setName($billing_data->name)
    ->setDescription($billing_data->description)
    ->setType($billing_data->type);

    $paymentDefinition = array();
    foreach ($billing_data->payment_definitions as $key => $value) {
      $p_def = new PaymentDefinition();
      $p_def->setName( $value->name )
      ->setType( $value->type )
      ->setFrequency( $value->frequency )
      ->setFrequencyInterval( $value->frequency_interval )
      ->setCycles( $value->cycles )
      ->setAmount(new Currency(array('value' => $value->amount->value, 'currency' => $value->amount->currency)));      

      $paymentDefinition[] = $p_def;
    }

    $merchantPreferences = new MerchantPreferences();
    $merchantPreferences->setReturnUrl($billing_data->merchant_preferences->return_url)
    ->setCancelUrl($billing_data->merchant_preferences->cancel_url)
    ->setAutoBillAmount($billing_data->merchant_preferences->auto_bill_amount)
    ->setInitialFailAmountAction($billing_data->merchant_preferences->initial_fail_amount_action)
    ->setMaxFailAttempts($billing_data->merchant_preferences->max_fail_attempts)
    ->setSetupFee(new Currency(array('value' => 0, 'currency' => 'USD')));

    $plan->setPaymentDefinitions($paymentDefinition);
    $plan->setMerchantPreferences($merchantPreferences);

    $request = clone $plan;

    try {
      $result = $plan->create($apiContext);
      $output['status']= true;
      $output['value'] = $result->toArray();

      return $output;
    } catch (Exception $ex) {
      $output['message']  = $ex;
      return $output;
    }
  }
  public function update_billing_plan($plan=array()){
    $output = array('status'=>false, 'value'=>array(), 'message'=>'');

    $billing_id = $plan['id'];

    $res = $this->billing_plan($billing_id);
    $createdPlan = $res['value'];

    $billing_plan = $plan;

    $patchRequest = new PatchRequest();

    try {
      /* Set first layer field - general info */
      $patch = new Patch();
      $value = array(
        'name' => $billing_plan['name'], 
        'description' => $billing_plan['description'], 
        'type' => $billing_plan['type'], 
      );
      $patch->setOp('replace')
          ->setPath('/')
          ->setValue($value);
      $patchRequest->addPatch($patch);

      /* Set second layer fields - merchant preferences */
      $patch = new Patch();
      $patch->setOp('replace')
          ->setPath('/merchant-preferences')
          ->setValue($billing_plan['merchant_preferences']);
      $patchRequest->addPatch($patch);

      /* Set second layer fields - payment definitions */
      $paymentDefinitions = $createdPlan->getPaymentDefinitions();
      for ($i=0; $i < count($paymentDefinitions); $i++) { 
        $patch = new Patch();

        $paymentDefinitionId = $paymentDefinitions[$i]->getId();

        if (isset($billing_plan['payment_definitions'][$i])) {
          $payment_definition_value = $billing_plan['payment_definitions'][$i];
          unset($payment_definition_value['type']);
          unset($payment_definition_value['id']);

          $patch->setOp('replace')
          ->setPath('/payment-definitions/' . $paymentDefinitionId)
          ->setValue($payment_definition_value);
        }

        $patchRequest->addPatch($patch);
      }

      /* execute patch */
      // $createdPlan->setMerchantPreferences($merchantPreference);
      $apiContext = $this->paypal_context();
      $createdPlan->update($patchRequest, $apiContext);

      /* retrieve updated Billing Plan Info */
      $apiContext = $this->paypal_context();
      $plan = Plan::get($createdPlan->getId(), $apiContext);

      $output['status'] = true;
      $output['value'] = $plan->toArray();
    } catch (Exception $ex) {
      $output['message'] = $ex;
    }

    return $output;
  }
  public function delete_billing_plan($plan_id = ""){
    $output = array( 'status'  => false, 'value' => '', 'message' => '', );

    $res = $this->billing_plan($plan_id);
    $createdPlan = $res['value'];

    if ($createdPlan) {
      try {
        /* Start the Update */
        $createdPlan->delete($this->paypal_context());

        $output['status'] = true;
        $output['value']  = "";
        $output['message'] = "Susbcription Plan is Deleted";
      } catch (Exception $ex) {
        $output['message'] = $ex;
      }
    }else{
      $output['message'] = "Unable to Retrieved Subscription Plan";
    }

    return $output;
  }
  public function billing_plan($id, $toArray=false){
    $output = array( 'status'  => false, 'value'   => null, 'message' => '', );

    /* SDK*/
    try {
      $plan = Plan::get($id, $this->paypal_context());
      $output['status'] = true;
      $output['value'] = $toArray ? $plan->toArray() : $plan;
    } catch (Exception $ex) {
      $output['message'] = $ex;
    }
    /* SDK*/

    return $output;
  }
  public function billing_plan_activate($transaction_id=""){
    $output = array( 'status'  => false, 'value'   => '', 'message' => '', );

    $res = $this->billing_plan($transaction_id);
    $createdPlan = $res['value'];

    try {
      /* generate patch data - Active State */
      $value = array('state'=>'ACTIVE');

      /* Start the patch*/
      $patch = new Patch();
      $patch->setOp('replace')
          ->setPath('/')
          ->setValue($value);

      /* Create a Request  */
      $patchRequest = new PatchRequest();
      $patchRequest->addPatch($patch);
      /* Start the Update */
      $apiContext = $this->paypal_context();
      $createdPlan->update($patchRequest, $apiContext);
      /* Retrieve update Billing Plan */
      $plan = Plan::get($createdPlan->getId(), $apiContext);

      $output['status'] = true;
      $output['value']  = $plan;
    } catch (Exception $ex) {
      $output['message'] = $ex;
    }

    return $output;
  }
  public function billing_agreement_create($plan_info = array(), $customer = array()){
    $datetime = new DateTime(date("Y-m-d H:i:s"));
    $plan_info = array(
      'id'    => $plan_info['id'],
      'name'  => $plan_info['name'],
      'description' => $plan_info['description'],
      // 'start_date'  => $datetime->format(DateTime::ATOM), // '2019-06-17T9:45:04Z'
    );

    $agreement = new Agreement();
    $agreement->setName($plan_info['name'])
      ->setDescription($plan_info['description'])
      ->setStartDate('2019-06-17T9:45:04Z');

    $plan = new Plan();
    $plan->setId($plan_info['id']);
    $agreement->setPlan($plan);

    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $agreement->setPayer($payer);

    // $shippingAddress = new ShippingAddress();
    // $shippingAddress->setLine1('111 First Street')
    //     ->setCity('Saratoga')
    //     ->setState('CA')
    //     ->setPostalCode('95070')
    //     ->setCountryCode('US');
    // $agreement->setShippingAddress($shippingAddress);

    $request = clone $agreement;

    try {
      $agreement    = $agreement->create($this->paypal_context());
      $approvalUrl  = $agreement->getApprovalLink();

      $result = array(
        'status' => true,
        'agreement' => $agreement->toArray(),
        'approval'  => $approvalUrl,
      );

      return $result;
    } catch (Exception $ex) {

      return array( 'status'=>false, 'value'=>$ex, 'message'=> 'Error!');
    }
  }
  public function billing_agreement_execute($token_agreement = ""){
    $output = array( 'status'  => false, 'value'   => '', 'message' => '', );

    if ($token_agreement != "") {
      $token = $token_agreement;
      $data = array();

      $agreement = new \PayPal\Api\Agreement();
      try {
        $agreement->execute($token, $this->paypal_context());
        $data = $agreement->toArray();
      } catch (Exception $ex) {
        $error = array(
          "action" => "Executed an Agreement",
          "title" => "Agreement",
          "id" => $agreement->getId(),
          "token" => $token_agreement,
          "data" => $ex,
        );

        return array( 'status'  => false, 'value'   => $error, 'message' => 'Error!');
      }

      $output = $this->billing_agreement_get( $agreement->getId() );
    }else{
      return array( 'status'  => false, 'value'   => "", 'message' => 'Error!');
    }

    return $output;
  }
  public function billing_agreement_get($agreement_id = ""){
    if ($agreement_id != "") {
      try {
        $agreement = \PayPal\Api\Agreement::get($agreement_id, $this->paypal_context());
        $data = $agreement->toArray();
      } catch (Exception $ex) {
        $error = array(
          "action" => "Get Agreement",
          "title" => "Agreement",
          "id" => null,
          "token" => null,
          "data" => $ex,
        );
        return array( 'status'  => false, 'value'   => $error, 'message' => 'Error!');
      }
      
      $output['status'] = true;
      $output['value']  = $data;
    }else{
      return array( 'status'  => false, 'value'   => "", 'message' => 'Error!');
    }

    return $output;
  }
  public function billing_agreement_suspend($agreement_id = ""){
    if ($agreement_id != "") {
      try {
        /* Get Agreement */
        $agreement = \PayPal\Api\Agreement::get($agreement_id, $this->paypal_context());
        $a = $agreement->toArray();

        if (isset($a['state'])) {
          if (strtolower($a['state']) != 'suspended') {
            /* Create Suspend Description */
            $suspention_description = "Suspending the agreement";
            $agreementStateDescriptor = new \PayPal\Api\AgreementStateDescriptor();
            $agreementStateDescriptor->setNote($suspention_description);

            /* Suspend Agreement */
            $agreement->suspend($agreementStateDescriptor, $this->paypal_context());

            $agreement = \PayPal\Api\Agreement::get($agreement_id, $this->paypal_context());
            $data = $agreement->toArray();

            if (isset($data['state']) && strtolower($data['state']) == 'suspended') {
              return array( 'status' => true, 'value' => "Subscription Suspended!", 'message' => 'Subscription Suspended!');
            }else{
              return array( 'status' => false, 'value' => "Unable to suspend selected account!", 'message' => 'Unable to suspend selected account!');
            }
          }else{
            /* Already Suspended */
            return array( 'status' => true, 'value' => "Already Suspended!", 'message' => 'Already Suspended!');
          }
        }else{
          /* Unable to find subscriber */
          return array( 'status' => false, 'value' => "Unable to find account!", 'message' => 'Unable to find account!');
        }
      } catch (Exception $ex) {
        return array( 'status' => false, 'value' => "Unable to suspend selected account", 'message' => 'Unable to suspend selected account!');
      }
    }else{
      return array( 'status' => false, 'value' => "", 'message' => 'Error!');
    }
  }
  public function billing_agreement_reactivate($agreement_id = ""){
    if ($agreement_id != "") {
      try {
        /* Get Agreement */
        $agreement = \PayPal\Api\Agreement::get($agreement_id, $this->paypal_context());
        $a = $agreement->toArray();

        if (isset($a['state'])) {
          if (strtolower($a['state']) == 'suspended') {
            /* Create Suspend Description */
            $suspention_description = "Reactivating the agreement";
            $agreementStateDescriptor = new \PayPal\Api\AgreementStateDescriptor();
            $agreementStateDescriptor->setNote($suspention_description);

            /* reActivate Agreement */
            $agreement->reActivate($agreementStateDescriptor, $this->paypal_context());
            $data = $agreement->toArray();

            if (isset($data['state']) && strtolower($data['state']) == 'suspended') {
              return array( 'status' => true, 'value' => "Subscription Reactivated!", 'message' => 'Subscription Reactivated!');
            }else{
              return array( 'status' => false, 'value' => "Unable to reactivate selected account!", 'message' => 'Unable to reactivate selected account!');
            }
          }else{
            /* Already Suspended */
            return array( 'status' => true, 'value' => "Already Activated!", 'message' => 'Already Activated!');
          }
        }else{
          /* Unable to find subscriber */
          return array( 'status' => false, 'value' => "Unable to find account!", 'message' => 'Unable to find account!');
        }
      } catch (Exception $ex) {
        return array( 'status' => false, 'value' => "Unable to reactivate selected account", 'message' => 'Unable to reactivate selected account!');
      }
    }else{
      return array( 'status' => false, 'value' => "", 'message' => 'Error!');
    }
  }

  /* System Processing */
  public function save_order(){
    $data = $this->get_user_detail();

    $this->db->table = "orders";
    $this->db->data = $data;
    $order = $this->db->insertGetID();

    return $order;
  }
  public function save_order_items($order_id = 0){
    $items = $_SESSION['ecatalog_product_enquiry'];
    $item_ids = array();

    $this->db->table = "order_details";
    foreach ($items as $key => $value) {
      $data = array(
        "order_id"          => $order_id,
        "product_id"        => $value['product']['id'],
        "product_option_id" => "",
        "product_option"    => "",
        "image_url"         => $value['product']['featured_image_url'],
        "item_name"         => $value['product']['product_name'],
        "quantity"          => $value['quantity'],
        "price"             => $value['product']['price'],
        "status"            => "active",
      );

      $this->db->data = $data;
      $order = $this->db->insertGetID();

      $item_ids[] = $order;
    }
    return $item_ids;
  }
  public function save_order_additional_items($order_id = 0){
    $items = array(); /* replace with array additional items */
    $item_ids = array();

    $this->db->table = "orders_additional";
    foreach ($items as $key => $value) {
      $data = array(
        "order_id"          => $order_id,
        "product_id"        => isset($value['product_id']) ? $value['product_id'] : '',
        "new_product_name"  => isset($value['product_name']) ? $value['product_name'] : '',
        "quantity"          => isset($value['quantity']) ? $value['quantity'] : '',
        "price"             => isset($value['price']) ? $value['price'] : '',
      );

      $this->db->data = $data;
      $order = $this->db->insertGetID();

      $item_ids[] = $order;
    }
    return $item_ids;
  }
  public function save_order_delivery_detail($order_id = 0){
    $items = array(); /* replace with array additional items */
    $item_ids = array();

    $this->db->table = "order_delivery_detail";
    foreach ($items as $key => $value) {
      $data = array(
        "mode"              => isset($value['mode']) ? $value['mode'] : '',
        "order_id"          => $order_id,
        "delivery_date"     => isset($value['delivery_date']) ? $value['delivery_date'] : '',
        "delivery_time"     => isset($value['delivery_time']) ? $value['delivery_time'] : '',
        "delivery_address"  => isset($value['delivery_address']) ? $value['delivery_address'] : '',
        "delivery_postal"   => isset($value['delivery_postal']) ? $value['delivery_postal'] : '',
        "delivery_type"     => isset($value['delivery_type']) ? $value['delivery_type'] : '',
      );

      $this->db->data = $data;
      $order = $this->db->insertGetID();

      $item_ids[] = $order;
    }
    return $item_ids;
  }
  public function save_order_payment($order_id = 0, $payment_detail = array()){
    $items = array(); /* replace with array additional items */
    $checkout = $_SESSION['paypal-checkout']['execute'];
    $transaction = $checkout['transactions'][0];

    $this->db->table = "order_payments";
    $data = array(
      "order_id"            => $order_id,
      "payment_issue_date"  => isset($checkout['create_time']) ? date("Y-m-d H:i:s", strtotime($checkout['create_time'])) : date("Y-m-d H:i:s"),
      "payment_ref_number"  => isset($checkout['id']) ? $checkout['id'] : "",
      "payment_mode_id"     => $_SESSION['product_current_user_detail_method'],
      "payment_amount"      => isset($transaction['amount']['total']) ? $transaction['amount']['total'] : "",
      "payment_gst"         => isset($checkout['payment_gst']) ? $checkout['payment_gst'] : "",
      "payment_total_amount"=> isset($transaction['amount']['total']) ? $transaction['amount']['total'] : "",
      "payment_status"      => 1,
      "payment_description" => "",
      "voucher_number"      => 0,
      "recieved_by"         => "",
    );

    $this->db->data = $data;
    $order = $this->db->insertGetID();

    $item_ids[] = $order;
    return $item_ids;
  }

  /* Data Generators */
  public function get_payment_data(){
    unset($_SESSION['paypal-checkout']); /* to reset paypal-checkout session*/

    $output = array(
      'status'  => false,
      'value'   => '',
      'message'   => '',
    );

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

      // $shipping = array();
      // if ( isset($current_user['shipping_address']) && $current_user['shipping_address'] != "" && 
      //    isset($current_user['shipping_postal']) && $current_user['shipping_postal'] != "") {
      //   if ( isset($current_user['shipping_address']) && $current_user['shipping_address']!="" ) { $shipping['line1'] = $current_user['shipping_address']; } //required
      //   if ( isset($current_user['shipping_address_line_2']) && $current_user['shipping_address_line_2']!="" ) { $shipping['line2'] = $current_user['shipping_address_line_2']; }
      //   if ( isset($current_user['shipping_city']) && $current_user['shipping_city']!="" ) { $shipping['city'] = $current_user['shipping_city']; }
      //   if ( isset($current_user['shipping_country']) && $current_user['shipping_country']!="" ) { $shipping['country_code'] = $this->get_country_code($current_user['shipping_country']); } //required
      //   if ( isset($current_user['shipping_postal']) && $current_user['shipping_postal']!="" ) { $shipping['postal_code'] = $current_user['shipping_postal']; }
      //   if ( isset($current_user['shipping_state']) && $current_user['shipping_state']!="" ) { $shipping['state'] = $current_user['shipping_state']; }
      //   if ( isset($current_user['shipping_phone']) && $current_user['shipping_phone']!="" ) { $shipping['phone'] = $current_user['shipping_phone']; }

      //   $item_list['shipping_address'] = $shipping;
      // }
      
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
  public function generate_billing_plan($data = array()){
    $payment_definition = array(
      array(
        "name" => $this->options['paypal_subscription_title_trial'],
        "type" => "TRIAL",
        "frequency" => $this->options['paypal_subscription_frequency_trial'],
        "frequency_interval" => $this->options['paypal_subscription_frequency_interval_trial'],
        "amount" => array(
          "value" => $this->options['paypal_subscription_amount_trial'], // Trial Amount
          "currency" => "USD"
        ),
        "cycles" => $this->options['paypal_subscription_cycle_trial'], // set 0 for infinite payment
      ),
      array(
        "name" => $this->options['paypal_subscription_title'],
        "type" => "REGULAR",
        "frequency" => $this->options['paypal_subscription_frequency'],
        "frequency_interval" => $this->options['paypal_subscription_frequency_interval'],
        "amount" => array(
          "value" => $this->options['paypal_subscription_amount'], // Regular Amount
          "currency" => "USD"
        ),
        "cycles" => $this->options['paypal_subscription_cycle'], // set 0 for infinite payment
      ),
    );

    $merchant_preferences = array(
      // "setup_fee" => array(
      //     "value" => "1",
      //     "currency" => "USD"
      //   ),
      "return_url" => $this->options['paypal_subscription_url_confirmed'],
      "cancel_url" => $this->options['paypal_subscription_url_cancelled'],
      "auto_bill_amount" => $this->options['paypal_subscription_plan_auto_billing'],
      "initial_fail_amount_action" => $this->options['paypal_subscription_plan_initial_fail_action'],
      "max_fail_attempts" => $this->options['paypal_subscription_plan_max_fail_attempts']
    );

    $plan = array(
      "name" => $this->options['paypal_subscription_plan_name'],
      "description" => $this->options['paypal_subscription_plan_description'],
      "type" => $this->options['paypal_subscription_plan_type'],
      "payment_definitions" => $payment_definition,
      "merchant_preferences" => $merchant_preferences,
    );

    return $plan;
  }
  public function generate_billing_plan_old($override_data = array()){
    $payment_definition = array(
      array(
        "name" => $this->options['paypal_subscription_title_trial'],
        "type" => "TRIAL",
        "frequency" => $this->options['paypal_subscription_frequency_trial'],
        "frequency_interval" => $this->options['paypal_subscription_frequency_interval_trial'],
        "amount" => array(
          "value" => $this->options['paypal_subscription_amount_trial'], // Trial Amount
          "currency" => "USD"
        ),
        "cycles" => $this->options['paypal_subscription_cycle_trial'], // set 0 for infinite payment
      ),
      array(
        "name" => $this->options['paypal_subscription_title'],
        "type" => "REGULAR",
        "frequency" => $this->options['paypal_subscription_frequency'],
        "frequency_interval" => $this->options['paypal_subscription_frequency_interval'],
        "amount" => array(
          "value" => $this->options['paypal_subscription_amount'], // Regular Amount
          "currency" => "USD"
        ),
        "cycles" => $this->options['paypal_subscription_cycle'], // set 0 for infinite payment
      ),
    );

    $merchant_preferences = array(
      // "setup_fee" => array(
      //     "value" => "1",
      //     "currency" => "USD"
      //   ),
      "return_url" => $this->options['paypal_subscription_url_confirmed'],
      "cancel_url" => $this->options['paypal_subscription_url_cancelled'],
      "auto_bill_amount" => $this->options['paypal_subscription_plan_auto_billing'],
      "initial_fail_amount_action" => $this->options['paypal_subscription_plan_initial_fail_action'],
      "max_fail_attempts" => $this->options['paypal_subscription_plan_max_fail_attempts']
    );

    $plan = array(
      "name" => $this->options['paypal_subscription_plan_name'],
      "description" => $this->options['paypal_subscription_plan_description'],
      "type" => $this->options['paypal_subscription_plan_type'],
      "payment_definitions" => $payment_definition,
      "merchant_preferences" => $merchant_preferences,
    );

    return $plan;
  }
  public function generate_agreement( $plan_id = "" ){
    $agreement = array();
    $datetime = new DateTime(date("Y-m-d H:i:s"));

    $agreement['name']        = $this->options['paypal_subscription_agreement_name'];
    $agreement['description'] = $this->options['paypal_subscription_agreement_description'];
    $agreement['start_date']  = $datetime->format(DateTime::ATOM);
    $agreement['plan']        = array( "id" => $plan_id );

    $payer = array();
    $payer['payment_method'] = "paypal";
    if (isset($_REQUEST['email'])) {
      $payer['payer_info'] = array( "email" => $_REQUEST['email'] );
    }
    $agreement['payer'] = $payer;

    return $agreement;
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
      $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : 0; // get the current logged in customer
      $data = array(
        "cid"             => $customer_id,
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

  /* Old Subscription Function */
  public function billing_plan_list(){
    $output = array( 'status'  => false, 'value'   => '', 'message' => '', );

    $access_token = $this->paypal_token();
    $access_token = isset($access_token['value']) ? $access_token['value'] : "";
    $paypal_url   = $this->paypal_api_url['plan_list'];

    $curl = new Curl();
    $curl->setHeader('Content-Type', 'application/json');
    $curl->setHeader('Authorization', 'Bearer ' . $access_token);
    $curl->get( "https://api.sandbox.paypal.com/v1/payments/billing-plans", array("status"=>'CREATED') );

    if (!$curl->error) {
      if (isset($curl->response->plans)){
        $output['status'] = true;
        $output['value']  = $curl->response;
        $_SESSION['paypal']['plan_list'] = $curl->response->plans;
      }
    }else{
      $output['message']  = $curl->errorMessage;
    }

    return $output;
  }
  public function billing_plan_delete($id=""){
    $output = array( 'status'  => false, 'value'   => array(), 'message' => '', );

    $payment_data = array(
      array(
        "op" => "replace",
        "path" => "/",
        "value" => array(
          "state" => "DELETED"
        ),
      )
    );

    $access_token = $this->paypal_token();
    $access_token = isset($access_token['value']) ? $access_token['value'] : "";
    $paypal_url   = str_replace("PAY-XXX", $id, $this->paypal_api_url['plan_update']);

    $curl = new Curl();
    $curl->setHeader('Content-Type', 'application/json');
    $curl->setHeader('Authorization', 'Bearer ' . $access_token);
    $curl->patch( $paypal_url, $payment_data );

    if (!$curl->error) {
      if (isset($curl->response->id)) {
        $output['status'] = true;
        $output['value']  = $curl->response;
      }
    }else{
      $output['message']  = $curl->errorMessage;
    }

    return $output;
  }
  public function billing_agreement_create_old($plan_id = ""){
    $output = array( 'status'  => false, 'value'   => '', 'message' => '', );

    $payment_data = $this->generate_agreement( $plan_id );

    $access_token = $this->paypal_token();
    $access_token = isset($access_token['value']) ? $access_token['value'] : "";

    $curl = new Curl();
    $curl->setHeader('Content-Type', 'application/json');
    $curl->setHeader('Authorization', 'Bearer ' . $access_token);
    $curl->post( $this->paypal_api_url['agreement'] , $payment_data);

    if (!$curl->error) {
      if (isset($curl->response->id)) {
        /* Store create payment transaction to a session */
        $_SESSION['paypal-checkout']['agreement'] = $curl->response; 

        $output['status'] = true;
        $output['value']  = $curl->response->id;
      }
    }else{
      $output['message']  = $curl->errorMessage;
    }

    return $output;
  }
  public function billing_agreement_execute_old($token_agreement = ""){
    $output = array( 'status'  => false, 'value'   => '', 'message' => '', );

    $payment_data = array();

    $access_token = $this->paypal_token();
    $access_token = isset($access_token['value']) ? $access_token['value'] : "";
    $execute_agreement = str_replace("PAY-XXX", $token_agreement, $this->paypal_api_url['agreement_execute']);

    $curl = new Curl();
    $curl->setHeader('Content-Type', 'application/json');
    $curl->setHeader('Authorization', 'Bearer ' . $access_token);
    $curl->post( $execute_agreement , $payment_data);

    if (!$curl->error) {
      if (isset($curl->response)) {
        /* Store create payment transaction to a session */
        $_SESSION['paypal']['agreement_execute'] = $curl->response; 

        $output['status'] = true;
        $output['value']  = $curl->response;
      }
    }else{
      $output['message']  = $curl->errorMessage;
    }

    return $output;
  }
  /* Old Subscription Function End */
}