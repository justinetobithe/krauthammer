<?php

class CF extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        echo 'hello post!';
    }

    function submit() {
        $return_data = array(
            'status' => 'error',
            'message' => '<strong>Error:</strong> Message not sent!'
        );
        $get_data = $_POST;
        if($this->verify_human($get_data)) {

            $mail = new PHPMailer;

            $cf = selectToAssoc("SELECT * FROM `cms_contact_forms` WHERE `id` = {$get_data['_cmscf_id']}")[0]; // get contact form

            $cf['mail_1_message_body'] = $this->form_replace_short_code_with_data($cf['mail_1_message_body'], $get_data);

            $subject = $cf['mail_1_subject'];

            if(isset($get_data['subject'])) {
                $subject = $get_data['subject'];
            }

            $mail_from = array(
                'name' => '',
                'email' => ''
            );
            preg_match_all("/\[(\w.*?)\]/", $cf['mail_1_from'], $output);
            if(count($output)) {
                $mail_from['name'] = $get_data[$output[1][0]]; // name
                $mail_from['email'] = $get_data[$output[1][1]]; // email
            }

            // SAVE COLLECTED CF DATA TO DATABASE
            $cf_collected_data = array();
            $not_cf_data = array('_cmscf_id', 'g-recaptcha-response');
            foreach ($get_data as $key => $datum) {
                if(!empty($datum) && !in_array($key, $not_cf_data)) {
                    $cf_collected_data[ucwords(str_replace("_", " ", $key))] = $datum;
                }
            }

            // save attachments
            $cf_attachments = array();
            if(!empty($files = $_FILES)) {
                $path = ROOT . "files/cf-files/".$get_data['_cmscf_id']."/";

                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }

                foreach ($files as $key => $file) {
                    $file_type_allowed = array('jpg', 'jpeg', 'png', 'tiff', 'bmp', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'oxps');
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    if(in_array(strtolower($ext), $file_type_allowed)) {
                        move_uploaded_file($file['tmp_name'], $path . $file['name']);

                        $file_url = URL . "files/cf-files/".$get_data['_cmscf_id']."/"  . $file['name'];

                        if (strpos($file['type'], 'image') !== false) {
                            $file_url = "<img src='{$file_url}' alt>";
                        }

                        $cf_collected_data[ucwords(str_replace("_", " ", $key))] = $file_url;

                        $mail->AddAttachment( $path . $file['name'] , $file['name'] );
                    }
                }
                // foreach ($files as..
            }
            // if(!empty($files = $_FILES))

            // save to database
            $this->model->db->table = 'contact_form_7_forms_collected_data';
            $this->model->save(
                array(
                    'form_id' => $get_data['_cmscf_id'],
                    'form_data' => json_encode($cf_collected_data)
                )
            );
            // foreach ($get_data as...

            // SEND EMAIL TO ENQUIRY EMAIL ADDRESS 
            //Create a new PHPMailer instance

            //Set who the message is to be sent from
            $mail->setFrom($mail_from['email'], $mail_from['name']);
            //Set an alternative reply-to address
            // $mail->addReplyTo('replyto@example.com', 'First Last');
            //Set who the message is to be sent to
            $mail->addAddress($cf['mail_1_to'], '');
            // $mail->addCC(system_option('enquiry_form_email'), system_option('company_name'));
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($cf['mail_1_message_body']);
            //Replace the plain text body with one created manually
            $mail->AltBody = '';
            //Attach an image file
            // $mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $return_data['status'] = 'error';
                $return_data['message'] = '<strong> Mailer Error:</strong> ' . $mail->ErrorInfo;;
            } else {
                $return_data['status'] = 'success';
                $return_data['message'] = '<strong> Success:</strong> Message sent!';
                // echo "Message sent!";
            }

        } else {
            $return_data['message'] = '<strong>Error:</strong> Not this time, robot!';
        }

        header_json();
        echo json_encode($return_data);
    }

    function verify_human($post) {
         // check if human
        if($post['from-singapore'] != "") {
            return false;
        }
        if($post['job-country'] != "") {
            return false;
        }
        if($post['office-address'] != "") {
            return false;
        }
        if($post['travelling-experience'] != "") {
            return false;
        }
        if($post['card-number-exclusive'] != "") {
            return false;
        }
        if($post['promotional-code-ex-full-discount'] != "") {
            return false;
        }
        if($post['overseas-telephone-number'] != "") {
            return false;
        }
        if($post['alternate-email-3'] != "") {
            return false;
        }
        if($post['pw-agent-id-1225'] != "") {
            return false;
        }
        if($post['job-experience'] != "") {
            return false;
        }
        if($post['job-title'] != "") {
            return false;
        }

        // check robot using google recaptcha
        if(isset($_POST['g-recaptcha-response'])) {
            if(!$this->check_grecaptcha()) {
                return false;
            }
        }

        // --
        return true;
    }

    function news_submit() {
        $return_data = array(
            'status' => 'error',
            'message' => '<strong>Error:</strong> Message not sent!'
        );

        $get_data = $_POST;
        $email = $get_data['nemail'];

        $news_emails = selectToAssoc("SELECT * FROM cms_items WHERE value = '$email'");


        if (count($news_emails) > 0) {
            $return_data['status'] = 'duplicate';
            $return_data['message'] = '<strong> duplicate</strong>';
        }
        else {
            $cf = custom_query("INSERT INTO cms_items (guid, type, value) 
                                        VALUES ('0', 'newsletter-email', '$email')"); 

            if ($cf === TRUE) {
                $return_data['status'] = 'success';
                $return_data['message'] = '<strong> Success</strong>';
            } else {
                $return_data['status'] = 'error';
                $return_data['message'] = '<strong> Error</strong>';
                
            }
        }       
        

        header_json();
        echo json_encode($return_data);
    }

    function form_replace_short_code_with_data($string, $data){
        foreach ($data as $key => $datum){
            $string = str_replace('['.$key.']', $datum, $string);
        }
        return $string;
      }

    private function check_grecaptcha() {
        // check robot using google recaptcha
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . get_system_option('GOOGLE_RECAPTCHA_SECRET') . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
        $obj = json_decode($response);

        return $obj->success;
    }
}