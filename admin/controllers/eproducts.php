<?php


class Eproducts extends Controller{
	function __construct(){
		parent::__construct();
	}
    function index(){
        Session::handleLogin();
        $product_categories = $this->model->get_products_details(array('id', 'product_name'));
        $this->view->set('product_categories', $product_categories);
        $this->view->render('header');
        $this->view->render('eproducts/index');
        $this->view->render('footer');
    }
    function edit($product_id=""){
        Session::handleLogin();
        if($product_id == "" || $product_id < 0 || !ctype_digit($product_id) ){
            header('location:'.URL.'eproducts/');
        }
        else
        {
            $product =  $this->model->loadProductsByID($product_id);
            $pro_exist = $this->model->checkProductExistence($product_id);
            //$products_attributes  = $this->model->get_product_attributes($product_id);
            $products_appointments = $this->model->get_product_appointments($product_id);
            //print_r($products_attributes);
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

              //  $this->view->set('products_attributes', $products_attributes);
                $this->view->set('selected_categories',$select);
                $this->view->set('appointments', $products_appointments);

                $this->view->render('header');
                $this->view->render('eproducts/edit');
                $this->view->render('footer');
            }
        }
    }
	function add(){
        Session::handleLogin();
        $product_categories = $this->model->loadProductCategories();
        $this->view->set('product_categories',$product_categories);
        $this->view->render('header');
        $this->view->render('eproducts/add');
        $this->view->render('footer');
        //$this->model->trythis()
    
    }
    function addProduct()
    {   

        if($_POST)
        {   
           $result = true;
            #PRODUCT IMAGE
            $image_name = '';
            $image_tmp;
            $upload_path;
            $p_id = 0; //FOR IMAGE GALLERY

            if(!empty($_FILES['image_file'])) {

                $image_name =  seoUrl($_FILES['image_file']['name']);//lowercase filename
                $image_name = uniqid().$image_name;
                $image_tmp = $_FILES['image_file']['tmp_name'];//temp path
                if (!is_valid_image_type($_FILES['image_file'])) {
                    echo "Invalid File!"; exit();
                }
                $upload_path = "../images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/";//check folder
                $upload = "/images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/";
                if(!is_dir($upload_path)){
                    $path = "../images/uploads/";
                    $date = date('Y/m/d')."/".$image_name;
                    if(!mkdir($path . '/' . $date, 0755, TRUE)){}//
                }
            } 

            #PRODUCT DETAILS
            $product_name = $_POST['product_name'];
            $product_description = escape_string($_POST['product_description']);
            $product_category = $_POST['product_category'];
            #LIST DETAILS
            $featured_product = $_POST['featured_product'];
            $product_price = $_POST['product_price'];
            $product_sku = $_POST['product_sku'];
            $product_qty = $_POST['product_qty'];
            if($product_qty == '') $product_qty = -1;
            $product_min_order_qty = $_POST['product_min_order_qty'];
            $product_stock = $_POST['product_stock'];
            $product_status = $_POST['submit'];
            $product_tabs = '';
            //$product_tabs = explode('!2/!/s202,',$_POST['product_tabs']);
            $product_tabs = json_decode($_POST['product_tabs']);

            $seo_title = post('seo_title');
            if($seo_title == '') $seo_title = $product_name;

            $seo_description = post('seo_description');
            $seo_no_index = post('seo_no_index');
            $track_inventory = 'NO';
            $recommended_for_checkout = $_POST['recommended_checkout'];
            $url_slug = post('url_slug');
            $status = post('status');

            if(isset($_POST['track_inventory']))
                $track_inventory = $_POST['track_inventory'];

            if($product_status == "Draft")
                $product_status = "trashed";
            else
                $product_status = "active";

           // check_old_slug($url_slug, 'products');
            #ADDING PRODUCT
            if($image_name != '')
            {
                if (is_valid_image_type($_FILES['image_file'])) {
                    if(move_uploaded_file($image_tmp, $upload_path . $image_name))
                        $p_id = $this->model->addProduct($upload.$image_name,$product_name,$product_description,$product_category,$featured_product,$product_price,$product_sku,$product_qty,$product_min_order_qty,$product_stock,$product_status,$product_tabs,$seo_title,$seo_description,$seo_no_index,$track_inventory,$recommended_for_checkout, $url_slug,$status);
                }

            }
            else
            {
                $image_name = uniqid().generateRandomString(10);
                $p_id = $this->model->addProduct("",$product_name,$product_description,$product_category,$featured_product,$product_price,$product_sku,$product_qty,$product_min_order_qty,$product_stock,$product_status,$product_tabs,$seo_title,$seo_description,$seo_no_index,$track_inventory,$recommended_for_checkout, $url_slug,$status);  
            }      
            if(!empty($_FILES['additional_files_input'])){
               $temp_upload_path_addtional_files = "../images/uploads/".date('Y/m/d')."/additional_files/".$p_id.'/';
               $upload_path_addtional_files = "images/uploads/".date('Y/m/d')."/additional_files/".$p_id;
               if(!is_dir($temp_upload_path_addtional_files)){
                        if(!mkdir($temp_upload_path_addtional_files, 0755, TRUE)){}//
                }//

                foreach($_FILES['additional_files_input']['name'] as $i => $name) {
                    $f_name = $name;
                    $name = uniqid().seoUrl($name);
                    $tmp =  $_FILES['additional_files_input']['tmp_name'][$i];
                    if (is_valid_image_type($tmp)) {
                        if(move_uploaded_file($tmp, $temp_upload_path_addtional_files.$name))
                            $this->model->add_additional_files($p_id, $upload_path_addtional_files.'/'.$name,$f_name,$i);
                    }
                    
                }
            }
        #FOR GALLERY IMAGES ONLY

       /* if($p_id > 0){
            if(!empty($_FILES['gallery_images']))
            {
                 
                foreach ($_FILES['gallery_images']['name'] as $i => $name) {
                   $name = uniqid().seoUrl($name);
                   $tmp =  $_FILES['gallery_images']['tmp_name'][$i];
                   $u_path = "../images/uploads/".date('Y')."/".date('m')."/".date('d')."/galleryimage/".$image_name."/";
                   $path_i = "/images/uploads/".date('Y')."/".date('m')."/".date('d')."/galleryimage/".$image_name."/";
                    if(!is_dir($u_path)){
                        $path = "../images/uploads/";
                            $date = date('Y/m/d')."/galleryimage/".$image_name;
                            if(!mkdir($path . '/' . $date, 0755, TRUE)){}//
                    }
                    if(move_uploaded_file($tmp, $u_path . $name))
                       {
                        $added = $this->model->add_image_gallery($path_i.$name, $p_id); 
                       
                            if(!$added)
                                 $result = false;
                       }
                    else
                        $result = false;
                }
            }
         }
         else
            $result = false;*/

       // $data = json_decode($_POST['product_attributes']);
        //$this->model->add_product_attributes($data,$p_id);

        $this->model->add_product_appointments(json_decode($_POST['hidden_product_appointments']), $p_id);
        
        $results = array();
        $results['image_name'] = $image_name;
        $results['product_id'] = $p_id;
        if(!$result)
            $results['status'] = 0;
        else
            $results['status'] = 1;
        
        echo json_encode($results);
        }
        else
            header('location:'.URL.'eproducts/');

    }
    function updateProduct()
    {
        if($_POST)
        {
            #PRODUCT IMAGE
            $result = true;
            $image_name = 'nothing';
            $image_tmp = '';
            $upload_path = '';
            $p_id = 0; //IMAGE GALLERY
            if(!empty($_FILES['image_file'])) {
                $image_name =  seoUrl($_FILES['image_file']['name']);//lowercase filename
                $image_name = uniqid().$image_name;
                $image_tmp = $_FILES['image_file']['tmp_name'];//temp path
                if (!is_valid_image_type($_FILES['image_file'])) {
                    echo "Invlaid File."; exit();
                }
                $upload_path = "../images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/";//check folder
                $upload = "/images/uploads/".date('Y')."/".date('m')."/".date('d')."/".$image_name."/";
                if(!is_dir($upload_path)){
                    $path = "../images/uploads/";
                        $date = date('Y/m/d')."/".$image_name;
                        if(!mkdir($path . '/' . $date, 0755, TRUE)){}//
                }
                
            } 
            #PRODUCT DETAILS
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $product_description = escape_string($_POST['product_description']);
            $product_category = $_POST['product_category'];
            #LIST DETAILS
            $featured_product = $_POST['featured_product'];
            $product_price = $_POST['product_price'];
            $product_sku = $_POST['product_sku'];
            $product_qty = $_POST['product_qty'];
            if($product_qty == '')
                $product_qty = -1;
            $product_min_order_qty = $_POST['product_min_order_qty'];
            $product_stock = $_POST['product_stock'];
            $product_status = $_POST['submit'];

            //$product_tabs = explode("!2/!/s202,", $_POST['product_tabs']);
            $product_tabs = json_decode($_POST['product_tabs']);
            $seo_title = post('seo_title');
            if($seo_title == '')
                $seo_title = $product_name;
            $seo_description = post('seo_description');
            $seo_no_index = post('seo_no_index');
            $url_slug = post('url_slug');
            $status = post('status');

            $track_inventory = 'NO';
            if(isset($_POST['track_inventory']))
                $track_inventory = $_POST['track_inventory'];

            if($product_status == "Draft")
                $product_status = "trashed";
            else
                $product_status = "active";

            $old_slug = post('old_slug');
           // echo $old_slug;
            if($old_slug == $url_slug)
                $old_slug = '';

        //check_old_slug($url_slug, 'cms_posts');
            #UPDATE PRODUCT
            $update = false;
            if($image_name != 'nothing')
            {
                if (is_valid_image_type($_FILES['image_file'])) {
                    if(move_uploaded_file($image_tmp, $upload_path . $image_name))
                        $update = $this->model->updateProduct($product_id,$upload.$image_name,$product_name,$product_description,$product_category,$featured_product,$product_price,$product_sku,$product_qty,$product_min_order_qty,$product_stock,$product_status,$product_tabs,$seo_title,$seo_description,$seo_no_index,$track_inventory,$url_slug,$old_slug,$status);
                }

                    if($update)
                    {
                        $p_id = 1;
                        //echo "not goood";
                    }
            }
            else{
                  $update = $this->model->updateProduct($product_id,"",$product_name,$product_description,$product_category,$featured_product,$product_price,$product_sku,$product_qty,$product_min_order_qty,$product_stock,$product_status,$product_tabs,$seo_title,$seo_description,$seo_no_index,$track_inventory,$url_slug,$old_slug,$status);
                   
                   if($update)
                        $p_id = 1;
                   // echo "not good";
            }
            if(!empty($_FILES['additional_files_input'])){
               $temp_upload_path_addtional_files = "../images/uploads/".date('Y/m/d')."/additional_files/".$product_id.'/';
               $upload_path_addtional_files = "images/uploads/".date('Y/m/d')."/additional_files/".$product_id;
               if(!is_dir($temp_upload_path_addtional_files)){
                        if(!mkdir($temp_upload_path_addtional_files, 0755, TRUE)){}//
                }//
                 $sort_index = $sort_index = $this->model->get_sort_index_for_additional_files($product_id);
                foreach($_FILES['additional_files_input']['name'] as $i => $name) {
                    $f_name = $name;
                  //  $f_name = $this->model->get_additional_name(basename($name),$product_id);
                    $name = uniqid().seoUrl($name);
                    $tmp =  $_FILES['additional_files_input']['tmp_name'][$i];
                    if (is_valid_image_type($tmp)) {
                        if(move_uploaded_file($tmp, $temp_upload_path_addtional_files.$name))
                            $this->model->add_additional_files($product_id, $upload_path_addtional_files.'/'.$name,$f_name, $sort_index);
                    }
                    $sort_index++;
                    
                }
            }
            /*if($p_id > 0){
                if(!empty($_FILES['gallery_images']))
                {
                     
                    foreach ($_FILES['gallery_images']['name'] as $i => $name) {
                           if($name!=''){
                               $name = uniqid().seoUrl($name);
                               $tmp =  $_FILES['gallery_images']['tmp_name'][$i];
                               $u_path = "../images/uploads/".date('Y')."/".date('m')."/".date('d')."/galleryimage/".$image_name."/";
                               $path_i = "/images/uploads/".date('Y')."/".date('m')."/".date('d')."/galleryimage/".$image_name."/";
                                if(!is_dir($u_path)){
                                    $path = "../images/uploads/";
                                        $date = date('Y/m/d')."/galleryimage/".$image_name;
                                        if(!mkdir($path . '/' . $date, 0755, TRUE)){}//
                                }
                                if(move_uploaded_file($tmp, $u_path . $name))
                                   {
                                    $added = $this->model->add_image_gallery($path_i.$name, $product_id); 
                                   
                                        if(!$added)
                                             $result = false;
                                   }
                                else
                                    $result = false;
                        }
                    }
                }
         }
         else
            $result = false;
*/
      //  $this->model->delete_product_attributes($product_id);
       // $data = json_decode($_POST['product_attributes']);
       // $this->model->add_product_attributes($data,$product_id);

        $this->model->delete_product_appointments($product_id);
        $this->model->add_product_appointments(json_decode($_POST['hidden_product_appointments']), $product_id);
        
        if(!$result)
            echo "0";
        else
            echo "1";

        }
        else
            header('location:'.URL.'eproducts/');
    }
    function getLastID()
    {
        if(hasPost('action', 'getLastID'))
            echo json_encode($this->model->getLastID());
        else
            header('location:'.URL.'eproducts/');
    }
    function getProducts()
    {
           // $rows = $this->model->getProducts();
            $output = array(
                "sEcho" => 1,
                "iTotalRecords" => 1,
                "iTotalDisplayRecords" => 1,
                "aaData" => array()
            );
           // header("Content-type: application/json");
 /// $connection_1 = $this->datatable_connection_1();
        $product_category = '';
        if (isset($_GET['product_category']) && $_GET['product_category'] != 'all') {
            $product_category = " and `pc`.`id` = '{$_GET['product_category']}'";
        }

   // $sql = "SELECT * FROM `products` WHERE `product_status` <> 'trashed' ORDER BY `id` DESC";
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
    if($vv[1] == '')
    {
        $image_url = FRONTEND_URL.'/images/uploads/default.png';
        $product_image_thumbnails = FRONTEND_URL.'/thumbnails/84x73/uploads/default.png';
    }
    else
    {
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
      $output['aaData'][$kk][2] = '<div><h5 class="light blue"><a href="'.URL.'products/edit/'.$vv[0].'">'.$vv[2].'</a></h5><a href="'.URL.'products/edit/'.$vv[0].'" class="light blue">Edit</a> | <a href="#" class="red" onclick ="deleteProductModal('.$vv[0].')">Delete</a> | <a href="'.URL.'products/'.$vv[0].'"class="light blue">Preview</a> </div></div>';
      $output['aaData'][$kk][4] = $qty;
      $output['aaData'][$kk][6] = '<i class="'.$feature_product.'"></i>';
      //$output['aaData'][$kk][7] =  $cat_;

    }
            
    echo json_encode($output);
        
    }
    function getCategory()
    {
        if(hasPost('action', 'getCategory'))
        {
            $id = post('id');
            echo json_encode($this->model->getCategory($id));
        }
        else
            header('location:'.URL.'eproducts/');
    }
    function deleteProduct()
    {
        if(hasPost('action','deleteProduct'))
        {
            $id = post('id');
            echo json_encode($this->model->deleteProduct($id));
        }
    }
  

   function addGallery()
   {
    //print_r(get_declared_classes());
        if($_POST)
        {
            $product_id = $_POST['product_id'];

            if($product_id == '')
            {
                //echo "null";
                $product_name = $_POST['product_name'];
                $product_categories = $_POST['product_categories'];

                echo $product_name. " = ".$product_categories;
            }
            else
            {
                
            }
        }
   }

   function deleteImages()
   {
    if(hasPost('action','delete_image'))
    {
        $id = post('img_id');

        if($this->model->delete_image($id))
            echo json_encode("1");
        else
            echo json_encode("0");
    }
   }

   function loadImages()
   {
        if(hasPost('action', 'load_images'))
        {
            $id = post('id');

            echo json_encode($this->model->load_product_images($id));
        }
   }
   function load_parent_category()
   {
        if(hasPost('action','load'))
        {
            echo json_encode($this->model->loadProductCategories());
        }
   }
   function addCategory()
   {
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
        // header("Content-type: application/json");
        
        $categories = $this->model->loadProductCategories();
        $parent = array();
        foreach ($categories as $key => $category) {
        if($category['category_parent'] != 0)
            array_push($parent, $category['category_parent']);

        }

        $haschildren = array_unique($parent);
        $sub_category = array();

        foreach ($categories as $key => $value) {
            if(in_array($value['category_parent'], $haschildren))
                 $sub_category[] = $value['id'];
        }

        $categories_grouped = $this->getChild(0, $categories);

        // echo $this->traceParent($categories_grouped); exit();

        // print_r($categories_grouped); exit();

        //print_r($categories_grouped);
        echo '<script>';

        echo 'var newData = {';
        // foreach ($categories as $key => $category) {
        //             $name = $category['category_name'];
        //             if(strlen($name) > 30)
        //                 $name =  substr($name,0, 30).'..';
        //             $data1 = '<span data-rel="tooltip" data-placement="top" title="'.$category['category_name'].'">'.$name.'<input type="hidden" class="value" value='.$category['id'].'></span>';
        //             if($category['category_parent']==0)
        //                 echo $category['id']." : {name: '".$data1."', type: 'item'}   ,";
        //             else
        //             {
        //                 echo $category['id']." : {name: '--".$data1."', type: 'item'}   ,";
        //             }
        //     }
        
        echo $this->traceParent_2($categories_grouped);

        echo '};';
 

        echo '</script>';
     
    }

    function getChild($parent_val=0, $data_list=array()){
        $temp = array();
        foreach ($data_list as $key => $value) {
            if($parent_val==$value['category_parent']){
                $temp[$value['id']]['data'] = $value; //store id
                $temp[$value['id']]['children'] = $this->getChild($value['id'], $data_list); //getChildren (if any)
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
    
    function __other($url=""){

        $categories = new loader();
        
        if($url[1] == 'categories' && empty($url[2]))
        {
            $categories->load_controller('product-categories','ProductCategories','manage');
        }
        else if($url[1] == 'categories' && $url[2] == 'add')
        {
             $categories->load_controller_method('product-categories','ProductCategories','add','');
        }
        else if($url[1] == 'categories' && $url[2] == 'edit' && !empty($url[3]))
        {
           if($url[3] > 0 && is_numeric($url[3]))
                  $categories->load_controller_method('product-categories','ProductCategories','edit',$url[3]);
           else
                $categories->load_error();
        }
        else if($url[1] == 'categories' && $url[2] == 'sorting')
        {
                $categories->load_controller_method('product-categories','ProductCategories','sort');
        }
        else
            $categories->load_error();

    }

    function get_product_sku(){

        if(hasPost('action','get')){
            echo json_encode($this->model->get_products_details());
        }
    }

    function get_available_slug(){

        if(hasPost('action','get')){
            echo json_encode($this->model->get_available_slug($_POST['slug']));
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
        echo $this->get_upload_url('asdw');
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
         //$sql = "SELECT * FROM `products_gallery_images` WHERE `product_id` = '$product_id' ";

         $data = $this->model->load_product_images($product_id);

         $files = array();
         foreach ($data as $key => $value) {
          # code...
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
                // $upload_handler->get(true);
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
                # code...
                $event_photo = $event_photo[0];

                $sql = "Delete From `products_gallery_images` Where `id` = '{$event_photo->id}'";

                $result = $this->model->db->query($sql);

                if ($result) {
                # code...
                $filename = pathinfo($event_photo->image_url);
                $response[] = array($filename['basename'] => true);
                }
                }else{
                $response[] = array($selected_photo_name => true);
                }

                header_json();
                echo json_encode(array('files' => $response));

                // $upload_handler->delete(true);
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
             } else {
                $mime_type="application/force-download";
             };
         };

        // echo $mime_type;
     
        header('Content-Type:'. $mime_type);
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');
        readfile($file_url);
    }
}