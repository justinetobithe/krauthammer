<?php

require_once __DIR__ . "/../../plugins/url-checker/url-checker-2.php";

class UC extends UrlChecker{
	private $current_language;
	private $cached_categories;
	private $cached_product_categories;
	private $baseurl;

	function __construct(){
		parent::__construct();
		$this->set_current_language();

		$this->cached_categories = $this->load_cached_categories();
		$this->cached_product_categories = $this->load_cached_product_categories();
		$this->baseurl = get_system_option('site_url');
	}

	public function set_current_language($lang = ''){
		$lang = $lang =='' ? $this->reserved_language : $lang;
		$this->current_language = $lang;
	}
	public function uc_get_url_validity_info_2( $current_url = ""){
		$url 			= $current_url;
		$header 	= '200';
    $no_index = false;

		$loop_token 	= '';
		$loop_counter = 0;

		if ($this->blacklisted( $current_url )) {
			return array(
				"url" 				=> $current_url,
				"final_url" 	=> $current_url,
				"url_header" 	=> "404",
				);
		}

		$r301 = $this->uc_check_link( $current_url );

		if ($r301 != '') {
			return array(
				"url" 				=> $current_url,
				"final_url" 	=> $r301,
				"url_header" 	=> "301",
				);
		}

		$url_system_info 	= $this->uc_get_current_url_settings( $current_url );
		$url_info 				= $url_system_info['current_url_info'];
		$url_path 				= $url_info['path'];

		/* To removed start*/
		/*$homepage_redirect = $url_system_info['current_url_info']['path'] != $url_system_info['language']['slug_url'];*/

		$url = '';

		/* NEW URL CHECKER ALGO START */
		$uc_controller 	= $this->uc_url_controller_2( $url_system_info );
		$redirect_url		= trim($url_system_info['language']['slug_url'], '/');

		/*if( $homepage_redirect ){
			$header = '301';
			$url = $redirect_url;
		}else */
		
		if ($uc_controller == 'Page') {
			$page_validator_info = $this->uc_page_validator_2( $url_system_info );
			$url 			= $page_validator_info['new_url'];
			$header 	= $page_validator_info['header'];
			$no_index = $page_validator_info['no_index'];
		}elseif ($uc_controller == 'Categories') {
			$categories_validator_info = $this->uc_categories_validator_2( $url_system_info );
			if ($categories_validator_info['new_url'] != '') {
				$url 		= $categories_validator_info['new_url'];
				$header = $categories_validator_info['header'];
			}
		}elseif ($uc_controller == 'Products') {
			$product_validator_info = $this->uc_product_validator_2( $url_system_info );
			$url 			= $product_validator_info['new_url'];
			$header 	= $product_validator_info['header'];
			$no_index = $product_validator_info['no_index'];
		}elseif ($uc_controller == 'ProductCategory') {
			$product_category_validator_info = $this->uc_product_category_validator_2( $url_system_info );
			$url 		= $product_category_validator_info['new_url'];
			$header = $product_category_validator_info['header'];
		}else{
			$url 		= $redirect_url;
			$header = '200';
		}

		if (($uc_controller == 'Products' || $uc_controller == 'ProductCategory') && $header == '200') {
			$uc_params = explode('/', trim($url_system_info['language']['slug_url'],'/'));
			$_c = $uc_params[0];
			$_x = $uc_params;

			$pref_temp 	= $this->reserved_language != $this->current_language ? '_'.$this->current_language : '';
			$putemp 		= get_system_option("product_url{$pref_temp}");
			$putemp 		= $putemp!=''?$putemp:'products';
			$pcutemp 		= get_system_option("product_category_url{$pref_temp}");
			$pcutemp 		= $pcutemp!=''?$pcutemp:'product-category';

			if ($_c == 'product-category' && $_c != $pcutemp) {
				$_x[0] = $pcutemp;
			}elseif ($_c == 'products' && $_c != $putemp) {
				$_x[0] = $putemp;
			}

			/* Redirect Product page to new base URL */
			$url = implode('/', $_x);
			$header = '301';
		}

		$p = $url_system_info['site_url_info']['protocol'];
		$w = $url_system_info['site_url_info']['has_www'] ? 'www.' : '';
		$q = $url_system_info['current_url_info']['query'] != '' ? '?' . $url_system_info['current_url_info']['query'] : '';
		$l = $url_system_info['language']['is_default'] ? '' : $url_system_info['language']['slug_selected'] . ($url != '' ? '/' : '');
		$s = ($url_system_info['site_url_info']['has_slash']) ? '/' : '';
		if ("{$l}{$url}{$q}" == "") {
			if (!strpos($url_system_info['site_url_info']['host'], '/')) {
				$s = "";
			}
			if (strpos($url_system_info['site_url_info']['host'], '/')) {
				$s = "/";
			}
		}
		$h = $url_system_info['site_url_info']['host'] . ("{$l}{$url}{$q}" != "" ? "/" : "");

		$u = "{$p}://{$h}{$l}{$url}{$s}{$q}";

		$request_scheme = $_SERVER['REQUEST_SCHEME'] != '' ? $_SERVER['REQUEST_SCHEME'] : $p;

		$output = array(
			"url" => $current_url,
			"final_url" => $u,
      "url_header" => $header == '404' ? '404' : ($current_url != $u ? '301' : '200'),
			"no_index" => $no_index,
			);
		
		return $output;

		/* NEW URL CHECKER ALGO END */
	}
	public function uc_url_controller_2( $data = array() ){
		$current_url_setting = $data;

		$controllerPath 				= $this->uc_get_root().'controllers/';
		$system_controllerPath 	= $this->uc_get_root().'system_plugins/'; /*Plugin controller location*/
		$system_modelPath 			= $this->uc_get_root().'system_plugins/'; /*Plugin controller location*/
		$controller 						= null; /*Plugin controller location*/
		$defaultPage = 					"page.php";

		$uc_params = explode('/', trim($current_url_setting['language']['slug_url'],'/'));

		if (!empty($uc_params)) {
			/* Camel Case the first string of uc_params */
			$url = implode('', array_map(function($e){ return ucfirst($e); }, explode('-', $uc_params[0])));

			$file = $controllerPath . $uc_params[0] . '.php';

			$system_plugin_controller = $system_controllerPath . $uc_params[0] . "/frontend/controllers/" . $uc_params[0] . '.php';
			$system_plugin_model = $system_modelPath . $uc_params[0] . "/models/";

			if (file_exists($file)) {
				if (!class_exists($url)) {
					require $file;
				}
				/*$controller = new $url;*/
				$controller = $url;
				/*$this->controller->loadModel($this->url[0]);*/
			} else {
				/* get controllers from system_plugins folder */
				$files1 = array_diff(scandir($system_controllerPath), array('.', '..'));
				/* retrieving system type */
				$system_type = $this->system_option('system_type');

				/*CMS-Type base controller import*/
				if ($system_type == 'ECOMMERCE' || $system_type == 'ECATALOG') {
					/* sytem_plugin directory (ecommerce/ecatalog) */
					$v = strtolower($system_type);

					$_c = $uc_params[0];

					$pref_temp = $this->reserved_language != $this->current_language ? '_'.$this->current_language : '';
					$putemp = get_system_option("product_url{$pref_temp}");
					$pcutemp = get_system_option("product_category_url{$pref_temp}");
					
					$putemp = $putemp!=''?$putemp:'products';
					$pcutemp = $pcutemp!=''?$pcutemp:'product-category';

					if ($putemp != $pcutemp) {
						if ($_c == $pcutemp) {
							$_c = 'product-category'; /* Get the product controller if product page detected */
						}elseif ($_c == $putemp) {
							$_c = 'products'; /* Get the product controller if product page detected */
						}
						$url = implode('', array_map(function($e){ return ucfirst($e); }, explode('-', $_c)));
					}else{
						$lang = $this->current_language;

						$pdb = new Database();
						$_slug = "";

						if (isset($uc_params[1])) {
							$_slug = $uc_params[1];
						}else{
							$_slug = 'index';
						}

						$p_sql = "SELECT * From (SELECT url_slug, 'product' type, c.meta, c.language FROM products p LEFT JOIN cms_translation c on p.id = c.guid and c.type = 'product' and c.language = '{$lang}' Where `url_slug` LIKE '{$_slug}' or c.meta LIKE '%\"{$_slug}\"%' ) t1 UNION (SELECT * FROM ( SELECT url_slug, 'product-category' type, c.meta, c.language FROM product_categories p LEFT JOIN cms_translation c on p.id = c.guid and c.type = 'product-category' and c.language = '{$lang}' Where `url_slug` LIKE '{$_slug}' or c.meta LIKE '%\"{$_slug}\"%') t2) Order By Field(type, 'product-category', 'product') Limit 1";

						$p = $pdb->select( $p_sql );

						if (isset($p[0])) {
							/* Get the product controller if product page detected */
							$_c = isset($p[0]->type) ? ($p[0]->type=='product' ? 'products' : 'product-category') : '';
							$url = implode('', array_map(function($e){ return ucfirst($e); }, explode('-', $_c)));
						}
					}

					// header_json(); 
					// print_r($_c); 
					// print_r($url); 
					// exit();

					$system_plugin_controller = $system_controllerPath . "{$v}/frontend/controllers/" . $_c . '.php';
					$system_plugin_model = $system_modelPath . "{$v}/frontend/models/";

					if (file_exists($system_plugin_controller)) {
						if (!class_exists($url)) {
							require $system_plugin_controller;
						}
						$controller = $url;
						return $controller;
					}
				}

				/*Check on other folders*/
				foreach ($files1 as $key => $value) {
					if ($value == 'ecommerce' || $value == 'ecatalog') {
						continue;
					}
					$system_plugin_controller = $system_controllerPath . $value . "/frontend/controllers/" . $uc_params[0] . '.php';
					$system_plugin_model = $system_modelPath . $value . "/frontend/models/";

					if (file_exists($system_plugin_controller)) {
						if (!class_exists($url)) {
							require $system_plugin_controller;
						}
						$controller = $url;
						return $controller;
					}
				}

				/*Default Controller*/
				if (!class_exists('Page')) {
					require $controllerPath.$defaultPage;
				}
				/*$controller = new Page();*/
				$controller = 'Page';
				/*$controller->loadModel('page');*/
				return $controller;
			}
		} 
		else{
			/*Default Controller*/
			if (!class_exists('Page')) {
				require $controllerPath.$defaultPage;
			}
			/*$controller = new Page();*/
			$controller = 'Page';
			/*$controller->loadModel('page');*/
		}

		return $controller;
	}
	public function uc_page_validator_2( $data = array() ){
		$new_url = $data['language']['slug_url'];

		$output = array(
			'header'		=> '200',
			'new_url'		=> trim($new_url,'/'),
			'no_index'	=> false,
		);
		$url_info 	= $data['current_url_info']; /*to delete: not used inside the function*/

		$url_arr 		= explode('/', trim($data['language']['slug_url'], '/'));
		$url 				= end($url_arr);

		$trans_slug = '';

		if ($url == "") {
			$s = $this->homepage_slug_2();
			if ($s != "") {
				$trans_slug = $s;
				/*$sql = "Select * From `cms_posts` Where `url_slug` = '". $this->homepage_slug_2() ."'"; */
				$sql = "SELECT url_slug, ci.meta language, seo_no_index 
								FROM `cms_posts`, (
									SELECT id, meta 
									FROM `cms_items` 
									WHERE `type` = 'cms-language-default' 
									UNION ( SELECT '0' `id`, 'en' `meta` ) 
									ORDER BY `id` 
									DESC LIMIT 1
								) ci 
								WHERE url_slug = '{$s}' 
								UNION 
									SELECT url_slug, language, seo_no_index 
									FROM `cms_posts_translate` 
									WHERE `url_slug` = '{$s}' and language = '{$this->current_language}';";

				$temp = $this->uc_db->select( $sql );

				if (count($temp)>0) {
					$output['no_index'] 	= ($temp[0]->seo_no_index == 'Y');
					$output['header'] = '200';
				}else{
					/*error*/
					$output['header'] = '404';
				}
			}else{
				$output['header'] = '200';
			}
		}else{
			$len 						= sizeof($url_arr) - 1;
			$trans_slug 		= count($url_arr) ? end($url_arr) : '';
			$page_validate 	= $this->uc_page_validate_2($url_arr);

			if ($page_validate['header'] == '301') {
				$output['header'] 	= '301';
				$output['new_url'] 	= $page_validate['new_url'];
			}

			$url_slug = $url_arr[$len];

			$info = $this->uc_get_post_info_2( $url_slug );

			if ( count($info) ) {
				$output['no_index'] 	= ($info->seo_no_index == 'Y');

				if ($info->url_slug == $this->cms_get_homepage_slug()) {
					$output['header']		= '301';
					$output['new_url']	= '';
				}
			}else{
				$output['header'] = '404';
			}

			$info = $this->uc_get_blog_info_2( $url_arr );

			if ( count($info) ) {
				$trans_slug = $info->url_slug;
				$output['no_index'] 	= ($info->seo_no_index == 'Y');

				if ($info->orig_slug == $this->cms_get_blog_slug()) {
					$output['header']		= '200';
					$output['new_url']	= trim($new_url,'/');
				}
			}else{
				$output['header'] = '404';
			}

			// header_json(); print_r($info); exit();
		}

		if ($this->current_language != $this->reserved_language) {
			$sql_trans = "SELECT * FROM `cms_posts_translate` WHERE `url_slug` = '{$trans_slug}' and `language` = '{$this->current_language}'";
			if (!count($this->uc_db->select( $sql_trans ))) {
				$output['header'] = '404';
			}
		}

		// header_json(); print_r($output); exit();

		return $output;
	}
	public function uc_get_post_info_2( $url_slug = "" ){
		$sql = "SELECT * FROM (
							SELECT * FROM `cms_posts` 
							UNION ( 
								SELECT `post_id` `id`, `post_author`, `post_date`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_type`, `url_slug`, `old_slug`, `seo_canonical_url`, `page_template`, `seo_title`, `seo_description`, `seo_no_index`, `parent_id`, `status`, `featured_image`, `featured_image_crop`, `featured_image_crop_data`, `meta_data`, `date_added` 
								FROM `cms_posts_translate` `c`
							)
						) `cms_posts` 
						WHERE `url_slug` = '{$url_slug}';";
		
		$info = $this->uc_db->select($sql); 
		return count($info) ? $info[0] : array();
	}
	public function uc_get_blog_info_2( $url_slug = [] ){
		$blog_slug = array_shift($url_slug);
		$sql = "SELECT * FROM (
							SELECT *, `cms_posts`.`url_slug` `orig_slug` FROM `cms_posts` 
							UNION ( 
								SELECT `post_id` `id`, `c`.`post_author`, `c`.`post_date`, `c`.`post_content`, `c`.`post_title`, `c`.`post_excerpt`, `c`.`post_status`, `c`.`post_type`, `c`.`url_slug`, `c`.`old_slug`, `c`.`seo_canonical_url`, `c`.`page_template`, `c`.`seo_title`, `c`.`seo_description`, `c`.`seo_no_index`, `c`.`parent_id`, `c`.`status`, `c`.`featured_image`, `c`.`featured_image_crop`, `c`.`featured_image_crop_data`, `c`.`meta_data`, `c`.`date_added`, `cms_posts`.`url_slug` `orig_slug`
								FROM `cms_posts_translate` `c`
								Left Join `cms_posts` On `c`.`post_id` = `cms_posts`.`id`
							)
						) `cms_posts` 
						WHERE `url_slug` = '{$blog_slug}';";

		// header_json(); print_r($sql); exit();

		$info = $this->uc_db->select($sql); 
		return count($info) ? $info[0] : array();
	}
	public function uc_page_validate_2($url = array()){
		$url_slug = end($url);
		$output 	= array(
			'header' 	=> '200',
			'new_url'	=> implode('/', $url),
			);
		$page 		= $this->uc_db->select( "SELECT * FROM `cms_posts` WHERE `url_slug` = '{$url_slug}'" );

		$sql = "SELECT c.*, IFNULL(cp.url_slug, c.url_slug) trans_slug 
						FROM cms_posts c 
						LEFT JOIN cms_posts_translate cp On cp.post_id = c.id and language = '{$this->current_language}'
						WHERE c.url_slug = '{$url_slug}'";

		$page 		= $this->uc_db->select( $sql );

		if (!count($page)) {
			$output['header'] = '404';
			return $output;
		}

		$page = $page[0];

		if ($page->post_type == 'post') {
			if ( $this->cms_validate_post_url_2( $page, implode('/', $url) ) ) {
				return $output;
			}else{
				$possible_url 			= $this->cms_get_post_url_2( $page );
				$output['header'] 	= '301';
				$output['new_url'] 	= $possible_url['new_url'];
			}
		}else{
			$sql 	= "SELECT c.id , c.parent_id, IFNULL(cp.url_slug, c.url_slug) url_slug 
							FROM cms_posts c 
							LEFT JOIN cms_posts_translate cp On cp.post_id = c.id and language = '{$this->current_language}'";
			$pages = $this->uc_db->select( $sql );
			$_p 	= array();
			foreach ($pages as $key => $value) {
				$_p[$value->id] = $value;
			}

			$_c = $_p[$page->id];
			$p 	= array($page->url_slug);
			while (isset($_p[$_c->parent_id])) {
				$_c = $_p[$_c->parent_id];
				array_unshift($p, $_c->url_slug);
			}


			// $p 					= array($page->url_slug);
			// $parent_id 	= $page->parent_id;
			// while($parent_id != 0){
			// 	$rowx 			= $this->uc_db->select( "SELECT `url_slug`,`parent_id` FROM `cms_posts` WHERE `id` = '{$parent_id}'" )[0];
			// 	$parent_id 	= $rowx->parent_id;
			// 	array_unshift($p, $rowx->url_slug);
			// }

			$parent = implode('/', $p);

			/*test if current url matches the url with parent url slug*/
			if ($parent != implode("/", $url)) {
				$output['header'] 	= '301';
				$output['new_url'] 	= $parent;
			}
		}

		return $output;
	}
	public function uc_categories_validator_2( $data = array() ){
		$current_url = $data['current_url'];

		$url_system_info = $data;

		$pc = explode('/', trim($data['language']['slug_url'],'/'));

		$output = array(
			"header" => '200',
			"new_url" => implode('/', $pc),
			);

		$pc_slug = count($pc) > 1 ? end($pc) : '';

		if ($this->current_language == $this->reserved_language) {
			$current_category = $this->uc_db->select("Select * From `post_category` Where `url_slug` = '{$pc_slug}'");
			if (count($current_category)<= 0) {
				$output['header'] = '404';
			}else{
				$parent_slug_new = $this->get_parent_slug($current_category[0]->id, $this->reserved_language);
			}
		}else{
			$category_exist = false;
			$parent_slug_new = "";

			if (isset($this->cached_categories[$this->current_language])) {
				foreach ($this->cached_categories[$this->current_language] as $key => $value) {
					$m = json_decode($value->meta);
					if (isset($m->url_slug) && $m->url_slug == $pc_slug) {
						$parent_slug_new = $this->get_parent_slug($value->id, $this->current_language);
						$category_exist = true;
					}
				}

				if (!$category_exist) {
					$output['header'] = '404';
				}
			}else{
				$output['header'] = '404';
			}

			$bckslsh = substr($this->baseurl, -1)=='/' ? '/' : '';
			$new_url = trim($this->baseurl,'/') . ($this->current_language != $this->default_language?"/{$this->current_language}":'') . "/categories{$parent_slug_new}{$bckslsh}";

			if (trim($new_url,'/') != trim($current_url,'/')) {
				$output['header'] = '404';
			}
		}

		return $output;
	}
	public function uc_product_validator_2( $data = array() ){
		$db = new Database();

		$current_url 			= $data['current_url'];
		$url_system_info 	= $data;
		$uc_temp 					= explode('/', trim($url_system_info['language']['slug_url'],'/'));
		// $uc_prod_slug 		= isset($uc_temp[1]) ? $uc_temp[1] : '';
		$uc_prod_slug 		= count($uc_temp) > 1 ? end($uc_temp) : (isset($uc_temp[1]) ? $uc_temp[1] : '');

		$output = array(
			"header" => '200',
			"new_url" => implode('/', $uc_temp),
			"no_index" => false,
			);

		if (isset($uc_prod_slug)) {
			if ($uc_prod_slug == "" || $uc_prod_slug == "index") {
				/*getting the first product*/
				// $product = $this->uc_db->select("Select * From `products` Order By `id` Asc");
				// $url_slug = count($product) > 0 && isset($product[0]->url_slug) ? $product[0]->url_slug : "";
				// $url = "products/{$url_slug}";

				// if ($current_url != $url) {
				// 	$output = array(
				// 		"header" => '301',
				// 		"new_url" => $url,
				// 		);
				// }
			}else{
				$h404 = true;

				if ($this->current_language != $this->reserved_language) {
					// $sql = "Select `p`.* From `products` `p` Inner Join `cms_translation` `c` On `c`.`guid` = `p`.`id` and `language` = '{$this->current_language}' and `type`='product' Where `p`.`url_slug`='{$uc_prod_slug}' ";
					$sql = "SELECT cms_translation.*, products.seo_no_index 
									FROM cms_translation 
									LEFT JOIN products on products.id = cms_translation.guid 
									WHERE type = 'product' and language = '{$this->current_language}'";
					$temp = $this->uc_db->select( $sql );

					foreach ($temp as $key => $value) {
						$j = json_decode($value->meta);
						if ( isset($j->product)) {
							if (isset($j->product->url_slug) && $uc_prod_slug == $j->product->url_slug) {
								$h404 = false;
								$output['no_index'] = ($value->seo_no_index == 'Y');
								break;
							}
						}
					}
				}else{
					$sql = "Select * From `products` Where `url_slug`='{$uc_prod_slug}'";
					$_prod = $this->uc_db->select($sql);
					if (count( $_prod )>0) {
						$value = $_prod[0];
						$output['no_index'] = ($value->seo_no_index == 'Y');
						$h404 = false;
					}
				}

				if ($h404) {
					$output = array(
						"header" 		=> '404',
						"new_url" 	=> '',
						"no_index" 	=> false,
						);
				}
			}
		}

		return $output;
	}
	public function uc_product_category_validator_2( $data = array() ){
		$format_url 			= get_system_option('product_category_format_url');
		$current_url 			= $data['current_url'];
		$url_system_info 	= $data;

		$pc = explode('/', trim($data['language']['slug_url'],'/'));

		$output = array(
			"header" => '200',
			"new_url" => implode('/', $pc),
			);

		$pc_slug = count($pc) > 1 ? end($pc) : '';
		$parent_id = 0;

		$h404 = true;
		if ($this->current_language != $this->reserved_language) {
			$sql = "Select `p`.* From `product_categories` `p` Inner Join `cms_translation` `c` On `c`.`guid` = `p`.`id` and `language` = '{$this->current_language}' and `type`='product-category' Where `p`.`url_slug`='{$pc_slug}' ";

			$sql = "SELECT c.*, pc.category_parent FROM cms_translation c LEFT JOIN product_categories pc ON pc.id = c.guid WHERE c.type = 'product-category' and language = '{$this->current_language}';";
			$temp = $this->uc_db->select( $sql );

			foreach ($temp as $key => $value) {
				$j = json_decode($value->meta);
				if (isset($j->url_slug)) {
					if ($pc_slug == $j->url_slug) {
						$parent_id = $value->category_parent;
						$h404 = false;
						break;
					}
				}
			}
		}else{
			$sql = "Select * From `product_categories` Where `url_slug` = '{$pc_slug}'";
			$temp = $this->uc_db->select( $sql );
			if (count($temp)> 0) {
				$parent_id = $temp[0]->category_parent;
				$h404 = false;
			}
		}

		/* Determine URL Format */
		$lang_suffix = $this->current_language != $this->reserved_language ? "_{$this->current_language}" : "";
		$parent_url_slug = get_system_option("product_category_url{$lang_suffix}"); /* Initialize with the product category base url */
		if ($format_url == 'show_parent') {
			$limiter = 10;
			while ( $parent_id != 0 ) {
				$slug = "";
				$temp_parent_id = $parent_id;
				if (isset($this->cached_product_categories[$this->current_language][$parent_id])) {
					$m = json_decode($this->cached_product_categories[$this->current_language][$parent_id]->meta);
					$slug = isset($m->url_slug) ? $m->url_slug : "";
					$temp_parent_id = $this->cached_product_categories[$this->current_language][$parent_id]->category_parent;
				}

				if (isset($this->cached_product_categories[$this->reserved_language][$parent_id]) && $slug == "") {
					$slug = $this->cached_product_categories[$this->reserved_language][$parent_id]->url_slug;
					$temp_parent_id = $this->cached_product_categories[$this->reserved_language][$parent_id]->category_parent;
				}else{
					$temp_parent_id = 0;
				}

				$parent_id = $temp_parent_id;

				$parent_url_slug .= "/" . $slug;

				if ($limiter--<1) {
					break;
				}
			}
		}

		/* Test if Product Category URL Slug is match with the specified product category URL format */
		if ($parent_url_slug . "/" . $pc_slug != $output['new_url']) {
			$_p = trim($url_system_info['current_url_info']['protocol'],'/');
			$_h = trim($url_system_info['current_url_info']['host'],'/');
			$_q = trim($url_system_info['current_url_info']['query'],'/');
			$_s = $url_system_info['current_url_info']['has_slash'] ? "/" : '';
			$_u = $parent_url_slug . "/" . $pc_slug;
			$_l = $url_system_info['language']['is_default'] ? "" : $url_system_info['language']['slug_default'];

			$_q = $_q != "" ? "?{$_q}" : "";
			$_u = $_u != "" ? "/{$_u}" : "";
			$_u = $_u != "" ? "/{$_l}" : "";
			$contruct_url = "{$_p}://{$_h}{$_l}{$_u}{$_s}{$_q}";

			// redirect( $contruct_url );
			$output['header'] = 301;
			$output['new_url'] = $parent_url_slug . "/" . $pc_slug;
		}

		if ($h404) {
			$output['header'] = '404';
		}

		return $output;
	}

	function cms_validate_post_url_2( $post = array(), $current_url = '' ){
		global $url;

		$post_url_format = get_system_option(array("option_name" => "post_url_format"));

		$slug  = $post->url_slug;

		if ($post_url_format == 'category-postname') {
			return $current_url == $this->cms_get_post_url_2 ( $post )['new_url'];
		}else if ($post_url_format == 'postname') {
			
		}

		return $slug == $current_url;


		return true; /*temporary suspend post format validation*/

		if (count($page_info) <= 0) {
			return false;
		}

		$post_url_format = get_system_option(array("option_name" => "post_url_format"));
		$post_url_info = cms_post_format_info( $post_url_format );
		$post_url_info_extracted = explode("/", trim($post_url_info['format'], '/'));
		$current_url_data = $url->url;
		$post_slug = $page_info->url_slug;

		$link_db = new Database();

		$post_categories_result = $link_db->select("SELECT * FROM `post_category` Left Join `posts_categories_relationship` On `post_category`.`id` = `posts_categories_relationship`.`category_id` Where `posts_categories_relationship`.`post_id` = '{$page_info->id}'");
		$post_category = array();
		foreach ($post_categories_result as $key => $value) {
			$post_category[] = $value->url_slug;
		}

    //testing if the FORMAT and current url have the same array size
		$valid_format = true;
		if (count($post_url_info_extracted) == count($current_url_data) ) {
			for ($i=0; $i < count($post_url_info_extracted); $i++) { 
				$value_type = $post_url_info_extracted[$i];

				if ($value_type == '[post-category]') {
					if ( !in_array($current_url_data[$i], $post_category) ) {
						$valid_format = false;
					}
				}elseif($value_type == '[post-name]'){
					if ($current_url_data[$i] != $post_slug) {
						$valid_format = false;
					}
				}else{

				}
			}
		}else{
			$valid_format = false;
		}

		return $valid_format;
	}
	function cms_get_post_url_2 ( $post = array() ){
		$output = array(
			'new_url' => "",
			'header' => "301",
			);

		$post_url_format = $this->system_option('post_url_format');

		$output['new_url']  = $post->url_slug;

		if ($post_url_format == 'category-postname') {
			/* future code here */
			$pcr = $this->uc_db->select("SELECT * FROM `posts_categories_relationship` Where `post_id` = '{$post->id}' and `category_id` <> 0");

			$parent_slug = array();

			if (count($pcr)) {
				$categories = array();
				foreach ($this->uc_db->select("SELECT * FROM `post_category`") as $key => $value) {
					$categories[$value->id] = $value;
				}

				foreach ($pcr as $key => $value) {
					$p_temp = [];
					$cat_id = $value->category_id;

					$ctr = 0;
					while ($cat_id > 0 ) {
						if (isset($categories[$cat_id])) {
							array_unshift($p_temp, $categories[$cat_id]->url_slug);
							$cat_id = $categories[$cat_id]->category_parent;
						}else{
							$cat_id = 0;
						}

						if ($ctr++ > 20) {
							break;
						}
					}
					$parent_slug[] = implode('/', $p_temp);
				}
			}else{
				$parent_slug[] = 'uncategorized';
			}

			if (count($parent_slug) > 0) {
				$output['new_url'] = $parent_slug[0] . "/" . $output['new_url'];
			}
		}

		return $output;
	}


	public function cms_get_homepage_slug(){
		$db = new Database();
		$homepage_info = $db->select("Select * From `cms_posts` Where `id` = '". get_system_option('homepage') ."' and `post_type` = 'page'");
		return isset($homepage_info[0]) ? $homepage_info[0]->url_slug : "";
	}
	public function cms_get_blog_slug(){
		$db = new Database();
		$blog = $db->select("Select * From `cms_posts` Where `id` = '". get_system_option('blog_page') ."' and `post_type` = 'page'");
		return isset($blog[0]) ? $blog[0]->url_slug : "";
	}

	public function uc_get_final_url( $current_url = "" ){
		$url = $this->uc_get_url_validity_info_2($current_url);
		return $url['final_url'];
	}
	public function homepage_slug_2(){
		$homepage_id = $this->system_option('homepage');
		// $sql = "Select * From `cms_posts` Where `id` = '{$homepage_id}' and `post_type` = 'page'";
		$sql = "select ifnull(ct.url_slug, c.url_slug) url_slug From ( select cms_posts.*, c2.meta language from cms_posts, ( Select id, meta From cms_items i where type = 'cms-language' Union( Select id, meta From ( Select id, meta From `cms_items` Where `type` = 'cms-language-default' Union all( Select 0 id, 'en' meta) Order By `id` desc Limit 1 ) `t4` ) Order By `id`) c2 Where cms_posts.id = {$homepage_id} ) c Left JOIN cms_posts_translate ct on ct.post_id = c.id and ct.language = c.language Where c.language = '{$this->current_language}' and ifnull(ct.post_type, c.post_type) = 'page'"; /*Modified sql*/

		$homepage_info = $this->uc_db->select( $sql );
		return count($homepage_info) ? $homepage_info[0]->url_slug : "";
	}

	public function load_cached_categories(){
		$db = new Database();
		$current_post_categories = $db->select("Select pc.*,ct.meta,ct.language From post_category pc LEFT JOIN cms_translation ct On pc.id = ct.guid and ct.type = 'post-category' and pc.status = 'active'");
		$_temp = array();

		foreach ($current_post_categories as $key => $value) {
			$_temp[$this->reserved_language][$value->id] = $value;
			if (isset($value->language) && $value->language != '') {
				$_temp[$value->language][$value->id] = $value;
			}
		}

		return $_temp;
	}
	public function load_cached_product_categories(){
		$db = new Database();
		$cached_product_categories = $db->select("Select pc.*,ct.meta,ct.language From product_categories pc LEFT JOIN cms_translation ct On pc.id = ct.guid and ct.type = 'product-category'");

		$_temp = array();

		foreach ($cached_product_categories as $key => $value) {
			$_temp[$this->reserved_language][$value->id] = clone $value;
			$_temp[$this->reserved_language][$value->id]->meta = "";
			$_temp[$this->reserved_language][$value->id]->language = $this->reserved_language;

			if ($value->language != '' && $value->language != $this->reserved_language) {
				if (!isset($_temp[$value->language])) {
					$_temp[$value->language] = array();
				}
				$_temp[$value->language][$value->id] = $value;
			}
		}

		return $_temp;
	}


	function get_parent_slug($post_category_parent_id, $language){
		if (!isset($language)) {
			$language = $this->reserved_language;
		}

		if ($post_category_parent_id==0) {
			return "";
		}

		$post_category = $this->cached_categories[$language][$post_category_parent_id];
		$_m = json_decode($post_category->meta);
		$parent = "/" . (isset($_m->url_slug) && $language != $this->reserved_language ? $_m->url_slug : $post_category->url_slug);

		while ($post_category->category_parent != 0) {
			if (!isset($this->cached_categories[$language][$post_category->category_parent])) {
				break;
			}
			$temp = $this->cached_categories[$language][$post_category->category_parent]; 

			if (isset($this->cached_categories[$language][$post_category->category_parent])) {
				$temp = $this->cached_categories[$language][$post_category->category_parent]; 
				$_m = json_decode($temp->meta);
				$temp->url_slug = isset($_m->url_slug) && $language != $this->reserved_language ? $_m->url_slug : $temp->url_slug;
			}

			$post_category = $temp;
			$parent = "/{$post_category->url_slug}{$parent}";
		}

		return $parent;
	}
}