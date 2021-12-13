<?php
class Products_Model extends Model{
	public function __construct(){
		parent::__construct();
	}

	public function check_submit(){
		if (isPost('product-submit')) {
			return $this->process_submittion();
		}
		return null;
	}
	public function process_submittion(){
		$product = ecatalog_get_product(array("id" => $_POST['product-id']));
		if (count($product)) {
			ecatalog_cart_add_product($product, $_POST['product-quantity']);
			return array("product" => $product, "quantity" => $_POST['product-quantity']);
		}
		return null;
	}
}