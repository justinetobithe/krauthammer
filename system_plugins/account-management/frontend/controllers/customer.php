<?php

class Customer extends Controller {

	function __construct() {
		parent::__construct();
		$this->view->alt_location = ROOT . "system_plugins/account-management/frontend/views/";
	}

	function index($url = "") {
		$this->view->render('error');
	}

	function login() {
		if(isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != 0) {
			redirect( get_bloginfo("baseurl") );
			// $this->view->render('error');
		} else {
			$this->view->render('page-login');
			// $this->view->render('customer-login');
		}
	}
	function forgot_password($url = "") {
		if (cms_is_login()) {
			redirect( get_bloginfo("baseurl") );
		}else{
			$this->view->render('page-lost-password');
		}
	}
	function logout() {
		unset($_SESSION['customer_id']);
		unset($_SESSION['customer']);

		if(isset($_COOKIE['customer_id'])) {
			setcookie('customer_id', '', time()-1, '/');
		}
		redirect( get_bloginfo("baseurl") . "customer/login" );
	}
	function reset_password() {
		if(isGet('hash') && get('hash') != '') {
			$customer = $this->get_customer(get('hash'), 'password_reset_hash');
			if(!$customer) {
				$this->view->render('error');
			} else {
				$this->view->set('reset_hash', $customer->password_reset_hash);
				$this->view->render('page-reset-password');
			}
		} else {
			$this->view->render('error');
		}
	}
	function submit_login() {
		$return_data = array(
			'success' => false,
			'msg' => 'Account does not exist!'
			);
		// check account info
		if(isPost('login_email')) {
			$customer = $this->get_customer(post('login_email'));

			if(!$customer) {
				$return_data['success'] = false;
				$return_data['msg'] = 'Account does not exist!';
				redirect( get_bloginfo("baseurl") . "customer/login/?error=login" );
			} else {
				if (!isPost('login_password') || post('login_password') == "") {
					$return_data['success'] = false;
					$return_data['msg'] = 'Invalid Password';
				}elseif($this->check_password(post('login_password'), $customer->password, $customer->salt)) {

					$_SESSION['customer'] = array(
						'email' => $customer->email,
						'first_name' => $customer->firstname,
						'last_name' => $customer->lastname,
						'phone' => $customer->contact_number,
						'billing_country' => $customer->billing_country,
						'billing_address' => $customer->billing_address,
						'billing_address_line_2' => $customer->billing_address_2,
						'billing_city' => $customer->billing_city,
						'billing_postal' => $customer->billing_postal_code
						);

					$_SESSION['customer_id'] = $customer->id;

					if(isPost('login_remember') && post('login_remember') == 'on') {
						setcookie('customer_id', $_SESSION['customer_id'], strtotime("+1 year"), '/');
					}

					$return_data['success'] = true;
					$return_data['msg'] = 'Logged in successfully!';

					/* Make Paypal API call to get Subscription Info */
					$this->validate_subscriptions();
				}
				// if (get_last_url() != "") {
				// 	return array('redirect'=>get_last_url());
				// 	// redirect( get_last_url() );
				// }else{
				// 	return array('redirect'=>get_bloginfo("baseurl"));
				// 	// redirect( get_bloginfo("baseurl") );
				// }
			}
		}else{
			return get_bloginfo("baseurl") . "customer/login/?error=login" ;
			// redirect( get_bloginfo("baseurl") . "customer/login/?error=login" );
		}
		// echo json_encode($return_data);
		header_json();
		echo json_encode($return_data);
	}
	function submit_registration() {
		if(isPost('register_submit')) {
	        // check if human
			if($_POST['from-singapore'] != "") {
				return false;
			}
			if($_POST['job-country'] != "") {
				return false;
			}
			if($_POST['office-address'] != "") {
				return false;
			}
			if($_POST['travelling-experience'] != "") {
				return false;
			}
			if($_POST['card-number-exclusive'] != "") {
				return false;
			}
			if($_POST['promotional-code-ex-full-discount'] != "") {
				return false;
			}
			if($_POST['overseas-telephone-number'] != "") {
				return false;
			}
			if($_POST['alternate-email-3'] != "") {
				return false;
			}
			if($_POST['pw-agent-id-1225'] != "") {
				return false;
			}
			if($_POST['job-experience'] != "") {
				return false;
			}
			if($_POST['job-title'] != "") {
				return false;
			}
			if($_POST['register_password'] == "") {
				return false;
			}
	        // --

			$customer = array(
				'email' => $_POST['register_email'],
				'password' => $_POST['register_password'],
				'firstname' => $_POST['register_firstname'],
				'lastname' => $_POST['register_lastname'],
				'contact_number' => $_POST['register_phone'],
				'company_name' => $_POST['register_company_name'],
				'billing_country' => $_POST['register_country'],
				'billing_address' => $_POST['register_address1'],
				'billing_address_2' => $_POST['register_address2'],
				'billing_city' => $_POST['register_city'],
				'billing_state' => $_POST['register_state'],
				'billing_email' => $_POST['register_email'],
				'billing_postal_code' => $_POST['register_postal'],
				'billing_phone' => $_POST['register_phone'],
				);

			// format password
			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
			$customer['salt'] = md5(time().$randomString);
			$customer['password'] = sha1($customer['salt'].$customer['password'].$customer['salt']);

			$this->model->db->table = 'customers';
			$this->model->db->data = $customer;
			$_SESSION['customer_id'] = $this->model->db->insertGetID();

			// echo $_SESSION['customer_id'];
			redirect( get_bloginfo("baseurl") );
		}
	}
	function submit_forgot_password() {
		$return_data = array(
			'success' => false,
			'msg' => 'Email address does not exist!'
			);

		if(isPost('login_email')) {
			$customer = $this->get_customer(post('login_email'));
			
			if($customer) {
				$password_reset_hash = md5('password_reset'.time().rand(1,20000));

				$this->model->db->query("UPDATE `customers` SET `password_reset_hash` = '{$password_reset_hash}' WHERE `id` = {$customer->id}");

				require ROOT . 'plugins/phpmailer/PHPMailerAutoload.php';
				$mail = new PHPMailer;
		        //Set who the message is to be sent from
				$mail->setFrom(get_system_option('company_email'), get_system_option('company_name'));
		        //Set an alternative reply-to address
		        // $mail->addReplyTo('replyto@example.com', 'First Last');
		        //Set who the message is to be sent to
				$mail->addAddress($customer->email, $customer->firstname . ' ' . $customer->lastname);
		        //Set the subject line
				$mail->Subject = strtoupper(get_system_option('company_name')) . ' Password reset request.';
		        //Read an HTML message body from an external file, convert referenced images to embedded,
		        //convert HTML into a basic plain-text alternative body
				$mail->msgHTML($this->email_content($password_reset_hash));
		        //Replace the plain text body with one created manually
				$mail->AltBody = '';
		        //Attach an image file
		        // $mail->addAttachment('images/phpmailer_mini.png');

		        //send the message, check for errors
				if (!$mail->send()) {
					// echo "Not Sent";
		      // echo "Mailer Error: " . $mail->ErrorInfo;
		      $this->view->render('page-reset-password-message');
				} else {
		      // echo "Message sent!";
					$this->view->set('message', 'We send a reset link in your email. <a href="'. get_bloginfo("baseurl") . "customer/forgot_password/" .'">Click Here to Try Again.</a>');
		      $this->view->render('page-reset-password-message');
				}
				
				$return_data['success'] = true;
				$return_data['msg'] = 'We have sucessfully sent a password reset link to your email address.';
			}

		}
		// echo json_encode($return_data);
	}
	function submit_reset_password() {
		if(isPost('reset_password') && isPost('reset_password_confirm') && post('reset_password') == post('reset_password_confirm') ) {
			$customer = $this->get_customer(post('reset_code'), 'password_reset_hash');

			if ($customer != false) {
				$new_password = sha1($customer->salt . post('reset_password') . $customer->salt);
				$result = $this->model->db->query("UPDATE `customers` SET `password` = '{$new_password}', `password_reset_hash` = '' WHERE `password_reset_hash` = '{$customer->password_reset_hash}'");

				if ($result) {
					$this->view->set('message', 'Successfully changed password... <a href="'. get_bloginfo("baseurl") . "customer/login/" .'">Login Here.</a>');
			  	$this->view->render('page-reset-password-message');
				}else{
					$this->view->set('message', 'Invalid Password...');
			  	$this->view->render('page-reset-password-message');
				}
			}else{
				redirect(get_bloginfo("baseurl") . "customer/login/");
			}
		}else{
		  $this->view->set('message', "Invalid Password...");
		  $this->view->render('page-reset-password-message');
		}
	}
	function check_email() {
		if(isPost('email')) {
			$email = $_POST['email'];
			$check_email = $this->model->db->select("SELECT `email` FROM `customers` WHERE `email` = '{$email}'");
			echo count($check_email);
		}
	}
	function validate_subscriptions(){
		$customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : '';

		require_once ROOT . "admin/libraries/plugins/cms-api/cms-paypal.php";
		$paypal = new CMS_Paypal();

		$subscription_info = array();

		$sql = "SELECT o.id order_id, op.id payment_id, od.item_name plan, op.payment_ref_number ref FROM orders o
						left join order_details od on od.order_id = o.id
						left join order_payments op on op.order_id = o.id
						where o.cid = '{$customer_id}' and o.order_status = 'active' and od.product_id = 0 and od.item_name <> ''";
		$subscriptions = $this->model->db->select($sql);

		foreach ($subscriptions as $key => $value) {
			$res = $paypal->billing_agreement_get($value->ref);

			if ($res['status']) {
				$plan = $res['value'];

				$subscription_info[$value->plan] = $plan['state'];
			}
		}

		$_SESSION['customer']['subscription_info'] = $subscription_info;
	}

	private function email_content($password_reset_hash){
		$password_reset_link = URL . 'customer/reset_password/?hash=' . $password_reset_hash;
		$html = "
		<html>
		<body style='font-family:Arial,sans-serif;'>
			<h3 style='margin:0;'>".get_system_option('company_name')."</h3> 
			<div>".get_system_option('company_address')."</div>
			<div>Tel: ".get_system_option('company_contact_number')." / Fax: ".get_system_option('company_fax_number')."</div>
			<div>".get_system_option('company_email')." | ".URL."</div>
			<div>
				<h4>Password Reset Request</h4>
				<p>We received a request to reset the password associated with this email address. If you made this request, please click the link below to complete your request.</p>
				<p><a href='{$password_reset_link}'>{$password_reset_link}</a></p>
				<p>If you didn't make this password reset request, please ignore this email and no changes will be made to your account.</p>
			</div>
		</body>
		</html>
		";
		return $html;
	}
	private function get_customer($val, $col='email') {
		$customer_db = $this->model->db->select("SELECT * FROM `customers` WHERE `{$col}` = '{$val}'");

		if(count($customer_db) > 0) {
			return $customer_db[0];
		} else {
			return false;
		}
	}
	private function check_password($password1, $password2, $salt) {
		return (sha1($salt . $password1 . $salt) == $password2);
	}

}