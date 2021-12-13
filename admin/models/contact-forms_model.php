<?php

class ContactForms_Model extends Model{

	const OPTIONS = 'cms_options';

	public function __construct(){
		parent::__construct();
		$this->db->table= 'cms_contact_forms';
		$this->db->data=array();
	}

	public function getOption($name){
		$qry=$this->db->query("SELECT option_value FROM ".self::OPTIONS." WHERE option_name='{$name}'");
		$row= $this->db->fetch($qry);
		return $row[0];
	}

	public function getContactForm($id){
		$qry=$this->db->query("SELECT * FROM ".$this->db->table." WHERE ID='{$id}'");
		$row = $this->db->fetch($qry,'array');
		return $row;
	}	

	public function getContactFormByShortcode($shortcode){
		$qry=$this->db->query("SELECT * FROM ".$this->db->table." WHERE shortcode='{$shortcode}'");
		$row = $this->db->fetch($qry,'array');
		return $row;
	}	

	public function getContactForms(){
		$rows=array();
		$qry=$this->db->query("SELECT id, name, form_code FROM ".$this->db->table." ORDER BY name");
		while($row=$this->db->fetch($qry,'array')){
			$rows[]= $row;
		}
		return $rows;
	}

	public function create($name,$form_code,$mail1,$mail2,$message,$add_set){

		$mail1_use = 'N';
		$mail2_use = 'N';
		$mail2_enabled = 'N';

		if($mail1['mail_html_content_type'] == '1')
			$mail1_use = 'Y';
		if($mail2['mail2_html_content_type'] == '1')
			$mail2_use = 'Y';
		if($mail2['mail2_active'] == '1')
			$mail2_enabled = 'Y';

		$qry = $this->db->query("INSERT INTO `cms_contact_forms`(`name`,`form_code`,`mail_1_to`,`mail_1_from`,`mail_1_subject`,`mail_1_additional_headers`,`mail_1_message_body`,`mail_1_use_html_content_type`,`mail_2_to`,`mail_2_from`,`mail_2_subject`,`mail_2_additional_headers`,`mail_2_message_body`,`mail_2_use_html_content_type`,`mail_2_enabled`)
			VALUES('$name','$form_code','".$mail1['mail_to']."','".$mail1['mail_from']."','".$mail1['mail_subject']."','".$mail1['mail_additional_header']."','".$mail1['mail_message_body']."','$mail1_use','".$mail2['mail2_to']."','".$mail2['mail2_from']."','".$mail2['mail2_subject']."','".$mail2['mail2_additional_header']."','".$mail2['mail2_message_body']."','$mail2_use','$mail2_enabled')");

		if($qry)
		{
			$qry_new_added = $this->db->query("SELECT * FROM `cms_contact_forms` ORDER BY `id` DESC LIMIT 1");
			$row = $this->db->fetch($qry_new_added,'array');

			return $row['id'];
		}

		return 0;
	}

	public function update($id,$name,$form_code,$mail1,$mail2,$message,$add_set,$option){
		$mail1_use = 'N';
		$mail2_use = 'N';
		$mail2_enabled = 'N';

		if($mail1['mail_html_content_type'] == '1')
			$mail1_use = 'Y';
		if($mail2['mail2_html_content_type'] == '1')
			$mail2_use = 'Y';
		if($mail2['mail2_active'] == '1')
			$mail2_enabled = 'Y';

		$data = array(
			"id"=>$id,
			"name"=>$name,
			"form_code"=>$form_code,
			"mail_1_to"=>$mail1['mail_to'],
			"mail_1_from"=>$mail1['mail_from'],
			"mail_1_subject"=>$mail1['mail_subject'],
			"mail_1_additional_headers"=>$mail1['mail_additional_header'],
			"mail_1_message_body"=>$mail1['mail_message_body'],
			"mail_1_use_html_content_type"=>$mail1_use,
			"mail_2_to"=>$mail2['mail2_to'],
			"mail_2_from"=>$mail2['mail2_from'],
			"mail_2_subject"=>$mail2['mail2_subject'],
			"mail_2_additional_headers"=>$mail2['mail2_additional_header'],
			"mail_2_message_body"=>$mail2['mail2_message_body'],
			"mail_2_use_html_content_type"=>$mail2_use,
			"mail_2_enabled"=>$mail2_enabled,
			"enable_captcha"=> $option['enable_captcha'],
			"message_notifiactions"=>json_encode($message)
			);

		$this->db->data = $data;
		$this->db->table = 'cms_contact_forms';
		return $this->db->update();
	}

	public function hasContactFormId($id){
		$qry=$this->db->query("SELECT ID FROM ".$this->db->table." WHERE id='{$id}'");
		$count = $this->db->numRows($qry);
		if($count){
			return true; 
		}
		return false;
	}

	public function delete($id){
		$this->db->id=$id;
		return $this->db->delete();
	}

	private function getAuthor($id){
		$qry=$this->db->query("SELECT user_login FROM cms_users WHERE ID='{$id}'");
		$row=$this->db->fetch($qry);
		return $row[0];
	}

	public function hasShortCode($shortcode){
		$qry=$this->db->query("SELECT shortcode FROM ".$this->db->table." WHERE shortcode='{$shortcode}'");
		$count = $this->db->numRows($qry);
		if($count){
			return true; 
		}
		return false;
	}

	public function getMail($id){
		$qry=$this->db->query("SELECT mail FROM ".$this->db->table." WHERE id='{$id}'");
		$row = $this->db->fetch($qry);
		return $row[0];
	}   


	public function getMessage($id){
		$qry=$this->db->query("SELECT messages FROM ".$this->db->table." WHERE id='{$id}'");
		$row = $this->db->fetch($qry);
		return $row[0];
	}  

	public function getMessageByShortcode($shortcode){
		$qry=$this->db->query("SELECT messages FROM ".$this->db->table." WHERE shortcode='{$shortcode}'");
		$row = $this->db->fetch($qry);
		return $row[0];
	}

	function get_contact_form_data_by_form_id($id){
		$qry = $this->db->query("SELECT * FROM `contact_form_7_forms_collected_data` WHERE `form_id` = '$id'");
		$row =  $this->db->fetch($qry, 'array');
		return json_decode($row['form_data']);
	} 

	public function get_contact_form_collections($contact_form_id = 0){
		$output = array(
			"headers" => array(),
			"responses" => array(),
			);

		if ($contact_form_id != null) {
			$collection = $this->db->select("SELECT * FROM `contact_form_7_forms_collected_data` Where `form_id` = '{$contact_form_id}' or ('0' = '{$contact_form_id}' and `type` = 'promotional') ORDER BY `id`");

			$responses = array();
			$table_headers = array();

			foreach ($collection as $key => $value) {
				$temp = array();
				$temp['Date/Time'] = $value->date_added;

				$row = json_decode($value->form_data);
				foreach ($row as $key2 => $value2) {
					if (!in_array($key2, $table_headers)) {
						$table_headers[] = $key2;
					}
					$temp[$key2] = $value2;
				}
				// if (count($table_headers) < count(array_keys($temp))) {
				// 	$table_headers = array_keys($temp);
				// }
				$responses[$value->id] = $temp;
				// $responses[] = array(
				// 	'id' => $value->id,
				// 	'item' => $temp,
				// 	'date' => $value->date_added,
				// );
			}

			array_unshift($table_headers, 'Date/Time');
			$output['headers'] = $table_headers;
			$output['responses'] = $responses;
		}
		
		return $output;
	}
}