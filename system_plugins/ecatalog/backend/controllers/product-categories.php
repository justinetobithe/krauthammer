<?php
class ProductCategories extends Controller{
	function __construct(){
		parent::__construct();
		Session::handleLogin();
	}
	function manage(){
		$this->view->setScriptFiles(array('product-categories-list'));
		$this->view->render('header');
		$this->view->render('product-categories/product-categories');
		$this->view->render('footer');   
	}
	function add(){
		set_module_sub_title("Add");
		$this->view->setScriptFiles(array('product-categories-add'));
		$this->view->render('header');
		$this->view->render('product-categories/add');
		$this->view->render('footer');
	}
	function edit($id){
		set_module_sub_title("Edit");
		$c = $this->model->db->select("Select * From `product_categories` Where `id` = '{$id}'");
		if (count($c) <= 0) {
			redirect( URL . "products/categories/");
		}
		$this->view->setScriptFiles(array('product-categories-edit'));
		$this->view->set('id',$id);
		$this->view->set('languages',$this->get_languages());
		$this->view->render('header');
		$this->view->render('product-categories/edit');
		$this->view->render('footer');
	} 
	function sort(){
		$this->view->render('header');
		$this->view->render('product-categories/sort');
		$this->view->render('footer');
	}  
	function add_product_category(){
		if($_POST){
			$name = $_POST['name'];
			$parent = $_POST['parent'];
			$hide = $_POST['hide'];
			$description = $_POST['description'];
			$image = '';
			$url_slug = post('url_slug');

			if(!empty($_FILES['image'])){
				$image_name =  seoUrl($_FILES['image']['name']);
				$image_name = uniqid().$image_name;
				$image_tmp = $_FILES['image']['tmp_name'];
				$upload_path = "../images/uploads/".date('Y')."/".date('m')."/".date('d')."/category_images/".$image_name."/";

				if (is_valid_image_type($_FILES['image'])) {
					echo "Invalid File"; exit();
				}

				if(!is_dir($upload_path)){
					$path = "../images/uploads/";
					$folder = date('Y/m/d')."/category_images/".$image_name."/";
					if(!mkdir($path . '/' .$folder , 0755, TRUE)){

					}
				}

				if(move_uploaded_file($image_tmp, $upload_path . $image_name)){
					$image = FRONTEND_URL."/images/uploads/".date('Y')."/".date('m')."/".date('d')."/category_images/".$image_name."/".$image_name;
				}
			}
			echo json_encode($this->model->add_product_category($name,$parent, $description,$image,$hide, $url_slug));
		}
	}
	function update_product_category(){
		if($_POST){
			$id 					= $_POST['id'];
			$name 				= $_POST['name'];
			$hide 				= $_POST['hide'];
			$parent 			= $_POST['parent'];
			$description 	= $_POST['description'];
			$save 				= $_POST['submit'];
			$url_slug 		= post('url_slug');
			$old_slug 		= post('old_slug') == $url_slug ? "" : post('old_slug');
			$lang 				= isPost('language') ? post('language') : '';
			$image 				= '';

			if(!empty($_FILES['image'])){
				$image_name = seoUrl($_FILES['image']['name']);
				$image_name = uniqid().$image_name;
				$image_tmp 	= $_FILES['image']['tmp_name'];
				$upload_dir 	= "images/uploads/category_images/".date('Y')."/".date('m')."/".date('d');
				$upload_path 	= FRONTEND_ROOT . "/{$upload_dir}/";

				if (!is_valid_image_type($_FILES['image'])) {
					echo "Invalid File"; exit();
				}

				if(!is_dir($upload_path)){
					$_temp_dir = "";
					foreach (explode('/', $upload_dir) as $key => $value) {
						$_temp_dir .= "/{$value}";
						if (!is_dir(FRONTEND_ROOT . $_temp_dir)) {
							mkdir(FRONTEND_ROOT . $_temp_dir);
						}
					}
				}

				if(move_uploaded_file($image_tmp, $upload_path . $image_name)){
					$image = FRONTEND_URL."/$upload_dir/{$image_name}";
				}
			}

			echo json_encode($this->model->update_product_category($id,$name, $parent, $description,$image,$save,$hide,$url_slug, $old_slug, $lang));
		}
	}
	function load_parents(){
		if(hasPost('action', 'get'))
			$current_category_id = isPost('current_id') ? post("current_id") : 0;
			echo json_encode($this->model->get_parents_2( $current_category_id ));
	}
	function load_categories(){
		if(hasPost('action', 'get')){
			$categories_grouped = $this->getChild(0, $this->model->get_categories_2());
			$categories_flat = $this->flatArray($categories_grouped);
			echo json_encode($categories_flat);
		}
	}
	function getChild($parent_val=0, $data_list=array()){
		$temp = array();
		foreach ($data_list as $key => $value) {
			if($parent_val==$value['parent']){
				$temp[$value['id']]['data'] = $value; /* store id */
				$temp[$value['id']]['children'] = $this->getChild($value['id'], $data_list); /*getChildren (if any)*/
			}
		}

		return $temp;
	}
	function flatArray($data_list=array()){
		$str = array();
		foreach ($data_list as $key => $value) {
			$data = $value['data'];
			$str[] = $data;

			foreach ($this->flatArray($value['children']) as $k => $v) {
				$str[] =$v;
			}
		}
		return $str;
	}
	function flatArray_2($data_list=array(), $indent=""){
		$str = array();
		foreach ($data_list as $key => $value) {
			$data = $value['data'];
			$data['category_name'] = $indent . $data['category_name'];
			$str[] = $data;

			foreach ($this->flatArray_2($value['children'], $indent . " - ") as $k => $v) {
				$str[] =$v;
			}
		}
		return $str;
	}

	/*2014-07-16 update end*/
	function delete_categories(){
		if(hasPost('action', 'delete')){
			$id = post('id');
			echo json_encode($this->model->delete_categories($id)); 
		}
	}
	function get_category(){
		if(hasPost('action','get')){
			$id = post('id');
			echo json_encode($this->model->get_category($id));
		}
	}
	function get_categories_parent_zero(){
		if(hasPost('action','get')){
			echo json_encode($this->model->get_categories_parent_zero());
		}
	}
	function sort_category(){
		if(hasPost('action','save')){
			$sort = post('arr');
			echo json_encode($this->model->sort_categories($sort));
		}
	}
	function crop(){
		if(hasPost('action','get')){
			echo json_encode(crop_image(post('data'),post('image')));
		}
	}
	function get_available_slug(){
		if(hasPost('action','get')){
			echo json_encode($this->model->get_available_slug($_POST['slug'], isset($_POST['lang']) ? $_POST['lang'] : ''));
		} 
	}
	function get_languages(){
		return $this->model->db->select("Select `id`, `value`, `meta` `slug`, if(`guid`=1, 'selected' , '') `selected` FROM (SELECT * FROM (( Select *, '0' `is_default` From `cms_items` Where `type` = 'cms-language' Union( Select `id`, if((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guid`, 'cms-language' `type`, `value`, `meta`, `status`, `date_added`, '1' `is_default` From ( Select * From `cms_items` Where `type` = 'cms-language-default' Union (Select '0' `id`, '0' `guid`, 'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added`) Order By `id` desc Limit 1 ) `t4` ))) `t1` Where (`type` = 'cms-language' or `type` = 'cms-language-default') Order By `guid` desc, `status` asc, `value` asc) `t1` Where type = 'cms-language' and `status` = 'active' Order By `guid` desc, `value` asc");
	}
}