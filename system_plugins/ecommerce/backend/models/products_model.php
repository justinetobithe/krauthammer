<?php
if (!class_exists('XMLSitemap')) {
	include ROOT . "libraries/plugins/sitemap/sitemap.php";
}
class Products_Model extends Model{
	public $language_reserved;
	public $language_default;
	public $baseurl;

	public function __construct(){
		parent::__construct();
		$this->db->table='products';
		$this->language_reserved 	= get_reserved_language();
		$this->language_default 	= get_default_language();
		$this->baseurl 	= get_system_option('product_url');
	}
	function save($data = array()){
		$this->db->data = $data;
		return $this->db->insertGetID();
	}
	function update($data){
		$this->db->data = $data;
		return $this->db->update();
	}

	public function loadProductCategories(){
		$qry = $this->db->query("SELECT * FROM `product_categories`");
		$rows = array();

		while($row = $this->db->fetch($qry,"array")){
			$rows[] = $row;
		}

		return $rows;

	}
	function addProduct($data=array()){

		$product_created = date('Y-m-d h:i:s');
		/*$url_slug = get_url($product_name, 'products');*/
		$this->db->data = array(
			'product_name' => $data['product_name'],
			'product_description' => $data['product_description'],
			'product_status' => $data['product_status'],
			'featured_image_url' => $data['upload_path'],
			'featured_product' => $data['featured_product'],
			'price' => $this->get_number($data['product_price']),
			'sku' => $data['product_sku'],
			'quantity' =>$this->get_number($data['product_qty']),
			'min_order_qty' => $this->get_number($data['product_min_order_qty']),
			'max_order_qty' => $this->get_number($data['product_max_order_qty']),
			'qty_interval' => $this->get_number($data['product_interval']),
			'qty_label' => $data['product_qty_label'],
			'out_of_stock_status' => $data['product_stock'],
			'product_created' => $product_created,
			'url_slug' => $data['url_slug'],
			'seo_title' => $data['seo_title'],
			'seo_description' => $data['seo_description'],
			'seo_no_index' => $data['seo_no_index'],
			'track_inventory' => $data['track_inventory'],
			'recommended_for_checkout' => $data['recommended_for_checkout'],
			'status' => $data['status'],
			'product_brand_id' => $this->get_number($data['product_brand_id']),
			);

		$product_id = $this->db->insertGetID();
		$qry = $product_id > 0;

		$arr = explode(",", $data['product_category']);
		$this->db->table = 'products_categories_relationship';
		foreach ($arr as $key ) {
			$this->db->data = array(
				'category_id' => $key,
				'product_id' => $product_id,
				);;
			$this->db->insertGetID();
		}


		$product_id = 0;
		if($qry){
			$qry_1 = $this->db->query("SELECT * FROM `products` ORDER BY `id` DESC LIMIT 1");

			$r_qry_1 = $this->db->fetch($qry_1,"array");

			$product_id = $r_qry_1['id'];

			// add_url(FRONTEND_URL.'/'.$data['url_slug'], $product_id, 'products');
			$this->baseurl = get_system_option('product_url' . ($this->language_default != $_POST['language'] ? "_{$_POST['language']}" : ""));
			$this->baseurl = $this->baseurl==''?'products':$this->baseurl;
			$new_url = FRONTEND_URL . (isset($_POST['language']) && $_POST['language'] != $this->language_reserved ? "/{$this->language_reserved}" : '') . "/{$this->baseurl}/{$data['url_slug']}";
		}

			replace_url("", $new_url, 'products');
		
		if(!empty($data['product_tabs']))
			$this->db->table = 'product_tabs';
		foreach ($data['product_tabs'] as $key => $tab) {

			$this->db->data = array(
				'product_id' => $product_id,
				'tab_title' => $tab->title,
				'tab_content' => $tab->content,
				'sort_order' => $key,
				);

			$this->db->insertGetID();
		}

		/* Added Translation */
		if (isset($_POST['language']) && $_POST['language'] != 'en' ) {
			$translation['product'] = array(
				'product_name' => $data['product_name'],
				'product_description' => $data['product_description'],
				'seo_title' => $data['seo_title'],
				'seo_description' => $data['seo_description'],
				'url_slug' => $data['url_slug'],
				);

			$this->db->table = 'cms_translation';
			
			$this->db->data = array(
				"guid" => $product_id,
				"type" => 'product',
				"language" => $_POST['language'],
				"meta" => json_encode($translation),
				);
			$this->db->insert();

			/*$qry = $this->db->query($qryUpdate);*/
			$this->baseurl = get_system_option('product_url' . ($_POST['language'] != $this->language_reserved ? "_{$_POST['language']}" : ""));
			$this->baseurl = $this->baseurl==''?'products':$this->baseurl;
			$new_url = FRONTEND_URL . (isset($_POST['language']) && $_POST['language'] != $this->language_default ? "/{$_POST['language']}" : '') . "/{$this->baseurl}/{$data['url_slug']}";
			// header_json(); 
			// print_r($old_url); 
			// print_r(" | "); 
			// print_r($new_url); 
			// exit();
			replace_url("", $new_url, 'products');
		}

		return $product_id;
	}
	public function updateProduct($data=array()){
		$old_url = "";
		$new_url = "";

		$qryUpdate = '';

		$this->db->data = array(
			'id'=>$this->get_number($data['product_id']),
			'product_name'=>$data['product_name'],
			'product_description'=>$data['product_description'],
			'featured_product'=>$data['featured_product'],
			'price'=>$this->get_number($data['product_price']),
			'sku'=>$data['product_sku'],
			'quantity'=>$this->get_number($data['product_qty']),
			'min_order_qty'=>$this->get_number($data['product_min_order_qty']),
			'max_order_qty'=>$this->get_number($data['product_max_order_qty']),
			'qty_interval'=>$this->get_number($data['product_interval']),
			'qty_label'=>$data['product_qty_label'],
			'out_of_stock_status'=>$data['product_stock'],
			'seo_title'=>$data['seo_title'],
			'seo_description'=>$data['seo_description'],
			'seo_no_index'=>$data['seo_no_index'],
			'track_inventory'=>$data['track_inventory'],
			'url_slug'=>$data['url_slug'],
			'old_slug'=>$data['old_slug'],
			'status'=>$data['status'],
			'product_brand_id'=>$this->get_number($data['product_brand_id']),
			);

		if ($data['upload_path'] != '') {
			$this->db->data['featured_image_url'] = $data['upload_path'];
		}

		/* Get the current product info. Assuming that the given ID is existing */
		$product_old_info = $this->db->select("select p.*, c.meta From products p LEFT JOIN cms_translation c ON p.id = c.guid and c.type = 'product' and c.language = '{$_POST['language']}' Where p.id = {$data['product_id']}")[0];
		$product_old_info_meta = json_decode($product_old_info->meta);
		/* Get the product base url of specific language */
		$this->baseurl = get_system_option('product_url' . ($_POST['language'] != $this->language_reserved ? "_{$_POST['language']}" : ""));
		$this->baseurl = $this->baseurl==''?'products':$this->baseurl;

		$old_url = FRONTEND_URL . (isset($_POST['language']) && $_POST['language'] != $this->language_default ? "/{$_POST['language']}" : '') . "/{$this->baseurl}/" . (isset($product_old_info_meta->product->url_slug) ? $product_old_info_meta->product->url_slug : $product_old_info->url_slug);

		if (isset($_POST['language']) && $_POST['language'] != get_reserved_language() ) {
			unset($this->db->data['product_name']);
			unset($this->db->data['product_description']);
			unset($this->db->data['seo_title']);
			unset($this->db->data['seo_description']);
			unset($this->db->data['url_slug']);
		}

		$this->db->table = 'products';
		$qry = $this->db->update();

		$translation = array();

		if (isset($_POST['language']) && $_POST['language'] != 'en' ) {
			$product_translated = $this->db->select("Select * From `cms_translation` Where `guid` = '{$data['product_id']}' and `language` = '{$_POST['language']}' and `type`='product'");

			$translation['product'] = array(
				'product_name' => $data['product_name'],
				'product_description' => $data['product_description'],
				'seo_title' => $data['seo_title'],
				'seo_description' => $data['seo_description'],
				'url_slug' => $data['url_slug'],
				);

			$this->db->table = 'cms_translation';

			if (count($product_translated)>0) {
				$this->db->data = array(
					"id" => $product_translated[0]->id,
					"meta" => json_encode($translation),
					);
				$this->db->update();
			}else{
				$this->db->data = array(
					"guid" => $data['product_id'],
					"type" => 'product',
					"language" => $_POST['language'],
					"meta" => json_encode($translation),
					);
				$this->db->insert();
			}
		}

		/*$qry = $this->db->query($qryUpdate);*/

		$new_url = FRONTEND_URL . (isset($_POST['language']) && $_POST['language'] != $this->language_default ? "/{$_POST['language']}" : '') . "/{$this->baseurl}/{$data['url_slug']}";
		// header_json(); 
		// print_r($old_url); 
		// print_r(" | "); 
		// print_r($new_url); 
		// exit();
		replace_url($old_url, $new_url, 'products');
		// add_url(FRONTEND_URL.'/'.$data['url_slug'], $data['product_id'], 'products');
		$arr = explode(",", $data['product_category']);

		if($this->db->query("DELETE FROM `products_categories_relationship` WHERE `product_id` = '". $data['product_id'] ."'")){
			foreach ($arr as $key ) {
				$this->db->query("INSERT INTO `products_categories_relationship` (`id`,`category_id`,`product_id`) VALUES (NULL,'{$key}','". $data['product_id'] ."')");
			}
		}

		$not_to_delete_tabs = array();
		$to_update_data = array();
		$this->db->table = 'product_tabs';
		foreach ($data['product_tabs'] as $key => $tab) {
			if (isset($tab->id)) {
				$ptd = array(
					"id" => $tab->id,
					"product_id" => $data['product_id'],
					"tab_title" => $tab->title,
					"tab_content" => $tab->content,
					"sort_order" => $key,
					);

				$this->db->data = $ptd;
				if ($tab->id == '0' || $tab->id == '') {
					/*insert here*/
					unset($this->db->data['id']);
					if (isset($_POST['language']) && $_POST['language'] != 'en') {
						$this->db->data['tab_content'] = '';
					}
					$ptd['id'] = $this->db->insertGetID();
				}else{
					/*update here*/
					if (isset($_POST['language']) && $_POST['language'] == 'en') {
						/*Update tab table if language is english*/
						$this->db->update();
					}
				}

				$to_update_data[] = $ptd;
				$not_to_delete_tabs[] = "'". $ptd['id'] ."'";
			}
		}

		$sql_tab_delete = "DELETE FROM `product_tabs` WHERE `product_id` = '". $data['product_id'] ."'";
		if (count($not_to_delete_tabs) > 0) {
			$tab_list = implode(',', $not_to_delete_tabs);
			$sql_tab_delete = "DELETE FROM `product_tabs` WHERE `product_id` = '". $data['product_id'] ."' and `id` not in (". $tab_list .")";
		}
		$this->db->query($sql_tab_delete);

		if (isset($_POST['language']) && $_POST['language'] != 'en' ) {
			$product_translated = $this->db->select("Select * From `cms_translation` Where `guid` = '{$data['product_id']}' and `language` = '{$_POST['language']}' and `type` = 'product-tab'");

			$tab_translation = array();
			foreach ($to_update_data as $key => $tab) {
				$tab_translation[$tab['id']] = array(
					"id" => $tab['id'],
					"title" => $tab['tab_title'],
					"content" => $tab['tab_content'],
					"order" => $key,
					);
			}

			$this->db->table = 'cms_translation';
			if (count($product_translated)>0) {
				$this->db->data = array(
					"id" => $product_translated[0]->id,
					"meta" => json_encode($tab_translation),
					);
				$this->db->update();
			}else{
				$this->db->data = array(
					"guid" => $data['product_id'],
					"type" => 'product-tab',
					"language" => $_POST['language'],
					"meta" => json_encode($tab_translation),
					);
				$this->db->insert();
			}
		}


		if($qry){
			return true;
		}

		return false;
	}
	function checkIfInString($needle, $haystack) {
		$delimiters = ' ,.';
		return in_array($needle, explode($haystack, $delimiters));
	}
	function get_product_tabs($id){

		$qry = $this->db->query("SELECT * FROM `product_tabs` WHERE `product_id` = '$id' ORDER BY `sort_order`");
		$rows = array();
		while($row = $this->db->fetch($qry, 'array'))
			$rows[] = $row;

		return $rows;
	}
	function getLastID()
	{
		$qry = $this->db->query("SELECT * FROM ".$this->db->table." ORDER BY `id` DESC LIMIT 1");
		$id = $this->db->fetch($qry,"array");

		return $id['id'];
	}
	function getProducts()
	{	

		$qry = $this->db->query("SELECT * FROM ".$this->db->table." WHERE `product_status` <> 'trashed' ORDER BY `id` DESC ");
		$rows = array();

		while($row = $this->db->fetch($qry,"array"))
			$rows[] = $row;

		return $rows;
	}
	function getCategory($id)
	{
		$qry = $this->db->query("SELECT pc.category_name, pc.id FROM `product_categories` pc INNER JOIN `products_categories_relationship` pcr ON pc.id = pcr.category_id WHERE pcr.product_id = '$id'");
		$rows = array();

		while($row = $this->db->fetch($qry, "array"))
			$rows[] = $row;

		return $rows;
	}
	function checkProductExistence($id)
	{
		$qry = $this->db->query("SELECT * FROM ".$this->db->table." WHERE `id` = '$id'");

		if($qry)
			return $this->db->numRows($qry);
	}
	function deleteProduct($id)
	{
		$qry = $this->db->query("UPDATE ".$this->db->table. " SET `product_status` = 'trashed' WHERE `id` = '$id'");

		if($qry){
			$sitemap = new XMLSitemap();
			$sitemap->update();
			
			return 'deleted';
		}

		return 'error';
	}
	function loadProductsByID($id)
	{
		$qry = $this->db->query("SELECT * FROM ".$this->db->table. " WHERE `id` = '$id'");

		if($qry)
			return $this->db->fetch($qry,"array");

		return 'error';
	}
	function loadSelectedCategories($id)
	{
		$qry = $this->db->query("SELECT * FROM `products_categories_relationship` WHERE product_id = '$id'");
		$rows = array();

		if($qry){
			while($row = $this->db->fetch($qry,"array")){
				$rows[] = $row;
			}
		}

		return $rows;
	}

	function add_image_gallery($url,$product_id)
	{
		$qry = $this->db->query("INSERT INTO `products_gallery_images` (`id`, `product_id`, `image_url`) VALUES (NULL, '$product_id', '$url')");

		if($qry)
			return true;

		return false; 
	}
	function load_product_images($id)
	{

		$qry = $this->db->query("SELECT * FROM `products_gallery_images` WHERE `product_id` = '$id' ORDER BY `sort_order`");
		$rows = array();

		while($row = $this->db->fetch($qry,"array"))
			$rows[] = $row;

		return $rows;

	}
	function delete_image($id)
	{
		$qry = $this->db->query("DELETE FROM `products_gallery_images` WHERE `id` = '$id' ");

		if($qry)
			return true;

		return false;
	}

	function addCategory_single($category_name)
	{
		$url_slug = get_url($category_name, 'product_categories');
		$qry = $this->db->query("INSERT INTO `product_categories` (`id`, `category_name`, `category_parent`, `category_description`,`url_slug`) VALUES (NULL, '$category_name',0,'','$url_slug')");

		if($qry)
			return "1";

		return "0";

	}

	function addCategory_multiple($category_name, $arr){
		/*$parents = explode(',', $arr);*/
		$err = false;
		foreach ($arr as $key => $parent) {
			$url_slug = get_url($category_name, 'product_categories');
			$qry = $this->db->query("INSERT INTO `product_categories` (`id`, `category_name`, `category_parent`, `category_description`,`url_slug`) VALUES (NULL, '$category_name','$parent','','$url_slug')");

			if(!$qry)
				$err = true;
		}

		if(!$err)
			return "1";

		return "0";
	}

	function get_products_details($fields=array()){
		$sql = "SELECT * FROM `products`";
		if (count($fields)) {
			foreach ($fields as $key => $value) {
				$fields[$key] = "`{$value}`";
			}
			$field_str = implode(",", $fields);

			$sql = "SELECT {$field_str} FROM `products`";
		}

		$qry = $this->db->query($sql);
		$rows = array();

		if($qry){
			while($row = $this->db->fetch($qry, 'array')){
				$rows[] = $row;
			}
		}

		return $rows;
	}

	function get_products_categories($fields=array()){
		if (count($fields)) {
			foreach ($fields as $key => $value) {
				$fields[$key] = "`{$value}`";
			}
			$field_str = implode(",", $fields);

			$sql = "SELECT {$field_str} FROM `product_categories`";
		}

		$qry = $this->db->query($sql);
		$rows = array();

		if($qry){
			while($row = $this->db->fetch($qry, 'array')){
				$rows[] = $row;
			}
		}

		return $rows;
	}

	function add_product_attributes($data,$product_id){
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$qry = $this->db->query("INSERT INTO `product_attributes`(`product_id`, `label`, `is_color_selection`, `required`) VALUES 
					('$product_id','$value->label','$value->is_color_selection','$value->required')");
				$qry_id = $this->db->query("SELECT * FROM `product_attributes` ORDER BY `id` DESC LIMIT 1");
				$row = $this->db->fetch($qry_id,'array');
				$product_attribute_id = $row['id'];

				foreach ($value->product_attribute_selection as $key => $product_attr) {
					$qry_add_selection = $this->db->query("INSERT INTO `product_attribute_selection`(`label`,`product_attribute_id`, `price`, `item_on_sale`, `sale_price`, `calculate_shipping_fee`, `shipping_fee`, `track_inventory`, `delivery_method`) VALUES 
						('$product_attr->label','$product_attribute_id','$product_attr->price','$product_attr->item_on_sale','$product_attr->sale_price','$product_attr->calculate_shipping_fee','$product_attr->shipping_fee','$product_attr->track_inventory','$product_attr->delivery_method')");
				}
			}
		}
	}

	function get_product_attributes($product_id){
		$qry = $this->db->query("SELECT * FROM `product_attributes` WHERE `product_id` = '$product_id' ");
		$rows = array();
		while($row = $this->db->fetch($qry, 'array')){
			$rows_selection = array();
			$id = $row['id'];

			$qry_selection = $this->db->query("SELECT * FROM `product_attribute_selection` WHERE `product_attribute_id` = '$id'");

			while($row_sle = $this->db->fetch($qry_selection, 'array')){
				$rows_selection[] = $row_sle;
			}

			$row['product_attributes_selection'] = $rows_selection;

			$rows[] = $row;
		}

		return $rows;
	}

	function delete_product_attributes($p_id){
		$qry = $this->db->query("SELECT * FROM `product_attributes` WHERE `product_id` = '$p_id' ");

		while($row = $this->db->fetch($qry, 'array')){
			$id = $row['id'];
			$this->db->query("DELETE FROM `product_attribute_selection` WHERE `product_attribute_id` = '$id'");
		}

		$this->db->query("DELETE FROM `product_attributes` WHERE `product_id` = '$p_id' ");

	}

	function add_product_appointments($datas, $product_id){
		if(!empty($datas)){
			foreach ($datas as $key => $data) {
				$date_from = get_date($data->date_from);
				$date_to = get_date($data->date_to);
				$qry = $this->db->query("INSERT INTO `product_appointments`(`product_id`, `date_from`, `date_to`, `spot`, `sort_index`) VALUES ('$product_id','$date_from','$date_to','$data->spot','$key')");
			}
		}
	}

	function get_product_appointments($product_id){
		$qry = $this->db->query("SELECT * FROM `product_appointments` WHERE `product_id` = '$product_id'");
		$rows = array();

		if($qry){
			while($row = $this->db->fetch($qry, 'array')){
				$date_f = date_create($row['date_from']);
				$date_t = date_create($row['date_to']);

				$rows[] = array('id' => $row['id'], 'date_from' => date_format($date_f,"d/m/Y"), 'date_to' => date_format($date_t,"d/m/Y"), 'spot' => $row['spot']);
			}
		}

		return $rows;
	}

	function delete_product_appointments($product_id){
		$this->db->query("DELETE FROM `product_appointments` WHERE `product_id` = '$product_id'");
	}

	function get_available_slug($slug, $lang='', $id=0){
		$rows = array();

		$slug = remove_accents($slug);
		$final_slug = $slug;
		$products = [];

		/* Get Slugs*/
		$sql = "select distinct(url_slug) url_slug, type from (
	    SELECT url_slug, 'product' type FROM products Where `id` <> '{$id}'
	    UNION SELECT url_slug, 'product-category' type from product_categories 
	    UNION select url_slug, post_type type from cms_posts 
	    UNION SELECT url_slug, 'category' type FROM post_category
	    UNION select url_slug, concat('translated-', post_type) type from cms_posts_translate ) t GROUP BY url_slug, type order by url_slug";
		foreach ($this->db->select( $sql ) as $key => $value) {
			$products[] = $value->url_slug;
		}
		$sql = "Select * From `cms_translation` Where type IN ('product-category','product') and guid <> '{$id}'";
		foreach ($this->db->select( $sql ) as $key => $value) {
			if ($value->type == 'product-category') {
				$jdata = json_decode($value->meta);
				if ($jdata->url_slug) {
					$products[] = $jdata->url_slug;
				}
			}
			if ($value->type == 'product') {
				$jdata = json_decode($value->meta);
				if (isset($jdata->product->url_slug)) {
					$products[] = $jdata->product->url_slug;
				}
			}
		}

		$ctr = 1;

		while (in_array($final_slug, $products)) {
			$final_slug = "{$slug}-{$ctr}";
		}


		$uc = new UC();

		$res_lang = get_reserved_language();
		$prod_url = get_system_option('product_url' . ($res_lang != $lang ? '_'.$lang : ''));
		$url = ($prod_url!=''?$prod_url:'products')."/{$final_slug}";

		$uc_url = trim(get_system_option('site_url'),'/').'/'.trim($lang,'/'). '/' . trim($url,'/') . '/';
		$url_system_info = $uc->uc_get_current_url_settings($uc_url);

		$p = $url_system_info['site_url_info']['protocol'];
		// $w = $url_system_info['site_url_info']['has_www'] ? 'www.' : '';
		$q = $url_system_info['current_url_info']['query'] != '' ? '?' . $url_system_info['current_url_info']['query'] : '';
		$l = $url_system_info['language']['is_default'] ? '' : $url_system_info['language']['slug_selected'] . ($url != '' ? '/' : '');
		$s = $url_system_info['site_url_info']['has_slash'] ? '/' : '';
		$h = $url_system_info['site_url_info']['host'] . ("{$l}{$url}" != "" ? "/" : "");

		$u = "{$p}://{$h}{$l}{$url}{$s}{$q}";

		$rows['slug'] = $final_slug;
		$rows['permalink'] = $u;
		/*$rows['slug'] = get_url($slug,'products');*/
		return $rows;
	}

	function get_product_gallery_image_by_id($id){
		$qry = $this->db->query("SELECT * FROM `products_gallery_images` WHERE `id` = '$id'");

		return $this->db->fetch($qry, 'array');
	}

	function sort_product_image($datas){
		$ret = true;
		foreach ($datas as $key => $data){
			$id = $data['id'];
			$sort_order = $data['index'];

			if(!$this->db->query("UPDATE `products_gallery_images` SET `sort_order`= '$sort_order' WHERE `id` = '$id' "));
			$ret = false;
		}

		return $ret;
	}

	function add_additional_files($id,$url,$name,$index){
		$this->db->query("INSERT INTO `product_addtiontal_files`(`product_id`,`name`, `src`,`sort_index`) VALUES ('$id','$name','$url','$index')");
	}

	function get_additional_files($id){
		$rows = array();
		$qry = $this->db->query("SELECT * FROM `product_addtiontal_files` WHERE `product_id` = '$id' AND `status` <> 'trashed' ORDER BY `sort_index`");
		while($row = $this->db->fetch($qry, 'array'))
			$rows[] = $row;

		return $rows;
	}
	function get_files_url($id){
		$qry = $this->db->query("SELECT * FROM `product_addtiontal_files` WHERE `id` = '$id' AND `status` <> 'trashed'");
		$row = $this->db->fetch($qry,'array');

		return FRONTEND_ROOT.'/'.$row['src'];
	}
	function delete_additional_files($id){
		return $this->db->query("UPDATE `product_addtiontal_files` SET `status`= 'trashed' WHERE `id` = '$id' ");
	}
	function get_additional_name($name,$id){
		$sql = "SELECT * FROM `product_addtiontal_files` WHERE `name` = '$name' AND `product_id` = '$id'";
		$qry = $this->db->query($sql);
		$count = $this->db->numRows($qry);
		if($count!=0){
			$count++;
			$extension = pathinfo($name, PATHINFO_EXTENSION);
			return $name.'('.$count.')'.'.'.$extension;
			/*return $count;*/
		}
		return $name;
	}
	function get_sort_index_for_additional_files($id){
		$sql = "SELECT * FROM `product_addtiontal_files` WHERE `product_id` = '$id'";
		$qry = $this->db->query($sql);
		$count = $this->db->numRows($qry);
		if($count!=0)
			return $count++;
		return 0;
	}

	function sort_additional_files($data){
		foreach ($data as $key => $id) {
			$this->db->query("UPDATE `product_addtiontal_files` SET `sort_index` = '$key' WHERE `id` = $id");
		}
	}

	function save_product_option($product_options = array(), $product_option_items = array(), $product_id = 0){
		$current_product_options_data = $this->get_current_product_options($product_id);
		$current_product_options_results = $current_product_options_data['product_option'];
		$current_product_items_results = $current_product_options_data['product_items'];

		$ouput_data = array();

		$this->db->table = "product_option_items";
		foreach ($product_option_items as $key => $value) {
			$data = array(
				"id" => isset($value->id) ? $value->id : 0,
				"product_id" => $product_id,
				"product_option_value_labes" => $value->label,
				"product_option_sku" => $value->sku,
				"product_option_quantity" => $value->qty,
				"product_option_price" => $value->price,
				"product_option_enable" => $value->enable,
				);

			foreach ($current_product_items_results as $kk => $vv) {
				if ($vv->product_option_value_labes == $value->label ){
					$data['id'] = $vv->id;
					unset($current_product_items_results[$kk]);
					break;
				}
			}

			if (isset($data['id']) && $data['id'] != 0) {
				$this->update($data);
			}else{
				unset($data['id']);
				$data['id'] = $this->save($data);
			}

			$ouput_data['product_option_items'][] = $data;
		}

		if (count($current_product_items_results)) {
			$todelete = array();
			foreach ($current_product_items_results as $key => $value) {
				$todelete[] = "'{$value->id}'";
			}
			$todelete_str = implode(",", $todelete);
			$this->db->query("Delete From `product_option_items` Where `id` IN ({$todelete_str})");
		}

		$current_product_options = array();

		foreach ($current_product_options_results as $key => $value) {
			$current_product_options[] = $value['detail']->product_option_name;
		}

		foreach ($product_options as $key => $value) {
			$product_option_id = 0;
			$product_option_current_values = array();

			if (in_array($key, $current_product_options)) {
				foreach ($current_product_options_results as $key_2 => $value_2) {
					if ($value_2['detail']->product_option_name == $key) {
						$product_option_id = $value_2['detail']->id;
						$product_option_current_values = $value_2['values'];
						unset($current_product_options_results[$key_2]);
					}
				}
			}else{
				$product_option_data = array(
					"product_id" => $product_id,
					"product_option_name" => $key,
					);

				$this->db->table = "product_option";
				$product_option_id = $this->save($product_option_data);
			}

			$ouput_data['product_option'][$key] = $value;

			foreach ($value as $key_2 => $value_2) {
				$option_value_id = 0;
				foreach ($product_option_current_values as $key_3 => $value_3) {
					if ($value_3->product_option_values == $value_2) {
						$option_value_id = $value_3->id;
						unset($product_option_current_values[$key_3]);
						break;
					}
				}

				$this->db->table = "product_option_values";

				if ($option_value_id != 0) {
					$product_option_value = array(
						"id" => $option_value_id,
						"product_option_id" => $product_option_id,
						"product_option_values" => $value_2,
						);
					$product_option_value_id = $this->update($product_option_value);
				}else{
					$product_option_value = array(
						"product_option_id" => $product_option_id,
						"product_option_values" => $value_2,
						);
					$product_option_value_id = $this->save($product_option_value);
				}
			}

			foreach ($product_option_current_values as $key => $value) {
				$this->db->query("Delete From `product_option_values` Where `id` = '{$value->id}'");
			}
		}

		foreach ($current_product_options_results as $key => $value) {
			$this->db->query("Delete From `product_option` Where `id` = '{$value['detail']->id}'");
		}

		return $ouput_data;
	}
	function get_product_options($product_id = 0){
		return $this->get_current_product_options($product_id);
	}
	function get_current_product_options($product_id=0){
		$current_product_options = array();

		$product_options = $this->db->select("Select * From `product_option` Where `product_id` = '{$product_id}'");

		$product_option_values = array();

		if (count($product_options) > 0) {
			foreach ($product_options as $key => $value) {
				$product_option_values[$value->id]['detail'] = $value;
				$product_option_values[$value->id]['values'] = $product_values = $this->db->select("Select * From `product_option_values` Where `product_option_id` = '{$value->id}'");
			}
		}
		$current_product_options['product_option'] = $product_option_values;

		$product_items = $this->db->select("Select * From `product_option_items` Where `product_id` = '{$product_id}'");
		$current_product_options['product_items'] = $product_items;

		return $current_product_options;
	}
	function get_number($d = ''){
		return $d != '' ? $d : 0;
	}
}	


