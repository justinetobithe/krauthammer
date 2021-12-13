<?php

class Url {
	public $url = null;
	private $controller = null;
	private $controllerPath = 'controllers/';
	private $system_controllerPath = 'system_plugins/';
	private $system_modelPath = 'system_plugins/';
	private $errorPage = 'error.php';
	private $defaultPage = 'page.php';
	private $url_checker_status = '';
	private $uc;
	private $uc_data;

	public function __construct() {
		$this->uc = new UC();
	}
	public function initialize() {
		$this->getUrl();

		if (!empty($this->url[0])) {
			$this->loadExistingController();
			$this->callControllerMethod();
			return false;
		} 
		else{
			$this->loadDefaultController();
		}
	}

	public function getUrl() {
		global $cms_current_language;
		global $cms_reserved_language;
		global $cms_default_language;

		$uc = new UC();
		$this->url_data = $uc->uc_get_current_url_settings(get_current_url());
		
		$this->loadSiteMap($this->url_data['uc_params'][0]);

		$this->url = explode('/', trim($this->url_data['language']['slug_url'],'/'));

		$cms_current_language = $this->url_data['language']['exist'] && $this->url_data['language']['slug_selected'] != '' ? $this->url_data['language']['slug_selected'] : $this->url_data['language']['slug_default'];
		$cms_default_language = $this->url_data['language']['slug_default'];

		$url_db = new Database();
		$res_lang = $url_db->select("Select `id`, if((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guid`, 'cms-language' `type`, `value`, `meta`, `status`, `date_added`, '1' `is_default` From ( Select * From `cms_items` Where `type` = 'cms-language-default' Union ( Select '0' `id`, '0' `guid`, 'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added` ) Order By `id` desc Limit 1 ) `t4`");

		if (count($res_lang)) {
			$cms_reserved_language = $res_lang[0]->meta;
		}

		$this->formatRedirect();
	}

	public function loadSiteMap($params = ""){
		if ($params != "") {
			$file = __DIR__ . '/../xmls/' . $params;

      if (file_exists($file) && get_system_option('sitemap-enable') == 'ON') {
        header('Content-Type: application/xml');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
      }
		}
	}

	public function formatRedirect(){
		global $cms_current_language;
		$uc = new UC();
		$uc->set_current_language($cms_current_language);
		$url_data = $uc->uc_get_url_validity_info_2(get_current_url());

		if ($url_data['url_header'] == '301') {
			redirect( $url_data['final_url'] );
		}elseif($url_data['url_header'] == '404'){
			$this->url_checker_status = '404';
		}else{
			if (get_system_option('enable_https_redirect')=='ON') {
				if ($_SERVER['REQUEST_SCHEME'] != 'https') {
					redirect("https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
				}
			}
		}

		return;
	}

	private function loadControllerModel($controller = "Page", $model='page', $system_plugin_model = ""){
		if ($this->url_checker_status == '404') {
			$this->errorPage();
			exit();
		}
		$this->controller = new $controller();
		if ($system_plugin_model == "") {
			$this->controller->loadModel($model);
		}else{
			$this->controller->loadModel($model, $system_plugin_model);
		}
	}

	private function loadDefaultController() {
		require_once $this->controllerPath . $this->defaultPage;
		$this->loadControllerModel();
		$this->controller->index();
	}

	private function loadExistingController() {
		$url = implode('', array_map(function($e){ return ucfirst($e); }, explode('-', $this->url[0])));

		$this->uc->set_current_language(cms_get_language());
		$uc_controller = $this->uc->uc_url_controller_2( $this->url_data );	
		$fname = strtolower(trim(preg_replace("/([A-Z])/", "-$0", $uc_controller),'-'));

		$file = $this->controllerPath . strtolower($uc_controller) . '.php';


		if (file_exists($file)) {
			require_once $file;
			$this->loadControllerModel($uc_controller, $fname);
		} else {
			$files1 = scandir($this->system_controllerPath);
			$files1 = array_diff($files1, array('.', '..'));

			$system_type = get_system_option(array('option_name'=>'system_type'));
			$controller_name = $this->url[0];

			$v = "";
			if ($system_type == 'ECOMMERCE' || $system_type == 'ECATALOG') {
				$v = $system_type == 'ECOMMERCE' ? 'ecommerce' : ($system_type == 'ECATALOG' ? 'ecatalog' : '');

				$pref_temp = $this->uc->reserved_language != cms_get_language() ? '_'.cms_get_language() : '';
				$putemp = get_system_option("product_url{$pref_temp}");
				$pcutemp = get_system_option("product_category_url{$pref_temp}");

				if ($putemp != $pcutemp) {
					if ($controller_name == $pcutemp) {
						$controller_name = 'product-category'; /* Get the product controller if product page detected */
					}elseif ($controller_name == $putemp) {
						$controller_name = 'products'; /* Get the product controller if product page detected */
					}
					$url = implode('', array_map(function($e){ return ucfirst($e); }, explode('-', $controller_name)));
				}else{
					$lang = cms_get_language();

					$pdb = new Database();
					$_slug = $this->getControllerMethod();
					$p_sql = "SELECT * From (SELECT url_slug, 'product' type, c.meta, c.language FROM products p LEFT JOIN cms_translation c on p.id = c.guid and c.type = 'product' and c.language = '{$lang}' Where `url_slug` LIKE '{$_slug}' or c.meta LIKE '%\"{$_slug}\"%' ) t1 UNION (SELECT * FROM ( SELECT url_slug, 'product-category' type, c.meta, c.language FROM product_categories p LEFT JOIN cms_translation c on p.id = c.guid and c.type = 'product-category' and c.language = '{$lang}' Where `url_slug` LIKE '{$_slug}' or c.meta LIKE '%\"{$_slug}\"%') t2) Order by Field (type, 'product-category', 'product') Limit 1";

					// header_json(); print_r($p_sql); exit();
					
					$p = $pdb->select( $p_sql );

					if (isset($p[0])) {
						$controller_name = isset($p[0]->type) ? ($p[0]->type=='product' ? 'products' : 'product-category') : ''; /* Get the product controller if product page detected */
						$url = implode('', array_map(function($e){ return ucfirst($e); }, explode('-', $controller_name)));
					}
				}
			}else{
				$v = '';
			}

			if ($v != "") {
				$system_plugin_controller = $this->system_controllerPath . "{$v}/frontend/controllers/" . $controller_name . '.php';
				$system_plugin_model = $this->system_modelPath . "{$v}/frontend/models/";

				if (file_exists($system_plugin_controller)) {
					require_once $system_plugin_controller;
					$this->loadControllerModel($url, $controller_name, $system_plugin_model);
					/*$this->controller = new $url;
					$this->controller->loadModel($this->url[0], $system_plugin_model);*/

					return;
				}
			}

			/*Check on other folders*/
			foreach ($files1 as $key => $value) {
				if ($value == 'ecommerce' || $value == 'ecatalog') {
					continue;
				}
				$system_plugin_controller = $this->system_controllerPath . $value . "/frontend/controllers/" . $this->url[0] . '.php';
				$system_plugin_model = $this->system_modelPath . $value . "/frontend/models/";

				if (file_exists($system_plugin_controller)) {
					require_once $system_plugin_controller;
					$this->loadControllerModel($url, $this->url[0], $system_plugin_model);
					/*$this->controller = new $url;
					$this->controller->loadModel($this->url[0], $system_plugin_model);*/

					/*load plugin libraries*/
					/*foreach (glob($this->system_controllerPath . $value . "/libraries/" . '*.php') as $class) {
						require_once $class;
					}*/
					return;
				}
			}


			require_once $this->controllerPath . $this->defaultPage;
			$this->loadControllerModel();
			/*$this->controller = new Page();
			$this->controller->loadModel('page');*/

			return false;
		}
	}

	private function getControllerMethod(){
		if (isset($this->url[1])) {
			return $this->url[1];
		}else{
			return 'index';
		}
	}

	private function checkControllerMethod(){
		$method = '';
		if ($this->url > 1) {
			$method = str_replace('-', '_', $this->url[1]);
			if (method_exists($this->controller, $method)) {
				return $method;
			}else{
				if (method_exists($this->controller, '__other')) {
					return "";
				} else {
					return "__other";
				}
			}
		}else{
			return "index";
		}
	}

	private function callControllerMethod() {
		$length = count($this->url);
		$method = '';
		if ($length > 1) {
			$method = str_replace('-', '_', $this->url[1]);
			if (!method_exists($this->controller, $method)) {
				if (method_exists($this->controller, '__other')) {
					$this->controller->__other($this->url);
					return;
				} else {

					$this->errorPage();
				}
			}
		}
		switch ($length) {
			case 5:
			$this->controller->{$method}($this->url[2], $this->url[3], $this->url[4]);
			break;
			case 4:
			$this->controller->{$method}($this->url[2], $this->url[3]);
			break;
			case 3:
			$this->controller->{$method}($this->url[2]);
			break;
			case 2:
			$this->controller->{$method}();
			break;
			default:
			$this->controller->index();
			break;
		}
	}

	public function getController() {
		return $this->url[0];
	}

	private function errorPage() {
		require_once $this->controllerPath . $this->errorPage;
		$this->controller = new Error();
		$this->controller->view->error();
		exit;
	}	

	private function is_blacklisted(){
		$actual_link = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$blacklistes = get_system_option('blacklisted_url');

		if ($blacklistes !=="") {
			# code...
			
			$site_url = get_system_option('site_url');
			$blacklistes = explode("\n", $blacklistes);

			foreach ($blacklistes as $key => $value) {
				$temp_url = str_replace($site_url, "", $actual_link);

				if (ltrim(htmlspecialchars($temp_url),'/') === ltrim(htmlspecialchars($value),'/') ) {
					return true;
				}
			}
		}

		return false;
	}
}
