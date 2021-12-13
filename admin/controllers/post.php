<?php
class Post extends Controller{
	function __construct(){
		parent::__construct();
		Session::handleLogin();
	}

	function index(){
		$this->view->render('header');
		$this->view->render('post/index');
		$this->view->render('footer');
	}
	function add(){
		set_module_sub_title("Add");
		$categories = $this->model->load_post_categories();

		$this->view->set('categories', $categories);
		$this->view->set('language',$this->get_languages());
		$this->view->set('author',$this->get_authors());
		$this->view->set('current_user',isset($_SESSION['current_user']) ? $_SESSION['current_user'] : "");

		$this->view->render('header');
		$this->view->render('post/add');
		$this->view->render('footer');
	}
	function edit($id=""){
		set_module_sub_title("Edit");
		$categories = $this->model->load_post_categories();

		$this->view->set('js_files',array("post-comment"));

		$this->view->set('id',$id);
		$this->view->set('categories', $categories);
		$this->view->set('language',$this->get_languages());
		$this->view->set('author',$this->get_authors());

		$this->view->render('header');
		$this->view->render('post/edit');
		$this->view->render('footer');
	}
	function get_archiveds(){
		if(hasPost('action','get'))
		{
			echo json_encode($this->model->get_archiveds(post('id')));
		}
		else
			header('location:'.URL.'pages/');
	}
	function get_data_by_id(){
		if(hasPost('action', 'get')){
			echo json_encode($this->model->get_data_by_id(post('id'), post('lang')));
		}
		else
			header('location:'.URL.'pages/');
	}
	function remove_translation(){
		if (isset($_POST['page_id']) && isset($_POST['lang'])) {
			$page_id = $_POST['page_id'];
			$lang = $_POST['lang'];

			if ($this->model->db->query("Delete From `cms_posts_translate` Where `post_id` = '{$page_id}' and `language` = '{$lang}'")) {
				echo json_encode(array('status' => 'success', 'message' => "Successfully removed translation"));
			}else{
				echo json_encode(array('status' => 'error', 'message' => "Unable to removed translation"));
			}
		}
	}
	function get_url_slug(){
		if(hasPost('action', 'get'))
		{
			echo json_encode($this->model->get_url_slug(post('data'), (isPost('post_id') ? post('post_id') : 'en'), isPost('lang') ? post('lang') : 'en'));
		}
		else
			header('location:'.URL.'pages/');
	}
	function get_categories_by_post_id(){
		if(hasPost('action', 'get'))
		{
			echo json_encode($this->model->get_categories_by_post_id(post('id')));
		}
		else
			header('location:'.URL.'pages/');
	}

	function add_post(){
		if(hasPost('action', 'save'))
		{
			echo json_encode($this->model->add_post(post('data')));
		}
		else
			header('location:'.URL.'pages/');
	}
	function delete_post(){
		if(hasPost('action', 'delete'))
		{
			echo json_encode($this->model->delete_post(post('id')));
		}
		else
			header('location:'.URL.'pages/');
	}
	function save_post(){
		if(hasPost('action', 'save'))
		{
			echo json_encode($this->model->save_post(post('data')));
		}
		else
			header('location:'.URL.'pages/');
	}
	function __other($url=""){
		$categories = new loader();

		if($url[1] == 'categories' && empty($url[2]))
		{
			$categories->load_controller('post-categories','PostCategories','manage');
		}
		else if($url[1] == 'categories' && $url[2] == 'add')
		{
			$categories->load_controller_method('post-categories','PostCategories','add','');
		}
		else if($url[1] == 'categories' && $url[2] == 'edit' && $url[3] != "")
		{
			if($url[3] >= 0 && is_numeric($url[3]))
				$categories->load_controller_method('post-categories','PostCategories','edit',$url[3]);
			else
				$categories->load_error();
		}
		else if($url[1] == 'categories' && $url[2] == 'sorting')
		{
			$categories->load_controller_method('post-categories','PostCategories','sort');
		}
		else
			$categories->load_error();
	}
	function get_data(){
		$categories = $this->model->load_post_categories();
		$parent = array();
		foreach ($categories as $key => $category) {
			if($category['category_parent'] != 0){
				array_push($parent, $category['category_parent']);
			}
		}

		$haschildren = array_unique($parent);
		$sub_category = array();

		foreach ($categories as $key => $value) {
			if(in_array($value['category_parent'], $haschildren))
				$sub_category[] = $value['id'];
		}

		$categories_grouped = $this->getChild(0, $categories);

		echo json_encode($this->traceParent_3($categories_grouped)); exit();

		// echo '<script>';
		// echo 'var newData = ' . json_encode($this->traceParent_3($categories_grouped));
		// echo '</script>';
	}

	function getChild($parent_val=0, $data_list=array()){
		$temp = array();
		foreach ($data_list as $key => $value) {
			if($parent_val==$value['category_parent']){
				$temp[$value['id']]['data'] = $value; 
				$temp[$value['id']]['children'] = $this->getChild($value['id'], $data_list); 
			}
		}

		return $temp;
	}

	function traceParent($data_list=array()){
		$str = "";
		foreach ($data_list as $key => $value) {
			$data = $value['data'];

			$name = $data['category_name'];
			if(strlen($name) > 30)
				$name =  substr($name,0, 30).'..';
			$data1 = '<span data-rel="tooltip" data-placement="top" title="'.$data['category_name'].'">'.$name.'<input type="hidden" class="value" value='.$data['id'].'></span>';

			if(count($value['children'])>0)
				$str .= "'". $data['id'] ."':{name:'". $data1 ."',type:'folder',additionalParameters:{'children':{". $this->traceParent($value['children']) ."}}},";
			else
				$str .= "'". $data['id'] ."':{name:'". $data1 ."',type:'item'},";
		}
		return $str;
	}

	function traceParent_2($data_list=array(), $intent=""){
		$str = "";
		foreach ($data_list as $key => $value) {
			$data = $value['data'];

			$name = $data['category_name'];
			if(strlen($name) > 30)
				$name =  substr($name,0, 30).'..';
			$data1 = $intent . '<span data-rel="tooltip" data-placement="top" title="'.$data['category_name'].'">'.$name.'<input type="hidden" class="value" value='.$data['id'].'></span>';

			if(count($value['children'])>0)
				$str .= "'". $data['id'] ."':{name:'". $data1 ."',type:'item'}," . $this->traceParent_2($value['children'], $intent . ' - ');
			else
				$str .= "'". $data['id'] ."':{name:'". $data1 ."',type:'item'},";
		}

		$uncategorized = '<span data-rel="tooltip" data-placement="top" title="Uncategorized">Uncategorized<input type="hidden" class="value" value="0"></span>';
		$str .= "'0':{name:'". $uncategorized ."',type:'item'},";
		return $str;
	}

	function traceParent_3($data_list=array(), $intent=""){
		$output = array();

		foreach ($data_list as $key => $value) {
			$data = $value['data'];

			$name = $data['category_name'];
			if(strlen($name) > 30) $name =  substr($name,0, 30).'..';

			$data1 = $intent . '<span data-rel="tooltip" data-placement="top" title="'.$data['category_name'].'">'.$name.'<input type="hidden" class="value" value='.$data['id'].'></span>';

			if(count($value['children'])>0){
				$output[$data['id']] = array(
					"name" => $data1,
					"type" => 'item',
					);

				$temp = $this->traceParent_3($value['children'], $intent . ' - ');
				foreach ($temp as $key => $value) {
					$output[$key] = $value;
				}
			}else{
				$output[$data['id']] = array(
					"name" => $data1,
					"type" => 'item',
					);
			}
		}

		$uncategorized = '<span data-rel="tooltip" data-placement="top" title="Uncategorized">Uncategorized<input type="hidden" class="value" value="0"></span>';
		$output[0] = array(
			"name" => $uncategorized,
			"type" => 'item',
			);

		return $output;
	}

	function get_post(){
		echo json_encode($this->model->get_post());
	}

	function add_category(){
		if(hasPost('action', 'add'))
		{
			echo json_encode($this->model->add_category(post('data')));
		}
		else
			header('location:'.URL.'post/');
	}

	function upload_featured_image(){
		if (isPost('action')) {
			if (post('action') == 'post-featured-image') {
				$post_id = post('post_id');
				$this->upload( $post_id );
			}elseif (post('action') == 'post-cropped-featured-image') {
				$post_id = post('post_id');
				$this->upload_cropped( $post_id );
			}elseif (post('action') == 'get-image-url-info') {
				$post_id = post('post_id');
				$image = $this->get_image_detail( $post_id );
				echo json_encode( $image );
			}
		}
	}
	function upload( $post_id = 0 ){
		$targetFolder = '/images/post/featured-image/';

		if (!is_dir(rtrim(FRONTEND_ROOT,'/') . '/images')) { mkdir(rtrim(FRONTEND_ROOT,'/') . '/images');}
		if (!is_dir(rtrim(FRONTEND_ROOT,'/') . '/images/post')) { mkdir(rtrim(FRONTEND_ROOT,'/') . '/images/post');}
		if (!is_dir(rtrim(FRONTEND_ROOT,'/') . '/images/post/featured-image')) { mkdir(rtrim(FRONTEND_ROOT,'/') . '/images/post/featured-image');}

		$generated_name = sha1(rand()) . md5(time());

		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$targetPath = rtrim(FRONTEND_ROOT,'/') . $targetFolder;
			$image_url = rtrim(FRONTEND_URL,'/') . $targetFolder;

			$fileTypes = array('jpg','jpeg','gif','png');
			$fileParts = pathinfo($_FILES['file']['name']);

			if (in_array($fileParts['extension'],$fileTypes)) {
				$new_file_name = $generated_name . "." . $fileParts['extension'];
				$targetFile = rtrim($targetPath,'/') . '/' . $new_file_name;
				$urlFile = rtrim($image_url,'/') . '/' . $new_file_name;
				if (!is_valid_image_type($_FILES['file'])) {
					echo 'Invalid file type.'; exit();
				}
				move_uploaded_file($tempFile,$targetFile);

				$this->model->db->table = 'cms_posts';
				$this->model->db->data = array(
					"id" => $post_id, 
					"featured_image" => $urlFile, 
					"featured_image_crop" => "", 
					"featured_image_crop_data" => "", 
					);
				$result_id = $this->model->db->update();

				echo json_encode(array('id'=>$result_id, 'url' => $urlFile));
			} else {
				echo 'Invalid file type.';
			}
		}else{
			echo 'Embty File.';
		}
	}
	function upload_cropped( $post_id = 0 ){
		$targetFolder = '/images/post/featured-image-cropped/';

		if (!is_dir(rtrim(FRONTEND_ROOT,'/') . '/images')) { mkdir(rtrim(FRONTEND_ROOT,'/') . '/images');}
		if (!is_dir(rtrim(FRONTEND_ROOT,'/') . '/images/post')) { mkdir(rtrim(FRONTEND_ROOT,'/') . '/images/post');}
		if (!is_dir(rtrim(FRONTEND_ROOT,'/') . '/images/post/featured-image-cropped')) { mkdir(rtrim(FRONTEND_ROOT,'/') . '/images/post/featured-image-cropped');}

		$generated_name = sha1(rand()) . md5(time());

		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$targetPath = rtrim(FRONTEND_ROOT,'/') . $targetFolder;
			$image_url = rtrim(FRONTEND_URL,'/') . $targetFolder;

			$canvas_data = array(
				"canvasData" => post('canvasData'),
				"cropBoxData" => post('cropBoxData'),
				);

			$fileTypes = array('jpg','jpeg','gif','png');
			$fileParts = pathinfo($_FILES['file']['name']);

			$ext = isset( $fileParts['extension'] ) ? $fileParts['extension'] : 'jpg';
			$new_file_name = $generated_name . "." . $ext;
			$targetFile = rtrim($targetPath,'/') . '/' . $new_file_name;
			$urlFile = rtrim($image_url,'/') . '/' . $new_file_name;
			if (!is_valid_image_type($_FILES['file'])) {
				echo json_encode(value); exit();
			}
			move_uploaded_file($tempFile,$targetFile);

			$this->model->db->table = 'cms_posts';
			$this->model->db->data = array(
				"id" => $post_id, 
				"featured_image_crop" => $urlFile, 
				"featured_image_crop_data" => serialize($canvas_data), 
				);
			$result_id = $this->model->db->update();

			echo json_encode(array('id'=>$result_id, 'url' => $urlFile));
		}else{
			echo 'Embty File.';
		}
	}
	function get_image_detail($post_id = 0){
		$sql = "Select `id`, `featured_image`, `featured_image_crop`, `featured_image_crop_data` From `cms_posts` Where `id` = '{$post_id}'";
		$image = $this->model->db->select( $sql );
		if (!count($image)) { return array(); }
		$image = $image[0];
		$serialize_data = @unserialize($image->featured_image_crop_data);
		$image->featured_image_crop_data = $serialize_data ? $serialize_data : array();
		return $image;
	}

	function post_comment_table_processor(){
		$post_id = $_GET['post_id'];

		$output = array(
			"sEcho" => 1,
			"iTotalRecords" => 1,
			"iTotalDisplayRecords" => 1,
			"aaData" => array()
			);

		/*$sql = "SELECT `c`.*, `c2`.`post_title` FROM `cms_posts_items` `c` INNER JOIN `cms_posts` `c2` on `c`.`post_id` = `c2`.`id` WHERE `c`.`type` = 'post-comment' and `c`.`status`='active' ORDER BY `date_added` DESC";*/
		/*previously using `cms_posts_items` database table*/
		$sql = "SELECT `c`.*, `c2`.`post_title` FROM `comments` `c` INNER JOIN `cms_posts` `c2` on `c`.`post_id` = `c2`.`id` WHERE `c`.`type` = 'post-comment' and `c`.`parent_id` = 0 and `c`.`post_id` = '{$post_id}' and `c`.`status` <> 'deleted' ORDER BY `date_added` DESC";

		$columns = array(
			'id',
			'post_id',
			'parent_id',
			'type',
			'content',
			'author_name',
			'status',
			'post_title',
			'date_added',
			);

		$output = datatable_processor($columns, "id", "", $sql);

		$this->model->comment_set_cache($post_id);

		foreach($output['aaData'] as $kk=>$vv){
			$posted_date = date("Y-m-d H:i:s", strtotime($vv[8]));
			$status = '<span class="label arrowed-in arrowed-in-right '. ($vv[6] =='approved' ? "label-success" : ($vv[6] =='pending' ? "label-info" : "label-important")) .'">'.ucfirst($vv[6]).'</span>';

			$s = explode(" ", $vv[4]);
			$colexp = count($s) > 15 ? '<a href="javascript:void(0)" class="comment-toggle"><small>expand/collapse</small></a>' : "";
			$s_comment = count($s) > 15 ? '<p class="shorten-content">'.implode(' ', array_splice($s, 0, 15)).' ... </p><p class="main-comment" style="display:none">'.$vv[4].'</p>' : $vv[4];
			$r = $this->model->comment_hierarchy_node_count($post_id, $vv[0]);
			$comment_control = '<div class="comment-controls">'.$colexp.'<a href="javascript:void(0)" class="btn-view-comments pull-right btn-post-comment-reply" data-value="'.$vv[0].'"><small>Reply</small>( <span class="reply"><b>'.$r.'</b></span> )</a></div>';

			$output['aaData'][$kk][0] = '<div class="comment-collapse">'.nl2br($s_comment). $comment_control.'</div>';
			$output['aaData'][$kk][1] = $vv[5] ? $vv[5] : "-Unknown-";

			$btn = "";
			$btn .= '<div class="visible-md visible-lg hidden-sm hidden-xs text-center">';
			$btn .= '  <button class="btn btn-minier btn-success btn-post-comment-reply" data-rel="tooltip" data-placement="top" title="Reply" data-value="'.$vv[0].'">';
			$btn .= '    <i class="icon icon-reply bigger-120"></i>';
			$btn .= '  </button>';
			$btn .= '  <button class="btn btn-minier btn-info btn-post-comment-edit" data-rel="tooltip" data-placement="top" title="Edit" data-value="'.$vv[0].'">';
			$btn .= '    <i class="icon-edit bigger-120"></i>';
			$btn .= '  </button>';
			$btn .= '  <button class="btn btn-minier btn-danger btn-post-comment-delete" data-rel="tooltip" data-placement="top" title="Delete" data-value="'.$vv[0].'">';
			$btn .= '    <i class="icon-trash bigger-120"></i>';
			$btn .= '  </button>';
			$btn .= '</div>';

			$output['aaData'][$kk][2] = '<div class="relative-table-cell text-center">'.$posted_date.'<div class="response-overlay">'.$btn.'</div>'.$status.'</div>';
		}

		echo json_encode($output);
	}
	function get_languages(){
		return $this->model->db->select("Select `id`, `value`, `meta` `slug`, if(`guid`=1, 'selected' , '') `selected` FROM (SELECT * FROM (( Select *, '0' `is_default` From `cms_items` Where `type` = 'cms-language' Union( Select `id`, if((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guid`, 'cms-language' `type`, `value`, `meta`, `status`, `date_added`, '1' `is_default` From ( Select * From `cms_items` Where `type` = 'cms-language-default' Union (Select '0' `id`, '0' `guid`, 'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added`) Order By `id` desc Limit 1 ) `t4` ))) `t1` Where (`type` = 'cms-language' or `type` = 'cms-language-default') Order By `guid` desc, `status` asc, `value` asc) `t1` Where type = 'cms-language' and `status` = 'active' Order By `guid` desc, `value` asc");
	}
	function get_authors(){
		$current_user = isset($_SESSION['current_user']) ? $_SESSION['current_user'] : "";
		$sql = "Select `id`, `username`, `user_fullname`, if(`username` = '{$_SESSION['current_user']}', 'Y', '') `current_user` From `system_users`;";
		return $this->model->db->select( $sql );
	}
}