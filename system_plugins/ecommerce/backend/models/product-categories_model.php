<?php
if (!class_exists('XMLSitemap')) {
	include ROOT . "libraries/plugins/sitemap/sitemap.php";
}

class ProductCategories_Model extends Model{
	public $language_reserved;
	public $language_default;
	public $baseurl;
	public $product_category_base_slug;
	public $cached_categories;

	public function __construct(){
		parent::__construct();
		$this->product_category_base_slug = "product-category";

		$this->language_reserved 	= get_reserved_language();
		$this->language_default 	= get_default_language();
		$this->baseurl 	= get_system_option('product_category_url');

		$this->cached_categories = $this->load_cached_categories();
	}

	public function get_parents(){
		$qry = $this->db->query("SELECT * FROM `product_categories`");
		$rows = array();
		$parent_rows = array();
		$final_rows = array();

		while($row = $this->db->fetch($qry,'array')){
			$rows[] = $row;
		}

		foreach ($rows as $key => $category) {
			$parent_rows[] = $category['category_parent'];
		}

		$parent_rows = array_unique($parent_rows);
		$category_name = array();
		foreach ($rows as $key => $value) {
			if(in_array($value['id'], $parent_rows)){
				$string = '--';
				if(!in_array($value['category_name'], $category_name)){
					$final_rows[] = $value;
				}else{
					$string .= '--';
				}

				$id = $value['id'];

				$qry_children =  $this->db->query("SELECT * FROM `product_categories` WHERE `category_parent` = '$id' ");

				while($row_children = $this->db->fetch($qry_children,'array')){
					$name = $string.$row_children['category_name'];
					$category_name[] = $row_children['category_name'];
					$final_rows[] = array('id'=>$row_children[0],'category_name'=>$name, 'category_description' => $row_children[3]);
				}
			}
			else{
				if($value['category_parent'] == 0){
					$final_rows[] = $value;
				}
			}
		}

		return $final_rows;
	}
	public function get_parents_2($current_category = 0){
		$product_categories = $this->db->select("SELECT * FROM `product_categories`");
		$categories = array();

		foreach ($product_categories as $key => $value) {
			$categories[$value->id] = $value;
		}

		$d = $this->get_category_parent_heirarchy($categories);
		$arranged = $this->arrange_parent( $d, "" , $current_category );
		return $arranged;
	}
	public function arrange_parent($categories=array(), $level = "", $current_category = 0){
		$d = array();
		foreach ($categories as $key => $value) {
			if ($value['detail']->id == $current_category) {
				continue;
			}
			$value['detail']->level = $level;
			$d[] = $value['detail'];
			if (count($value['children'])) {
				$c = $this->arrange_parent($value['children'], $level . "-", $current_category);
				if (count($c)) {
					$d = array_merge($d, $c);
				}
			}
		}
		return $d;
	}

	public function get_categories(){
		$qry = $this->db->query("SELECT * FROM `product_categories` WHERE `category_parent` = 0 ORDER BY `sort_order` ");
		$rows = array();
		$parents = array();
		$parent = array();
		while($row = $this->db->fetch($qry,'array')){
			$parent_id = $row['id'];

			$rows[] = array('id'=>$row['id'],'category_name'=>$row['category_name'],'category_description'=>$row['category_description'],'category_parent'=> ' ');   

			$qry_children = $this->db->query("SELECT * FROM `product_categories` WHERE `category_parent` = '$parent_id' ORDER BY `id` ");
			if($qry_children){
				while ($children = $this->db->fetch($qry_children, 'array')) {
					/*$rows[] = array('id'=>$children['id'],'category_name'=>$children['category_name'],'category_description'=>$children['category_description'],'category_parent'=>$row['category_name']);*/
				}
			}
		}

		return $rows;
	}

	public function get_categories_2(){
		$qry = $this->db->query("SELECT * FROM `product_categories` ORDER BY `id` ASC, `sort_order` ASC ");
		$rows = array();
		$categories = array();
		$parent = array();
		while($row = $this->db->fetch($qry,'array'))
			$categories[] = $row;
		foreach ($categories as $key => $category) {
			$parent_name = ' ';
			foreach ($categories as $key => $check_category) {
				if($category['category_parent'] == $check_category['id'])
					$parent_name = $check_category['category_name'];
			}
			$rows[] = array('id'=>$category['id'],'category_name'=>$category['category_name'],'category_description'=>$category['category_description'],'category_parent'=>$parent_name,'parent'=>$category['category_parent'],);
		}
		return $rows;
	}

	public function add_product_category($name, $parent, $description,$image,$hide,$url_slug){
		$qry_sort_no = $this->db->query("SELECT * FROM `product_categories` ORDER BY `sort_order` DESC LIMIT 1");
		$sort = $this->db->fetch($qry_sort_no,'array');
		$sort_no = (int)$sort['sort_order'] + 1;
		$qry = $this->db->query("INSERT INTO `product_categories` (`id`,`category_name`,`category_parent`,`category_description`,`image_url`,`url_slug`,`hide_category`,`sort_order`) VALUES(NULL, '$name','$parent','$description','$image', '$url_slug', '$hide', '$sort_no')");

		if (get_system_option('product_no_index_category_page') != "Y") {
			/*UPDATING SITEMAP*/
			$sitemap = new XMLSitemap();
			$sitemap->update();
		}

		if($qry){
			$qry_1 = $this->db->query("SELECT * FROM `product_categories` ORDER BY `id` DESC LIMIT 1");
			$id = $this->db->fetch($qry_1,'array');

			if ($this->language_default != $this->language_reserved ) {
				$td = array(
					"guid" => $id['id'],
					"type" => "product-category",
					"language" => $this->language_default,
					"meta" => json_encode(array(
						"category_name" => $name,
						"category_description" => $description,
						"url_slug" => $url_slug,
						)),
				);

				$this->db->table = "cms_translation";
				$this->db->data = $td;
				$this->db->insertGetID();

				/* Get Current Product Categori Info */
				/* Get Product Category URL Slug */
				$this->baseurl = get_system_option("product_category_url");
				$this->baseurl = $this->baseurl==''?'products_category':$this->baseurl;

				$new_url = FRONTEND_URL . "/{$this->language_reserved}/{$this->baseurl}/{$url_slug}";
				replace_url("", $new_url, 'product_categories');
			}

			$this->baseurl = get_system_option('product_category_url' . ($this->language_reserved != $this->language_default ? "_{$this->language_default}" : ""));
			$this->baseurl = $this->baseurl==''?'products_category':$this->baseurl;
			$new_url = FRONTEND_URL . "/{$this->baseurl}/{$url_slug}";
			replace_url("", $new_url, 'product_categories');


			return $id['id'];
		}
		else
			return "0";
	}

	public function delete_categories($id){
		$qry = $this->db->query("DELETE FROM `product_categories` WHERE `id` = '$id' ");

		if($qry){
			$sitemap = new XMLSitemap();
			$sitemap->update();
			
			return "1";
		}else{
			return "0";
		}
	}

	public function get_category($id){
		$qry = $this->db->query("SELECT * FROM `product_categories` WHERE `id` = '$id' ");

		if($qry){
			$c = $this->db->fetch($qry, 'array');
			if ( count($c) ) {
				$siteurl_info = get_site_url_info();
				$base_url = trim($siteurl_info['siteurl'],'/') . '/';
				// $c['permalink'] = $base_url . $this->get_parent_url_slug( $c['category_parent'] ) . $c['url_slug'];
				$prod_url = get_system_option('product_category_url');
				$_base 		= ($prod_url!=''?$prod_url:'product-category') . "/";
				$_pslug		= ltrim(($this->get_parent_slug( $c['category_parent'] )),'/');
				$_pslug		= $_pslug != "" ? $_pslug . "/" : "";

				$c['permalink'] = $base_url. $_base . $_pslug . $c['url_slug'];
				$c['permalink'] .= $siteurl_info['has_slash'] ? "/" : "";
				$c['translate'] = "main";
				return count($c) ? $c : array();
			}
		}

		return array();
	}
	public function load_categories($filter_data = array()){
		$language = isset($filter_data['language']) ? $filter_data['language'] : $this->language_default;

		$sql = "SELECT c.*, t.meta trans_data FROM `product_categories` c 
						LEFT JOIN cms_translation t ON c.id = t.guid AND t.type = 'product-category' and t.language = '{$language}' 
						ORDER BY c.id ASC";
		$product_categories = $this->db->select( $sql );

		$temp = array();
		foreach ($product_categories as $key => $value) {
			$temp[$value->id] = $value;
		}

		return $temp;
	}
	public function get_product_category_parent($product_category_id = 0, $lang){
		$temp_prod_cats 				= $this->load_categories(array("language"=>isset($lang) ? $lang : $this->language_default));
		$temp_parent_slug 			= "";
		$temp_current_prod_cat 	= array();
		$temp_parent_id 				= $product_category_id;
		while (isset($temp_prod_cats[$temp_parent_id])) {
			$temp_current_prod_cat = $temp_prod_cats[$temp_parent_id];
			$temp_parent_id = $temp_current_prod_cat->category_parent;

			$m = json_decode($temp_current_prod_cat->trans_data);
			$s = (isset($m->url_slug) ? $m->url_slug : $temp_current_prod_cat->url_slug);
			$temp_parent_slug .= $s!=''?"/{$s}":"" . "{$temp_parent_slug}";
		}
		return $temp_parent_slug;
	}
	public function update_product_category($id,$name, $parent, $description,$image,$save,$hide,$url_slug, $old_slug, $lang = ''){
		$uc = new UC();
		$old_url 		= "";
		$new_url 		= "";
		$format_url = get_system_option('product_category_format_url');

		$trans_col = $uc->reserved_language == $lang ? " `category_name` = '$name', `category_description` = '{$description}', " : "";

		/* Get Current Product Categori Info */
		$sql = "SELECT p.*, t.meta 
						FROM product_categories p 
						LEFT JOIN cms_translation t 
						ON p.id = t.guid AND t.type = 'product-category' AND t.language = '{$lang}' 
						WHERE p.id = '{$id}'";
		$product_category_info = $this->db->select( $sql )[0];
		
		/* Get Product Category URL Slug */
		$this->baseurl = get_system_option('product_category_url' . ($this->language_reserved != $lang ? "_{$lang}" : ""));
		$this->baseurl = $this->baseurl == '' ? 'products_category' : $this->baseurl;

		$temp_parent_slug_old = $format_url=='show_parent' ? $this->get_product_category_parent($product_category_info->category_parent, $lang) : "";
		$temp_parent_slug_new = $format_url=='show_parent' ? $this->get_product_category_parent($parent, $lang) : "";

		$_slug = isset($product_category_info->meta) ? json_decode($product_category_info->meta) : array();
		$_slug = isset($_slug->url_slug) && $this->language_reserved != $lang?$_slug->url_slug:$product_category_info->url_slug;
		
		$old_url = FRONTEND_URL . ($lang != '' && $lang != $this->language_default ? "/{$lang}" : '') . "/{$this->baseurl}{$temp_parent_slug_old}/{$_slug}";
		$new_url = FRONTEND_URL . ($lang != '' && $lang != $this->language_default ? "/{$lang}" : '') . "/{$this->baseurl}{$temp_parent_slug_new}/{$url_slug}";

		$this->db->table 	= "product_categories";
		$this->db->data 	= array(
			"id" => $id,
			"category_parent" => $parent,
			"hide_category" 	=> $hide,
			"url_slug" 				=> $url_slug,
			"old_slug" 				=> $old_slug,
			);
		if ($lang != $uc->reserved_language) {
			unset($this->db->data['url_slug']);
		}
		if($image != ''){
			$this->db->data['image_url'] = $image;
		}

		$qry = $this->db->update();

		/*moved thsi code to the bottom part*/
		// if ($qry) {
		// 	replace_url($old_url, $new_url, 'product_categories');
		// }


		/* Procrss translation */
		if ($lang != $uc->reserved_language) {
			/* Get current translation */
			$cms_translation_sql = "Select * From `cms_translation` Where `language` = '{$lang}' and `type`='product-category' and `guid` = '{$id}'";
			$current_trans = $this->db->select( $cms_translation_sql );

			$td = array(
				"guid" => $id,
				"type" => "product-category",
				"language" => $lang != "" ? $lang : $uc->reserved_language,
				"meta" => json_encode(array(
					"category_name" => $name,
					"category_description" => $description,
					"url_slug" => $url_slug,
					)),
			);

			$this->db->table = "cms_translation";
			if (count($current_trans)) {
				$td = array_merge(array('id'=>$current_trans[0]->id), $td);
				$this->db->data = $td;
				$this->db->update();
			}else{
				$this->db->data = $td;
				$this->db->insertGetID();
			}
		}

		if (get_system_option('product_no_index_category_page') != "Y") {
			/*UPDATING SITEMAP*/
			$sitemap = new XMLSitemap();
			$sitemap->update();
		}

		if($qry){
			if ($product_category_info->category_parent != $parent) {
				/*Apply Redirect to all languages*/
				$sql = "SELECT p.id, p.url_slug, p.category_parent, '0' trans_id, '' meta, '{$this->language_reserved}' language 
								FROM product_categories p 
								WHERE p.id = '{$product_category_info->id}'
								UNION
									SELECT p.id, p.url_slug, p.category_parent, c.id trans_id, c.meta trans_data, c.language 
									FROM product_categories p 
									INNER JOIN cms_translation c 
									WHERE c.type = 'product-category' AND c.guid = p.id and p.id = '{$product_category_info->id}'";

				$siteurl = trim(get_system_option('site_url'),'/') . "/";
				foreach ($this->db->select($sql) as $key => $value) {
					$baseurl = get_system_option('product_category_url' . ($this->language_reserved != $value->language ? "_{$value->language}" : ""));
					$baseurl = "/" . ($baseurl == '' ? 'products_category' : $baseurl);

					$parent_slug_old 	= $this->get_parent_slug($product_category_info->category_parent, $value->language);
					$parent_slug_new 	= $this->get_parent_slug($value->category_parent, $value->language);

					$meta = json_decode($value->meta);
					$url = isset($meta->url_slug) ? $meta->url_slug : $value->url_slug;

					$lang = $value->language == $this->language_default ? "" : "/{$value->language}";

					$url1 	= trim($siteurl,'/') . "{$lang}{$baseurl}{$parent_slug_old}/{$url}{$bckslsh}";
					$url2 	= trim($siteurl,'/') . "{$lang}{$baseurl}{$parent_slug_new}/{$url}{$bckslsh}";

					replace_url($url1, $url2, 'product_categories');
				}
			}else{
				/*Apply Redirect selected languages*/
				replace_url($old_url, $new_url, 'product_categories');
			}

			if($save == 'Save and Add New'){
				return "ok";
			}
			else{
				return "ok-save";
			}
		}else{
			return "0";
		}
	}
	public function get_categories_parent_zero(){
		$qry = $this->db->query("SELECT * FROM `product_categories` WHERE `category_parent` = 0 ORDER BY `sort_order`");
		$rows = array();

		while($row = $this->db->fetch($qry,'array'))
			$rows[] = $row;

		return $rows;
	}
	public function sort_categories($sort){
		$index = 1;
		$err = false;
		foreach ($sort as $key => $category_id) {

			$qry = $this->db->query("UPDATE `product_categories` SET `sort_order` = '$index' WHERE `id` = '$category_id' ");

			if(!$qry)
				$err = true; 
			$index++;
		}

		if($err){
			return "0";
		}

		return "1";
	}

	public function get_available_slug($slug, $lang=''){
		/* Product Categories New Start*/
		$uc = new UC();

		$slug = remove_accents($slug);

		$rows 			= array();
		$current_id = isset($_POST['current_id']) ? $_POST['current_id'] : 0;
		$parent_id 	= isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;
		$filter 		= " and `id` <> '{$current_id}'";
		$lang 			= $lang == '' ? $uc->reserved_language : $lang;

		/*Get product category using the current ID*/
		$current_category = $this->db->select("SELECT * FROM `product_categories` Where `id` = '{$current_id}'");

		$prod_c = array();
		$prod_p = array();
		$sql = "SELECT distinct(url_slug) url_slug, type FROM (
						SELECT url_slug, 'product' type FROM products
						UNION SELECT url_slug, 'product-category' type FROM product_categories Where `id` <> '{$current_id}'
						UNION SELECT url_slug, post_type type FROM cms_posts 
						UNION SELECT url_slug, 'category' type FROM post_category
						UNION SELECT url_slug, concat('translated-', post_type) type FROM cms_posts_translate Where `id` <> '{$current_id}') t
						GROUP BY url_slug, type order by url_slug";
		foreach ( $this->db->select( $sql ) as $key => $value) {
			$prod_c[] = $value->url_slug;
		}

		foreach ( $this->db->select( "SELECT * FROM `product_categories` Where `id` <> '{$current_id}'" ) as $key => $value) {
			$prod_p[$value->id] = $value;
		}
		
		$sql = "Select * From `cms_translation` Where type IN ('product-category','product')  and guid <> '{$current_id}'";
		foreach ($this->db->select( $sql ) as $key => $value) {
			if ($value->type == 'product-category') {
				$jdata = json_decode($value->meta);
				if ($jdata->url_slug) {
					$prod_c[] = $jdata->url_slug;
				}
			}
			if ($value->type == 'product') {
				$jdata = json_decode($value->meta);
				if (isset($jdata->product->url_slug)) {
					$prod_c[] = $jdata->product->url_slug;
				}
			}
		}

		$url = trim($slug,'/');
		$url2 = count($current_category) ? trim($current_category[0]->url_slug,'/') : '';

		$pctr = 0;
		while (in_array($url, $prod_c)) {
			$url = trim($slug,'/') . "-" . ++$pctr;
		}

		$parent = "";
		$translation = array();
		$is_translated = "no translation";

		if ($lang != $uc->reserved_language && count($current_category)) {
			/*get translation*/
			$prod_t = $this->db->select("Select * From `cms_translation` Where `type` = 'product-category' and `language` = '{$lang}' and `guid` = '{$current_category[0]->id}'");
			$t = array();
			if (count($prod_t)) {
				$t = json_decode($prod_t[0]->meta);
			}

			if (isset($t->category_name)) {
				$is_translated = 'translated';
				$current_category[0]->category_name = $t->category_name;
			}
			if (isset($t->url_slug)) {
				$current_category[0]->url_slug = isset($t->url_slug) ? $t->url_slug : '';
				$url2 = trim($t->url_slug,'/');
				$pctr = 0;
				while (in_array($url2, $prod_c)) {
					$url2 = trim($t->url_slug,'/') . "-" . ++$pctr;
				}
			}
			$current_category[0]->category_description = isset($t->category_description) ? $t->category_description : '';
		}


		$p_id = $parent_id;
		$loop_limit = 0;

		while (isset($prod_p[$p_id])) {
			if (count($current_category)) {
				if ($current_category[0]->id == $prod_p[$p_id]->id) {
					break;
				}
			}

			$parent .= $prod_p[$p_id]->url_slug . "/";
			$p_id = $prod_p[$p_id]->category_parent;

			if (++$loop_limit > 10) {
				break;
			}
		}

		$parent = $this->get_parent_slug($parent_id, $lang);

		$res_lang = get_reserved_language();
		$prod_url = get_system_option('product_category_url' . ($res_lang != $lang ? '_'.$lang : ''));

		$temp = $uc->uc_get_current_url_settings(get_system_option('site_url'));
		$p = $temp['site_url_info']['protocol'];
		$w = $temp['site_url_info']['has_www'] ? 'www.' : '';
		$q = $temp['current_url_info']['query'] != '' ? '?' . $temp['current_url_info']['query'] : '';
		$l = $temp['language']['slug_default'] == $lang ? '' : $lang . ($url != '' ? '/' : '');
		$s = $temp['site_url_info']['has_slash'] ? '/' : '';
		$h = $temp['site_url_info']['host'] . ("{$l}{$url}" != "" ? "/" : "");
		
		$parent = $parent != '' ? trim($parent,'/') . '/' : "";
		$u = ($prod_url!=''?$prod_url:'product-category') . '/' . $parent . trim($url);

		$permalink = "{$p}://{$h}{$l}{$u}{$s}{$q}";


		/* START: calculating permalink for the detail */
		$_l = $temp['language']['slug_default'] == $lang ? '' : $lang . ($url2 != '' ? '/' : '');
		$_h = $temp['site_url_info']['host'] . ("{$_l}{$url2}" != "" ? "/" : "");
		
		$parent = $parent != '' ? trim($parent,'/') . '/' : "";
		$_u = ($prod_url!=''?$prod_url:'product-category') . '/' . $parent . trim($url2);

		$_permalink = "{$p}://{$_h}{$_l}{$_u}{$s}{$q}";

		if (count($current_category)) {
			$current_category[0]->permalink = $_permalink;
		}
		/* END: calculating permalink for the detail*/

		if ($lang == $uc->reserved_language) {
			$is_translated = "default";
		}

		if ($temp['language']['slug_default'] == $lang) {
			$is_translated = "main";
		}

		return array(
			"slug" => $url,
			"parent_slug" => $parent,
			"permalink" => $permalink,
			"detail" => count($current_category) ? $current_category[0] : array(),
			"translate" => $is_translated,
			);

		exit();
		/* Product Categories New END*/
		/*
		$rows = array();
		$current_id = 0;
		$parent_id = 0;
		$filter = "";

		$siteurl_info = get_site_url_info();
		$base_url = trim($siteurl_info['siteurl'],'/') . '/';

		if (isset($_POST['current_id'])) {
			$current_id = $_POST['current_id'];
			$filter = " and `id` <> '{$current_id}'";
		}
		if (isset($_POST['parent_id'])) {
			$parent_id = $_POST['parent_id'];
		}

		if ($filter != "") {
			$rows['slug'] = get_url($slug,'product_categories', $filter);
		}else{
			$rows['slug'] = get_url($slug,'product_categories');
		}

		$rows['parent_slug'] = $this->get_parent_url_slug( $parent_id );

		$rows['permalink'] = $base_url . $rows['parent_slug'] . $rows['slug'];
		$rows['permalink'] .= $siteurl_info['has_slash'] ? "/" : "";

		return $rows;
		*/
	}
	private function get_parent_url_slug( $parent_id = 0 ){
		$parent_slug = "";
		if ($parent_id != 0) {
			$product_categories = $this->db->select("SELECT * FROM `product_categories`");
			$categories = array();

			foreach ($product_categories as $key => $value) {
				$categories[$value->id] = $value;
			}

			$parent_slug = $this->get_category_parent_slug($categories, $parent_id) . "/";
			$parent_slug = $parent_slug != "" ? trim($parent_slug,'/') . "/" : "";
		}

		return $this->product_category_base_slug . "/" . $parent_slug;
	}
	private function get_category_parent_heirarchy($categories = array(), $parent = 0){
		$heirarchy = array();

		foreach ($categories as $key => $value) {
			if ($value->category_parent == $parent) {
				$category = array(
					"detail" => $value,
					"children" => $this->get_category_parent_heirarchy($categories, $value->id),
					);
				$heirarchy[$value->id] = $category;
			}
		}

		return $heirarchy;
	}
	private function get_category_parent_slug($categories = array(), $parent = 0){
		if ($parent == 0) {
			return "";
		}

		$slug = "";
		if (isset($categories[$parent])) {
			$slug = $categories[$parent]->url_slug;
		}

		$parent_slug = $this->get_category_parent_slug($categories, $categories[$parent]->category_parent);
		$heirarchy_slug = "{$parent_slug}/{$slug}";

		return $heirarchy_slug;
	}
	private function load_cached_categories(){
		$sql = "SELECT pc.*,ct.meta,ct.language 
						FROM product_categories pc 
						LEFT JOIN cms_translation ct 
						ON pc.id = ct.guid 
						AND ct.type = 'product-category' 
						ORDER BY pc.id Asc";

		$current_post_categories = $this->db->select( $sql );
		$_temp = array();

		foreach ($current_post_categories as $key => $value) {
			$_temp[$this->language_reserved][$value->id] = clone $value;
			$_temp[$this->language_reserved][$value->id]->meta = '';
			$_temp[$this->language_reserved][$value->id]->language = $this->language_reserved;

			if (isset($value->language) && $value->language != '') {
				if (!isset($_temp[$value->language])) {
					$_temp[$value->language] = array();
				}
				$_temp[$value->language][$value->id] = clone $value;
			}
		}

		return $_temp;
	}
	function get_parent_slug($product_category_parent_id, $language=null){
		if (!isset($language) || $language == null) {
			$language = $this->language_reserved;
		}

		if ($product_category_parent_id==0 || !isset($this->cached_categories[$language][$product_category_parent_id]) || get_system_option('product_category_format_url') != 'show_parent') {
			return "";
		}

		$product_category = $this->cached_categories[$language][$product_category_parent_id];
		$_m = json_decode($product_category->meta);
		$parent = "/" . (isset($_m->url_slug) ? $_m->url_slug : $product_category->url_slug);

		while ($product_category->category_parent != 0) {
			if (!isset($this->cached_categories[$language][$product_category->category_parent])) {
				break;
			}
			$temp = $this->cached_categories[$language][$product_category->category_parent]; 
			$_m = json_decode($temp->meta);
			$_s = isset($_m->url_slug) ? $_m->url_slug : $temp->url_slug;

			$parent = "/{$_s}{$parent}";
			$product_category = $temp;
		}

		return $parent;
	}

}