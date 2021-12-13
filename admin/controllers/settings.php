<?php
include ROOT . "libraries/plugins/sitemap/sitemap.php";
include ROOT . "libraries/plugins/php-curl-class-master/src/Curl/Curl.php";
include ROOT . "libraries/plugins/php-curl-class-master/src/Curl/CaseInsensitiveArray.php";

use Curl\Curl;

class Settings extends Controller{
  public $uc;
  function __construct(){
    $this->uc = new UC();
    parent::__construct();
    Session::handleLogin();
    set_module_title("General Settings");
  }

  function index(){
    $system_option = $this->model->load_system_settings();
    $system_type = isset($system_option['option_value']) ? $system_option['option_value'] : "CMS";

    $tabs = array(
      array(
        "tab" => array(
          "id"=>"home3",
          "label"=>"General Settings",
          "icon"=>"pink icon-cogs",
          "active"=>"active",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/general-settings.php',
        ),
      array(
        "tab" => array(
          "id"=>"profile3",
          "label"=>"System Email",
          "icon"=>"blue icon-envelope",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/system-email.php',
        ),
      array(
        "tab" => array(
          "id"=>"category_listing_page",
          "label"=>"Category Listing Page",
          "icon"=>"icon-barcode",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/category-page-setting.php',
        ),
      array(
        "tab" => array(
          "id"=>"company_profile",
          "icon"=>"icon-briefcase",
          "label"=>"Company Profile",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/company-profile.php',
        ),
      array(
        "tab" => array(
          "id"=>"maps",
          "icon"=>"icon-map-marker",
          "label"=>"Maps",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/map.php',
        ),
      array(
        "tab" => array(
          "id"=>"blog-setting",
          "icon"=>"icon-book",
          "label"=>"Blog Setting",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/blog-settings.php',
        ),
      array(
        "tab" => array(
          "id"=>"page-setting",
          "icon"=>"icon-cog",
          "label"=>"Page Settings",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/page-settings.php',
        ),
      array(
        "tab" => array(
          "id"=>"contact-form-setting",
          "icon"=>"icon-phone",
          "label"=>"Contact Form Settings",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/contact-form-setting.php',
        ),
      array(
        "tab" => array(
          "id"=>"product-setting",
          "icon"=>"icon-cog",
          "label"=>"Product Settings",
          "active"=>"",
          "show"=>$system_type == 'ECOMMERCE' || $system_type == 'ECATALOG',
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/product-settings.php',
        ),
      array(
        "tab" => array(
          "id"=>"customer-setting",
          "icon"=>"icon-cog",
          "label"=>"Customer Settings",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/customer-settings.php',
        ),
      array(
        "tab" => array(
          "id"=>"seo-setting",
          "icon"=>"icon-cog",
          "label"=>"SEO Settings",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/seo-settings.php',
        ),
      array(
        "tab" => array(
          "id"=>"container-sitemap",
          "icon"=>"icon-cog",
          "label"=>"XML-Sitemap",
          "active"=>"",
          "show"=>true,
          ),
        "layout"=> ROOT . 'views/general-settings/tabs/sitemap-settings.php',
        ),
      );

    $maps = $this->model->get_maps();
    $blog_settings = $this->model->load_blog_setting();
    $permalink = $this->model->load_siteurl();
    $pages = $this->model->load_pages();
    $post_format = $this->model->load_post_url_format();
    $languages = $this->model->get_languages();

    $this->view->set('sytem_setting_tabs', $tabs);
    $this->view->set('system_type', $system_type);
    $this->view->set('maps', $maps);
    $this->view->set('blog_settings', $blog_settings);
    $this->view->set('pages', $pages);
    $this->view->set('site_url', $permalink);
    $this->view->set('post_format', $post_format);
    $this->view->set('languages', $languages);
    $this->view->set('res_lang', $this->model->get_language_reserved());

    $js_files = array('settings-blog', 'settings-page', 'settings-permalink', 'settings-product', 'settings-comment', 'settings-contact-form');
    $this->view->set('js_files', $js_files);
    $this->view->setStyleFiles(array('settings'));

    $this->view->render('header');
    $this->view->render('general-settings/general-settings');
    $this->view->render('footer');
  }
  function guide($page = ""){
    if ($page == "") {
      $this->view->setScriptFiles(array('guide'));
      $this->view->setStyleFiles(array('settings'));
      $this->view->render('header');
      $this->view->render('guide/guide');
      $this->view->render('footer');
    }else{
      /* This part is use to get fragment of guide page */
      if (isPost('operation') && post('operation') == 'page-request') {
        if ($page == 'functions') {
          $page = 'functions-cached';
        }
        $this->view->render("guide/guide-{$page}");
      }else{
        echo "invalid request";
      }
    }
  }
  function guide_functions(){
    $cms_functions = file_get_contents(FRONTEND_ROOT . "/libraries/CMS_Functions.php");
    preg_match_all('/\/\*\*\n(.*?)\*\//s', $cms_functions, $cms_functions);

    $this->view->set('functions_list', $cms_functions[1]);
    $this->view->render('header');
    $this->view->render('guide/guide-functions-cached');
    $this->view->render('footer');
  }
  function get_cms_functions(){
    $cms_functions = file_get_contents(FRONTEND_ROOT . "/plugins/ecatalog/libraries/Functions.php");
    preg_match_all('/\/\*\*(.*?)\*\//s', $cms_functions, $test);

    $my_file = FRONTEND_ROOT . "/admin/views/guide/cached/guide-functions-product-cached.html";
    if (is_file($my_file)) {
      unlink($my_file);
    }
    $handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
    foreach ($test[1] as $key => $value) {
      $data = str_replace("*", "", $value);
      $data = str_replace("<code>", "<pre>", $data);
      $data = str_replace("</code>", "</pre>", $data);
      $data = str_replace('<?php', '&lt;?php', $data);
      $html = '<div class="row-fluid"><div class="span12 well well-small">' . "\n". (trim($data)) .'</div></div>' . "\n";
      fwrite($handle, $html);
    }
    fclose($handle);

    /* CMS FUNTIONS */
    $cms_functions = file_get_contents(FRONTEND_ROOT . "/libraries/CMS_Functions.php");
    preg_match_all("/\/\*\*(.*?)\*\//s", $cms_functions, $test);

    $my_file = FRONTEND_ROOT . "/admin/views/guide/cached/guide-functions-cached.html";
    if (is_file($my_file)) {
      unlink($my_file);
    }
    $handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
    foreach ($test[1] as $key => $value) {
      $data = str_replace("*", "", $value);
      $data = str_replace("<code>", "<pre>", $data);
      $data = str_replace("</code>", "</pre>", $data);
      $data = str_replace('<?php', '&lt;?php', $data);
      $html = '<div class="row-fluid"><div class="span12 well well-small">' . "\n". (trim($data)) .'</div></div>' . "\n";
      fwrite($handle, $html);
    }
    fclose($handle);
  }


  function saveSetting(){   
    if(hasPost('action','save_image')){
      $image = post('image');
      $copyright_footer = post('copyright_footer');
      $con_tracking_code = post('con_tracking_code');
      $web_analytics = post('web_analytics');
      $switch_event = post('switch_event');

      if($this->model->saveSetting($image,"website_logo") && $this->model->saveSetting($copyright_footer,"website_footer_copyright_text") && $this->model->saveSetting($switch_event,"google_event_tracking") && $this->model->saveSetting($web_analytics,"google_analytics_code") && $this->model->saveSetting($con_tracking_code,"conversion_tracking_code") ){
        echo "jQuery('#messageAlert1').append(alertMessage('Successfully Updated.','success','alert_status')); ";
      }else{
        echo "jQuery('#messageAlert1').append(alertMessage('Please try it again. Something wrong happened.','error','alert_status')); ";
      }
    }elseif (hasPost('action', 'save_email')) {
      $email = post('email');
      $name = post('name');

      if($this->model->saveSetting($email,"system_email") && $this->model->saveSetting($name,"system_email_name")){
        echo "jQuery('#messageAlert2').append(alertMessage('Successfully updated system email and name.','success','alert_status')); ";
      }else{
        echo "jQuery('#messageAlert2').append(alertMessage('Please try it again. Something wrong happened.','error','alert_status')); ";
      }
    }elseif(hasPost('action','save_page_settings')){
      $page_settings = post('arr_page_settings');
      $err = false;
      foreach ($page_settings as $key => $setting) {
        if($key == 0){
          if(!$this->model->saveSetting($setting,"category_page_display_order")){
            $err = true;
          }
        }
        elseif($key == 1){
          if(!$this->model->saveSetting($setting,"category_page_display_view")){
            $err = true;
          }
        }
        elseif($key == 2){
          if(!$this->model->saveSetting($setting,"listing_page_display_related_items_count")){
            $err = true;
          }
        }
        elseif($key == 3){
          if(!$this->model->saveSetting($setting,"customer_login_required")){
            $err = true;
          }
        }
      } 

      if($err){
        echo json_encode('0');
      }else{
        echo json_encode('1');          
      }
    }else{
      header('location:'.URL.'settings/');
    }
  }
  function loadData(){
    if(hasPost('action', 'loadData')){
      echo $this->model->loadData();
    }else{
      header('location:'.URL.'settings/');
    }
  }
  function save_company_profile(){
    if(hasPost('action', 'save_company_profile')){
      $err = 1;
      foreach (post('arr_company_profile') as $key => $value) {
        if(!$this->model->saveSetting($value, $key)){
          $err = 0;
        }
      }

      echo $err;
    }else{
      header('location:'.URL.'settings/');
    }
  }
  function save_map(){
    if(hasPost('action','save_map')){
      echo json_encode($this->model->save_map($_POST['data']));
    }
  }
  function update_map(){
    if(hasPost('action','update_map')){
      echo json_encode($this->model->update_map($_POST['data']));
    }
  }
  function delete_map(){
    if(hasPost('action','delete_map')){
      echo json_encode($this->model->delete_map($_POST['id']));
    }
  }
  function save_logo(){
    if($_POST){
      $arrs = array();
      $arrs['status'] = false;
      $arrs['image'] = FRONTEND_URL."/images/uploads/default.png";
      $error = false;
      $image_name = '';
      $image_tmp;
      $upload_path;
      $upload = post('logo_url');
      $p_id = 0; 

      if(!empty($_FILES['logo'])) {
        $image_name =  seoUrl($_FILES['logo']['name']);
        $image_name = uniqid().$image_name;
        $image_tmp = $_FILES['logo']['tmp_name'];
        $upload_path = "../images/uploads/company_logo/";
        $upload = FRONTEND_URL."/images/uploads/company_logo/".$image_name;

        if(!is_dir($upload_path)){
          if(!mkdir($upload_path, 0755, TRUE)){

          }
        }
        if (is_valid_image_type($_FILES['logo'])) {
          if(!move_uploaded_file($image_tmp, $upload_path . $image_name)){
            $error = true;
          }
        }else{
          $upload = post('logo_url');
        }
      }

      $arrs['image'] = $upload;
      $arr = array();
      $arr['website_logo'] =  $upload;
      $arr['website_footer_copyright_text'] = escape_string(post('footer_text'));
      $arr['google_event_tracking'] = post('switch');
      $arr['google_analytics_code'] = escape_string(post('analytic_codes'));
      $arr['conversion_tracking_code'] = escape_string(post('tracking_code'));
      $arr['website_name'] = post('website_name');

      foreach ($arr as $key => $value) {
        if(!$this->model->saveSetting($value, $key)){
          $error = 0;
        }
      }

      if(!$error){
        $arrs['status'] = true;
      }

      echo json_encode($arrs);
    }
  }

  function blog_setting_processor(){
    if (isPost("action")) {
      if (post('action') == 'save') {
        $json_data = json_decode(post('data'));
        $data = array(
          array(
            "option_name" => "blog_post_limit",
            "option_value" => $json_data->blog_count,
            )
          );
        $this->model->update_system_option('save', $data);
      }elseif (post('action') == 'save-customer-registration') {
        $json_data = json_decode(post('data'));
        $data = array(
          array(
            "option_name" => "enable_customer_registration",
            "option_value" => $json_data->enable_customer_registration,
            )
          );
        $this->model->update_system_option('save', $data);
      }
      echo "Saved";
    }
  }
  function page_setting_processor(){
    if (isPost("action")) {
      if (post('action') == 'save') {
        $json_data = json_decode(post('data'));

        if ($json_data->homepage == $json_data->blogpage &&  $json_data->homepage != "" && $json_data->blogpage != "") {
          echo "Homepage and Blog page should not be the same page";
          exit();
        }

        $data = array(
          array(
            "option_name" => "homepage",
            "option_value" => $json_data->homepage,
            ),

          array(
            "option_name" => "blog_page",
            "option_value" => $json_data->blogpage,
            ),

          );
        $this->model->update_system_option('save', $data);
      }

      echo "Saved";
    }
  }
  function product_setting_processor(){
    if (isPost("action")) {
      if (post('action') == 'save') {
        $json_data = json_decode(post('data'));
        $data = array();

        foreach ($json_data as $key => $value) {
          $data[] = array(
            "option_name" => $key,
            "option_value" => $value,
            );  
        }
        $this->model->update_product_settings('save', $data);

        $sitemap = new XMLSitemap();
        $sitemap->update();
        echo "Saved";
      }elseif(post('action') == 'get-dir'){
        $lang = isPost('lang') && post('lang') != '' ? "_" . post('lang') : '';
        $pdu = get_system_option('product_url' . $lang);
        $pcdu = get_system_option('product_category_url' . $lang);
        $dirs = array(
          'product_url' => $pdu!=''?$pdu:'products',
          'product_url_flag' => $pdu!=''?'set':'default',
          'product_category_url' => $pcdu!=''?$pcdu:'product-category',
          'product_category_url_flag' => $pcdu!=''?'set':'default',
        );
        echo json_encode($dirs);
      }
    }
  }
  function permalink_processor(){
    if (isPost("action")) {
      if (post('action') == 'save') {
        $json_data = json_decode(post('data'));

        $this->setSystemOption("site_url", $json_data->siteurl);
        $this->setSystemOption("post_url_format", $json_data->post_url_format);
        $this->setSystemOption("disallow_indexing", $json_data->indexing);
        $this->setSystemOption("disallow_blog_indexing", $json_data->blog_indexing);
        $this->setSystemOption("disallow_blog_post_indexing", $json_data->blog_post_indexing);
        $this->setSystemOption("disallow_blog_search_indexing", $json_data->blog_search_indexing);
        $this->setSystemOption("disallow_blog_pagination_indexing", $json_data->blog_pagination_indexing);
        $this->setSystemOption("disallow_blog_category_indexing", $json_data->blog_category_indexing);
        $this->setSystemOption("enable_https_redirect", $json_data->https_redirect);
        $this->setSystemOption("blacklisted_url", trim($json_data->blacklisted_url));
        $this->setSystemOption("system_robot_txt", $json_data->robots_txt);

        $this->setSystemOption("structured_data_company_name", $json_data->structured_data_company_name);
        $this->setSystemOption("structured_data_office_address", $json_data->structured_data_office_address);
        $this->setSystemOption("structured_data_telephone", $json_data->structured_data_telephone);
        $this->setSystemOption("structured_data_email", $json_data->structured_data_email);
        $this->setSystemOption("structured_data_enable", $json_data->structured_data_enable);
        $this->setSystemOption("structured_data_price_range", $json_data->structured_data_price_range);

        $front_config = fopen(FRONTEND_ROOT."/robots.txt","w");
        $txt = '';

        $txt .= $json_data->robots_txt != '' ? $json_data->robots_txt : "";
        $txt .= ($txt != '' ? "\n\n" : "") . ($json_data->indexing == 'YES' ? "User-agent: * \nDisallow: /" : "User-agent: * \nAllow: /");

        fwrite($front_config,$txt);
        fclose($front_config);

        /*Blacklisted URLS*/
        $blacklisted_url = trim($json_data->blacklisted_url);
        $blacklisted_url = explode("\n", $blacklisted_url);

        $htaccess = file_get_contents(FRONTEND_ROOT."/.htaccess");
        $htaccess = trim($htaccess);

        $blacklist_start = "#Blacklisted URL START";
        $blacklist_end = "#Blacklisted URL END";

        $pos1 = strpos($htaccess, $blacklist_start) + strlen($blacklist_start);
        $pos2 = strpos($htaccess, $blacklist_end);

        if ($pos1 && $pos2) { 
          /* If marker exist */
          $indent = ""; substr($htaccess, strpos($htaccess, $blacklist_start)-1, 1);
          $indent_position = 1;

          while ( substr($htaccess, strpos($htaccess, $blacklist_start)-$indent_position, 1) != "\n" ) {
            $indent .= substr($htaccess, strpos($htaccess, $blacklist_start)-$indent_position, 1);
            $indent_position++;
          }

          $black_list_content_1 = "\n";
          $black_list_content_2 = "";

          $ctr = 0;
          foreach ($blacklisted_url as $key => $value) {
            $ctr++;
            $flag = $ctr < count($blacklisted_url) ? "[OR,NC]" : "";
            if ($value != "") {
              $trimmed_value = ltrim($value,'/');

              if (substr($trimmed_value, 0, 1) == '?') {
                /*A query string*/
                $n =substr($trimmed_value, 1);
                $black_list_content_2 .= $indent . "RewriteCond %{QUERY_STRING} ^{$n}$ {$flag}\n";
              }else{
                $black_list_content_1 .= $indent . "RewriteCond %{REQUEST_URI} {$value}$ {$flag}\n";
              }
            }
          }
          /*$black_list_content_2 .= $indent;*/
          $black_list_content_rule = $indent . "RewriteRule ^(.+)$ - [L,R=404]\n" . $indent;

          /*$htaccess = substr_replace($htaccess, "{$black_list_content_1}{$black_list_content_2}{$black_list_content_rule}", $pos1, $pos2 - $pos1);*/
          $htaccess = substr_replace($htaccess, "\n".$indent, $pos1, $pos2 - $pos1);
          file_put_contents(FRONTEND_ROOT."/.htaccess", $htaccess);
        }

        /*HTTPS ENABLED*/
        $https_start = "#HTTPS START";
        $https_end = "#HTTPS END";

        $htaccess = file_get_contents(FRONTEND_ROOT."/.htaccess");
        $htaccess = trim($htaccess);

        $pos1 = strpos($htaccess, $https_start) + strlen($https_start);
        $pos2 = strpos($htaccess, $https_end);

        if ($pos1 && $pos2) { /*If marker exist*/
          $indent = ""; substr($htaccess, strpos($htaccess, $https_start)-1, 1);
          $indent_position = 1;

          while ( substr($htaccess, strpos($htaccess, $https_start)-$indent_position, 1) != "\n" ) {
            $indent .= substr($htaccess, strpos($htaccess, $https_start)-$indent_position, 1);
            $indent_position++;
          }

          $https_content  = "\n";
          if ($json_data->https_redirect=="ON") {
            $https_content .= $indent . "RewriteCond %{HTTPS} off \n";
            $https_content .= $indent . "RewriteCond %{REQUEST_URI} !^/[0-9]+\..+\.cpaneldcv$ \n";
            $https_content .= $indent . "RewriteCond %{REQUEST_URI} !^/[A-F0-9]{32}\.txt(?:\ Comodo\ DCV)?$ \n";
            $https_content .= $indent . "RewriteRule .* https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301] \n";
          }
          $https_content .= $indent;

          /*$htaccess = substr_replace($htaccess, $https_content, $pos1, $pos2 - $pos1);*/
          $htaccess = substr_replace($htaccess, "\n".$indent, $pos1, $pos2 - $pos1);
          file_put_contents(FRONTEND_ROOT."/.htaccess", $htaccess);
        }

        echo "Saved";
      } else if (post('action') == 'get-structured-data') {
        $_temp = array(
          'structured_data_enable' => get_system_option('structured_data_enable'),
          'structured_data_company_name' => get_system_option('structured_data_company_name'),
          'structured_data_office_address' => get_system_option('structured_data_office_address'),
          'structured_data_telephone' => get_system_option('structured_data_telephone'),
          'structured_data_email' => get_system_option('structured_data_email'),
          'structured_data_price_range' => get_system_option('structured_data_price_range'),
        );

        $d  = array(
          "enable"    => $_temp['structured_data_enable'] != '' ? $_temp['structured_data_enable'] : 'OFF',
          "name"      => $_temp['structured_data_company_name'] != '' ? $_temp['structured_data_company_name'] : get_system_option('company_name'),
          "address"   => $_temp['structured_data_office_address'] != '' ? $_temp['structured_data_office_address'] : get_system_option('company_address'),
          "telephone" => $_temp['structured_data_telephone'] != '' ? $_temp['structured_data_telephone'] : get_system_option('company_contact_number'),
          "email"     => $_temp['structured_data_email'] != '' ? $_temp['structured_data_email'] : get_system_option('company_email'),
          "logo"      => get_system_option('website_logo'),
        );

        header_json(); echo json_encode($d); exit();
      }
    }
  }
  function sitemap_processor(){
    $xml_sitemap = new XMLSitemap();

    $msg = array(
      "Successfully notified Google.",
      "There's an error while submitting sitemap to Google.",
      "Turning on [Disallow Indexing] on SEO Setting will prevent google from crawling your site and causing the sitemap to produce empty urls.",
      );

    if (isPost("action")) {
      if (post('action') == 'save-sitemap') {
        $data = json_decode(post('data'));

        if (isset($data->sitemap))          $this->setSystemOption("sitemap-enable", $data->sitemap);
        if (isset($data->auto_ping_google)) $this->setSystemOption("sitemap-auto-ping-google", $data->auto_ping_google);

        $xml_sitemap->generateSiteMap();
        $sitemap_option = $xml_sitemap->sitemap_option();
        $ping_result    = isset($sitemap_option['ping-google']) ? $sitemap_option : false;
        if (get_system_option('sitemap-auto-ping-google') == 'ON') {
          $ping_result    = $xml_sitemap->ping_google();
          $sitemap_option = $xml_sitemap->sitemap_option();
        }

        echo json_encode(array(
          "status"  => $ping_result ? "success" : "warning",
          "message" => "Sitemap setting saved. ",
          "data" => array(
            "google" => array(
              'status'    => $ping_result,
              'message'   => $ping_result ? $msg[0] : (get_system_option('disallow_indexing') == 'OFF' ? $msg[1] : $msg[2] ),
              'last-ping' => $sitemap_option['last-google-ping'],
              ),
            ),
          ));
      }elseif(post('action') == 'ping'){
        $ping_result = $xml_sitemap->ping_google();
        $sitemap_option = $xml_sitemap->sitemap_option();
        echo json_encode(array(
          "status"=> $ping_result ? "success" : "error",
          "message"=>"",
          "data"=>array(
            "google" => array(
              'status' => $ping_result,
              'message' => $ping_result ? $msg[0] : (get_system_option('disallow_indexing') == 'OFF' ? $msg[1] : $msg[2] ),
              'last-ping' => $sitemap_option['last-google-ping'],
              ),
            ),
          ));
      }
    }
  }
  function comments_processor(){
    if (isPost("action")) {
      if (post('action') == 'save') {
        $json_data = json_decode(post('data'));

        $data = array(
          "comments-allow-on-article" => $json_data->{"comments-allow-on-article"},
          "comments-article-comment-auto-close" => $json_data->{"comments-article-comment-auto-close"},
          "comments-article-comment-days-old" => $json_data->{"comments-article-comment-days-old"},
          "comments-author-previously-approved" => $json_data->{"comments-author-previously-approved"},
          "comments-email-me-on-comment" => $json_data->{"comments-email-me-on-comment"},
          "comments-email-me-on-moderate" => $json_data->{"comments-email-me-on-moderate"},
          "comments-enable-hold" => $json_data->{"comments-enable-hold"},
          "comments-enable-nesting" => $json_data->{"comments-enable-nesting"},
          "comments-hold-count-trigger" => $json_data->{"comments-hold-count-trigger"},
          "comments-list-blacklisted-words" => $json_data->{"comments-list-blacklisted-words"},
          "comments-list-moderated-words" => $json_data->{"comments-list-moderated-words"},
          "comments-manual-approve" => $json_data->{"comments-manual-approve"},
          "comments-nesting-level" => $json_data->{"comments-nesting-level"},
          "comments-require-email-name" => $json_data->{"comments-require-email-name"},
          "comments-required-registration" => $json_data->{"comments-required-registration"},
          );

        foreach ($data as $key => $value) {
          $this->setSystemOption($key, $value);
        }

        echo json_encode(array('status'=>'ok','message'=>'Saved'));
      }
    }
  }
  function contact_form_processor(){
    if (isPost("action")) {
      if (post('action') == 'save') {
        $json_data = json_decode(post('data'));

        $data = array(
          "GOOGLE_RECAPTCHA_SECRET" => $json_data->{"secret"},
          "GOOGLE_RECAPTCHA_KEY"    => $json_data->{"key"},
          );

        foreach ($data as $key => $value) {
          $this->setSystemOption($key, $value);
        }

        echo json_encode(array('status'=>'ok','message'=>'Saved'));
      }
    }
  }
  function setSystemOption($option_name = "", $option_value = "" ){
    $data = array(
      array(
        "option_name" => $option_name,
        "option_value" => $option_value,
        )
      );
    return $this->model->update_system_option('save', $data);
  }

}