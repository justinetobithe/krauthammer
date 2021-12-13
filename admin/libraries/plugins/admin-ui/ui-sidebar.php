<?php
class CMSUISidebar{
  private $default_menu;
  private $db;
  private $cache_current_url;

  function __construct(){
    $this->db = new Database();
    /*
    $this->add_menu_property(label, id, url, icon, system_type, perm[], url_indicator[], submenu[]),
    $this->add_menu_property("", "", "", "", "", array(), array(), array()),
    return Array(
    */

    $this->default_menu = array();
    
    $this->default_menu[] = $this->add_menu_property("Orders", "orders", "orders/", "icon-barcode", "ECOMMERCE", array('orders'), array("orders/*"), array(
      $this->add_menu_property("Create New Order", "orders-add", "orders/add/", "", "", array(), array("orders/add/*")),
      $this->add_menu_property("Orders", "orders-list", "orders/", "", "", array(), array("orders/", "orders/edit/*")),
    ));
    $this->default_menu[] =  $this->add_menu_property("Invoices", "invoices", "invoices/", "icon-credit-card", "ECOMMERCE", array('invoices'), array("invoices/*"), array(
      $this->add_menu_property("All Invoices", "invoices-list", "invoices/", "", "", array(), array("invoices/")),
      $this->add_menu_property("Paid Invoices", "invoices-paid", "invoices/paid/", "", "", array(), array("invoices/paid/")),
      $this->add_menu_property("Unpaid Invoices", "invoices-unpaid", "invoices/unpaid/", "", "", array(), array("invoices/unpaid/")),
    ));
    $this->default_menu[] = $this->add_menu_property("eCatalog Enquiries", "enquiries", "enquiries/", "icon-lightbulb", "ECATALOG", array('enquiries'), array("enquiries/*"), array(
      $this->add_menu_property("Enquiries", "enquiries-list", "enquiries/", "", "", array(), array("enquiries/")),
    ));
    $this->default_menu[] = $this->add_menu_property("Customers", "customers", "customers/", "icon-group", "", array('customers'), array("customers/*"), array(
      $this->add_menu_property("Add New", "customers-add", "customers/add/", "", "", array(), array("customers/add/")),
      $this->add_menu_property("Manage", "customers-list", "customers/", "", "", array(), array("customers/", "customers/edit/*")),
    ));
    $this->default_menu[] = $this->add_menu_property("Newsletter", "newsletter", "newsletter/", "icon-file-alt", "", array('newsletter'), array("newsletter/*"), array(
      $this->add_menu_property("Subscribers", "newsletter-list", "newsletter/", "", "", array(), array("newsletter/")),
      $this->add_menu_property("MailChimp", "newsletter-mailchimp", "newsletter/mailchimp/", "", "", array(), array("newsletter/mailchimp/")),
    ));
    $this->default_menu[] = $this->add_menu_property("Pages", "pages", "pages/", "icon-file-alt", "", array('pages'), array("pages/*"), array(
      $this->add_menu_property("Add Page", "pages-add", "pages/add/", "", "", array(), array("pages/add/")),
      $this->add_menu_property("Page", "pages-list", "pages/", "", "", array(), array("pages/", "pages/edit/*")),
    ));
    $this->default_menu[] = $this->add_menu_property("Post", "post", "post/", "icon-file-alt", "", array('posts'), array("post/*"), array(
      $this->add_menu_property("Add Post", "post-add", "post/add/", "", "", array(), array("post/add/")),
      $this->add_menu_property("Post", "post-list", "post/", "", "", array(), array("post/", "post/edit/*")),
      $this->add_menu_property("Categories", "post-categories", "post/categories/", "", "", array(), array("post/categories/*")),
    ));
    $this->default_menu[] = $this->add_menu_property("Comments", "comments", "comments/", "icon-comment-alt", "", array('pages', 'posts'), array("comments/*"), array(
      $this->add_menu_property("Comments", "comments-list", "comments/", "", "", array(), array("comments/")),
    ));
    $this->default_menu[] = $this->add_menu_property("Testimonial", "testimonials", "testimonials/", "icon-comment", "", array(), array("testimonials/*"), array(
      $this->add_menu_property("View Testimonials", "testimonials-list", "testimonials/", "", "", array(), array("testimonials/")),
    ));
    $this->default_menu[] = $this->add_menu_property("Appearance", "appearance", "appearance/", "icon-desktop", "", array('appearance'), array("menus/*", "sliders/*", "settings/guide/*", "widgets/*", "theme/*"), array(
      $this->add_menu_property("Theme", "theme", "theme/", "", "", array(), array("theme/*")),
      $this->add_menu_property("Widgets", "widgets-list", "widgets/", "", "", array(), array("widgets/*")),
      $this->add_menu_property("Menus", "menus-list", "menus/", "", "", array(), array("menus/*")),
      $this->add_menu_property("Sliders", "sliders-list", "sliders/", "", "", array(), array("sliders/*")),
      $this->add_menu_property("Guide", "guide-list", "settings/guide/", "", "", array(), array("settings/guide/*")),
    ));
    $this->default_menu[] = $this->add_menu_property("Contact Form", "contact-form", "contact-form/", "icon-phone", "", array(), array("contact-forms/*"), array(
      $this->add_menu_property("Contact Forms", "contact-forms-list", "contact-forms/", "", "", array(), array("contact-forms/","contact-forms/add/*", "contact-forms/edit/*")),
      $this->add_menu_property("Contact Form Enquiries", "contact-forms-response-list", "contact-forms/responses/", "", "", array(), array("contact-forms/responses/")),
    ));
    $this->default_menu[] = $this->add_menu_property("Media", "media", "media/", "icon-picture", "", array(), array("media/*"), array(
      $this->add_menu_property("Media", "media-list", "media/", "", "", array(), array("media/", "media/edit/*")),
    ));
    $this->default_menu[] = $this->add_menu_property("Users", "users", "users/", "icon-user", "", array('users'), array("users/*"), array(
      $this->add_menu_property("Add New User", "users-add", "users/add/", "", "", array(), array("users/add/*")),
      $this->add_menu_property("Users", "users-list", "users/", "", "", array(), array("users/", "users/edit/*")),
    ));
    // $this->add_menu_property(label, id, url, icon, system_type, perm[], url_indicator[], submenu[]),
    $this->default_menu[] = $this->add_menu_property("eCommerce", "ecommerce", "ecommerce/", "icon-credit-card", "", array('payment', 'shipping'), array("payment-gateways/*","shipping/*","tax/*"), array(
      $this->add_menu_property("Payment Gateway", "payment-gateways-list", "payment-gateways/", "", "", array('payment'), array("payment-gateways/")),
      $this->add_menu_property("Paypal Subscription", "payment-gateways-subscription", "payment-gateways/subscription/", "icon-calendar", "ECOMMERCE", array('payment'), array("payment-gateways/subscription")),
      $this->add_menu_property("Shipping", "shipping-list", "shipping/", "", "", array('shipping'), array("shipping/*")),
      $this->add_menu_property("Tax", "tax-list", "tax/", "", "", array(), array("tax/*")),
    ));

    /* Retrieving Menu from system_plugin directory */
    $sp_dir = scandir(FRONTEND_ROOT . "/system_plugins");
    $sp_dir = array_diff($sp_dir, array('.', '..'));

    foreach ($sp_dir as $key => $value) {
      if (is_file(FRONTEND_ROOT . "/system_plugins/{$value}/plugin-config.php")) {
        $temp = $this->process_plugin_config(FRONTEND_ROOT . "/system_plugins/{$value}/plugin-config.php");
        foreach ($temp as $key => $value) {
          $this->default_menu[] = $value;
        }
      }
    }

    $this->default_menu[] = $this->add_menu_property("General Settings", "settings", "settings/", "icon-cog", "", array('settings'), array("settings/"), array(
      $this->add_menu_property("General Settings", "settings-list", "settings/", "", "", array(), array("settings/*")),
    ));
    
    if (SESSION::get('user_role') == 'super_admin') {
      $this->default_menu[] = $this->add_menu_property('Super Admin  <span class="badge badge-important sidebar-item-cms-patch-notification" style="display: none;"></span>', "system-settings", "system-settings/", "icon-cogs", "", array(), array("system-settings/*", "patcher/*"), array(
        $this->add_menu_property("System Settings", "system-settings-list", "system-settings/", "", "", array(), array("system-settings/*")),
        $this->add_menu_property('Updates <span class="badge badge-important sidebar-item-cms-patch-notification" style="display: none;"></span>', "patcher-list", "patcher/", "", "", array(), array("patcher/*")),
      ));
    }

    $this->cache_current_url = "http://{$_SERVER['HTTP_HOST']}".(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "");
  }

  public function generateMenu( $system_data = array() ){
    $layout = "";
    $layout .= '<ul class="nav nav-list">';
    // print_r (json_encode($this->default_menu)); exit();
    $layout .= $this->menu_layout($this->default_menu, $system_data);
    $layout .= '</ul>';

    echo $layout;
  }

  public function menu_layout( $menu_set=array(), $system_data = array() ){
    $layout = "";

    foreach ($menu_set as $key => $value) {
      if (!isset($system_data['system-type']) || $system_data['system-type']!=(isset($value['system-type'])&&$value['system-type']!=''?$value['system-type']:$system_data['system-type'])) {
        continue;
      }
      if (isset($value['perm']) && count($value['perm'])>0) {
        if (!$this->check_permissions($value['perm'])) {
          continue;
        }
      }


      $has_sub = isset($value['submenu']) && count($value['submenu']) > 0;
      $sub_layout = "";
      
      $link = !$has_sub ? URL . $value['url'] : "#";
      $active = '';
      // foreach ($value['url_indicator'] as $k => $v) {
      //   $active = $this->is_active_menu($v) ? 'active' : $active;
      // }
      $active = $this->is_active_menu($value['url_indicator']) ? 'active' : $active;

      $icon = isset($value['icon']) && $value['icon']!='' ? $value['icon'] : 'icon-double-angle-right';

      $layout .= '<li class="'. $active .'">';
      $layout .= '<a href="'. $link .'" class="dropdown-toggle">';
      $layout .= '<i class="icon '. $icon .'"></i>';
      $layout .= '<span class="menu-text">'. $value['label'] .'</span>';
      $layout .= $has_sub ? '<b class="arrow icon-angle-down"></b>' : "";
      $layout .= '</a>';
      if ($has_sub) {
        $layout .= '<ul class="submenu">';
        $layout .= $this->menu_layout($value['submenu'], $system_data);
        $layout .= '</ul>';
      }
      $layout .= '</li>';
    }
    return $layout;
  }
  private function is_active_menu($menu_patterns = array()){
    $exclude = false;
    $is_active = false;
    foreach ($menu_patterns as $key => $menu_pattern) {
      $c = trim(str_replace(URL, '', $this->cache_current_url),'/');
      $pattern = $menu_pattern;

      if (substr($pattern, -1)=='*') {
        /*any ending url*/
        $p = trim(substr($pattern, 0, -1),'/');
        $cc = trim(substr($c, 0, strlen($p)),'/');

        $is_active = $is_active?true:($p == $cc);
      }elseif (substr($pattern, -1)=='!') {
        /*any ending url*/
        $p = trim(substr($pattern, 0, -1),'/');
        $cc = trim(substr($c, 0, strlen($p)),'/');

        if ($p == $cc) {
          $exclude = true;
          break;
        }

      }else{
        /*exact match*/
        $is_active = $is_active?true:(trim($pattern,'/') == trim($c,'/'));
      }
    }

    return $is_active && !$exclude;
  }

  private function check_permissions($permisson_set = array()){
    $all_valid = false;

    /* Requires once valid permission to pass */
    foreach ($permisson_set as $key => $value) {
      if (Permission::checkAllowedSidebarModule($value)) {
        $all_valid = true;
      }
    }

    return $all_valid;
  }

  /*
  fn: add_menu_property(label, id, url, icon, system_type, perm[], url_indicator[], submenu[])
  */
  private function add_menu_property($label="", $id="", $url="", $icon="", $system_type="", $perm=array(), $url_indicator=array(), $submenu=array()){
    return array(
      "label" => $label, /*The label to display on the sidebar*/
      "id" => $id, /*To be used in sidebar-ui*/
      "url" => $url, /*Will be use of no submenu*/
      "icon" => $icon, /*Will be use of no submenu*/
      "system-type" => $system_type, /*To filter module by system type [CMS, ECOMMERCE, ECATALOG]*/
      "perm" => $perm, /*To filter module by permisson*/
      "url_indicator" => $url_indicator, /* List of URL format that activates the menu*/
      "submenu" => $submenu,
    );
  }

  /*
  fn: process_plugin_config
  desc: this function will extract information from the plugin-config file. Plugin-config files are located inside each directory in system_plugins
  */
  private function process_plugin_config($config_location=""){
    $menu = array();

    $system_plugin_config_content = file_get_contents($config_location);

    preg_match_all("/(Menu:\s*\[.*\]|Sub:\s*\[.*\])/", $system_plugin_config_content, $system_plugin_menus);

    foreach ($system_plugin_menus[1] as $key => $value) {
      preg_match_all("/(.*?):\s*(\[.*\]?)/", $value, $system_plugin_menus_value);

      $menu_data = array();

      if (isset($system_plugin_menus_value[2][0])) {
        preg_match_all('/\[\s*\\"(.*?)\\"\s*,\s*\\"(.*?)\\"\s*,\s*\\"(.*?)\\"\s*,\s*\\"(.*?)\\"\s*,\s*\\"(.*?)\\"\s*,\s*(\[.*?\])\s*,\s*(\[.*?\])\]/', $system_plugin_menus_value[2][0], $v);

        preg_match_all('/\\"(.*?)\\"|\'(.*?)\\\'/', $v[6][0], $permission);
        preg_match_all('/\\"(.*?)\\"|\'(.*?)\\\'/', $v[7][0], $indicators);

        $menu_data = array(
          "label" => isset($v[1][0]) ? $v[1][0] : '', /*The label to display on the sidebar*/
          "id" => isset($v[2][0]) ? $v[2][0] : '', /*To be used in sidebar-ui*/
          "url" => isset($v[3][0]) ? $v[3][0] : '' , /*Will be use of no submenu*/
          "icon" => isset($v[4][0]) ? $v[4][0] : '', /*Will be use of no submenu*/
          "system-type" => isset($v[5][0]) ? $v[5][0] : '', /*To filter module by system type [CMS, ECOMMERCE, ECATALOG]*/
          "perm" => isset($permission[1]) ? $permission[1] : array(), /*To filter module by permisson*/
          "url_indicator" => isset($indicators[1]) ? $indicators[1] : array(), /* List of URL format that activates the menu*/
          "submenu" => array(),
        );
      }

      if (isset($system_plugin_menus_value[1][0]) && $system_plugin_menus_value[1][0] == 'Menu') {
        $menu[] = $menu_data;
      }elseif (isset($system_plugin_menus_value[1][0]) && $system_plugin_menus_value[1][0] == 'Sub') {
        if (isset($menu[count($menu)-1]) && isset($menu[count($menu)-1]['submenu'])) {
          $menu[count($menu)-1]['submenu'][] = $menu_data;
        }
      }
    }

    return $menu;
  }
}