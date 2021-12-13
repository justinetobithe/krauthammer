<?php

class ProductCategory extends Controller {

	function __construct() {
		parent::__construct();
		Session::set('ispage', 'product-category');
	}

	function index($url = "") {
		$products = $this->model->db->select("Select * From `products` `p1` Order By `p1`.`id` Asc");

		$data = array(
			"product" => $products,
			"product_categories" => ecatalog_get_categories(),
			"categories" => ecatalog_get_categories(),
			"latest_products" => ecatalog_get_latest_products(),
			"related_products" => ecatalog_get_related_products(),
			"product_tabs" => ecatalog_get_product_tabs(),
			);

		EcommerceManager::product_category_page( $data );
	}

	function __other($url = "") {
		$cat = end($url);
		$lang = cms_get_language();
		$sql = "Select `product_categories`.*, t.meta translate From `product_categories` Left Join `cms_translation` `t` ON t.guid = product_categories.id and language='{$lang}' and `type` = 'product-category' Where `url_slug` = '{$cat}'";
		$current_category = $this->model->db->select($sql);
		$current_category = count($current_category) ? $current_category[0] : array();

		if ($lang != cms_get_language_reserved()) {
			$sql = "SELECT * FROM cms_translation WHERE type = 'product-category' and language = '{$lang}'";
			$pcd = $this->model->db->select($sql);

			foreach ($pcd as $key => $value) {
				$j = json_decode($value->meta);
				if (isset($j->url_slug) && $j->url_slug == $cat) {
					$sql = "Select `product_categories`.*, t.meta translate From `product_categories` Left Join `cms_translation` `t` ON t.guid = product_categories.id and language='{$lang}' and `type` = 'product-category' Where `product_categories`.`id` = '{$value->guid}' ";
					
					$pcd_temp = $this->model->db->select( $sql );
					if (count($pcd_temp)) {
						$current_category = $pcd_temp[0];
						break;
					}
				}
			}
		}

		if (count($current_category)) {
			$t = json_decode($current_category->translate);

			if (isset($t->category_name)) {
				$current_category->category_name = $t->category_name;
			}
			if (isset($t->category_description)) {
				$current_category->category_description = $t->category_description;
			}
		}

		$sql = "Select `p1`.* From `products` `p1` Inner Join `products_categories_relationship` `p2` On `p1`.`id` = `p2`.`product_id` Inner Join `product_categories` `p3` On `p3`.`id` = `p2`.`category_id` Where `p3`.`url_slug` = '{$cat}' Order By `p1`.`id` Asc";
		$product = $this->model->db->select($sql);

		$data = array(
			"product" => $product,
			/*"product_categories" => ecatalog_get_categories( array("product_id" => $product[0]->id) ),*/
			"current_category" => $current_category,
			"categories" => ecatalog_get_categories(),
			"latest_products" => ecatalog_get_latest_products(),
			"related_products" => ecatalog_get_related_products(),
			"product_tabs" => ecatalog_get_product_tabs(),
			);

		EcommerceManager::product_category_page( $data );
	}
}
