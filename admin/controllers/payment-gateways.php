<?php
include ROOT . "libraries/plugins/php-curl-class-master/src/Curl/Curl.php";
include ROOT . "libraries/plugins/php-curl-class-master/src/Curl/CaseInsensitiveArray.php";
require_once ROOT."libraries/plugins/cms-api/cms-paypal.php";

class PaymentGateways extends Controller{
    public $cms_paypal;

	function __construct(){
		parent::__construct();
        Session::handleLogin();

        $this->cms_paypal = new CMS_Paypal();
	}
    /* Main pages*/
	function index(){
		$data = $this->model->get_data();
        $gateway = $this->model->get_getway();
        $payment_options = $this->model->get_getway_options();

        $this->view->set('gateway', $gateway);
        $this->view->set('options', $payment_options);
		$this->view->set('settings', $data);

        $this->view->render('header');
        $this->view->render('payment-gateways/index');
        $this->view->render('footer');
    }
    function subscription(){
        $gateway = $this->model->get_getway();
        $payment_options = $this->model->get_getway_options();

        $payment_options['paypal_subscription_client_id'] = isset($payment_options['paypal_subscription_client_id']) ? $payment_options['paypal_subscription_client_id'] : '';
        $payment_options['paypal_subscription_secret'] = isset($payment_options['paypal_subscription_secret']) ? $payment_options['paypal_subscription_secret'] : '';
        $payment_options['prod_subs_default_return'] = isset($payment_options['prod_subs_default_return']) ? $payment_options['prod_subs_default_return'] : '';
        $payment_options['prod_subs_default_cancel'] = isset($payment_options['prod_subs_default_cancel']) ? $payment_options['prod_subs_default_cancel'] : '';
        $payment_options['prod_subs_default_type'] = isset($payment_options['prod_subs_default_type']) ? $payment_options['prod_subs_default_type'] : '';
        $payment_options['prod_subs_default_auto_billing'] = isset($payment_options['prod_subs_default_auto_billing']) ? $payment_options['prod_subs_default_auto_billing'] : '';
        $payment_options['prod_subs_default_initial_fail_action'] = isset($payment_options['prod_subs_default_initial_fail_action']) ? $payment_options['prod_subs_default_initial_fail_action'] : '';
        $payment_options['prod_subs_default_max_fail_attempts'] = isset($payment_options['prod_subs_default_max_fail_attempts']) ? $payment_options['prod_subs_default_max_fail_attempts'] : '';
        $payment_options['prod_subs_default_agreement_name'] = isset($payment_options['prod_subs_default_agreement_name']) ? $payment_options['prod_subs_default_agreement_name'] : '';
        $payment_options['prod_subs_default_agreement_description'] = isset($payment_options['prod_subs_default_agreement_description']) ? $payment_options['prod_subs_default_agreement_description'] : '';

        $this->view->set('gateway', $gateway);
        $this->view->set('options', $payment_options);

        $this->view->setScriptFiles(array('payment-gateways-subscription'));
        $this->view->setStyleFiles(array('subscription'));

        $this->view->render('header');
        $this->view->render('payment-gateways/subscription');
        $this->view->render('footer');
    }

    function save_currency_settings(){
    	if(hasPost('action','save_settings')){

    		$data = $_POST['data'];

            $result = array();

    		foreach ($data as $key => $value) {
    			if(!$this->model->save_settings($key, $value))
                    $result[] = "Unable to save ". $value;
    		}
    		
    		echo json_encode($result);
    	}
    }
    function save_gateways(){
        if(hasPost('action','save_gateways')){
            $data_options   = isset($_POST['data_options'])     ? $_POST['data_options']    : array();
            $data_gateways  = isset($_POST['data_gateways'])    ? $_POST['data_gateways']   : array();
            $payment_method = isset($_POST['payment_method'])   ? $_POST['payment_method']  : array();
            $products       = isset($_POST['product'])          ? $_POST['product']         : array();

            $result = array();

            foreach ($payment_method as $key => $value) {
                $pg = $value['detail'];
                $this->model->db->table = "payment_gateway";
                $new_id = $this->model->db->insert_returl_ID("INSERT INTO `payment_gateway` (`id`, `display_name`, `gateway_type`, `tax`, `enabled`) VALUES('{$pg['id']}', '{$pg['display_name']}', '{$pg['gateway_type']}', '{$pg['tax']}', '{$pg['enabled']}') ON DUPLICATE KEY UPDATE `display_name`='{$pg['display_name']}',`gateway_type`='{$pg['gateway_type']}',`tax`='{$pg['tax']}',`enabled`='{$pg['enabled']}'");

                $this->model->db->table = "payment_gateway_options";

                $new_id = $new_id != 0 ? $new_id : $pg['id'];
                foreach ($value['options'] as $option_key => $option_value) {
                    $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('{$pg['id']}', '{$option_key}', '{$option_value}') ON DUPLICATE KEY UPDATE `option_value`='{$option_value}',`payment_gateway_id`='{$pg['id']}'");
                }

                // if ($pg['gateway_type'] == 'PAYPAL_SUBSCRIPTION') {
                //     $plans = $this->model->db->select("SELECT * FROM `cms_items` WHERE type = 'paypal-subscription-plan'");

                //     if (count($plans)) {
                //         $plans = $plans[0];

                //         if ($plans->status == 'pending') {
                //             $result = $this->cms_paypal->billing_plan_update($plans->value, "replace");

                //             if ($result['status']) {
                //                 $data = array(
                //                     "id"    => $plans->id,
                //                     "meta"  => json_encode($result['value']->toArray()),
                //                 );
                //                 $this->model->db->data  = $data;
                //                 $this->model->db->table = "cms_items";
                //                 $res = $this->model->db->update();
                //             }
                //         }
                //     }else{
                //         $plan = $this->cms_paypal->create_billing_plan();

                //         if (isset($plan['value']['id']) && $plan['value']['id'] != "") {
                //             $data = array(
                //                 "guid"  => '',
                //                 "type"  => 'paypal-subscription-plan',
                //                 "value" => $plan['value']['id'],
                //                 "meta"  => json_encode($plan['value']),
                //                 "status"=> 'pending',
                //             );

                //             $this->model->db->data = $data;
                //             $this->model->db->table = "cms_items";
                //             $res = $this->model->db->insertGetID();
                //         }
                //     }
                // }
            }

            /* Product Default Subscription Settings  */
            $products['agreement_name'] = substr($products['agreement_name'], 0, 127);
            $products['agreement_desc'] = substr($products['agreement_desc'], 0, 127);
            
            $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('0', 'prod_subs_default_return', '{$products['confirmed']}') ON DUPLICATE KEY UPDATE `option_value`='{$products['confirmed']}',`payment_gateway_id`='0'");
            $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('0', 'prod_subs_default_cancel', '{$products['cancelled']}') ON DUPLICATE KEY UPDATE `option_value`='{$products['cancelled']}',`payment_gateway_id`='0'");
            $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('0', 'prod_subs_default_type', '{$products['type']}') ON DUPLICATE KEY UPDATE `option_value`='{$products['type']}',`payment_gateway_id`='0'");
            $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('0', 'prod_subs_default_auto_billing', '{$products['auto_billing']}') ON DUPLICATE KEY UPDATE `option_value`='{$products['auto_billing']}',`payment_gateway_id`='0'");
            $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('0', 'prod_subs_default_initial_fail_action', '{$products['initial_fail_action']}') ON DUPLICATE KEY UPDATE `option_value`='{$products['initial_fail_action']}',`payment_gateway_id`='0'");
            $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('0', 'prod_subs_default_max_fail_attempts', '{$products['max_fail_attempts']}') ON DUPLICATE KEY UPDATE `option_value`='{$products['max_fail_attempts']}',`payment_gateway_id`='0'");
            $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('0', 'prod_subs_default_agreement_name', '{$products['agreement_name']}') ON DUPLICATE KEY UPDATE `option_value`='{$products['agreement_name']}',`payment_gateway_id`='0'");
            $this->model->db->query("INSERT INTO `payment_gateway_options` (`payment_gateway_id`, `option_name`, `option_value`) VALUES('0', 'prod_subs_default_agreement_description', '{$products['agreement_desc']}') ON DUPLICATE KEY UPDATE `option_value`='{$products['agreement_desc']}',`payment_gateway_id`='0'");

            echo json_encode($result);
        }elseif(hasPost('action','save_subscription_plan')){
            $data_options = isset($_POST['options']) ? $_POST['options'] : array();
            $current_item = isset($_POST['id']) ? $_POST['id'] : array();
            $message = "";

            if (!$this->validate_subscription_plan_data($data_options)) {
                cms_header('404'); echo "Empty field."; exit();
            }

            /* Set Data Start */
            $paypal_definition = array();
            $paypal_definition[0] = array(
                "id" => "", //$data_options->paypal_subscription_definition_id_trial
                "name" => $data_options['paypal_subscription_title_trial'],
                "type" => 'TRIAL',
                "cycles" => $data_options['paypal_subscription_cycle_trial'],
                "frequency" => $data_options['paypal_subscription_frequency_trial'],
                "frequency_interval" => $data_options['paypal_subscription_frequency_interval_trial'],
                "amount" => array(
                    "currency" => "USD",
                    "value" => $data_options['paypal_subscription_amount_trial']
                ),
            );
            $paypal_definition[1] = array(
                "id" => "", //$data_options['paypal_subscription_definition_id']
                "name" => $data_options['paypal_subscription_title'],
                "type" => 'REGULAR',
                "cycles" => $data_options['paypal_subscription_cycle'],
                "frequency" => $data_options['paypal_subscription_frequency'],
                "frequency_interval" => $data_options['paypal_subscription_frequency_interval'],
                "amount" => array(
                    "currency" => "USD",
                    "value" => $data_options['paypal_subscription_amount']
                ),
            );
            $plan = array(
                "id" => "", //$data_options['paypal_subscription_id'],
                "name" => $data_options['paypal_subscription_plan_name'],
                "description" => substr($data_options['paypal_subscription_plan_description'], 0, 127),
                "type" => $data_options['paypal_subscription_plan_type'],
                "payment_definitions" => $paypal_definition,
                "merchant_preferences" => array(
                    "max_fail_attempts" => $data_options['paypal_subscription_plan_max_fail_attempts'],
                    "return_url" => $data_options['paypal_subscription_url_confirmed'],
                    "cancel_url" => $data_options['paypal_subscription_url_cancelled'],
                    "auto_bill_amount" => $data_options['paypal_subscription_plan_auto_billing'],
                    "initial_fail_amount_action" => $data_options['paypal_subscription_plan_initial_fail_action'],
                ),
            );
            $agreement = array(
                'name' => $data_options['paypal_subscription_agreement_name'],
                'description' => substr($data_options['paypal_subscription_agreement_description'], 0, 127),
            );

            /* Set Data End */
            $current_plan = $this->model->get_subscription_plan($current_item);

            if (count($current_plan)) {
                $meta = json_decode($current_plan->meta, true);

                $plan = $this->get_modified_data($meta['plan'], $plan);

                $data = array(
                    'id'    => $current_plan->id,
                    // 'meta'  => json_encode(array(
                    //     "plan" => $plan,
                    //     "agreement" => $agreement,
                    // )),
                );

                if ($current_plan->status != 'active') {
                    $plan = $this->get_modified_data($meta['plan'], $plan);
                    $meta['plan'] = $plan;
                    $meta['agreement'] = $agreement;
                    $data['meta'] = json_encode($meta);
                }else{
                    $meta['agreement'] = $agreement;
                    $data['meta'] = json_encode($meta);
                }

                $this->model->db->data = $data;
                $this->model->db->table = "cms_items";

                if ($this->model->db->update()) {
                    $message = "Successfully Updated Subscription Plan.";
                    /*
                    status:
                    -[created]  - created in the database but not yet added to Paypal
                    -[pending]  - data is added to Paypal but not yet activateed. in this state, the Billing Plan is editable in Paypal
                    -[active]   - data is added to Paypal and have an ACTIVE status, this state, the Billing Plan is not editable..
                    */
                    if ($current_plan->status == 'created') {
                        /*
                        if status is 'created' executed an API call to register the detail to Paypal.
                        */

                        if ($this->register_a_plan($current_plan->id)) {
                            $message .= " Paypal Subscription Plan was created.";
                        }else{
                            $message .= " Subscription detail was not yet added to paypal.";
                        }
                    }elseif($current_plan->status == 'pending'){
                        /* execute an API call to update Billing Plan detail */
                        if ($this->update_a_plan($current_plan->id)) {
                            $message .= " Paypal Subscription Plan was update.";
                        }else{
                            $message .= " Unable to update Subscription detail on detail.";
                        }
                    }
                }else{
                    cms_header('404'); 
                    $message = "Error occured while saving a new Subscription Plan.";
                }
            }else{
                $data = array(
                    'guid' => "0",
                    'type' => "paypal-subscription-plan",
                    'value' => "",
                    'meta' => json_encode(array(
                        "plan" => $plan,
                        "agreement" => $agreement,
                    )),
                    'status' => "created",
                );

                $this->model->db->data = $data;
                $this->model->db->table = "cms_items";
                $new_subscription_plan_id = $this->model->db->insertGetID();
                if ($new_subscription_plan_id) {
                    $message = "Successfully create new Subscription Plan.";

                    /* PAYPAL API call: Create Billing Plan */
                    if ($this->register_a_plan($new_subscription_plan_id)) {
                        $message .= " Paypal Subscription Plan was created.";
                    }else{
                        $message .= " Subscription detail was not yet added to paypal.";
                    }
                    /* PAYPAL API call: Create Billing Plan */
                }else{
                    cms_header('404'); 
                    $message = "Error occured while saving a new Subscription Plan.";
                }
            }

            echo $message;
        }elseif(hasPost('action','save_checkout_paypal_button')){
            $json_data = json_decode($_POST['data']);
            $data = array(
                'pp_chkout_btn_label'   => $json_data->label,
                'pp_chkout_btn_size'    => $json_data->size,
                'pp_chkout_btn_shape'   => $json_data->shape,
                'pp_chkout_btn_color'   => $json_data->color,
                'pp_chkout_btn_tag'     => $json_data->tag,
            );

            $temp_gw    = $this->model->db->select("Select * From payment_gateway Where gateway_type = 'PAYPAL_CHECKOUT'");

            if (count($temp_gw) > 0) {
                $current_data = array();
                foreach ($this->model->db->select("Select * From payment_gateway_options Where option_name Like 'pp_chkout_btn_%'") as $key => $value) {
                    $current_data[] = $value->option_name;
                }

                foreach ($data as $key => $value) {
                    $all_saved = true;
                    if (in_array($key, $current_data)) {
                        /* perform */
                        $all_saved = $this->model->db->query("Update payment_gateway_options Set option_value = '{$value}' Where option_name = '{$key}'") ? $all_saved : false;
                    }else{
                        $all_saved = $this->model->db->query("Insert Into payment_gateway_options (payment_gateway_id, option_name, option_value) Values ('{$temp_gw[0]->id}', '{$key}', '{$value}')") ? $all_saved : false;
                    }

                }

                echo $all_saved ? 1 : "Unable to saved Paypal Checkout button option.";
            }else{
                echo "No Payment Gateway (Paypal Checkout) found";
            }
        }elseif(hasPost('action','save_subscription_paypal_button')){
            $json_data = json_decode($_POST['data']);
            $data = array(
                'pp_subscription_btn_label'   => $json_data->label,
                'pp_subscription_btn_size'    => $json_data->size,
                'pp_subscription_btn_shape'   => $json_data->shape,
                'pp_subscription_btn_color'   => $json_data->color,
                'pp_subscription_btn_tag'     => $json_data->tag,
            );

            $temp_gw    = $this->model->db->select("Select * From payment_gateway Where gateway_type = 'PAYPAL_SUBSCRIPTION'");

            if (count($temp_gw) > 0) {
                $current_data = array();
                foreach ($this->model->db->select("Select * From payment_gateway_options Where option_name Like 'pp_subscription_btn%'") as $key => $value) {
                    $current_data[] = $value->option_name;
                }

                foreach ($data as $key => $value) {
                    $all_saved = true;
                    if (in_array($key, $current_data)) {
                        /* perform */
                        $all_saved = $this->model->db->query("Update payment_gateway_options Set option_value = '{$value}' Where option_name = '{$key}'") ? $all_saved : false;
                    }else{
                        $all_saved = $this->model->db->query("Insert Into payment_gateway_options (payment_gateway_id, option_name, option_value) Values ('{$temp_gw[0]->id}', '{$key}', '{$value}')") ? $all_saved : false;
                    }

                }

                echo $all_saved ? 1 : "Unable to saved Paypal Subscription button option.";
            }else{
                echo "No Payment Gateway (Paypal Subscription) found";
            }
        }elseif(hasPost('action','save_billing_period')){
            $data = post('data');

            $name = isset($data['billing_period_title']) ? $data['billing_period_title'] : 'Untitled';
            $meta = array(
                "title"                     => isset($data['title']) ? $data['title'] : '',
                "frequency"                 => isset($data['frequency']) ? $data['frequency'] : '',
                "frequency_interval"        => isset($data['frequency_interval']) ? $data['frequency_interval'] : '',
                "cycle"                     => isset($data['cycle']) ? $data['cycle'] : '',
                "enable_trial"              => isset($data['enable_trial']) ? $data['enable_trial'] : '',
                "title_trial"               => isset($data['title_trial']) ? $data['title_trial'] : '',
                "amount_trial"              => isset($data['amount_trial']) ? $data['amount_trial'] : '',
                "frequency_trial"           => isset($data['frequency_trial']) ? $data['frequency_trial'] : '',
                "frequency_interval_trial"  => isset($data['frequency_interval_trial']) ? $data['frequency_interval_trial'] : '',
                "cycle_trial"               => isset($data['cycle_trial']) ? $data['cycle_trial'] : '',
                "default"                   => "NO",
                "enable"                    => "NO",
            );

            $new_value = array(
                'guid'  => 0,
                'type'  => 'billing-period-default-item',
                'value' => $name,
                'meta'  => json_encode($meta),
                'status'=> 'active',
            );

            if (isset($data['current_item_id']) && $data['current_item_id'] != "") {
                $new_value = array_merge(array( 'id' => $data['current_item_id'] ), $new_value);

                $current_item = $this->model->db->select("SELECT * FROM cms_items WHERE id = '{$data['current_item_id']}'");
                if (count($current_item)) {
                    $current_item = $current_item[0];
                    $current_meta = json_decode($current_item->meta);

                    $meta['default'] = isset($current_meta->default) ? $current_meta->default : 'NO';
                    $meta['enable'] = isset($current_meta->enable) ? $current_meta->enable : 'NO';

                    $this->model->db->data = $new_value;
                    $this->model->db->table = "cms_items";
                    $this->model->db->update();
                    $new_id = $data['current_item_id'];

                    echo json_encode(array('status' => true, 'data' => $new_id));
                }else{
                    echo json_encode(array('status' => false, 'data' => "Error while saving Billing Period."));
                }
            }else{
                $this->model->db->data = $new_value;
                $this->model->db->table = "cms_items";
                $new_id = $this->model->db->insertGetID();
                echo json_encode(array('status' => true, 'data' => $new_id));
            }
        }elseif(hasPost('action','save_billing_period_column')){
            $data = post('data');

            if (isset($data['id']) && isset($data['default']) && isset($data['enable'])) {
                $current_item = $this->model->db->select("SELECT * FROM cms_items WHERE id = '{$data['id']}'");
                if (count($current_item)) {
                    $current_item = $current_item[0];
                    $current_meta = json_decode($current_item->meta, true);

                    $current_meta['default'] = $data['default'];
                    $current_meta['enable'] = $data['enable'];

                    $new_data = array(
                        'id' => $data['id'],
                        'meta' => json_encode($current_meta),
                    );

                    $this->model->db->data = $new_data;
                    $this->model->db->table = "cms_items";
                    $this->model->db->update();
                    $new_id = $data['id'];

                    echo json_encode(array('status' => true, 'data' => $new_id));
                }else{
                    echo json_encode(array('status' => false, 'data' => "Item not found"));
                }
            }else{
                echo json_encode(array('status' => false, 'data' => "Invalid fields"));
            }
        }elseif(hasPost('action','delete_billing_period_column')){
            $data = post('data');

            if (isset($data['id'])) {
                $current_item = $this->model->db->select("SELECT * FROM cms_items WHERE id = '{$data['id']}'");
                if (count($current_item)) {
                    $current_item = $current_item[0];
                    $result = $this->model->db->query("DELETE FROM cms_items WHERE id = '{$current_item->id}'");
                    $new_id = $data['id'];

                    if ($result) {
                        echo json_encode(array('status' => true, 'data' => $new_id));
                    }else{
                        echo json_encode(array('status' => false, 'data' => "Unable to delete"));
                    }
                }else{
                    echo json_encode(array('status' => false, 'data' => "Item not found"));
                }
            }else{
                echo json_encode(array('status' => false, 'data' => "Invalid fields"));
            }
        }elseif(hasPost('action','get_checkout_paypal_button')){
            $current_data = array();
            foreach ($this->model->db->select("Select * From payment_gateway_options Where option_name Like 'pp_chkout_btn_%'") as $key => $value) {
                $current_data[$value->option_name] = $value->option_value;
            }

            print_r(json_encode($current_data));
        }elseif(hasPost('action','get_subscription_paypal_button')){
            $current_data = array();
            foreach ($this->model->db->select("Select * From payment_gateway_options Where option_name Like 'pp_subscription_btn%'") as $key => $value) {
                $current_data[$value->option_name] = $value->option_value;
            }

            print_r(json_encode($current_data));
        }
    }
    function gateway_processor(){
        if(isPost('action')){
            $filter = "";
            if (isset($_POST['show_deleted']) && $_POST['show_deleted'] == 'N') {
                $filter = "and status <> 'deleted'";
            }
            $subscription_detail = $this->model->db->select("SELECT * FROM `cms_items` Where type='paypal-subscription-plan' {$filter} ORDER BY `id` DESC ");

            if (post('action') == "refresh-paypal-billing-plan") {
                if (count($subscription_detail)) {
                    $subscription_detail = $subscription_detail[0];
                    $plan = $this->cms_paypal->billing_plan( $subscription_detail->value, true );

                    if (isset($plan['value']) && $plan['value'] != "") {
                        $this->model->db->data = array(
                            "id" => $subscription_detail->id,
                            "meta" => json_encode($plan['value']),
                        );
                        $this->model->db->table = "cms_items";
                        $this->model->db->update();
                    }

                    echo json_encode(array('status'=>$subscription_detail->status, 'data' => json_encode($plan['value'])));
                }else{
                    cms_header('404'); echo "Not yet added to Paypal. Save first the settings to start adding Subscription/Billing Plan to Paypal.";
                }
            }elseif (post('action') == "activate-paypal-billing-plan") {
                if (isPost('id')) {
                    $cms_item_id = post('id');
                    $cms_item    = $this->model->get_subscription_plan($cms_item_id);

                    if (count($cms_item)) {
                        $meta = json_decode($cms_item->meta, true);

                        $plan = $this->cms_paypal->billing_plan_activate($cms_item->value);

                        if (isset($plan['status']) && $plan['status']) {
                            $meta['plan'] = $plan['value']->toArray();

                            $this->model->db->data = array(
                                "id" => $cms_item->id,
                                "meta" => json_encode($meta),
                                "status" => 'active',
                            );
                            $this->model->db->table = "cms_items";
                            $this->model->db->update();
                        }

                        echo json_encode($plan);
                    }else{
                        cms_header('404'); echo "No Item Found";
                    }
                }else{
                    cms_header('404'); echo "Invalid Parameter";
                }
            }elseif (post('action') == "load-subscription-plans"){
                echo json_encode(array_map(function($n){
                    $n->meta = json_decode($n->meta);
                    return $n;
                }, $subscription_detail));
            }elseif (post('action') == "load-subscription-plan"){
                $result = array();

                if (isPost('id')) {
                    $subscription_plan_id = post('id');
                    $subscription_detail = $this->model->db->select("SELECT * FROM `cms_items` Where type='paypal-subscription-plan' and id = '{$subscription_plan_id}' ");

                    if (count($subscription_detail)) {
                        $result = $subscription_detail[0];
                        $result->meta = json_decode($result->meta);
                    }
                }

                echo json_encode($result);
            }elseif (post('action') == "load-billing-period"){
                $result = array();

                if (isPost('id')) {
                    $selected_item_id = isPost('id') ? post('id') : '';
                    $subscription_detail = $this->model->db->select("SELECT * FROM `cms_items` Where type='billing-period-default-item' and id = '{$selected_item_id}' ");

                    if (count($subscription_detail)) {
                        $result = $subscription_detail[0];
                        $result->meta = json_decode($result->meta);
                    }
                }

                echo json_encode($result);
            }elseif (post('action') == "refresh-global-subscriber-status"){
                $id = $_POST['id'];

                $order_detail = $this->model->db->select("SELECT o.*, op.payment_ref_number reference, op.id op_id FROM orders o INNER JOIN order_payments op ON o.id = op.order_id WHERE o.id='{$id}'");

                if (count($order_detail) > 0) {
                    $result = $this->cms_paypal->billing_agreement_get($order_detail[0]->reference);

                    if (isset($result['value']) && isset($result['value']['state'])) {
                        $modified = false;
                        if (strtolower($result['value']['state']) == strtolower($order_detail[0]->order_status)) {
                            $data = array(
                                'id' => $order_detail[0]->id,
                                'order_status' => strtolower($result['value']['state']),
                            );
                            $this->model->db->table = 'orders';
                            $this->model->db->data = $data;
                            $this->model->db->update($data);

                            $data2 = array(
                                'id' => $order_detail[0]->op_id,
                                'payment_status' => strtolower($result['value']['state']) =='active' ? 1 : 0,
                            );
                            $this->model->db->table = 'order_payments';
                            $this->model->db->data = $data2;
                            $this->model->db->update($data2);

                            echo json_encode(array("status"=>true, 'message' => "Subscription has been modified"));
                        }else{
                            echo json_encode(array("status"=>true, 'message' => "Subscription is updated"));
                        }
                    }else{
                        echo json_encode(array("status"=>false, 'message' => "Unable to retrieve "));
                    }
                }else{
                    echo json_encode(array("status"=>0, "message"=>"Error occured while sending request to Paypal"));
                }
            }elseif (post('action') == "delete-subscription-plan"){
                if (isPost('id')) {
                    $cms_item_id = post('id');
                    $cms_item    = $this->model->get_subscription_plan($cms_item_id);

                    if (count($cms_item)) {
                        $meta = json_decode($cms_item->meta, true);

                        $plan = $this->cms_paypal->delete_billing_plan( $cms_item->value );

                        $this->model->db->data = array(
                            "id" => $cms_item->id,
                            "status" => 'deleted',
                        );
                        $this->model->db->table = "cms_items";
                        $this->model->db->update();

                        /* Remove Subscription Plan from Products */
                        $affected_products = $this->model->db->select("Select * From product_items Where type = 'billing-period-setting' and meta like '%Global Subscription%' and meta like '%{$cms_item->id}%'");

                        $this->model->db->table = "product_items";
                        foreach ($affected_products as $key => $value) {
                            $pmeta = json_decode($value->meta);

                            if (isset($pmeta->type) && $pmeta->type == 'Global Subscription' && isset($pmeta->required_subs) && count($pmeta->required_subs) > 0 && in_array($cms_item->id, $pmeta->required_subs)) {
                                $new_pmeta = $pmeta;
                                $new_pmeta->required_subs = array_diff($new_pmeta->required_subs, array($cms_item->id));
                                $new_pmeta->required_subs = array_values($new_pmeta->required_subs);

                                $this->model->db->data = array(
                                    "id" => $value->id,
                                    "meta" => json_encode($new_pmeta),
                                );
                                $this->model->db->update();
                            }
                        }
                        /* Remove Subscription Plan from Products End */

                        if (isset($plan['status']) && !$plan['status']) {
                            echo json_encode(array("status" => true, 'message' => "Successfully Deleted Subscription Plan."));
                        }else{
                            echo json_encode(array("status" => false, 'message' => "Unable to delete Subscription Plan"));
                        }
                    }else{
                        cms_header('404'); echo "No Item Found";
                    }
                }else{
                    cms_header('404'); echo "Invalid Parameter";
                }
            }
        }else{
            cms_header("400"); echo "Invalid Request";
        }
    }
    function subscriber_table_processor(){
        // $sql = "SELECT o.id, o.first_name, o.last_name, o.invoice_number, o.order_status, pg.display_name 
        //         FROM orders o 
        //         INNER JOIN payment_gateway pg ON o.payment_method_id = pg.id 
        //         WHERE pg.gateway_type = 'PAYPAL_SUBSCRIPTION' and pg.enabled = 'Y'";

        $subscription_gateway_id = isset($_GET['subscription']) ? $_GET['subscription'] : "000";

        $sql = "SELECT o.id, o.first_name, o.last_name, o.invoice_number, o.order_status, op.payment_ref_number reference, od.product_id, p.product_name  FROM cms_items ci
            INNER JOIN order_details od ON od.item_name = ci.value
            INNER JOIN orders o ON o.id = od.order_id
            INNER JOIN order_payments op ON op.order_id = o.id
            INNER JOIN products p ON p.id = od.product_id
            WHERE ci.type = 'paypal-subscription-plan' and ci.id = '{$subscription_gateway_id}' 
            ORDER BY o.order_status ASC";

        // header_json(); print_r($sql); exit();
        $output = array(
            "sEcho" => 1,
            "iTotalRecords" => 1,
            "iTotalDisplayRecords" => 1,
            "aaData" => array()
            );

        $columns = array(
            'first_name',       /*0*/
            'invoice_number',   /*1*/
            'order_status',     /*2*/
            'id',               /*3*/
            'last_name',        /*4*/
            'reference',        /*5*/
            'product_name',     /*6*/
            );

        $output = datatable_processor($columns, "id", "", $sql);

        foreach($output['aaData'] as $kk=>$vv){
            $btnViewUser = table_button(array(
                "class"             => "btn btn-minier btn-success btn-view",
                "data-rel"          => "tooltip",
                "data-placement"    => "top",
                "title"             => "View Subscription",
                "data-value"        => $vv[3],
                "label"             => '<i class="icon-user"></i>',
            ));
            $btnCancelUser = table_button(array(
                "class"             => "btn btn-minier btn-danger btn-unsubscribe",
                "data-rel"          => "tooltip",
                "data-placement"    => "top",
                "title"             => "Cancel Subscription",
                "data-value"        => $vv[3],
                "label"             => '<i class="icon-trash"></i>',
            ));
            $btnRefreshUser = table_button(array(
                "class"             => "btn btn-minier btn-primary btn-refresh",
                "data-rel"          => "tooltip",
                "data-placement"    => "top",
                "title"             => "Refresh Subscription",
                "data-value"        => $vv[3],
                "label"             => '<i class="icon-refresh"></i>',
            ));

            $name = ucfirst($vv[0]) . " " . ucfirst($vv[4]);
            $status = ucfirst($vv[2]);
            $reference_number = $vv[5];

            $s = '<span class="label label-success arrowed arrowed-right pull-right">Active</span>';
            $p = $vv[6];

            $output['aaData'][$kk][0] = "{$name} {$s}";
            $output['aaData'][$kk][1] = $p;
            $output['aaData'][$kk][2] = $reference_number;
            $output['aaData'][$kk][3] = '<div class="btn-group">'. $btnCancelUser .'</div>';
        }

        echo json_encode($output);
    }
    function billing_period_table_processor(){
        $sql = "SELECT * FROM `cms_items` WHERE type = 'billing-period-default-item'";

        $output = array(
            "sEcho" => 1,
            "iTotalRecords" => 1,
            "iTotalDisplayRecords" => 1,
            "aaData" => array()
            );

        $columns = array(
            'id',           /*0*/
            'guid',         /*1*/
            'value',        /*2*/
            'type',         /*3*/
            'meta',         /*4*/
            'status',       /*5*/
            'date_added',   /*6*/
            );

        $output = datatable_processor($columns, "id", "", $sql);

        foreach($output['aaData'] as $kk=>$vv){
            $btnEditUser = table_button(array(
                "class"             => "btn btn-minier btn-success btn-billing-period-edit",
                "data-rel"          => "tooltip",
                "data-placement"    => "top",
                "title"             => "View Subscription",
                "data-value"        => $vv[0],
                "label"             => '<i class="icon-cog"></i>',
            ));
            $btnDeleteUser = table_button(array(
                "class"             => "btn btn-minier btn-danger btn-billing-period-delete",
                "data-rel"          => "tooltip",
                "data-placement"    => "top",
                "title"             => "View Subscription",
                "data-value"        => $vv[0],
                "label"             => '<i class="icon-trash"></i>',
            ));

            $meta    = json_decode($vv[4], true);
            
            $is_default = isset($meta['default']) && $meta['default']=="YES" ? 'checked="checked"' : '';
            $is_enabled = isset($meta['enable']) && $meta['enable']=="YES" ? 'checked="checked"' : '';
            $freq       = isset($meta['frequency_trial']) ? $meta['frequency_trial'] : '';
            $freq_intvl = isset($meta['frequency_interval']) ? $meta['frequency_interval'] : '';

            $default = '<label><input class="ace ace-checkbox-2 billing-period-default" id="billing-period-col-default-'. $vv[0] .'" value="'. $vv[0] .'" type="checkbox" '. $is_default .'><span class="lbl"></span></label>';
            $enable  = '<label><input class="ace ace-checkbox-2 billing-period-enable" id="billing-period-col-enable-'. $vv[0] .'" value="'. $vv[0] .'" type="checkbox" '. $is_enabled .'><span class="lbl"></span></label>';
            $freq_col    = "<strong>Regular</strong>: {$freq_intvl} ". strtolower($freq) . ($freq_intvl > 1 ? 's' : '');

            if (isset($meta['enable_trial']) && $meta['enable_trial'] =='YES') {
                $t_freq         = $meta['frequency_trial'];
                $t_freq_intvl   = $meta['frequency_interval_trial'];
                $freq_col .= "<br><strong>Trial</strong>: {$t_freq_intvl} " . strtolower($t_freq) . ($t_freq_intvl > 1 ? 's' : '');
            }

            $output['aaData'][$kk][0] = '<div class="text-center">'. $default .'</div>';
            $output['aaData'][$kk][1] = '<div class="text-center">'. $enable .'</div>';
            $output['aaData'][$kk][2] = $vv[2];
            $output['aaData'][$kk][3] = "{$freq_col}";
            $output['aaData'][$kk][4] = '<div class="btn-group">'. $btnEditUser . $btnDeleteUser .'</div>';
        }

        echo json_encode($output);
    }

    /* extra fucntion */
    private function validate_subscription_plan_data($data = array()){ 
        /* Simple validation */
        $validate = true;

        $validate = isset($data['paypal_subscription_amount']) ? $validate : false;
        $validate = isset($data['paypal_subscription_amount_trial']) ? $validate : false;
        $validate = isset($data['paypal_subscription_cycle']) ? $validate : false;
        $validate = isset($data['paypal_subscription_cycle_trial']) ? $validate : false;
        $validate = isset($data['paypal_subscription_frequency_interval']) ? $validate : false;
        $validate = isset($data['paypal_subscription_frequency_interval_trial']) ? $validate : false;
        $validate = isset($data['paypal_subscription_frequency']) ? $validate : false;
        $validate = isset($data['paypal_subscription_frequency_trial']) ? $validate : false;
        $validate = isset($data['paypal_subscription_plan_auto_billing']) ? $validate : false;
        $validate = isset($data['paypal_subscription_plan_initial_fail_action']) ? $validate : false;
        $validate = isset($data['paypal_subscription_plan_max_fail_attempts']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_agreement_description']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_agreement_name']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_plan_description']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_plan_name']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_plan_type']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_title']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_title_trial']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_url_cancelled']) ? $validate : false;
        $validate = !empty($data['paypal_subscription_url_confirmed']) ? $validate : false;

        return $validate;
    }
    private function register_a_plan($cms_item_id = 0){
        /*
        this function will execute an API call to create a new subscription entry using given plan detail
        plan detail, will be retrieved from cms_items using the profivded [id] "$cms_item_id"
        */
        $cms_item = $this->model->get_subscription_plan($cms_item_id);
        $meta = json_decode($cms_item->meta);

        $billing_response = $this->cms_paypal->create_billing_plan( $meta->plan );

        $transaction_id = $billing_response['value']['id'];
        $meta->plan = $billing_response['value'];

        $update_cms_item_data = array(
            'id'    => $cms_item->id,
            'value' => $transaction_id,
            'meta'  => json_encode($meta),
            'status'=> 'pending',
        );

        $this->model->db->data = $update_cms_item_data;
        $this->model->db->table = "cms_items";
        if ($this->model->db->update()) {
            return true;
        }else{
            return false;
        }
    }
    private function remove_a_plan($cms_item_id = 0){
        /*
        this function will execute an API call to create a new subscription entry using given plan detail
        plan detail, will be retrieved from cms_items using the profivded [id] "$cms_item_id"
        */
        $cms_item = $this->model->get_subscription_plan($cms_item_id);
        $meta = json_decode($cms_item->meta);

        $billing_response = $this->cms_paypal->create_billing_plan( $meta->plan );

        $transaction_id = $billing_response['value']['id'];
        $meta->plan = $billing_response['value'];

        $update_cms_item_data = array(
            'id'    => $cms_item->id,
            'value' => $transaction_id,
            'meta'  => json_encode($meta),
            'status'=> 'pending',
        );

        $this->model->db->data = $update_cms_item_data;
        $this->model->db->table = "cms_items";
        if ($this->model->db->update()) {
            return true;
        }else{
            return false;
        }
    }
    private function update_a_plan($cms_item_id = 0){
        /*
        this function will execute an API call to create a new subscription entry using given plan detail
        plan detail, will be retrieved from cms_items using the profivded [id] "$cms_item_id"
        */
        $cms_item = $this->model->get_subscription_plan($cms_item_id);
        $meta = json_decode($cms_item->meta, true);

        $billing_response = $this->cms_paypal->update_billing_plan( $meta['plan'] );

        if (isset($billing_response['value']->id)) {
            $transaction_id = $billing_response['value']->id;
            $meta['plan']   = $billing_response['value'];

            $update_cms_item_data = array(
                'id'    => $cms_item->id,
                'value' => $transaction_id,
                'meta'  => json_encode($meta),
                'status'=> 'pending',
            );

            $this->model->db->data = $update_cms_item_data;
            $this->model->db->table = "cms_items";
            if ($this->model->db->update()) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    private function get_modified_data($old=array(), $new=array()){
        /* Set Data Start */

        /* Setting Payment Definitions */
        $payment_definition = array();

        $temp1  = $new['payment_definitions'][0];
        $temp2  = $old['payment_definitions'][0];
        $payment_definitions[0] = array(
            "id"        => isset($temp2['id']) ? $temp2['id'] : '', 
            "name"      => isset($temp1['name']) ? $temp1['name'] : $temp2['name'],
            "type"      => isset($temp1['type']) ? $temp1['type'] : $temp2['type'],
            "cycles"    => isset($temp1['cycles']) ? $temp1['cycles'] : $temp2['cycles'],
            "frequency" => isset($temp1['frequency']) ? $temp1['frequency'] : $temp2['frequency'],
            "frequency_interval" => isset($temp1['frequency_interval']) ? $temp1['frequency_interval'] : $temp2['frequency_interval'],
            "amount" => array(
                "currency" => isset($temp1['currency']) ? $temp1['currency'] : $temp2['amount']['currency'],
                "value" => isset($temp1['value']) ? $temp1['value'] : $temp2['amount']['value'],
            ),
        );

        $temp1  = $new['payment_definitions'][1];
        $temp2  = $old['payment_definitions'][1];
        $payment_definitions[1] = array(
            "id"        => isset($temp2['id']) ? $temp2['id'] : '', 
            "name"      => isset($temp1['name']) ? $temp1['name'] : $temp2['name'],
            "type"      => isset($temp1['type']) ? $temp1['type'] : $temp2['type'],
            "cycles"    => isset($temp1['cycles']) ? $temp1['cycles'] : $temp2['cycles'],
            "frequency" => isset($temp1['frequency']) ? $temp1['frequency'] : $temp2['frequency'],
            "frequency_interval" => isset($temp1['frequency_interval']) ? $temp1['frequency_interval'] : $temp2['frequency_interval'],
            "amount" => array(
                "currency" => isset($temp1['currency']) ? $temp1['currency'] : $temp2['amount']['currency'],
                "value" => isset($temp1['value']) ? $temp1['value'] : $temp2['amount']['value'],
            ),
        );

        /* Setting Plan Data */
        $plan = $old;
        $plan['name'] = $new['name'];
        $plan['description'] = $new['description'];
        $plan['type'] = $new['type'];
        $plan['payment_definitions'] = $payment_definitions;
        $plan['merchant_preferences']['max_fail_attempts'] = $new['merchant_preferences']['max_fail_attempts'];
        $plan['merchant_preferences']['return_url'] = $new['merchant_preferences']['return_url'];
        $plan['merchant_preferences']['cancel_url'] = $new['merchant_preferences']['cancel_url'];
        $plan['merchant_preferences']['auto_bill_amount'] = $new['merchant_preferences']['auto_bill_amount'];
        $plan['merchant_preferences']['initial_fail_amount_action'] = $new['merchant_preferences']['initial_fail_amount_action'];

        return $plan;
    }
}
