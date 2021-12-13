<?php

class ProductBrand extends Controller{
    function __construct(){
        Session::handleLogin();
        parent::__construct();
    }
    function index(){
        $this->view->render('header');
        $this->view->render('product-brand/index-brand');
        $this->view->render('footer');
    }

    function product_brand_processor(){
        if (isPost('action')) {
            if (post('action') == 'tabulate'){
                $this->datatable_product_brand();
            }elseif(post('action') == 'save-brand-image-1'){
                $this->upload('main');
            }elseif(post('action') == 'save-brand-image-2'){
                $this->upload('alt');
            }elseif(post('action') == 'delete-brand'){
                $post_data = json_decode(post('data'));
                $result = $this->deleted_product_brand( $post_data ) ? "Deleted" : "Unable to delete";
                echo json_encode(array('data' => $result));
            }elseif(post('action') == 'load-brand'){
                $post_data = json_decode(post('data'));
                $result = $this->load_product_brand( $post_data );
                echo json_encode(array('data' => $result));
            }elseif(post('action') == 'save-brand'){
                $post_data = json_decode(post('data'));
                $result = $this->save_product_brand( $post_data );
                echo json_encode( $result );
            }
        }elseif (isGet('action')) {
            if (get('action') == 'tabulate'){
                $this->datatable_product_brand();
            }
        }
    }

    function datatable_product_brand(){
        $output = array(
            "sEcho" => 1,
            "iTotalRecords" => 1,
            "iTotalDisplayRecords" => 1,
            "aaData" => array()
            );

        $sql = "SELECT * FROM `product_brands` `pb` Where `active` = 1";

        $columns = array(
           'id',
           'brand_name',
           'brand_desc',
           'logo_main_url',
           'logo_alt_url',
           );

        $output = datatable_processor($columns, "id", "", $sql);

        foreach($output['aaData'] as $kk=>$vv){
            $output['aaData'][$kk][0] = '<div class="text-center"><label><input type="checkbox" class="ace item-checkbox"><span class="lbl"></span></label></div>';
            $output['aaData'][$kk][1] = $vv[1];
            $output['aaData'][$kk][2] = '<div class="text-center"><img src="'. $vv[3] .'" alt="'. $vv[3] .'" style="max-height: 150px;"></div>';
            $output['aaData'][$kk][3] = '<div class="text-center"><img src="'. $vv[4] .'" alt="'. $vv[4] .'" style="max-height: 150px;"></div>';

            $edit_btn = '<a href="javascript:void(0)" class="btn btn-mini btn-primary btn-product-brand-edit" data-value="'.$vv[0].'"><i class="icon icon-edit"></i></a>';
            $delete_btn = '<a href="javascript:void(0)" class="btn btn-mini btn-danger btn-product-brand-delete" data-value="'. $vv[0] .'"><i class="icon icon-trash"></i></a>';

            $output['aaData'][$kk][4] = '<div class="text-center">'. $edit_btn .' '. $delete_btn .'</div>';
        }

        echo json_encode($output);
    }
    function upload($image_type = ""){
        if ($image_type != "main" && $image_type != 'alt') { return; }

        $targetFolder = '/images/brands';
        $generated_string = sha1(rand() . time());

        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetPath = rtrim(FRONTEND_ROOT,'/') . $targetFolder;
            $image_url = rtrim(FRONTEND_URL,'/') . $targetFolder;

            $fileTypes = array('jpg','jpeg','gif','png');
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes)) {
                $new_file_name = $generated_string . "." . $fileParts['extension'];
                $targetFile = rtrim($targetPath,'/') . '/' . $new_file_name;
                $urlFile = rtrim($image_url,'/') . '/' . $new_file_name;

                if (!is_dir(rtrim(FRONTEND_ROOT,'/') . "/images")) { mkdir(rtrim(FRONTEND_ROOT,'/') . "/images"); }
                if (!is_dir(rtrim(FRONTEND_ROOT,'/') . "/images/brands")) { mkdir(rtrim(FRONTEND_ROOT,'/') . "/images/brands"); }
                if (!is_valid_image_type($_FILES['file'])) {
                    echo "Invalid File"; exit();
                }
                move_uploaded_file($tempFile,$targetFile);

                $image_type_column = $image_type =='main' ? "logo_main_url" : "logo_alt_url";

                $this->model->db->table = 'product_brands';
                $this->model->db->data = array(
                    "id" => isset($_POST['brand_id']) ? $_POST['brand_id'] : 0,
                    $image_type_column => $urlFile,
                    );
                $result = $this->model->db->update();

                echo json_encode(array('result'=>$result, 'url' => $urlFile));
            } else {
                echo 'Invalid file type.';
            }
        }else{
            echo 'Empty File.';
        }
    }
    function load_product_brand( $post_data = array() ){
        $brand_id = $post_data->id;

        $brand_sql = "Select * From `product_brands` Where `id` = '{$brand_id}'";
        $brand_data = $this->model->db->select( $brand_sql );
        $output = count($brand_data) ? $brand_data[0] : array();

        return $output;
    }
    function deleted_product_brand( $post_data = array() ){
        $brand_id = $post_data->id;

        $brand_sql = "Update `product_brands` Set `active` = 0 Where `id` = '{$brand_id}'";
        $result = $this->model->db->query( $brand_sql );

        return $result;
    }

    function save_product_brand( $post_data = array() ){
        if (!isset($post_data)) {
            return;
        }

        $output = array(
            "new_id" => 0,
            "message" => "Unable to save new Product Brand",
            );

        $data = array(
            "id" => $post_data->id,
            "brand_name" => $post_data->name,
            "brand_desc" => $post_data->description,
            );

        $this->model->db->table = "product_brands";

        if ( !isset($post_data->id) || $post_data->id == '' || $post_data->id == 0) { 
            unset($data['id']); 

            $this->model->db->data = $data;
            $brand_id = $this->model->db->insertGetID();

            if ($brand_id) {
                $output['new_id'] = $brand_id;
                $output['message'] = "Product ID: " . $brand_id;
            }
        }else{
            $this->model->db->data = $data;
            if ( $this->model->db->update() ) {
                $brand_id = $post_data->id;

                if ($brand_id) {
                    $output['new_id'] = $brand_id;
                    $output['message'] = "Product ID: " . $brand_id . ". Update Successfully";
                }
            }
        }

        return $output;
    }
}