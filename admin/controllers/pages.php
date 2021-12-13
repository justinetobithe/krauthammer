<?php
class Pages extends Controller{
	function __construct(){
		parent::__construct();
		Session::handleLogin();
	}

	function index(){
		$this->view->set('js_files',array("pages-list"));
		$this->view->render('header');
		$this->view->render('pages/index');
		$this->view->render('footer');
	}
	function add(){
		set_module_sub_title("Add");
		$pages = $this->model->getPages();
		$templates = $this->load_templates();

		$this->view->set('templates',$templates);
		$this->view->set('pages', $pages);
		$this->view->set('language',$this->get_languages());
		$this->view->set('authors',$this->get_authors());
		$this->view->set('js_files',array("pages-gallery", "pages-add", "pages-custom-fields"));

		$this->view->render('header');
		$this->view->render('pages/add');
		$this->view->render('footer');
	}
	function edit($page_id = ""){
		set_module_sub_title("Edit");
		if($page_id == '' || $page_id < 0 ||  !ctype_digit($page_id)){
			header('location:'.URL.'pages/');
		}
		else{
			$post = $this->model->getPostByID($page_id);

			if($post == null){
				header('location:'.URL.'pages/');
			}
			else{
				$pages = $this->model->getPages($page_id);
				$templates = $this->load_templates();
				$this->view->set('templates',$templates);
				$this->view->set('post',$post);
				$this->view->set('language',$this->get_languages());
				$this->view->set('authors',$this->get_authors());
				$this->view->set('js_files',array("pages-gallery", "pages-edit", "pages-custom-fields"));

				$this->view->set('pages', $pages);
				$this->view->render('header');
				$this->view->render('pages/edit');
				$this->view->render('footer');
			}
		}
	}
	function __other($url=""){
		$categories = new loader();

		if($url[1] == 'categories' && empty($url[2])){
			$categories->load_controller('post-categories','PostCategories','manage');
		}
		else if($url[1] == 'categories' && $url[2] == 'add'){
			$categories->load_controller_method('post-categories','PostCategories','add','');
		}
		else if($url[1] == 'categories' && $url[2] == 'edit' && !empty($url[3])){
			if($url[3] > 0 && is_numeric($url[3]))
				$categories->load_controller_method('post-categories','PostCategories','edit',$url[3]);
			else
				$categories->load_error();
		}
		else if($url[1] == 'categories' && $url[2] == 'sorting'){
			$categories->load_controller_method('post-categories','PostCategories','sort');
		}
		else
			$categories->load_error();
	}

	function process_album(){
		if (isPost('action')) {
			if (post('action') == 'save') {
				$album_data = post('album_data');
				$page_id = isPost('page_id') ? post('page_id') : 0;
				$output = array();

				$this->model->db->table = 'album';
				$order_counter = 0;
				foreach ($album_data as $key => $value) {
					$data = array();
					$data["id"] = isset($value['album_id']) && $value['album_id'] != "" ? $value['album_id'] : "";
					$data["album_name"] = isset($value['album_label']) ? $value['album_label'] : "";
					$data["description"] = isset($value['description']) ? $value['description'] : "";
					$data["guid"] = $page_id;
					$data["sort_order"] = ++$order_counter;
					$data["type"] = "page-album";
					$data["meta"] = json_encode(array('featured'=>$value['album_featured_image']));

					$this->model->db->data = $data;

					$result = 0;
					if (isset($value['album_id']) && $value['album_id'] != '0'&& $value['album_id'] != '') {
						$result = $value['album_id'];
						$this->model->db->update();
					}else{
						unset($data["id"]);
						$this->model->db->data = $data;
						$result = $this->model->db->insertGetID();
					}

					$output[] = array(
						'return_id' => $value['album_return_id'],
						'new_id' => $result,
						);
				}

				echo json_encode($output);
			}else if (post('action') == 'save-photo') {
				$json_data = json_decode(post('data'));

				$data = array(
					"id" => $json_data->id,
					"name" => $json_data->name,
					"description" => $json_data->description,
					);

				$this->model->db->table = 'album_photos';
				$this->model->db->data = $data;
				$result = $this->model->db->update();

				echo json_encode(array(
					'success'=> $result?true:false, 
					'message'=>$result ? "Successfully Update Photo Info" : "Unable to Update Photo Info",
					'data'=>$data,
					));
			}else if (post('action') == 'load-photo-info') {
				$photo_id = post('photo_id');

				$photo = $this->model->db->select("Select * From `album_photos` Where `id` = '{$photo_id}'");
				$photo = count($photo) ? $photo[0] : array();

				echo json_encode($photo);
			}else if (post('action') == 'load') {
				$page_id = isPost('page_id') ? post('page_id') : "";
				$output = array();

				if ($page_id != "") {
					$sql = "SELECT `album`.`id` `album_id`, `album_photos`.`id` `photo_id`, `album_name`, `guid`, `type`, `sort_order`, `name`, `url`, `album_photos`.`description` `photo_description`, `album`.`meta` `album_meta` FROM `album` LEFT JOIN (Select * From `album_photos` Where `status` = 'active') `album_photos` ON `album`.`id` = `album_photos`.`album_id` Where `album`.`status`= 'active' and `guid`= '{$page_id}' and `type` = 'page-album' Order By `sort_order` ASC, `album`.`id` ASC, `album_photos`.`id` ASC";
					$photos = $this->model->db->select( $sql );

					foreach ($photos as $key => $value) {
						$f = json_decode($value->album_meta);
						$value->featured_image = isset($f->featured) ? ($f->featured==$value->photo_id) : false;
					}
					$output = $photos;
				}

				echo json_encode(array("data"=> $output));
			}else if (post('action') == 'delete-album') {
				$data = isPost('data') ? post('data') : array('id' => 0, 'token' => "");
				$output = array(
					'success'=>false,
					'message'=>"Authentication Failed.",
					);

                // $verifyToken = md5('unique_salt' . $_SESSION['upload_timestamp']);
				$verifyToken = $data['token'];
				if ($data['token'] == $verifyToken) {
					$data = array(
						'id' => $data['id'],
						'status' => "deleted",
						);
					$this->model->db->table = "album";
					$this->model->db->data = $data;
					$delete_result = $this->model->db->update();

					$this->model->db->query("Update `album_photos` Set `status` = 'deleted' Where `album_id`='{$data['id']}'");

					if ($delete_result) {
						$output['message'] = "Album was deleted";
						$output['success'] = true;
					}else{
						$output['message'] = "Unable to delete album.";
					}
				}

				echo json_encode($output);
			}else if (post('action') == 'delete-photo') {
				$data = isPost('data') ? post('data') : array('id' => 0, 'token' => "");
				$output = array(
					'success'=>false,
					'message'=>"Authentication Failed.",
					);

				$verifyToken = md5('unique_salt' . $_SESSION['upload_timestamp']);
				$verifyToken = $data['token'];
				if ($data['token'] == $verifyToken) {
					$data = array(
						'id' => $data['id'],
						'status' => "deleted",
						);
					$this->model->db->table = "album_photos";
					$this->model->db->data = $data;
					$delete_result = $this->model->db->update();

					if ($delete_result) {
						$output['message'] = "Photo was deleted";
						$output['success'] = true;
					}else{
						$output['message'] = "Unable to delete.";
					}
				}

				echo json_encode($output);
			}
		}
	}
	function upload(){
		$targetFolder = '/images/gallery';

		$verifyToken = md5('unique_salt' . $_SESSION['upload_timestamp']);

		if (!empty($_FILES)) {

			$tempFile = $_FILES['file']['tmp_name'];
			$targetPath = rtrim(FRONTEND_ROOT,'/') . $targetFolder;
			$image_url = rtrim(FRONTEND_URL,'/') . $targetFolder;

			if (!is_dir($targetPath)) {
				mkdir($targetPath);
			}

			$fileTypes = array('jpg','jpeg','gif','png');
			$fileParts = pathinfo($_FILES['file']['name']);

			if (in_array(strtolower($fileParts['extension']),$fileTypes)) {
				$new_file_name = sha1($_FILES['file']['name'] . $_SESSION['upload_timestamp']) . "." . $fileParts['extension'];
				$targetFile = rtrim($targetPath,'/') . '/' . $new_file_name;
				$urlFile = rtrim($image_url,'/') . '/' . $new_file_name;
				if (!is_valid_image_type($_FILES['file'])) {
					echo 'Invalid file type.'; exit();
				}
				move_uploaded_file($tempFile,$targetFile);

				$album_id = isset($_POST['album_id']) ? $_POST['album_id'] : 0;

				$this->model->db->table = 'album_photos';
				$this->model->db->data = array(
					"name" => $new_file_name, 
					"description" => "", 
					"url" => $urlFile,
					"album_id" => $album_id,
					"meta" => "",
					"status" => "active",
					);
				$result_id = $this->model->db->insertGetID();

				$album = $this->model->db->select("Select * From `album` Where `id` = '{$album_id}'");
				if (count($album)) {
					$album = $album[0];
					$f = json_decode($album->meta);
					if ($album->meta == "" || !isset($f->featured) || $f->featured == 0 ) {
						$this->model->db->table = 'album';
						$this->model->db->data = array("id"=>$album_id, "id"=>$album_id, "meta"=>json_encode(array("featured"=>$result_id)));
						$this->model->db->update();
					}
				}

				echo json_encode(array('id'=>$result_id, 'url' => $urlFile));
			} else {
				echo 'Invalid file type.';
			}
		}else{
			echo 'Embty File.';
		}
	}
	function load_detail(){
		if (isPost('page_id')) {
			$page_id = post('page_id');
			$page_lang = post('language');

			$page = $this->model->getPostByID($page_id, $page_lang);

			foreach ($page as $key => $value) {
				if (is_int($key)) unset($page[$key]);
			}

			header_json(); echo json_encode($page);
		}
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
	function addPage(){
		if($_POST){
			$data = array(
				'title' => $_POST['title'],
				'post_content' => $_POST['content'],
				'post_status' => 'active',
				'post_type' => 'page',
				'seo_title' => post('seo_title'),
				'seo_description' => post('seo_description'),
				'seo_no_index' => post('seo_no_index'),
				'seo_canonical_url' => post('seo_canonical_url'),
				'page_template' => post('page_template'),
				'url_slug' => post('url_slug'),
				'parent_id' => post('parent_id'),
				'status' => post('status'),
				'link' => post('link'),
				'post_author' => isPost('author') ? post('author') : 0,
				'post_date' => '',
				'post_excerpt' => '',
				'blog_page_categories' => explode(",", $_POST['page_blog_categories']),
				'meta_data' => isPost('custom_fields') ? post('custom_fields') : json_encode(array()),
				'language' => isPost('language') ? post('language') : 'en',
				);


			if($data['seo_title'] == '') $data['seo_title'] = $data['title'];

			echo json_encode($this->model->addPage($data));
		}
		else
			header('location:'.URL.'pages/');
	}
	function updatePage(){
		if($_POST){
			$data = array(
				"id" =>  $_POST['id'],
				"title" => $_POST['title'],
				"post_content" => $_POST['content'],
				"post_type" => 'page',
				"post_author" => isset($_POST['author']) ? $_POST['author'] : 0,
				"post_date" => "",
				"post_excerpt" => "",
				"seo_title" => post('seo_title'),
				"seo_description" => post('seo_description'),
				"seo_no_index" => post('seo_no_index'),
				"seo_no_index_search_engine" => isPost('seo_no_index_search_engine') ? 1 : 0,
				"page_template" => post('page_template'),
				"url_slug" => post('url_slug'),
				"parent_id" => post('parent_id'),
				"status" => post('status'),
				"link" => post('link'),
				"seo_canonical_url" => post('seo_canonical_url'),
				"meta_data" => isPost('custom_fields') ? post('custom_fields') : json_encode(array()),
				"language" => isPost('language') ? post('language') : 'en',
				);

			if($data['seo_title'] == '')
				$data['seo_title'] = $data['title'];

			$blog_page_categories = explode(",", $_POST['page_blog_categories']);
			$this->model->updatePageBlogCategories($_POST['id'], $blog_page_categories);

			/*UPDATING PAGE RETURN 0 IF NOT UPDATE SUCCESSFULLY, RETURN 1 IF SUCCESSFULL*/
			echo json_encode($this->model->updatePage($data));
		}
		else
			header('location:'.URL.'pages/');
	}
	function get(){
		if(hasPost('action','getPages')){
			echo json_encode($this->model->getPages());
		}
		else
			header('location:'.URL.'pages/');
	}
	function get_archiveds(){
		if(hasPost('action','get')){
			echo json_encode($this->model->get_archiveds(post('id')));
		}
		else
			header('location:'.URL.'pages/');
	}
	function deletePage(){
		if(hasPost('action', 'delete')){
			$id = post('id');
			echo json_encode($this->model->deletePage($id));
		}
		else
			header('location:'.URL.'pages/');
	}
	function load_templates(){
		$active_themes = get_system_option("frontend_theme") ? get_system_option("frontend_theme") : ACTIVE_THEME;
		$active_themes = FRONTEND_ROOT . "/views/themes/{$active_themes}/";
		$files = scandir($active_themes);
		$templates = array();
		$page_prefix = "page";
		$system_type = get_system_option("system_type");

		foreach ($files as $key => $file) {
			if (strpos($file,'.php') && (substr($file, 0, strlen($page_prefix)) == $page_prefix)) {
				if( strpos(file_get_contents($active_themes.$file),'Template Name') !== false) {
					$template = array();
					$lines = file($active_themes.$file);
					$name = explode(': ', $lines[2]);
					$template['name'] = $name[1];
					$template['value'] = substr_replace($file ,"",-4);

					if( $template['name']== 'Default Page Template' || $file == "{$page_prefix}.php" )
						array_unshift($templates, $template);
					elseif($template['name']!= '')
						$templates[] = $template;
				}elseif($file == "{$page_prefix}.php"){
					array_unshift($templates, array("name"=>"Default Page Template", "value"=>"page"));
				}
			}
		}

		if (!count($templates)) {
			array_unshift($templates, array("name"=>"Default Page Template", "value"=>"index"));
		}

		/*GET SYSTEM PLUGIN CONFIGS*/
		$sp_dir = FRONTEND_ROOT . "/system_plugins";
		// $PLUGIN_CONFIGS = array();
		if (is_dir($sp_dir)) {
			$plugins = scandir($sp_dir);
			$plugins = array_diff($plugins, array('.', '..'));

			foreach ($plugins as $key => $value) {
				if (is_file($sp_dir . "/{$value}/plugin-config.php")) {
					// include $sp_dir . "/{$value}/plugin-config.php";
					$system_plugin_config_content = file_get_contents($sp_dir . "/{$value}/plugin-config.php");
					preg_match_all("/(.*)\s*:\s*(.*)/", $system_plugin_config_content, $system_plugin_menus);

					$items = array();

					/*Getting Items*/
					foreach ($system_plugin_menus[0] as $key => $value) {
			    	if (isset($system_plugin_menus[1][$key])) {
			    		if ($system_plugin_menus[1][$key] == 'Menu' || 
			    			$system_plugin_menus[1][$key] == 'Sub'|| 
			    			$system_plugin_menus[1][$key] == 'Templates') {
			    			if (!isset($items[$system_plugin_menus[1][$key]])) {
			    				$items[trim($system_plugin_menus[1][$key])] = array();
			    			}
			    			$items[trim($system_plugin_menus[1][$key])][] = trim($system_plugin_menus[2][$key]);
			    		}else{
			    			$items[trim($system_plugin_menus[1][$key])] = isset($system_plugin_menus[2][$key]) ? trim($system_plugin_menus[2][$key]) : '';
			    		}
			    	}
			    }

			    if (isset($items['Type']) && $items['Type']!=$system_type) {
			    	continue;
			    }

			    if (isset($items['Templates'])) {
			    	foreach ($items['Templates'] as $key => $value) {
			    		$t = explode(',', $value);
			    		$templates[] = array(
			    			'name' => isset($t[0]) ? trim($t[0]) : '',
			    			'value' => isset($t[1]) ? trim($t[1]) : '',
			    			);
			    	}
			    }
			    continue;

					// if (isset($PLUGIN_CONFIG)) {
					// 	$PLUGIN_CONFIGS[$value]  = $PLUGIN_CONFIG;
					// }

					// if ($PLUGIN_CONFIG['system_requirement']['type'] != get_system_option("system_type")) {
					// 	continue;
					// }

					// if (isset($PLUGIN_CONFIG['system_requirement']['page']['templates'])) {
					// 	$t = $PLUGIN_CONFIG['system_requirement']['page']['templates'];

					// 	foreach ($t as $t_key => $t_value) {
					// 		$templates[] = $t_value;
					// 	}
					// }
				}
			}
		}

		return $templates;
	}
	function get_available_slug(){
		if(hasPost('action', 'get')){
			$site_url_info = get_site_url_info();
			$slug = trim(str_replace(' ', '', remove_accents(post('slug'))));
			$lang = post('lang');

			$current_page = $this->model->db->select("SELECT * From `cms_posts` WHERE `id` = '". post('page_id') ."' and `post_type` = 'page'");
			
			if (count($current_page)) {
				$current_page = $current_page[0];
				$current_page_slug = $current_page->url_slug; 

				if ($current_page_slug == $slug) {
					$output['slug'] = $current_page_slug;
				}else{
					$output = $this->model->get_available_slug($slug, $current_page->id, $lang);
				}
			}else{
				$output = $this->model->get_available_slug($slug, 0, $lang);
			}

			$language = $this->get_languages();
			$l = '';
			foreach ($language as $key => $value) {
				if (post('lang') == $value->slug) {
					$l = $value->selected != "selected" ? "/" . $value->slug : "";
				}
			}

			$s_s_temp = [];
			// $p_temp = post('parent_id');

			// while ($p_temp != 0) {
			// 	$r_temp = $this->model->db->select("Select * From `cms_posts` Where `id` = '{$p_temp}'");
			// 	if (count($r_temp)) {
			// 		$p_temp = $r_temp[0]->parent_id;
			// 		array_unshift($s_s_temp, $r_temp[0]->url_slug);
			// 	}else{
			// 		$p_temp = 0;
			// 	}
			// }

			$pages 	= $this->model->load_pages(post('lang'));
			if (isset($pages[post('parent_id')]) && post('page_id') != get_system_option('homepage')) {
				$_p = $pages[post('parent_id')];
				array_unshift($s_s_temp, $_p->trans_slug);
		  	$_temp = array();
		  	while ($_p->parent_id != 0) {
		  		if (!in_array($_p->id, $_temp)) {
		  			$_temp[] = $_p->id;
		  		}else{
		  			break;
		  		}
		  		$_p = $pages[$_p->parent_id];
					array_unshift($s_s_temp, $_p->trans_slug);
		  	}
			}

			$output['siteurl'] 			= rtrim($this->get_site_url_format(), "/");
			$output['parent_slug'] 	= trim(implode('/', $s_s_temp), "/");
			$output['trail_slash'] 	= $site_url_info['has_slash'] ? "/" : "";
			$output['is_home'] 			= post('page_id') == get_system_option('homepage');

			$l = $output['parent_slug'] != "" ? $l . "/" : $l;

			$output['parent_slug'] = trim(get_system_option('site_url'),'/') . $l .$output['parent_slug'];

			echo json_encode( $output );
		}
		else
			header('location:'.URL.'pages/');
	}
	function get_parents(){
		if(hasPost('action', 'get')){
			echo json_encode($this->model->get_parents(post('id')));
		}
		else
			header('location:'.URL.'pages/');
	}
	function get_blog_post_categories(){
		$id = isPost('id') ? post('id') : 0;
		$categories = $this->model->load_post_categories( $id ); /*post_category*/
		$categories_hierarchy = $this->getChildren($categories, 0);
		$categories_hierarchy_output = $this->arrangeNodes($categories_hierarchy['children']);

		$output = array();
		$output[] = array('name'=> '<span data-value="all">All Categories</span>', 'type' => 'item', "additionalParameters" => array("id" => -1,'item-selected' =>false));
		$output[] = array('name'=> '<span data-value="uncat">Uncategorized</span>', 'type' => 'item', "additionalParameters" => array("id" => 0,'item-selected' =>($categories[0]->select_status==='selected')));

		$selected_ctr = 0;
		$selected_enabled = 0;

		foreach ($categories_hierarchy_output as $key => $value) {
			$isSelected = $value->select_status == 'selected' ? true : false;
			$isEnable = $value->enable_status == 'disabled' ? 'disabled' : 'enabled';

			if ($isEnable == 'disabled') continue;
			$selected_enabled++;

			$name = '<span data-value="'. $value->id .'" data-enable="'. $isEnable .'">'. $value->indent . $value->category_name .'</span>';
			$output[] = array('name'=> $name, 'type' => 'item', "additionalParameters" => array("id" => $value->id,'item-selected' =>$isSelected ));

			if ($isSelected) $selected_ctr++;
		}
		if ($selected_ctr == $selected_enabled) { 
			$output[0]['additionalParameters']['item-selected'] = true; 
		}

		header_json(); echo json_encode($output);
	}
	function getChildren($categories=array(), $parent_id = 0, $detail = ""){
		$children = array();
		foreach ($categories as $key => $value) {
			if ($value->category_parent == $parent_id && $value->id != $parent_id) {
				$children[] = $this->getChildren($categories, $value->id, $value);
			}
		}

		return array( "detail" => $detail, "children"=> $children );
	}
	function arrangeNodes($categories = array(), $indent = ""){
		$joint_array = array();
		foreach ($categories as $key => $value) {
			$value['detail']->indent = $indent;
			$joint_array[] = $value['detail'];

			if (count($value['children'])) {
				$joint_array = array_merge($joint_array, $this->arrangeNodes($value['children'], $indent . "- "));
			}
		}

		return $joint_array;
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
		return $str;
	}
	function get_site_url_format(){
		$site_url_info = get_site_url_info();
		return $site_url_info['siteurl'];
	}

	function get_cf_values( $page = array(), $disable = false ){
		$meta_data = array();
		$cf = array();

		$meta_data = json_decode( $page['meta_data'] );

		/*$meta_data is an object containing the name of the field as its "key" and array of "values" of custom fields with similar name*/
		foreach ($meta_data as $key => $value) { /*get all fields defiled*/
			$ctr = 0;
			if (count($value) && (gettype($value) =='array' || gettype($value) =='object')) {
				$container = '<div class="custom-field"></div>';

				foreach ($value as $vkey => $vvalue) { /*get values of the specified field*/
					$field_btn_remove = '<a href="javscript:void(0)" class="btn btn-mini btn-danger custom-field-btn-remove"><i class="icon icon-trash"></i></a>';
					$field_name = '<div class="span6"><input type="text" class="input custom-field-name" value="'.$key.'"></div>';
					$field_value = '<div class="span6"><textarea type="text" class="input custom-field-value">'.( gettype($vvalue) != 'object' ? $vvalue:'').'</textarea> '.$field_btn_remove.'</div>';
					$cf[] = '<div class="row-fluid page-custom-fields" style="margin-bottom: 10px;">'.$field_name. $field_value.'</div>';
				}
			}
		}

		return $cf;
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