<?php
/*
Theme Name : Default 101
*/

$cmsThumbnailAllowedSizes = array(
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

/*Sample functions*/
function custom_function_add_subscribers(){
	$post_data = $_POST;
	mailchimp_add_subscriber();
	redirect(get_current_url());
}

/* CUSTOM FUNCTIONS: Products Related functions */
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
* breadcrumbs for ecatalog
* 
* @param array $uri
* @return string
*/
function ecatalog_breadcrumbs($uri){
	array_shift($uri);
	print_r($uri); die;
}


/**
* Get a category array from an array of categories based on url_slug.
*
* @param array $arr
* @param string $slug
* @return array
*/

function get_category_from_categories_array($arr, $slug){
	$temp = array_filter($arr, function($elem) use ($slug) {
		return $elem['url_slug'] == $slug;
	});
	$temp = array_values($temp);
	return $temp[0];
}


# HIERARCHICAL SQL
# NOTE: not used
# TODO: remove if not used
function getFullTree($ancestor){
	global $db;
	$sql = "SELECT t1.id AS lev1, t2.id as lev2, t3.id as lev3, t4.id as lev4
	FROM product_categories AS t1
	LEFT JOIN product_categories AS t2 ON t2.category_parent = t1.id
	LEFT JOIN product_categories AS t3 ON t3.category_parent = t2.id
	LEFT JOIN product_categories AS t4 ON t4.category_parent = t3.id
	WHERE t1.id = $ancestor";

	$qry = $db->query($sql);
	$retrieves = array();

	while ($row = $db->fetch($qry, 'assoc')){
		$retrieves[] = $row;
	}
	return $retrieves;
}

function getDescendants($ancestor){
	global $db;
	$sql = "SELECT t1.id AS lev1, t2.id as lev2, t3.id as lev3, t4.id as lev4
	FROM product_categories AS t1
	LEFT JOIN product_categories AS t2 ON t2.category_parent = t1.id
	LEFT JOIN product_categories AS t3 ON t3.category_parent = t2.id
	LEFT JOIN product_categories AS t4 ON t4.category_parent = t3.id
	WHERE t1.id = $ancestor";

	$qry = $db->query($sql);
	$retrieves = array();

	while ($row = $db->fetch($qry, 'assoc')){
		if ($row['lev1'] != null) $retrieves[$row['lev1']] = $row['lev1'];
		if ($row['lev2'] != null) $retrieves[$row['lev2']] = $row['lev2'];
		if ($row['lev3'] != null) $retrieves[$row['lev3']] = $row['lev3'];
		if ($row['lev4'] != null) $retrieves[$row['lev4']] = $row['lev4'];
	}

	return array_keys($retrieves);
}

function get_products_by_category_array($category_array, $limit = 4){
	global $db;
	$in_clause = join(',', $category_array);

	$sql = "SELECT rel.category_id, p.*  
	FROM `products_categories_relationship` as rel
	JOIN `products` as p 
	ON rel.product_id = p.id
	WHERE rel.category_id IN ($in_clause)
	GROUP BY p.id
	LIMIT $limit";

	$qry = $db->query($sql);
	$retrieves = array();

	while ($row = $db->fetch($qry, 'assoc')){
		$retrieves[] = $row;
	}
	return $retrieves;
}

/**
* Gets products categories
*
* @return array
*/
function get_parent_product_categories($id = 0){
	$product_categories = array();
	global $db;
	$sql = "SELECT * FROM `product_categories` WHERE`category_parent` = $id";
	$qry = $db->query($sql);

	while ($row = $db->fetch($qry, 'assoc')){
		$product_categories[] = $row;
	}
	return $product_categories;
}

function get_child_product_categories($parent_id){
	global $db;
	$sql = "SELECT * FROM `product_categories` WHERE`category_parent` = $parent_id";
	$qry = $db->query($sql);

	$children = array();

	while ($row = $db->fetch($qry, 'assoc')){
		$children[] = $row;
	}
	return $children;
}

function cms_get_sub_categories($tree = array()){
	$cat = array();

	if (isset($tree['children'])) {
		foreach ($tree['children'] as $key => $value) {
			$cat[] = $value['detail'];
		}
	}

	return $cat;
}
function cms_get_category_products($tree = array(), $full = false){
	$products = array();

	if (isset($tree['products'])) {
		foreach ($tree['products'] as $key => $value) {
			$products[$value->id] = $value;
		}
	}

	if ($full) {
		foreach ($tree['children'] as $key => $value) {
			$temp_prod = cms_get_category_products($value, $full);
			foreach ($temp_prod as $key => $value) {
				$products[$value->id] = $value;
			}
		}
	}

	return $products;
}

function cms_get_category_parent_heirarchy($categories = array(), $parent = 0){
	$heirarchy = array();

	foreach ($categories as $key => $value) {
		if ($value->category_parent == $parent) {
			$category = array(
				"detail" => $value,
				"children" => $this->get_category_parent_heirarchy($categories, $value->id),
				);
			$heirarchy[$value->id] = $category;
		}
	}

	return $heirarchy;
}