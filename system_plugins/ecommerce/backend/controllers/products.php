<?php
if (!class_exists('XMLSitemap')) {
	include ROOT . "libraries/plugins/sitemap/sitemap.php";
}
class Products extends Controller{
	function __construct(){
		Session::handleLogin();
		parent::__construct();
	}
	function index(){
		$product_categories = $this->model->get_products_categories(array('id', 'category_name'));
		$this->view->set('product_categories', $product_categories);
		$this->view->render('header');
		$this->view->render('products/index');
		$this->view->render('footer');
	}
	function edit($product_id=""){
		set_module_sub_title("Edit");
		$this->view->setStyleFiles(array("products-edit")); /* Adding Style on Adming page*/
		$this->view->setScriptFiles(array("products-custom-fields", "products-custom-fields-composer", "products-billing-period")); /* Adding Style on Adming page*/
		if($product_id == "" || $product_id < 0 || !ctype_digit($product_id) ){
			header('location:'.URL.'products/');
		}
		else{
			$product =  $this->model->loadProductsByID($product_id);
			$pro_exist = $this->model->checkProductExistence($product_id);
			/*$products_attributes  = $this->model->get_product_attributes($product_id);*/
			$products_appointments = $this->model->get_product_appointments($product_id);
			/*print_r($products_attributes);*/
			if($product['product_status'] == "trashed")
				header('location:'.URL.'products/');
			else if($pro_exist < 1)
				header('location:'.URL.'products/');
			else
			{
				$this->view->set('products',$product);

				$additional_files = $this->model->get_additional_files($product_id);
				$this->view->set('additional_files',$additional_files);

				$product_categories = $this->model->loadProductCategories();
				$this->view->set('product_categories',$product_categories);

				$selected_categories = $this->model->loadSelectedCategories($product_id);
				$select= '';

				$product_tabs = $this->model->get_product_tabs($product_id);
				$this->view->set('product_tabs',$product_tabs);
				foreach ($selected_categories as $key) {
					$select.= $key['category_id'].",";
				}

				$product_brand = $this->model->db->select("Select * From `product_brands` Where `id` = '{$product['product_brand_id']}'");
				$product_brand = count($product_brand) ? $product_brand[0] : array();

				/*$this->view->set('products_attributes', $products_attributes);*/
				$this->view->set('selected_categories',$select);
				$this->view->set('appointments', $products_appointments);
				$this->view->set('product_brand', $product_brand);
				$this->view->set('languages', $this->get_languages());

				/* Added Subscription detail */
				$subs_temp = $this->billing_period_gateway_setting();

				$this->view->set('subscription_setting', $subs_temp);

				$this->view->render('header');
				$this->view->render('products/edit');
				$this->view->render('footer');
			}
		}
	}
	function add(){
		Session::handleLogin();
		set_module_sub_title("Add");
		$this->view->setStyleFiles(array("products-add")); /* Adding Style on Adming page*/
		$this->view->setScriptFiles(array("products-custom-fields", "products-custom-fields-composer")); /* Adding Style on Adming page*/
		
		$product_categories = $this->model->loadProductCategories();
		$this->view->set('product_categories',$product_categories);
		$this->view->set('languages', $this->get_languages());

		$this->view->render('header');
		$this->view->render('products/add');
		$this->view->render('footer');
		/*$this->model->trythis()*/
	}
	function addProduct(){   
		if($_POST){
			$result = true; 

			$image_name = ''; /*#PRODUCT IMAGE*/
			$image_tmp;
			$upload_path;
			$p_id = 0; /*FOR IMAGE GALLERY*/

			if(!empty($_FILES['image_file'])) {

				$image_name =  seoUrl($_FILES['image_file']['name']); /*lowercase filename*/
				$image_name = uniqid().$image_name;
				$image_tmp = $_FILES['image_file']['tmp_name'];/*temp path*/
				$upload_path = "../images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/"; /*check folder*/
				$upload = "/images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/";
				if(!is_dir($upload_path)){
					$path = "../images/uploads/";
					$date = date('Y/m/d')."/".$image_name;

					if(!mkdir($path . '/' . $date, 0755, TRUE)){

					}
				}
			} 

			/*PRODUCT DETAILS*/
			$product_name = $_POST['product_name'];
			$product_description = $_POST['product_description'];
			$product_category = $_POST['product_category'];

			/*LIST DETAILS*/
			$featured_product = $_POST['featured_product'];
			$product_price = $_POST['product_price'];
			$product_sku = $_POST['product_sku'];
			$product_qty = $_POST['product_qty'];
			if($product_qty == '')
				$product_qty = -1;
			$product_min_order_qty = $_POST['product_min_order_qty'];
			$product_max_order_qty = $_POST['product_max_order_qty'];
			$product_interval = $_POST['product_interval'];
			$product_qty_label = $_POST['product_qty_label'];
			$product_stock = $_POST['product_stock'];
			$product_status = $_POST['submit'];
			$product_tabs = '';

			/*$product_tabs = explode('!2/!/s202,',$_POST['product_tabs']);*/
			$product_tabs = json_decode($_POST['product_tabs']);

			$seo_title = post('seo_title');
			if($seo_title == '')
				$seo_title = $product_name;

			$seo_description = post('seo_description');
			$seo_no_index = post('seo_no_index');
			$track_inventory = 'NO';
			$recommended_for_checkout = $_POST['recommended_checkout'];
			$url_slug = post('url_slug');
			$status = post('status');

			$product_brand_id = post('selected_brand_id') != "" && post('selected_brand_id') != "0" ? post('selected_brand_id'):"0";

			if(isset($_POST['track_inventory']))
				$track_inventory = $_POST['track_inventory'];

			if($product_status == "Draft")
				$product_status = "trashed";
			else
				$product_status = "active";

			/*ADDING PRODUCT*/
			if($image_name != ''){
				$imgurl = "";
				if (is_valid_image_type($_FILES['image_file'])) {
					if(move_uploaded_file($image_tmp, $upload_path . $image_name)){
						$imgurl = $upload.$image_name;
					}
				}
				$p_id = $this->model->addProduct(array(
					"upload_path"=>$imgurl,
					"product_name"=>$product_name,
					"product_description"=>$product_description,
					"product_category"=>$product_category,
					"featured_product"=>$featured_product,
					"product_price"=>$product_price,
					"product_sku"=>$product_sku,
					"product_qty"=>$product_qty,
					"product_min_order_qty"=>$product_min_order_qty,
					"product_max_order_qty"=>$product_max_order_qty,
					"product_interval"=>$product_interval,
					"product_qty_label"=>$product_qty_label,
					"product_stock"=>$product_stock,
					"product_status"=>$product_status,
					"product_tabs"=>$product_tabs,
					"seo_title"=>$seo_title,
					"seo_description"=>$seo_description,
					"seo_no_index"=>$seo_no_index,
					"track_inventory"=>$track_inventory,
					"recommended_for_checkout"=>$recommended_for_checkout,
					"url_slug"=>$url_slug,
					"status"=>$status,
					"product_brand_id"=>$product_brand_id,
					));
			}else{
				$image_name = uniqid().generateRandomString(10);
				$p_id = $this->model->addProduct(array(
					"upload_path"=>"",
					"product_name"=>$product_name,
					"product_description"=>$product_description,
					"product_category"=>$product_category,
					"featured_product"=>$featured_product,
					"product_price"=>$product_price,
					"product_sku"=>$product_sku,
					"product_qty"=>$product_qty,
					"product_min_order_qty"=>$product_min_order_qty,
					"product_max_order_qty"=>$product_max_order_qty,
					"product_interval"=>$product_interval,
					"product_qty_label"=>$product_qty_label,
					"product_stock"=>$product_stock,
					"product_status"=>$product_status,
					"product_tabs"=>$product_tabs,
					"seo_title"=>$seo_title,
					"seo_description"=>$seo_description,
					"seo_no_index"=>$seo_no_index,
					"track_inventory"=>$track_inventory,
					"recommended_for_checkout"=>$recommended_for_checkout,
					"url_slug"=>$url_slug,
					"status"=>$status,
					"product_brand_id"=>$product_brand_id,
					));
			}

			if(!empty($_FILES['additional_files_input'])){
				$temp_upload_path_addtional_files = "../images/uploads/".date('Y/m/d')."/additional_files/".$p_id.'/';
				$upload_path_addtional_files = "images/uploads/".date('Y/m/d')."/additional_files/".$p_id;
				if(!is_dir($temp_upload_path_addtional_files)){
					if(!mkdir($temp_upload_path_addtional_files, 0755, TRUE)){

					}
				}

				foreach($_FILES['additional_files_input']['name'] as $i => $name) {
					$f_name = $name;
					$name = uniqid().seoUrl($name);
					$tmp =  $_FILES['additional_files_input']['tmp_name'][$i];
					if (is_valid_image_type($tmp)) {
						if(move_uploaded_file($tmp, $temp_upload_path_addtional_files.$name)){
							$this->model->add_additional_files($p_id, $upload_path_addtional_files.'/'.$name,$f_name,$i);
						}
					}

				}
			}

			/*FOR GALLERY IMAGES ONLY*/
			$this->model->add_product_appointments(json_decode($_POST['hidden_product_appointments']), $p_id);

			if (get_system_option('product_no_index_detail_page') != "Y") {
				/*UPDATING SITEMAP*/
				$sitemap = new XMLSitemap();
				$sitemap->update();
			}

			$results = array();
			$results['image_name'] = $image_name;
			$results['product_id'] = $p_id;

			if(!$result)
				$results['status'] = 0;
			else
				$results['status'] = 1;

			echo json_encode($results);
		}
		else{
			header('location:'.URL.'products/');
		}
	}
	function updateProduct(){
		if($_POST){
			/*PRODUCT IMAGE*/
			$result = true;
			$image_name = 'nothing';
			$image_tmp = '';
			$upload_path = '';
			$p_id = 0; /*IMAGE GALLERY*/

			if(!empty($_FILES['image_file'])) {
				$image_name =  seoUrl($_FILES['image_file']['name']); /*lowercase filename*/
				$image_name = uniqid().$image_name;
				$image_tmp = $_FILES['image_file']['tmp_name']; /*temp path*/
				$upload_path = "../images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/"; /*check folder*/
				$upload = "/images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/";
				if(!is_dir($upload_path)){
					$path = "../images/uploads/";
					$date = date('Y/m/d')."/".$image_name;
					if(!mkdir($path . '/' . $date, 0755, TRUE)){
					}
				}
			} 
			/*PRODUCT DETAILS*/
			$product_id = $_POST['product_id'];
			$product_name = $_POST['product_name'];
			$product_description = $_POST['product_description'];
			$product_category = $_POST['product_category'];

			/*LIST DETAILS*/
			$featured_product = $_POST['featured_product'];
			$product_price = $_POST['product_price'];
			$product_sku = $_POST['product_sku'];
			$product_qty = $_POST['product_qty'];
			if($product_qty == ''){
				$product_qty = -1;
			}
			$product_min_order_qty = $_POST['product_min_order_qty'];
			$product_max_order_qty = $_POST['product_max_order_qty'];
			$product_interval = $_POST['product_interval'];
			$product_qty_label = $_POST['product_qty_label'];
			$product_stock = $_POST['product_stock'];
			$product_status = $_POST['submit'];

			/*$product_tabs = explode("!2/!/s202,", $_POST['product_tabs']);*/
			$product_tabs = json_decode($_POST['product_tabs']);
			$seo_title = post('seo_title');
			if($seo_title == ''){
				$seo_title = $product_name;
			}
			$seo_description = post('seo_description');
			$seo_no_index = post('seo_no_index');
			$url_slug = post('url_slug');
			$status = post('status');

			$product_brand_id = post('selected_brand_id');

			$track_inventory = 'NO';
			if(isset($_POST['track_inventory'])){
				$track_inventory = $_POST['track_inventory'];
			}

			if($product_status == "Draft"){
				$product_status = "trashed";
			}else{
				$product_status = "active";
			}

			$old_slug = post('old_slug');

			if($old_slug == $url_slug){
				$old_slug = '';
			}

			/*UPDATE PRODUCT*/
			$update = false;
			$img_name = "";
			if($image_name != 'nothing'){
				if (is_valid_image_type($_FILES['image_file'])) {
					if(move_uploaded_file($image_tmp, $upload_path . $image_name)){
						$img_name = $upload.$image_name;
					}
				}
			}

			$update = $this->model->updateProduct(array(
				'product_id' => $product_id,
				'upload_path' => $img_name,
				'product_name' => $product_name,
				'product_description' => $product_description,
				'product_category' => $product_category,
				'featured_product' => $featured_product,
				'product_price' => $product_price,
				'product_sku' => $product_sku,
				'product_qty' => $product_qty,
				'product_min_order_qty' => $product_min_order_qty,
				'product_max_order_qty' => $product_max_order_qty,
				'product_interval' => $product_interval,
				'product_qty_label' => $product_qty_label,
				'product_stock' => $product_stock,
				'product_status' => $product_status,
				'product_tabs' => $product_tabs,
				'seo_title' => $seo_title,
				'seo_description' => $seo_description,
				'seo_no_index' => $seo_no_index,
				'track_inventory' => $track_inventory,
				'url_slug' => $url_slug,
				'old_slug' => $old_slug,
				'status' => $status, 
				'product_brand_id' => $product_brand_id,
				));

			if($update){
				$p_id = 1;
			}

			if(!empty($_FILES['additional_files_input'])){
				$temp_upload_path_addtional_files = "../images/uploads/".date('Y/m/d')."/additional_files/".$product_id.'/';
				$upload_path_addtional_files = "images/uploads/".date('Y/m/d')."/additional_files/".$product_id;
				if(!is_dir($temp_upload_path_addtional_files)){
					if(!mkdir($temp_upload_path_addtional_files, 0755, TRUE)){

					}
				}
				$sort_index = $sort_index = $this->model->get_sort_index_for_additional_files($product_id);
				foreach($_FILES['additional_files_input']['name'] as $i => $name) {
					$f_name = $name;
					/* $f_name = $this->model->get_additional_name(basename($name),$product_id);*/
					$name = uniqid().seoUrl($name);
					$tmp =  $_FILES['additional_files_input']['tmp_name'][$i];
					if (is_valid_image_fild($tmp)) {
						if(move_uploaded_file($tmp, $temp_upload_path_addtional_files.$name)){
							$this->model->add_additional_files($product_id, $upload_path_addtional_files.'/'.$name,$f_name, $sort_index);
						}
						$sort_index++;
					}
				}
			}


			$this->model->delete_product_appointments($product_id);
			$this->model->add_product_appointments(json_decode($_POST['hidden_product_appointments']), $product_id);

			if (get_system_option('product_no_index_detail_page') != "Y") {
				/*Updating Sitemap*/
				$sitemap = new XMLSitemap();
				$sitemap->update();
			}

			if(!$result){
				echo "0";
			}else{
				echo "1";
			}

		}
		else{
			header('location:'.URL.'products/');
		}
	}
	function getLastID(){
		if(hasPost('action', 'getLastID'))
			echo json_encode($this->model->getLastID());
		else
			header('location:'.URL.'products/');
	}
	function getProducts(){
		$output = array(
			"sEcho" => 1,
			"iTotalRecords" => 1,
			"iTotalDisplayRecords" => 1,
			"aaData" => array()
			);

		$product_category = '';
		if (isset($_GET['product_category']) && $_GET['product_category'] != 'all') {
			$product_category = " and `pc`.`id` = '{$_GET['product_category']}'";
		}

		$sql = "SELECT p.*, GROUP_CONCAT(pc.category_name) category_name FROM `products` p LEFT JOIN `products_categories_relationship` pcr ON p.id = pcr.product_id LEFT JOIN `product_categories` pc ON pcr.category_id = pc.id WHERE p.product_status <> 'trashed' {$product_category} group by p.id DESC";

		$columns = array(
			'id',
			'featured_image_url',
			'product_name',
			'price',
			'quantity',
			'sku',
			'featured_product',
			'category_name',
			);

		$output = datatable_processor($columns, "id", "", $sql);

		foreach($output['aaData'] as $kk=>$vv){
			$categories = $this->model->getCategory($vv[0]);
			$cat_ = '';
			foreach ($categories as $key => $category) {
				$cat_ .= $category['category_name'].'<br>';
			}
			$image_url = '';
			$feature_product = '';
			$product_viewed = '-';
			$product_image_thumbnails = '';
			if($vv[1] == ''){
				$image_url = FRONTEND_URL.'/images/uploads/default.png';
				$product_image_thumbnails = FRONTEND_URL.'/thumbnails/84x73/uploads/default.png';
			}
			else{
				$product_image_thumbnails = FRONTEND_URL.str_replace('/images/', '/thumbnails/84x73/', $vv[1]);
				$image_url = FRONTEND_URL.$vv[1];
			}

			if($vv[6] == 'yes')
				$feature_product = 'icon-check green bigger-150';
			else
				$feature_product = 'icon-minus red bigger-150';

			if($vv[4] < 0)
				$qty = 0;
			else
				$qty = $vv[4];

			$output['aaData'][$kk][0] = '<label><input type="checkbox" class="item-checkbox"><span class="lbl"></span></label>';
			$output['aaData'][$kk][1] = '<ul class="ace-thumbnails"><li><a href="'.$image_url.'" data-rel="colorbox"><img src="'.$product_image_thumbnails.'" /><div class="text"><div class="inner">Click to see full image</div></div></a></li></ul>';
			$output['aaData'][$kk][2] = '<div><h5 class="light blue"><a href="'.URL.'products/edit/'.$vv[0].'">'.$vv[2].'</a></h5><a href="'.URL.'products/edit/'.$vv[0].'" class="light blue">Edit</a> | <a href="javascript:void(0)" class="red" onclick ="deleteProductModal('.$vv[0].')">Delete</a> | <a href="'.URL.'products/edit/'.$vv[0].'"class="light blue">Preview</a> </div></div>';
			$output['aaData'][$kk][4] = $qty;
			$output['aaData'][$kk][6] = '<i class="'.$feature_product.'"></i>';
		}

		echo json_encode($output);

	}
	function getCategory(){
		if(hasPost('action', 'getCategory'))
		{
			$id = post('id');
			echo json_encode($this->model->getCategory($id));
		}
		else
			header('location:'.URL.'products/');
	}
	function deleteProduct(){
		if(hasPost('action','deleteProduct'))
		{
			$id = post('id');
			echo json_encode($this->model->deleteProduct($id));
		}
	}


	function addGallery(){
		/*print_r(get_declared_classes());*/
		if($_POST)
		{
			$product_id = $_POST['product_id'];

			if($product_id == '')
			{
				/*echo "null";*/
				$product_name = $_POST['product_name'];
				$product_categories = $_POST['product_categories'];

				echo $product_name. " = ".$product_categories;
			}
			else
			{

			}
		}
	}

	function deleteImages(){
		if(hasPost('action','delete_image'))
		{
			$id = post('img_id');

			if($this->model->delete_image($id))
				echo json_encode("1");
			else
				echo json_encode("0");
		}
	}

	function loadImages(){
		if(hasPost('action', 'load_images'))
		{
			$id = post('id');

			echo json_encode($this->model->load_product_images($id));
		}
	}
	function load_parent_category(){
		if(hasPost('action','load'))
		{
			echo json_encode($this->model->loadProductCategories());
		}
	}
	function addCategory(){
		if(hasPost('action','add_category_parent'))
		{
			$category_name = post('c_name');

			echo json_encode($this->model->addCategory_single($category_name));

		}
		else if(hasPost('action','add_category_children'))
		{
			$category_name = post('c_name');
			$category_parent = post('arr');

			echo json_encode($this->model->addCategory_multiple($category_name,$category_parent));
		}
	}

	function getData(){
		$categories = $this->model->loadProductCategories();
		$parent = array();
		foreach ($categories as $key => $category) {
			if($category['category_parent'] != 0){
				array_push($parent, $category['category_parent']);
			}
		}

		$haschildren = array_unique($parent);
		$sub_category = array();

		foreach ($categories as $key => $value) {
			if(in_array($value['category_parent'], $haschildren))
				$sub_category[] = $value['id'];
		}

		$categories_grouped = $this->getChild(0, $categories);

		// echo '<script>';
		// echo 'var newData = {';
		// echo $this->traceParent_3($categories_grouped);
		// echo '};';
		// echo '</script>';

		echo json_encode($this->traceParent_3($categories_grouped));
	}

	function getChild($parent_val=0, $data_list=array()){
		$temp = array();
		foreach ($data_list as $key => $value) {
			if($parent_val==$value['category_parent']){
				$temp[$value['id']]['data'] = $value; /*store id*/
				$temp[$value['id']]['children'] = $this->getChild($value['id'], $data_list); /*getChildren (if any)*/
			}
		}

		return $temp;
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
	function traceParent_3($data_list=array(), $intent=""){
		$output = array();
		foreach ($data_list as $key => $value) {
			$data = $value['data'];

			$name = $data['category_name'];
			if(strlen($name) > 30){ $name =  substr($name,0, 30).'..'; }

			$data1 = $intent . '<span data-rel="tooltip" data-placement="top" title="'.$data['category_name'].'">'.$name.'<input type="hidden" class="value" value='.$data['id'].'></span>';

			$output[$data['id']] = array(
				'name' => $data1,
				'type' => 'item',
				);

			if(count($value['children'])>0){
				$o = $this->traceParent_3($value['children'], $intent . ' - ');
				foreach ($o as $key => $value) {
					$output[$key] = $value;
				}
			}
		}
		return $output;
	}

	function __other($url=""){
		$categories = new loader();
		$root_path = $this->getRootPath() . "controllers/";
		$categories->controllerPath = $root_path;

		if($url[1] == 'categories' && empty($url[2])){
			$categories->load_controller('product-categories','ProductCategories','manage', $this->getBasePath(), $this->getRootPath());
		}
		else if($url[1] == 'categories' && $url[2] == 'add'){
			$categories->load_controller_method('product-categories','ProductCategories','add','', $this->getBasePath(), $this->getRootPath());
		}
		else if($url[1] == 'categories' && $url[2] == 'edit' && !empty($url[3])){
			if($url[3] > 0 && is_numeric($url[3]))
				$categories->load_controller_method('product-categories','ProductCategories','edit',$url[3], $this->getBasePath(), $this->getRootPath());
			else
				$categories->load_error();
		}
		else if($url[1] == 'categories' && $url[2] == 'sorting'){
			$categories->load_controller_method('product-categories','ProductCategories','sort', $this->getBasePath(), $this->getRootPath());
		}
		else{
			$categories->load_error();
		}
	}

	function get_product_sku(){

		if(hasPost('action','get')){
			echo json_encode($this->model->get_products_details());
		}
	}

	function get_available_slug(){

		if(hasPost('action','get')){
			echo json_encode($this->model->get_available_slug($_POST['slug'], isset($_POST['lang']) ? $_POST['lang'] : '', $_POST['id']));
		} 
	}
	function crop(){
		if(hasPost('action','crop')){
			echo json_encode(crop_image(post('data'),post('image')));
		} 
	}

	function get_product_gallery_image_by_id(){
		if(hasPost('action','get')){
			echo json_encode($this->model->get_product_gallery_image_by_id(post('id')));
		} 
	}
	function test(){
		require_once ROOT."libraries/plugins/cms-api/cms-paypal.php";

		$cms_paypal = new CMS_Paypal();

		$reference_number = "I-VUWM4FSKS308";

		$result = $cms_paypal->billing_agreement_suspend($reference_number);

		header_json(); print_r($result); exit();
	}
	function upload_gallery(){

		error_reporting(E_ALL | E_STRICT);
		require_once ROOT.'assets/include/php/uploadhandler.php';
		$options = array('upload_url' => $this->get_upload_url(post('image_name')), 'upload_dir' => $this->get_upload_dir(post('image_name')), 'product_id' => post('product_id_for_gallery'), 'db_url' => $this->upload_url(post('image_name')));
		$upload_handler = new UploadHandler($options);
	}
	function get_upload_url($image_name){
		return FRONTEND_URL."/images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/galleryimage/";
	}   
	function get_upload_dir($image_name){
		return FRONTEND_ROOT."/images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/galleryimage/";
	}

	function upload_url($image_name){
		return "/images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/galleryimage/";
	}

	function get_event_files($product_id=""){
		$data = $this->model->load_product_images($product_id);

		$files = array();
		foreach ($data as $key => $value) {
			$info = $this->external_file_info(FRONTEND_URL.$value['image_url']);

			$files[] = array(
				"id"=> $value['id'],
				"name"=> $info['pathinfo']['filename'],
				"size"=> 0,
				"url"=> FRONTEND_URL.$value['image_url'],
				"thumbnailUrl"=> FRONTEND_URL.str_replace('/images/', '/thumbnails/143x89/', $value['image_url']),
				"deleteUrl"=>' ',/* URL . "merchants/manage_event_file_upload?" . $info['pathinfo']['filename']*/
				"deleteType"=> "DELETE"
				);
		}

		echo json_encode(array("files"=>$files));
	}
	function external_file_info($file_url){
		return array("pathinfo" => pathinfo($file_url));
	}
	function manage_event_file_upload($para1 = ''){
		error_reporting(E_ALL | E_STRICT);
		require_once ROOT.'assets/js/plugins/jquery_upload/server/php/uploadhandler.php';

		$upload_handler = new UploadHandler(null, false);

		switch ($upload_handler->cms_get_server_var('REQUEST_METHOD')) {
			case 'OPTIONS':
			case 'HEAD':
			$upload_handler->head();
			break;
			case 'GET':
			/*$upload_handler->get(true);*/
			$event_lsiting_id = $para1;
			$this->get_event_files($event_lsiting_id);
			break;
			case 'PATCH':
			case 'PUT':
			case 'POST':
			if($para1 == 0)
				$event_lsiting_id = post('product_id_for_gallery');
			else
				$event_lsiting_id = $para1;
			$options = array('upload_url' => $this->get_upload_url(post('image_name')), 'upload_dir' => $this->get_upload_dir(post('image_name')), 'product_id' =>$event_lsiting_id , 'db_url' => $this->upload_url(post('image_name')));
			$upload_handler = new UploadHandler($options);
			break;
			case 'DELETE':
			$selected_photo_id = get('photo_id');
			$selected_photo_name = get('photo_name');

			$sql = "Select * From `products_gallery_images` Where `id` = '{$selected_photo_id}'";

			$event_photo = $this->model->db->select($sql);

			$response = array();

			if (count($event_photo)>0) {
				/*code...*/
				$event_photo = $event_photo[0];

				$sql = "Delete From `products_gallery_images` Where `id` = '{$event_photo->id}'";

				$result = $this->model->db->query($sql);

				if ($result) {
					/*code...*/
					$filename = pathinfo($event_photo->image_url);
					$response[] = array($filename['basename'] => true);
				}
			}else{
				$response[] = array($selected_photo_name => true);
			}

			header_json();
			echo json_encode(array('files' => $response));

			/*$upload_handler->delete(true);*/
			break;
			default:
			$upload_handler->header('HTTP/1.1 405 Method Not Allowed');
		}
	}

	function sort_product_image(){

		if(hasPost('action', 'sort'))
		{
			$datas = post('data');

			echo json_encode($this->model->sort_product_image($datas));
		}
	}

	function delete_additional_files(){
		if(hasPost('action', 'delete'))
		{
			echo json_encode($this->model->delete_additional_files(post('id')));
		}
	}

	function sort_additional_files(){
		if(hasPost('action', 'sort'))
		{
			echo json_encode($this->model->sort_additional_files(post('data')));
		}
	}

	function download_files($id=""){
		$file_url = $this->model->get_files_url($id);
		if(!is_readable($file_url)) die('File not found or inaccessible!');
		$size = filesize($file_url);
		$name = rawurldecode($file_url);
		$mime_type = '';
		$known_mime_types=array(
			"htm" => "text/html",
			"exe" => "application/octet-stream",
			"zip" => "application/zip",
			"doc" => "application/msword",
			"jpg" => "image/jpg",
			"php" => "text/plain",
			"xls" => "application/vnd.ms-excel",
			"ppt" => "application/vnd.ms-powerpoint",
			"gif" => "image/gif",
			"pdf" => "application/pdf",
			"txt" => "text/plain",
			"html"=> "text/html",
			"png" => "image/png",
			"jpeg"=> "image/jpg"
			);

		if($mime_type==''){
			$file_extension = strtolower(substr(strrchr($file_url,"."),1));

			if(array_key_exists($file_extension, $known_mime_types)){
				$mime_type=$known_mime_types[$file_extension];
			}else{
				$mime_type="application/force-download";
			};
		}

		header('Content-Type:'. $mime_type);
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
		header("Content-Transfer-Encoding: binary");
		header('Accept-Ranges: bytes');
		readfile($file_url);
	}

	function getProductDetail(){
		if (isPost('action')) {
			if (post('action') == 'get') {
				header_json(); 
				$uc = new UC();

				$json_data = post('data');
				$lang = isset($json_data['lang']) ? $json_data['lang'] : $uc->reserved_language;

				$product =  $this->model->db->select("SELECT * FROM `products` WHERE `id` = '{$json_data['id']}'");
				$product = count($product) ? $product[0] : array();

				$product_tabs =  $this->model->db->select("SELECT * FROM `product_tabs` WHERE `product_id` = '{$json_data['id']}' ORDER BY `sort_order`");

				$translate_status = 'not translated';

				if ($lang != $uc->reserved_language) {
					$product_translate = $this->model->db->select("Select * From `cms_translation` Where `guid` = '{$json_data['id']}' and `language` = '{$lang}' and `type` = 'product'");
					$product->product_description = "";

					if (count($product_translate)>0) {
						$ptr = json_decode($product_translate[0]->meta);
						if (isset($ptr->product)) {
							$translate_status = 'translated';

							if (isset($ptr->product->product_description)) {
								$product->product_description = $ptr->product->product_description;
							}
							if (isset($ptr->product->product_name)) {
								$product->product_name = $ptr->product->product_name;
							}
							if (isset($ptr->product->seo_title)) {
								$product->seo_title = $ptr->product->seo_title;
							}
							if (isset($ptr->product->seo_description)) {
								$product->seo_description = $ptr->product->seo_description;
							}
							if (isset($ptr->product->url_slug)) {
								$product->url_slug = $ptr->product->url_slug;
							}
						}
					}

					$product_tabs_tanslated_result = $this->model->db->select("Select * From `cms_translation` Where `type` = 'product-tab' and `language` = '{$lang}' and `guid` = '{$json_data['id']}'");
					$product_tabs_tanslated = array();

					if (count($product_tabs_tanslated_result)) {
						$t = json_decode($product_tabs_tanslated_result[0]->meta);

						foreach ($t as $key => $value) {
							$product_tabs_tanslated[$key] = $value;
						}

						foreach ($product_tabs as $key => $value) {
							if (isset($product_tabs_tanslated[$value->id])) {
								if (isset($product_tabs_tanslated[$value->id]->title)) {
									$product_tabs[$key]->tab_title = $product_tabs_tanslated[$value->id]->title;
								}
								if (isset($product_tabs_tanslated[$value->id]->content)) {
									$product_tabs[$key]->tab_content = $product_tabs_tanslated[$value->id]->content;
								}
							}
						}
					}
				}

				$uc_url = trim(get_system_option('site_url'),'/').trim($lang,'/'). '/' . trim($product->url_slug,'/') . '/';
				$uc_info = $uc->uc_get_current_url_settings($uc_url);

				if ($lang == $uc_info['language']['slug_default']) {
					$translate_status = 'main';
				}elseif ($lang == $uc->reserved_language) {
					$translate_status = 'default';
				}

				$p['translated'] = $translate_status;

				$data_output = array(
					'detail' => $product,
					'tabs' => $product_tabs,
					'translated' => $translate_status,
					);

				echo json_encode($data_output);
			}
		}
	}

	function product_option_processor(){
		header_json();

		if ( isPost("action") ) {
			if (post("action") == 'save_option') {
				$data = json_decode(post('data'));
				$items = json_decode(post('items'));
				$id = post('id');

				$saved_items = $this->model->save_product_option($data, $items,  $id);
				echo json_encode($saved_items);
			}elseif (post("action") == 'get') {
				$id = post('id');
				if ($id == 0) exit();

				$product_options = $this->model->get_product_options($id);
				$product_option_output = array();

				foreach ($product_options['product_option'] as $key => $value) {
					foreach ($value['values'] as $kk => $vv) {
						$product_option_output[$value['detail']->product_option_name][] = $vv->product_option_values;
					}
				}
				echo json_encode(array("product_options"=>$product_option_output, "product_items" => $product_options['product_items']));
			}
		}
	}
	function review_processor(){
		if (isGet('action')) {
			if (get('action')=='table') {
				$product_id = isGet('id') ? get('id') : 0;

				$output = array(
					"sEcho" => 1,
					"iTotalRecords" => 1,
					"iTotalDisplayRecords" => 1,
					"aaData" => array()
					);

				$sql = "SELECT * FROM `product_items` Where `type`='review-rate' and `status`='Y' and `guid`='{$product_id}' Order By `date_modified` Desc";

				$columns = array(
					'value',
					'id',
					'date_modified',
					'meta',
					);

				$output = datatable_processor($columns, "id", "", $sql);

				foreach($output['aaData'] as $kk=>$vv){
					$meta_info = json_decode($vv[3]);
					$date_modified = strtotime($vv[2]);

					$review = '<p>'. $vv[0] .'</p>';
					$review .='<p><small><b>Rating</b>: '. (isset($meta_info->rate) ? $meta_info->rate : "Not Rated") .'</small></p>';
					$author = '<p>Name: <b>'. (isset($meta_info->name) ? $meta_info->name : '-unknown-' ) .'</b><br>'. 
					'Email: ' .(isset($meta_info->email) ? $meta_info->email : '-unknown-' ) .'</p>';
					$author .= '<p><small><b>Date Modified: </b>'. (date("Y-m-d H:i")) .'</small></p>';

					$btn_edit = table_button(array(
						"class" => "btn btn-mini btn-success btn-review-edit",
						"data-rel" => "tooltip",
						"data-placement" => "top",
						"title" => "Edit this review.",
						"data-value" => $vv[1],
						"label" => '<i class="icon icon-edit"></i>',
						));
					$btn_delete = table_button(array(
						"class" => "btn btn-mini btn-danger btn-review-delete",
						"data-rel" => "tooltip",
						"data-placement" => "top",
						"title" => "Delete this review.",
						"data-value" => $vv[1],
						"label" => '<i class="icon icon-trash"></i>',
						));

					$output['aaData'][$kk][0]=$review;
					$output['aaData'][$kk][1]=$author;
					$output['aaData'][$kk][2]=$btn_edit . $btn_delete;
				}

				echo json_encode($output);
			}
		}elseif(isPost('action')){
			if (post('action') == 'save') {
				$data = isPost('data') ? json_decode(post('data')) : array();

				$review_data = array(
					'id' => $data->id,
					'guid' => $data->guid != '' ? $data->guid : 0,
					'type' => 'review-rate',
					'name' => '',
					'value' => $data->review,
					'meta' => json_encode(array(
						'name' => $data->name,
						'email' => $data->email,
						'rate' => $data->rate,
						)),
					);

				$this->model->db->table = 'product_items';
				$this->model->db->data = $review_data	;

				if ($data->id==0 || $data->id=='' ) {
					unset($this->model->db->data['id']);
					$res = $this->model->db->insertGetID();
					if ($res) {
						echo json_encode(array('status'=>'success', 'message'=>"Successfully added new review."));
					}else{
						echo json_encode(array('status'=>'fail', 'message'=>"Unable to add new review."));
					}
				}else{
					$current_review = $this->model->db->select("Select * From `product_items` Where `id` = '{$data->id}' and `guid` = '{$data->guid}'");
					if (count($current_review)) {
						if ($this->model->db->update()) {
							echo json_encode(array('status'=>'success', 'message'=>"Successfully added new review."));
						}else{
							echo json_encode(array('status'=>'fail', 'message'=>"Unable to update review."));
						}
					}else{
						echo json_encode(array('status'=>'fail', 'message'=>"Unrecognized review."));
					}
				}
			}elseif (post('action') == 'get') {
				$id = post('review_id');

				$current_review = $this->model->db->select("Select * From `product_items` Where `id` = '{$id}' and `type` = 'review-rate'");
				if (count($current_review)) {
					$current_review = $current_review[0];

					$meta_info = json_decode($current_review->meta);

					echo json_encode(array('status'=>'success', 'message'=>"Successfully retrieve review detail.", 'data' => array(
						'id' => $current_review->id,
						'name' => isset($meta_info->name) ? $meta_info->name : '',
						'email' => isset($meta_info->email)? $meta_info->email : '',
						'content' => $current_review->value,
						'rate' => isset($meta_info->rate) ? $meta_info->rate : 0,
						)));
				}else{
					echo json_encode(array('status'=>'success', 'message'=>"Unable to find selected review."));
				}
			}
		}
	}
  function getProductBrands(){
      $sql = "SELECT * FROM `product_brands` WHERE `active` = '1'";

      $output = array(
        "sEcho" => 1,
        "iTotalRecords" => 1,
        "iTotalDisplayRecords" => 1,
        "aaData" => array()
        );

      $columns = array(
        'id',           	/*0*/
        'logo_main_url', 	/*1*/
        'brand_name', 		/*2*/
        );

      $output = datatable_processor($columns, "id", "", $sql);

      foreach($output['aaData'] as $kk=>$vv){
				$btnEdit = table_button(array(
					"class" => "btn btn-primary btn-select-product-brand",
					"data-rel" => "tooltip",
					"data-placement" => "top",
					"title" => "Select brand {$vv[2]}",
					"data-value" => $vv[0],
					"label" => 'Select',
					));
				$btns = $btnEdit;

        $output['aaData'][$kk][0] = '<p class="text-center"><img src="'. $vv[1] .'" alt="'. $vv[1] .'" id="product-brand-image-'. $vv[0] .'"></p>';
        $output['aaData'][$kk][1] = '<span id="product-brand-label-'. $vv[0] .'">'. $vv[2] .'</span>';
        $output['aaData'][$kk][2] = '<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">'. $btns .'</div>';
      }

      echo json_encode($output);
  }
	
	function get_languages(){
		$sql_language = "Select `id`, `value`, `meta` `slug`, if(`guid`=1, 'selected' , '') `selected` FROM (SELECT * FROM (( Select *, '0' `is_default` From `cms_items` Where `type` = 'cms-language' Union( Select `id`, if((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guid`, 'cms-language' `type`, `value`, `meta`, `status`, `date_added`, '1' `is_default` From ( Select * From `cms_items` Where `type` = 'cms-language-default' Union (Select '0' `id`, '0' `guid`, 'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added`) Order By `id` desc Limit 1 ) `t4` ))) `t1` Where (`type` = 'cms-language' or `type` = 'cms-language-default') Order By `guid` desc, `status` asc, `value` asc) `t1` Where type = 'cms-language' and `status` = 'active' Order By `guid` desc, `value` asc;";

		return $this->model->db->select($sql_language);
	}

	function delete_translation(){
		if (isPost('id') || isPost('lang')) {
			$id = post('id');
			$lang = post('lang');

			$res = $this->model->db->query("Delete From `cms_translation` Where `guid` = '{$id}' and `language`='{$lang}' and `type`='product' ");
			$res_tab = $this->model->db->query("Delete From `cms_translation` Where `guid` = '{$id}' and `language`='{$lang}' and `type`='product-tab' ");

			if ($res) {
				$msg = "Successfully Deleted Translation. " . (!$res_tab ? "But unable to remote the translated tabs.":"");

				echo json_encode(array('status'=>'success', 'message'=> $msg ));
			}
		}
	}

	function product_custom_fields(){
		if (isPost('action') && post('action') == 'save_custom_field') {
			$data 					= post('data');
			$product_id 		= isPost('product_id') ? post('product_id') : '0';
			$product_cf_id 	= isPost('product_cf_id') ? post('product_cf_id') : '0';
			$language 			= isPost('language') ? post('language') : $this->get_languages();

			if ($product_id != 0) {
				$d = array(
					'guid' 			=> $product_id,
					'type' 			=> 'custom-field',
					'name' 			=> '',
					'value' 		=> '',
					'meta' 			=> $data,
					'status' 		=> 'Y',
					'language' 	=> $language,
				);

				$this->model->db->table = "product_items";
				if ($product_cf_id != '0') {
					/* Update */
					// array_unshift($d, $product_cf_id);
					$d = array('id' => $product_cf_id) + $d;
					$this->model->db->data = $d;
					$this->model->db->update();
				}else{
					/* Insert */
					$this->model->db->data = $d;
					$this->model->db->insertGetID();
				}
			}
		}elseif (isPost('action') && post('action') == 'get_custom_field') {
			$id 	= post('product_id');
			$lang = isPost('language') ? post('language') : $this->get_languages();

			$custom_fields = $this->model->db->select("SELECT * FROM `product_items` WHERE guid = '{$id}' and guid <> 0 and language = '{$lang}'");
			$custom_fields = count($custom_fields) > 0 ? $custom_fields[0] : array();
			$cfs = isset($custom_fields->meta) ? json_decode($custom_fields->meta) : array();

			echo json_encode(array(
				"cf_id" 	=> isset($custom_fields->id) ? $custom_fields->id : 0,
				"cf_data" => $cfs,
			));
		}elseif (isPost('action') && post('action') == 'save_custom_field_template') {
			$template_data = json_decode(post('template'));
			if (!isPost('name')) {
				echo json_encode(array("success" => false, "message" => "Missing template name.")); exit();
			}
			if (count($template_data) < 0) {
				echo json_encode(array("success" => false, "message" => "Empty Field set.")); exit();
			}


			$data = array(
				"guid" 		=> 0,
				"type" 		=> "template-products-custom-fields",
				"value" 	=> post('name'),
				"meta" 		=> json_encode($template_data),
				"status" 	=> "active",
			);
			$this->model->db->data = $data;
			$this->model->db->table = "cms_items";
			if ($this->model->db->insertGetID()) {
				echo json_encode(array("success" => true, "message" => "Successfully saved template.")); exit();
			}else{
				echo json_encode(array("success" => true, "message" => "Unable to saved template.")); exit();
			}
		}elseif (isPost('action') && post('action') == 'get_custom_field_template') {
			$templates = $this->model->db->select("Select * FROM cms_items Where `type` = 'template-products-custom-fields' and status='active'");
			echo json_encode($templates);
		}elseif (isPost('action') && post('action') == 'delete_custom_field_template') {
			if (isPost('template_id')) {
				$template_id = post("template_id");
				$this->model->db->data = array(
					"id" 		 => $template_id,
					"status" => "trashed"
				);
				$this->model->db->table = "cms_items";
				if ($this->model->db->update()) {
					echo "1"; exit();
				}
			}
			echo "0";
		}
	}

	/* Billing Period */
	function billing_period_table_processor(){
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';

    $sql = "SELECT c.id, c.guid, c.value, c.type, c.meta, c.status, c.date_added, p.meta pmeta  FROM cms_items c 
						LEFT JOIN product_items p ON p.value = c.id and p.guid = '{$product_id}' and p.type = 'billing-period-item'
						WHERE c.type = 'billing-period-default-item'
						ORDER BY c.id ASC";
		$sql = "SELECT c.id, c.guid, c.value, c.type, c.meta, c.status, c.date_added, pi.meta pmeta, p.product_name  FROM cms_items c 
            LEFT JOIN product_items pi ON pi.value = c.id and pi.guid = '{$product_id}' and pi.type = 'billing-period-item'
            LEFT JOIN products p ON p.id = '{$product_id}' and p.product_status = 'active'
            WHERE c.type = 'billing-period-default-item'
            ORDER BY c.id ASC";
		// print_r($sql); exit();

    $output = array(
        "sEcho" => 1,
        "iTotalRecords" => 1,
        "iTotalDisplayRecords" => 1,
        "aaData" => array()
        );

    $columns = array(
        'id',           /*0*/
        'guid',         /*1*/
        'value',        /*2*/
        'type',         /*3*/
        'meta',         /*4*/
        'status',       /*5*/
        'date_added',   /*6*/
        'pmeta',   			/*7*/
        'product_name',	/*8*/
        );

    $output = datatable_processor($columns, "id", "", $sql);

    foreach($output['aaData'] as $kk=>$vv){
        $btnEditUser = table_button(array(
          "class"             => "btn btn-minier btn-success btn-billing-period-edit",
          "data-rel"          => "tooltip",
          "data-placement"    => "top",
          "title"             => "View Subscription",
          "data-value"        => $vv[0],
          "label"             => '<i class="icon-cog"></i>',
        ));

        $meta 	= json_decode($vv[4], true);
        $pmeta 	= json_decode($vv[7], true);
        
        $is_default = (isset($pmeta['default']) ? $pmeta['default'] : (isset($meta['default']) ? $meta['default'] : 'NO')) =="YES" ? 'checked="checked"' : '';
        $is_enabled = (isset($pmeta['enable']) ? $pmeta['enable'] : (isset($meta['enable']) ? $meta['enable'] : 'NO')) =="YES" ? 'checked="checked"' : '';
        $freq       = isset($pmeta['frequency']) ? $pmeta['frequency'] : (isset($meta['frequency']) ? $meta['frequency'] : '');
        $freq_intvl = isset($pmeta['frequency_interval']) ? $pmeta['frequency_interval'] : (isset($meta['frequency_interval']) ? $meta['frequency_interval'] : '');

        $default 	= '<label><input class="ace ace-checkbox-2 billing-period-default" id="billing-period-col-default-'. $vv[0] .'" value="'. $vv[0] .'" data-value="'. $vv[0] .'" type="radio" name="billing-period-subscription" '. $is_default .'><span class="lbl"></span></label>';
        $enable  	= '<label><input class="ace ace-checkbox-2 billing-period-enable" id="billing-period-col-enable-'. $vv[0] .'" value="'. $vv[0] .'" data-value="'. $vv[0] .'" type="checkbox" '. $is_enabled .'><span class="lbl"></span></label>';
        $freq_col = "<strong>Regular</strong>: {$freq_intvl} ". strtolower($freq) . ($freq_intvl > 1 ? 's' : '');

        $trial_enable = isset($pmeta['enable_trial']) ? $pmeta['enable_trial'] == 'YES' : (isset($meta['enable_trial']) && $meta['enable_trial'] =='YES');

        if ($trial_enable) {
          $t_freq 			= isset($pmeta['frequency_trial']) ? $pmeta['frequency_trial'] : (isset($meta['frequency_trial']) ? $meta['frequency_trial'] : 'MONTH');
          $t_freq_intvl = isset($pmeta['frequency_interval_trial']) ? $pmeta['frequency_interval_trial'] : (isset($meta['frequency_interval_trial']) ? $meta['frequency_interval_trial'] : '1');;
          $freq_col .= "<br><strong>Trial</strong>: {$t_freq_intvl} " . strtolower($t_freq) . ($t_freq_intvl > 1 ? 's' : '');
        }

        $output['aaData'][$kk][0] = '<div class="text-center">'. $default .'</div>';
        $output['aaData'][$kk][1] = '<div class="text-center">'. $enable .'</div>';
        $output['aaData'][$kk][2] = isset($pmeta['plan_name']) ? $pmeta['plan_name'] : "{$vv[8]} ({$vv[2]})";
        $output['aaData'][$kk][3] = "{$freq_col}";
        $output['aaData'][$kk][4] = '<div class="btn-group">'. $btnEditUser .'</div>';
    }

    echo json_encode($output);
	}
	function billing_period_global_subscription_table_processor(){
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
    $selected_global_subsriptions = array();
    $res = $this->model->db->select( "SELECT * FROM `product_items` WHERE type = 'billing-period-setting' AND guid = '{$product_id}'" );
    foreach ($res as $key => $value) {
    	$selected_items = json_decode($value->meta, true);

    	if (isset($selected_items['required_subs']) && count($selected_items['required_subs'])) {
    		foreach ($selected_items['required_subs'] as $k => $v) {
	    		$selected_global_subsriptions[] = $v;
	    	}
    	}
    }

    $sql = "SELECT * FROM `cms_items` WHERE type = 'paypal-subscription-plan' and status = 'active'";

    $output = array(
      "sEcho" => 1,
      "iTotalRecords" => 1,
      "iTotalDisplayRecords" => 1,
      "aaData" => array()
      );

    $columns = array(
      'id', 		/*0*/
      'value', 	/*1*/
      'meta',	 	/*2*/
      );

    $output = datatable_processor($columns, "id", "", $sql);

    foreach($output['aaData'] as $kk=>$vv){
        $meta = json_decode($vv[2], true);
        $is_selected = in_array($vv[0], $selected_global_subsriptions) ? "checked = 'checked'" : '';

        $description = array();

        $payment_definitions = [];
        foreach ($meta['plan']['payment_definitions'] as $key => $value) {
        	$amount = $value['amount']['value'] > 0 ? "({$value['amount']['value']} {$value['amount']['currency']})": '';
        	$payment_definitions[] = "{$value['type']} {$amount}";
        }

        $subscription_name = "{$meta['plan']['name']} <br><small><em>{$vv[1]}</em></small>";

        $default 	= '<label><input class="ace ace-checkbox-2 billing-period-global-subs" data-value="'. $vv[0] .'" type="checkbox" name="global-subscription" '. $is_selected .'><span class="lbl"></span></label>';

        $output['aaData'][$kk][0] = '<div class="text-center">'. $default .'</div>';
        $output['aaData'][$kk][1] = $subscription_name;
        $output['aaData'][$kk][2] = '<a href="javascript:void(0)" class="btn-view-global-subscription-detail" data-value="'. $vv[0] .'">'. implode(" + ", $payment_definitions) .'</a>' ;
    }

    echo json_encode($output);
	}
	function billing_period_subscriber_processor(){
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
    $payment_type = isset($_GET['payment_type']) ? $_GET['payment_type'] : '';
    $customer_id = 1;

    /* Default SQL */
    $sql = "SELECT o.id, c.firstname, c.lastname, op.payment_ref_number, o.order_status status, od.item_name FROM orders o
						INNER JOIN order_details od ON o.id = od.order_id and od.product_id = '0'
						INNER JOIN order_payments op ON op.order_id = o.id
						INNER JOIN customers c ON c.id = o.cid
						WHERE 0
						ORDER BY o.id DESC";

    if ($payment_type == 'Global Subscription') {
    	$global_subscriptions = array();

    	$tem_sql = "SELECT * FROM `product_items` WHERE `guid` = '{$product_id}' and type = 'billing-period-setting' ORDER BY `id` DESC";
    	$temp = $this->model->db->select($tem_sql);
    	if (count($temp) > 0) {
    		$temp = $temp[0];
    		$temp_meta = json_decode($temp->meta);

    		$global_subscriptions = $temp_meta->required_subs;

    		if (count($global_subscriptions) > 0) {
    			$temp_sql = "SELECT GROUP_CONCAT('\'', value, '\'') value FROM `cms_items` WHERE id IN (". implode(',', $global_subscriptions) .")";
	    		$temp = $this->model->db->select( $temp_sql );

	    		if (count($temp) > 0) {
	    			$temp = $temp[0];
	    			$plan_ids = $temp->value;

	    			/* Final SQL for Global Subcription */
	    			$sql = "SELECT o.id, c.firstname, c.lastname, op.payment_ref_number, o.order_status status, od.item_name FROM orders o
										INNER JOIN order_details od ON o.id = od.order_id and od.product_id = '0' 
										INNER JOIN order_payments op ON op.order_id = o.id
										INNER JOIN customers c ON c.id = o.cid
										WHERE od.item_name IN ({$plan_ids})
										ORDER BY o.id DESC";
	    		}
    		}
    	}
    }elseif ($payment_type == 'Subscription'){
	    /* Final SQL for Global Subcription */
    	$sql = "SELECT o.id, c.firstname, c.lastname, op.payment_ref_number, o.order_status status FROM orders o
							INNER JOIN order_details od ON o.id = od.order_id and od.product_id = '{$product_id}' 
							INNER JOIN order_payments op ON op.order_id = o.id
							INNER JOIN customers c ON c.id = o.cid
							ORDER BY o.id DESC";
							
			header_json(); print_r($sql); exit();
    }

    $output = array(
      "sEcho" => 1,
      "iTotalRecords" => 1,
      "iTotalDisplayRecords" => 1,
      "aaData" => array()
      );

    $columns = array(
      'id', 					/*0*/
      'firstname', 		/*1*/
      'lastname',	 		/*2*/
      'payment_ref_number',	 	/*3*/
      'status',	 			/*4*/
      );

    $output = datatable_processor($columns, "id", "", $sql);

    foreach($output['aaData'] as $kk=>$vv){
    	$status = $vv[4] == 'active' ? '<span class="label label-success arrowed arrowed-right">Active</span>' : '<span class="label label-warning arrowed arrowed-right">'. ($vv[4]=='cancelled' ? 'Suspended' : ucfirst($vv[4])) .'</span>';

    	$btn_suspend = '<a href="javascript:void(0)" class="btn btn-minier btn-danger btn-subscriber-cancel" data-value="'. $vv[0] .'"><i class="icon icon-user"></i></a>';
    	$btn_reactivate = '<a href="javascript:void(0)" class="btn btn-minier btn-success btn-subscriber-reactivate" data-value="'. $vv[0] .'"><i class="icon icon-refresh"></i></a>';

      $output['aaData'][$kk][0] = ucfirst($vv[1]) . " " . ucfirst($vv[2]);
      $output['aaData'][$kk][1] = "<small>{$vv[3]}</small>";
      $output['aaData'][$kk][2] = '<p class="text-center">'.$status.'</p>';
      $output['aaData'][$kk][3] = $vv[4]=='cancelled' ? $btn_reactivate : $btn_suspend;
    }

    echo json_encode($output);
	}
	function product_files_table_processor(){
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
    $product_files = isset($_GET['product_files']) ? $_GET['product_files'] : array();

    $selected_files_sql = $product_files != "" ? $product_files : 0;

    $list_id = "WHERE id IN (" . $selected_files_sql .')';

    $sql = "SELECT * FROM cms_files {$list_id}";

    $output = array(
      "sEcho" => 1,
      "iTotalRecords" => 1,
      "iTotalDisplayRecords" => 1,
      "aaData" => array()
      );

    $columns = array(
      'filename', /*0*/
      'id', 			/*1*/
      'mime',	 		/*2*/
      'meta',	 		/*3*/
      );

    $output = datatable_processor($columns, "id", "", $sql);

    foreach($output['aaData'] as $kk=>$vv){
    	$btn_delete = table_button(array(
				"class" => "btn btn-mini btn-danger btn-view-file",
				"data-rel" => "tooltip",
				"data-placement" => "top",
				"title" => "Remove this File",
				"data-value" => $vv[1],
				"label" => '<i class="icon icon-trash"></i>',
				));

    	$output['aaData'][$kk][0] = '<a href="javascript:void(0)">'. $vv[0] .'</a>' ;
    	$output['aaData'][$kk][1] = mime_dictionary($vv[2])['desc'];
    	$output['aaData'][$kk][2] = $btn_delete;
    }

    echo json_encode($output);
	}
	function product_media_table_processor(){
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
    $product_files = isset($_GET['product_files']) ? $_GET['product_files'] : array();

    // $selected_global_subsriptions = array();
    // $res = $this->model->db->select( "SELECT * FROM `product_items` WHERE type = 'billing-period-setting' AND guid = '{$product_id}'" );

    // $selected_files = array();
    // foreach ($res as $key => $value) {
    // 	$selected_items = json_decode($value->meta, true);

    // 	if (isset($selected_items['product_files'])) {
    // 		foreach ($selected_items['product_files'] as $kk => $vv) {
    // 			$selected_files[] = $vv;
    // 		}
    // 	}
    // }

    // $selected_files_sql = $selected_files ? implode(",", $selected_files) : 0;

    $selected_files_sql = $product_files != "" ? $product_files : 0;

    $list_id = "WHERE id NOT IN ({$selected_files_sql})";

    $sql = "SELECT * FROM cms_files {$list_id}";

    $output = array(
      "sEcho" => 1,
      "iTotalRecords" => 1,
      "iTotalDisplayRecords" => 1,
      "aaData" => array()
      );

    $columns = array(
      'filename', /*0*/
      'id', 			/*1*/
      'mime',	 		/*2*/
      'meta',	 		/*3*/
      );

    $output = datatable_processor($columns, "id", "", $sql);

    foreach($output['aaData'] as $kk=>$vv){
    	$output['aaData'][$kk][0] = '<div class="text-center"><label><input type="checkbox" class="item-checkbox" data-value="'. $vv[1] .'"><span class="lbl"></span></label></div>';
    	$output['aaData'][$kk][1] = '<a href="javascript:void(0)">'. $vv[0] .'</a>';
    	$output['aaData'][$kk][2] = $vv[2];
    }

    echo json_encode($output);
	}
	function billing_period_processor(){
		if (isPost('action')) {
			switch (post('action')) {
				case 'get-billing-period':
					$prod = post('product_id');
					$item = post('period_id');
					$default_settings = $this->billing_period_get_default_settings();

					$product_item_info = array();
					if (!isset($product_item_info['product_detail'])) { 
						$product_item_info['product_detail'] = array();
					}
					if (!isset($product_item_info['product_billing_period'])) {
						$product_item_info['product_billing_period'] = array();
					}

					$sql = "SELECT  products.id product_id, 
									        cms_items.id cms_item_id, 
									        product_items.id product_item_id, 
													products.product_name product_name, 
													products.product_description product_description, 
													products.price price, 
									        cms_items.meta default_meta, 
									        product_items.meta product_meta,
									        cms_items.value period_name
									FROM products 
									Left JOIN cms_items ON cms_items.type = 'billing-period-default-item' and cms_items.id = '{$item}' 
									Left JOIN product_items ON product_items.value = cms_items.id and product_items.type = 'billing-period-item' and products.id = product_items.guid
									WHERE products.id = '{$prod}'";

					$product_item = $this->model->db->select( $sql );

					foreach ($product_item as $key => $value) {
						$product_item_info['product_detail']['id'] = $value->product_id; //products_id
						$product_item_info['product_detail']['name'] = $value->product_name; //products_product_name

						$billing_period = $this->billing_period_set_values( $value );

						$product_item_info['product_billing_period'] = $billing_period;
					}

					echo json_encode($product_item_info);
					break;

				case 'get-billing-period-setting':
					$prod = post('product_id');
					$settings = array();

					$sql = "SELECT * FROM product_items WHERE guid = '{$prod}' and type='billing-period-setting'";

					$product_setting = $this->model->db->select( $sql );
					if (count($product_setting) > 0) {
						$product_setting = $product_setting[0];
						$settings = json_decode($product_setting->meta, true);
					}else{
						$settings = array(
							"enable" 	=> 'NO',
							"type" 		=> 'One-time',
							"product_type" 	=> 'Physical Goods',
						);
					}

					echo json_encode($settings);
					break;

				case 'get-global-subscription':
					$item_id = post('subs_id');
					$sql = "SELECT * FROM `cms_items` WHERE type = 'paypal-subscription-plan' and id='{$item_id}' ORDER BY `cms_items`.`id` DESC";
					$gobal_subscriptions_result = $this->model->db->select( $sql );
					$gobal_subscriptions = array();
					
					foreach ($gobal_subscriptions_result as $key => $value) {
						$gobal_subscriptions[ $value->id ] = json_decode($value->meta, true);
					}
					echo json_encode(array('status'=>true, 'response' => $gobal_subscriptions));
					break;

				case 'save-billing-period':
					$product_id = post('prod_id');
					$data 			= post('data');
					$settings 	= post('setting');
					$selected_global_subscriptions = isPost('data_sub') ? post('data_sub') : array();
					$files 			= isPost('files') ? post('files') : array();
					$files_trial= isPost('files_trial') ? post('files_trial') : array();

					/* Save subscription setting START */
					$sql = "SELECT * FROM product_items WHERE guid = '{$product_id}' and type='billing-period-setting'";
					$product_setting = $this->model->db->select( $sql );

					$new_meta = array(
						'enable'					=> $settings['enable'],
						'type' 						=> $settings['type'],
						'product_type' 		=> $settings['product_type'],
						'product_files'		=> $files,
						'product_files_trial' => $files_trial,
						'required_subs'		=> $selected_global_subscriptions,
					);

					if (count($product_setting) > 0) {
						$product_setting = $product_setting[0];
						$current_meta = json_decode($product_setting->meta, true);

						// $new_meta['product_files'] = isset($current_meta['product_files']) ? $current_meta['product_files'] : $new_meta['product_files'];

						$new_data = array(
							'id' 		=>  $product_setting->id, 
							'meta' 	=>  json_encode($new_meta), 
						);
						$this->model->db->data 	= $new_data;
						$this->model->db->table = "product_items";
						$this->model->db->update();
					}else{
						$new_data = array(
							'guid' 	=> $product_id, 
							'type' 	=> 'billing-period-setting', 
							'name' 	=> '', 
							'value' => '', 
							'meta' 	=> json_encode( $new_meta ), 
							'status' => "Y", 
							'language' => "en", 
						);
						$this->model->db->data 	= $new_data;
						$this->model->db->table = "product_items";
						$this->model->db->insertGetID();
					}
					/* Save subscription setting END */

					/* Save subscription items START */
					$sql = "SELECT  products.id product_id, 
									        cms_items.id cms_item_id, 
									        product_items.id product_item_id, 
													products.product_name product_name, 
													products.product_description product_description, 
													products.price price, 
									        cms_items.meta default_meta, 
									        product_items.meta product_meta,
									        cms_items.value period_name
									FROM products 
									Left JOIN cms_items ON cms_items.type = 'billing-period-default-item' 
									Left JOIN product_items ON product_items.value = cms_items.id and product_items.type = 'billing-period-item' and product_items.guid = products.id 
									WHERE products.id = '{$product_id}'";

					$current_items = $this->model->db->select( $sql );

					$error_counter = 0;

					foreach ($current_items as $key => $value) {
						$default_meta = json_decode($value->default_meta, true);
						$product_meta = json_decode($value->product_meta, true);

						$is_default = isset($data[$value->cms_item_id]['default']) ? $data[$value->cms_item_id]['default'] : $default_meta['default'];
						$is_enable 	= isset($data[$value->cms_item_id]['enable']) ? $data[$value->cms_item_id]['enable'] : $default_meta['enable'];

						if (isset($product_meta['enable'])) {
							$product_meta['enable'] = $is_enable;
							$product_meta['default'] = $is_default;

							$product_meta['plan_name'] = isset($product_meta['plan_name']) ? substr($product_meta['plan_name'], 0, 127) : "Undefined";
							$product_meta['plan_description'] = isset($product_meta['plan_description']) ? substr($product_meta['plan_description'], 0, 127) : "Undefined";
							$product_meta['agreement_name'] = isset($product_meta['agreement_name']) ? substr($product_meta['agreement_name'], 0, 127) : "Undefined";
							$product_meta['agreement_desc'] = isset($product_meta['agreement_desc']) ? substr($product_meta['agreement_desc'], 0, 127) : "Undefined";

							$new_product_item_data = array(
								'id' => $value->product_item_id,
								'meta' => json_encode($product_meta),
							);

							$this->model->db->data = $new_product_item_data;
							if (!$this->model->db->update()) {
								$error_counter++;
							};
						}else{
							$product_meta = $this->billing_period_set_values( $value );
							$product_meta['enable'] = $is_enable;
							$product_meta['default'] = $is_default;
							$period_id = $value->cms_item_id;

							$new_data = array(
								'guid' => $product_id, 
								'type' => 'billing-period-item', 
								'name' => '', 
								'value' => $period_id, 
								'meta' => json_encode( $product_meta ), 
								'status' => "Y", 
								'language' => "en", 
							);

							$this->model->db->data = $new_data;
							if (!$this->model->db->insertGetID()) {
								$error_counter++;
							}
						}
					}
					/* Save subscription items END */

					if ( $error_counter <= 0 ) {
						echo json_encode(array('status' => true, 'message' => "Successfully save Billing Period Setting"));
					}else{
						echo json_encode(array('status' => false, 'message' => "{$error_counter} error". ($error_counter > 0 ? 's' : '') ." occur while saving"));
					}

					break;

				case 'save-billing-period-item':
					$data = post('data');
					$product_id = $data['product_id'];
					$period_id 	= $data['current_item'];

					$billing_period = array(
						"plan_name" 			=> isset($data['plan_name']) ? substr($data['plan_name'], 0, 127) : "Undefined",
						"plan_description"=> isset($data['plan_description']) ? substr($data['plan_description'], 0, 127) : "Undefined",
						"plan_url_return" => isset($data['url_return']) ? $data['url_return'] : "",
						"plan_url_cancel" => isset($data['url_cancel']) ? $data['url_cancel'] : "",
						"plan_type" 			=> isset($data['type']) ? $data['type'] : "",
						"plan_auto_billing" => isset($data['auto_billing']) ? $data['auto_billing'] : "",
						"plan_max_fail_attempts" => isset($data['max_fail_attempts']) ? $data['max_fail_attempts'] : "",
						"plan_initial_fail_action" => isset($data['initial_fail_action']) ? $data['initial_fail_action'] : "",

						"title" 					=> isset($data['title']) ? $data['title'] : "Regular",
						"frequency" 			=> isset($data['frequency']) ? $data['frequency'] : "MONTH",
						"frequency_interval" => isset($data['frequency_interval']) ? $data['frequency_interval'] : "1",
						"cycle" 					=> isset($data['cycle']) ? $data['cycle'] : "0",
						"enable_trial" 		=> isset($data['enable_trial']) ? $data['enable_trial'] : "NO",
						"enable_regular" 	=> isset($data['enable_regular']) ? $data['enable_regular'] : "NO",
						"title_trial" 		=> isset($data['title_trial']) ? $data['title_trial'] : "Trial",
						"amount_trial" 		=> isset($data['amount_trial']) ? $data['amount_trial'] : "0",
						"frequency_trial" => isset($data['frequency_trial']) ? $data['frequency_trial'] : "MONTH",
						"frequency_interval_trial" => isset($data['frequency_interval_trial']) ? $data['frequency_interval_trial'] : "1",
						"cycle_trial" 		=> isset($data['cycle_trial']) ? $data['cycle_trial'] : "1",
						"default" 				=> isset($data['default']) ? $data['default'] : "NO",
						"enable" 					=> isset($data['enable']) ? $data['enable'] : "NO",
						"amount" 					=> isset($data['amount']) ? $data['amount'] : number_format($default_price, 2, '.',''),
						"agreement_name" 	=> isset($data['agreement_name']) && $data['agreement_description'] != "" ? substr($data['agreement_name'], 0, 127) : "Undefined",
						"agreement_desc" 	=> isset($data['agreement_description']) && $data['agreement_description'] != "" ? substr($data['agreement_description'], 0, 127) : "Undefined",
					);

					$get_current_product_subscription = $this->model->db->select("SELECT * FROM product_items WHERE guid = '{$product_id}' and value = '{$period_id}' and type = 'billing-period-item'");

					if (count($get_current_product_subscription) > 0) {
						$v = $get_current_product_subscription[0];
						$prod_meta = json_decode($v->meta, true);

						$billing_period['enable'] = isset($prod_meta['enable']) ? $prod_meta['enable'] : $billing_period['enable'];
						$billing_period['default'] = isset($prod_meta['default']) ? $prod_meta['default'] : $billing_period['default'];

						$new_data = array(
							'id' => $v->id, 
							'meta' => json_encode( $billing_period ), 
						);

						$this->model->db->data = $new_data;
						$this->model->db->table = "product_items";
						if ($this->model->db->update()) {
							echo json_encode(array('status' => true, 'message' => "Successfully save Billing Period Setting"));
						}else{
							echo json_encode(array('status' => false, 'message' => "Unable to save Billing Period Setting"));
						}
					}else{
						$new_data = array(
							'guid' => $product_id, 
							'type' => 'billing-period-item', 
							'name' => '', 
							'value' => $period_id, 
							'meta' => json_encode( $billing_period ), 
							'status' => "Y", 
							'language' => "en", 
						);

						$this->model->db->data = $new_data;
						$this->model->db->table = "product_items";
						if ($this->model->db->insertGetID()) {
							echo json_encode(array('status' => true, 'message' => "Successfully save Billing Period Setting"));
						}else{
							echo json_encode(array('status' => false, 'message' => "Unable to save Billing Period Setting"));
						}
					}
					break;

				case 'add-billing-period-file':
					$files 	= post('files');
					$prod 	= post('product');

					$res = $this->model->db->select( "SELECT * FROM `product_items` WHERE type = 'billing-period-setting' AND guid = '{$prod}'" );
					if (count($res)) {
						$res = $res[0];
						$meta = json_decode($res->meta, true);

						$meta['product_files'] = array_merge((isset($meta['product_files']) ? $meta['product_files'] : array()), $files);

						$new_item = array(
							'id' => $res->id,
							'meta' => json_encode($meta),
						);

						$this->model->db->table = "product_items";
						$this->model->db->data 	= $new_item;

						if ($this->model->db->update()) {
							echo json_encode(array('status' => true, "message" => "Successfully added new files."));
						}else{
							echo json_encode(array('status' => false, "message" => "Unable to add files."));
						}
					}else{
						echo json_encode(array('status' => false, "message" => "Unable to add files."));
					}

					break;

				case 'remove-billing-period-file':
					$file = post('file');
					$prod = post('product');

					$res = $this->model->db->select( "SELECT * FROM `product_items` WHERE type = 'billing-period-setting' AND guid = '{$prod}'" );
					if (count($res)) {
						$res = $res[0];
						$meta = json_decode($res->meta, true);
						$meta['product_files'] = array_diff($meta['product_files'], array($file));

						$new_item = array(
							'id' => $res->id,
							'meta' => json_encode($meta),
						);

						$this->model->db->table = "product_items";
						$this->model->db->data 	= $new_item;

						if ($this->model->db->update()) {
							echo json_encode(array('status' => true, "message" => "Successfully updated."));
						}else{
							echo json_encode(array('status' => false, "message" => "Unable to updated."));
						}
					}else{
						echo json_encode(array('status' => false, "message" => "Unable to updated."));
					}

					break;
				
				case 'suspend':
					require_once ROOT."libraries/plugins/cms-api/cms-paypal.php";
					$cms_paypal = new CMS_Paypal();

					$order_id = $_POST['id'];

					/* Get Order */
					$sql = "SELECT op.* FROM orders o INNER JOIN order_payments op ON o.id = op.order_id WHERE o.id = '{$order_id}'";
					$order = $this->model->db->select( $sql );
					$order = $order[0];

					$reference_number = $order->payment_ref_number;

					$result = $cms_paypal->billing_agreement_suspend($reference_number);

					if (isset($result['status']) && $result['status']) {
						$res1 = $this->model->db->query("UPDATE orders Set order_status = 'cancelled' WHERE id = '{$order_id}'");
						$res2 = $this->model->db->query("UPDATE order_details Set status = 'trash' WHERE order_id = '{$order_id}'");
						$res3 = $this->model->db->query("UPDATE order_payments Set payment_status = '0' WHERE order_id = '{$order_id}'");
					}

					echo $result['value'];
					break;
				
				case 'reactivate':
					require_once ROOT."libraries/plugins/cms-api/cms-paypal.php";
					$cms_paypal = new CMS_Paypal();

					$order_id = $_POST['id'];

					/* Get Order */
					$sql = "SELECT op.* FROM orders o INNER JOIN order_payments op ON o.id = op.order_id WHERE o.id = '{$order_id}'";
					$order = $this->model->db->select( $sql );
					$order = $order[0];

					$reference_number = $order->payment_ref_number;

					$result = $cms_paypal->billing_agreement_reactivate($reference_number);

					if (isset($result['status']) && $result['status']) {
						$res1 = $this->model->db->query("UPDATE orders Set order_status = 'active' WHERE id = '{$order_id}'");
						$res2 = $this->model->db->query("UPDATE order_details Set status = 'active' WHERE order_id = '{$order_id}'");
						$res2 = $this->model->db->query("UPDATE order_payments Set payment_status = '1' WHERE order_id = '{$order_id}'");
					}

					echo $result['value'];
					break;
				
				default:
					echo "Invalid Request!";
					break;
			}
		}else{
			echo "Invalid Request!";
		}
	}
	private function billing_period_set_values($value = array(), $default_meta_over=array(), $product_meta_over=array() ){
		$default_settings = $this->billing_period_get_default_settings();

		$default_meta = json_decode($value->default_meta, true); //default_meta
		$product_meta = json_decode($value->product_meta, true); //product_meta

		if (count($default_meta_over) > 0) { $default_meta = $default_meta_over; }
		if (count($product_meta_over) > 0) { $product_meta = $product_meta_over; }

		$product_name = isset($value->product_name) ? $value->product_name : "unknown";
		$product_desc 	= isset($value->product_description) ? $value->product_description : "";
		$period_name 	= isset($value->period_name) ? $value->period_name : "Unknown Period";

		$defaults = array(
			"plan_name" 			=> "{$product_name} ({$period_name})",
			"plan_description"=> strip_tags($product_desc),
			"plan_url_return" => isset($default_settings['prod_subs_default_return']) ? $default_settings['prod_subs_default_return'] : "",
			"plan_url_cancel" => isset($default_settings['prod_subs_default_cancel']) ? $default_settings['prod_subs_default_cancel'] : "",
			"plan_type" 			=> isset($default_settings['prod_subs_default_type']) ? $default_settings['prod_subs_default_type'] : "INFINITE",
			"plan_auto_billing" => isset($default_settings['prod_subs_default_auto_billing']) ? $default_settings['prod_subs_default_auto_billing'] : "YES",
			"plan_max_fail_attempts" => isset($default_settings['prod_subs_default_max_fail_attempts']) ? $default_settings['prod_subs_default_max_fail_attempts'] : '1',
			"plan_initial_fail_action" => isset($default_settings['prod_subs_default_initial_fail_action']) ? $default_settings['prod_subs_default_initial_fail_action'] : '1',

			"title" 					=> isset($default_meta['title']) ? $default_meta['title'] : "Regular",
			"frequency" 			=> isset($default_meta['frequency']) ? $default_meta['frequency'] : "MONTH",
			"frequency_interval" => isset($default_meta['frequency_interval']) ? $default_meta['frequency_interval'] : "1",
			"cycle" 					=> isset($default_meta['cycle']) ? $default_meta['cycle'] : "0",
			"enable_trial" 		=> isset($default_meta['enable_trial']) ? $default_meta['enable_trial'] : "NO",
			"title_trial" 		=> isset($default_meta['title_trial']) ? $default_meta['title_trial'] : "Trial",
			"amount_trial" 		=> isset($default_meta['amount_trial']) ? $default_meta['amount_trial'] : "0",
			"frequency_trial" => isset($default_meta['frequency_trial']) ? $default_meta['frequency_trial'] : "MONTH",
			"frequency_interval_trial" => isset($default_meta['frequency_interval_trial']) ? $default_meta['frequency_interval_trial'] : "1",
			"cycle_trial" 		=> isset($default_meta['cycle_trial']) ? $default_meta['cycle_trial'] : "1",
			"default" 				=> isset($default_meta['default']) ? $default_meta['default'] : "YES",
			"enable" 					=> isset($default_meta['enable']) ? $default_meta['enable'] : "NO",
			"amount" 					=> number_format($value->price, 2, '.',''),
			"agreement_name" 	=> isset($default_meta['prod_subs_default_agreement_name']) ? $default_meta['prod_subs_default_agreement_name'] : "",
			"agreement_desc" 	=> isset($default_meta['prod_subs_default_agreement_description']) ? $default_meta['prod_subs_default_agreement_description'] : "",
		);

		$billing_period = array(
			"plan_name" 			=> isset($product_meta['plan_name']) ? $product_meta['plan_name'] : $defaults['plan_name'],
			"plan_description"=> isset($product_meta['plan_description']) ? $product_meta['plan_description'] : $defaults['plan_description'],
			"plan_url_return" => isset($product_meta['plan_url_return']) ? $product_meta['plan_url_return'] : $defaults['plan_url_return'],
			"plan_url_cancel" => isset($product_meta['plan_url_cancel']) ? $product_meta['plan_url_cancel'] : $defaults['plan_url_cancel'],
			"plan_type" 			=> isset($product_meta['plan_type']) ? $product_meta['plan_type'] : $defaults['plan_type'],
			"plan_auto_billing" => isset($product_meta['plan_auto_billing']) ? $product_meta['plan_auto_billing'] : $defaults['plan_auto_billing'],
			"plan_max_fail_attempts" => isset($product_meta['plan_max_fail_attempts']) ? $product_meta['plan_max_fail_attempts'] : $defaults['plan_max_fail_attempts'],
			"plan_initial_fail_action" => isset($product_meta['plan_initial_fail_action']) ? $product_meta['plan_initial_fail_action'] : $defaults['plan_initial_fail_action'],

			"title" 					=> isset($product_meta['title']) ? $product_meta['title'] : $defaults['title'],
			"frequency" 			=> isset($product_meta['frequency']) ? $product_meta['frequency'] : $defaults['frequency'],
			"frequency_interval" => isset($product_meta['frequency_interval']) ? $product_meta['frequency_interval'] : $defaults['frequency_interval'],
			"cycle" 					=> isset($product_meta['cycle']) ? $product_meta['cycle'] : $defaults['cycle'],
			"enable_trial" 		=> isset($product_meta['enable_trial']) ? $product_meta['enable_trial'] : $defaults['enable_trial'],
			"title_trial" 		=> isset($product_meta['title_trial']) ? $product_meta['title_trial'] : $defaults['title_trial'],
			"amount_trial" 		=> isset($product_meta['amount_trial']) ? $product_meta['amount_trial'] : $defaults['amount_trial'],
			"frequency_trial" => isset($product_meta['frequency_trial']) ? $product_meta['frequency_trial'] : $defaults['frequency_trial'],
			"frequency_interval_trial" => isset($product_meta['frequency_interval_trial']) ? $product_meta['frequency_interval_trial'] : $defaults['frequency_interval_trial'],
			"cycle_trial" 		=> isset($product_meta['cycle_trial']) ? $product_meta['cycle_trial'] : $defaults['cycle_trial'],
			"default" 				=> isset($product_meta['default']) ? $product_meta['default'] : $defaults['default'],
			"enable" 					=> isset($product_meta['enable']) ? $product_meta['enable'] : $defaults['enable'],
			"amount" 					=> isset($product_meta['amount']) ? $product_meta['amount'] : $defaults['amount'],
			"agreement_name" 	=> isset($product_meta['agreement_name']) ? $product_meta['agreement_name'] : $defaults['agreement_name'],
			"agreement_desc" 	=> isset($product_meta['agreement_desc']) ? $product_meta['agreement_desc'] : $defaults['agreement_desc'],
		);

		$billing_period['plan_name'] = isset($billing_period['plan_name']) && $billing_period['plan_name'] != "" ? substr($billing_period['plan_name'], 0, 127) : "Undefined";
		$billing_period['plan_description'] = isset($billing_period['plan_description']) && $billing_period['plan_description'] != "" ? substr($billing_period['plan_description'], 0, 127) : "Undefined";
		$billing_period['agreement_name'] = isset($billing_period['agreement_name']) && $billing_period['agreement_name'] != "" ? substr($billing_period['agreement_name'], 0, 127) : "Undefined";
		$billing_period['agreement_desc'] = isset($billing_period['agreement_desc']) && $billing_period['agreement_desc'] != "" ? substr($billing_period['agreement_desc'], 0, 127) : "Undefined";

		return $billing_period;
	}
	private function billing_period_get_default_settings(){
		$default_settings = array();

		$sql = "SELECT * FROM payment_gateway_options WHERE option_name LIKE 'prod_subs_default_%'";
		$res = $this->model->db->select( $sql );
		foreach ($res as $key => $value) {
			$default_settings[$value->option_name] = $value->option_value;
		}

		return $default_settings;
	}
	private function billing_period_gateway_setting(){
		$default_settings = array();

		$sql = "SELECT * FROM `payment_gateway` WHERE gateway_type = 'PAYPAL_SUBSCRIPTION'";
		$res = $this->model->db->select( $sql );
		
		$default_settings['paypal_subscription_enabled'] = count($res) > 0 ? ($res[0]->enabled == 'Y') : false;

		return $default_settings;
	}
}