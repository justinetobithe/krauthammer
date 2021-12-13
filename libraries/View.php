<?php

class View{

	private $data =array();
	public $alt_location = "";

	function __construct(){}

	public function set($var,$val){
		$this->data[$var]=$val;
	}

	public function render($name){
		extract($this->data);
		if (is_file('views/themes/'.ACTIVE_THEME.'/'.$name.'.php')) {
			require 'views/themes/'.ACTIVE_THEME.'/'.$name.'.php';
		}else{
			if ($this->alt_location != "" && is_file(rtrim($this->alt_location, "/") . "/" . $name .'.php') ) {
				require rtrim($this->alt_location, "/") . "/" . $name .'.php';
			}else{
				/*create a flag*/
				$has_default = false;

				/*load system plugin settings*/
				$this->system_plugin_config = $this->load_system_plugin_config();

				/*check selected template on system_config [Templates]*/
				foreach ($this->system_plugin_config as $key => $value) {
					if (isset($value['Templates']) && isset($value['Templates'][$name])) {
						if ($has_default = is_file(ROOT . "system_plugins/{$key}/frontend/views/{$name}.php")) {
							/*loading the template file from the system plugins*/
							include ROOT . "system_plugins/{$key}/frontend/views/{$name}.php";
						}
					}
				}

				/*tempalte (page-cart, page-checkout, etc) file must be register in the config file*/
				/*if no file deltected, prompt Not Found File Error*/
				if (!$has_default) {
					echo "Not Found File: {$name}.php";
				}
			}
		}
	}

	public function error(){
		/* Removed the condition if current navigated url is under the blacklisted url_slugs */
		/* if (uc_blacklisted(get_current_url())) {} */
		/* Now All 404 page will x_robot_tag */
		x_robots_tag();

		header_404();

		if (is_file( 'views/themes/'.ACTIVE_THEME.'/error.php' )) {
			require 'views/themes/'.ACTIVE_THEME.'/error.php';
		}
	}

	private function load_system_plugin_config(){
		$system_plugins = array();

		$system_plugin_path = ROOT . "system_plugins";
		$system_plugin_directory = scandir($system_plugin_path);
		$system_plugin_directory = array_diff($system_plugin_directory, array('.', '..'));

		$system_type = get_system_option('system_type');

		foreach ($system_plugin_directory as $key => $value) {
			$system_plugin_config_file = ROOT . "system_plugins/{$value}/plugin-config.php";
			if (is_file($system_plugin_config_file)) {
				preg_match_all("/(.*)\s*:\s*(.*)/", file_get_contents($system_plugin_config_file), $system_plugin_menus);
				$temp_sys = array();
				foreach ($system_plugin_menus[0] as $k => $v) {
					if (trim($system_plugin_menus[1][$k] == 'Templates')) {
						if (!isset($temp_sys[$system_plugin_menus[1][$k]])) {
							$temp_sys[$system_plugin_menus[1][$k]] = array();
						}

						$temp = explode(',', $system_plugin_menus[2][$k]);
						$temp_sys[$system_plugin_menus[1][$k]][trim(isset($temp[1])?$temp[1]:'')] = trim(isset($temp[0])?$temp[0]:'');
					}else{
						$temp_sys[$system_plugin_menus[1][$k]] = $system_plugin_menus[2][$k];
					}
				}
				if (count($temp_sys)) {
					$system_plugins[$value] = $temp_sys;
				}
			}
		}

		return $system_plugins;
	}
}