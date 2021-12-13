<?php

class View{

	private $data =array();
	private $alt_path;
	private $alt_path_root;
	private $patch_version;

	function __construct(){
		$this->setAlternativePath();
		$db = new Database();
		$p = $db->select("Select * From `patch_log` Where `status`='done' Order By `id` Desc Limit 1");
		$patch_version = md5((count($p) ? $p[0]->id : 0));

		$this->set('script_version', $patch_version);
		$this->set('style_version', $patch_version);
	}

	public function setAlternativePath($alt_path = URL, $alt_path_root = ROOT){
		$this->alt_path = $alt_path;
		$this->alt_path_root = $alt_path_root;
	}

	public function set($var,$val){
		$this->data[$var]=$val;
	}

	public function setScriptFile($file_name = ""){
		$file_raw = "assets/js/controllers/{$file_name}.js";

		if (is_file(ROOT . $file_raw)) {
			$this->data['main_js_file']=URL . $file_raw;
		}elseif(is_file($this->alt_path_root . $file_raw)){
			$this->data['main_js_file']=$this->alt_path . $file_raw;
		}
	}
	public function setScriptFiles($file_names = array()){
		$js_files = array();
		foreach ($file_names as $key => $value) {
			$file_raw = "assets/js/controllers/{$value}.js";

			if(is_file($this->alt_path_root . $file_raw)){
				$js_files[]=$this->alt_path . $file_raw;
			}elseif (is_file(ROOT . $file_raw)) {
				$js_files[]=URL . $file_raw;
			}
		}

		$this->data['js_plugin_files'] = $js_files;
	}

	public function setStyleFile($file_name = ""){
		$file_raw = "assets/css/custom/{$file_name}.css";

		if (is_file(ROOT . $file_raw)) {
			$this->data['main_css_file']=URL . $file_raw;
		}elseif(is_file($this->alt_path_root . $file_raw)){
			$this->data['main_css_file']=$this->alt_path . $file_raw;
		}
	}
	public function setStyleFiles($file_names = array()){
		$js_files = array();
		foreach ($file_names as $key => $value) {
			$file_raw = "assets/css/custom/{$value}.css";

			if(is_file($this->alt_path_root . $file_raw)){
				$js_files[]=$this->alt_path . $file_raw;
			}elseif (is_file(ROOT . $file_raw)) {
				$js_files[]=URL . $file_raw;
			}
		}

		$this->data['css_plugin_files'] = $js_files;
	}

	public function render($name){
		extract($this->data);
		if (is_file(ROOT . 'views/'.$name.'.php')) {
			require 'views/'.$name.'.php';
		}else if(is_file($this->alt_path_root . 'views/'.$name.'.php')){
			require $this->alt_path_root . 'views/'.$name.'.php';
		}
	}

	public function errorPage(){
		extract($this->data);
		require 'views/header.php';
		require 'views/error/index.php';
		require 'views/footer.php';
	}

	public function include_layout($directory_name = "", $variables = array()){
		extract($variables);
		include $dir = ROOT . "views/_layout/" . $directory_name . ".php";
	}
	public function include_component_files($directory_name = "", $variables = array()){
		extract($variables);
		$fragments = $this->get_component_files($directory_name);
		foreach ($fragments as $key => $value) {
			include $dir = ROOT . "views/" . $directory_name . "/" . $value['file'] . ".php";
		}
	}

	public function get_component_files($directory_name = ""){
		$dir = ROOT . "views/" . $directory_name;
		$scan = array_diff(scandir( $dir ), array('.', '..'));
		$tab_files = array();
		$templates = array();

		foreach ($scan as $key => $file) {
			$template = array( "title" => '', "order" => 0, "file" => '', );
			if( strpos(file_get_contents($dir."/".$file),'Title') !== false) {
				$lines = file($dir."/".$file);
				$name = explode(': ', $lines[2]);
				$template['title'] = trim($name[1], "\x0A");
				$template['title'] = trim($name[1], "\n");
			}
			if( strpos(file_get_contents($dir."/".$file),'Order') !== false) {
				$lines = file($dir."/".$file);
				$name = explode(': ', $lines[3]);
				$template['order'] = trim($name[1], "\x0A");
				$template['order'] = trim($name[1], "\n");
			}
			if( strpos(file_get_contents($dir."/".$file),'Order') !== false) {
				$lines = file($dir."/".$file);
				$name = explode(': ', $lines[4]);
				$template['id'] = trim($name[1], "\x0A");
				$template['id'] = trim($name[1], "\n");
			}
			$template['file'] = substr_replace($file ,"",-4);
			$templates[$template['order']] = $template;

		}

		ksort($templates);

		return $templates;
	}
	
}