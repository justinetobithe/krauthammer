<?php
include FRONTEND_ROOT . "/plugins/sitemap-php-master/Sitemap.php";
include FRONTEND_ROOT . "/plugins/cms-functions/cms-functions.php";

class XMLSitemap{
  public $uc;
  public $db;
  public $site_url;
  public $cms_current_language;
  public $cms_reserved_language;
  public $cms_default_language;
  public $custom_urls;

  function __construct(){
    $this->db = new Database();
    $this->site_url = trim(get_system_option('site_url'),'/') . "/";
    $this->uc = new UC();
    $this->uc->uc_set_root(FRONTEND_ROOT . "/");
    $this->uc->uc_set_url(FRONTEND_URL . "/");

    $uc = new UC();
    $url_data = $uc->uc_get_current_url_settings($this->site_url);

    $this->cms_default_language = $url_data['language']['slug_default'];

    $res_lang = $this->db->select("Select `id`, if((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guid`, 'cms-language' `type`, `value`, `meta`, `status`, `date_added`, '1' `is_default` From ( Select * From `cms_items` Where `type` = 'cms-language-default' Union ( Select '0' `id`, '0' `guid`, 'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added` ) Order By `id` desc Limit 1 ) `t4`");

    if (count($res_lang)) {
      $this->cms_reserved_language = $res_lang[0]->meta;
    }

    $this->custom_urls = array();
  }

  function generateSiteMap(){
    $this->reset_htaccess_sitemap();

    $sitemap_enable = strtolower(get_system_option('sitemap-enable'));

    /*Stop if sitemap is OFF*/
    if ($sitemap_enable != "on") {return;}

    /*Create directory xmls: where the sitemap file will be placed*/
    if (!is_dir(FRONTEND_ROOT . "/xmls")) { mkdir(FRONTEND_ROOT . "/xmls"); }

    /*Get sitemap URL*/
    $site_url = $this->site_url;

    /*Instantiating sitemap object [plugins/sitemap-php-master/Sitemap.php]*/
    $sitemap = new Sitemap(trim($site_url,'/'));
    /*Setting xml path*/
    $sitemap->setPath(FRONTEND_ROOT . "/xmls/");

    if (!function_exists('strtrim')) {
      function strtrim($a){
        return trim($a, "/");
      }
    }

    /*Get blacklisted urls / url_slugs*/
    $blacklisted = array_map('strtrim', explode("\n", get_system_option('blacklisted_url')));

    /*Retrieve all the urls*/
    $the_pages = $this->get_all_pages();

    foreach ($the_pages as $key => $value) {
      $this->addSitemap($sitemap, $value);
    }

    $sitemap->createSitemapIndex($site_url . "xmls/", 'Today');
  }
  function reset_htaccess_sitemap(){
    $sitemap_enable = get_system_option('sitemap-enable');
    /*Reset Htaccess Sitemap Exepmtion*/
    $htaccessContent = "\n";
    $htaccess = file_get_contents(FRONTEND_ROOT."/.htaccess");
    $htaccess = trim($htaccess);

    $mstarts = "#Sitemap START";
    $mfinish = "#Sitemap END";

    $pos1 = strpos($htaccess, $mstarts) + strlen($mstarts);
    $pos2 = strpos($htaccess, $mfinish);

    if ($pos1 && $pos2) { /*If marker exist*/
      $indent = ""; substr($htaccess, strpos($htaccess, $mstarts)-1, 1);
      $indent_position = 1;

      while ( substr($htaccess, strpos($htaccess, $mstarts)-$indent_position, 1) != "\n" ) {
        $indent .= substr($htaccess, strpos($htaccess, $mstarts)-$indent_position, 1);
        $indent_position++;
      }

      if (strtolower($sitemap_enable) == "on") {
        // $htaccessContent .= $indent."RewriteCond %{REQUEST_FILENAME} !-f\n";
        // $htaccessContent .= $indent."RewriteCond %{REQUEST_FILENAME} !-d\n";
        // $htaccessContent .= $indent."RewriteRule ^sitemap.xml$ xmls/sitemap.xml?url=$1 [L,QSA]\n";
        // $htaccessContent .= $indent."RewriteRule ^sitemap.xsl$ xmls/sitemap.xsl?url=$1 [L,QSA]\n";
        // $htaccessContent .= $indent."RewriteRule ^sitemap-index.xml$ xmls/sitemap-index.xml?url=$1 [L,QSA]\n";
      }
      $htaccessContent .= $indent."RewriteRule ^xmls/(.*)$ index.php?url=$1 [L,QSA]\n";
      $htaccessContent .= $indent;

      $htaccess = substr_replace($htaccess, "{$htaccessContent}", $pos1, $pos2 - $pos1);
      file_put_contents(FRONTEND_ROOT."/.htaccess", $htaccess);
    }
    /*Reset Htaccess Sitemap Exepmtion END*/
  }
  function get_all_pages(){
    $all_pages      = array();

    if (get_system_option('disallow_indexing') != 'YES') {
      $site_url = get_system_option('site_url');

      $sql = "SELECT meta, is_reserved, if(guid=1,'Y','N') is_default 
              FROM (
                (
                  SELECT * FROM (SELECT *, 'N' is_reserved FROM cms_items) cms_items
                  Union All (
                    SELECT '0' id, 
                      if((SELECT count(*) c FROM cms_items WHERE type = 'cms-language' AND guid=1)>0,0,1) guid,
                      'cms-language' type, 
                      ifnull((SELECT value FROM cms_items WHERE type = 'cms-language-default'),'English') value, 
                      ifnull((SELECT meta FROM cms_items WHERE type = 'cms-language-default'),'en') meta, 
                      'active' status, 
                      NOW() date_added,
                      'Y' is_reserved
                  )
                )
              ) t1 WHERE type = 'cms-language' AND status = 'active' ORDER BY FIELD(is_default,'Y','N') ASC;";

      /* Loop: Language */
      foreach ( $this->db->select($sql) as $key => $value ) {
        /* GROUP 1 START: GET PAGES */
        $temp_all_pages = $this->get_url_group_page($site_url, $value->meta, $value->is_default, $value->is_reserved);
        $all_pages      = array_merge($all_pages, $temp_all_pages);
        /* GROUP 1 END:   GET PAGES */

        /* GROUP 2 START: GET POSTS CATEGORIES */
        if (get_system_option('disallow_blog_category_indexing') != "ON") {
          $temp_all_pages = $this->get_url_group_post_categories($site_url, $value->meta, $value->is_default, $value->is_reserved);
          $all_pages      = array_merge($all_pages, $temp_all_pages);
        }
        /* GROUP 2 END: GET POSTS CATEGORIES */

        /* GROUP 3 START: GET POSTS */
        if (get_system_option('disallow_blog_post_indexing') != "ON") {
          $temp_all_pages = $this->get_url_group_posts($site_url, $value->meta, $value->is_default, $value->is_reserved);
          $all_pages      = array_merge($all_pages, $temp_all_pages);
        }
        /* GROUP 3 END: GET POSTS */

        /* GROUP 4 START: GET PRODUCT CATEGORIES */
        if (get_system_option('product_no_index_category_page') != "Y") {
          $temp_all_pages = $this->get_url_group_product_categories($site_url, $value->meta, $value->is_default, $value->is_reserved);
          $all_pages      = array_merge($all_pages, $temp_all_pages);
        }
        /* GROUP 4 END: GET PRODUCT CATEGORIES */

        /* GROUP 5 START: GET PRODUCT */
        if (get_system_option('product_no_index_detail_page') != "Y") {
          $temp_all_pages = $this->get_url_group_products($site_url, $value->meta, $value->is_default, $value->is_reserved);
          $all_pages      = array_merge($all_pages, $temp_all_pages);
        }
        /* GROUP 5 END: GET PRODUCT */

      }
    }

    $custom_pages = $this->get_custom_pages();
    
    $all_pages = array_merge($all_pages, $custom_pages);

    return $all_pages;
  }
  function get_url_group_page($site_url = '',$language = '', $is_default = 'N', $is_reserved='N'){
    $all_pages      = array();
    $all_pages_temp = array();
    $temppages_temp = array();
    $pages          = array();

    $slash          = substr($site_url, -1)=='/'?'/':'';
    $site_url       = trim($site_url,'/');

    $base_language  = $is_default  == 'Y' ? "" : "/{$language}";
    $join_type      = $is_reserved == 'Y' ? "LEFT" : "INNER";

    $sql = "SELECT c.id, ifnull(t.url_slug, c.url_slug) url_slug, c.parent_id, if(s.option_name='homepage','Y','N') is_home
            FROM cms_posts c
            {$join_type} JOIN cms_posts_translate t on t.post_id = c.id and t.language = '{$language}'
            LEFT JOIN system_options s ON c.id = s.option_value and s.option_name = 'homepage'
            WHERE c.post_status = 'active' and c.post_type = 'page' and c.seo_no_index <> 'Y'
            ORDER BY FIELD(is_home, 'Y','N')";

    /* Caching pages for faster tracing of page heirarchy */
    foreach ($this->db->select($sql) as $key => $value) {
      $pages[$value->id] = $value;
    }

    /* Loop: adding page and info */
    foreach ($pages as $key => $value) {
      $current_page = $value;
      $url = "/{$current_page->url_slug}";

      /* Get: panret slug */
      while ($current_page->parent_id != 0) {
        if (isset($pages[$current_page->parent_id])) {
          $current_page = $pages[$current_page->parent_id];
          $url          = "/{$current_page->url_slug}{$url}";
        }else{
          break;
        }
      }
      
      $temppages_temp[] = $value->is_home=='Y' ? "{$site_url}{$base_language}{$slash}" : "{$site_url}{$base_language}{$url}{$slash}";
      $all_pages_temp[ $value->is_home=='Y' ? "{$site_url}{$base_language}{$slash}" : "{$site_url}{$base_language}{$url}{$slash}" ] = array(
        'url'       => $value->is_home=='Y' ? "{$site_url}{$base_language}{$slash}" : "{$site_url}{$base_language}{$url}{$slash}",
        'freq'      => $value->is_home=='Y' ? 'daily' : 'monthly',
        'priority'  => $value->is_home=='Y' ? '1' : '0.6',
      );
    }
    sort($temppages_temp);

    foreach ($temppages_temp as $key => $value) {
      if (isset($all_pages_temp[$value])) {
        $all_pages[] = $all_pages_temp[$value];
      }
    }

    return $all_pages;
  }
  function get_url_group_post_categories($site_url = '',$language = '', $is_default = 'N', $is_reserved='N'){
    $all_pages      = array();
    $all_pages_temp = array();
    $temppages_temp = array();
    $categories     = array();

    $slash          = substr($site_url, -1)=='/'?'/':'';
    $site_url       = trim($site_url,'/');

    $base_language  = $is_default  == 'Y' ? "" : "/{$language}";
    $join_type      = $is_reserved == 'Y' ? "LEFT" : "INNER";

    $sql = "SELECT c.id, c.url_slug, c.category_parent, t.meta
            FROM post_category c
            {$join_type} JOIN cms_translation t on t.guid = c.id and t.language = '{$language}' and type = 'post-category'
            WHERE c.status = 'active'";

    /* Caching post categories for faster tracing of category heirarchy */
    foreach ($this->db->select($sql) as $key => $value) {
      $categories[$value->id] = $value;
    }

    /* Loop: adding category and info */
    foreach ($categories as $key => $value) {
      $current_item = $value;
      $m            = json_decode($current_item->meta);
      $url          = "/" . (isset($m->url_slug) ? $m->url_slug : $current_item->url_slug);

      while ($current_item->category_parent != 0) {
        if (isset($categories[$current_item->category_parent])) {
          $current_item = $categories[$current_item->category_parent];
          $m            = json_decode($current_item->meta);
          $url          = "/" . (isset($m->url_slug) ? $m->url_slug : $current_item->url_slug) . "{$url}";
        }else{
          break;
        }
      }
      
      $temppages_temp[] = "{$site_url}{$base_language}/categories{$url}{$slash}";
      $all_pages_temp[ "{$site_url}{$base_language}/categories{$url}{$slash}" ] = array(
        'url'       => "{$site_url}{$base_language}/categories{$url}{$slash}",
        'freq'      => 'monthly',
        'priority'  => '0.6',
      );
    }
    sort($temppages_temp);

    foreach ($temppages_temp as $key => $value) {
      if (isset($all_pages_temp[$value])) {
        $all_pages[] = $all_pages_temp[$value];
      }
    }

    return $all_pages;
  }
  function get_url_group_posts($site_url = '',$language = '', $is_default = 'N', $is_reserved='N'){
    $all_pages      = array();
    $all_pages_temp = array();
    $slash          = substr($site_url, -1)=='/'?'/':'';
    $site_url       = trim($site_url,'/');

    $base_language  = $is_default  == 'Y' ? "" : "/{$language}";
    $join_type      = $is_reserved == 'Y' ? "LEFT" : "INNER";
    $pages          = array();

    $sql = "SELECT c.id, ifnull(t.url_slug, c.url_slug) url_slug, c.parent_id
            FROM cms_posts c
            {$join_type} JOIN cms_posts_translate t on t.post_id = c.id and t.language = '{$language}'
            WHERE c.post_status = 'active' and c.post_type = 'post' and c.seo_no_index <> 'Y'
            ORDER BY url_slug ASC";

    /* Caching pages for faster tracing of page heirarchy */
    foreach ($this->db->select($sql) as $key => $value) {
      $pages[$value->id] = $value;
    }

    /* Loop: adding page and info */
    foreach ($pages as $key => $value) {
      $url = "/{$value->url_slug}";
      
      $all_pages[] = array(
        'url'       => "{$site_url}{$base_language}{$url}{$slash}",
        'freq'      => 'monthly',
        'priority'  => '0.6',
      );
    }

    return $all_pages;
  }
  function get_url_group_product_categories($site_url = '',$language = '', $is_default = 'N', $is_reserved='N'){
    $all_pages      = array();
    $all_pages_temp = array();
    $temppages_temp = array();
    $categories     = array();

    $slash          = substr($site_url, -1)=='/'?'/':'';
    $site_url       = trim($site_url,'/');

    $base_language  = $is_default  == 'Y' ? "" : "/{$language}";
    $join_type      = $is_reserved == 'Y' ? "LEFT" : "INNER";

    $base_prod_cat  = get_system_option("product_category_url" . ($is_reserved=='Y'?"":"_{$language}"));
    $base_prod_cat  = $base_prod_cat == '' ? 'product-category' : $base_prod_cat;

    $sql = "SELECT c.id, c.url_slug, c.category_parent, t.meta
            FROM product_categories c
            {$join_type} JOIN cms_translation t on t.guid = c.id and t.language = '{$language}' and type = 'product-category'";

    /* Caching product categories for faster tracing of category heirarchy */
    foreach ($this->db->select($sql) as $key => $value) {
      $categories[$value->id] = $value;
    }

    /* Loop: adding category and info */
    foreach ($categories as $key => $value) {
      $current_page = $value;
      $m            = json_decode($current_page->meta);
      $url          = "/" . (isset($m->url_slug) ? $m->url_slug : $current_page->url_slug);

      while ($current_page->category_parent != 0) {
        if (isset($categories[$current_page->category_parent])) {
          $current_page = $categories[$current_page->category_parent];
          $m            = json_decode($current_page->meta);
          $url          = "/" . (isset($m->url_slug) ? $m->url_slug : $current_page->url_slug) . "{$url}";
        }else{
          break;
        }
      }
      
      $temppages_temp[] = "{$site_url}{$base_language}/{$base_prod_cat}{$url}{$slash}";
      $all_pages_temp[ "{$site_url}{$base_language}/{$base_prod_cat}{$url}{$slash}" ] = array(
        'url'       => "{$site_url}{$base_language}/{$base_prod_cat}{$url}{$slash}",
        'freq'      => 'monthly',
        'priority'  => '0.6',
      );
    }
    sort($temppages_temp);

    foreach ($temppages_temp as $key => $value) {
      if (isset($all_pages_temp[$value])) {
        $all_pages[] = $all_pages_temp[$value];
      }
    }

    return $all_pages;
  }
  function get_url_group_products($site_url = '',$language = '', $is_default = 'N', $is_reserved='N'){
    $all_pages      = array();
    $all_pages_temp = array();
    $temppages_temp = array();
    $categories     = array();

    $slash          = substr($site_url, -1)=='/'?'/':'';
    $site_url       = trim($site_url,'/');

    $base_language  = $is_default  == 'Y' ? "" : "/{$language}";
    $join_type      = $is_reserved == 'Y' ? "LEFT" : "INNER";

    $base_prod      = get_system_option("product_url" . ($is_reserved=='Y'?"":"_{$language}"));
    $base_prod      = $base_prod == '' ? 'products' : $base_prod;

    $sql = "SELECT c.id, c.url_slug, t.meta
            FROM products c
            {$join_type} JOIN cms_translation t on t.guid = c.id and t.language = '{$language}' and type = 'product' 
            WHERE c.product_status = 'active'";

    /* Caching product categories for faster tracing of category heirarchy */
    foreach ($this->db->select($sql) as $key => $value) {
      $categories[$value->id] = $value;
    }

    /* Loop: adding category and info */
    foreach ($categories as $key => $value) {
      $current_page = $value;
      $m            = json_decode($current_page->meta);

      // if (!isset($m->product) || !isset($m->product->url_slug)) {
      //   continue;
      // }
      $url          = "/" . (isset($m->product) && isset($m->product->url_slug) ? $m->product->url_slug : $current_page->url_slug);
      
      $all_pages[] = array(
        'url'       => "{$site_url}{$base_language}/{$base_prod}{$url}{$slash}",
        'freq'      => 'monthly',
        'priority'  => '0.6',
      );
    }
    sort($temppages_temp);

    return $all_pages;
  }

  function get_custom_pages(){
    $system_plugin_dir = FRONTEND_ROOT . "/system_plugins";
    foreach (scandir($system_plugin_dir) as $key => $value) {
      if (is_file($system_plugin_dir . "/{$value}/libraries/sitemap-register-url.php")) {
        include $system_plugin_dir . "/{$value}/libraries/sitemap-register-url.php";
      }
    }
    return $this->custom_urls;
  }
  function sitemap_register_url($a, $type = 'page'){
    foreach ($a as $key => $value) {
      $this->custom_urls[] = array(
        'url' => $value,
        'type' => $type,
      );
    }
  }
  function validate_page($page_url = ""){
    /* ------------------------- */
    /* getting the url */
    $uc = new UC();
    $uc->uc_set_root(FRONTEND_ROOT . "/");
    $uc->uc_set_url(FRONTEND_URL . "/");

    $url_data = $uc->uc_get_current_url_settings($page_url);

    $url = explode('/', trim($url_data['language']['slug_url'],'/'));

    $this->cms_current_language = $url_data['language']['exist'] && $url_data['language']['slug_selected'] != '' ? $url_data['language']['slug_selected'] : $url_data['language']['slug_default'];

    // $this->formatRedirect();
    /* ------------------------- */

    /* formatdiredirect start*/
    $uc->set_current_language($this->cms_current_language);
    $res = $uc->uc_get_url_validity_info_2($page_url);
    $res['lang'] = $this->cms_current_language;

    return $res;

    if ($url_data['url_header'] == '301') {

    }elseif($url_data['url_header'] == '404'){

    }else{
      if (get_system_option('enable_https_redirect')=='ON') {
        if ($_SERVER['REQUEST_SCHEME'] != 'https') {
          redirect("https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
        }
      }
    }
    /* formatdiredirect end*/

    $post_url_info = $this->uc->uc_get_url_validity_info_2($page_url);
    return $post_url_info;
  }

  function ping_google(){
    $site_url = get_system_option("site_url");
    $indexing = get_system_option('disallow_indexing') == 'NO';
    $ping_success = false;

    if ($indexing) {
      $google_ping_url = "http://google.com/ping?sitemap=" . trim($site_url, "/") . "/sitemap.xml";

      /*step1*/
      $cSession = curl_init(); 
      /*step2*/
      curl_setopt($cSession,CURLOPT_URL,$google_ping_url);
      curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($cSession,CURLOPT_HEADER, false); 
      /*step3*/
      $result=curl_exec($cSession);

      $info = curl_getinfo($cSession);

      if ($info['http_code'] == 200) {
        $ping_success = true;
      }
      /*step4*/
      curl_close($cSession);
      /*step5*/

      if ($ping_success) {
        $this->sitemap_option(array('ping-google' => true));
      }else{
        $this->sitemap_option(array('ping-google' => false));
      }
      $this->sitemap_option(array('last-google-ping' => date("Y-m-d H:i:s")));
    }

    return $ping_success;
  }
  function addSitemap($sitemap_instance, $data){
    $value = isset($data['url']) ? $data['url'] : ''; /*default priority*/
    $priority = isset($data['priority']) ? $data['priority'] : '0.6'; /*default priority*/
    $freq = isset($data['freq']) ? $data['freq'] : 'daily'; /*default priority*/
    $lastmod = isset($data['lastmod']) ? $data['lastmod'] : date("Y-m-d"); /*default priority*/

    $page_url = $value;

    /*$sitemap_instance->addItem($page_url, $priority, $freq, $lastmod);*/
    $sitemap_instance->addItem($page_url, $priority, $freq, $lastmod);
  }
  function sitemap_option($data = array()){
    if (count($data)) {
      $sitemap_option = $this->db->select("Select * From `system_options` Where `option_name` = 'sitemap-options'");
      
      $this->db->table = 'system_options';
      if (count($sitemap_option)) {
        $s_data = json_decode($sitemap_option[0]->meta_data);

        foreach ($data as $key => $value) {
          $s_data->$key = $value;
        }

        /* Saving system option*/
        $this->db->data = array(
          "id" => $sitemap_option[0]->id,
          "meta_data" => json_encode($s_data),
          );
        $this->db->update();
      }else{
        $s_data = array();
        foreach ($data as $key => $value) {
          $s_data[$key] = $value;
        }

        $this->db->data = array(
          "option_name" => "sitemap-options",
          "option_value" => "",
          "auto_load" => "no",
          "meta_data" => json_encode($s_data),
          );
        $this->db->insertGetID();
      }
    }else{
      $temp = $this->db->select("Select * From `system_options` Where `option_name` = 'sitemap-options'");
      $temp = count($temp) ? json_decode($temp[0]->meta_data) : array();

      $sitemap_option = array(
        "ping-google" => isset($sitemap_option->{'ping-google'}) ? $sitemap_option->{'ping-google'} : false,
        'last-google-ping' => isset($sitemap_option->{'last-google-ping'}) ? $sitemap_option->{'last-google-ping'} : "unknown",
        );

      return $sitemap_option;
    }
  }
  function replace_url($prev="", $new="", $disabled_by_seo = false){
    $sitemapxml_file = ROOT . "../xmls/sitemap.xml";
    $sitemapxml_content = file_get_contents($sitemapxml_file);
    if (strpos($sitemapxml_content, $prev)) {
      if (!$disabled_by_seo) {
        $sitemapxml_content = str_replace($prev, $new, $sitemapxml_content);
        file_put_contents($sitemapxml_file,$sitemapxml_content);
      }else{
        $this->generateSiteMap();
      }
    }else{
      if (!$disabled_by_seo) {
        $this->generateSiteMap();
      }
    }
  }
  function update(){
    if (get_system_option('sitemap-enable') == 'ON') {
      /* Auto Generate Sitemap */
      $this->generateSiteMap();

      if (get_system_option('sitemap-auto-ping-google') == 'ON') {
        $this->ping_google();
      }
    }
  }
}