<?php
include __DIR__ . "../../admin/libraries/includes/variables.php";
include __DIR__ . "../../admin/libraries/plugins/widget/widget.php";

if (is_dir( __DIR__ . "/extra-functions" )) {
  foreach (glob( __DIR__ . "/extra-functions/*.php") as $_plugin) {
    require_once $_plugin;
  }
}


/**
* @var cms_posts_retrieved should be false, but it will be set to true if there are post or page is found
*/
$cms_posts_retrieved = false;
/**
* @var cms_posts should be null, but it will be set value if cms_posts_retrieved is true
*/
$cms_posts = null;
/**
* @var cms_current_post should be null, but it will be set if post is selected
*/
$cms_current_post = null;
/**
* @var cms_query_filters array, set to query filters
*/
$cms_query_filters = array();
/**
* @var cms_theme_file_path: is the directory of active theme
*/
$cms_theme_file_path = ROOT . "views/themes/" . ACTIVE_THEME . "/";

/**
* @var cms_blog_post_limit: is used in function cms_have_posts to limit the query of posts per page
*/
$cms_blog_post_limit = -1;
/**
* @var cms_page_offset: is used in function cms_have_posts. related to pagination event
*/
$cms_page_offset = 0;
/**
* @var cms_enable_honey_pot: is used in function cms_have_posts. related to pagination event
*/
$cms_enable_honey_pot = true;
/**
* @var cms_current_language: contain the current selected language
*/
$cms_current_language = 'en';
/**
* @var cms_default_language: contain the systems default language
*/
$cms_default_language = '';
/**
* @var cms_reserved_language: contain the systems reserved language
*/
$cms_reserved_language = '';

/**
* get_bloginfo: will echo the template directory or installation url
* @param string - [ baseurl / template_directory / installation_url]
* Here is an inline example:
* <code>
* bloginfo('installation_url')
* </code>  
* @return void
*/
function get_bloginfo($parameter) {
  /*$parameter*/
  /*template_directory: This will output a url to the active-theme's folder. eg "http://th.dyna.sg/ecatalog-system/views/themes/drachir-xhtml" (without trailing slash)*/

  switch ($parameter) {
    case 'baseurl':
    return get_bloginfo('installation_url') . '/';
    break;
    case 'template_directory':
    return get_bloginfo('installation_url') . '/views/themes/' . ACTIVE_THEME;
    break;
    case 'installation_url':
    return rtrim(URL, '/');
    break;
  }
}

/**
* cms_apply_thumbnail: will return the url of the thumbnail
* Here is an inline example:
* <code>
* <?php
*      echo  cms_apply_thumbnail($options = array());
* ?>
* </code>
* @return string
*/

function cms_apply_thumbnail($options = array()) {
  $hosts = array(str_ireplace('www.', '', parse_url(get_bloginfo('installation_url'), PHP_URL_HOST)) => get_bloginfo('installation_url').'/thumbnails/');
  $thumbnail_url = $options['url'];
  $size = $options['size'];

  foreach ($hosts as $host => $cdn_path) {
    /*Match each host against our list.*/
    if (strpos($options['url'], $host) !== FALSE) {
      $url_information = parse_url($options['url']);
      $thumbnail_url = $cdn_path.$size.'/'.str_replace( URL_PROTOCOL . '://' . $url_information['host'].'/images/', '', $options['url']);
      /*'/phpThumb/phpThumb.php?src=' . urlencode(str_replace('http://' . $_SERVER['HTTP_HOST'], '', $prow['merchant_logo_1'])) . '&amp;w=176&amp;h=167&amp;zc=true';*/
    }
  }

  return $thumbnail_url;
}
/**
* get_current_url: will return the current url 
* Here is an inline example:
* <code>
* <?php
*      echo  get_current_url();
* ?>
* </code> 
* @return string
*/
function get_current_url(){
  return URL_PROTOCOL . "://$_SERVER[HTTP_HOST]" . (strlen($_SERVER['REQUEST_URI'])>1 && $_SERVER['REQUEST_URI']!='/'?$_SERVER['REQUEST_URI']:'');
}
/**
* get_current_uri: will return the current URI 
* Here is an inline example:
* <code>
* <?php
*      echo  get_current_uri();
* ?>
* </code> 
* @return string 
*/
function get_current_uri(){
  $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
  return URL_PROTOCOL . "://$_SERVER[HTTP_HOST]".$uri_parts[0];
}

/**
* bloginfo: will echo the template directory or installation url
* @param string - [ baseurl / template_directory / installation_url]
* Here is an inline example:
* <code>
* bloginfo('installation_url')
* </code>  
* @return void
*/

function bloginfo($parameter) {
  /*$parameter*/
  /*template_directory: This will output a url to the active-theme's folder. eg "http://th.dyna.sg/ecatalog-system/views/themes/drachir-xhtml" (without trailing slash)*/
  echo get_bloginfo($parameter);
}

/**
* cms_initialize: will initialized some variables 
* @return void
*/
function cms_initialize() {
  global $cms_blog_post_limit;
  global $cms_page_offset;
  // if (isGet('page') && get('page') == 1) {
  //   redirect( get_current_uri() );
  // }

  $cms_blog_post_limit = get_system_option(array("option_name" => 'blog_post_limit'));
  $cms_page_offset = $cms_page_offset >=0 ? $cms_page_offset : 0;
}

/**
* cms_set_page_offset: set the current page index 
* @return int
*/
function cms_set_page_offset($page_num = 0) {
  global $cms_page_offset;
  $cms_page_offset = $page_num;
  return $cms_page_offset + 1;
}
/**
* cms_current_page_offset: get the current page index 
* @return int
*/
function cms_current_page_offset() {
  global $cms_page_offset;
  return $cms_page_offset + 1;
}
/**
* cms_have_next_page_offset: test if has next page index 
* @return boolean
*/
function cms_have_next_page_offset() {
  return cms_next_page_offset() != cms_current_page_offset();
}
/**
* cms_next_page_offset: get the next page index 
* @return int
*/
function cms_next_page_offset() {
  global $cms_page_offset;

  $offset_page = $cms_page_offset + 1;

  $page_count = cms_get_max_post_offset(); 

  if ($offset_page + 1 <= $page_count) {
    return $offset_page + 1;
  }else{
    return $offset_page;
  }
}
/**
* cms_have_prev_page_offset: check if has the previous page index 
* @return boolean
*/
function cms_have_prev_page_offset() {
  return cms_prev_page_offset() != cms_current_page_offset();
}
/**
* cms_prev_page_offset: get the previous page index 
* @return int
*/
function cms_prev_page_offset() {
  global $cms_page_offset;

  $offset_page = $cms_page_offset + 1;

  if ($offset_page - 1 > 0) {
    return $offset_page - 1;
  }else{
    return $offset_page;
  }
}
/**
* cms_get_max_post_offset: get the max offset (max number of pages)
* @return int
*/
function cms_get_max_post_offset() {
  global $cms_blog_post_limit;
  $posts_count = cms_get_posts_count();
  return ceil($posts_count / $cms_blog_post_limit);
}
/**
* cms_get_posts_count: get the total number posts of the current query
* @return int
*/
function cms_get_posts_count() {
  global $cms_blog_post_limit;
  global $cms_page_offset;
  global $db;
  global $cms_query_filters;

  $filter_with_category = false;
  foreach ($cms_query_filters as $key => $value) {
    if ($key == 'category_id') {
      $filter_with_category = true;
      if (gettype($value) == 'array') {
        $category_ids = array();
        foreach ($value as $category_key => $category_value) {
          $category_ids[] = "'$category_value'";
        }
        if (count($category_ids)) $additional_filters_sql_array[] = "`$key` IN (". implode(",", $category_ids) .")";
        break;
      }
    }

    $additional_filters_sql_array[] = "`$key` = '$value'";
  }

  $join_table = "";
  if ($filter_with_category) {
    $join_table = " Left Join `posts_categories_relationship` On `posts_categories_relationship`.`post_id` = `cms_posts`.`id` ";
  }

  $additional_filters_sql_array[] = "`status` = 'publish' AND `post_status`='active' AND `post_type`='post' ";
  $additional_filters_sql = implode(' AND ', $additional_filters_sql_array);
  $additional_filters_sql = count($additional_filters_sql_array) > 0 ? 'Where ' . $additional_filters_sql : '';

  return count($db->select("SELECT * FROM `cms_posts` {$join_table} {$additional_filters_sql} "));
}

/**
* cms_query_posts: will query to know if there is a post 
* Here is an inline example:
* <code>
* <?php
*   cms_query_posts($str_query);
* ?>
* </code> 
* @return void
*/
function cms_query_posts($str_query) {
  global $cms_query_filters;

  $str_query = $str_query;
  parse_str($str_query, $cms_query_filters);
}

/**
* cms_is_page: if the post is page then it will return true
* Here is an inline example:
* <code>
* <?php
*  if(cms_is_page($id)){}
* ?>
* </code> 
* @return boolean
*/
function cms_is_page($id = ''){
  global $cms_current_post;

  if($cms_current_post['id'] == $id || $cms_current_post['url_slug'] == $id){
    return true; 
  } else {
    return false;
  }
}

/**
* cms_have_posts: check if there is/are post/s exist
* Here is an inline example:
* <code>
* <?php
*  if(cms_have_posts()){}
* ?>
* </code> 
* @return boolean
*/
function cms_have_posts() {
  global $cms_posts;
  global $db;
  global $cms_posts_retrieved;
  global $cms_query_filters;

  if ($cms_posts_retrieved == false) {
    $cms_posts_retrieved = true;
    $cms_posts = array();

    $filter_with_category = false;
    $additional_filters_sql_array = array();
    foreach ($cms_query_filters as $key => $value) {
      if ($key == 'category_id') {
        $filter_with_category = true;
        if (gettype($value) == 'array') {
          $category_ids = array();
          foreach ($value as $category_key => $category_value) {
            $category_ids[] = "'$category_value'";
          }
          if (count($category_ids))  $additional_filters_sql_array[] = "`$key` IN (". implode(",", $category_ids) .")";
          break;
        }
      }

      $additional_filters_sql_array[] = "`$key` = '$value'";
    }
    $additional_filters_sql_array[] = "`status` = 'publish' AND post_status='active' ";
    $additional_filters_sql = implode(' AND ', $additional_filters_sql_array);
    $additional_filters_sql = count($additional_filters_sql_array) > 0 ? 'Where ' . $additional_filters_sql : '';

    $join_table = "";
    $group_by = "";
    if ($filter_with_category) {
      $join_table = " Left Join `posts_categories_relationship` On `posts_categories_relationship`.`post_id` = `cms_posts`.`id` ";
      $group_by = " Group By `cms_posts`.`id` Order By `cms_posts`.`post_date` Desc ";

      $additional_filters_sql .= " {$group_by} ";
    }

    global $cms_blog_post_limit;
    global $cms_page_offset;

    $filter_range = "";
    if ($cms_blog_post_limit >= 0 ) {
      $page_offset = $cms_page_offset * $cms_blog_post_limit;
      $filter_range .= "Limit {$cms_blog_post_limit} OFFSET {$page_offset}";
      // $additional_filters_sql .= "Limit {$cms_blog_post_limit} OFFSET {$page_offset}";
    }

    $sql = "SELECT `cms_posts`.* FROM `cms_posts` {$join_table} {$additional_filters_sql} ";

    //get translated page
    if (cms_get_language_reserved() != cms_get_language()) {
      // $sql = "SELECT `c`.`id`,`ct`.`post_author`,`ct`.`post_date`,`ct`.`post_content`,`ct`.`post_title`,`ct`.`post_excerpt`,`ct`.`post_status`,`ct`.`post_type`,`ct`.`url_slug`,`ct`.`old_slug`,`ct`.`seo_canonical_url`,`ct`.`page_template`,`ct`.`seo_title`,`ct`.`seo_description`,`ct`.`seo_no_index`,`ct`.`parent_id`,`ct`.`status`,`ct`.`featured_image`,`ct`.`featured_image_crop`,`ct`.`featured_image_crop_data`,`ct`.`meta_data`,`ct`.`date_added` FROM ({$sql}) `c` INNER JOIN `cms_posts_translate` `ct` On `c`.`id` = `ct`.`post_id` and `ct`.`language` = '". cms_get_language() ."'";

      $sql_translated = "Select c.id,ct.post_author,ct.post_date,ct.post_content,ct.post_title,ct.post_excerpt,ct.post_status,ct.post_type,ct.url_slug,ct.old_slug,ct.seo_canonical_url,ct.page_template,ct.seo_title,ct.seo_description,ct.seo_no_index,ct.parent_id,ct.status,ct.featured_image,ct.featured_image_crop,ct.featured_image_crop_data,ct.meta_data,ct.date_added From cms_posts c Left Join cms_posts_translate ct On c.id = ct.post_id and ct.language = '". cms_get_language() ."' Where ct.id IS NOT NULL";

      $sql = "SELECT `cms_posts`.* FROM ({$sql_translated}) `cms_posts` {$join_table} {$additional_filters_sql} ";
    }
    $sql .= " {$filter_range}";

    $result = $db->query( $sql );
    // echo "SELECT * FROM `cms_posts` $additional_filters_sql ";
    //return DB_USER;

    while ($row = $db->fetch($result, 'assoc')) {
      $cms_posts[] = $row;
    }
  }
  return count($cms_posts) > 0;
}

/**
* cms_post_count: return count of all the post
* Here is an inline example:
* <code>
* <?php
*  if(cms_post_count() > 0){}
* ?>
* </code> 
* @return int
*/
function cms_post_count() {
  global $cms_posts;
  echo count($cms_posts);
}

/**
* cms_the_post: will shift the arrays to the current array
* Here is an inline example:
* <code>
* <?php
*  cms_the_post()
* ?>
* </code> 
* @return void
*/
function cms_the_post() {
  global $cms_posts;
  global $cms_current_post;

  $cms_current_post = array_shift($cms_posts);

  /* Find translation */
  global $db;
  global $cms_current_language;

  $l = $db->select("Select * From `cms_posts_translate` Where `post_id` = '{$cms_current_post['id']}' and `language` = '{$cms_current_language}'");

  if (count($l) > 0) {
    $cms_current_post['post_content'] = $l[0]->post_content;
    $cms_current_post['post_title'] = $l[0]->post_title;
    $cms_current_post['seo_title'] = $l[0]->seo_title;
    $cms_current_post['seo_description'] = $l[0]->seo_description;
    $cms_current_post['seo_canonical_url'] = $l[0]->seo_canonical_url;
    $cms_current_post['post_type'] = $l[0]->post_type;
  }

  $author = $db->select("Select * From `system_users` Where `id` = '{$cms_current_post['post_author']}'");
  $cms_current_post['post_author'] = count($author) && isset($author[0]->user_fullname) ? $author[0]->user_fullname : 'Unknown';
}

/**
* cms_meta_title:return current meta title
* Here is an inline example:
* <code>
* <?php
*  echo cms_meta_title();
* ?>
* </code>
* @return string
*/
function cms_meta_title($prefix = "", $sep = "|"){
  global $cms_current_post;

  $meta_title = $cms_current_post['seo_title'] != '' ? $cms_current_post['seo_title'] : "";

  if ($meta_title == '') {
    $meta_title = ecatalog_meta_title();
  }

  if ($prefix != "") {
    $prefix .= " " . $sep . " ";
  }

  return $prefix . $meta_title;
}

/**
* cms_meta_data:return current meta data
* Here is an inline example:
* <code>
* <?php
*  echo cms_meta_data();
* ?>
* </code>
* @return array
*/

function cms_meta_data($data_name = ''){
  global $cms_current_post;

  $meta_data = $cms_current_post['meta_data'] != '' ? $cms_current_post['meta_data'] : "";

  if ($meta_data == '') {
    $meta_data = json_encode(array());
  }


  if (isset($data_name) && $data_name != '') {
    $meta_data = json_decode($meta_data);
    $value = isset($meta_data->{$data_name}) ? $meta_data->{$data_name} : array();
    foreach ($value as $k => $v) {
      $value[$k] = cms_post_content_processor($v);
    }
    return $value;
  }else{
    $a = array();
    foreach (json_decode($meta_data) as $key => $value) {
      foreach ($value as $k => $v) {
        $value[$k] = cms_post_content_processor($v);
      }
      $a[$key] = $value;
    }
    return $a;
  }
}

/**
* cms_get_seo_description:return current meta description
* Here is an inline example:
* <code>
* <?php
*  echo cms_get_seo_description();
* ?>
* </code>
* @return string
*/

function cms_get_seo_description(){
  global $cms_current_post;
  return $cms_current_post['seo_description'];
}

/**
* cms_get_post_title:return current tile
* cms_get_seo_description:return current meta description
* Here is an inline example:
* <code>
* <?php
*  echo cms_get_post_title();
* ?>
* </code>
* @return string
*/

function cms_get_post_title() {
  global $cms_current_post;
  return $cms_current_post['post_title'];
}

/**
* cms_post_title:echo current tile
* Here is an inline example:
* <code>
* <?php
* cms_post_title();
* ?>
* </code>
* @return void
*/

function cms_post_title() {
  echo cms_get_post_title();
}

/**
* cms_get_post_content:return the content 
* Here is an inline example:
* <code>
* <?php
* echo cms_get_post_content(); 
* ?>
* </code>
* @return string
*/

function cms_get_post_content() {
  global $cms_current_post;
  return $cms_current_post['post_content'];
}
/**
* cms_get_post_excerpt: Get excerpt from string
* 
* @param String $str String to get an excerpt from
* @param Integer $startPos Position int string to start excerpt from
* @param Integer $maxLength Maximum length the excerpt may be
*
* @return string
*/
function cms_get_post_excerpt($startPos=0, $maxLength=100) {
  $str = cms_get_post_content();

  if(strlen($str) > $maxLength) {
    $excerpt   = substr($str, $startPos, $maxLength-3);
    $lastSpace = strrpos($excerpt, ' ');
    $excerpt   = substr($excerpt, 0, $lastSpace);
    $excerpt  .= '...';
  } else {
    $excerpt = $str;
  }

  return $excerpt;
}

/**
* cms_post_content:check content if there is contact form inside the content and echo the content
* Here is an inline example:
* <code>
* <?php
* echo cms_post_content(); 
* ?>
* </code>
* @return void
*/

function cms_post_content() {
  unset( $_SESSION['form_id']);

  $cms_content = '';
  $cms_slider = '';
  $content = cms_get_post_content();
  $validator_form = '/\[contact-form.*?id=\"(\d+)\".title=\"(.*?)\"]/';
  $validator_slide = '/\[rev_slider.*]/';
  $validator_maps = '/\[maps-([0-9]+)]/';

  if(preg_match_all($validator_form,$content, $out_form)){
    $matches = $out_form[1];
    // $cms_content .= 
    $content = preg_replace($validator_form, str_replace('$', '\$', cms_replacement_form_code($matches[0])), $content);
    
    $cms_content .= $content;
    $content = $cms_content;
  }

  if(preg_match_all($validator_slide,$content, $out_slider)){
    $matches = $out_slider[0];
    foreach ($matches as $key => $value) {
      $cms_content = preg_replace($validator_slide, cms_replacement_slide($value), $content);
    }
    $content = $cms_content;
  }

  if(preg_match_all($validator_maps, $content, $out_map)){
    $matches = $out_map[0];
    foreach ($matches as $key => $value) {
      $content = str_replace($value,cms_decode_maps($value,$key), $content);
    }
  }

  if (preg_match_all("^\[(.*?)\]^",$content,$out_map, PREG_PATTERN_ORDER)) {
    $matches = $out_map[0];
    foreach ($matches as $key => $value) {
      $content = str_replace($value,cms_decode_function($value), $content);
    }
  }

  $cms_content = $content;
  echo $cms_content;
}
function cms_post_content_processor($content = ""){
  $cms_content = '';
  $cms_slider = '';

  $validator_form = '/\[contact-form.*?id=\"(\d+)\".title=\"(.*?)\"]/';
  $validator_slide = '/\[rev_slider.*]/';
  $validator_maps = '/\[maps-([0-9]+)]/';

  if(preg_match_all($validator_form,$content, $out_form)){
    $matches = $out_form[1];
    $cms_content .= preg_replace($validator_form, cms_replacement_form_code($matches[0]), $content);
    $content = $cms_content;
  }

  if(preg_match_all($validator_slide,$content, $out_slider)){
    $matches = $out_slider[0];
    foreach ($matches as $key => $value) {
      $cms_content = preg_replace($validator_slide, cms_replacement_slide($value), $content);
    }
    $content = $cms_content;
  }

  if(preg_match_all($validator_maps, $content, $out_map)){
    $matches = $out_map[0];
    foreach ($matches as $key => $value) {
      $content = str_replace($value,cms_decode_maps($value,$key), $content);
    }
  }

  if (preg_match_all("^\[(.*?)\]^",$content,$out_map, PREG_PATTERN_ORDER)) {
    $matches = $out_map[0];
    foreach ($matches as $key => $value) {
      $content = str_replace($value,cms_decode_function($value), $content);
    }
  }

  return $content;
}

function cms_decode_maps($value,$index){
  global $db;
  $maps = '';
  $qry = $db->query("SELECT * FROM `maps` WHERE `short_code` = '$value'");

  $map = $db->fetch($qry, 'array');
  $position = $map['position'];
  $position = str_replace("(","",$position);
  $position = str_replace(")","",$position);
  $latlng = explode(',', $position);

  preg_match_all("/(\S[\d,]*)(\D*)/i", $map['width'], $preg_w);
  preg_match_all("/(\S[\d,]*)(\D*)/i", $map['height'], $preg_h);

  $map_width = count($preg_w) ? (isset($preg_w[1]) ? $preg_w[1][0] . (isset($preg_w[2]) && $preg_w[2][0] != '' ? $preg_w[2][0] : 'px') : 'auto') : 'auto';
  $map_height = count($preg_h) ? (isset($preg_h[1]) ? $preg_h[1][0] . (isset($preg_h[2]) && $preg_h[2][0] != '' ? $preg_h[2][0] : 'px') : 'auto') : 'auto';

  $maps = '<div id="map_canvas_'.$index.'" style="width:'.$map_width.'; height:'.$map_height.';"></div>';
  $maps .='<script type="text/javascript">$(document).ready(function(){initialize('.$latlng[0].','.$latlng[1].', "'.$map['title'].'", "'.$map['description'].'",'.$index.')})</script>';
  
  return $maps;
}
function cms_decode_function($value, &$the_controller=null){
  global $db;
  $function = trim($value,"[] ");
  $content = $function;

  if (function_exists($function)) {
    if ($the_controller == null) {
      $content = $function();
    }else{
      $content = $function($the_controller);
    }
  }
  
  return $content;
}

function cms_replacement_slide($value){



  global $db;



  $data = explode('rev_slider', $value);



  $alias = rtrim($data[1],']');



  $alias = trim($alias);



  $content = '';

  $sql = "SELECT * FROM `revolution_sliders` WHERE `slider_alias` ='$alias' ";

    //echo $sql;

  $get_id_qry = $db->query($sql);





  $row_get_id = $db->fetch($get_id_qry, 'assoc');

  $revolution_slide_id = $row_get_id['id'];





  $get_slides_qry = $db->query("SELECT * FROM `revolution_slides` WHERE `revolution_slide_id` = '$revolution_slide_id'");

  $rows_slides = array();



  while($row = $db->fetch($get_slides_qry, 'array'))

    $rows_slides[] = $row;

  if(!empty($rows_slides) ){

    $main_image = str_replace('/images/', '/thumbnails/200x120/', $rows_slides[0]['image_url']);

    $content = '<div id="rev_slider_1_1_wrapper" class="rev_slider_wrapper" style="height:370px; width:764px; float:left;background-color:#E9E9E9;padding:0px;margin-left:0px;margin-right:0px;margin-top:0px;margin-bottom:0px; position:relative;">

    <div id="rev_slider_1_1" class="rev_slider" style="display:none;height:370px;width:764px;">

    <ul style="margin-top:-100px;">    <!-- SLIDE  -->

    <li data-transition="notransition" data-slotamount="7" data-masterspeed="300"  data-saveperformance="off" style="width:764px; height:370px" >

    <!-- MAIN IMAGE -->

    <img src="'.$rows_slides[0]['image_url'].'"  alt="banner-11"  data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat">

    <!-- LAYERS -->



    <!-- LAYER NR. 1 -->

    <div class="tp-caption btn-rm tp-fade tp-resizeme"

    data-x="31"

    data-y="238" 

    data-speed="300"

    data-start="500"

    data-easing="Power3.easeInOut"

    data-splitin="none"

    data-splitout="none"

    data-elementdelay="0.1"

    data-endelementdelay="0.1"

    data-end="-297"

    data-endspeed="300"

    style="z-index: 2; max-width: auto; max-height: auto; white-space: nowrap;">Read More

    </div>

    </li>';



    foreach ($rows_slides as $key => $slides) {

      if($key!= 0){

        $image = str_replace('/images/', '/thumbnails/200x120/', $slides['image_url']);

        $content .= '<!-- SLIDE  -->

        <li data-transition="random" data-slotamount="7"  data-saveperformance="off" >

        <!-- MAIN IMAGE -->

        <img src="'.$slides['image_url'].'"  alt=""  data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat">

        <!-- LAYERS -->

        </li>';

      }

    }

    $content .= '</ul>

    <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div> </div>

    </div>';



    $content .= '<script type="text/javascript">



    /******************************************

    -   PREPARE PLACEHOLDER FOR SLIDER  -

                ******************************************/





    var setREVStartSize = function() {

      var tpopt = new Object();

      tpopt.startwidth = 980;

      tpopt.startheight = 370;

      tpopt.container = jQuery("#rev_slider_1_1");

      tpopt.fullScreen = "off";

      tpopt.forceFullWidth="off";



      tpopt.container.closest(".rev_slider_wrapper").css({height:tpopt.container.height()});tpopt.width=parseInt(tpopt.container.width(),0);tpopt.height=parseInt(tpopt.container.height(),0);tpopt.bw=tpopt.width/tpopt.startwidth;tpopt.bh=tpopt.height/tpopt.startheight;if(tpopt.bh>tpopt.bw)tpopt.bh=tpopt.bw;if(tpopt.bh<tpopt.bw)tpopt.bw=tpopt.bh;if(tpopt.bw<tpopt.bh)tpopt.bh=tpopt.bw;if(tpopt.bh>1){tpopt.bw=1;tpopt.bh=1}if(tpopt.bw>1){tpopt.bw=1;tpopt.bh=1}tpopt.height=Math.round(tpopt.startheight*(tpopt.width/tpopt.startwidth));if(tpopt.height>tpopt.startheight&&tpopt.autoHeight!="on")tpopt.height=tpopt.startheight;if(tpopt.fullScreen=="on"){tpopt.height=tpopt.bw*tpopt.startheight;var cow=tpopt.container.parent().width();var coh=jQuery(window).height();if(tpopt.fullScreenOffsetContainer!=undefined){try{var offcontainers=tpopt.fullScreenOffsetContainer.split(",");jQuery.each(offcontainers,function(e,t){coh=coh-jQuery(t).outerHeight(true);if(coh<tpopt.minFullScreenHeight)coh=tpopt.minFullScreenHeight})}catch(e){}}tpopt.container.parent().height(coh);tpopt.container.height(coh);tpopt.container.closest(".rev_slider_wrapper").height(coh);tpopt.container.closest(".forcefullwidth_wrapper_tp_banner").find(".tp-fullwidth-forcer").height(coh);tpopt.container.css({height:"100%"});tpopt.height=coh;}else{tpopt.container.height(tpopt.height);tpopt.container.closest(".rev_slider_wrapper").height(tpopt.height);tpopt.container.closest(".forcefullwidth_wrapper_tp_banner").find(".tp-fullwidth-forcer").height(tpopt.height);}

    };



    /* CALL PLACEHOLDER */

    setREVStartSize();





    /*var tpj=jQuery;

    tpj.noConflict();*/

    var revapi1;



    $(document).ready(function() {



      if($("#rev_slider_1_1").revolution == undefined)

        revslider_showDoubleJqueryError("#rev_slider_1_1");

      else

        revapi1 = $("#rev_slider_1_1").show().revolution(

          {

            dottedOverlay:"none",

            delay:3000,

            startwidth:980,

            startheight:370,

            hideThumbs:200,



            thumbWidth:100,

            thumbHeight:50,

            thumbAmount:0,



            navigationType:"none",

            navigationArrows:"none",

            navigationStyle:"round",



            touchenabled:"on",

            onHoverStop:"off",



            swipe_velocity: 0.7,

            swipe_min_touches: 1,

            swipe_max_touches: 1,

            drag_block_vertical: false,





            keyboardNavigation:"off",



            navigationHAlign:"center",

            navigationVAlign:"bottom",

            navigationHOffset:0,

            navigationVOffset:20,



            soloArrowLeftHalign:"left",

            soloArrowLeftValign:"center",

            soloArrowLeftHOffset:20,

            soloArrowLeftVOffset:0,



            soloArrowRightHalign:"right",

            soloArrowRightValign:"center",

            soloArrowRightHOffset:20,

            soloArrowRightVOffset:0,



            shadow:0,

            fullWidth:"off",

            fullScreen:"off",



            spinner:"spinner3",



            stopLoop:"off",

            stopAfterLoops:-1,

            stopAtSlide:-1,



            shuffle:"off",











            hideTimerBar:"on",

            hideThumbsOnMobile:"off",

            hideNavDelayOnMobile:1500,

            hideBulletsOnMobile:"off",

            hideArrowsOnMobile:"off",

            hideThumbsUnderResolution:0,



            hideSliderAtLimit:0,

            hideCaptionAtLimit:0,

            hideAllCaptionAtLilmit:0,

            startWithSlide:0                    });









          }); /*ready*/



          </script>





          <style type="text/css">

    #rev_slider_1_1_wrapper .tp-loader.spinner3 div { background-color: #FFFFFF !important; }

          .code-java { overflow: hidden; }

          .tp-bgimg  { position: absolute !important; top:0; }

          </style>

          <!-- END REVOLUTION SLIDER -->'; 

        }else

        $content = 'No Slides Found.';



        return $content;

      }

/**
* cms_get_post_permalink: return the url slug of the page
* Here is an inline example:
* <code>
* <?php
* echo cms_get_post_permalink(); 
* ?>
* </code>
* @return string
*/

function cms_get_post_permalink() {
  global $cms_current_post;
  return $cms_current_post['url_slug'];
}

/**
* cms_get_post_link: return the full url of the page
* Here is an inline example:
* <code>
* <?php
* echo cms_get_post_link(); 
* ?>
* </code>
* @return string
*/

function cms_get_post_link() {
  global $cms_current_post;
  $uc = new UC();
  $uc->set_current_language(cms_get_language());
  $final_url = $uc->uc_get_url_validity_info_2( get_bloginfo("baseurl") . cms_get_language() . "/{$cms_current_post['url_slug']}" );
  return $final_url['final_url'];
}

/**
* cms_permalink: echo the url slug of the page
* Here is an inline example:
* <code>
* <?php
* echo cms_permalink(); 
* ?>
* </code>
* @return void
*/

function cms_permalink() {

  echo cms_get_post_permalink();

}

/**
* cms_get_baseurl: return the base URL
* Here is an inline example:
* <code>
* <?php
* echo cms_get_baseurl(); 
* ?>
* </code>
* @return string
*/
function cms_get_baseurl() {
  return URL;
}

/**
* cms_reset_query: setting the global variables to its default value
* Here is an inline example:
* <code>
* <?php
*  cms_reset_query(); 
* ?>
* </code>
* @return void
*/

function cms_reset_query() {
  global $cms_posts_retrieved;
  global $cms_posts;
  global $cms_current_post;
  global $cms_query_filters;

  $cms_posts_retrieved = false;
  $cms_posts = null;
  $cms_current_post = null; 
  $cms_query_filters = array();
}

/**
* cms_get_page_template: setting the global variables to its default value
* Here is an inline example:
* <code>
* <?php
*  echo cms_get_page_template($options = array());
* ?>
* </code>
* @return void
*/

function cms_get_page_template($options = array()) {
  global $cms_current_post;
  return $cms_current_post['page_template'] == '' ? $cms_current_post['post_type'] : $cms_current_post['page_template'];
}

/**
* cms_get_menu: will return the header menu
* Here is an inline example:
* <code>
* <?php
*  $menu =  cms_get_menu(array('id' => id, 'name' => name)) ;
* ?>
* </code>
* @return array
*/

function cms_get_menu($options = array()){
  global $db;
  $sql = '';

  if(isset($options['id'])){
    $sql ="SELECT * FROM `menus` WHERE `id` = '{$options['id']}' ";
  }else if(isset($options['name'])){
    $sql = "SELECT * FROM `menus` WHERE `name` = '{$options['name']}' ";
  }else{
    $sql = "SELECT * FROM `menus` Limit 1";
  }

  $qry = $db->query($sql);

  if ($db->numRows($qry) <= 0) {
    $sql = "SELECT * FROM `menus` Limit 1";
    $qry = $db->query($sql);
  }

  $default_lang = cms_get_language_default();
  $reserve_lang = cms_get_language_reserved();
  $lang = cms_get_language();
  $uc = new UC();
  $uc->set_current_language($lang);

  $rows = array();
  if($db->numRows($qry) > 0){
    $menu = $db->fetch($qry, 'array');
    $id = $menu['id'];

    // $menu_sql = "Select `menu_items`.*, `cms_posts`.`url_slug`, `t`.`meta` `translation` From `menu_items` Left Join `cms_posts` On `menu_items`.`guid` = `cms_posts`.`id` Left Join `cms_translation` `t` On `menu_items`.`id` = `t`.`guid` and `t`.`type` = 'menu-item' and `t`.`language` = '{$lang}' Where `menu_items`.`menu_id` = '{$id}' Order By `menu_items`.`sort_order` ASC";

    $menu_sql = "Select `menu_items`.*, `cms_posts`.`url_slug`, `t`.`meta` `translation`, cp.parent_id page_parent, cp.parent_id page_slug, ct.url_slug page_translated_slug From `menu_items` 
      Left Join `cms_posts` On `menu_items`.`guid` = `cms_posts`.`id` 
      Left Join `cms_translation` `t` On `menu_items`.`id` = `t`.`guid` and `t`.`type` = 'menu-item' and `t`.`language` = '{$lang}' 
      Left Join `cms_posts_translate` `ct` On `ct`.`post_id` = `menu_items`.`guid` and `ct`.`language` = '{$lang}'
      Left Join `cms_posts` `cp` On `cp`.`id` = `menu_items`.`guid` and cp.id=ct.post_id
      Where `menu_items`.`menu_id` = '{$id}'
      Order By `menu_items`.`sort_order` ASC";


    $menu_items = $db->select( $menu_sql );

    foreach ($menu_items as $key => $value) {
      $menu_url = "";
      $site_url_info = get_site_url_info();
      $parent_url = trim(cms_get_post_parent_url( $value->page_parent ), "/");
      $parent_url .= "/" . ($reserve_lang != $lang ? $value->page_translated_slug : $value->url_slug);

      if ($parent_url != "") {
        $parent_url = $site_url_info["has_slash"] ? $parent_url . "/" : $parent_url;
        $parent_url = '/' . $parent_url;
      }else{
        $parent_url = $site_url_info["has_slash"] ? "/" : "";
      }

      if ($value->type == 'page') {
        $_site_url = trim($site_url_info["siteurl"], '/');
        $menu_url = $_site_url . "{$lang}/{$parent_url}";
      }else{
        $menu_url = $value->url;
      }

      $url_data = $uc->uc_get_url_validity_info_2( $menu_url );

      if (isset($value->translation)) {
        $t = json_decode($value->translation);
        $value->label = isset($t->label) ? $t->label : $value->label;
      }

      $rows[] = array(
        'id' =>$value->id,
        'link_text'=>$value->label, 
        'link_url'=>isset($value->meta) && $value->meta != '' ? ($value->meta == 'Y' ? $url_data['final_url'] : 'javascript:void(0)') : $url_data['final_url'], 
        'menu_parent'=>$value->parent, 
        'sort_order'=>$value->sort_order
      );
    }
  }

  $new_menu_heirarchy = cms_menu_heirarchy( $rows );

  return $new_menu_heirarchy; 
}
function cms_get_post_parent_url( $parent_id = 0 ){
  global $db;
  $homepage_slug = cms_get_homepage_slug();
  $parent = '';

  if($parent_id !=0){
    $qry = $db->query("SELECT `url_slug`,`parent_id` FROM `cms_posts` WHERE `id` = '$parent_id'");
    $row = $db->fetch($qry,'array');
    if ($homepage_slug == $row['url_slug']) {
      $parent = "/";
    }else{
      $parent = '/'.$row['url_slug'].'/';
      $parent_id = $row['parent_id'];
      while($parent_id != 0){
        $qryx = $db->query("SELECT `url_slug`,`parent_id` FROM `cms_posts` WHERE `id` = '$parent_id'");
        $rowx = $db->fetch($qryx,'array');
        $parent = '/'.$rowx['url_slug'].$parent;
        $parent_id = $rowx['parent_id'];
      }
    }
  }

  return $parent;
}

function cms_menu_heirarchy($menu_items = array(), $menu_parent = 0){
  $new_menu_heirarchy = array();

  foreach ($menu_items as $key => $value) {
    if ($value['menu_parent'] == $menu_parent) {
      unset($menu_items[ $key ]);
      $value['sub_menus'] = cms_menu_heirarchy( $menu_items, $value['id'] );
      $new_menu_heirarchy[] = $value;
    }
  }

  return $new_menu_heirarchy;
}

function cms_display_menu( $options = array(), $menu_template = "" ){
  $menu_items = cms_get_menu( $options );
  cms_generate_display_menu( $menu_items, $menu_template );
}
function cms_generate_display_menu( $menu_items = array(), $menu_template = "" ){
  foreach ($menu_items as $key => $value) {
    cms_include_file(array(
      "base_name" => $menu_template == "" ? "menu" : "menu-" . trim($menu_template),
      "variables" => array(
        "header_menu" => $value
      )
    ));
  }
}

/**
* sort_array: will sort array
* Here is an inline example:
* <code>
* <?php
*  $sorted_array = sort_array($rows,'sort_order','asc');
* ?>
* </code>
* @return array
*/

function sort_array($array,$sortBy,$direction){



  $sortedArray=array();

  $tmpArray=array();

  foreach($array as $obj)

  {

    $tmpArray[]=$obj[$sortBy];

  }

  if($direction=='asc'){

    asort($tmpArray);

  }else{

    arsort($tmpArray);

  }



  foreach($tmpArray as $k=>$tmp){

    $sortedArray[]=$array[$k];

  }



  return $sortedArray;



}

/**
* get_system_option: will sort array desc or asc
* Here is an inline example:
* <code>
* <?php
*  $value = get_system_option(array('option_name' => option_name));
* ?>
* </code>
* @return array
*/

function get_system_option($option = array()){
  global $db;

  if(isset($option['option_name']) || gettype($option) == 'string'){
    $option_name = isset($option['option_name']) ? $option['option_name'] : ( gettype($option) == 'string' ? $option : "" );

    $qry = $db->query("SELECT * FROM `system_options` WHERE `option_name` = '$option_name' ");

    $row = $db->fetch($qry, 'array');

    return $row['option_value'];

  }

}


function get_site_url_info(){
  $site_url = get_system_option(array('option_name' => 'site_url'));
  if ($site_url == "") {
    $site_url = rtrim(URL , "/");
  }
  return get_url_info( $site_url );
}

/**
* cms_google_analytics_is_on: check if google analytics is on
* Here is an inline example:
* <code>
* <?php
*  if(cms_google_analytics_is_on()){}
* ?>
* </code>
* @return boolean
*/

function cms_google_analytics_is_on(){
  global $db;

  $qry = $db->query("SELECT * FROM `system_options` WHERE `option_name` = 'google_event_tracking'");

  if($qry)
    $row = $db->fetch($qry,'array');

  if($row['option_value'] == 'ON')
    return true;

  return false;
}

/**
* cms_google_analytics_code: echo google analytic code
* Here is an inline example:
* <code>
* <?php
*  cms_google_analytics_code();
* ?>
* </code>
* @return string
*/

function cms_google_analytics_code(){
  global $db;

  $qry = $db->query("SELECT * FROM `system_options` WHERE `option_name` = 'google_analytics_code'");

  if($qry){
    $row = $db->fetch($qry,'array');
  }

  if($row['option_value'] != null){
    echo $row['option_value'];
  }else{
    echo '';
  }
}

/**
* cms_check_contact_form_exist: echo google analytic code
* Here is an inline example:
* <code>
* <?php
*  if(cms_check_contact_form_exist($id)){}
* ?>
* </code>
* @return boolean
*/

function cms_check_contact_form_exist($id){
  global $db;

  $qry = $db->query("SELECT * FROM `cms_contact_forms` WHERE `id` = '$id'");

  $count = $db->numRows($qry);

  if($count > 0)
    return true;

  return false;
}

/**
* cms_replacement_form_code: echo google analytic code
* Here is an inline example:
* <code>
* <?php
*  echo cms_replacement_form_code($id);
* ?>
* </code>
* @return string
*/

function cms_replacement_form_code($id, $action=""){
  global $db;

  $_SESSION['form_id'] = $id;

  contact_form_process_submitted( $_SESSION['form_id'] );

  $qry = $db->query("SELECT * FROM `cms_contact_forms` WHERE `id` = '$id'"); 

  $row = $db->fetch($qry,'array');
  preg_match_all("^\[(.*?)\]^",$row['form_code'],$fields, PREG_PATTERN_ORDER);

  $matches = $fields[0];
  $return = $row['form_code'];

  $action = $action == "" ? get_current_uri() : get_bloginfo("baseurl") . $action;

  foreach ($matches as $key => $match) {
    $exploded_match = explode(" ", $match);
    if (trim(trim($exploded_match[0],'[')) == 'recaptcha') {
      if ($row['enable_captcha'] == 'N') {
        $return = str_replace($match,"", $return);
        continue;
      }
    }
    $return = str_replace($match,change_tags($exploded_match), $return);
  }

  $cms_content = '<form action="'. $action .'" method="post" class="cms-form cms-form-'. $id .'" novalidate="novalidate">
  <div style="display: none;">
  <input type="hidden" name="_cmscf_id" value="'. $id .'" />
  </div>';

  $cms_content .= '<div class="other-info" style="display: none;">'. cms_contact_form_honey_pot() .'</div>';
  $cms_content .= $return;
  $cms_content .= '<div class="cms-response-output cms-display-none"></div>

  </form>';            

  return $cms_content;
}


/**
* change_tags: change contact form tags to form input
* Here is an inline example:
* <code>
* $content = change_tags($data);
* </code>
* @return string
*/

function change_tags(array $data){
  $return = '';
  $replace = array('*','[',']','"');
  $add_ons = ' aria-required="true" aria-invalid="false"';
  $isRequired = strpos($data[0], "*") && substr($data[0], -1) == "*" ? "required" : "";

  switch (str_replace($replace,"",$data[0])) {
    case "text":
    $return = "<input type='text' name='".str_replace($replace,"",$data[1])."' ".$add_ons." ".get_addons($data,'text')." {$isRequired} >";
    break;
    case "email":
    $return = "<input type='email' name='".str_replace($replace,"",$data[1])."' ".get_addons($data,'text')." ".$add_ons." {$isRequired} >";
    break;
    case "textarea":
    $return = "<textarea name='".str_replace($replace,"",$data[1])."' ".get_addons($data,'textarea')."   ".$add_ons." {$isRequired}></textarea>";
    break;   
    case "submit":
    $return = "<input type='submit' ".get_addons($data,'button uppercase')." name='submit' >";
    break;
    case "url":
    $return = "<input type='url' name='".get_addons($data,'text')."' ".get_addons($data,'text')." ".$add_ons." {$isRequired}>";
    break;
    case "number":
    $return = "<input type='number' name='".get_addons($data,'text')."' ".get_addons($data,'text')." ".$add_ons." {$isRequired}>";
    break;
    case "date":
    $return = "<input type='date' name='".get_addons($data,'text')."' ".get_addons($data,'text')." ".$add_ons." {$isRequired}>";
    break;
    case "dropdown":
    $return = "<select name='".get_addons($data,'text')."' ".get_addons($data,'text')." ".$add_ons." {$isRequired}>".get_options($data)."</select>";
    break;
    case "recaptcha":
    $return = '<div class="g-recaptcha" data-sitekey="'. (get_system_option('GOOGLE_RECAPTCHA_KEY')) .'"></div>';
    break;
    case "checkbox":
    $return = get_check($data,'checkbox');
    break;
    case "radio":
    $return = get_check($data,'radio');
    break;
    case "file":
    $return = "<input type='file' name='".str_replace($replace,"",$data[1])."' ".get_addons($data,'text')." ".$add_ons." {$isRequired}>";
    break;
    default:
    if (isset($data[0])) {
      $fn = str_replace(array('*','[',']','"'), "", $data[0]);

      if (function_exists($fn)) {
        $return = $fn();
      }
    }

    break;

    $return = "not defined yet";

    break;

  }

  return '<span class="cms-form-control-wrap">'.$return.'</span>';

}

/**
* get_addons: adding attributtes to input tags
* Here is an inline example:
* <code>
* <?php
*  $content = get_addons($data,$input);
* ?>
* </code>
* @return string
*/

function get_addons(array $data,$input){
  $add_ons = '';
  $replace = array('*','[',']','"');
  $numItems = count($data);
  $i = 0;
  $watermark = false;
  $tmp_class = '';

  foreach ($data as $key => $value) {
    if(++$i === $numItems && $watermark === false && strpos($value,'"') !== false && $input !== 'select' && $input !== 'checkbox' && $input !== 'radio') {
      $add_ons .= 'value="'.str_replace($replace,"",$value).'"';
    }else{
      if(strpos($value,'id:') !== false){
        $tmp=explode(":", $value);
        $add_ons .= 'id="'.str_replace($replace,"",$tmp[1]).'" ';
      }
      else if($value == 'watermark'){
        preg_match_all("/\"(.*)\"/", implode(" ", $data), $watermark_matches);
        $watermark_text = isset($watermark_matches[1][0]) ? $watermark_matches[1][0] : "";
        $add_ons .= 'placeholder="'. $watermark_text .'" ';
        $watermark = true;
      }
      else if(strpos($value,'class:') !== false){
        $tmp=explode(":", $value);
        $tmp_class .= str_replace($replace,"",$tmp[1]) . " ";
      }
      else if(strpos($value,'/') !== false){
        $tmp=explode("/", $value);
        $add_ons .= 'size="'.str_replace($replace,"",$tmp[0]).'" ';
        if($tmp[1]!=''){
          $add_ons .= 'maxlength="'.str_replace($replace,"",$tmp[1]).'" ';
        }
      }
      else if(strpos($value,'min') !== false){
        $tmp=explode(":", $value);
        $add_ons .= 'min="'.str_replace($replace,"",$tmp[1]).'" ';
      }
      else if(strpos($value,'max') !== false){
        $tmp=explode(":", $value);
        $add_ons .= 'max="'.str_replace($replace,"",$tmp[1]).'" ';
      }
      else if(strpos($value,'step') !== false){
        $tmp=explode(":", $value);
        $add_ons .= 'step="'.str_replace($replace,"",$tmp[1]).'" ';
      }
      else if($value == 'multiple'){
        $add_ons .= ' multiple ';
      }
      else if(preg_match('/(?:\d+)?[Xx](?:\d+)?/',$value) && $input == 'textarea'){
        $tmp=explode("x", str_replace($replace,"",$value));
        if(is_numeric($tmp[0]) && $tmp[0] !=''){
          $add_ons .= 'cols="'.$tmp[0].'" ';
        }
        if(is_numeric($tmp[0]) && $tmp[1] !=''){
          $add_ons .= 'rows="'.$tmp[1].'" ';
        }
      }
    }
  }
  $class = 'class="cms-form-control cms-'.$input.' cms-validates-as-required '.$tmp_class.'" ';

  return $class.$add_ons;
}

/**
* get_addons: adding attributtes to input tags
* Here is an inline example:
* <code>
* <?php
*  $content = get_options($data);
* ?>
* </code>
* @return string
*/

function get_options(array $data){

  $options = '';

  $replace = array('*','[',']','"');

  foreach ($data as $key => $value) {

    if($value == 'include_blank'){

      $options .= '<option value="0"></option>';

    }else if(strpos($value,'"') !== false){

      $options .='<option value="'.str_replace($replace,"",$value).'">'.str_replace($replace,"",$value).'</option>';

    }

  }



  return $options;

}

/**
* get_check: adding attributtes and division in checkbox/radio inputs
* Here is an inline example:
* <code>
* <?php
*  $content = get_check($data,$kind);
* ?>
* </code>
* @return string
*/

function get_check(array $data,$kind){
  $replace = array('*','[',']','"');
  $checkbox = '';
  if(in_array("label_first", $data)){
    if(in_array("use_label_element", $data) && in_array("exclusive", $data)){
      foreach ($data as $key => $value) {
        if(strpos($value,'"') !== false && $value!=='"'){   
          $checkbox .= '<label>'.str_replace($replace,"",$value).'<input type="'.$kind.'" '.get_addons($data,'checkbox').'value="'.str_replace($replace,"",$value).'"></label><br>';
        }
      }
    }else if(in_array("use_label_element", $data)){
      foreach ($data as $key => $value) {
        if(strpos($value,'"') !== false && $value!=='"'){
          $checkbox .= '<label>'.str_replace($replace,"",$value).' <input type="'.$kind.'" name="'.str_replace($replace,"",$data[1]).'" '.get_addons($data,$kind).'value="'.str_replace($replace,"",$value).'"></label><br>';
        }
      }
    }else{
      foreach ($data as $key => $value) {
        if(strpos($value,'"') !== false && $value!=='"'){
          $checkbox .= '<label>'.str_replace($replace,"",$value).' <input type="'.$kind.'" name="'.str_replace($replace,"",$data[1]).'" '.get_addons($data,$kind).'value="'.str_replace($replace,"",$value).'"></label><br>';
        }
      }
    }
  }else{
    if(in_array("use_label_element", $data) && in_array("exclusive", $data)){
      foreach ($data as $key => $value) {
        if(strpos($value,'"') !== false && $value!=='"'){
          $checkbox .= '<label> <input type="'.$kind.'" '.get_addons($data,''.$kind.'').'value="'.str_replace($replace,"",$value).'"> '.str_replace($replace,"",$value).'</label><br>';
        }
      }
    }else if(in_array("use_label_element", $data)){
      foreach ($data as $key => $value) {
        if(strpos($value,'"') !== false && $value!=='"'){
          $checkbox .= '<label> <input type="'.$kind.'" name="'.str_replace($replace,"",$data[1]).'" '.get_addons($data,$kind).'value="'.str_replace($replace,"",$value).'"> '.str_replace($replace,"",$value).'</label><br>';
        }
      }
    }else{
      foreach ($data as $key => $value) {
        if(strpos($value,'"') !== false && $value!=='"'){
          $checkbox .= ' <input type="'.$kind.'" name="'.str_replace($replace,"",$data[1]).'" '.get_addons($data,$kind).'value="'.str_replace($replace,"",$value).'"> '.str_replace($replace,"",$value).'<br>';
        }
      }
    }
  }
  return $checkbox;
}


/**
* cms_get_convert_contact_forms: converts contact form short code to html form
* Here is an inline example:
* <code>
* <?php
*  $content = get_check($data,$kind);
* ?>
* </code>
* @return string
*/

function cms_get_convert_contact_forms($value){
  $validator_form = '/\[contact-form.*?id=\"(\d+)\".title=\"(.*?)\"]/';

  if(preg_match_all($validator_form,$value, $out_form)){
    $matches = $out_form[1];
    return cms_replacement_form_code($matches[0]);
  }
  return 'nothing';
}
function cms_get_covert_contact_forms($value){
  $validator_form = '/\[contact-form.*?id=\"(\d+)\".title=\"(.*?)\"]/';

  if(preg_match_all($validator_form,$value, $out_form)){
    $matches = $out_form[1];
    return cms_replacement_form_code($matches[0]);
  }
  return 'nothing';
}


/**
* cms_get_author: will get author of current post
* Here is an inline example:
* <code>
* <?php
*  cms_get_author()
* ?>
* </code> 
* @return String
*/
function cms_get_author() {
  global $cms_current_post;
  return $cms_current_post['post_author'];
}


/**
* cms_get_languages: will get list of defined languages of the system
* Here is an inline example:
* <code>
* <?php
*  cms_get_languages()
* ?>
* </code> 
* @return void
*/
function cms_get_languages() {
  /* get languages from cms_items */
  global $db;
  global $cms_current_language;

  $sql = "SELECT `meta` FROM ((Select * From `cms_items` Union All (Select '0' `id`, if((Select count(*) `c` From `cms_items` Where `type` = 'cms-language' and `guid`=1)>0,0,1) `guide`,'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added`))) `t1` Where type = 'cms-language' and `status` = 'active' Order By `value` asc";
  $l = array();

  foreach ($db->select($sql) as $key => $value) {
    $l[] = $value->meta;
  }

  return $l;
}

/**
* cms_get_language: will get list of current defined languages of the system
* Here is an inline example:
* <code>
* <?php
*  cms_get_language()
* ?>
* </code> 
* @return void
*/
function cms_get_language() {
  global $cms_current_language;
  return $cms_current_language;
}
/**
* cms_get_language_default: will get list of default languages of the system
* Here is an inline example:
* <code>
* <?php
*  cms_get_language_default()
* ?>
* </code> 
* @return void
*/
function cms_get_language_default() {
  global $cms_default_language;
  return $cms_default_language;
}
/**
* cms_get_language_default: will get list of default languages of the system
* Here is an inline example:
* <code>
* <?php
*  cms_get_language_default()
* ?>
* </code> 
* @return void
*/
function cms_get_language_reserved() {
  global $cms_reserved_language;
  return $cms_reserved_language;
}

/**
* cms_set_language: will set the value of `cms_current_language` and return boolean if the added language exist
* Here is an inline example:
* <code>
* <?php
*  cms_set_language()
* ?>
* </code> 
* @return boolean
*/
function cms_set_language($lang_value = '') {
  global $cms_current_language;
  $languages = cms_get_languages();
  if (in_array($lang_value, $languages)) {
    $cms_current_language = $lang_value;
    return true;
  }
  return false;
}



function cms_indexing(){
  $front_config = fopen("robots.txt","w");
  $txt = '';
  $robot_text = get_system_option('system_robot_txt');

  $txt .= $robot_text != '' ? $robot_text : "";
  $txt .= ($txt != '' ? "\n\n" : "") . (get_system_option('disallow_indexing') == 'YES' ? "User-agent: * \nDisallow: /" : "User-agent: * \nAllow: /");

  fwrite($front_config,$txt);
  fclose($front_config);
}

function cms_check_link($link=""){
  $link_db = new Database();

  $navigated_url = $link_db->select("SELECT * FROM `urls` WHERE `url` = '{$link}' Order By `id` Desc Limit 1");

  if (count($navigated_url)) {
    $navigated_url = $navigated_url[0];

    $latest_urls = $link_db->select("Select * From `urls` WHere `id` > '{$navigated_url->id}'");
    $latest_url = $navigated_url;
    $is_new = false;
    $has_new = true;

    while ($has_new) {
      $_c = false;
      foreach ($latest_urls as $key => $value) {
        if ($value->url_id == $latest_url->id) {
          $latest_url = $value;
          $_c = true;
          $is_new = true;
          unset($latest_urls[$key]);
          break;
        }
      }
      if (!$_c) {
        $has_new = false;
      }
    }

    if ($is_new) {
      redirect($latest_url->url);
    } 
  }

  return;
}

function cms_is_page_template(){

  return true;

}

function cms_string_sanitizer($data=""){

  if (gettype($data)=='array'){

    foreach(array_keys($data) as $key){

      $data[$key] = cms_string_sanitizer($data[$key]);

    }

    return $data;

  }elseif (gettype($data)=='object'){

    foreach ($data as $key => $value) {

      $data->{$key} = cms_string_sanitizer($value);

    }

    return $data;

  }

  else{

    return filter_var($data, FILTER_SANITIZE_STRING);

  }

}

function cms_is_home(){
  global $cms_current_post;
  $homepage_slug = cms_get_homepage_slug();
  return $homepage_slug == $cms_current_post['url_slug'];
}
function cms_get_homepage_slug(){
  global $db;
  $homepage_id = get_system_option(array('option_name'=> 'homepage'));
  $homepage_slug = "";
  // $homepage_info = $db->select("Select * From `cms_posts` Where `id` = '{$homepage_id}' and `post_type` = 'page'");
  
  $sql = "Select url_slug, ci.meta language From `cms_posts`, (Select id, meta From `cms_items` Where `type` = 'cms-language-default' Union ( Select '0' `id`, 'en' `meta` ) Order By `id` desc Limit 1) ci Where cms_posts.id = '{$homepage_id}' and ci.meta = '". cms_get_language() ."' UNION SELECT url_slug, language from `cms_posts_translate` Where `post_id` = '{$homepage_id}' and language = '". cms_get_language() ."';";

  $homepage_info = $db->select($sql);

  if (count($homepage_info)) {
    $homepage_info = $homepage_info[0];
    $homepage_slug = $homepage_info->url_slug;
  }

  return $homepage_slug;
}
function cms_is_blog(){
  global $cms_current_post;
  $homepage_slug = cms_get_blog_slug();
  return $homepage_slug == $cms_current_post['url_slug'];
}
function cms_get_blog_slug(){
  global $db;
  $blog_id = get_system_option(array('option_name'=> 'blog_page'));
  $blog_slug = "";
  $blog_info = $db->select("Select * From `cms_posts` Where `id` = '{$blog_id}' and `post_type` = 'page'");
  if (count($blog_info)) {
    $blog_info = $blog_info[0];
    $blog_slug = $blog_info->url_slug;
  }

  return $blog_slug;
}

function cms_query_blog_posts( $category_ids = array() ) {
  global $cms_query_filters;
  $cms_query_filters['category_id'] = $category_ids;
}
function cms_get_post_category_ids( $post_id = 0 ){
  global $db;
  $post_id = $post_id != 0 ? $post_id : cms_get_post_id();
  $condition = $post_id != 0 ? " Where `post_id` = '{$post_id}'" : "";
  $result = $db->select ("SELECT * FROM `posts_categories_relationship` `table1` {$condition} ");

  $output = array();
  foreach ($result as $key => $value) {
    $output[] = $value->category_id;
  }
  $output = array_values(array_unique($output));
  return $output;
}

function cms_get_post_detail($key = "") {
  global $cms_current_post;
  if ($key != "" && isset($cms_current_post[$key])) {
    return $cms_current_post[$key];
  }
  return "";
}
function cms_get_post_id() {
  return cms_get_post_detail('id');
}
function cms_get_post_date($format = "Y-m-d H:i:s") {
  $post_date = strtotime(cms_get_post_detail('post_date'));
  return date($format, $post_date);;
}
function cms_get_post_active_status() {
  return cms_get_post_detail('post_status');
}
function cms_get_post_status() {
  return cms_get_post_detail('status');
}
function cms_get_post_type() {
  return cms_get_post_detail('post_type');
}
function cms_get_home_canonical(){
  $siteurl_info = get_site_url_info();
  $siteurl = $siteurl_info['siteurl'];

  return $siteurl;
}
function cms_get_canonical(){
  $canonical_url = "";
  if (cms_is_home()) {
    $canonical_url = cms_get_home_canonical();
  }else{
    if ( cms_get_post_detail('post_type') == 'page' ) {
      $canonical_url = cms_get_post_detail('seo_canonical_url');
    }elseif( cms_get_post_detail('post_type') == 'post' ){
      $post_url_slug = cms_get_post_detail('url_slug');
      $link_db = new Database();
      $page_info = $link_db->select("Select * From `cms_posts` Where `url_slug` = '{$post_url_slug}'");
      if (count($page_info)) {
        $page_info = $page_info[0];

        $canonical_url = cms_get_post_url($page_info);
      }
    }
    if ($canonical_url == "") {
      $canonical_url = get_current_url();
    }
  }
  $canonical_url = $canonical_url != "" ? '<link rel="canonical" href="'. $canonical_url .'" />' : "";
  return $canonical_url;
}

// COMPONENTS
function get_header($header = ""){
  cms_include_file(array(
    "base_name" => "header", "type" => $header,
  ));
}
function get_sidebar($sidebar = ""){
  cms_include_file(array(
    "base_name" => "sidebar", "type" => $sidebar,
  ));
}
function get_footer($footer = ""){
  cms_structured_data();
  cms_include_file(array(
    "base_name" => "footer", "type" => $footer,
  ));
}
function cms_get_fragment($fragment_folder = "", $fragment_type = "", $default=""){
  cms_include_file(array(
    "base_folder" => "fragments", 
    "base_name" => "fragment", 
    "type" => $fragment_type,
  ));
}
function cms_include_file($option = array()){
  global $cms_theme_file_path;

  $file_folder = isset($option['base_folder']) ? $option['base_folder'] . "/" : "";
  $file_prefix = isset($option['base_name']) ? $option['base_name'] : "";
  $file_name = isset($option['type']) ? "-".$option['type'] : "";

  $file = $file_folder . $file_prefix . $file_name . ".php";
  $file_defualt = $file_folder . $file_prefix . ".php";

  if (isset( $option['variables'] )) {
    extract($option['variables']);
  }

  if(is_file( $cms_theme_file_path . $file )){
    include $cms_theme_file_path . $file;
    return;
  }elseif(is_file( $cms_theme_file_path . $file_defualt )){
    include $cms_theme_file_path . $file_defualt;
    return;
  }
}

function cms_get_sidebar_item( $item_name = "" ){
  switch ( $item_name ) {
    case 'categories':
    echo cms_get_sidebar_item_categories();
    break;
    case 'product-categories':
    echo cms_get_sidebar_item_product_categories();
    break;
    case 'recent-post':
    echo cms_get_sidebar_item_recent_post();
    break;
    default:
    cms_get_sidebar_custom_item($item_name);
    break;
  }
}

function cms_get_sidebar_custom_item($sidebar_item){
  if (isset($sidebar_item)) {
    print_r($sidebar_item);
  }else{
    print_r("no");
  }
}

function cms_get_sidebar_item_categories(){
  global $db;
  
  $lang_default = cms_get_language_default();
  $lang = cms_get_language();
  $l = $lang != $lang_default ? cms_get_language() . "/" : '';

  $categories_sql = "Select `t1`.*, `ct`.`meta` `translate` From (Select * From `post_category` Union All (Select 0 `id`, 'Uncategorized' `category_name`, '' `category_description`, 0 `category_parent`, 'uncategorized' `url_slug`, 0 `sort_order`, 'post' `categories_type`, 'active' `status`)) `t1` Left Join `cms_translation` `ct` On `t1`.`id` = `ct`.`guid` and `language` = '{$lang}' and type = 'post-category' Where `status` = 'active' Order By `sort_order`, `id`";
  $categories = $db->select($categories_sql);

  $_output = array();

  $uc = new UC();
  foreach ($categories as $key => $value) {
    if ($value->translate) {
      $t = json_decode($value->translate);
      $value->category_name = $t->category_name;
      $value->category_description = $t->category_description;
    }

    $category_url = $uc->uc_get_final_url(get_bloginfo('baseurl') . $l . "categories/" .  $value->url_slug);
    $_output[] = '<li><a href="'. $category_url .'">'. $value->category_name .'</a></li>';
  }

  return '<ul>'. implode("\n", $_output) .'</ul>';
}
function cms_get_sidebar_item_product_categories(){
  global $db;

  $lang_default = cms_get_language_default();
  $lang = cms_get_language();
  $l = $lang != $lang_default ? $lang . "/" : '';

  $temp = $db->select("Select * From `cms_translation` Where `type` = 'product-category' and `language` = '{$lang}'");
  $translated_categories = array();
  foreach ($temp as $key => $value) {
    $t = json_decode($value->meta);
    $translated_categories[$value->guid] = array(
      "category_name" => $t->category_name,
      "category_description" => $t->category_description,
      "url_slug" => $t->url_slug,
    );
  }

  $categories = ecatalog_get_categories();
  $_output = array();
  $uc = new UC();

  /*Get custom defined slug for Product Category*/
  $product_category_base_url = get_system_option('product_category_url' . (cms_get_language()!=cms_get_language_reserved()?'_'.cms_get_language() : ''));   
  
  foreach ($categories as $key => $value) {
    if (isset($translated_categories[$value['id']])) {
      $value['category_name'] = $translated_categories[$value['id']]['category_name'];
      $value['category_description'] = $translated_categories[$value['id']]['category_description'];
      $value['url_slug'] = isset($translated_categories[$value['id']]['url_slug']) ? $translated_categories[$value['id']]['url_slug'] : $value['url_slug'];
    }

    $pcurl = $uc->uc_get_final_url(get_bloginfo("baseurl") . $l . "{$product_category_base_url}/" . $value['url_slug']);
    $_output[] = '<li><a href="'. $pcurl .'">'. $value['category_name'] .'</a></li>';
  }
  return '<ul>'. implode("\n", $_output) .'</ul>';
}
function cms_get_sidebar_item_recent_post(){
  global $db;
  $lang_default = cms_get_language_default();
  $lang = cms_get_language();
  $l = $lang != $lang_default ? cms_get_language() . "/" : '';

  $recent_posts = $db->select("Select `c`.*, ifnull(`c2`.`post_title`, '') `translated_post_title`, ifnull(`c2`.`post_content`, '') `translated_post_content` From `cms_posts` `c` Left Join `cms_posts_translate` `c2` ON `c2`.`post_id` =  `c`.`id` and `language` = '{$lang}' Where `c`.`status` = 'publish' and `c`.`post_type` = 'post' Order By `c`.`post_date` Desc");

  $_output = array();
  $uc = new UC();
  $uc->set_current_language(cms_get_language());
  foreach ($recent_posts as $key => $value) {
    $rpurl = $uc->uc_get_final_url( get_bloginfo('baseurl') . $l . $value->url_slug );

    if ($value->translated_post_title != "") {
      $value->post_title = $value->translated_post_title;
    }
    if ($value->translated_post_content != "") {
      $value->post_content = $value->translated_post_content;
    }
    $_output[] = '<li><a href="'. $rpurl .'">'. $value->post_title .'</a></li>';
  }
  return '<ul>'. implode("\n", $_output) .'</ul>';
}

function cms_contact_form_honey_pot(){
  global $cms_enable_honey_pot;
  if (!$cms_enable_honey_pot) return "";

  $honey_pot_fields = array(
    array(
      "label" => "Are you from Singapore ?",
      "id" => "from-singapore",
    ),
    array(
      "label" => "What is your Job Title?",
      "id" => "job-title",
    ),
    array(
      "label" => "Job Experience ?",
      "id" => "job-experience",
    ),
    array(
      "label" => "Job Country ?",
      "id" => "job-country",
    ),
    array(
      "label" => "Office Address ?",
      "id" => "office-address",
    ),
    array(
      "label" => "Traveling Experience ?",
      "id" => "travelling-experience",
    ),
    array(
      "label" => "Card Number Exclusive",
      "id" => "card-number-exclusive",
    ),
    array(
      "label" => "Promotional Code Ex-full Discount",
      "id" => "promotional-code-ex-full-discount",
    ),
    array(
      "label" => "Overseas Telephone Number",
      "id" => "overseas-telephone-number",
    ),
    array(
      "label" => "Alternate Email Address",
      "id" => "alternate-email-3",
    ),
    array(
      "label" => "PW Agent ID",
      "id" => "pw-agent-id-1225",
    ),
  );

  $str_field = "";

  foreach ($honey_pot_fields as $key => $value) {
    $input = '<input type="text" name="'. $value['id'] .'" id="'. $value['id'] .'">';
    $wrapper = '<span class="cms-form-control-wrap '. $value['id'] .'">'. $input .'</span>';
    $p = '<label>'. $value['label'] .'</label><br>'. $wrapper;
    $str_field .= $p;
  }
  return $str_field;
}


function cms_validate_post_url( $page_info = array() ){
  global $url;

  return true; /*temporary suspend post format validation*/

  if (count($page_info) <= 0) {
    return false;
  }

  $post_url_format = get_system_option(array("option_name" => "post_url_format"));
  $post_url_info = cms_post_format_info( $post_url_format );
  $post_url_info_extracted = explode("/", trim($post_url_info['format'], '/'));
  $current_url_data = $url->url;
  $post_slug = $page_info->url_slug;

  $link_db = new Database();

  $post_categories_result = $link_db->select("SELECT * FROM `post_category` Left Join `posts_categories_relationship` On `post_category`.`id` = `posts_categories_relationship`.`category_id` Where `posts_categories_relationship`.`post_id` = '{$page_info->id}'");
  $post_category = array();
  foreach ($post_categories_result as $key => $value) {
    $post_category[] = $value->url_slug;
  }

    //testing if the FORMAT and current url have the same array size
  $valid_format = true;
  if (count($post_url_info_extracted) == count($current_url_data) ) {
    for ($i=0; $i < count($post_url_info_extracted); $i++) { 
      $value_type = $post_url_info_extracted[$i];

      if ($value_type == '[post-category]') {
        if ( !in_array($current_url_data[$i], $post_category) ) {
          $valid_format = false;
        }
      }elseif($value_type == '[post-name]'){
        if ($current_url_data[$i] != $post_slug) {
          $valid_format = false;
        }
      }else{

      }
    }
  }else{
    $valid_format = false;
  }

  return $valid_format;
}
function cms_get_post_url( $page_info = array() ){
  $post_url_format = get_system_option(array("option_name" => "post_url_format"));
  $post_url_info = cms_post_format_info( $post_url_format );
  $post_url_info_extracted = explode("/", trim($post_url_info['format'], '/'));
  $post_slug = $page_info->url_slug;

  $link_db = new Database();
  $post_categories_result = $link_db->select("SELECT * FROM `post_category` Left Join `posts_categories_relationship` On `post_category`.`id` = `posts_categories_relationship`.`category_id` Where `posts_categories_relationship`.`post_id` = '{$page_info->id}'");
  $post_category = array();
  foreach ($post_categories_result as $key => $value) {
    $post_category[] = $value->url_slug;
  }

    //finding the possible posts
  $url_set = array();

  for ($i=0; $i < count($post_url_info_extracted); $i++) { 
    $value_type = $post_url_info_extracted[$i];

    if ($value_type == '[post-category]') {
      $url_set[] = $post_category[0];
    }elseif($value_type == '[post-name]'){
      $url_set[] = $post_slug;
    }else{

    }
  }

  $siteurl_info = get_site_url_info();
  $possible_slug = implode("/", $url_set);
  $trailing_slash = $siteurl_info['has_slash'] ? "/" : "";

  return trim($siteurl_info['siteurl'],'/') . '/' . ltrim($possible_slug, '/') . $trailing_slash;
}
function cms_post_format_info( $post_url_format = '' ){
  global $post_link_format;

  $post_url_info = null;
  foreach ($post_link_format as $key => $value) {
    if ($post_url_format == $value['value']) {
      $post_url_info = $value;
    }
  }
  return $post_url_info;
}


function cms_get_seo_meta_robot($system_option = ''){
  $noindex = false;

  $uc = new UC();
  $uc->set_current_language(cms_get_language());
  $current_url_status = $uc->uc_get_url_validity_info_2(get_current_url());

  if ($system_option != '') {
    $index_option = get_system_option(array("option_name" => $system_option));
    if ( strtolower($index_option) == 'n' ) {
      $noindex = true;
    }
  }elseif($current_url_status['url_header'] == '404'){
    $noindex = true;
  }elseif(isset($current_url_status['no_index']) && $current_url_status['no_index']){
    $noindex = true;
  }else{
    $option_query = '';
    $condition = 'n';

    switch ( Session::get('ispage') ) {
      case 'product':
      $condition = "y";
      $option_query = 'product_no_index_ecommerce_ecatalog';
      break;
      case 'product-category':
      $condition = "y";
      $option_query = 'product_no_index_category_page';
      break;
      case 'product-detail':
      $condition = "y";
      $option_query = 'product_no_index_detail_page';
      break;
      case 'product-cart':
      $condition = "y";
      $option_query = 'product_no_index_cart';
      break;
      case 'product-checkout':
      $condition = "y";
      $option_query = 'product_no_index_checkout';
      break;
      case 'product-enquiry':
      $condition = "y";
      $option_query = 'product_no_index_order_enquiry';
      break;
      case 'isblog':
      $condition = "on";
      $option_query = 'disallow_blog_indexing';
      break;
      case 'ispost':
      $condition = "on";
      $option_query = 'disallow_blog_post_indexing';
      break;
      case 'isblogsearch':
      $condition = "on";
      $option_query = 'disallow_blog_search_indexing';
      break;
      case 'isblogpagination':
      $condition = "on";
      $option_query = 'disallow_blog_pagination_indexing';
      break;
      case 'isblogcategory':
      $condition = "on";
      $option_query = 'disallow_blog_category_indexing';
      break;
      default:
      $noindex = strtolower(cms_get_post_detail('seo_no_index')) == 'y' ? true : false;
      break;
    }

    $index_option = get_system_option($option_query);
    if ( strtolower($index_option) == $condition ) {
      $noindex = true;
    }
  }

  if (get_system_option('disallow_indexing') == 'YES') {
    $noindex = true;
  }

  return $noindex ? '<meta name="robots" content="noindex">' : '';
}

/*PRODUCTS FUNTIONS*/
function cms_get_product_category_tree($parent_id = 0){
  global $db;
  $lang = cms_get_language();
  // $category = $db->select("Select * From `product_categories` Where `id` = '{$parent_id}'");
  $category = $db->select("Select `pc`.*, `ct`.`meta` `translate` From `product_categories` `pc` Left Join `cms_translation` `ct` On `pc`.`id` = `ct`.`guid` and `ct`.`language` = '{$lang}' and `ct`.`type` = 'product-category' Where `pc`.`id` = '{$parent_id}'");
  $installation_url = get_bloginfo('installation_url');

  $category = count($category) ? $category[0] : array();
  $uc = new UC();
  $category->final_url = $uc->uc_get_final_url($installation_url . "/product-category/". $category->url_slug);

  $categories = cms_get_product_categories($parent_id);
  $products = cms_get_product($parent_id);

  if (count($category)) {
    $t = json_decode($category->translate);

    if (isset($t->category_name)) {
      $category->category_name = $t->category_name;
    }
    if (isset($t->category_description)) {
      $category->category_description = $t->category_description;
    }
  }

  $output = array(
    "detail" => $category,
    "products" => $products,
    "children" => array(),
  );

  foreach ($categories as $categories_key => $categories_value) {
    $output['children'][] = cms_get_product_category_tree($categories_value->id);
  }

  return $output;
}
function cms_get_product_category_children($parent_id = 0){
  $categories = cms_get_product_categories($parent_id);
  $output = array();

  foreach ($categories as $categories_key => $categories_value) {
    $output[] = array(
      "detail" => $categories_value,
      "products" => cms_get_product($categories_value->id),
      "children" => cms_get_product_category_children($categories_value->id),
    );
  }

  return $output;
}
function cms_get_product_categories($filter_parent = ""){
  global $db;

  $sql = "SELECT `product_categories`.* FROM `product_categories`";
  if ($filter_parent !== "") {
    $sql .= " Where `category_parent` = '{$filter_parent}'";
  }
  $sql .= " ORDER BY `id` ASC, `sort_order` ASC";

  $children = $db->select($sql);
  return $children;
}
function cms_get_product($filter_parent = ""){
  global $db;

  // $sql = "SELECT `products`.* FROM `products` Inner Join `products_categories_relationship` `pcr` On `products`.`id` = `pcr`.`product_id` ";
  $lang = cms_get_language();
  $sql = "SELECT `products`.*, `ct`.`meta` `translate` FROM `products` Inner Join `products_categories_relationship` `pcr` On `products`.`id` = `pcr`.`product_id` Left Join `cms_translation` `ct` On `ct`.`type` = 'product' and `ct`.`language` = '{$lang}' and `ct`.`guid` = `products`.`id` ";

  if ($filter_parent !== "") {
    $sql .= " Where `category_id` = '{$filter_parent}'";
  }
  $sql .= " ORDER BY `id` ASC";

  $children = $db->select($sql);

  foreach ($children as $key => $value) {
    if (isset($value->translate)) {
      $t = json_decode($value->translate);

      if (isset($t->product->product_name)) {
        $children[$key]->product_name = $t->product->product_name;
      }
      if (isset($t->product->product_description)) {
        $children[$key]->product_description = $t->product->product_description;
      }
    }
  }
  return $children;
}

function cms_generate_payment_button(){
  $db = new Database();
  $options = array();
  foreach ($db->select('SELECT * FROM `payment_gateway_options`') as $key => $value) {
    $options[$value->option_name] = $value->option_value;
  }

  if (isset($_SESSION['product_current_user_detail_method'])) {
    $payment_info = ecatalog_get_payment_info($_SESSION['product_current_user_detail_method']);

    $paypal_info = array(
      "sandbox"   => isset($options['paypay_is_sandbox']) ? $options['paypay_is_sandbox'] : 'N',
      "express_sandbox" => isset($options['paypal_express_is_sandbox']) ? $options['paypal_express_is_sandbox'] : '',
      "email"     => isset($options['paypal_email']) ? $options['paypal_email'] : '',
      "code"      => isset($options['paypal_currence_code']) ? $options['paypal_currence_code'] : '',
      "recurring" => isset($options['paypal_recurring_payments']) ? $options['paypal_recurring_payments'] : '',
      "language"  => isset($options['paypal_language']) ? $options['paypal_language'] : '',
      "username"  => isset($options['paypal_express_username']) ? $options['paypal_express_username'] : '',
      "password"  => isset($options['paypal_express_password']) ? $options['paypal_express_password'] : '',
      "signature" => isset($options['paypal_express_signature']) ? $options['paypal_express_signature'] : '',
      "production_key" => '',
    );

    $style = array(
      "size"    => 'large',
      "shape"   => 'pill',
      "color"   => 'gold',
      "tagline" => false,
      "label"   => 'checkout',
    );

    if($payment_info->gateway_type == 'PAYPAL_CHECKOUT'){
      if ($payment_info->enabled != 'Y') {
        echo "Payment Method was disabled selected";
        return;
      }

      $paypal_checkout_options = array();
      foreach ($db->select("SELECT * FROM `payment_gateway_options` Where `option_name` Like 'paypal_checkout_%'") as $key => $value) {
        $paypal_checkout_options[$value->option_name] = $value->option_value;
      }

      if (isset($paypal_checkout_options['paypal_checkout_production_key'])) {
        $paypal_info['production_key'] = $paypal_checkout_options['paypal_checkout_production_key'];
      }
      if (isset($paypal_checkout_options['paypal_checkout_sandbox'])) {
        $paypal_info['sandbox'] = $paypal_checkout_options['paypal_checkout_sandbox'];
      }

      /* Set Paypal Checkout Button */
      $pp_btn = array();
      foreach ($db->select("SELECT * FROM `payment_gateway_options` Where `option_name` Like 'pp_chkout_btn_%'") as $key => $value) {
        $pp_btn [$value->option_name] = $value;
      }

      $style = array(
        "size"    => isset($pp_btn['pp_chkout_btn_size']) ? $pp_btn['pp_chkout_btn_size']->option_value : 'small',
        "shape"   => isset($pp_btn['pp_chkout_btn_shape']) ? $pp_btn['pp_chkout_btn_shape']->option_value : 'pill',
        "color"   => isset($pp_btn['pp_chkout_btn_color']) ? $pp_btn['pp_chkout_btn_color']->option_value : 'gold',
        "label"   => isset($pp_btn['pp_chkout_btn_label']) ? $pp_btn['pp_chkout_btn_label']->option_value : 'checkout',
        "tagline" => (isset($pp_btn['pp_chkout_btn_tag']) && $pp_btn['pp_chkout_btn_tag']->option_value == 'Y'),
      );

      $data = array(
        'sandbox' => $paypal_info['sandbox'],
        'paypal_checkout_sandbox_client_id' => $paypal_checkout_options['paypal_checkout_sandbox_client_id'],
        'paypal_checkout_client_id' => $paypal_checkout_options['paypal_checkout_client_id'],
        'style' => $style,
        // 'transaction' => $transaction,
      );

      _private_paypal_checkout_layout($data);

     
    }else if($payment_info->gateway_type == 'PAYPAL_STANDARD' || $payment_info->gateway_type == 'PAYPAL_EXPRESS'){
      echo '<form action="'. cms_get_payment_url() .'" method="post">
        <p>Finish you purchase by paying through paypal</p>
        <button class="btn btn-success" name="proceed-paypal" value="proceed-paypal">Proceed to Paypal</button>
      </form>';
    }else{
      $action = cms_get_confirmation_url();
      echo '<form action="'. $action .'" method="post"><p>Finish you purchase by submitting your order</p><button class="btn btn-success" name="submit" value="submit">Submit Order</button></form>';
    }
  }
}
function cms_product_set_payment_method($method_id = 0){
  if ($_method_id != 0) {
    $_SESSION['product_current_user_detail_method'] = $_method_id;
  }
}
function cms_product_set_user_detail($user = array()){
  $billing_name = (isset($user['first_name']) && isset($user['first_name'])) ? "{$user['first_name']} {$user['last_name']}" : "";

  $data = array(
    "company"         => isset($user['company']) ? $user['company'] : "",
    "email"           => isset($user['email']) ? $user['email'] : "",
    "first_name"      => isset($user['first_name']) ? $user['first_name'] : "",
    "last_name"       => isset($user['last_name']) ? $user['last_name'] : "",
    "phone"           => isset($user['phone']) ? $user['phone'] : "",

    "billing_name"    => $billing_name,
    "billing_address" => isset($user['billing_address']) ? $user['billing_address'] : '',
    "billing_address_line_2" => isset($user['billing_address_line_2']) ? $user['billing_address_line_2'] : '',
    "billing_city"    => isset($user['billing_city']) ? $user['billing_city'] : '',
    "billing_postal"  => isset($user['billing_postal']) ? $user['billing_postal'] : '',
    "billing_state"   => isset($user['billing_state']) ? $user['billing_state'] : '',
    "billing_country" => isset($user['billing_country']) ? $user['billing_country'] : "",
    "billing_email"   => isset($user['billing_email']) ? $user['billing_email'] : "",
    "billing_phone"   => isset($user['billing_phone']) ? $user['billing_phone'] : "",

    "shipping_name"   => isset($user['shipping_name']) ? $user['shipping_name'] : '',
    "shipping_address" => isset($user['shipping_address']) ? $user['shipping_address'] : '',
    "shipping_address_line_2" => isset($user['shipping_address_line_2']) ? $user['shipping_address_line_2'] : '',
    "shipping_city"   => isset($user['shipping_city']) ? $user['shipping_city'] : '',
    "shipping_postal" => isset($user['shipping_postal']) ? $user['shipping_postal'] : '',
    "shipping_state"  => isset($user['shipping_state']) ? $user['shipping_state'] : '',
    "shipping_country"=> isset($user['shipping_country']) ? $user['shipping_country'] : '',
    "shipping_email"  => isset($user['shipping_email']) ? $user['shipping_email'] : '',
    "shipping_phone"  => isset($user['shipping_phone']) ? $user['shipping_phone'] : '',
    "meta_data"       => isset($user['meta_data']) ? $user['meta_data'] : '',
    
    "payment_method_id" => cms_product_get_payment_method(),
    "message"         => isset($user['message']) ? $user['message'] : "",
    "invoice_number"  => isset($user['invoice_number']) ? $user['invoice_number'] : "",
    "order_status"    => "active",
  );

  if (!isset($_SESSION['product_current_user_detail'])) {
    $_SESSION['product_current_user_detail'] = array();
  }

  foreach ($data as $key => $value) {
    $_SESSION['product_current_user_detail'][$key] = $value;
  }
}
function cms_product_set_other_fees($data = array()){
  $fee = isset($_SESSION['product_other_fees']) ? $_SESSION['product_other_fees'] : array();
  $_SESSION['product_other_fees'] = array(
    "tax"       => isset($data['tax']) ? $data['tax'] : (isset($fee['tax']) ? $fee['tax'] : 0),
    "insurance" => isset($data['insurance']) ? $data['insurance'] : (isset($fee['insurance']) ? $fee['insurance'] : 0),
    "handling"  => isset($data['handling']) ? $data['handling'] : (isset($fee['handling']) ? $fee['handling'] : 0),
    "shipping"  => isset($data['shipping']) ? $data['shipping'] : (isset($fee['shipping']) ? $fee['shipping'] : 0),
    "shipping_discount" => isset($data['shipping_discount']) ? $data['shipping_discount'] : (isset($fee['shipping_discount']) ? $fee['shipping_discount'] : 0),
  );
}
function cms_product_get_payment_method(){
  return isset($_SESSION['product_current_user_detail_method']) ? $_SESSION['product_current_user_detail_method'] : 0;
}
function _private_paypal_checkout_layout($data = array()){
  $server_url = trim(get_system_option('site_url'),'/');
  $server_payment_url = "{$server_url}/api/cms-paypal/paypal_checkout_create_payment/";
  $server_execute_url = "{$server_url}/api/cms-paypal/paypal_execute_payment/";

  $db = new Database();

  $paypal_checkout_options = array();
  foreach ($db->select("SELECT * FROM `payment_gateway_options` Where `option_name` Like 'paypal_checkout_%'") as $key => $value) {
    $paypal_checkout_options[$value->option_name] = $value->option_value;
  }

  $page_confirmed = isset($paypal_checkout_options['paypal_checkout_url_confirmed']) ? $paypal_checkout_options['paypal_checkout_url_confirmed'] : "";
  $page_cancelled = isset($paypal_checkout_options['paypal_checkout_url_cancelled']) ? $paypal_checkout_options['paypal_checkout_url_cancelled'] : "";

  echo '<div id="paypal-button"></div>';
  echo '<script src="https://www.paypalobjects.com/api/checkout.js"></script>';
  echo "<script>
    (function(){
      paypal.Button.render({
        // Configure environment
        env: '". ($data['sandbox'] == 'Y' ? 'sandbox' : 'production') ."',
        // Customize button (optional)
        style: " . json_encode($data['style']) . ",
        // Set up a payment
        payment: function (data, actions) {
          return actions.request.post('{$server_payment_url}')
          .then(function(res) {
            // 3. Return res.id from the response
            return res.id;
          });
        },
        // Execute the payment
        onAuthorize: function (data, actions) {
          return actions.request.post('{$server_execute_url}', {
              paymentID: data.paymentID,
              payerID:   data.payerID
            })
              .then(function(res) {
                // 3. Show the buyer a confirmation message.
                if(res.status){
                  return actions.redirect();
                }else{
                  console.log('An error found while executing payment')
                  console.log('Execution Error: ' + res.message)
                }
                // return actions.redirect();
              });
        },
        onCancel: function (data, actions) {
          // Show a cancel page or return to cart
          return actions.redirect();
        },
      }, '#paypal-button');
    })();
    </script>";
}
function cms_get_shipping_info($area_name = ''){
  $db = new Database();

  $rate = $db->select("Select * From `shipping_rates` Where `rate_type` = 'default'");
  $rate_output = array(
    "rate_name"   => "",
    "rate_amount" => 0,
  );

  if (count($rate) > 0) {
    $rate = $rate[0];

    $rate_output = array(
      "rate_name"   => $rate->rate_name,
      "rate_amount" => $rate->rate_amount,
    );
  }

  $_s = array(
    "'shipping_origin_name'",
    "'shipping_origin_address_1'",
    "'shipping_origin_address_2'",
    "'shipping_origin_city'",
    "'shipping_origin_postal'",
    "'shipping_origin_country'",
    "'shipping_origin_phone'",
    );
  $_so = implode(",", $_s);

  $temp = $db->select("Select * From `system_options` Where `option_name` In ({$_so})");
  $shipping_data = array();

  if (count($temp)) {
    foreach ($temp as $key => $value) {
      $shipping_data[$value->option_name] = $value->option_value;
    }
  }

  /* shipping area start */
  $shipping_area_detail = array();

  $sql_countries  = "SELECT * FROM `countries`";
  $temp_countries = $db->select( $sql_countries );
  $country        = array();
  foreach ($temp_countries as $key => $value) {
    $country[$value->value] = $value;
  }

  if ($area_name == "") {
    $sql_shipping_area = "SELECT *, a.id main_id FROM `shipping_areas` a Left Join `shipping_rates` r on a.id = r.area_id and r.rate_type <> 'default' Left JOIN (SELECT area area_id, GROUP_CONCAT(country_id) country_ids FROM `shipping_country` Group By area) c On c.area_id = a.id";
  }else{
    $sql_shipping_area = "SELECT *, a.id main_id FROM `shipping_areas` a Left Join `shipping_rates` r on a.id = r.area_id and r.rate_type <> 'default' Left JOIN (SELECT area area_id, GROUP_CONCAT(country_id) country_ids FROM `shipping_country` Group By area) c On c.area_id = a.id Where a.area_name = '{$area_name}'";
  }

  $result_shipping_area = $db->select( $sql_shipping_area );
  $shipping_area = array();

  foreach ($result_shipping_area as $key => $value) {
    if (!isset($shipping_area[$value->area_name])) {
      unset($value->id);
      $shipping_area[$value->area_name] = array(
        'detail' => $value,
        'rate' => array(),
        'country' => array(),
      );
    }

    $shipping_area[$value->area_name]['rate'][] = array(
      "rate_name" => $value->rate_name,
      "rate_description" => $value->rate_description,
      "rate_type" => $value->rate_type,
      "rate_min" => $value->rate_min,
      "rate_max" => $value->rate_max,
      "rate_free" => $value->rate_free,
      "rate_amount" => $value->rate_amount,
      "countries" => $value->country_ids,
    );

    if ($value->country_ids) {
      $c_temp = explode(',', $value->country_ids);
      foreach ($c_temp as $kk => $vv) {
        if (isset($country[trim($vv)])) {
          $shipping_area[$value->area_name]['country'][$vv] = $country[$vv]->name;
        }
      }
    }
  }

  $d = $db->select("SELECT * FROM `shipping_rates` Where `rate_type` = 'default'");
  if (count($d) > 0) {
    $d = $d[0];
  }

  $shipping_default_rate = array(
    'area_id' => isset($d->area_id) ? $d->area_id : '',
    'rate_name' => isset($d->rate_name) ? $d->rate_name : '',
    'rate_description' => isset($d->rate_description) && $d->rate_description != null ? $d->rate_description : '',
    'rate_type' => isset($d->rate_type) ? $d->rate_type : 'default',
    'rate_min' => isset($d->rate_min) ? $d->rate_min : 0,
    'rate_max' => isset($d->rate_max) ? $d->rate_max : 0,
    'rate_amount' => $d->rate_amount,
  );

  $output = array(
    "default"  => $shipping_default_rate,
    "area"     => $shipping_area,
  );

    return $output;
}
function cart_get_products(){
  $items    = ecatalog_cart_get_products();
  $shipping = cms_get_shipping_info();
  $shipping_enable = get_system_option('shipping_rate_enable') == 'Y';
  $total    = 0;

  if ($shipping_enable){
    if (isset($shipping['default']) && isset($shipping['default']['rate_amount'])) {
      $total += $shipping['default']['rate_amount'];
    }
  }else{
    $shipping['default']['rate_amount'] = 0;
  }

  foreach ($items as $key => $value) {
    $total += floatval($value['product']['price']) * intval($value['quantity']);
  }

  $output = array(
    'cart_items'      => $items,
    'cart_total'      => $total,
    'shipping_enable' => $shipping_enable,
    'shipping_default'=> $shipping['default'],
    'shipping_area'   => $shipping['area'],
    'tax_info'        => array(),
  );

  return $output;
}
function cms_set_last_order_id($id){
  $_SESSION['cms_last_order_id'] = $id;
}
function cms_get_last_order_id(){
  return isset($_SESSION['cms_last_order_id']) ? $_SESSION['cms_last_order_id'] : null;
}
function cms_order_detail($order_id){
  global $db;

  /* GET ORDER */
  $order = array();
  $order_additional = array();
  $order_delivery = array();
  $order_details = array();
  $order_payment = array();

  $temp = $db->select("Select * From orders Where id = '{$order_id}'");
  if (count($temp) > 0) {
    foreach ($temp[0] as $key => $value) {
      $order[$key] = $value;
    }

    /* GET ORDER DETAIL */
    $temp = $db->select("Select * From order_details Where order_id = '{$order_id}'");
    foreach ($temp as $key => $value) {
      $t = array();
      foreach ($value as $k => $v) {
        $t[$k] = $v;
      }
      $order_details[] = $t;
    }

    /* GET ORDER ADDITIONAL */
    $temp = $db->select("Select * From orders_additional Where order_id = '{$order_id}'");
    foreach ($temp as $key => $value) {
      $t = array();
      foreach ($value as $k => $v) {
        $t[$k] = $v;
      }
      $order_additional[] = $t;
    }

    /* GET ORDER DELIVERY DETAIL */
    $temp = $db->select("Select * From order_delivery_detail Where order_id = '{$order_id}'");
    if (count($temp) > 0) {
      foreach ($temp[0] as $key => $value) {
        $order_delivery[$key] = $value;
      }
    }

    /* GET PAYMENT */
    $temp = $db->select("Select * From order_payments Where order_id = '{$order_id}'");
    if (count($temp) > 0) {
      foreach ($temp[0] as $key => $value) {
        $order_payment[$key] = $value;
      }
    }
  }

  $order_info = array(
    'order'           => $order,
    'order_additional'=> $order_additional,
    'order_delivery'  => $order_delivery,
    'order_details'   => $order_details,
    'order_payment'   => $order_payment,
  );

  return $order_info;
}

function cms_comment_get_comments($post_id = 0){
  $post = new CMSPost();

  if ($post_id == 0) {
    $post_id = cms_get_post_id();
  }

  return $post->get_comment_by_post($post_id);
}
function cms_comment_get_comment($comment_id = 0){
  $post = new CMSPost();

  if ($comment_id==0) {
    return array();
  }
  return $post->get_comment_by_id($comment_id);
}
function cms_comment_update($comment_id = 0, $data = array()){
  $post = new CMSPost();

  if ($comment_id==0) {
    return false;
  }

  $d = array( "id" => $comment_id );
  foreach ($data as $key => $value) {
    $d[$key] = $value;
  }

  return $post->save_comment($d);
}
function cms_comment_delete($comment_id = 0){
  $post = new CMSPost();

  if ($comment_id==0) {
    return false;
  }

  $d = array( "id" => $comment_id, 'status' => 'deleted' );

  return $post->save_comment($d);
}
function cms_comment_add($post_id = 0, $data = array(), $comment_id=0){
  $post = new CMSPost();

  if ($post_id==0) {
    $post_id = cms_get_post_id();
    return false;
  }

  return $post->add_comment($post_id, $comment_id, $data);
}

function cms_get_testimonial($testimonial_id = 0){
  $db = new Database();

  if ($testimonial_id == 0) {
    $a = array();
    foreach ($db->select("Select * From `cms_items` Where `type` = 'cms-testimonial'") as $key => $value) {
      $t = array();
      foreach ($value as $k => $v) {
        $t[$k] = $v;
      }
      $a[] = $t;
    }
    return $a;
  }else{
    $result = $db->select("Select * From `cms_items` Where `type` = 'cms-testimonial' and `id` = '{$testimonial_id}'");
    $result = count($result) ? $result[0] : array();

    $a = array();
    foreach ($result as $key => $value) {
      $a[$key] = $value;
    }

    return $a;
  }
}

function cms_get_thumb($size, $file){
  return str_replace('files/uploads/image/', "files/thumbnail/{$size}/", $file);
}
function cms_structured_data(){
  $enable = get_system_option('structured_data_enable');
  $_temp = array(
    'structured_data_company_name' => get_system_option('structureds_data_company_name'),
    'structured_data_office_address' => get_system_option('structured_data_office_address'),
    'structured_data_telephone' => get_system_option('structured_data_telephone'),
    'structured_data_email' => get_system_option('structured_data_email'),
    'structured_data_price_range' => get_system_option('structured_data_price_range'),
  );

  $d  = array(
    "name"      => $_temp['structured_data_company_name'] != '' ? $_temp['structured_data_company_name'] : get_system_option('company_name'),
    "address"   => $_temp['structured_data_office_address'] != '' ? $_temp['structured_data_office_address'] : get_system_option('company_address'),
    "telephone" => $_temp['structured_data_telephone'] != '' ? $_temp['structured_data_telephone'] : get_system_option('company_contact_number'),
    "email"     => $_temp['structured_data_email'] != '' ? $_temp['structured_data_email'] : get_system_option('company_email'),
    "price"     => $_temp['structured_data_price_range'] != '' ? $_temp['structured_data_price_range'] : get_system_option('structured_data_price_range'),
    "logo"      => get_system_option('website_logo'),
  );

  $data = array(
    '@context'  => "http://schema.org/",
    '@type'     => "LocalBusiness",
    'name'      => $d['name'],
    'address'   => $d['address'],
    'telephone' => $d['telephone'],
    'image'     => $d['logo'],
    'email'     => $d['email'],
    'priceRange'=> $d['price'],
  );

  $structured_data = $enable == "ON" ? '<script type="application/ld+json">'. json_encode($data) .'</script>' : "";
  echo $structured_data;
}
function update_invoice_number(){
  global $db;
  $invoice_number = doubleval(get_system_option('invoice_next_number')) + 1;
  return $db->query("UPDATE system_options SET option_value = '{$invoice_number}' WHERE option_name = 'invoice_next_number'");
}
function get_active_theme_location(){
  global $cms_theme_file_path;
  return $cms_theme_file_path;
}

function set_last_url(){
  $_SESSION['last_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}
function get_last_url(){
  return isset($_SESSION['last_url']) ? $_SESSION['last_url'] : '';
}

/* CMS ACTIVE FUNTIONS */
function cms_active_plguin_script(){
  $db = new Database();
  $ps = $db->select("SELECT * FROM `system_options` WHERE `system_options`.`option_name` = 'promotional-popup-settings'");
  $ps = count($ps) ? $ps : null;


  $active_menu_list = array(
    "layout-custom-1" => ROOT . "system_plugins/promotional-popup/backend/views/promotional-popup/layout/layout-1.php",
    "layout-custom-2" => ROOT . "system_plugins/promotional-popup/backend/views/promotional-popup/layout/layout-2.php",
  );

  if ($ps) {
    $promo_settings = json_decode($ps[0]->meta_data);
    $promo_settings->layout->template;

    if (isset($_SESSION['user_role'])) {
      /* Edit Mode */
    }else{
      
    }

    if (isset($_GET['layout']) && isset($active_menu_list[$_GET['layout']])) {
      include $active_menu_list[$_GET['layout']];
    }elseif (isset( $active_menu_list[$promo_settings->layout->template] )) {
      include $active_menu_list[$promo_settings->layout->template];
    }
  }
}

function cms_add_log($message = "", $type = "", $user_id = ""){
  if ($user_id == "" && isset($_SESSION['customer_id'])) {
    $user_id = $_SESSION['customer_id'];
  }

  if ($type == '') {
    $type = "modify";
  }

  $db = new Database();
  $db->data = array(
    'guid' => $user_id,
    'type' => $type,
    'message' => $message,
  );
  $db->table = 'cms_logs';
  $new_log_id = $db->insertGetID();

  return $new_log_id;
}