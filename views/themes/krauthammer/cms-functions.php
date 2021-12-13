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




// Get parent page subpages
function _get_post_by_parent_id($parent_id) {
  global $db;
  
  $post_content = '';
  
  $sql = "Select post_content from cms_posts where parent_id = $parent_id and post_status = 'active'";
  $qry = $db->query($sql); 

	while ($row = $db->fetch($qry, 'assoc')){
		$post_content .= $row['post_content'];
	}
	return $post_content; 

}

require PLUGINS . 'simplehtmldom/simple_html_dom.php';
 

function get_publicationAll($pageNo) {
      
    
    error_reporting(E_ALL);
    ini_set('display_errors', true);

    $target = "http://blog.krauthammer.com/";
    $user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36';
    $timeout = '60';
    
    $ch = curl_init($target);
    
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEJAR, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "https://blog.krauthammer.com/" . $pageNo);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // grab URL and pass it to the browser
    $initpage = curl_exec($ch);
    
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    $answer = array();
    
    if (!empty($initpage)) {
        $html = str_get_html($initpage);
        $i = 0;
        // $elem = $html->find('div[class=section-wrapper]', 0);
        $listingWrapper = $html->find('div[class=listing-template]', 0);
        
        $displayImage = '';
        
        // $title = $elem->find('a.link-hover-theme', 0)->innertext = ''; 
        
        foreach ($listingWrapper->find('div.post-wrapper') as $postWrapper) { 
            
            // foreach ($postWrapper->find('div.clear') as $divClear) { 
            //     foreach ($postWrapper->find('div.featured-image') as $featuredImage) { 
            //         if($featuredImage == true || !empty($featuredImage) || $featuredImage != '') {
            //             foreach ($featuredImage->find('img.radius') as $image) {  
            //                 if($image == true || !empty($image) || $image != '') {
            //                       echo $answer[$i]['image'] = '
            //                         <div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">
            //                             <div class="white-block trans d-flex flex-column align-items-center justify-content-start p-0 h-100">
            //                                 <img src="' . $image->src . '" class="img-responsive img-fluid rounded mx-auto d-block">'; 
            //                 } else {
            //                         echo '
            //                             <div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">
            //                                 <div class="white-block trans d-flex flex-column align-items-center justify-content-start p-0 h-100">
            //                                     <img src="http://jus.maxdyna.com/krauthammer/views/themes/krauthammer/assets/images/publications/no-image.png" class="img-responsive img-fluid rounded mx-auto d-block">'; 
            //                 }
            //             }
            //         } else {
            //             echo '
            //                 <div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">
            //                     <div class="white-block trans d-flex flex-column align-items-center justify-content-start p-0 h-100">
            //                         <img src="http://jus.maxdyna.com/krauthammer/views/themes/krauthammer/assets/images/publications/no-image.png" class="img-responsive img-fluid rounded mx-auto d-block">'; 
            //         }
            //     }  
            // }
            
            // foreach ($postWrapper->find('div.clear') as $divClear) { 
            //     foreach ($postWrapper->find('div.featured-image') as $featuredImage) {   
            //         foreach ($featuredImage->find('img.radius') as $image) {   
            //             if($image == true || !empty($image) || $image != '') {
            //                 echo $answer[$i]['image'] = '
            //                     <div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">
            //                         <div class="white-block trans d-flex flex-column align-items-center justify-content-start p-0 h-100">
            //                             <img src="' . $image->src . '" class="img-responsive img-fluid rounded mx-auto d-block">';
            //             } else {
            //                 echo '
            //                 <div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">
            //                         <div class="white-block trans d-flex flex-column align-items-center justify-content-start p-0 h-100">
            //                             <img src="http://jus.maxdyna.com/krauthammer/views/themes/krauthammer/assets/images/publications/no-image.png" class="img-responsive img-fluid rounded mx-auto d-block">';
            //             }
                               
            //         } 
            //     }
                                 
            // }
             
            
            foreach ($postWrapper->find('div.section-intro') as $sectionIntro) {
                foreach ($sectionIntro->find('a.link-hover-theme') as $title) {
                    echo $answer[$i]['Title'] = ' 
                    <div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">
                                     <div class="white-block trans d-flex flex-column align-items-center justify-content-start p-0 h-100">
                                         <img src="http://jus.maxdyna.com/krauthammer/views/themes/krauthammer/assets/images/publications/no-image.png" class="img-responsive img-fluid rounded mx-auto d-block">
                     <div class="text-block d-flex flex-column align-items-center justify-content-center">
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="f_j_s c_222 t20" href="'. $title->href .'">'. $title->plaintext .'</a>
                                    </li>';
                }
            }
            
            foreach ($postWrapper->find('a.strong') as $author) {
                echo $answer[$i]['Author'] = '
                                    <li>    
                                        <a class="f_j_m c_008ece t16" href="'. $author->href .'">'. $author->plaintext .'</a>
                                    </li>';
            }
            
            foreach ($postWrapper->find('div.clear') as $divClear) {
                foreach ($divClear->find('p') as $aboutText) {
                    echo $answer[$i]['About'] = '
                                    <li>    
                                        <p class="f_j_b c_222 t16">'. $aboutText->plaintext .'</p>
                                    </li>';
                }
            }
            
            foreach ($postWrapper->find('div.clear') as $divClear) {
                foreach ($divClear->find('a.button') as $button) {
                    echo $answer[$i]['Button'] = '
                                    <li>    
                                        <a class="f_j_s t16 c_008ece text-uppercase" href="'. $button->href .'">Read More</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>';
                }
            }
            // echo $answer[$i]['content'] = '<div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">' .
            // $postWrapper . "</div>"; 
        }
         
        $i++;
        
    } 
    

}
 

 
 function publicationDisplay($tabLink = '', $pageNo = '') {
      
    
    error_reporting(E_ALL);
    ini_set('display_errors', true);

    $target = "http://blog.krauthammer.com/";
    $user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36';
    $timeout = '60';
    
    $ch = curl_init($target);
    
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEJAR, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "https://blog.krauthammer.com/tag/" . $tabLink. $pageNo);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // grab URL and pass it to the browser
    $initpage = curl_exec($ch);
    
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    $answer = array();
    
    if (!empty($initpage)) {
        $html = str_get_html($initpage);
        $i = 0;
        // $elem = $html->find('div[class=section-wrapper]', 0);
        $listingWrapper = $html->find('div[class=listing-template]', 0);
        
        $displayImage = '';
        
        // $title = $elem->find('a.link-hover-theme', 0)->innertext = ''; 
        
        foreach ($listingWrapper->find('div.post-wrapper') as $postWrapper) { 
            
            foreach ($postWrapper->find('div.clear') as $divClear) { 
                foreach ($postWrapper->find('div.featured-image') as $featuredImage) {   
                    foreach ($featuredImage->find('img.radius') as $image) {   
                        if($image == true || !empty($image) || $image != '') {
                            echo $answer[$i]['image'] = '
                                <div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">
                                    <div class="white-block trans d-flex flex-column align-items-center justify-content-start p-0 h-100">
                                        <img src="' . $image->src . '" class="img-responsive img-fluid rounded mx-auto d-block">';
                        } else {
                            echo '
                            <div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">
                                    <div class="white-block trans d-flex flex-column align-items-center justify-content-start p-0 h-100">
                                        <img src="http://jus.maxdyna.com/krauthammer/views/themes/krauthammer/assets/images/publications/no-image.png" class="img-responsive img-fluid rounded mx-auto d-block">';
                        }
                               
                    } 
                }
                                 
            }
                 
            foreach ($postWrapper->find('div.section-intro') as $sectionIntro) {
                foreach ($sectionIntro->find('a.link-hover-theme') as $title) {
                    echo $answer[$i]['Title'] = ' 
                     <div class="text-block d-flex flex-column align-items-center justify-content-center">
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="f_j_s c_222 t20" href="'. $title->href .'">'. $title->plaintext .'</a>
                                    </li>';
                }
            }
            
            foreach ($postWrapper->find('a.strong') as $author) {
                echo $answer[$i]['Author'] = '
                                    <li>    
                                        <a class="f_j_m c_008ece t16" href="'. $author->href .'">'. $author->plaintext .'</a>
                                    </li>';
            }
            
            foreach ($postWrapper->find('div.clear') as $divClear) {
                foreach ($divClear->find('p') as $aboutText) {
                    echo $answer[$i]['About'] = '
                                    <li>    
                                        <p class="f_j_b c_222 t16">'. $aboutText->plaintext .'</p>
                                    </li>';
                }
            }
            
            foreach ($postWrapper->find('div.clear') as $divClear) {
                foreach ($divClear->find('a.button') as $button) {
                    echo $answer[$i]['Button'] = '
                                    <li>    
                                        <a class="f_j_s t16 c_008ece text-uppercase" href="'. $button->href .'">Read More</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>';
                }
            }
            // echo $answer[$i]['content'] = '<div class="col-12 col-sm-6 col-lg-4 item f_j_b t16 c_fff d-flex flex-column align-items-center justify-content-center">' .
            // $postWrapper . "</div>"; 
        }
         
        $i++;
        
    } 
     
}

  
 
 function get_paginationAll($pageNo = '') { 
 
    error_reporting(E_ALL);
    ini_set('display_errors', true);

    $target = "http://blog.krauthammer.com/";
    $user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36';
    $timeout = '60';
    
    $ch = curl_init($target);
    
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEJAR, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "http://blog.krauthammer.com/");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // grab URL and pass it to the browser
    $initpage = curl_exec($ch);
    
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    $answer = array();
    
    if (!empty($initpage)) {
        $html = str_get_html($initpage);
        $i = 0;
        // $elem = $html->find('div[class=section-wrapper]', 0);
        $listingWrapper = $html->find('div[class=listing-template]', 0);
        
         
        $pagination = $listingWrapper->find('div.blog-navigation', 0);
        echo $pagination;
        
        
    }      

}
 
function paginationDisplay($paginationLink = '', $pageNo = '') { 
    error_reporting(E_ALL);
    ini_set('display_errors', true);

    $target = "http://blog.krauthammer.com/";
    $user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36';
    $timeout = '60';
    
    $ch = curl_init($target);
    
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEJAR, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "https://blog.krauthammer.com/tag/" . $paginationLink . $pageNo);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // grab URL and pass it to the browser
    $initpage = curl_exec($ch);
    
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    $answer = array();
    
    if (!empty($initpage)) {
        $html = str_get_html($initpage);
        $i = 0;
        // $elem = $html->find('div[class=section-wrapper]', 0);
        $listingWrapper = $html->find('div[class=listing-template]', 0);
        
        $pagination = $listingWrapper->find('div.blog-navigation', 0);
        echo $pagination;
        
        
    }  

}
  