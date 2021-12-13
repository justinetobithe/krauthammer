<?php
$db = Database::getInstance();
define('TERMS', 'cms_terms');
define('TAXONOMY', 'cms_term_taxonomy');
define('RELATION', 'cms_term_relationships');
define('CATEGORY', 'taxonomy="category"');


function isGet($value) {
	if (isset($_GET[$value])) {
		return true;
	}
	return false;
}

function get($value) {
	return $_GET[$value];
}

function isPost($value) {
	if (isset($_POST[$value])) {
		return true;
	}
	return false;
}

function post($value) {
	return $_POST[$value];
}

function emptyPost($value) {
	return empty($_POST[$value]);
}

function emptyGet($value) {
	return empty($_GET[$value]);
}

function hasPost($key, $value) {
	return (isPost($key) && post($key) == $value) ? true : false;
}

function redirect($page) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $page);
	exit();
}

function header_redirect($page = "") {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $page);
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
		"query" => isset($_temp['query']) ? $_temp['query'] : "",
		"has_www" => strtolower($_host[0]) == "www" ? true : false,
		"has_slash" => substr($path, -1,1) == "/" ? true : false,
		);

	return $output;
}

function getJavascriptByUrl() {
	$url = explode('/', $_GET['url']);
	return $url[0] . '.js';
}

function getController() {
	$url = explode('/', $_GET['url']);
	return $url[0];
}

function header_json() {
	header("Content-type: application/json");
}
function header_404(){
	header("HTTP/1.0 404 Not Found");
}
function x_robots_tag(){
	header('X-Robots-Tag: noindex, nofollow');
}

function encryptPassword($value, $salt) {

	return sha1($salt . $value . $salt);
}

function encrypt($value) {
	return md5(md5($value));
}

function model($model) {
	require ROOT . 'models/' . $model . '_model.php';
	$modelClass = ucfirst($model) . '_Model';
	$modelName = new $modelClass();
	return $modelName;
}

/**
 * Database 
 * */
function db() {
	return mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
}

function query($sql) {
	return mysqli_query(db(), $sql);
}

function select($table, $column, $id) {
	return query("SELECT $column FROM $table");
}

function fetch($qry, $type = "") {
	if (!empty($type)) {
		if ($type == "assoc") {
			return mysqli_fetch_assoc($qry);
		} else if ($type == "array") {
			return mysqli_fetch_array($qry);
		} else if ($type == "object") {
			return mysqli_fetch_object($qry);
		}
	} else {
		return mysqli_fetch_row($qry);
	}
}

function selectOne($table, $column, $id) {
	$qry = $this->query("SELECT $column FROM $table WHERE $id");
	$row = $this->fetch($qry);
	return $row[0];
}

function numRows($qry) {
	return mysqli_num_rows($qry);
}

function get_url_slug($name, $table) {
	$product_slug = toAscii($name);

	$qry = query("SELECT id FROM `$table` WHERE url_slug = '$product_slug'");
	$existing_slug_count = mysqli_num_rows($qry);
	$check = $existing_slug_count;
	$product_slug_increment = 0;

	while ($existing_slug_count > 0) {
        //  The url slug already exists. We have to incrementally test a series of slugs by appending a counter
        //  and check if the combination exists.
		$product_slug_increment++;
		$result = query("SELECT id FROM `$table` WHERE url_slug = '" . $product_slug . "-" . $product_slug_increment . "'");
		$existing_slug_count = mysqli_num_rows($result);
	}

// If there are no existing slugs, use the original slug. If there are, then we use the new slug that we picked.
	$final_slug = $check > 0 ? $product_slug . "-" . $product_slug_increment : $product_slug;
    /*if($existing_slug_count > 0)
            $final_slug = $product_slug . "-" . $product_slug_increment;
    else
    $final_slug = $product_slug;*/

    return $check;
  }

  function toAscii($str) {
  	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
  	$clean = strtolower(trim($clean, '-'));
  	$clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

  	return $clean;
  }

///////////////////////////////////////

/**
 * Category 
 * */
function printCategoryList($categories, $level = 0) {
	if (!is_null($categories) && count($categories) > 0) {
		foreach ($categories as $category) {
			echo '<option value="' . $category['name'] . '">' . str_repeat('&nbsp;&nbsp;', $level) . getCategoryName($category['name']) . '</option>';
			if (hasParentCategory($category['name'])) {
				printCategoryList($category['children'], $level + 1);
			}
		}
	}
}

function hasParentCategory($id) {
	$qry = query("SELECT term_id FROM " . TAXONOMY . " WHERE " . CATEGORY . " AND parent!='0' AND parent=" . $id . "");
	$count = numRows($qry);
	if ($count) {
		return true;
	}
	return false;
}

function isIdNotOne1($id) {
	if ($id != 1) {
		echo "<a href='javascript:deleteCategory(" . $id . ")' class='btn btn-mini btn-danger'>Delete</a>";
	}
}

function isIdNotOne2($id) {
	if ($id != 1) {
		echo "<input type='checkbox' name='item[]' id='item_" . $id . "' value='" . $id . "' />";
	}
}

function getCategoryName($id) {
	$qry = query("SELECT t.name FROM " . TERMS . " t INNER JOIN " . TAXONOMY . " tt ON t.term_id=tt.term_id WHERE " . CATEGORY . " AND t.term_id ='{$id}'");
	$row = fetch($qry);
	return $row[0];
}

function getCategorySlug($id) {
	$qry = query("SELECT slug FROM " . TERMS . " WHERE term_id ='{$id}'");
	$row = fetch($qry);
	return $row[0];
}

function getCategoryParent($id) {
	$qry = query("SELECT parent FROM " . TAXONOMY . " WHERE term_id ='{$id}'");
	$row = fetch($qry);
	return $row[0];
}

function getCategoryDescription($id) {
	$qry = query("SELECT description FROM " . TAXONOMY . " WHERE term_id ='{$id}'");
	$row = fetch($qry);
	return $row[0];
}

function getCategoryCount($id) {
	$qry = query("SELECT count FROM " . TAXONOMY . " WHERE term_id ='{$id}'");
	$row = fetch($qry);
	return $row[0];
}

function printCategoryTable($categories, $level = 0) {
	$count = count($categories);
	$null = is_null($categories);
	if (!$null && $count > 0) {
		foreach ($categories as $category) {
			echo "<tr id='row" . $category['name'] . "' onmouseover=hoverRow(" . $category['name'] . ",'categories') onmouseout=outRow(" . $category['name'] . ",'categories')  >";
			echo "<td class='rowTable'>";
            //isIdNotOne2($category['name']);
			if ($category['name'] != 1) {
				echo "<input type='checkbox' name='item[]' id='item_" . $category['name'] . "' value='" . $category['name'] . "' />";
			}
			echo "</td>";
			echo "<td class='rowTable'><a href='javascript:updateCategory(" . $category['name'] . "," . getCategoryParent($category['name']) . ")'  style='vertical-align:top;' ><input id='level" . $category['name'] . "' type='hidden' value='" . ($level + 1) . "' />" . str_repeat('&ndash;&nbsp;', $level) . "<span id='cName" . $category['name'] . "'>" . getCategoryName($category['name']) . "</span></a><br />";
			echo "<div><div><div>";
			echo "<div class='row_actions_categories'>";
			echo "<a href='#editCategory' onclick='updateCategory(" . $category['name'] . "," . getCategoryParent($category['name']) . ")' data-toggle='modal' class='btn btn-mini btn-primary'>Edit</a>";
			echo "&nbsp;";
            //isIdNotOne1($category['name']);

			if ($category['name'] != 1) {
				echo "<a href='javascript:deleteCategory(" . $category['name'] . ")' class='btn btn-mini btn-danger'>Delete</a>";
			}
			echo "</div></div></div></div>
		</td>
		<td class='rowTable'><span id='cDescription" . $category['name'] . "'>" . getCategoryDescription($category['name']) . "</span></td>
		<td class='rowTable'><span id='cSlug" . $category['name'] . "'>" . getCategorySlug($category['name']) . "</span></td>
		<td class='rowTable text-center'><span id='cPostCount" . $category['name'] . "'>" . getCategoryCount($category['name']) . "</span></td>
	</tr>";
	if (hasParentCategory($category['name'])) {
		printCategoryTable($category['children'], $level + 1);
	}
}
}
}

//////////////////////////////// End Category


function isSelected($option, $value) {
	if ($option == $value) {
		return print(' selected ');
	}
	return null;
}

/**
 * Pages
 */
function printPageList($pages, $level = 0) {
	if (!is_null($pages) && count($pages) > 0) {
		foreach ($pages as $page) {
			echo '<option value="' . $page['name'] . '">' . str_repeat('&nbsp;&nbsp;', $level) . getCategoryName($page['name']) . '</option>';
			if (hasParentCategory($page['name'])) {
				printPageList($page['children'], $level + 1);
			}
		}
	}
}

function hasParentPage($id) {
	$qry = query("SELECT term_id FROM " . TAXONOMY . " WHERE " . CATEGORY . " AND parent!='0' AND parent=" . $id . "");
	$count = numRows($qry);
	if ($count) {
		return true;
	}
	return false;
}

function getPageName($id) {
	$qry = query("SELECT t.name, t.slug, tt.description, tt.count, tt.term_id, tt.parent FROM " . TERMS . " t INNER JOIN " . TAXONOMY . " tt ON t.term_id=tt.term_id WHERE " . CATEGORY . " AND t.term_id ='{$id}'");
	$row = fetch($qry);
	return $row[0];
}

function escape_string($data = "") {
	$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if (gettype($data) == 'array') {
		foreach (array_keys($data) as $key) {
			$data[$key] = escape_string($data[$key]);
		}
		return $data;
	} else {
		return mysqli_real_escape_string($con, $data);
	}
}

// TABLE SERVER SIDE PROCESSOR SCRIPT

function datatable_processor($ac = array(), $sI = "", $sT = "", $_sql = "") {
    // header("Content-type: application/json");
    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
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
    $gaSql['user'] = DB_USER;
    $gaSql['password'] = DB_PASS;
    $gaSql['db'] = DB_NAME;
    $gaSql['server'] = DB_HOST;

    /* REMOVE THIS LINE (it just includes my SQL connection user/pass) */

    // include( $_SERVER['DOCUMENT_ROOT']."/datatables/mysql.php" );


    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * If you just want to use the basic configuration for DataTables with PHP server-side, there is
     * no need to edit below this line
     */

    /*
     * Local functions
     */
    function fatal_error($sErrorMessage = '') {
    	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    	die($sErrorMessage);
    }

    /*
     * MySQL connection
     */
    if (!$gaSql['link'] = mysqli_connect($gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db'])) {
    	fatal_error('Could not open connection to server');
    }

    // if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
    // {
    //     fatal_error( 'Could not select database ' );
    // }


    /*
     * Paging
     */
    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    	$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
    	intval($_GET['iDisplayLength']);
    }


    /*
     * Ordering
     */
    $sOrder = "";
    if (isset($_GET['iSortCol_0'])) {
    	$sOrder = "ORDER BY  ";
    	for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
    		if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
    			$sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
    			($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
    		}
    	}

    	$sOrder = substr_replace($sOrder, "", -2);
    	if ($sOrder == "ORDER BY") {
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
    if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
    	$sWhere = "WHERE (";
    	for ($i = 0; $i < count($aColumns); $i++) {
    		$sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%' OR ";
    	}
    	$sWhere = substr_replace($sWhere, "", -3);
    	$sWhere .= ')';
    }

    /* Individual column filtering */
    for ($i = 0; $i < count($aColumns); $i++) {
    	if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
    		if ($sWhere == "") {
    			$sWhere = "WHERE ";
    		} else {
    			$sWhere .= " AND ";
    		}
    		$sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_' . $i]) . "%' ";
    	}
    }


    /*
     * SQL queries
     * Get data to display
     */
    $sql_table = $_sql;

    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
    FROM  ({$sql_table}) `t1`
    $sWhere
    $sOrder
    $sLimit
    ";
    $rResult = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));

    /* Data set length after filtering */
    $sQuery = "
    SELECT FOUND_ROWS()
    ";
    $rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
    $aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    /* Total data set length */
    $sQuery = "
    SELECT COUNT(`" . $sIndexColumn . "`)
    FROM   ({$sql_table}) `t1`
    ";
    $rResultTotal = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
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

    while ($aRow = mysqli_fetch_array($rResult)) {
    	$row = array();
    	for ($i = 0; $i < count($aColumns); $i++) {
    		if ($aColumns[$i] == "version") {
    			/* Special output formatting for 'version' column */
    			$row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
    		} else if ($aColumns[$i] != ' ') {
    			/* General output */
    			$row[] = $aRow[$aColumns[$i]];
    		}
    	}

    	$output['aaData'][] = $row;
    }

    return $output;
  }

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