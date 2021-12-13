<?php

class ContactForms extends Controller{

	function __construct(){
		parent::__construct();
		Session::handleLogin();
	}

	function index(){
		$this->view->render('header');
		$this->view->render('contact-forms/index');
		$this->view->render('footer');
	}

	function responses($contact_form_id = ""){
		// if($contact_form_id >= 0 && $contact_form_id != ''){
			$contact_forms = $this->model->db->select("SELECT * FROM `cms_contact_forms`");

			$this->view->set('contact_form_id',$contact_form_id);
			$this->view->set('contact_forms',$contact_forms);
			$this->view->set('js_files',array("contact-form-responses"));
			$this->view->render('header');
			$this->view->render('contact-forms/responses');
			$this->view->render('footer');
		// }
	}

	function add(){
		set_module_sub_title("Add");
		$this->view->render('header');
		$this->view->render('contact-forms/add');
		$this->view->render('footer');
	}

	function edit($url = ""){
		set_module_sub_title("Edit");
		if($url != "" && $url > 0 ){
			$contact = $this->model->getContactForm($url);

			if(empty($contact)){
				header('location:'.URL.'contact-forms/');
			}

			$this->view->set('contact',$contact);
			$this->view->render('header');
			$this->view->render('contact-forms/edit');
			$this->view->render('footer');
		}else{
			header('location:'.URL.'contact-forms/');
		}
	}

	function create(){
		if(isPost('action') && post('action')=='create'){
			$name = $_POST['title'];
			$form_code = $_POST['form'];
			$mail1 = $_POST['mail'];
			$mail2 = $_POST['mail2'];
			$message = $_POST['messages'];
			$add_set = $_POST['additional_settings'];

			echo json_encode($this->model->create($name,$form_code,$mail1,$mail2,$message,$add_set));
		}
	}

	// function getContactForms(){
	// 	echo json_encode($this->model->getContactForms());
	// }

	function getContactForms(){
		/* New Datatable */
		$output = array(
			"sEcho" => 1,
			"iTotalRecords" => 1,
			"iTotalDisplayRecords" => 1,
			"aaData" => array()
			);

		$sql = "SELECT * FROM ".$this->model->db->table." ORDER BY id Desc";

		$columns = array(
			'id', 				/*0*/
			'name', 			/*1*/
			'form_code', 	/*2*/
			'mail_1_to', 	/*3*/
			'mail_1_from', 	/*4*/
			'mail_1_subject', 	/*5*/
			);

		$output = datatable_processor($columns, "id", "", $sql);

		foreach($output['aaData'] as $kk=>$vv){
			$btns = "";
			$btns .= "<a href='".URL."contact-forms/edit/".$vv[0]."'>Edit</a> <br>";
			$btns .= "<a href='".URL."contact-forms/responses/".$vv[0]."'>Response</a> <br>";
			$btns .= "<a href='javascript:copyContactForm(".$vv[0].")'>Copy</a>";

			$output['aaData'][$kk][0] = "<div class='text-center'><label><input type='checkbox' value='{$vv[0]}' class='ace' /><span class='lbl'></span></label></div>";
			$output['aaData'][$kk][1] = "<a href='".URL."contact-forms/edit/".$vv[0]."'>".$vv[1]."</a>";
			$output['aaData'][$kk][2] = "<input type='text' value='[contact-form id=".'"'.$vv[0].'"'." title=".'"'.$vv[1].'"'."]' readonly class='span12' />";
			$output['aaData'][$kk][3] = "";
			$output['aaData'][$kk][4] = "";
			$output['aaData'][$kk][5] = $btns;
		}

		echo json_encode($output);
	}

	function update(){
		if(isPost('action') && post('action')=='update'){
			$id 				= post('id');
			$name 			= $_POST['title'];
			$form_code 	= $_POST['form'];
			$mail1 			= $_POST['mail'];
			$mail2 			= $_POST['mail2'];
			$message 		= $_POST['messages'];
			$add_set 		= $_POST['additional_settings'];
			$option 		= $_POST['toggle_option'];

			if($this->model->update($id,$name,$form_code,$mail1,$mail2,$message,$add_set,$option)){
				echo json_encode('1');
			}else{
				echo json_encode('0');
			}
		}
	}
	function deleteContactForms(){
		if (isPost('action')){
			if (post('action')=='delete') {
				$ids = isPost('ids') ? post('ids') : array();
				
				foreach ($ids as $key => $value) {
					$sql_delete_cf = "Delete From `cms_contact_forms` Where `id` = '{$value}'";
					$this->model->db->query($sql_delete_cf);
				}

				echo "1";
			}else{
				echo "Invalid Request";
			}
		}else{
			echo "Invalid Request";
		}
	}

	function load_data(){
		if(post('action') == 'get'){
			echo json_encode($this->model->get_contact_form_data_by_form_id(post('id')));
		}
	}

	function response_processor(){
		header_json(); 
		if (isPost('action')) {
			if (post('action') == 'get-collection'){
				$cf_id = isPost('id') ? post('id') : 0;

				$collection_data = $this->model->get_contact_form_collections($cf_id);
				echo json_encode($collection_data); 
			}elseif(post('action') == 'get-delete'){
				$selected_id = post('id');

				$sql_delete = "Delete From `contact_form_7_forms_collected_data` Where `id` = '{$selected_id}'";
				if ($this->model->db->query($sql_delete)) {
					echo "1";
				}else{
					echo "Problem occur while saving...";
				}
			}elseif(post('action') == 'get-item'){
				$selected_id = post('id');

				$sql = "Select * From `contact_form_7_forms_collected_data` Where `id` = '{$selected_id}'";
				$item = $this->model->db->select($sql);

				$output = array();

				if (count($item)) {
					$item = $item[0];
					$temp = json_decode($item->form_data);

					$output['id'] = $item->id;

					foreach ($temp as $key => $value) {
						$output['values'][$key]= $value;
					}
				}
				print_r(json_encode($output));
			}elseif(post('action') == 'save-item'){
				$id = post('id');
				$data = json_decode(post('data'));
				/* run validation here */
				/* run validation here */
				$this->model->db->table="contact_form_7_forms_collected_data";
				$this->model->db->data = array('id'=>$id,'form_data'=>json_encode($data));
				
				if ($this->model->db->update()) {
					print_r("1");
				}else{
					print_r("Unable to save selected item.");
				}
			}
		}
	}
}