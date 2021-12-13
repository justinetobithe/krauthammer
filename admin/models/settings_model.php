<?php

class Settings_Model extends Model{
	public function __construct(){
		parent::__construct();
		$this->db->table='system_options';

	}

	public function saveSetting($image,$setting){
		$qry_str = "INSERT INTO `".$this->db->table."` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('{$setting}', '{$image}', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='{$image}'";
		$qry = $this->db->query( $qry_str );
		// $qry = $this->db->query("UPDATE ".$this->db->table." SET `option_value` = '$image' WHERE `option_name` = '$setting'");

		if($qry)
			return true;

		return false;
	}
	public function loadData(){
		$qry = $this->db->select("SELECT * FROM ".$this->db->table. " ORDER BY `id`");
		$rows = array();

		foreach ($qry as $key => $value) {
			$rows[$value->option_name] = $value;
		}

		return json_encode($rows);

	}
	function get_maps(){
		$qry = $this->db->query("SELECT * FROM `maps` ORDER BY `id` DESC");
		$rows = array();
		if($qry)
			while($row = $this->db->fetch($qry,'array')){
				$rows[] = $row;
		}

		return $rows;

	}
	function save_map($data){
		$short_code = '[maps-'.mt_rand().']';
		$position = $data['position'];
		$title = escape_string($data['title']);
		$description = escape_string($data['description']);
		$width = $data['width'];
		$height = $data['height'];
		if($this->db->query("INSERT INTO `maps`(`short_code`, `position`, `title`, `description`, `width`, `height`) VALUES ('$short_code','$position','$title','$description','$width','$height')"))
			return 1;

		return 0;
	}

	function update_map($data){
		$position = $data['position'];
		$title = escape_string($data['title']);
		$description = escape_string($data['description']);
		$width = $data['width'];
		$height = $data['height'];
		$id = $data['id'];

		if($this->db->query("UPDATE `maps` SET `position`='$position',`title`='$title',`description`='$description',`width`='$width',`height`='$height' WHERE `id` = '$id'"))
			return 1;
		return 0;
	}

	function delete_map($id){
		if($this->db->query("DELETE FROM `maps` WHERE `id` = '$id'"))
			return 1;
		return 0;
	}

	function make_robot(){
		$front_config = fopen("../robots.txt","w");
		$txt = "User-agent: *
Disallow: /";

    fwrite($front_config,$txt);
    fclose($front_config);
	}

	function delete_robot(){
		$front_config = fopen("../robots.txt","w");
		$txt = "User-agent: *
Allow: /";

    fwrite($front_config,$txt);
    fclose($front_config);
	}


	function load_blog_setting(){
		$qry = $this->db->select("SELECT * FROM `system_options` WHERE `option_name` Like '%blog_post_%'");
		$bsetting = array();

		foreach ($qry as $key => $value) {
			$bsetting[$value->option_name] = $value->option_value;
		}

		return $bsetting;
	}
	function load_siteurl(){
		$qry = $this->db->select("SELECT * FROM `system_options` WHERE `option_name` Like 'site_url'");
		$bsetting = FRONTEND_URL;
		if (count($qry)) {
			$qry = $qry[0];
			$bsetting = $qry->option_value;
		}

		return $bsetting;
	}
	function load_post_url_format(){
		$qry = $this->db->select("SELECT * FROM `system_options` WHERE `option_name` Like 'post_url_format'");
		$bsetting = FRONTEND_URL;
		if (count($qry)) {
			$qry = $qry[0];
			$bsetting = $qry->option_value;
		}

		return $bsetting;
	}
	function load_pages(){
		$qry = $this->db->select("Select * From `cms_posts` Where `post_type` = 'page' and `post_status` = 'active' and `status`='publish'");

		return $qry;
	}

	function update_product_settings($action = '', $data = array()){
		$data_cached = array();
		foreach ($data as $key => $value) {
			$data_cached[$value['option_name']] = $value;
		}

		$site_url = get_system_option('site_url');
		$slash		= substr($site_url, -1)=='/'?'/':'';
    $site_url	= trim($site_url,'/');

		$sql = "SELECT meta, is_reserved, if(guid=1,'Y','N') is_default 
	          FROM (
	            (
	              SELECT * FROM (SELECT *, 'N' is_reserved FROM cms_items) cms_items
	              Union All (
	                SELECT '0' id, 
	                  if((SELECT count(*) c FROM cms_items WHERE type = 'cms-language' AND guid=1)>0,0,1) guid,
	                  'cms-language' type, 
	                  ifnull((SELECT value FROM cms_items WHERE type = 'cms-language-default'),'English') value, 
	                  ifnull((SELECT meta FROM cms_items WHERE type = 'cms-language-default'),'en') meta, 
	                  'active' status, 
	                  NOW() date_added,
	                  'Y' is_reserved
	              )
	            )
	          ) t1 WHERE type = 'cms-language' AND status = 'active' ORDER BY FIELD(is_default,'Y','N') ASC;";
	  
	  $prod_cat_format_old	= get_system_option('product_category_format_url');
	  $prod_cat_format_new	= isset($data_cached['product_category_format_url']) ? $data_cached['product_category_format_url']['option_value'] : 'show_parent';

	  foreach ( $this->db->select($sql) as $key => $language_value ) {
	  	$prod_cat_option	= "product_category_url" . ($language_value->is_reserved=='Y'?"":"_{$language_value->meta}");
	  	$prod_option 			= "product_url" . ($language_value->is_reserved=='Y'?"":"_{$language_value->meta}");

	  	/* Update Product Categories URLs */
	  	if (isset($data_cached[$prod_cat_option])) {
		  	$base_prod_cat_current = get_system_option( $prod_cat_option );
		    $base_prod_cat_current = $base_prod_cat_current == '' ? 'product-category' : $base_prod_cat_current;

		    $base_prod_cat_updated = $data_cached[$prod_cat_option]['option_value'];
		    $base_prod_cat_updated = $base_prod_cat_updated == '' ? 'product-category' : $base_prod_cat_updated;


		  	$base_language  = $language_value->is_default  == 'Y' ? "" : "/{$language_value->meta}";
	    	$join_type      = $language_value->is_reserved == 'Y' ? "LEFT" : "INNER";
	      $categories 		= array();

		  	$sql = "SELECT c.id, c.url_slug, c.category_parent, t.meta
	            	FROM product_categories c
	            	{$join_type} JOIN cms_translation t on t.guid = c.id and t.language = '{$language_value->meta}' and type = 'product-category'";

	      foreach ($this->db->select($sql) as $key => $value) {
		      $categories[$value->id] = $value;
		    }

		    /* Loop: adding category and info */
		    foreach ($categories as $key => $value) {
		      $current_page = $value;
		      $m            = json_decode($current_page->meta);
		      $url_1        = "/" . (isset($m->url_slug) ? $m->url_slug : $current_page->url_slug);
		      $url_2        = "/" . (isset($m->url_slug) ? $m->url_slug : $current_page->url_slug);

		      while ($current_page->category_parent != 0) {
		        if (isset($categories[$current_page->category_parent])) {
		          $current_page = $categories[$current_page->category_parent];
		          $m            = json_decode($current_page->meta);


		          if ($prod_cat_format_old != 'no_parent') {
		          	$url_1        = "/" . (isset($m->url_slug) ? $m->url_slug : $current_page->url_slug) . "{$url_1}";
		          }

		          if ($prod_cat_format_new != 'no_parent') {
		          	$url_2        = "/" . (isset($m->url_slug) ? $m->url_slug : $current_page->url_slug) . "{$url_2}";
		          }
		        }else{
		          break;
		        }
		      }
		      
		      $old_link = "{$site_url}{$base_language}/{$base_prod_cat_current}{$url_1}{$slash}";
		      $new_link = "{$site_url}{$base_language}/{$base_prod_cat_updated}{$url_2}{$slash}";

		      // header_json(); 
		      // print_r($old_link); 
		      // print_r("\n"); 
		      // print_r($new_link); 
		      // print_r("\n"); 
		      // print_r("\n"); 

		      replace_url($old_link, $new_link, 'product_categories');
		    }
	    }

	    /* Update Products URLs */
	  	if (isset($data_cached[$prod_option])) {
	  		$base_prod_current = get_system_option( $prod_option );
		    $base_prod_current = $base_prod_current == '' ? 'product' : $base_prod_current;

		    $base_prod_updated = $data_cached[$prod_option]['option_value'];
		    $base_prod_updated = $base_prod_updated == '' ? 'product' : $base_prod_updated;


		  	$base_language  = $language_value->is_default  == 'Y' ? "" : "/{$language_value->meta}";
	    	$join_type      = $language_value->is_reserved == 'Y' ? "LEFT" : "INNER";

		  	$sql = "SELECT c.id, c.url_slug, t.meta
	            	FROM products c
	            	{$join_type} JOIN cms_translation t on t.guid = c.id and t.language = '{$language_value->meta}' and type = 'product'";

		    /* Loop: adding category and info */
		    foreach ($this->db->select($sql) as $key => $value) {
		      $current_item = $value;
		      $m            = json_decode($current_item->meta);
		      $url          = "/" . (isset($m->product) && isset($m->product->url_slug) ? $m->product->url_slug : $current_item->url_slug);

		      $old_link = "{$site_url}{$base_language}/{$base_prod_current}{$url}{$slash}";
		      $new_link = "{$site_url}{$base_language}/{$base_prod_updated}{$url}{$slash}";

		      replace_url($old_link, $new_link, 'products');
		    }
	  	}
	  }

		$this->update_system_option('save', $data);
	}
	function update_system_option($action = '', $data = array()){
		if ($action == 'save') {
			foreach ($data as $key => $value) {
				$this->db->query("INSERT INTO `system_options` (`option_name`, `option_value`) VALUES('{$value['option_name']}', '{$value['option_value']}') ON DUPLICATE KEY UPDATE `option_value`='{$value['option_value']}'");
			}
		}
	}
	
  function load_system_settings(){
    $rows = array();
    $qry = $this->db->query("SELECT * FROM `system_options` WHERE `option_name` = 'system_type'");
    if($qry) 
      return $this->db->fetch($qry,'array');

    return $rows;
  }

  function get_languages(){
    return $this->db->select("Select * From (Select * From `cms_items` Union All (Select '0' `id`, IF((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guid`,'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added`)) `t` Where `type`='cms-language' and `status`='active' Order by `value`");
  }
  function get_language_default(){
    return $this->db->select("Select `id`, `value`, `meta` `slug`, if(`guid`=1, 'selected' , '') `selected` FROM ((Select * From `cms_items` Union All (Select '0' `id`, if((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guide`,'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added`))) `t1` Where type = 'cms-language' and `status` = 'active' Order By `value` asc");
  }
  function get_language($lang_id = 0){
    return $this->db->select("Select * From (Select * From `cms_items` Union All (Select '0' `id`, '0' `guide`,'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added`)) `t` Where `status`='active' and `id` = '{$lang_id}' Order by `value`");
  }
  function get_language_reserved(){
  	$res_lang = $this->db->select("Select `id`, if((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guid`, 'cms-language' `type`, `value`, `meta`, `status`, `date_added`, '1' `is_default` From ( Select * From `cms_items` Where `type` = 'cms-language-default' Union ( Select '0' `id`, '0' `guid`, 'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added` ) Order By `id` desc Limit 1 ) `t4`");
  	return $res_lang[0];
  }
}