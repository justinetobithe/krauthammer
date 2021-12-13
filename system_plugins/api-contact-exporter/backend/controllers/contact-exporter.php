<?php
class ContactExporter extends Controller{
	function __construct(){
		Session::handleLogin();
		parent::__construct();
	}
	function index(){
		$this->view->render('header');
		$this->view->render('api-contact/index');
		$this->view->render('footer');
	}

  function upload_contact(){
    $this->loadPlugin('cms-api/contact-import');
    $ci = new ContactImport("http://localhost/test/importing/index.php"); /* Upload Destination*/
    echo $ci->upload();
  }

  function table(){
    $output = array(
      "sEcho" => 1,
      "iTotalRecords" => 1,
      "iTotalDisplayRecords" => 1,
      "aaData" => array()
    );

    $sql = "SELECT * FROM `customers`";

    $columns = array(
      'id',                  //0
      'firstname',           //1
      'contact_number',      //2
      'billing_address',     //3
      'lastname',            //4
      'billing_address_2',   //5
      'billing_postal_code', //6
      'billing_city',        //7
      'billing_country',     //8
      'billing_state',       //9
      'sync_status',         //10
    );

    $output = datatable_processor($columns, "id", "", $sql);

    foreach($output['aaData'] as $kk=>$vv){
      $btn_edit = table_button(array(
        "class"           => "btn btn-mini btn-success btn-review-edit",
        "data-rel"        => "tooltip",
        "data-placement"  => "top",
        "title"           => "Edit this review.",
        "data-value"      => $vv[0],
        "label"           => '<i class="icon icon-upload"></i>',
      ));

      $address = "";
      $address .= $vv[3] != "" ? "<b>Address</b>: {$vv[3]} <br>" : ($vv[5] != "" ? "Address: {$vv[5]} <br>" : "");
      $address .= $vv[7] != "" ? "<b>City</b>: {$vv[7]} <br>" : "";
      $address .= $vv[8] != "" ? "<b>Country</b>: {$vv[8]} <br>" : "";

      $output['aaData'][$kk][0] = '<div class="text-center"><label><input type="checkbox" class="ace" /><span class="lbl"></span></label></div>';
      $output['aaData'][$kk][1] = "{$vv[1]} {$vv[4]}";
      $output['aaData'][$kk][3] = $address;
      $output['aaData'][$kk][4] = '<div class="text-center"><b>'. ($vv[10] == 'Y' ? '<span class="text-success">Sync</span>' : '<span class="text-error">Not Sync</span>') .'</b></div>';
      $output['aaData'][$kk][5] = '<div class="text-center">'. $btn_edit .'</div>';
    }

    echo json_encode($output);
  }
}