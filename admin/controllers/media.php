<?php

class Media extends Controller{
	function __construct(){
		parent::__construct();
		Session::handleLogin();
	}

	function index(){
		$this->view->setStyleFile('media');

		$this->view->render('header');
		$this->view->render('media/media');
		$this->view->render('footer');
	}

	function upload(){
		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];

			$name = strtolower($_FILES['file']['name']);
			$name = preg_replace("/[^a-z0-9.]+/i", " ", $name);
			$name = preg_replace("/[\s-]+/", " ", $name);
			$name = preg_replace("/[\s_]/", "-", $name);

			$type = $_FILES['file']['type'];
			$type = $type != "" ? $type : 'others';
			$ext = explode('.', $name);
			$ext = end($ext);

			$targetPath = "files/uploads/{$type}/" . date("Y") . "/" . date('m') . "/" . date("d");
			$targetFile =  "/{$targetPath}/" . md5(time() . uniqid() . $name). ($ext!=''?".{$ext}" :'');

			@mkdir(FRONTEND_ROOT . "/{$targetPath}", 0755, true);

			if ($this->is_validate_file($_FILES['file'])) {
				move_uploaded_file($tempFile,FRONTEND_ROOT . $targetFile);
				if (function_exists("exif_read_data") && 
						function_exists("imagecreatefromjpeg") && 
						function_exists("imagerotate") && 
						function_exists("imagedestroy") && 
						function_exists("imagejpeg")) {
					$this->image_fix_orientation(FRONTEND_ROOT . $targetFile);
				}

				$meta = array(
					"original_name" => $name,
					"original_size" => $_FILES['file']['size'],
				);

				$this->model->db->table = "cms_files";
				$this->model->db->data = array(
					"filename"	=> $name,
					"url" 			=> FRONTEND_URL . $targetFile,
					"type" 			=> $_FILES['file']['type'] != '' ? explode('/', $_FILES['file']['type'])[0] : 'file',
					"mime" 			=> $_FILES['file']['type'],
					"meta" 			=> json_encode($meta),
					"status" 		=> "active",
				);
				$new_id = $this->model->db->insertGetID();

				if (isset($_POST['get_id'])) {
					echo json_encode(array('id' => $new_id, 'url' => FRONTEND_URL . $targetFile));
				}else{
					echo FRONTEND_URL . $targetFile;
				}
			}else{
				header_404(); echo "Invalid File."; exit();
			}
		}
	}
	function is_validate_file($file = ""){
		if (is_valid_image_type($file) || is_valid_audio_type($file) || is_valid_audio_video($file) || is_valid_docs($file)) {
			return true;
		}else{
			return false;
		}
	}

	function image_fix_orientation($path){
		ini_set('memory_limit','-1');

		$image = @imagecreatefromjpeg($path);
		$exif = @exif_read_data($path);

		if (empty($exif['Orientation'])){
			return false;
		}

		switch ($exif['Orientation']){
			case 3:
			$image = imagerotate($image, 180, 0);
			break;
			case 6:
			$image = imagerotate($image, - 90, 0);
			break;
			case 8:
			$image = imagerotate($image, 90, 0);
			break;
		}

		@imagejpeg($image, $path);
		@imagedestroy($image);

		return true;
	}

	function get_system_setting(){
		/* Credit: */
		/* https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size */
		$max_size = 0;
		$max_size_actual = -1;

		if ($max_size_actual < 0) {
			/*Start with post_max_size.*/
			$post_max_size = $this->parse_size(ini_get('post_max_size'));
			if ($post_max_size > 0) {
				$max_size = ini_get('post_max_size');
				$max_size_actual = $post_max_size;
			}

			/*If upload_max_size is less, then reduce. Except if upload_max_size is*/
			/*zero, which indicates no limit.*/
			$upload_max = $this->parse_size(ini_get('upload_max_filesize'));
			if ($upload_max > 0 && $upload_max < $max_size) {
				$max_size = ini_get('upload_max_filesize');
				$max_size_actual = $upload_max;
			}
		}

		$setting = array(
			"max_upload_size" 				=> $max_size,
			"actual_max_upload_size" 	=> $max_size_actual,
		);

		echo json_encode($setting);
	}
	function get_files(){
		$type = '';
		$filter = isPost('filter') ? post('filter') : array();

		if (isPost('type')) {
			if (post('type') == 'other') {
				$type = " and type NOT IN ('video', 'audio', 'image') ";
			}elseif(post('type') != 'all'){
				$type = " and type='". post('type') ."' ";
			}
		}

		$lmt = isset($filter['limit']) ? $filter['limit'] : 30;
		$off = isset($filter['offset']) ? $filter['offset'] : 0;

		$sql = "Select id, filename, url, type, mime From cms_files Where status='active' {$type} Order By id Desc LIMIT {$lmt} OFFSET {$off}";
		$fiels = $this->model->db->select($sql);
		echo json_encode($fiels);
	}

	private function parse_size($size) {
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); /* Remove the non-unit characters from the size.*/
		$size = preg_replace('/[^0-9\.]/', '', $size); /* Remove the non-numeric characters from the size.*/
		if ($unit) {
			/*Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.*/
			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
		}
		else {
			return round($size);
		}
	}
}