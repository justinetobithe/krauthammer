<?php

include "includes/variables.php";
include "libraries/plugins/format/formatting.php";
include __DIR__."/../../libraries/extra-functions/url-checker-2.php";

define('TERMS','cms_terms');
define('TAXONOMY','cms_term_taxonomy');
define('RELATION','cms_term_relationships');
define('CATEGORY','taxonomy="category"');
/*define('sort_index_for_gallery_image', 0);*/


function getModules(){
  $system_modules = array(
    "products" => "Products",
    "orders" => "Orders",
    "invoices" => "Invoices",
    "customers" => "Customers",
    "posts" => "Posts",
    "pages" => "Pages",
    "enquiries" => "Enquiries",
    "appearance" => "Appearance",
    "users" => "Users",
    "ecommerce" => "Ecommerce",
    "payment" => "Payment Gateway",
    "shipping" => "Shipping",
    "settings" => "General Settings",
    "super-admin" => "Super Admin",
    );

  return $system_modules;
}
function getCMSTypeModule(){
  $system_type_list = array(
    "CMS" => array('posts','pages','enquiries','appearance','users','settings','super-admin',),
    "ECOMMERCE" => array('products','orders','invoices','customers','posts','pages','enquiries','appearance','users','ecommerce','payment','shipping','settings','super-admin',), 
    "ECATALOG" => array('products','orders','invoices','customers','posts','pages','enquiries','appearance','users','ecommerce','settings','super-admin',)
    );

  return $system_type_list;
}

$current_system_module_title = "";
$current_system_module_sub_title = "";

function set_module_title($title = ""){
  global $current_system_module_title;
  $current_system_module_title = $title;
}
function set_module_sub_title($title = ""){
  global $current_system_module_sub_title;
  $current_system_module_sub_title = $title;
}
function get_module_title(){
  global $current_system_module_title;
  global $current_system_module_sub_title;
  return $current_system_module_title . ($current_system_module_sub_title != "" ? " - {$current_system_module_sub_title}" : "");
}

function isGet($value){
	if(isset($_GET[$value])){
		return true;
	}
	return false;
}

function get($value){
	return $_GET[$value];
}

function isPost($value){
	if(isset($_POST[$value])){
		return true;
	}
	return false;
}
function post($value){
	return $_POST[$value];
}

function emptyPost($value){
	return empty($_POST[$value]);
}

function emptyGet($value){
	return empty($_GET[$value]);
}

function hasPost($key,$value){
  return (isPost($key) && post($key)==$value) ? true: false;
}

function get_url($url,$table,$filter=""){
  $url = remove_accents( $url );
  $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $url);
  $clean = strtolower(trim($clean, '-'));
  $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

  $qry = query("SELECT * FROM `$table` WHERE `url_slug` = '$clean' ".$filter);
  $existing_slug_count = numRows($qry);
  $check = $existing_slug_count;
  $product_slug_increment = 0;

  while ($existing_slug_count > 0) {
    /*The url slug already exists. We have to incrementally test a series of slugs by appending a counter
    and check if the combination exists.*/
    $product_slug_increment++;
    $result = query("SELECT id FROM `$table` WHERE url_slug = '" . $clean . "-" . $product_slug_increment . "' ".$filter);
    $existing_slug_count = numRows($result);
  }

  /*If there are no existing slugs, use the original slug. If there are, then we use the new slug that we picked.*/
  /*$final_slug = $existing_slug_count > 0 ? $product_slug . "-" . $product_slug_increment : $product_slug;*/
  if($check > 0)
    $final_slug = $clean . "-" . $product_slug_increment;
  else
    $final_slug = $clean;

  return $final_slug;
}

function redirect($page){
  /*
  $location ="<script type='text/javascript'>";
  $location .="window.location.href='".$page."'";
  $location .="</script>";
  return print($location);
  */
  header("Location: ". $page);
}
function header_redirect($page = ""){
  header("Location: ". $page);
}
function get_site_url_info(){
  $site_url = get_system_option(array('option_name' => 'site_url'));
  if ($site_url == "") {
    $site_url = rtrim(FRONTEND_URL , "/");
  }
  return get_url_info( $site_url );
}
function get_url_info($url = ""){
  $site_url = $url;
  $_temp = parse_url( $site_url );
  $host = isset($_temp['host']) ? $_temp['host'] : "";
  $_host = explode(".", $host);
  $path = isset($_temp['path']) ? $_temp['path'] : "";

  $output = array(
    "siteurl" => $site_url,
    "protocol" => isset($_temp['scheme']) ? $_temp['scheme'] : "",
    "host" => $host,
    "path" => $path,
    "has_www" => strtolower($_host[0]) == "www" ? true : false,
    "has_slash" => substr($path, -1,1) == "/" ? true : false,
    );

  return $output;
}

function getJavascriptByUrl($url=""){
  $url=explode('/',$url);
  return $url[0].'.js';
}

function getController(){
  $url=explode('/',$_GET['url']);
  return $url[0];
}
function header_json(){
  header("Content-type: application/json");
}
function header_404(){
  header("HTTP/1.0 404 Not Found");
}
function cms_header($code = '200'){
  switch ($code) {
    case '400':
    header("HTTP/1.0 400 Bad Request"); break;
    case '401':
    header("HTTP/1.0 401 Unauthorized"); break;
    case '404':
    header("HTTP/1.0 404 Not Found"); break;
    case '204':
    header("HTTP/1.0 204 No Content"); break;
    default:
    header("HTTP/1.1 200 OK");
  }
}


function encryptPassword($value,$salt){

  return sha1($salt.$value.$salt);
}
function encrypt($value)
{
  return md5(md5($value));
}

function model($model){
 require ROOT.'models/'.$model.'_model.php';
 $modelClass  = ucfirst($model).'_Model';
 $modelName = new $modelClass();
 return $modelName;
}

/**
* Database 
**/
function db(){
  return mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
}

function query($sql){
	return mysqli_query(db(),$sql);
}	

function select($table,$column,$id){
  return query("SELECT $column FROM $table");
}

function fetch($qry, $type=""){
  if(!empty($type)){
   if($type=="assoc"){
    return mysqli_fetch_assoc($qry);
  }else if($type=="array"){
    return  mysqli_fetch_array($qry);
  }else if($type=="object"){
    return mysqli_fetch_object($qry);
  }
}else{
 return mysqli_fetch_row($qry);
}
}

function selectOne($table, $column, $id){
  $qry = $this->query("SELECT $column FROM $table WHERE $id");
  $row=$this->fetch($qry);
  return $row[0];
}

function numRows($qry){
	return mysqli_num_rows($qry);
}
function getUrlSlug($name, $table)
{

  $product_slug = toAscii($name);
  
  if($product_slug == 'process');
  $existing_slug_count = 1;

  $qry = query("SELECT id FROM `$table` WHERE url_slug = '$product_slug'");
  $existing_slug_count = mysqli_num_rows($qry);

  $product_slug_increment = 0;

  while($existing_slug_count > 0){
    //  The url slug already exists. We have to incrementally test a series of slugs by appending a counter
    //  and check if the combination exists.
    $product_slug_increment++;
    $result = query("SELECT id FROM `$table` WHERE url_slug = '".$product_slug."-".$product_slug_increment."'");
    $existing_slug_count = mysqli_num_rows($result);
    
  }

// If there are no existing slugs, use the original slug. If there are, then we use the new slug that we picked.
  $final_slug = $existing_slug_count > 0 ? $product_slug."-".$product_slug_increment : $product_slug;
  
  return $final_slug;
}
function toAscii($str) {
  $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
  $clean = strtolower(trim($clean, '-'));
  $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

  return $clean;
}

///////////////////////////////////////


/**
*Category 
**/

function printCategoryList($categories,$level=0) {
 if(!is_null($categories) && count($categories) > 0) {
  foreach($categories as $category) {
   echo '<option value="'.$category['name'].'">'.str_repeat('&nbsp;&nbsp;',$level).getCategoryName($category['name']).'</option>';
   if(hasParentCategory($category['name'])){
    printCategoryList($category['children'], $level+ 1);
  }       
}
}
}




function hasParentCategory($id){
 $qry = query("SELECT term_id FROM ".TAXONOMY." WHERE ".CATEGORY ." AND parent!='0' AND parent=".$id."");  
 $count = numRows($qry);
 if($count){
  return true; 
}
return false;
}

function isIdNotOne1($id){
 if($id!=1){
  echo "<a href='javascript:deleteCategory(".$id.")' class='btn btn-mini btn-danger'>Delete</a>";
}

}

function isIdNotOne2($id){
 if($id!=1){
  echo "<input type='checkbox' name='item[]' id='item_".$id."' value='".$id."' />";
}
}


function getCategoryName($id){
  $qry=query("SELECT t.name FROM ".TERMS." t INNER JOIN ".TAXONOMY." tt ON t.term_id=tt.term_id WHERE ".CATEGORY ." AND t.term_id ='{$id}'");
  $row=fetch($qry);
  return $row[0];
}

function getCategorySlug($id){
  $qry=query("SELECT slug FROM ".TERMS." WHERE term_id ='{$id}'");
  $row=fetch($qry);
  return $row[0];
}

function getCategoryParent($id){
  $qry=query("SELECT parent FROM ".TAXONOMY." WHERE term_id ='{$id}'");
  $row=fetch($qry);
  return $row[0];
}

function getCategoryDescription($id){
  $qry=query("SELECT description FROM ".TAXONOMY." WHERE term_id ='{$id}'");
  $row=fetch($qry);
  return $row[0];
}

function getCategoryCount($id){
  $qry=query("SELECT count FROM ".TAXONOMY." WHERE term_id ='{$id}'");
  $row=fetch($qry);
  return $row[0];
}


function printCategoryTable($categories,$level=0) {
  $count =count($categories);
  $null= is_null($categories);
  if(!$null && $count > 0) {
    foreach($categories as $category) {
      echo "<tr id='row".$category['name']."' onmouseover=hoverRow(".$category['name'].",'categories') onmouseout=outRow(".$category['name'].",'categories')  >";
      echo "<td class='rowTable'>";
       //isIdNotOne2($category['name']);
      if($category['name']!=1){
        echo "<input type='checkbox' name='item[]' id='item_".$category['name']."' value='".$category['name']."' />";
      }
      echo "</td>";
      echo "<td class='rowTable'><a href='javascript:updateCategory(".$category['name'].",".getCategoryParent($category['name']).")'  style='vertical-align:top;' ><input id='level".$category['name']."' type='hidden' value='".($level + 1)."' />". str_repeat('&ndash;&nbsp;',$level)."<span id='cName".$category['name']."'>". getCategoryName($category['name'])."</span></a><br />";
      echo "<div><div><div>";
      echo "<div class='row_actions_categories'>";
      echo "<a href='#editCategory' onclick='updateCategory(".$category['name'].",".getCategoryParent($category['name']).")' data-toggle='modal' class='btn btn-mini btn-primary'>Edit</a>";
      echo "&nbsp;";  
      //isIdNotOne1($category['name']);
      
      if($category['name']!=1){
        echo "<a href='javascript:deleteCategory(".$category['name'].")' class='btn btn-mini btn-danger'>Delete</a>";
      }
      echo "</div></div></div></div>
    </td>
    <td class='rowTable'><span id='cDescription".$category['name']."'>".getCategoryDescription($category['name'])."</span></td>
    <td class='rowTable'><span id='cSlug".$category['name']."'>".getCategorySlug($category['name'])."</span></td>
    <td class='rowTable text-center'><span id='cPostCount".$category['name']."'>".getCategoryCount($category['name'])."</span></td>
  </tr>";
  if(hasParentCategory($category['name'])){
    printCategoryTable($category['children'], $level + 1);
  }                              
}
}

}

//////////////////////////////// End Category


function isSelected($option, $value){
  if($option==$value){
    return print(' selected ');
  }
  return null;
}


/**
 * Pages
 */

function printPageList($pages,$level=0) {
 if(!is_null($pages) && count($pages) > 0) {
  foreach($pages as $page) {
   echo '<option value="'.$page['name'].'">'.str_repeat('&nbsp;&nbsp;',$level).getCategoryName($page['name']).'</option>';
   if(hasParentCategory($page['name'])){
    printPageList($page['children'], $level+ 1);
  }       
}
}
}

function hasParentPage($id){
  $qry = query("SELECT term_id FROM ".TAXONOMY." WHERE ".CATEGORY ." AND parent!='0' AND parent=".$id."");  
  $count = numRows($qry);
  if($count){
   return true; 
 }
 return false;
}

function getPageName($id){
  $qry=query("SELECT t.name, t.slug, tt.description, tt.count, tt.term_id, tt.parent FROM ".TERMS." t INNER JOIN ".TAXONOMY." tt ON t.term_id=tt.term_id WHERE ".CATEGORY ." AND t.term_id ='{$id}'");
  $row=fetch($qry);
  return $row[0];
}

function escape_string($data=""){
  $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if (gettype($data)=='array'){
    foreach(array_keys($data) as $key){
      $data[$key] = escape_string($data[$key]);
    }
    return $data;
  }
  else{
    return mysqli_real_escape_string($con, $data);
  }
}

// TABLE SERVER SIDE PROCESSOR SCRIPT

function datatable_processor($ac=array(), $sI="", $sT="", $_sql=""){
  // header("Content-type: application/json");
 /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
   * Easy set variables
   */

  /* Array of database columns which should be read and sent back to DataTables. Use a space where
   * you want to insert a non-database field (for example a counter or static image)
   */
  // $aColumns = array( 'christian_name', 'first_name', 'employee_name', 'ic_no', 'code', 'estate_id', 'join_date', 'resign_date', 'resigned', 'id', 'middle_name', 'last_name' );
  $aColumns = $ac;
  
  /* Indexed column (used for fast and accurate table cardinality) */
  $sIndexColumn = $sI;
  
  /* DB table to use */
  $sTable = $sT;
  
  /* Database connection information */
  $gaSql['user']       = DB_USER;
  $gaSql['password']   = DB_PASS;
  $gaSql['db']         = DB_NAME;
  $gaSql['server']     = DB_HOST;
  
  /* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
  // include( $_SERVER['DOCUMENT_ROOT']."/datatables/mysql.php" );
  
  
  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
   * If you just want to use the basic configuration for DataTables with PHP server-side, there is
   * no need to edit below this line
   */
  
  /* 
   * Local functions
   */

  function fatal_error ( $sErrorMessage = '' )
  {

    header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
    die( $sErrorMessage );
  }

  
  /* 
   * MySQL connection
   */
  if ( ! $gaSql['link'] = mysqli_connect( $gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db']  ) )
  {
    fatal_error( 'Could not open connection to server' );
  }

  // if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
  // {
  //     fatal_error( 'Could not select database ' );
  // }
  
  
  /* 
   * Paging
   */
  $sLimit = "";
  if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
  {
    $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
    intval( $_GET['iDisplayLength'] );
  }
  
  
  /*
   * Ordering
   */
  $sOrder = "";
  if ( isset( $_GET['iSortCol_0'] ) )
  {
    $sOrder = "ORDER BY  ";
    for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
    {
      if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
      {
        $sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
        ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
      }
    }

    $sOrder = substr_replace( $sOrder, "", -2 );
    if ( $sOrder == "ORDER BY" )
    {
      $sOrder = "";
    }
  }
  
  
  /* 
   * Filtering
   * NOTE this does not match the built-in DataTables filtering which does it
   * word by word on any field. It's possible to do here, but concerned about efficiency
   * on very large tables, and MySQL's regex functionality is very limited
   */
  $sWhere = "";
  if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
  {
    $sWhere = "WHERE (";
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
      $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch'] )."%' OR ";
    }
    $sWhere = substr_replace( $sWhere, "", -3 );
    $sWhere .= ')';
  }
  
  /* Individual column filtering */
  for ( $i=0 ; $i<count($aColumns) ; $i++ )
  {
    if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
    {
      if ( $sWhere == "" )
      {
        $sWhere = "WHERE ";
      }
      else
      {
        $sWhere .= " AND ";
      }
      $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_'.$i])."%' ";
    }
  }
  
  
  /*
   * SQL queries
   * Get data to display
   */
  $sql_table = $_sql;

  $sQuery = "
  SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
  FROM  ({$sql_table}) `t1`
  $sWhere
  $sOrder
  $sLimit
  ";
  $rResult = mysqli_query( $gaSql['link'], $sQuery ) or fatal_error( 'MySQL Error: ' . mysqli_errno($gaSql['link']) );
  
  /* Data set length after filtering */
  $sQuery = "
  SELECT FOUND_ROWS()
  ";
  $rResultFilterTotal = mysqli_query( $gaSql['link'], $sQuery ) or fatal_error( 'MySQL Error: ' . mysqli_errno($gaSql['link']) );
  $aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
  $iFilteredTotal = $aResultFilterTotal[0];
  
  /* Total data set length */
  $sQuery = "
  SELECT COUNT(`".$sIndexColumn."`)
  FROM   ({$sql_table}) `t1`
  ";
  $rResultTotal = mysqli_query( $gaSql['link'], $sQuery ) or fatal_error( 'MySQL Error: ' . mysqli_errno($gaSql['link']) );
  $aResultTotal = mysqli_fetch_array($rResultTotal);
  $iTotal = $aResultTotal[0];
  
  
  /*
   * Output
   */
  $output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
    );
  
  while ( $aRow = mysqli_fetch_array( $rResult ) )
  {
    $row = array();
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
      if ( $aColumns[$i] == "version" )
      {
        /* Special output formatting for 'version' column */
        $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
      }
      else if ( $aColumns[$i] != ' ' )
      {
        /* General output */
        $row[] = $aRow[ $aColumns[$i] ];
      }
    }

    $output['aaData'][] = $row;
  }
  
  return $output;
}

// function datatable_processor_2($pTable, $pId, $pColumns, $cmd, $where) {
//   // DB table to use
//   $table = $pTable;
//   // Table's primary key
//   $primaryKey = $pId;
//   // Array of database columns which should be read and sent back to DataTables.
//   // The `db` parameter represents the column name in the database, while the `dt`
//   // parameter represents the DataTables column identifier. In this case simple
//   // indexes
//   $columns = $pColumns;
//   // SQL server connection information
//   $sql_details = array(
//     'user' => DB_USER,
//     'pass' => DB_PASS,
//     'db'   => DB_NAME,
//     'host' => DB_HOST
//     );
//   require_once (ROOT.'assets/plugin/datatable/examples/server_side/scripts/ssp.class.php');
//   if ($cmd == 0)
//     echo json_encode( SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns ) );
//   else if ($cmd == 1)
//     echo json_encode( SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, $where ) );
// }

function datatable_processor_2($pTable, $pId, $pColumns, $cmd, $where, $qry="", $havingClauseCond = "") {
  // DB table to use
  $table = $pTable;
  // Table's primary key
  $primaryKey = $pId;
  // Array of database columns which should be read and sent back to DataTables.
  // The `db` parameter represents the column name in the database, while the `dt`
  // parameter represents the DataTables column identifier. In this case simple
  // indexes
  $columns = $pColumns;
  // SQL server connection information
  $sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASS,
    'db'   => DB_NAME,
    'host' => DB_HOST
    );
  require_once (ROOT.'assets/plugin/datatable/examples/server_side/scripts/ssp.class.php');

  if($qry == ""){
    if ($cmd == 0)
      echo json_encode( SSP::simple( $_REQUEST, $sql_details, $table, $primaryKey, $columns ) );
    else if ($cmd == 1)
      echo json_encode( SSP::complex( $_REQUEST, $sql_details, $table, $primaryKey, $columns, $where ) );
  }else{
    echo json_encode( SSP::customQry( $_REQUEST, $sql_details, $table, $primaryKey, $columns, $qry, $havingClauseCond ) );
  }
}

function table_button($option){
  $option['class'] = isset($option['class']) ? $option['class'] : "default";
  $option['tooltip'] = isset($option['tooltip']) ? $option['tooltip'] : "";
  $option['icon'] = isset($option['icon']) ? $option['icon'] : "file";
  $option['link'] = isset($option['link']) ? $option['link'] : "javascript:void(0)";
  $option['value'] = isset($option['value']) ? $option['value'] : "0";
  $option['target'] = isset($option['target']) ? $option['target'] : "_self";

  $button = '<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
  <a href="'. $option['link'] .'" class="btn '. $option['class'] .' btn-xs" data-rel="tooltip" data-original-title="'. $option['tooltip'] .'" title="'. $option['tooltip'] .'" data-value="'. $option['value'] .'" target="'.$option['target'].'"><i class="icon icon-'. $option['icon'] .'"></i></a>
</div>';

$attr = array();
$label = "";
foreach ($option as $key => $value) {
  if($key == 'label'){
    $label = $value;
  }else{
    $attr[] = $key . '="'.$value.'"';
  }
}

$attributes = implode(" ", $attr);
$btn = "<a {$attributes}>".$label."</a>";

return $btn;
}

#NEW ADDED FUNCTIONS
function seoUrl($string) {
    //Lower case everything
  $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
  $string = preg_replace("/[^a-z0-9.]+/i", " ", $string);
    //Clean up multiple dashes or whitespaces
  $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
  $string = preg_replace("/[\s_]/", "-", $string);
  return $string;
}

function check_system($value){
  $qry = query("SELECT * FROM `system_options` WHERE `option_name` = '$value'");

  return fetch($qry,'array');
}

function get_system_option($option = array()){
  if(isset($option['option_name']) || gettype($option) == 'string'){
    $option_name = isset($option['option_name']) ? $option['option_name'] : ( gettype($option) == 'string' ? $option : "" );

    $qry = query("SELECT * FROM `system_options` WHERE `option_name` = '$option_name' ");

    $row = fetch($qry, 'array');

    return $row['option_value'];

  }
}

function format_date($value){
  $rawdate = explode(' ', $value);
  $unformated_date = explode('-', $rawdate[0]);
  $format_date = $unformated_date[1]. '-' .$unformated_date[2].'-'.$unformated_date[0];
  return $format_date;
}

function sort_array($array,$sortBy,$direction)
{
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


/*function crop_image($data,$filename){

            $sizes = array(
                        '200x120',
                        '203x153',
                        '205x154',
                        '176x167',
                        '600x600',
                        '84x73',
                        '327x175',
                        '234x155',
                        '388x294',
                        '78x66'
            );

            $output = strpos($filename, 'images');
            $output =  substr($filename,$output-1);
            //$output = str_replace("/","\\",$output);

            $filename = FRONTEND_ROOT.$output;
            //echo FRONTEND_ROOT.$output;
            // Get new dimensions
            list($width, $height) = getimagesize($filename);
            $src_x = $data['x'];
            $src_y = $data['y'];

            //$new_width = $data['width'];
            //$new_height = $data['height'];
            print_r($data);
            foreach ($sizes as $key => $size) {
              $s = explode('x', $size);
              $new_width = $s[0];
              $new_height = $s[1];
            // Resample
              $image_p = imagecreatetruecolor($new_width, $new_height);
              $image = imagecreatefromjpeg($filename);
              
              $ext = pathinfo($filename, PATHINFO_EXTENSION);
              //$thumb = imagecreatetruecolor($new_width,$new_height);
              //imagealphablending($thumb, false);
              //imagecopyresized($thumb,$image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
              imagecopyresampled($image_p, $image, 0, 0, $src_x, $src_y, $new_width, $new_height, $width, $height);
              //imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
             // $t = imagecopy($image_p, $image, 0, 0, $src_x, $src_y, $width, $height);
              if(!is_dir(FRONTEND_ROOT.'/sample/'.$size.'/'))
                mkdir(FRONTEND_ROOT.'/sample/'.$size.'/', 0755, TRUE);
              // Output
              if($ext === 'jpg')
                $t = imagejpeg($image_p,FRONTEND_ROOT.'/sample/'.$size.'/test.jpg', 90);
              else if($ext === 'png')
                $t = imagepng($image_p,FRONTEND_ROOT.'/sample/'.$size.'/test.png', 90);
              else if($ext === 'gif')
                $t = imagegif($image_p,FRONTEND_ROOT.'/sample/'.$size.'/test.gif', 90);

            }

            if($t)
              return true;

            return false;
            
          }*/

          function crop_image($data,$src){

            $msg = '';
            $aw = '';
            $sizes = array(
              '200x120',
              '203x153',
              '205x154',
              '176x167',
              '600x600',
              '84x73',
              '327x175',
              '234x155',
              '388x294',
              '78x66',
              '143x89',
              '388x294',
              '660x356'

              );
 /*   $sizes = array(
                        '660x356',
                         '143x89',
                         '388x294'
                         );*/
                         if (!empty($src) && !empty($data)) {
                          $ext = pathinfo($src, PATHINFO_EXTENSION);
                          switch ($ext) {
                            case 'gif':
                            $src_img = imagecreatefromgif($src);
                            break;
                            case 'jpg':
                            $src_img = imagecreatefromjpeg($src);
                            break;
                            case 'png':
                            $src_img = imagecreatefrompng($src);
                            break;
                          }
                          if (!$src_img) {
                            $msg = "Failed to read the image file";
                          }
                          $size = getimagesize($src);
      $size_w = $size[0]; // natural width
      $size_h = $size[1]; // natural height
      $src_img_w = $size_w;
      $src_img_h = $size_h;
      $degrees = $data['rotate'];
      // Rotate the source image
      if (is_numeric($degrees) && $degrees != 0) {
        // PHP's degrees is opposite to CSS's degrees
        $new_img = imagerotate( $src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );
        imagedestroy($src_img);
        $src_img = $new_img;
        $deg = abs($degrees) % 180;
        $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
        $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
        $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
        // Fix rotated image miss 1px issue when degrees < 0
        $src_img_w -= 1;
        $src_img_h -= 1;
      }
      $tmp_img_w = $data['width'];
      $tmp_img_h = $data['height'];

      $src_x = $data['x'];
      $src_y = $data['y'];
     /* if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
        $src_x = $src_w = $dst_x = $dst_w = 0;
      } else if ($src_x <= 0) {
        $dst_x = -$src_x;
        $src_x = 0;
        $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
      } else if ($src_x <= $src_img_w) {
        $dst_x = 0;
        $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
      }
      if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
        $src_y = $src_h = $dst_y = $dst_h = 0;
      } else if ($src_y <= 0) {
        $dst_y = -$src_y;
        $src_y = 0;
        $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
      } else if ($src_y <= $src_img_h) {
        $dst_y = 0;
        $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
      }*/
      // Scale to destination position and size
      foreach ($sizes as $key => $xs) {
        $s = explode('x', $xs);
        $dst_img_w = $s[0];
        $dst_img_h = $s[1];
        /*$ratio = $tmp_img_w / $dst_img_w;
        $dst_x /= $ratio;
        $dst_y /= $ratio;
        $dst_w /= $ratio;
        $dst_h /= $ratio;*/
        $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
        // Add transparent background to destination image
        imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));

        imagesavealpha($dst_img, true);

        $result = imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $dst_img_w, $dst_img_h, $tmp_img_w, $tmp_img_h);
       // $name = explode('/', $src);
        //$t = get_path($src);
        $u_path = FRONTEND_ROOT."/thumbnails/".$xs.get_path($src);
        //$u_path = 'sample/'.$xs.'/';
        if(!is_dir($u_path)){

          if(!mkdir($u_path, 0755, TRUE))
            $msg = 'unable to make folders';
        }
        $dst = $u_path.get_filename($src);
        //$dst = FRONTEND_ROOT."/thumbnails/".$xs."/uploads/".date('Y')."/".date('m')."/".date('d')."/".end($name)."/".end($name);
        //$dst = $u_path.'/test.jpg';
        if ($result) {
          if (!imagejpeg($dst_img, $dst)) {
            $msg = "Failed to save the cropped image file";
          }
        } else {
          $msg = "Failed to crop the image file";
        }
        imagedestroy($dst_img);
      }
      imagedestroy($src_img);
      
    }

    return $msg;
  }
  function get_path($src){

    $raw = explode('/', $src);
    $count = 0;
    $limit = count($raw) - 1;
    $string = '';
    foreach ($raw as $key => $r) {
      if($r == 'images')
        $count = $key;
    }

    foreach ($raw as $key => $i) {
      if($key > $count && $key < $limit)
        $string .= '/'.$i;
    }

    return $string.'/';


  }
  function get_filename($src){
    $raw = explode('/', $src);

    return end($raw);
  }
  function get_date($date){
   // $rw_date = str_replace('-', '/', $date);
    $d = explode('/', $date);
    return date('Y-m-d', strtotime($d[1].'/'.$d[0].'/'.$d[2]));
   // return $d[0];
  }
  function get_modules_for_admin(){
   $qry = query("SELECT * FROM `modules` WHERE `super_admin_only` = 'N' ORDER BY `sort_index`");
   $rows = array();


   while($row = fetch($qry, 'array'))
    $rows[] = $row;

  return $rows;
}
function get_modules_for_super_admin(){
 $qry = query("SELECT * FROM `modules`  ORDER BY `sort_index`");
 $rows = array();


 while($row = fetch($qry, 'array'))
  $rows[] = $row;

return $rows;
}

function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function add_gallery_images($product_id, $file_name){
  if($file_name != '')
    $qry = query("INSERT INTO `products_gallery_images`(`product_id`, `image_url`, `sort_order`) VALUES ('$product_id','$file_name', '0')");
}

/* add_url function's primary objective is to add new redirect entry into the databae (datatable: urls) */
function add_url($link, $url_rel_id, $table){
  $d_qry = query("SELECT `id` FROM `urls` WHERE `id` = '{$url_rel_id}' AND `table` = '{$table}' AND `status` = 'active' ");
  $row = fetch($d_qry, 'array');
  $id = $row['id'];
  query("UPDATE `urls` SET `status` = 'trashed' WHERE `id` = '$id'");
  $qry = query("INSERT INTO `urls`( `url_id`, `url`, `table`) VALUES ('$url_rel_id','$link','$table')");
}
/* repalce_url is related to add_url function */
function replace_url($old_link, $new_link, $type){
  $old_link = explode('?', $old_link)[0];
  $new_link = explode('?', $new_link)[0];

  $url_query = "SELECT `id` FROM `urls` WHERE url = '". trim($old_link) ."' or url = '" . trim($old_link,'/'). "/' AND `status` = 'active' Order By id Desc Limit 1 ";
  $d_qry = query( $url_query );

  $prev_url_info = fetch($d_qry, 'array');

  $id = isset($prev_url_info['id']) ? $prev_url_info['id'] : 0;
  query("UPDATE `urls` SET `status` = 'trashed' WHERE `id` = '{$id}'");
  $qry = query("INSERT INTO `urls`( `url_id`, `url`, `table`) VALUES ('{$id}','{$new_link}','{$type}')");
}


function field_builder($field_info = array()){
  $field_html = "";
  $temp_html = '';
  $disbale = isset($field_info['disable']) && $field_info['disable'] == true ? 'disabled' : '';
  $readonly = isset($field_info['readonly']) && $field_info['readonly'] == true ? 'readonly' : '';

  if ( $field_info['type'] == 'text' ) {
    $temp_html = '<input type="text" value="'. $field_info['value'] .'" id="'. $field_info['id'] .'" name="'. $field_info['name'] .'" class="'. $field_info['class'] .'" placeholder="'. $field_info['placeholder'] .'" '. $disbale .' '. $readonly .' >';
  }else if( $field_info['type'] == 'select' ){
    $temp_html = '<select id="'. $field_info['id'] .'" class="'. $field_info['class'] .'" name="'. $field_info['name'] .'" '. $disbale .' '. $readonly .'>';
    foreach ($field_info['value'] as $key => $value) {
      $selected = $field_info['selected'] == $key ? 'selected = "selected"' : "";
      $temp_html .= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
    }
    $temp_html .= '</select>';
  }else if( $field_info['type'] == 'datepicker' ){
    $temp_html = '<input type="text" value="'. $field_info['value'] .'" id="'. $field_info['id'] .'" name="'. $field_info['name'] .'" class="'. $field_info['class'] .'" placeholder="'. $field_info['placeholder'] .'" '. $disbale .' '. $readonly .' >';
    $temp_html = '<div class="row-fluid input-append">
    '. $temp_html .'
    <span class="add-on">
      <i class="icon-calendar"></i>
    </span>
  </div>';
}else if( $field_info['type'] == 'timepicker' ){
  $temp_html = '<input type="text" value="'. $field_info['value'] .'" id="'. $field_info['id'] .'" name="'. $field_info['name'] .'" class="'. $field_info['class'] .'" placeholder="'. $field_info['placeholder'] .'" '. $disbale .' '. $readonly .' >';
  $temp_html = '<div class="input-append bootstrap-timepicker">
  '. $temp_html .'
  <span class="add-on">
    <i class="icon-time"></i>
  </span>
</div>';
}else if( $field_info['type'] == 'link' ){
  $href = isset($field_info['value']) ? $field_info['value'] : '';
  $link_label = isset($field_info['link_label']) && $field_info['link_label'] != "" ? $field_info['link_label'] : $href;
  $c = "other-field-link " . ($field_info['class']!="" ? $field_info['class'] : "");
  $n = $field_info['name']!="" ? 'name="'. $field_info['name'] .'"' : "";
  $temp_html = '<input type="text" value="'. $field_info['value'] .'" id="'. $field_info['id'] .'" name="'. $field_info['name'] .'" class="'. $field_info['class'] .'" placeholder="'. $field_info['placeholder'] .'" '. $disbale .' '. $readonly .' >';
  $temp_html = '<a href="'. $href .'" id="'. $field_info['id'] .'" class="'.$c.'" '.$n.'  >'. $link_label .'</a>';
}

$field_html = '<div class="control-group">
<label class="control-label" for="#'. $field_info['id'] .'">'. $field_info['label'] .':</label>
<div class="controls">'. $temp_html .'</div>
</div>';

return $field_html;
}

function get_global_variable( $variable_name = '' ){
  if ($variable_name == '') { return null; }
  global $$variable_name;
  if (isset($$variable_name)) {
    return $$variable_name;
  }
  return null;
}
function get_reserved_language(){
  $db = new Database();
  return $db->select("Select * From `cms_items` Where `type` = 'cms-language-default' Union ( Select '0' `id`, '0' `guid`, 'cms-language' `type`, 'English' `value`, 'en' `meta`, 'active' `status`, NOW() `date_added`) Order By `id` desc Limit 1")[0]->meta;
}
function get_default_language(){
  $db = new Database();
  $default_lang = $db->select("Select * From cms_items Where (type = 'cms-language' or type = 'cms-language-default') and guid = '1'");
  $default_lang = isset($default_lang[0]) ? $default_lang[0]->meta : 'en';

  return $default_lang;
}
function print_n($v){
  print_r($v);
  print_r("<br>");
}

/* Gobal Validation Functions */
function is_valid_image_type($file = null, $allowed_types = array()){
  if($file != null){
    $is_valid_file = true;

    $allowed_types = array(
      'image/jpeg', 'image/jpg', 'image/png', 'image/icon', 'image/gif', 
    );
    $allowed =  array('gif','png' ,'jpg');

    if(isset($file['type']) && $is_valid_file){
      $mine = $file['type'];
      $is_valid_file = in_array($mine,$allowed_types);
    }

    if(function_exists("pathinfo") && $is_valid_file){
      $fname = $file['name'];
      $ext = pathinfo($fname, PATHINFO_EXTENSION);
      $is_valid_file = in_array($ext,$allowed);
    }

    if(isset($file['tmp_name'])){
      $file = $file['tmp_name'];
    }
    if(function_exists('finfo_file') && $is_valid_file){
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $type = finfo_file($finfo, $file );
      finfo_close($finfo);
      $is_valid_file = in_array($type, $allowed_types);
    }

    if(function_exists('exif_imagetype') && $is_valid_file){
      $a_temp = array();
      if(defined('IMAGETYPE_GIF')) $a_temp[] = IMAGETYPE_GIF;
      if(defined('IMAGETYPE_JPEG')) $a_temp[] = IMAGETYPE_JPEG;
      if(defined('IMAGETYPE_PNG')) $a_temp[] = IMAGETYPE_PNG;
      if(defined('IMAGETYPE_SWF')) $a_temp[] = IMAGETYPE_SWF;
      if(defined('IMAGETYPE_PSD')) $a_temp[] = IMAGETYPE_PSD;
      if(defined('IMAGETYPE_BMP')) $a_temp[] = IMAGETYPE_BMP;
      if(defined('IMAGETYPE_TIFF_II')) $a_temp[] = IMAGETYPE_TIFF_II;
      if(defined('IMAGETYPE_TIFF_MM')) $a_temp[] = IMAGETYPE_TIFF_MM;
      if(defined('IMAGETYPE_JPC')) $a_temp[] = IMAGETYPE_JPC;
      if(defined('IMAGETYPE_JP2')) $a_temp[] = IMAGETYPE_JP2;
      if(defined('IMAGETYPE_JPX')) $a_temp[] = IMAGETYPE_JPX;
      if(defined('IMAGETYPE_JB2')) $a_temp[] = IMAGETYPE_JB2;
      if(defined('IMAGETYPE_SWC')) $a_temp[] = IMAGETYPE_SWC;
      if(defined('IMAGETYPE_IFF')) $a_temp[] = IMAGETYPE_IFF;
      if(defined('IMAGETYPE_WBMP')) $a_temp[] = IMAGETYPE_WBMP;
      if(defined('IMAGETYPE_XBM')) $a_temp[] = IMAGETYPE_XBM;
      if(defined('IMAGETYPE_ICO')) $a_temp[] = IMAGETYPE_ICO;
      if(defined('IMAGETYPE_WEBP')) $a_temp[] = IMAGETYPE_WEBP;
      
      $allowedTypes = count($allowed_types) > 0 ? $allowed_types : $a_temp;
      $detectedType = @exif_imagetype($file);
      $is_valid_file = in_array($detectedType, $a_temp);
    }
    
    return $is_valid_file;
  }else{
    return false;
  }
}
function is_valid_audio_type($file = null, $allowed_types = array()){
  $allowed_types = array(
    'audio/mpeg', 'audio/x-mpeg', 'audio/mpeg3', 'audio/x-mpeg-3', 'audio/aiff', 
    'audio/mid', 'audio/x-aiff', 'audio/x-mpequrl','audio/midi', 'audio/x-mid', 
    'audio/x-midi','audio/wav','audio/x-wav','audio/xm','audio/x-aac','audio/basic',
    'audio/flac','audio/mp4','audio/x-matroska','audio/ogg','audio/s3m','audio/x-ms-wax',
    'audio/xm', 'audio/mp3',
  );
  $allowed =  array('mpeg', 'mpeg3', 'wav', 'mp4', 'mp3', 'ogg');
  $is_valid_file = true;

  if(isset($file['type']) && $is_valid_file){
    $mine = $file['type'];
    $is_valid_file = in_array($mine,$allowed_types);
  }
  if(function_exists("pathinfo") && $is_valid_file){
    $fname = $file['name'];
    $ext = pathinfo($fname, PATHINFO_EXTENSION);
    $is_valid_file = in_array($ext,$allowed);
  }

  if(isset($file['tmp_name'])){
    $file = $file['tmp_name'];
  }
  if(function_exists('finfo_open') && $is_valid_file){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $type = finfo_file($finfo, $file );
    finfo_close($finfo);
    $is_valid_file = in_array($type, $allowed_types);
  }

  return $is_valid_file;
}
function is_valid_audio_video($file = null, $allowed_types = array()){
  $allowed_types = array(
    'video/mp4', 'video/avi', 'video/flv', 'video/wmv', 'video/mov', 
  );
  $allowed =  array('mp4', 'avi', 'flv', 'wmv', 'mov');
  $is_valid_file = true;

  if(isset($file['type']) && $is_valid_file){
    $mine = $file['type'];
    $is_valid_file = in_array($mine,$allowed_types);
  }
  if(function_exists("pathinfo") && $is_valid_file){
    $fname = $file['name'];
    $ext = pathinfo($fname, PATHINFO_EXTENSION);
    $is_valid_file = in_array($ext,$allowed);
  }

  if(isset($file['tmp_name'])){
    $file = $file['tmp_name'];
  }
  if(function_exists('finfo_open') && $is_valid_file){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $type = finfo_file($finfo, $file );
    finfo_close($finfo);
    $is_valid_file = in_array($type, $allowed_types);
  }

  return $is_valid_file;
}
function is_valid_docs($file = null, $allowed_types = array()){
  $allowed_types = array(
    'text/calendar',
    'text/css',
    'text/csv',
    'text/html',
    'text/javascript',
    'text/plain',
    'text/xml ',
    'application/epub+zip',
    'application/msword',
    'application/ogg',
    'application/pdf',
    'application/rtf',
    'application/vnd.amazon.ebook',
    'application/vnd.ms-excel',
    'application/vnd.ms-powerpoint',
    'application/vnd.oasis.opendocument.presentation',
    'application/vnd.oasis.opendocument.spreadsheet',
    'application/vnd.oasis.opendocument.text',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/x-abiword',
    'application/x-freearc',
  );
  $allowed =  array('mp4', 'avi', 'flv', 'wmv', 'mov');
  $is_valid_file = true;

  if(isset($file['type']) && $is_valid_file){
    $mine = $file['type'];
    $is_valid_file = in_array($mine,$allowed_types);
  }
  // if(function_exists("pathinfo") && $is_valid_file){
  //   $fname = $file['name'];
  //   $ext = pathinfo($fname, PATHINFO_EXTENSION);
  //   $is_valid_file = in_array($ext,$allowed);
  // }

  // if(isset($file['tmp_name'])){
  //   $file = $file['tmp_name'];
  // }
  // if(function_exists('finfo_open') && $is_valid_file){
  //   $finfo = finfo_open(FILEINFO_MIME_TYPE);
  //   $type = finfo_file($finfo, $file );
  //   finfo_close($finfo);
  //   $is_valid_file = in_array($type, $allowed_types);
  // }

  return $is_valid_file;
}
function mime_dictionary($mime = ""){
  $list = array(
    "audio/aac" => array(
      "extn"=>".aac",
      "desc"=>"AAC audio",
      "mime"=>"audio/aac"
    ),
    "application/x-abiword" => array(
      "extn"=>".abw",
      "desc"=>"AbiWord document",
      "mime"=>"application/x-abiword"
    ),
    "application/x-freearc" => array(
      "extn"=>".arc",
      "desc"=>"Archive document (multiple files embedded)",
      "mime"=>"application/x-freearc"
    ),
    "video/x-msvideo" => array(
      "extn"=>".avi",
      "desc"=>"AVI: Audio Video Interleave",
      "mime"=>"video/x-msvideo"
    ),
    "application/vnd.amazon.ebook" => array(
      "extn"=>".azw",
      "desc"=>"Amazon Kindle eBook format",
      "mime"=>"application/vnd.amazon.ebook"
    ),
    "application/octet-stream" => array(
      "extn"=>".bin",
      "desc"=>"Any kind of binary data",
      "mime"=>"application/octet-stream"
    ),
    "image/bmp" => array(
      "extn"=>".bmp",
      "desc"=>"Windows OS/2 Bitmap Graphics",
      "mime"=>"image/bmp"
    ),
    "application/x-bzip" => array(
      "extn"=>".bz",
      "desc"=>"BZip archive",
      "mime"=>"application/x-bzip"
    ),
    "application/x-bzip2" => array(
      "extn"=>".bz2",
      "desc"=>"BZip2 archive",
      "mime"=>"application/x-bzip2"
    ),
    "application/x-csh" => array(
      "extn"=>".csh",
      "desc"=>"C-Shell script",
      "mime"=>"application/x-csh"
    ),
    "text/css" => array(
      "extn"=>".css",
      "desc"=>"Cascading Style Sheets (CSS)",
      "mime"=>"text/css"
    ),
    "text/csv" => array(
      "extn"=>".csv",
      "desc"=>"Comma-separated values (CSV)",
      "mime"=>"text/csv"
    ),
    "application/msword" => array(
      "extn"=>".doc",
      "desc"=>"Microsoft Word",
      "mime"=>"application/msword"
    ),
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => array(
      "extn"=>".docx",
      "desc"=>"Microsoft Word (OpenXML)",
      "mime"=>"application/vnd.openxmlformats-officedocument.wordprocessingml.document"
    ),
    "application/vnd.ms-fontobject" => array(
      "extn"=>".eot",
      "desc"=>"MS Embedded OpenType fonts",
      "mime"=>"application/vnd.ms-fontobject"
    ),
    "application/epub+zip" => array(
      "extn"=>".epub",
      "desc"=>"Electronic publication (EPUB)",
      "mime"=>"application/epub+zip"
    ),
    "image/gif" => array(
      "extn"=>".gif",
      "desc"=>"Graphics Interchange Format (GIF)",
      "mime"=>"image/gif"
    ),
    "text/html" => array(
      "extn"=>".htm/.html",
      "desc"=>"HyperText Markup Language (HTML)",
      "mime"=>"text/html"
    ),
    "image/vnd.microsoft.icon" => array(
      "extn"=>".ico",
      "desc"=>"Icon format",
      "mime"=>"image/vnd.microsoft.icon"
    ),
    "text/calendar" => array(
      "extn"=>".ics",
      "desc"=>"iCalendar format",
      "mime"=>"text/calendar"
    ),
    "application/java-archive" => array(
      "extn"=>".jar",
      "desc"=>"Java Archive (JAR)",
      "mime"=>"application/java-archive"
    ),
    "image/jpeg" => array(
      "extn"=>".jpeg/.jpg",
      "desc"=>"JPEG images",
      "mime"=>"image/jpeg"
    ),
    "text/javascript" => array(
      "extn"=>".js",
      "desc"=>"JavaScript",
      "mime"=>"text/javascript"
    ),
    "application/json" => array(
      "extn"=>".json",
      "desc"=>"JSON format",
      "mime"=>"application/json"
    ),
    "audio/midi audio/x-midi" => array(
      "extn"=>".mid/.midi",
      "desc"=>"Musical Instrument Digital Interface (MIDI)",
      "mime"=>"audio/midi audio/x-midi"
    ),
    "application/javascript" => array(
      "extn"=>".mjs",
      "desc"=>"JavaScript module",
      "mime"=>"application/javascript"
    ),
    "audio/mpeg" => array(
      "extn"=>".mp3",
      "desc"=>"MP3 audio",
      "mime"=>"audio/mpeg"
    ),
    "audio/mp3" => array(
      "extn"=>".mp3",
      "desc"=>"MP3 audio",
      "mime"=>"audio/mp3"
    ),
    "video/mpeg" => array(
      "extn"=>".mpeg",
      "desc"=>"MPEG Video",
      "mime"=>"video/mpeg"
    ),
    "application/vnd.apple.installer+xml" => array(
      "extn"=>".mpkg",
      "desc"=>"Apple Installer Package",
      "mime"=>"application/vnd.apple.installer+xml"
    ),
    "application/vnd.oasis.opendocument.presentation" => array(
      "extn"=>".odp",
      "desc"=>"OpenDocument presentation document",
      "mime"=>"application/vnd.oasis.opendocument.presentation"
    ),
    "application/vnd.oasis.opendocument.spreadsheet" => array(
      "extn"=>".ods",
      "desc"=>"OpenDocument spreadsheet document",
      "mime"=>"application/vnd.oasis.opendocument.spreadsheet"
    ),
    "application/vnd.oasis.opendocument.text" => array(
      "extn"=>".odt",
      "desc"=>"OpenDocument text document",
      "mime"=>"application/vnd.oasis.opendocument.text"
    ),
    "audio/ogg" => array(
      "extn"=>".oga",
      "desc"=>"OGG audio",
      "mime"=>"audio/ogg"
    ),
    "video/ogg" => array(
      "extn"=>".ogv",
      "desc"=>"OGG video",
      "mime"=>"video/ogg"
    ),
    "application/ogg" => array(
      "extn"=>".ogx",
      "desc"=>"OGG",
      "mime"=>"application/ogg"
    ),
    "font/otf" => array(
      "extn"=>".otf",
      "desc"=>"OpenType font",
      "mime"=>"font/otf"
    ),
    "image/png" => array(
      "extn"=>".png",
      "desc"=>"Portable Network Graphics",
      "mime"=>"image/png"
    ),
    "application/pdf" => array(
      "extn"=>".pdf",
      "desc"=>"Adobe Portable Document Format (PDF)",
      "mime"=>"application/pdf"
    ),
    "application/vnd.ms-powerpoint" => array(
      "extn"=>".ppt",
      "desc"=>"Microsoft PowerPoint",
      "mime"=>"application/vnd.ms-powerpoint"
    ),
    "application/vnd.openxmlformats-officedocument.presentationml.presentation" => array(
      "extn"=>".pptx",
      "desc"=>"Microsoft PowerPoint (OpenXML)",
      "mime"=>"application/vnd.openxmlformats-officedocument.presentationml.presentation"
    ),
    "application/x-rar-compressed" => array(
      "extn"=>".rar",
      "desc"=>"RAR archive",
      "mime"=>"application/x-rar-compressed"
    ),
    "application/rtf" => array(
      "extn"=>".rtf",
      "desc"=>"Rich Text Format (RTF)",
      "mime"=>"application/rtf"
    ),
    "application/x-sh" => array(
      "extn"=>".sh",
      "desc"=>"Bourne shell script",
      "mime"=>"application/x-sh"
    ),
    "image/svg+xml" => array(
      "extn"=>".svg",
      "desc"=>"Scalable Vector Graphics (SVG)",
      "mime"=>"image/svg+xml"
    ),
    "application/x-shockwave-flash" => array(
      "extn"=>".swf",
      "desc"=>"Small web format (SWF) or Adobe Flash document",
      "mime"=>"application/x-shockwave-flash"
    ),
    "application/x-tar" => array(
      "extn"=>".tar",
      "desc"=>"Tape Archive (TAR)",
      "mime"=>"application/x-tar"
    ),
    "image/tiff" => array(
      "extn"=>".tif/.tiff",
      "desc"=>"Tagged Image File Format (TIFF)",
      "mime"=>"image/tiff"
    ),
    "font/ttf" => array(
      "extn"=>".ttf",
      "desc"=>"TrueType Font",
      "mime"=>"font/ttf"
    ),
    "text/plain" => array(
      "extn"=>".txt",
      "desc"=>"Text, (generally ASCII or ISO 8859-n)",
      "mime"=>"text/plain"
    ),
    "application/vnd.visio" => array(
      "extn"=>".vsd",
      "desc"=>"Microsoft Visio",
      "mime"=>"application/vnd.visio"
    ),
    "audio/wav" => array(
      "extn"=>".wav",
      "desc"=>"Waveform Audio Format",
      "mime"=>"audio/wav"
    ),
    "audio/webm" => array(
      "extn"=>".weba",
      "desc"=>"WEBM audio",
      "mime"=>"audio/webm"
    ),
    "video/webm" => array(
      "extn"=>".webm",
      "desc"=>"WEBM video",
      "mime"=>"video/webm"
    ),
    "image/webp" => array(
      "extn"=>".webp",
      "desc"=>"WEBP image",
      "mime"=>"image/webp"
    ),
    "font/woff" => array(
      "extn"=>".woff",
      "desc"=>"Web Open Font Format (WOFF)",
      "mime"=>"font/woff"
    ),
    "font/woff2" => array(
      "extn"=>".woff2",
      "desc"=>"Web Open Font Format (WOFF)",
      "mime"=>"font/woff2"
    ),
    "application/xhtml+xml" => array(
      "extn"=>".xhtml",
      "desc"=>"XHTML",
      "mime"=>"application/xhtml+xml"
    ),
    "application/vnd.ms-excel" => array(
      "extn"=>".xls",
      "desc"=>"Microsoft Excel",
      "mime"=>"application/vnd.ms-excel"
    ),
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => array(
      "extn"=>".xlsx",
      "desc"=>"Microsoft Excel (OpenXML)",
      "mime"=>"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
    ),
    "application/xml" => array(
      "extn"=>".xml",
      "desc"=>"XML",
      "mime"=>"application/xml"
    ),
    "text/xml" => array(
      "extn"=>".xml",
      "desc"=>"XML",
      "mime"=>"text/xml"
    ),
    "application/vnd.mozilla.xul+xml" => array(
      "extn"=>".xul",
      "desc"=>"XUL",
      "mime"=>"application/vnd.mozilla.xul+xml"
    ),
    "application/zip" => array(
      "extn"=>".zip",
      "desc"=>"ZIP archive",
      "mime"=>"application/zip"
    ),
    "video/3gpp" => array(
      "extn"=>".3gp",
      "desc"=>"3GPP audio/video container",
      "mime"=>"video/3gpp"
    ),
    "audio/3gpp" => array(
      "extn"=>".3gp",
      "desc"=>"3GPP audio/video container",
      "mime"=>"audio/3gpp"
    ),
    "video/3gpp2" => array(
      "extn"=>".3g2",
      "desc"=>"3GPP2 audio/video container",
      "mime"=>"video/3gpp2"
    ),
    "audio/3gpp2" => array(
      "extn"=>".3g2",
      "desc"=>"3GPP2 audio/video container",
      "mime"=>"audio/3gpp2"
    ),
    "application/x-7z-compressed" => array(
      "extn"=>".7z",
      "desc"=>"7-zip archive",
      "mime"=>"application/x-7z-compressed"
    ),
  );
  
  if(isset($list[$mime])){
    return $list[$mime];
  }else{
    return false;
  }
}