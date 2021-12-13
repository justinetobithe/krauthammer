<?php
include ROOT . "libraries/plugins/php-curl-class-master/src/Curl/Curl.php";
include ROOT . "libraries/plugins/php-curl-class-master/src/Curl/CaseInsensitiveArray.php";

use Curl\Curl;

class Patcher extends Controller{
	public $sample_patch = array(
		array(
			'type' => "file",
			'command' => "download temp2/patch.php temp/patch.php",
			'description' => "Adding download patch file",
			),
		);

	public $baseurl;
	public $frontend_url;

	function __construct(){
		parent::__construct();
		Session::handleLogin();
		$this->baseurl = FRONTEND_ROOT . "/";
		$this->frontend_url = PATCH_SOURCE;
	}
	function index(){
		$this->view->render('header');
		$this->view->render('patch/index');
		$this->view->render('footer');
	}
	function get(){
		$curl = new Curl();
		$curl->get($this->frontend_url, array(
			'action' => 'get',
			'last_update_id' => $this->get_last_update_id(),
			));

		if ($curl->error) {
			/*echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;*/
		} else {
			/*echo 'Response:' . "\n";*/
			/*header_json(); print_r( $curl->response->detail ); exit();*/
			$this->model->db->table = 'patch_log';
			foreach ($curl->response->detail as $key => $value) {
				$data = clone $value;
				$data = $this->model->db->escape_string( $data );

				$sql = "INSERT INTO `patch_log` (`id`, `version`, `type`, `meta`) VALUES('{$data->id}','{$data->version}','{$data->type}','{$data->meta}') ON DUPLICATE KEY UPDATE `version`='{$data->version}',`type`='{$data->type}', `meta`='{$data->meta}',`status`='pending'";

				$patch_id = $this->model->db->insert_returl_ID( $sql );

				if (!$patch_id) {
					$patch_id = $value->id;
				}

				if ($value->type == 'force') {
					if (@unserialize($value->meta)) {
						$patch = unserialize( $value->meta );
						$this->process_command( $patch );

						$result = $this->model->db->query("Update `patch_log` Set `status` = 'done', `date_installed` = NOW() Where `id` = '{$patch_id}'");
					}
				}
			}
		}
	}
	function install(){
		$update_id = isPost('current_patch') ? post('current_patch') : 0;

		if ($update_id == 0) {
			echo json_encode(array('status' => false));
		}else{
			$update = $this->model->db->select("Select * From `patch_log` Where `status` = 'pending' and `id` = '{$update_id}'");

			if (count($update) > 0) {
				if (@unserialize($update[0]->meta)) {
					$patch = unserialize( $update[0]->meta );
					$this->process_command( $patch );

					$result = $this->model->db->query("Update `patch_log` Set `status` = 'done', `date_installed` = NOW() Where `id` = '{$update[0]->id}'");

					if ($result) {
						echo json_encode(array('status' => true));
					}else{
						echo json_encode(array('status' => false));
					}
				}else{
					echo json_encode(array('status' => false));
				}
			}else{
				echo json_encode(array('status' => false));
			}
		}
	}
	function install_all(){
		header_json();

		$updates = $this->get_updates();
		$last_patch_id = 0;
		$install_count = 0;

		if (count($updates)) {
			foreach ($updates as $key => $value) {
				if (@unserialize($value->meta)) {
					$patch = unserialize( $value->meta );
					$last_patch_id = $value->id;
					$this->process_command( $patch );

					$result = $this->model->db->query("Update `patch_log` Set `status` = 'done', `date_installed` = NOW() Where `id` = '{$value->id}'");

					if ($result) {
						$install_count++;
					}
				}
			}
		}

		echo json_encode(array("installed" => $install_count));
	}
	function updates(){
		header_json();
		$this->get();
		$patches = $this->get_updates();

		foreach ($patches as $key => $value) {
			if (@unserialize($value->meta)) {
				$patches[$key]->meta = unserialize($value->meta);
			}else{
				$patches[$key]->meta = array();
			}
		}

		echo json_encode(array("updates" => $patches));
	}

	function prev_updates(){
		header_json();
		$this->get();
		$patches = $this->model->db->select("Select *, DATE_FORMAT(`date_installed`, '%M %e, %Y %r') `date_installed_formated` , if(`type`='force', 'Force Update', 'Manual Installation') `update_type` From `patch_log` Where `status` = 'done' Order By `date_installed` Desc");;

		foreach ($patches as $key => $value) {
			if (@unserialize($value->meta)) {
				$patches[$key]->meta = unserialize($value->meta);
			}else{
				$patches[$key]->meta = array();
			}
		}

		echo json_encode(array("updates" => $patches));
	}
	function get_last_update_id(){
		$last_updates = $this->model->db->select("Select * From `patch_log` Order By `id` Desc Limit 1 ");
		$last_update_id = 0;

		if (count($last_updates)) {
			$last_update_id = $last_updates[0]->id;
		}

		return $last_update_id;
	}
	function get_updates(){
		$available_patch = $this->model->db->select("Select * From `patch_log` Where `status` = 'pending'");
		return $available_patch;
	}

	function process_command($sample_patch = array()){
		foreach ($sample_patch as $key => $value) {
			if ($value['type'] == 'database') {
				$this->execute_db( $value['command'] );
			}elseif ($value['type'] == 'file') {
				$this->execute_file( $value['command'] );
			}elseif ($value['type'] == 'folder') {
				$this->execute_folder( $value['command'] );
			}
		}
	}
	function execute_db( $command_string = "" ){
		$this->model->db->query( $command_string );
	}
	/*Command String: [command-type] [parameter 1] [parameter 2]*/
	function execute_file( $command_string = "" ){
		$cmd = explode(' ', $command_string);

		if (isset($cmd[0])) {
			switch ($cmd[0]) {
				case 'delete':
				if (isset($cmd[1])) {
					$file = $this->baseurl . trim($cmd[1],'/');

					if (is_file( $file )) {
						unlink( $file );
					}
				}
				break;
				case 'add':
				if (isset($cmd[1])) {
					$file = $this->baseurl . trim($cmd[1],'/');

					$myfile = fopen($file, "w") or die("Unable to open file!");
					fclose($myfile);
				}
				break;
				case 'backup':
				if (isset($cmd[1]) && isset($cmd[1])) {
					$f = trim($cmd[1],'/');
					$baseurl = $this->baseurl;

					if (is_file($baseurl . $f)) {
						$dirs = explode("/", $f);
						$file = array_pop($dirs);
						$parent_dir = "backup";

						if (!is_dir($baseurl . $parent_dir)) {
							mkdir($baseurl . $parent_dir);
						}
						$parent_dir .= "/";

						foreach ($dirs as $key => $value) {
							if (!is_dir($baseurl . $parent_dir . $value)) {
								mkdir( $baseurl . $parent_dir . $value );
							}
							$parent_dir .= $value . "/";
						}

						copy($baseurl . $f, $baseurl . $parent_dir . $file);
					}
				}
				break;
				case 'download':
				if (isset($cmd[1]) && isset($cmd[1])) {
					$f = trim($cmd[1],'/');
					$file_origin = $this->frontend_url . "?action=download&type=file&file={$f}";
					$file_destination = $this->baseurl . trim($cmd[2],'/');

					$curl = new Curl();
					$curl->setOpt(CURLOPT_ENCODING , 'php');
					$curl->download($file_origin, $file_destination);
				}
				break;
				case 'line-replace':
				$str = preg_replace('/line-replace /', "", $command_string, 1);
				$cmd2 = preg_match_all('/(?<={)[^}]+(?=})/', $str, $m) ? $m[0] : Array();

				$target_file = $cmd2[0];
				$keyword = $cmd2[1];
				$line_repalcement = $cmd2[2];

				$file = $this->baseurl . $target_file;
				$seed_file = md5( $file );
				$temp_file = dirname($file) . "/" . $seed_file ."tempfileonly.tmp";

				if (!is_file( $file )) { return; }
				$reading = fopen($file, 'r');
				$writing = fopen($temp_file, 'w');

				$replaced = false;

				while (!feof($reading)) {
					$line = fgets($reading);
					if (stristr($line, $keyword)) {
						$line = $line_repalcement . "\n";
						$replaced = true;
					}
					fputs($writing, $line);
				}
				fclose($reading); fclose($writing);
				/*might as well not overwrite the file if we didn't replace anything*/
				if ($replaced) 
				{
					rename($temp_file, $file);
				} else {
					unlink($temp_file);
				}

				break;
				case 'line-add':
				$str = preg_replace('/line-add /', "", $command_string, 1);
				$cmd2 = preg_match_all('/(?<={)[^}]+(?=})/', $str, $m) ? $m[0] : Array();

				$target_file = $cmd2[0];
				$flat = $cmd2[1];
				$line_append = isset($cmd2[2]) ? $cmd2[2] : "";

				$file = $this->baseurl . $target_file;

				if (is_file($file)) {
					file_put_contents($file, "\n" .$line_append, FILE_APPEND | LOCK_EX);
				}

				break;

				default:
				break;
			}
		}

	}
	/*Command String: [command-type] [parameter 1] [parameter 2]*/
	function execute_folder( $command_string = "" ){
		$cmd = explode(' ', $command_string);

		if (isset($cmd[0])) {
			switch ($cmd[0]) {
				case 'delete':
				if (isset($cmd[1])) {
					$dir = $this->baseurl . trim($cmd[1],'/');

					if (is_dir( $dir )) {
						$this->_deleteDir( $dir );
					}
				}
				break;
				case 'add':
				if (isset($cmd[1])) {
					$dir = trim($cmd[1],'/');
					$dirs = explode("/", $dir);
					$parent_dir = $this->baseurl;

					foreach ($dirs as $key => $value) {
						if (!is_dir( $parent_dir . $value )) {
							mkdir( $parent_dir . $value );
						}
						$parent_dir .= $value ."/";
					}
				}
				break;

				default:

				break;
			}
		}
	}

	public static function _deleteDir($dirPath) {
		if (! is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::_deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}

	function log_patch(){

	}
}