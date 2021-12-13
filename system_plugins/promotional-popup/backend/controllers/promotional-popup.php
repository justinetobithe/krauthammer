<?php
class PromotionalPopup extends Controller{
	function __construct(){
		Session::handleLogin();
		parent::__construct();
	}
	function index(){
    $file_names = array(
      "promotional-popup"
    );
    $this->view->setStyleFiles($file_names);
    $cf = $this->model->db->select("Select * From `cms_contact_forms`");

    $this->view->set('cf', $cf);

    $qry = $this->model->db->query("SELECT * FROM `system_options` WHERE `option_name` = 'promotional-popup-settings' ");
    $row = $this->model->db->fetch($qry, 'array');
    $popup_options = json_decode($row['meta_data']);

    $sql_page = "SELECT * FROM cms_posts WHERE `post_status` <> 'trashed' AND `post_type` = 'page' ORDER BY `id` DESC ";
    $pages    = $this->model->db->select( $sql_page );

    /* Get Default Templates */
    $template = [];
    $template1 = $this->get_default_templates();
    $template2 = $this->get_custom_templates();

    $this->view->set('template_default', $template1);
    $this->view->set('template_custom', $template2);
    $this->view->set('cf', $cf);
    $this->view->set('popup_options', $popup_options);
    $this->view->set('pages', $pages);
    
		$this->view->render('header');
		$this->view->render('promotional-popup/index');
		$this->view->render('footer');
	}
  function preview(){
    $layout = isset($_GET['layout']) ? $_GET['layout'] : '';
    $this->view->set('layout', $layout);
    $this->view->render('promotional-popup/demo/demo-1');
  }
  function save_settings(){
    if (isset($_POST['data'])) {
      $data = json_decode($_POST['data']);
      $data->date_modified = date("Y:m:d:H:i:s");

      $data = json_encode($data);

      $result = $this->model->db->query("INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`) VALUES('promotional-popup-settings', '', '{$data}') ON DUPLICATE KEY UPDATE `meta_data`='{$data}'");

      if ($result) {
        echo '1';
      }else{
        echo '2';
      }
    }
  }
  function get_popup_settings(){
    $qry = $this->model->db->query("SELECT * FROM `system_options` WHERE `option_name` = 'promotional-popup-settings' ");
    $row = $this->model->db->fetch($qry, 'array');
    echo $row['meta_data'];
  }

  function get_default_templates(){
    $directory = __DIR__ . "/../views/promotional-popup/layout/";
    $files = scandir($directory);
    $templates = array();
    foreach ($files as $key => $file) {
      $path = "{$directory}{$file}";
      if (is_file($path) && strpos($path,'.php')) {

        if (strpos(file_get_contents($path),'Layout Name') !== false) {
          $file_content = file_get_contents($path);
          preg_match_all("/(.*):(.*)/", $file_content, $info);

          $tmp = array(
            "name"  => '',
            "desc"  => '',
            "prev"  => '',
            "key"   => '',
            "path"  => '',
          );

          for ($i=0; $i < count($info[0]); $i++) { 
            $lbl = trim($info[1][$i]);
            $val = trim($info[2][$i]);

            if ($lbl == 'Layout Name') {
              $tmp['name'] = $val;
              $tmp['path'] = $path;
            }
            if ($lbl == 'Description') {
              $tmp['desc'] = $val;
            }
            if ($lbl == 'Preview') {
              $tmp['prev'] = trim(FRONTEND_URL, '/') . "/system_plugins/promotional-popup/backend/assets/image/" . $val;
            }
            if ($lbl == 'Key') {
              $tmp['key'] = $val;
            }
          }
          
          $templates[]  = $tmp;
        }
      }
    }

    return $templates;
  }

  function get_custom_templates(){
    $theme = ACTIVE_THEME;
    $templates = array();

    // $base = trim(FRONTEND_ROOT,'/') . "/views/themes/{$theme}";
    $base = __DIR__ . "/../../../../views/themes/{$theme}";
    $directory = $base . "/promotional-popup/layout/";

    if (!is_dir($directory)) {
      return $templates;
    }
    $files = scandir($directory);

    foreach ($files as $key => $file) {
      $path = "{$directory}{$file}";
      if (is_file($path) && strpos($path,'.php')) {

        if (strpos(file_get_contents($path),'Layout Name') !== false) {
          $file_content = file_get_contents($path);
          preg_match_all("/(.*):(.*)/", $file_content, $info);

          $tmp = array(
            "name"  => '',
            "desc"  => '',
            "prev"  => '',
            "key"   => '',
            "path"  => '',
          );

          for ($i=0; $i < count($info[0]); $i++) { 
            $lbl = trim($info[1][$i]);
            $val = trim($info[2][$i]);

            if ($lbl == 'Layout Name') {
              $tmp['name'] = $val;
              $tmp['path'] = $path;
            }
            if ($lbl == 'Description') {
              $tmp['desc'] = $val;
            }
            if ($lbl == 'Preview') {
              $tmp['prev'] = trim(FRONTEND_URL, '/') . "/views/themes/{$theme}/promotional-popup/layout/img/" . $val;
            }
            if ($lbl == 'Key') {
              $tmp['key'] = $val;
            }
          }
          
          $templates[]  = $tmp;
        }
      }
    }

    return $templates;
  }

  function download($option){
    if (isset($option)) {
      $file = "";
      if ($option == 'default-layout-1') {
        $file = __DIR__ . '/../views/promotional-popup/layout/layout-1.php';
      }elseif($option == 'default-layout-2'){
        $file = __DIR__ . '/../views/promotional-popup/layout/layout-2.php';
      }

      if (file_exists($file)) {
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename="'. basename($file). '"');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($file));
          readfile($file);
          exit;
      }
    }
  }

  function view($option){
    if (isset($option)) {
      $file = "";
      if ($option == 'default-layout-1') {
        $file = __DIR__ . '/../views/promotional-popup/layout/layout-1.php';
      }elseif($option == 'default-layout-2'){
        $file = __DIR__ . '/../views/promotional-popup/layout/layout-2.php';
      }

      if (file_exists($file)) {
          header('Content-Description: File Transfer');
          header('Content-Type: text/php');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($file));
          readfile($file);
          exit;
      }
    }
  }
}