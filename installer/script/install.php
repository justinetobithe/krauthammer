<?php
/*
This is a installer/extractor for CMS
*/

$install_location	= trim(__DIR__,'/') . "/../../";
$cms_sql 					= $install_location . "installer/script/cms.sql";

if (isset($_POST['install'])) {
	$zip = new ZipArchive;
	if ($zip->open($install_location.'installer/cms.zip') === TRUE) {
		if (!is_dir($install_location . '')) {
			mkdir($install_location . '');
		}

		$zip->extractTo($install_location . '');
		_process_database();
		_setup_configuration();
		_setup_configuration_htaccess();
		_setup_configuration_admin();
		_setup_configuration_admin_htaccess();

		echo 'ok';
	} else {
		echo 'failed';
	}
}elseif (isset($_POST['check'])) {
	$conn = new mysqli($_POST['dbconfig-host'], $_POST['dbconfig-user'], $_POST['dbconfig-pass'], $_POST['dbconfig-db']);
	/*Check Connection*/
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$dbs = array(
		"'system_options'", 
	);
	$res = array();

	$dbs_set = implode(',', $dbs);
	$dbname = $_POST['dbconfig-db'];
	$sql = "Show TABLES Where Tables_in_{$dbname} IN ({$dbs_set})";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$res[] = $row;
		}
	}
	$conn->close();

	if (count($res) == count($dbs)) {
		echo "1";
	}else{
		echo "0";
	}
}elseif (isset($_POST['install-check-db'])) {
	$_conn = new mysqli($_POST['dbconfig-host'], $_POST['dbconfig-user'], $_POST['dbconfig-pass'], $_POST['dbconfig-db']);
	/*Check Connection*/
	if ($_conn->connect_error) {
		die("Connection failed: " . $_conn->connect_error);
	}
	$_conn->close();

	echo "ok";
}elseif (isset($_POST['install-default'])) {
	_process_database_defaults();
	_process_default_items();
	echo 'ok';
}

function set_option($key, $val, $meta=''){
	/*Setting system option values*/
	return _query("INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`) VALUES('{$key}', '{$val}', '{$meta}') ON DUPLICATE KEY UPDATE `option_value`='{$val}', `meta_data`='{$meta}'");
}
function _query($sql){
	/*Connecting Databae*/
	$conn = new mysqli($_POST['dbconfig-host'], $_POST['dbconfig-user'], $_POST['dbconfig-pass'], $_POST['dbconfig-db']);

	/*Check Connection*/
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	if ($conn->multi_query($sql) === TRUE) {
		$conn->close();
		return true;
	} else {
		$conn->close();
		return false;
	}
}
function _start_import($file){
	$lines = file($file);
	$templine = '';
	$_sql = file_get_contents($file);
	_query($_sql);
}
function _setup_configuration(){
	global $install_location;
	$front_config = fopen($install_location . 'config.php',"w");

	$config_content  = "<?php " . "\n";
	$config_content .= "session_start();" . "\n";
	$config_content .= "" . "\n";

	$config_content .= "/* CHECKS PROTOCOL */" . "\n";
	$config_content .= "define('URL_PROTOCOL', isset(\$_SERVER['HTTPS']) ? 'https' : 'http');" . "\n";

	$config_content .= "define('DB_HOST','{$_POST['dbconfig-host']}');" . "\n";
	$config_content .= "define('DB_USER','{$_POST['dbconfig-user']}');" . "\n";
	$config_content .= "define('DB_PASS','{$_POST['dbconfig-pass']}');" . "\n";
	$config_content .= "define('DB_NAME','{$_POST['dbconfig-db']}');" . "\n";
	$config_content .= "" . "\n";

	$_u = trim(preg_replace('/https?:\/*/', '', $_SERVER['HTTP_REFERER']),'/');

	$config_content .= "define('URL', URL_PROTOCOL.'://". $_u ."/');" . "\n";
	$config_content .= "define('ROOT', realpath(dirname(__FILE__) . '/') . '/');" . "\n";
	$config_content .= "define('LIBS','libraries/');" . "\n";
	$config_content .= "define('PLUGINS',ROOT.'plugins/');" . "\n";
	$config_content .= "define('ACTIVE_THEME','default');" . "\n";
	$config_content .= "define('PATH',__DIR__.'/../assets/');" . "\n";

	$config_content .= "" . "\n";

  fwrite($front_config,$config_content);
  fclose($front_config);
}
function _setup_configuration_htaccess(){
	global $install_location;
	$front_config = fopen($install_location . '/.htaccess',"w");

	$_u = trim(preg_replace("/https?:\/*/", '', $_SERVER['HTTP_REFERER']),'/');
	$_u = trim(str_replace($_SERVER['HTTP_HOST'], '', $_u),'/');
	$_u = $_u!='/'?$_u.'/':'';

	$config_content  = "";
	$config_content .= "<IfModule mod_rewrite.c>" . "\n";
	$config_content .= "	RewriteEngine On " . "\n";
	$config_content .= "	RewriteBase /{$_u}/" . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-d " . "\n";
	$config_content .= "	RewriteRule ^controllers/(.*)$ index.php?url=$1 [L,QSA]" . "\n";
	$config_content .= "	RewriteRule ^libraries/(.*)$ index.php?url=$1 [L,QSA]" . "\n";
	$config_content .= "	RewriteRule ^models/(.*)$ index.php?url=$1 [L,QSA]" . "\n";
	$config_content .= "	RewriteRule ^admin/(.*)$ index.php?url=$1 [L,QSA]" . "\n";

	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-f" . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-d" . "\n";
	$config_content .= "	RewriteRule ^thumbnails/(.*)$ thumbnails/index.php?thumb=$1 [L,QSA]" . "\n";

	$config_content .= "	#Sitemap START" . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-f" . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-d" . "\n";
	$config_content .= "	RewriteRule ^sitemap.xml$ xmls/sitemap.xml?url=$1 [L,QSA]" . "\n";
	$config_content .= "	RewriteRule ^sitemap.xsl$ xmls/sitemap.xsl?url=$1 [L,QSA]" . "\n";
	$config_content .= "	RewriteRule ^sitemap-index.xml$ xmls/sitemap-index.xml?url=$1 [L,QSA]" . "\n";
	$config_content .= "	#Sitemap END" . "\n";

	$config_content .= "	#RewriteCond %{REQUEST_URI} !\.(js|ico|gif|jpg|png|css|swf|flv|xml|woff2|woff|ttf)$" . "\n";
	$config_content .= "	#RewriteCond $1 !^(index\.php|images|robots\.txt)" . "\n";
	$config_content .= "	#RewriteRule ^controllers/(.*)$ index.php?url=$1 [L,QSA]" . "\n";

	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-d " . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-f " . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-l " . "\n";
	$config_content .= "	RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]" . "\n";
	$config_content .= "</IfModule>" . "\n";
	$config_content .= "Options -indexes" . "\n";


  fwrite($front_config,$config_content);
  fclose($front_config);
}
function _setup_configuration_admin(){
	global $install_location;
	$front_config = fopen($install_location . '/admin/config.php',"w");

	$config_content  = "<?php " . "\n";
	$config_content .= "session_start();" . "\n";
	$config_content .= "" . "\n";

	$config_content .= "define('DB_HOST','{$_POST['dbconfig-host']}');" . "\n";
	$config_content .= "define('DB_USER','{$_POST['dbconfig-user']}');" . "\n";
	$config_content .= "define('DB_PASS','{$_POST['dbconfig-pass']}');" . "\n";
	$config_content .= "define('DB_NAME','{$_POST['dbconfig-db']}');" . "\n";
	$config_content .= "" . "\n";

	$_u = trim(preg_replace("/https?:\/*/", '', $_SERVER['HTTP_REFERER']),'/');
	$_fu = trim(str_replace($_SERVER['HTTP_HOST'], '', $_u),'/');
	$_fu = $_fu!='/'?'/'.$_fu:'';

	$config_content .= "define('URL', 'http://". $_u ."/admin/');" . "\n";
	$config_content .= "define('ROOT', realpath(dirname(__FILE__) . '/../') . '/admin/');" . "\n";
	$config_content .= "define('FRONTEND_ROOT',realpath(dirname(__FILE__) . '/../'));" . "\n";
	$config_content .= "define('FRONTEND_URL', rtrim('http://". $_SERVER['HTTP_HOST'] ."{$_fu}/','/'));" . "\n";
	$config_content .= "define('LIBS','libraries/');" . "\n";
	$config_content .= "define('ACTIVE_THEME','default');" . "\n";
	$config_content .= "define('PATH',__DIR__.'/../assets/');" . "\n";
	$config_content .= "" . "\n";
	
	$config_content .= "define('PATCH_SOURCE', 'http://cmsupdates.kb.sg/cms-patcher/');" . "\n";
	$config_content .= "" . "\n";


  fwrite($front_config,$config_content);
  fclose($front_config);
}
function _setup_configuration_admin_htaccess(){
	global $install_location;
	$front_config = fopen($install_location . '/admin/.htaccess',"w");

	$_u = trim(preg_replace("/https?:\/*/", '', $_SERVER['HTTP_REFERER']),'/');
	$_u = trim(str_replace($_SERVER['HTTP_HOST'], '', $_u),'/');
	$_u = $_u!='/'?$_u.'/':'';

	$config_content  = "";
	$config_content .= "<IfModule mod_rewrite.c>" . "\n";
	$config_content .= "	RewriteEngine On " . "\n";
	$config_content .= "	RewriteBase /{$_u}admin/" . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-d " . "\n";
	$config_content .= "	RewriteRule ^controllers/(.*)$ index.php?url=$1 [L,QSA]" . "\n";
	$config_content .= "	RewriteRule ^libraries/(.*)$ index.php?url=$1 [L,QSA]" . "\n";
	$config_content .= "	RewriteRule ^models/(.*)$ index.php?url=$1 [L,QSA]" . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-d " . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-f " . "\n";
	$config_content .= "	RewriteCond %{REQUEST_FILENAME} !-l " . "\n";
	$config_content .= "	RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]" . "\n";
	$config_content .= "</IfModule>" . "\n";
	$config_content .= "Options -indexes" . "\n";


  fwrite($front_config,$config_content);
  fclose($front_config);
}
function _process_database() {
	global $cms_sql;
	_start_import($cms_sql);
}
function _process_database_defaults() {
	$system_options = array(
		'website_logo' 															=> array('value'=>'','meta'=>''), 
		'website_footer_copyright_text' 						=> array('value'=>"Copyright © 2017 {$_POST['web-name']}. All Rights Reserved",'meta'=>''), 
		'system_email' 															=> array('value'=>$_POST['web-email'],'meta'=>''), 
		'system_email_name' 												=> array('value'=>$_POST['web-name'],'meta'=>''), 
		'enquiry_form_email' 												=> array('value'=>$_POST['web-email'],'meta'=>''), 
		'category_page_display_view' 								=> array('value'=>'LIST','meta'=>''), 
		'category_page_display_order' 							=> array('value'=>'FEATURED_LISTING_TOP','meta'=>''), 
		'listing_page_display_related_items_count'	=> array('value'=>'8','meta'=>''), 
		'customer_login_required' 									=> array('value'=>'OFF','meta'=>''), 
		'google_event_tracking' 										=> array('value'=>'OFF','meta'=>''), 
		'google_analytics_code' 										=> array('value'=>'','meta'=>''), 
		'conversion_tracking_code' 									=> array('value'=>'','meta'=>''), 
		'system_type' 															=> array('value'=>'CMS', 'meta'=>'["posts","pages","enquiries","appearance","users","settings"]'), 
		'currency_symbol' 													=> array('value'=>'$','meta'=>''), 
		'currency_code' 														=> array('value'=>'SGD','meta'=>''), 
		'invoice_currency_name' 										=> array('value'=>$_POST['web-name'],'meta'=>''), 
		'invoice_company_address' 									=> array('value'=>'','meta'=>''), 
		'invoice_number_prefix' 										=> array('value'=>$_POST['web-name'] . '-','meta'=>''), 
		'invoice_next_number' 											=> array('value'=>'111','meta'=>''), 
		'company_name' 															=> array('value'=>$_POST['web-name'],'meta'=>''), 
		'company_address' 													=> array('value'=>'','meta'=>''), 
		'company_contact_number' 										=> array('value'=>'','meta'=>''), 
		'company_fax_number' 												=> array('value'=>'','meta'=>''), 
		'company_email' 														=> array('value'=>$_POST['web-email'],'meta'=>''), 
		'business_registration_number' 							=> array('value'=>'','meta'=>''), 
		'disallow_indexing' 												=> array('value'=>'YES','meta'=>''), 
		'website_name' 															=> array('value'=>$_POST['web-name'],'meta'=>''), 
		'shipping_origin_postal' 										=> array('value'=>'','meta'=>''), 
		'shipping_origin_address_2' 								=> array('value'=>'','meta'=>''), 
		'shipping_origin_address_1' 								=> array('value'=>'','meta'=>''), 
		'shipping_origin_name' 											=> array('value'=>'','meta'=>''), 
		'shipping_origin_city' 											=> array('value'=>'','meta'=>''), 
		'shipping_origin_country' 									=> array('value'=>'','meta'=>''), 
		'shipping_origin_phone' 										=> array('value'=>'','meta'=>''), 
		'frontend_theme' 														=> array('value'=>'default','meta'=>''), 
		'blog_post_limit' 													=> array('value'=>'3','meta'=>''), 
		'homepage' 																	=> array('value'=>'1','meta'=>''), 
		'blog_page' 																=> array('value'=>'1','meta'=>''), 
		'system_patch_version' 											=> array('value'=>'0','meta'=>''), 
		// 'site_url' 																	=> array('value'=>"{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}",'meta'=>''), 
		'site_url' 																	=> array('value'=>"{$_SERVER['HTTP_REFERER']}",'meta'=>''), 
		'mailchimp-api-key' 												=> array('value'=>'','meta'=>''), 
		'mailchimp-checkbox-label' 									=> array('value'=>'Subscribe Me!!!','meta'=>''), 
		'mailchimp-checkbox-default' 								=> array('value'=>'No','meta'=>''), 
		'mailchimp-autoupdate' 											=> array('value'=>'Yes','meta'=>''), 
		'customer_login_enable' 										=> array('value'=>'N','meta'=>''), 
		'product_no_index_ecommerce_ecatalog' 			=> array('value'=>'Y','meta'=>''), 
		'product_no_index_category_page' 						=> array('value'=>'Y','meta'=>''), 
		'product_no_index_detail_page' 							=> array('value'=>'Y','meta'=>''), 
		'product_no_index_cart' 										=> array('value'=>'Y','meta'=>''), 
		'product_no_index_checkout' 								=> array('value'=>'Y','meta'=>''), 
		'product_no_index_order_enquiry' 						=> array('value'=>'Y','meta'=>''), 
		'product_home_page' 												=> array('value'=>'0','meta'=>''), 
		'product_cart_page' 												=> array('value'=>'0','meta'=>''), 
		'product_checkout_page' 										=> array('value'=>'0','meta'=>''), 
		'product_enquire_page' 											=> array('value'=>'0','meta'=>''), 
		'product_confirmation_page'									=> array('value'=>'0','meta'=>''), 
		'product_confirmed_page' 										=> array('value'=>'0','meta'=>''), 
		'product_payment_method_page' 							=> array('value'=>'0','meta'=>''), 
		'system_robot_txt' 													=> array('value'=>'','meta'=>''), 
		'sitemap-enable' 														=> array('value'=>'OFF','meta'=>''), 
		'post_url_format' 													=> array('value'=>'postname','meta'=>''), 
		'blacklisted_url' 													=> array('value'=>'','meta'=>''), 
		'disallow_blog_indexing' 										=> array('value'=>'OFF','meta'=>''), 
		'disallow_blog_post_indexing' 							=> array('value'=>'OFF','meta'=>''), 
		'disallow_blog_search_indexing' 						=> array('value'=>'ON','meta'=>''), 
		'disallow_blog_pagination_indexing' 				=> array('value'=>'ON','meta'=>''), 
		'disallow_blog_category_indexing' 					=> array('value'=>'ON','meta'=>''), 
		'enable_https_redirect' 										=> array('value'=>'OFF','meta'=>''), 
		'enable_customer_registration' 							=> array('value'=>'OFF','meta'=>''), 
		'ecommerce-enable-delivery-detail' 					=> array('value'=>'Y','meta'=>''), 
		'ecommerce-self-collection-discount' 				=> array('value'=>'','meta'=>''), 
		'ecommerce-normal-delivery-charge' 					=> array('value'=>'','meta'=>''), 
		'ecommerce-normal-delivery-time' 						=> array('value'=>'','meta'=>''), 
		'ecommerce-express-delivery-surcharge' 			=> array('value'=>'','meta'=>''), 
		'ecommerce-shipping-detail-enable' 					=> array('value'=>'N','meta'=>''), 
		'ecommerce-billing-detail-enable' 					=> array('value'=>'N','meta'=>''), 
		'sitemap-options' 													=> array('value'=>'','meta'=>''), 
		'sitemap-auto-ping-google' 									=> array('value'=>'OFF','meta'=>''), 
		'product_url' 															=> array('value'=>'products','meta'=>''), 
		'product_category_url' 											=> array('value'=>'product-category','meta'=>''), 
	);

	foreach ($system_options as $key => $value) {
		set_option($key, isset($value['value'])?$value['value']:'', isset($value['meta'])?$value['meta']:'');
	}
}
function _process_default_items(){
	$current_time = date("Y-m-d H:i:s");

	/* Add sample page */
	_query("INSERT INTO `cms_posts` (`id`, `post_author`, `post_date`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_type`, `url_slug`, `old_slug`, `seo_canonical_url`, `page_template`, `seo_title`, `seo_description`, `seo_no_index`, `parent_id`, `status`, `featured_image`, `featured_image_crop`, `featured_image_crop_data`, `meta_data`, `date_added`) VALUES (1, 1, '{$current_time}', '<p><strong>Lorem Ipsum</strong><span><span>&nbsp;</span>is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>', 'Sample Page', '', 'active', 'page', 'sample-page', '', '', 'page', 'Sample Page', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'N', 0, 'publish', NULL, NULL, NULL, '{}', '{$current_time}'), (2, 1, '{$current_time}', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'Sample Post', NULL, 'active', 'post', 'sample-post', '', NULL, 'post', 'Sample Post', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'N', 0, 'publish', NULL, NULL, NULL, NULL, '{$current_time}');");

	_query("INSERT INTO `cms_items` (`id`, `guid`, `type`, `value`, `meta`, `status`, `date_added`) VALUES (1, 0, 'cms-widget', 'primary-sidebar', '{\"widget_id\":\"text\",\"order\":1,\"fields\":[{\"type\":\"text\",\"val\":\"Text Sidebar\"},{\"type\":\"textarea\",\"val\":\"<p>Lorem ipsum dolor sit amet, duo at quis labores corrumpit, nec ne scaevola interpretaris, quem fuisset posidonium mei te.<\\/p>\"}]}', 'active', '2017-10-30 06:21:09'), (2, 0, 'cms-widget', 'primary-sidebar', '{\"widget_id\":\"pages\",\"order\":3,\"fields\":[{\"type\":\"text\",\"val\":\"Pages\"},{\"type\":\"dropdown\",\"val\":\"page-id\"},{\"type\":\"text\",\"val\":\"\"}]}', 'active', '2017-10-30 06:29:24'), (3, 0, 'cms-widget', 'primary-sidebar', '{\"widget_id\":\"recent_comments\",\"order\":5,\"fields\":[{\"type\":\"text\",\"val\":\"Comments\"},{\"type\":\"text\",\"val\":\"4\"}]}', 'active', '2017-10-30 06:29:41'), (4, 0, 'cms-widget', 'primary-sidebar', '{\"widget_id\":\"recent_posts\",\"order\":4,\"fields\":[{\"type\":\"text\",\"val\":\"Posts\"},{\"type\":\"text\",\"val\":\"3\"},{\"type\":\"checkbox\",\"val\":\"Y\"}]}', 'active', '2017-10-30 06:30:24'), (5, 0, 'cms-widget', 'primary-sidebar', '{\"widget_id\":\"html\",\"order\":6,\"fields\":[{\"type\":\"text\",\"val\":\"HMTL\"},{\"type\":\"textarea-plain\",\"val\":\"This is a sample <b>html widget<\\/b>\"}]}', 'active', '2017-10-30 06:30:39');");
}