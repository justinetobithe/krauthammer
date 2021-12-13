<?php

class Testimonials extends Controller{
	function __construct(){
		parent::__construct();
		Session::handleLogin();
	}

	/*PAGES*/
	function index(){
		/*header_json(); print_r(json_encode(array("author"=>"John", "company"=>"PVS", "position" => "Worker", "profile_picture" => "test-picture"))); exit();*/
		$this->view->render('header');
		$this->view->render('testimonials/index');
		$this->view->render('footer');
	}
	function add(){
		$this->view->render('header');
		$this->view->render('testimonials/add');
		$this->view->render('footer');
	}
	function edit(){
		$this->view->render('header');
		$this->view->render('testimonials/edit');
		$this->view->render('footer');
	}

	/*PROCESS*/
	function table_processor(){
		$columns = array(
			array( 
				'db' => 'meta', 
				'dt' => 0,
				'formatter' => function($d, $row){
					$jsondata = json_decode($row[1]);
					$img_default = trim(FRONTEND_URL, '/') . "/thumbnails/78x66/uploads/default.png";
					$img = isset($jsondata->profile_picture) && $jsondata->profile_picture !='' ? $jsondata->profile_picture : $img_default;
					return '<div class="testimonial-img text-center"><img src="'. $img .'" alt=""></div>';
				},
				),
			array( 
				'db' => 'meta', 
				'dt' => 1,
				'formatter' => function($d, $row){
					$jsondata = json_decode($row[1]);
					return isset($jsondata->author) ? $jsondata->author : "";
				},
				),
			array( 'db' => 'value', 'dt' => 2 ),
			array( 'db' => 'status', 'dt' => 3, 'formatter' => function($d, $row){
				if ($d == 'approved') {
					return '<span class="label label-success arrowed-in-right">Approved</span>';
				}elseif($d == 'pending'){
					return '<span class="label label-info arrowed-in-right">Pending</span>';
				}else{
					return '<span class="label label-important arrowed-in-right">Deleted</span>';
				}
			}),
			array( 
				'db' => 'id', 
				'dt' => 4 , 
				'formatter' => function($d, $row){
					$btnEditUser = table_button(array(
						"class" => "btn btn-minier btn-info btn-testimonial-edit",
						"data-rel" => "tooltip",
						"data-placement" => "top",
						"title" => "Edit",
						"data-value" => $d,
						"label" => '<i class="icon-edit"></i>',
						));
					$btnDeleteUser = table_button(array(
						"class" => "btn btn-minier btn-danger btn-testimonial-delete",
						"data-rel" => "tooltip",
						"data-placement" => "top",
						"title" => "Delete",
						"data-value" => $d,
						"label" => '<i class="icon-trash"></i>',
						));

					$btns = $btnEditUser . $btnDeleteUser;
					return '<div class="visible-md visible-lg hidden-sm hidden-xs btn-group full-width text-center">'. $btns .'</div>';
				}),
			);
		$output = datatable_processor_2("cms_items", "id", $columns, 1, "`type` = 'cms-testimonial' Order By `date_added` Desc");
	}
	function processor(){
		if (isPost('action')) {
			if (post('action') == 'save') { 
				/*Saving Testimonial*/
				$jsondata = json_decode(isPost('data') ? post('data') : array());

				$selected_testimonial_id = isset($jsondata->id) ? $jsondata->id : 0;

				$testimonial = $this->model->db->select("Select * From `cms_items` Where `type` = 'cms-testimonial' and `id` = '{$selected_testimonial_id}'");
				$testimonial = count($testimonial) ? $testimonial[0] : array();
				$testimonial_meta = isset($testimonial->meta) ? json_decode($testimonial->meta) : array();

				$meta = array(
					'author' => isset($jsondata->author) ? $jsondata->author : '',
					'company' => isset($jsondata->company) ? $jsondata->company : '',
					'position' => isset($jsondata->position) ? $jsondata->position : '',
					'profile_picture' => isset($testimonial_meta->profile_picture) ? $testimonial_meta->profile_picture : "",
					);

				if ($selected_testimonial_id == 0) {
					$this->model->db->table = 'cms_items';
					$this->model->db->data =array(
						'guid' => 0,
						'type' => 'cms-testimonial',
						'value' => isset($jsondata->content) ? $jsondata->content : '',
						'status' => isset($jsondata->status) ? $jsondata->status : 'pending',
						'meta' => json_encode($meta),
						);
					$new_id = $this->model->db->insertGetID();

					echo json_encode(array('status'=>'success', 'message' => 'Successfully added new testimonial', 'id'=>$new_id));
				}else{
					if (count($this->model->db->select("Select * From `cms_items` Where `type` = 'cms-testimonial' and `id` = '{$selected_testimonial_id}'"))) {
						$this->model->db->table = 'cms_items';
						$this->model->db->data =array(
							'id' => $selected_testimonial_id, 
							'value' => isset($jsondata->content) ? $jsondata->content : '',
							'status' => isset($jsondata->status) ? $jsondata->status : 'pending',
							'meta' => json_encode($meta),
							);
						if ($this->model->db->update()) {
							echo json_encode(array('status'=>'success', 'message' => 'Successfully deleted a testimonial', 'id'=>$selected_testimonial_id));
						}else{
							echo json_encode(array('status'=>'error', 'message' => 'Unable to save testimonial', 'id'=>$selected_testimonial_id));
						}
					}else{
						echo json_encode(array('status'=>'error', 'message' => 'No Testimonial Found', 'id' => 0));
					}
				}
			}elseif (post('action') == 'get') { 
				/*Retrieving detail of a specific testimonial*/
				$selected_testimonial_id = isPost('id') ? post('id') : 0;

				$testimonial = $this->model->db->select("Select * From `cms_items` Where `type` = 'cms-testimonial' and `id` = '{$selected_testimonial_id}'");
				$testimonial = count($testimonial) ? $testimonial[0] : array();

				echo json_encode(array('data'=>$testimonial));
			}elseif (post('action') == 'delete') {
				/*Delete a testimonial by changing the `status` value to "deleted"*/
				$selected_testimonial_id = isPost('id') ? post('id') : 0;

				if (count($this->model->db->select("Select * From `cms_items` Where `type` = 'cms-testimonial' and `id` = '{$selected_testimonial_id}'"))) {
					$this->model->db->data =array('id' => $selected_testimonial_id, 'status' => 'deleted');
					$this->model->db->table = 'cms_items';
					$this->model->db->update();
					echo json_encode(array('status'=>'success', 'message' => 'Successfully deleted a testimonial'));
				}else{
					echo json_encode(array('status'=>'error', 'message' => 'No Testimonial Found'));
				}
			}elseif (post('action') == 'upload') {
				/*Upload profile picture of a specific testimonial*/
				$targetFolder = '/images/testimonials/';
				$verifyToken = md5('unique_salt');

				if (!empty($_FILES)) {
					$tempFile = $_FILES['file']['tmp_name'];
					$targetPath = rtrim(FRONTEND_ROOT,'/') . $targetFolder;
					$image_url = rtrim(FRONTEND_URL,'/') . $targetFolder;

					if (!is_dir(rtrim(FRONTEND_ROOT,'/') . "/images")) {
						mkdir(rtrim(FRONTEND_ROOT,'/') . "/images");
					}
					if (!is_dir(rtrim(FRONTEND_ROOT,'/') . "/images/testimonials")) {
						mkdir(rtrim(FRONTEND_ROOT,'/') . "/images/testimonials");
					}

					$fileTypes = array('jpg','jpeg','gif','png');
					$fileParts = pathinfo($_FILES['file']['name']);

					if (in_array(strtolower($fileParts['extension']),$fileTypes)) {
						$new_file_name = sha1($_FILES['file']['name'] . time()) . "." . $fileParts['extension'];
						$targetFile = rtrim($targetPath,'/') . '/' . $new_file_name;
						$urlFile = rtrim($image_url,'/') . '/' . $new_file_name;

						if (is_valid_image_type($_FILES['file'])) {
							move_uploaded_file($tempFile,$targetFile);
						}

						/*Retrieving current testimonial*/
						$selected_testimonial_id = isPost('testimonial_id') ? post('testimonial_id') : 0;
						$testimonial = $this->model->db->select("Select * From `cms_items` Where `type` = 'cms-testimonial' and `id` = '{$selected_testimonial_id}'");
						$testimonial = count($testimonial) ? $testimonial[0] : array();
						/*Retriving the meta*/
						$m = json_decode($testimonial->meta);

						/*Adding image url to the meta value*/

						if (isset($m->profile_picture)) {
							$m->profile_picture = $urlFile;
						}

						/*Assigning Uploaded files to a testimonial*/
						$d = array(
							'id'  => $selected_testimonial_id,
							'meta'  => json_encode($m),
							);

						$this->model->db->data = $d;
						$this->model->db->table = 'cms_items';
						$result = $this->model->db->update();

						echo json_encode(array('status'=>$result, 'url' => $urlFile));
					} else {
						echo 'Invalid file type.';
					}
				}else{
					echo 'Embty File.';
				}
			}else{
				echo json_encode(array('status'=>'error', 'message' => 'Invalid Request'));
			}
		}
	}
}
