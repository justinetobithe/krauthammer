<?php

class Users_Model extends Model{


	public function __construct(){
		parent::__construct();
		$this->db->table='system_users';
	}

	public function insertUser($role,$username,$password,$full_name,$email){
		$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
		$salt = md5(time().$randomString);
		$pass = sha1($salt.$password.$salt);
		$date = date('Y-m-d H:i:s');
		$data = array(
			"user_role" => $role,
			"username" => $username,
			"salt" => $salt,
			"password" => $pass,
			"user_fullname" => $full_name,
			"user_email" => $email,
			"user_registered" => $date,
			);

		$this->db->table = "system_users";
		$this->db->data = $data;
		$new_user_id = $this->db->insertGetID();
		$qry = $new_user_id;

		$this->setDefaultRole( $new_user_id, $role );

		if($qry){
			if (!empty($_FILES)) {
				$filename = $this->upload($new_user_id);
				$this->db->table = "system_users";
				$this->db->data = array(
					"id" 						=> $new_user_id,
					"user_profpic" 	=> $filename,
				);
				$this->db->update();
			}

			return true;
		}
		return false;
	}
	public function setDefaultRole( $user_id = 0, $role = 0 ){
		if ($user_id == 0) {
			return;
		}

		/* default role module */
		$set_role_models = array(
			"super_admin" => array( "products", "orders", "invoices", "customers", "posts", "pages", "enquiries", "appearance", "users", "ecommerce", "payment", "shipping", "settings", "super-admin"),
			"administrator" => array( "products", "orders", "invoices", "customers", "posts", "pages", "enquiries", "appearance", "users", "ecommerce", "payment", "shipping", "settings"),
			"editor" => array( "products", "orders", "invoices", "customers", "posts", "pages", "enquiries", "appearance", "ecommerce", "payment", "shipping"),
			);

		$role_modules = array();

		switch ( $role ) {
			case 'administrator':
				$role_modules = $set_role_models['administrator'];
				break;
			case 'editor':
				$role_modules = $set_role_models['editor'];
				break;
			case 'super_admin':
				$role_modules = $set_role_models['super_admin'];
				break;
			default:
				$role_modules = array();
				break;
		}

		foreach ($role_modules as &$value) { 
			$value = "'{$value}'"; 
		}

		$role_includes = implode(",", $role_modules);
		$sql = "Select * From `modules` Where `module_name` In ({$role_includes})";

		$modules = $this->db->select($sql);

		$this->db->table = 'user_modules';
		foreach ($modules as $key => $value) {
			$data = array(
				"user_id" => $user_id,
				"module_id" => $value->id,
				);

			$this->db->data = $data;
			$this->db->insertGetID();
		}
	}
	public function getUserName($i){
		$qry = $this->db->query("SELECT * FROM ".$this->db->table." WHERE `username` = '$i'");

		if($qry){
			return $this->db->numRows($qry);
		}
	}
	public function getEmail($i){
		$qry = $this->db->query("SELECT * FROM ".$this->db->table." WHERE `user_email` = '$i' ");

		if($qry){
			return $this->db->numRows($qry);
		}
	}
	public function getUsers(){
		$qry = $this->db->query("SELECT * FROM ".$this->db->table. " ORDER BY `id` DESC");
		$rows = array();

		if($qry){
			while($row = $this->db->fetch($qry,"array"))
				$rows[] = $row;
		}

		return $rows;
	}
	public function tabulateUsers(){
		$output = array(
			"sEcho" => 1,
			"iTotalRecords" => 1,
			"iTotalDisplayRecords" => 1,
			"aaData" => array()
			);

		$sql = "Select * From `system_users`";

		if (SESSION::get('user_role') != 'super_admin'){
			$sql .= " Where `user_role` <> 'super_admin'"; 
		}

		$columns = array(
			'id',
			'user_role',
			'username',
			'user_fullname',
			'user_email',
			);

		$output = datatable_processor($columns, "id", "", $sql);

		foreach($output['aaData'] as $kk=>$vv){
			$btnEditUser = table_button(array(
				"class" => "btn btn-minier btn-info btn-edit",
				"data-rel" => "tooltip",
				"data-placement" => "top",
				"title" => "Select Customer",
				"data-value" => $vv[0],
				"label" => '<i class="icon-edit bigger-120"></i>',
				));
			$btnDeleteUser = table_button(array(
				"class" => "btn btn-minier btn-danger btn-delete",
				"data-rel" => "tooltip",
				"data-placement" => "top",
				"title" => "Select Customer",
				"data-value" => $vv[0],
				"label" => '<i class="icon-trash bigger-120"></i>',
				));

			$btns = '<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">'. $btnEditUser . $btnDeleteUser .'</div>';

			$output['aaData'][$kk][0] = "";
			$output['aaData'][$kk][5] = $btns;
		}

		echo json_encode($output);
	}
	public function deleteUser($id){
		$qry = $this->db->query("DELETE FROM ".$this->db->table." WHERE `id` = '$id'");

		if($qry){
			return true;
		}

		return false;
	}
	public function getUserByID($id){
		$qry = $this->db->query("SELECT * FROM ".$this->db->table." WHERE `id` = '$id'");

		if($qry){
			$row = $this->db->fetch($qry,"array");
		}

		return $row;
	}
	public function updateUser($id,$role,$username,$password,$full_name,$email){
		/* Get Current User */
		$current_user = $this->db->select("SELECT * FROM system_users WHERE id='{$id}'");

		$var = '';
		if($password=="empty"){
			$var = "UPDATE ".$this->db->table." SET `user_role`='$role',`username`='$username',`user_fullname`='$full_name',`user_email`='$email' WHERE `id` = '$id' ";
		}
		else{
			$salt = $this->getSalt($id);
			$pass = sha1($salt.$password.$salt);
			$var = "UPDATE ".$this->db->table." SET `user_role`='$role',`username`='$username',`password`='$pass',`user_fullname`='$full_name',`user_email`='$email' WHERE `id` = '$id' ";
		}

		$qry = $this->db->query($var);

		if (count($current_user) > 0) {
			if ($_SESSION['current_user'] == $current_user[0]->username) {
				session_destroy();
			}
		}

		if($qry){
			if (!empty($_FILES)) {
				$filename = $this->upload($id);
				$this->db->table = "system_users";
				$this->db->data = array(
					"id" 						=> $id,
					"user_profpic" 	=> $filename,
				);
				$this->db->update();
			}

			return true;
		}

		return false;
	}
	function upload($id=0){
		// header_json(); print_r($_FILES); exit();
		if (!empty($_FILES)) {
			// header_json(); print_r($_FILES); exit();
			$tempFile = $_FILES['user-profile-picture']['tmp_name'];

			$name = strtolower($_FILES['user-profile-picture']['name']);
			$name = preg_replace("/[^a-z0-9.]+/i", " ", $name);
			$name = preg_replace("/[\s-]+/", " ", $name);
			$name = preg_replace("/[\s_]/", "-", $name);

			$type = $_FILES['user-profile-picture']['type'];
			$ext 	= explode('.', $name);
			$ext 	= end($ext);

			$targetPath = "files/uploads/profile";
			$targetFile =  "/{$targetPath}/" . md5(time() . uniqid() . $name). ($ext!=''?".{$ext}" :'');

			@mkdir(FRONTEND_ROOT . "/{$targetPath}", 0755, true);
			if (!is_valid_image_type($_FILES['user-profile-picture'])) {
				echo "Invalid File!"; exit();
			}
			move_uploaded_file($tempFile,FRONTEND_ROOT . $targetFile);

			if (function_exists("exif_read_data") && 
					function_exists("imagecreatefromjpeg") && 
					function_exists("imagerotate") && 
					function_exists("imagedestroy") && 
					function_exists("imagecreatetruecolor") && 
					function_exists("imagecopyresampled") && 
					function_exists("imagesx") && 
					function_exists("imagesy") && 
					function_exists("getimagesize") && 
					function_exists("imagejpeg")) {
				$this->image_fix_orientation(FRONTEND_ROOT . $targetFile);
			}

			/* Create Thumbnail */
			$img_src 	= FRONTEND_ROOT . $targetFile;

			$img_path	= explode("/", $img_src);
			$filename	= array_pop($img_path);
			$img_path	= implode("/", $img_path);

			$dest 		= "{$img_path}/thumb";
			$img_des 	= "{$dest}/" . (explode('.', $filename)[0]) . ".jpg";

			if (!is_file($img_des)) {
				$im = imagecreatefromjpeg($img_src);
				$x = imagesx($im);
				$y = imagesy($im);
				$size = min($x, $y);
				$im2 = imagecrop($im, ['x' => ($x/2)-($size/2), 'y' => ($y/2)-($size/2), 'width' => $size, 'height' => $size]);
				if ($im2 !== FALSE) {
					@mkdir($dest, 0755, true);
					imagejpeg($im2, $img_des);

					list($width, $height) = getimagesize($img_des);
					$newwidth = 100;
					$newheight = 100;
					// Load
					$t = imagecreatetruecolor($newwidth, $newheight);
					$s = imagecreatefromjpeg($img_des);
					// Resize
					imagecopyresampled($t, $s, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
					// Output
					imagejpeg($t, $img_des);
				}
			}

			return FRONTEND_URL . $targetFile;
		}
	}
	function image_fix_orientation($path){
		ini_set('memory_limit','-1');

		$image = imagecreatefromjpeg($path);
		$exif = exif_read_data($path);

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

		imagejpeg($image, $path);
		imagedestroy($image);

		return true;
	}

	public function getSalt($id){
		$qry = $this->db->query("SELECT * FROM ".$this->db->table." WHERE `id` = '$id' ");

		if($qry){
			$row = $this->db->fetch($qry,"array");
		}

		return $row['salt'];
	}
}