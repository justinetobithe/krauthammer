<?php
include ROOT . "libraries/plugins/sitemap/sitemap.php";

class Pages_Model extends Model{
	public $language_reserved;
	public $language_default;
	public $cached_pages;

	public function __construct(){
		parent::__construct();
		$this->db->table='cms_posts';
		$this->language_reserved = get_reserved_language();
		$this->language_default = get_default_language();

		$this->cached_pages = $this->load_pages($this->language_reserved);
	}

	public function addPage($d){
		/*$data['meta_data'] = $this->db->escape_string(preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['meta_data']));*/
		$language = $d['language'];
		$d['meta_data'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $d['meta_data']);

		$new_page_id = 0;

		$data = array(
			'post_author' 			=> $d['post_author'],
			'post_content' 			=> $d['post_content'],
			'post_title' 				=> $d['title'],
			'post_excerpt' 			=> $d['post_excerpt'],
			'post_status' 			=> $d['post_status'],
			'post_type' 				=> 'page',
			'url_slug' 					=> $d['url_slug'],
			'seo_title' 				=> $d['seo_title'],
			'seo_description' 	=> $d['seo_description'],
			'seo_no_index' 			=> $d['seo_no_index'],
			'page_template' 		=> $d['page_template'],
			// 'parent_id' => $this->get_number(intval($d['parent_id'])),
			'parent_id' 				=> intval($d['parent_id']),
			'status' 						=> $d['status'],
			'seo_canonical_url' => $d['seo_canonical_url'],
			'meta_data' 				=> $d['meta_data'],
			);

		if ($language==$this->language_reserved) {
			$this->db->data = $data;
			$new_page_id = $this->db->insertGetID();
		}else{
			/*copy data*/
			$data2 = array(
				'post_author' 			=> $d['post_author'],
				'post_content' 			=> $d['post_content'],
				'post_title' 				=> $d['title'],
				'post_excerpt' 			=> $d['post_excerpt'],
				'post_status' 			=> $d['post_status'],
				'post_type' 				=> 'page',
				'url_slug' 					=> $d['url_slug'],
				'seo_title' 				=> $d['seo_title'],
				'seo_description' 	=> $d['seo_description'],
				'seo_no_index' 			=> $d['seo_no_index'],
				'page_template' 		=> $d['page_template'],
				// 'parent_id' 			=> $this->get_number(intval($d['parent_id'])),
				'parent_id' 				=> intval($d['parent_id']),
				'status' 						=> $d['status'],
				'seo_canonical_url' => $d['seo_canonical_url'],
				'meta_data' 				=> $d['meta_data'],
				);

			/*remove content*/
			unset($data['post_content']);

			/*save into cms_posts table*/
			$this->db->data = $data;
			$new_page_id = $this->db->insertGetID();
			if ($new_page_id) {
				/*add additional column value for translated column*/
				$data2['post_id'] = $new_page_id;
				$data2['language'] = $language;
				unset($data2['blog_page_categories']);

				/*save into cms_posts_translate*/
				$this->db->table = 'cms_posts_translate';
				$this->db->data = $data2;
				$this->db->insertGetID();

				$new_link = $this->get_parents( $d['parent_id'], $language );
				$new_link .= $d['url_slug'] . "/";
				replace_url("", $new_link, 'cms_posts');
			}
		}

		// add_url($d['link'], 0, 'cms_posts');

		if ($new_page_id!=0) {
			$new_link = $this->get_parents( $d['parent_id'], $this->language_reserved );
			$new_link .= $d['url_slug'] . "/";
			replace_url("", $new_link, 'cms_posts');
		}

		$this->updatePageBlogCategories($new_page_id, $d['blog_page_categories']);

		$sitemap = new XMLSitemap();
		$sitemap->update();

		return $new_page_id;

		/*$qry = $this->db->query(" INSERT INTO ".$this->db->table. "(`post_author`, `post_date`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_type`, `url_slug`, `seo_title`,`seo_description`,`seo_no_index`,`page_template`, `parent_id`,`status`,`seo_canonical_url`,`meta_data`) 
			VALUES ('{$data['post_author']}','{$data['post_date']}','{$data['post_content']}','{$data['title']}','{$data['post_excerpt']}','{$data['post_status']}','{$data['post_type']}','{$data['url_slug']}','{$data['seo_title']}','{$data['seo_description']}','{$data['seo_no_index']}','{$data['page_template']}', '{$data['parent_id']}','{$data['status']}','{$data['seo_canonical_url']}','{$data['meta_data']}')");

		if($qry){
			$qry_1 = $this->db->query(" SELECT * FROM ".$this->db->table. " ORDER BY `id` DESC LIMIT 1");
			$row = $this->db->fetch($qry_1,"array");
			// add_url($link, $row['id'], 'cms_posts');
			add_url($data['link'], 0, 'cms_posts');
			$this->updatePageBlogCategories($row['id'], $data['blog_page_categories']);
			return $row['id'];
		}

		return "0";*/
	}

	public function updatePage($data){
		$this->db->table = "cms_posts";
		/*adding archived*/
		$this->add_archived($data['id']);
		$response = "0";

		// $data['post_content'] = $this->db->escape_string($data['post_content']);
		$data['post_content'] = $data['post_content'];
		// $data['meta_data'] 		= $this->db->escape_string(preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['meta_data']));
		$data['meta_data'] 		= preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['meta_data']);

		$current_post 	= $this->getPostByID( $data['id'], $data['language']==$this->language_reserved ? '' : $data['language'] );
		$old_link 			= $this->get_parents( $current_post['parent_id'], $data['language']) . $current_post['url_slug'] . "/";
		$new_link 			= $old_link;


		if ($data['language']==$this->language_reserved) {
			// $current_post 	= $this->getPostByID( $data['id'] );
			// $old_link 			= $this->get_parents( $current_post['parent_id'], $data['language']) . $current_post['url_slug'] . "/";
			// $current_url_id = 0;
			// $current_url 		= $this->db->select("SELECT * FROM `urls` WHERE `url` = '{$old_link}' ORDER BY `id` DESC Limit 1");
			// if (count($current_url)) {
				// $current_url 		= $current_url[0];
				// $current_url_id = $current_url->id;
			// }

			// $data['post_content'] = $this->db->escape_string($data['post_content']);
			// $data['meta_data'] 		= $this->db->escape_string(preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['meta_data']));
			
			// $qry = $this->db->query("UPDATE ".$this->db->table." 
			// 	SET `post_content`= '{$data['post_content']}',
			// 			`post_title`='{$data['title']}', 
			// 			`post_author`='{$data['post_author']}', 
			// 			`seo_title` = '{$data['seo_title']}', 
			// 			`seo_description` = '{$data['seo_description']}', 
			// 			`seo_no_index` = '{$data['seo_no_index']}', 
			// 			`page_template` = '{$data['page_template']}', 
			// 			`url_slug` = '{$data['url_slug']}', 
			// 			`parent_id` = '{$data['parent_id']}', 
			// 			`status` = '{$data['status']}', 
			// 			`seo_canonical_url` = '{$data['seo_canonical_url']}', 
			// 			`meta_data` = '{$data['meta_data']}' 
			// 	WHERE `id` = '{$data['id']}'");

			$this->db->table = 'cms_posts';
			$d = array(
				'id' 								=> $data['id'],
				'post_content' 			=> $data['post_content'],
				'post_title' 				=> $data['title'], 
				'post_author' 			=> $data['post_author'], 
				'seo_title' 				=> $data['seo_title'], 
				'seo_description' 	=> $data['seo_description'], 
				'seo_no_index' 			=> $data['seo_no_index'], 
				'page_template' 		=> $data['page_template'], 
				'url_slug' 					=> $data['url_slug'], 
				'parent_id' 				=> $data['parent_id'], 
				'status' 						=> $data['status'], 
				'seo_canonical_url' => $data['seo_canonical_url'], 
				'meta_data' 				=> $data['meta_data'] 
				);
			$this->db->data = $d;
			$qry = $this->db->update();

			$_home		= get_system_option('homepage');
			$_parent 	= $_home != $data['id'] ? $data['parent_id'] : 0;
			$_slug		= $_home != $data['id'] ? $data['url_slug'] . "/" : "";

			$new_link = $this->get_parents( $_parent, $data['language'] ) . $_slug;


			// replace_url($old_link, $new_link, 'cms_posts');

			// if ($old_link != $new_link) {
			// 	$this->updateChildUrls($data['id'], $old_link, $new_link,'cms_posts');
			// }

			$response = $qry ? "1" : "0";
		}else{
			// $current_post = $this->getPostByID( $data['id'], $data['language'] );
			// $current_url_id = 0;
			// $old_link = $this->get_parents( $current_post['parent_id'], $data['language'] ) . $current_post['url_slug'] . "/";

			// $data['meta_data'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data['meta_data']);

			$this->db->table = 'cms_posts';
			$d = array(
				"id" => $data['id'],
				"seo_no_index"	=> $data['seo_no_index'],
				"page_template"	=> $data['page_template'],
				"post_author"		=> $data['post_author'],
				"parent_id"			=> $data['parent_id'],
				"status"				=> $data['status'],
				"meta_data"			=> $data['meta_data'],
				);
			$this->db->data = $d;
			$this->db->update();

			$get_current_translation = $this->get_translated_post($data['id'], $data['language']);

			$d = array(
				"id" 								=> isset($get_current_translation->id) ? $get_current_translation->id : 0,
				"post_id" 					=> $data['id'],
				"language" 					=> $data['language'],
				"post_title"				=> $data['title'],
				"post_content"			=> $data['post_content'],
				"seo_title"					=> $data['seo_title'],
				"seo_description"		=> $data['seo_description'],
				"seo_no_index"			=> $data['seo_no_index'],
				"page_template"			=> $data['page_template'],
				"url_slug"					=> $data['url_slug'],
				"parent_id"					=> $data['parent_id'],
				"status"						=> $data['status'],
				"seo_canonical_url"	=> $data['seo_canonical_url'],
				"meta_data"					=> $data['meta_data'],
				"post_type"					=> 'page',
				"post_author"				=> $data['post_author'],
				);
			$this->db->table = "cms_posts_translate";

			$is_saved = false;
			if (count($get_current_translation) <= 0) {
				unset($d['id']);
				$this->db->data = $d;
				$is_saved = $this->db->insertGetID();
			}else{
				unset($d['post_id']);
				unset($d['language']);
				$this->db->data = $d;
				$is_saved = $this->db->update();
			}

			$_home		= get_system_option('homepage');
			$_parent 	= $_home != $data['id'] ? $data['parent_id'] : 0;
			$_slug		= $_home != $data['id'] ? $data['url_slug'] . "/" : "";

			$new_link = $this->get_parents( $_parent, $data['language'] ) . $_slug;

			// replace_url($old_link, $new_link, 'cms_posts');

			// if ($old_link != $new_link) {
			// 	$this->updateChildUrls($data['id'], $old_link, $new_link,'cms_posts');
			// }

			$response = $is_saved ? "1" : "0";
		}

		if ($current_post['parent_id'] != $data['parent_id']) {
			/* Change all translated pages */
			$sql = "SELECT c.id, c.url_slug, '0' trans_id, c.url_slug trans_slug, '{$this->language_reserved}' language 
							FROM `cms_posts` c 
							WHERE c.id = '{$current_post['id']}' 
							UNION
								SELECT c.id, c.url_slug, c2.id trans_id, c2.url_slug trans_slug, c2.language 
								FROM `cms_posts` c 
								INNER JOIN cms_posts_translate c2 on c.id = c2.post_id 
								WHERE c.id = '{$current_post['id']}'";

			$l = $this->db->select( $sql );

			replace_url($old_link, $new_link, 'cms_posts');

			foreach ($l as $key => $value) {
				if ($value->language == $data['language']) {
					continue;
				}
				$url1 = $this->get_parents( $current_post['parent_id'], $value->language) . $value->trans_slug . "/";
				$url2 = $this->get_parents( $data['parent_id'], $value->language) . $value->trans_slug . "/";
				replace_url($url1, $url2, 'cms_posts');
			}
		}else{
			replace_url($old_link, $new_link, 'cms_posts');
		}

		/* Updating Sitemap */
		$sitemap = new XMLSitemap();
		$sitemap->update();

		return $response;
	}

	public function updateChildUrls($id, $old_link, $new_link, $url_type = ''){
		$ulrs = $this->db->select("Select * From `urls` Where `url` Like '{$old_link}%' and `url` <> '{$old_link}' and `status` = 'active'");
		foreach ($ulrs as $key => $value) {
			$child_url = str_replace($old_link, "", $value->url);
			add_url($new_link . $child_url, $value->id, 'cms_posts');
			$this->db->query("UPDATE `urls` SET `status`= 'trashed' WHERE `id` = '{$value->id}'");
		}
	}

	public function updatePageBlogCategories($id, $blog_page_categories){
		$this->db->query("Delete From `posts_categories_relationship` Where `post_id` = '{$id}'");
		$this->db->table = "posts_categories_relationship";
		$pageBlogCategoryIds = array();
		foreach ($blog_page_categories as $key => $value) {
			$cat_id = $value != 'uncat' ? $this->get_number($value) : 0;
			$data = array(
				'post_id' 		=> $id,
				'category_id' => $cat_id,
				);
			$this->db->data = $data;
			$pageBlogCategoryIds[] = $this->db->insertGetID();
		}

		return $pageBlogCategoryIds;
	}

	function add_archived($id){
		$qry = $this->db->query("INSERT INTO cms_posts_archived (`post_content`, `post_title`,`post_excerpt`,`status`,`url_slug`,`page_template`,`seo_title`,`seo_description`,`seo_no_index`,`parent_id`,`post_id`) SELECT `post_content`,`post_title`,`post_excerpt`,`status`,`url_slug`,`page_template`,`seo_title`,`seo_description`,`seo_no_index`,`parent_id`,`id` FROM `cms_posts` WHERE `cms_posts`.`id` = '$id'");
	}
	function get_archiveds($id){
		$qry = $this->db->query("SELECT * FROM `cms_posts_archived` WHERE `post_id` = '$id' ORDER BY `id` ASC");
		$rows = array();
		while($row = $this->db->fetch($qry, 'array'))         
			$rows['archived'][] = $row;

		if(!empty($rows['archived']))
			$rows['default'][] = $this->db->fetch($this->db->query("SELECT * FROM `cms_posts` WHERE `id` = $id"), 'array');


		return $rows;
	}
	public function getPostByID($page_id, $page_lang = ''){
		$page_lang = $page_lang != '' ? $page_lang : $this->language_reserved;
		$qry = $this->db->query(" SELECT * FROM `cms_posts` WHERE `id` = '$page_id' ");
		$page = $this->db->fetch($qry,"array");
		$translated = 'main';


		$uc = new UC();
		$uc_url = trim(get_system_option('site_url'),'/').trim($page_lang,'/'). '/' . trim($page['url_slug'],'/') . '/';
		$uc_info = $uc->uc_get_current_url_settings($uc_url);

		/*$uc_info['language']['slug_default'];*/

		if ($page_lang != $this->language_reserved) {
			/*if ($page_lang != $uc_info['language']['slug_default']) {*/
			$qry = $this->db->query(" SELECT * FROM `cms_posts_translate` WHERE `post_id` = '{$page_id}' and `language` = '{$page_lang}' ");
			$tran = $this->db->fetch($qry,"array");

			if (count($tran) > 0) {
				$translated = 'translated';
				unset($tran['post_id']);
				unset($tran['language']);
				$page['post_title'] = $tran['post_title'] != '' ? $tran['post_title'] : $page['post_title'];
				$page['post_content'] = $tran['post_content'];
				$page['url_slug'] = $tran['url_slug'];
				$page['seo_title'] = $tran['seo_title'];
				$page['seo_description'] = $tran['seo_description'];
				$page['seo_canonical_url'] = $tran['seo_canonical_url'];
			}else{
				$translated = 'not translated';
				$page['post_content'] = '';
			}
		}

		if ($page_lang == $this->language_reserved) {
			$translated = 'default';
		}elseif ($page_lang == $uc_info['language']['slug_default']) {
			$translated = 'main';
		}

		$page['translated'] = $translated;


		$_SESSION['archived'] = $page;

		return $page;
	}
	public function getPages($exception = 0){
		$page_condition = $exception != 0 && $exception != "" ? " and `id` <> '{$exception}'" : "";
		$qry = $this->db->query("SELECT * FROM ".$this->db->table. " WHERE `post_status` <> 'trashed' AND `post_type` = 'page' {$page_condition} ORDER BY `id` DESC ");
		$rows = array();

		if($qry){
			while($row = $this->db->fetch($qry,"array")){
				$row['admin_editor_link'] = URL . "pages/edit/" . $row['id'];
				$rows[] = $row;
			}
		}

		return $rows;
	}
	public function deletePage($id){
		$current_post = $this->db->select("SELECT * FROM cms_posts WHERE id = '{$id}'");
    if (count($current_post)) {
      $current_post = $current_post[0];
      $qry = $this->db->query("UPDATE ".$this->db->table." SET `post_status` = 'trashed', old_slug='{$current_post->url_slug}', url_slug = '' WHERE `id` = '$id' ");
      
      foreach ($this->db->select("SELECT * FROM `cms_posts_translate` WHERE post_id = '{$id}'") as $key => $value) {
        $this->db->query("UPDATE `cms_posts_translate` SET `post_status`='trashed', old_slug='{$value->url_slug}', url_slug = '' WHERE `id` = '{$value->id}'");
      }

      $sitemap = new XMLSitemap();
      $sitemap->update();

      if($qry){
        return '1';
      }
    }

    return '0';
	}

	public function load_post_categories($post_id=0){
		$selected_post = array();

		if ($post_id !='0') {
			$result = $this->db->select("SELECT * FROM `posts_categories_relationship` Where `post_id` = '{$post_id}'");
			foreach ($result as $key => $value) {
				$selected_post[] = "'$value->category_id'";
			}
		}
		$default_category = "Select 0 `id`, 'Uncategorized' `category_name`, '' `category_description`,  0 `category_parent`, 'uncategorized' `url_slug`, 0 `sort_order`, 'post' `categories_type`, 'active' `status`";

		$sql = "SELECT `t1`.*, IF(`t1`.`id`, 'not selected', 'not selected') `select_status`, IFNULL(`t2`.`post_id`,'disabled') `enable_status` FROM (Select * From `post_category` Union All ({$default_category})) `t1` Left Join `posts_categories_relationship` `t2` On  `t1`.`id` = `t2`.`category_id` Group By `t1`.`id`  Order By `sort_order`, `id`";

		if (count($selected_post) > 0) {
			$selected_post_str = implode(",", $selected_post);
			$sql = "SELECT `t1`.*, IF(`t1`.`id` In ({$selected_post_str}), 'selected', 'not selected') `select_status`, IFNULL(`t2`.`post_id`,'disabled') `enable_status`  FROM (Select * From `post_category` Union All ({$default_category})) `t1` Left Join `posts_categories_relationship` `t2` On  `t1`.`id` = `t2`.`category_id` Group By `t1`.`id` Order By `sort_order`, `id`";
		}

		$qry = $this->db->select($sql);
		return $qry;
	}

	function get_available_slug($slug, $page_id =0, $language){
		$slug = remove_accents(preg_replace("/\s+/", "-", $slug)); // GET URL and remove accented characters
		$s = $slug;
		if ($page_id != 0) {
			$sql = "SELECT * FROM (
								SELECT `id`, `url_slug` 
								FROM `cms_posts` 
								UNION (
									SELECT `post_id` `id`, `url_slug` 
									FROM `cms_posts_translate`
								)
							) `cms_post` 
							WHERE `url_slug` = '{$s}' and `url_slug`<>'' and `id` <> '{$page_id}'";

			$page = $this->db->select($sql); // query from cms_posts and cms_posts_translate
			$ctr = 1;
			while (count($page) > 0) {
				$s = $slug . "-" . $ctr++;
				// $page = $page = $this->db->select("SELECT * FROM `cms_posts` WHERE `url_slug` = '{$s}' and `id` <> '{$post_id}'");
				$sql = "SELECT * FROM (
									SELECT `id`, `url_slug` 
									FROM `cms_posts` 
									UNION (
										SELECT `post_id` `id`, `url_slug` 
										FROM `cms_posts_translate`
									)
								) `cms_post` 
								WHERE `url_slug` = '{$s}' and `url_slug`<>'' and `id` <> '{$page_id}'";
				$page = $this->db->select($sql); // query from cms_posts and cms_posts_translate
			}
		}else{
			$sql = "SELECT * FROM (
								SELECT `id`, `url_slug` 
								FROM `cms_posts` 
								UNION (
									SELECT `post_id` `id`, `url_slug` 
									FROM `cms_posts_translate`
								)
							) `cms_post` 
							WHERE `url_slug` = '{$s}' and `url_slug`<>''; ";

			$page = $this->db->select($sql); // query from cms_posts and cms_posts_translate
			$ctr = 1;
			while (count($page) > 0) {
				$s = $slug . "-" . $ctr++;
				$sql = "SELECT * FROM (
									SELECT `id`, `url_slug` 
									FROM `cms_posts` 
									UNION (
										SELECT `post_id` `id`, `url_slug` 
										FROM `cms_posts_translate`
									)
								) `cms_post` 
								WHERE `url_slug` = '{$s}' and `url_slug`<>''; ";
				// $page = $page = $this->db->select("SELECT * FROM `cms_posts` WHERE `url_slug` = '{$s}' and `id` <> '{$post_id}'");
				$page = $this->db->select($sql); // query from cms_posts and cms_posts_translate
			}
		}
		
		$rows['slug'] = rtrim($s, "/");

		return $rows;

		// old code
		// get url slug by using the get_url function
		$rows = array();
		$rows['slug'] = rtrim(get_url($slug,'cms_posts'), "/");

		return $rows;
	}

	function get_parents($id, $language=""){
		/* New Query */
		$parent = "";
		$pages 	= $this->load_pages($language);

		if (isset($pages[$id])) {
			$_p = $pages[$id];
			$parent = "/{$_p->trans_slug}";
	  	$_temp = array();
	  	while ($_p->parent_id != 0) {
	  		if (!in_array($_p->id, $_temp)) {
	  			$_temp[] = $_p->id;
	  		}else{
	  			break;
	  		}
	  		$_p = $pages[$_p->parent_id];
				$parent = "/{$_p->trans_slug}{$parent}";
	  	}
		}

  	$site_url_info = get_site_url_info();
		return trim($site_url_info['siteurl'], '/') . ($language!="" && $language!=$this->language_default?"/{$language}":"") . (isset($parent)?$parent . "/" : '');

		/* OLD Query */
		$parent = '/';
		if($id !=0){
			$qry = $this->db->query("SELECT c.url_slug, c.parent_id, c2.url_slug translated_slug FROM cms_posts c Left Join cms_posts_translate c2 on c.id = c2.post_id and language = '{$language}' WHERE c.id = '{$id}' ");
			$row = $this->db->fetch($qry,'array');
			$parent = '/'.($language!=""&&$language!=$this->language_default?$row['translated_slug'] : $row['url_slug']).'/';
			$parent_id = $row['parent_id'];

			while($parent_id != 0){
				$qryx = $this->db->query("SELECT `url_slug`,`parent_id` FROM `cms_posts` WHERE `id` = '$parent_id'");
				$rowx = $this->db->fetch($qryx,'array');
				$parent = '/'.$rowx['url_slug'].$parent;
				$parent_id = $rowx['parent_id'];
			}
		}
		$site_url_info = get_site_url_info();
		return trim($site_url_info['siteurl'], '/') . ($language!="" && $language!=$this->language_default?"/{$language}":"") . $parent;
	}

	function get_translated_post($post_id=0, $language = ''){
		/*Get the selected post/page*/
		$language = $language != '' ? $language : $this->language_reserved;
		$selected_post = array();
		if ($language==$this->language_reserved) {
			$selected_post = $this->db->select("Select * From `cms_posts` Where `id` = '{$post_id}'");
			$selected_post = count($selected_post) ? $selected_post[0] : array();
		}else{
			$selected_post = $this->db->select("Select * From `cms_posts_translate` Where `post_id` = '{$post_id}' and `language` = '{$language}'");
			$selected_post = count($selected_post) ? $selected_post[0] : array();
			unset($selected_post->post_id);
			unset($selected_post->language);
		}
		return $selected_post;
	}

	function get_number($d){
		$d = trim($d);
		$t = gettype((int)$d);
		return $t == 'integer' || $d == 'double' ? $d : 0;
	}

	function load_pages($language){
		if (!isset($language)) {
			$language = cms_get_language_default();
		}
  	$temp = array();

  	$sql = "SELECT c.id, c.url_slug, c.parent_id, IFNULL(cp.url_slug, c.url_slug) trans_slug
						FROM cms_posts c 
						LEFT JOIN cms_posts_translate cp 
						ON c.id = cp.post_id and language = '{$language}' and c.post_type = 'page'";

		$post_pages = $this->db->select( $sql );

		foreach ($post_pages as $key => $value) {
			$temp[$value->id] = $value;
		}

		return $temp;
  }
}
