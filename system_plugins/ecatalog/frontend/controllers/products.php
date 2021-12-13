<?php

class Products extends Controller {

	function __construct() {
		parent::__construct();
		Session::set('ispage', 'product-detail');
	}
	
	function index($url = "") {
		$product = ecatalog_get_products();
		$url_slug = isset($product[0]['url_slug']) ? $product[0]['url_slug'] : "";

		/*Enable redirect to first product*/
		$product_redirect = false;
		if ($product_redirect) {
			redirect(URL . "products/{$url_slug}");
		}else{
			// $this->view->error();
			$this->render_page("", $product_redirect);
		}
	}

	function __other($url = "") {
		$this->render_page($url[1]);
	}

	private function render_page($url_slug = "", $first_product_as_default = true){
		if ($first_product_as_default) {
			cms_reset_query();
			ecatalog_query_products('url_slug='. $url_slug);
			if (ecatalog_have_products()) {
				ecatalog_the_product();

				$product = ecatalog_get_products(array('url_slug' => $url_slug, 'posts_per_page' => 1));
				$product = count($product) == 1 ? $product[0] : $product;

				$data = array(
					"product" => $product,
					"product_categories" => ecatalog_get_categories( array("product_id" => $product['id']) ),
					"categories" => ecatalog_get_categories(),
					"latest_products" => ecatalog_get_latest_products(),
					"related_products" => ecatalog_get_related_products(),
					"product_tabs" => ecatalog_get_product_tabs(),
					"custom_fields" => ecatalog_get_product_custom_fields(),
					);

				$submitted_product = $this->model->check_submit(); /*check if there are data being submitted.*/
				if($submitted_product != null) $data["submitted_product"] = $submitted_product;

				EcommerceManager::product_page( $data );
			} else{
				$this->view->error();
			}
		}else{
			EcommerceManager::shop_page();
		}
	}
}
