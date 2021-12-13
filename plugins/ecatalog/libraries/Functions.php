<?php
/**
* @var ecatalog_products_retrieved should be false, but it will be set to true if there are products found
*/
$ecatalog_products_retrieved = false;
/**
* @var ecatalog_products should be null if there are ecatalog_products_retrieved is false.
*/
$ecatalog_products = null;
/**
* @var ecatalog_products should be false, but it will be set to true if there are products found
*/
$ecatalog_current_product = null;
/**
* @var ecatalog_query_filter data passed will be array;
*/
$ecatalog_query_filters = array();

/**
* Query all the products in products table
* Here is an inline example:
* <code>
* <?php
*  $products = ecatalog_query_products('url_slug='.$url);
* ?>
* </code>
* @return void
*/
function ecatalog_query_products($str_query) {

    global $ecatalog_query_filters;
    
    parse_str($str_query, $ecatalog_query_filters);
}
/**
* ecatalog_have_products will check if there are product existed or selected
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  ecatalog_query_products('url_slug=);
*  if(ecatalog_have_products()){}
* ?>
* </code>
* @return int
*/
function ecatalog_have_products() {
    global $ecatalog_products;
    global $ecatalog_products_retrieved;
    global $ecatalog_query_filters;

    if ($ecatalog_products_retrieved == false) {
        $ecatalog_products_retrieved = true;
        $ecatalog_products = ecatalog_get_products($ecatalog_query_filters);
    }

    return count($ecatalog_products) > 0;
}
/**
* ecatalog_product_count will count the ecatalog_products array
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  ecatalog_query_products('url_slug=);
*  if(ecatalog_product_count() > 0){}
* ?>
* </code>
* @return void
*/
function ecatalog_product_count() {
    global $ecatalog_products;
    echo count($ecatalog_products);
}
/**
* ecatalog_the_product shift the array and give the ecatalog_current_product and product
* Here is an inline example:
* <code>
* <?php
*  ecatalog_query_products('url_slug=);
*  if(ecatalog_product_count() > 0){
*   ecatalog_the_product(); 
*   }
* ?>
* </code>
* @return void
*/
function ecatalog_the_product() {
    global $ecatalog_products;
    global $ecatalog_current_product;
    $ecatalog_current_product = array_shift($ecatalog_products);
}
/**
* ecatalog_get_product_name will return the current product name
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  echo ecatalog_get_product_name();
* ?>
* </code>
* @return string | product name
*/
function ecatalog_get_product_name() {
    global $ecatalog_current_product;
    return $ecatalog_current_product['product_name'];
}
/**
* ecatalog_meta_title will return the current product meta title
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  echo ecatalog_meta_title();
* ?>
* </code>
* @return string | meta title
*/
function ecatalog_meta_title(){
    global $ecatalog_current_product;
    return $ecatalog_current_product['seo_title'];
}
/**
* ecatalog_meta_description will return the current product meta description
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  echo ecatalog_meta_description();
* ?>
* </code>
* @return string | meta description
*/
function ecatalog_meta_description(){
    global $ecatalog_current_product;
    return $ecatalog_current_product['seo_description'];
}
/**
* ecatalog_product_name will return the current product meta description
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <html>
*  <p><?php ecatalog_product_name();?> </p>
* </html>
* </code>
* @return string | meta description
*/
function ecatalog_product_name() {
    echo ecatalog_get_product_name();
}
/**
* ecatalog_get_featured_image will return the current product featured image url
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  echo ecatalog_get_featured_image()
* ?>
* </code>
* @return string | featured image url
*/
function ecatalog_get_featured_image() {
    global $ecatalog_current_product;
    return $ecatalog_current_product['featured_image_url'];
}
/**
* ecatalog_featured_image will echo the current product featured image url
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <html>
*  <image src="<?php ecatalog_featured_image(); ?>" alt=""/>
* </html>
* </code>
* @return void
*/
function ecatalog_featured_image() {
    echo ecatalog_get_featured_image();
}
/**
* ecatalog_get_product_permalink will return the current product permanent link
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  echo ecatalog_get_product_permalink();
* ?>
* </code>
* @return string | current product permanent link
*/
function ecatalog_get_product_permalink() {
    global $ecatalog_current_product;
    return get_bloginfo('installation_url') . '/products/' . $ecatalog_current_product['url_slug'];
}
/**
* ecatalog_product_permalink will echo the current product permanent link
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <html>
*  <a href="<?php ecatalog_product_permalink()?">Link</a>
* </html>
* </code>
* @return void
*/
function ecatalog_product_permalink() {
    echo ecatalog_get_product_permalink();
}
/**
* ecatalog_get_url_slug will return the current product url slug
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  echo ecatalog_get_url_slug();
* ?>
* </code>
* @return string | return the current product url slug
*/
function ecatalog_get_url_slug() {
    global $ecatalog_current_product;
    return $ecatalog_current_product['url_slug'];
}
/**
* ecatalog_url_slug will echo the current product url slug
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <html
*  <a href="website.com/<?php ecatalog_url_slug() ?>">Product Name</a>
* <html>
* </code>
* @return void
*/
function ecatalog_url_slug() {
    echo ecatalog_get_url_slug();
}
/**
 * Get excerpt from string
 * 
 * @param String $str String to get an excerpt from
 * @param Integer $startPos Position int string to start excerpt from
 * @param Integer $maxLength Maximum length the excerpt may be
 * @return String excerpt
 */
function ecatalog_get_product_excerpt($startPos=0, $maxLength=500) {
    $str = ecatalog_get_product_detail("product_description");

    if(strlen($str) > $maxLength) {
        /*
        $excerpt   = substr($str, $startPos, $maxLength-3);
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt   = substr($excerpt, 0, $lastSpace);
        */
        $excerpt  = substr($str, $startPos, $maxLength-3) . ' ...';
    } else {
        $excerpt = $str;
    }

    return $excerpt;
}
/**
* ecatalog_get_product_detail will return the product detail depending on the parameter
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  echo ecatalog_get_product_detail();
* ?>
* </code>
* @return string | product name
*/
function ecatalog_get_product_detail($key = "") {
    global $ecatalog_current_product;
    return isset($ecatalog_current_product[$key]) ? $ecatalog_current_product[$key] : "";
}
/**
* ecatalog_get_products will query based on categories and id
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  $posts = ecatalog_get_products(array('id' => $product_id));
* ?>
* </code>
* @return array | Array of Objects Product
*/
function ecatalog_get_products($options = array()) {
    global $db;
    $ecatalog_query_filters = array();
    $category_filter = array();
    $join_str = '';
    $posts_per_page = null;

    # Set default WHERE filters here. The product key is the column to filter.

    $ecatalog_query_filters['product_status'] = 'active';

    # Merge $options into the existing query filters.
    # Filters specified in $options will overwrite the options in $ecatalog_query_filters.

    $ecatalog_query_filters = array_merge($ecatalog_query_filters, $options);

    if (isset($ecatalog_query_filters['posts_per_page'])) {
        $posts_per_page = $ecatalog_query_filters['posts_per_page'];
        unset($ecatalog_query_filters['posts_per_page']);
    }

    if (isset($ecatalog_query_filters['category'])) {
        //  $category_filter
        # products_categories_relationship

        $ecatalog_query_filters['products_categories_relationship.category_id'] = explode(',', $ecatalog_query_filters['category']);
        $join_str = 'Inner Join products_categories_relationship on products.id = products_categories_relationship.product_id';
        unset($ecatalog_query_filters['category']);
    }

    $additional_filters_sql_array = array();


    foreach ($ecatalog_query_filters as $key => $value) {

        // str_replace($value, $key, $additional_filters_sql_array)
        $key = str_replace('.', '`.`', $key);
        if ((array) $value !== $value) {
            $additional_filters_sql_array[] = "`$key` = '$value'";
        } else {
            $additional_filters_sql_array[] = "`$key` IN('" . implode("','", $value) . "')";
        }
    }


    $additional_filters_sql = implode(' AND ', $additional_filters_sql_array);
    $additional_filters_sql = count($additional_filters_sql_array) > 0 ? 'Where ' . $additional_filters_sql : '';

    $limit_sql = $posts_per_page == null ? '' : 'Limit ' . $posts_per_page;

    $ecatalog_products_retrieved = array();
    $order = get_product_order_display(SESSION::get('order_type'));

    $lang = cms_get_language();

    $prod_table = " (Select `products`.*, `t`.`meta` `translate`, `i`.`meta` `custom_fields` FROM `products` Left JOIN `cms_translation` `t` ON `t`.`guid` = `products`.`id` and `language` = '{$lang}' and `type` = 'product' Left JOIN `product_items` `i` ON `i`.`guid` = `products`.`id` and `i`.`type` = 'custom-field' and `i`.`status` = 'Y' and `i`.`language` = '{$lang}'  ) `products` ";
    $prod_sql = "SELECT DISTINCT products.* FROM {$prod_table} $join_str $additional_filters_sql ".$order." $limit_sql ";

    $result = $db->query( $prod_sql );

    /*echo "SELECT DISTINCT products.* FROM `products` $join_str $additional_filters_sql ORDER BY `id` DESC $limit_sql";*/
    while ($row = $db->fetch($result, 'assoc')) {
    	if (isset($row['translate'])) {
    		$t = json_decode($row['translate']);

    		if (isset($t->product->product_name)) {
    			$row['product_name'] = $t->product->product_name;
    		}
            if (isset($t->product->product_description) && $t->product->product_description != '') {
                $row['product_description'] = $t->product->product_description;
            }
            if (isset($t->product->seo_title) && $t->product->seo_title != '') {
                $row['seo_title'] = $t->product->seo_title;
            }
    		if (isset($t->product->seo_description) && $t->product->seo_description != '') {
    			$row['seo_description'] = $t->product->seo_description;
    		}
    	}

        /* 2018-04-26 */
        /* preprocess custom fields */
        if (isset($row['custom_fields'])) {
            $cf     = json_decode($row['custom_fields']);
            $cf_i   = array();
            foreach ($cf as $key => $value) {
                // if ($value->type == 'textarea') {
                //     $value->value = cms_post_content_processor($value->value);
                // }
                // $cf_i[$value->key] = $value; /* @custom field */
            }

            $cf_i = _ecatalog_get_product_custom_fields_(json_decode($row['custom_fields']));

            foreach ($cf_i as $key => $value) {
                // $custom_fields[$value->key] = $value;
                if ($value->type == 'textarea' || $value->type == 'textarea-simple') {
                    $cf_i[$value->key]->value = cms_post_content_processor($value->value);
                }
            }

            $row['custom_fields'] = $cf_i;
        }
        /* 2018-04-26-end */

      $ecatalog_products_retrieved[] = $row;
    }

    /* Get translated page Product page Start*/
    if (cms_get_language() != cms_get_language_reserved() && count($ecatalog_products_retrieved)<=0) {
        if (isset($ecatalog_query_filters['url_slug'])) {
            $s = $ecatalog_query_filters['url_slug'];

            $pt_sql = "SELECT * FROM `cms_translation` WHERE type = 'product' and language = '". cms_get_language() ."' and meta like '%\"{$s}\"%'";
            $pt = $db->select($pt_sql);

            $selected_translated_product = array();

            foreach ($pt as $key => $value) {
                $j = json_decode($value->meta);
                if (isset($j->product) && isset($j->product->url_slug) && $s == $j->product->url_slug) {
                    $selected_translated_product = $value;
                    break;
                }
            }

            if (count($selected_translated_product)) {
                $p_id = $selected_translated_product->guid;

                /* Reconstruct filter START */
                $filter = $ecatalog_query_filters;
                unset($filter['url_slug']);
                $filter['id'] = $p_id;
                $additional_filters_sql_array = array();
                foreach ($filter as $key => $value) {
                    $key = str_replace('.', '`.`', $key);
                    if ((array) $value !== $value) {
                        $additional_filters_sql_array[] = "`$key` = '$value'";
                    } else {
                        $additional_filters_sql_array[] = "`$key` IN('" . implode("','", $value) . "')";
                    }
                }
                $additional_filters_sql = implode(' AND ', $additional_filters_sql_array);
                $additional_filters_sql = count($additional_filters_sql_array) > 0 ? 'Where ' . $additional_filters_sql : '';
                /* Reconstruct filter END */

                $prod_sql = "SELECT DISTINCT products.* FROM {$prod_table} $join_str $additional_filters_sql ".$order." $limit_sql ";
                $result = $db->query( $prod_sql );
                while ($row = $db->fetch($result, 'assoc')) {
                    if (isset($row['translate'])) {
                        $t = json_decode($row['translate']);

                        if (isset($t->product->product_name)) {
                            $row['product_name'] = $t->product->product_name;
                        }
                        if (isset($t->product->product_description) && $t->product->product_description != '') {
                            $row['product_description'] = $t->product->product_description;
                        }
                        if (isset($t->product->seo_title) && $t->product->seo_title != '') {
                            $row['seo_title'] = $t->product->seo_title;
                        }
                        if (isset($t->product->seo_description) && $t->product->seo_description != '') {
                            $row['seo_description'] = $t->product->seo_description;
                        }
                        if (isset($t->product->url_slug) && $t->product->url_slug != '') {
                            $row['url_slug'] = $t->product->url_slug;
                        }
                    }

                  $ecatalog_products_retrieved[] = $row;
                }
            }
        }
    }
    /* Get translated page Product page End*/

    // header_json(); print_r($ecatalog_products_retrieved); exit();

    return $ecatalog_products_retrieved;
}
/**
* ecatalog_get_product will return product based on categories and id
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  $product = ecatalog_get_product($product_id);
* ?>
* </code>
* @return array | array current products 
*/
function ecatalog_get_product($product_id = null, $options = array()) {
    global $ecatalog_current_product;
    if ($product_id == null) {
        return $ecatalog_current_product;
    } else {
        $posts = ecatalog_get_products(array('id' => $product_id));
        if(isset($posts[0]))
            return $posts[0];
    }
}
/**
* ecatalog_get_product_images will return based on product id
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*  $product_images = ecatalog_get_product_images(array('product_id' => $id));
* ?>
* </code>
* @return array | array current products images
*/
function ecatalog_get_product_images($options = array()) {
    global $db;
    $ecatalog_product_image = array();
    $result = $db->query("Select * From `products_gallery_images` Where `product_id` = '".$options['product_id']."'");
    while ($row = $db->fetch($result, 'assoc')) {
        $ecatalog_product_image[] = $row;
    }
    return $ecatalog_product_image;
}
/**
* ecatalog_get_categories will return categories based on categories and product_id
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*   $categories = ecatalog_get_categories();
* ?>
* </code>
* @return array | array categories
*/
function ecatalog_get_categories($options = array()) {
    //var_dump($options);
    global $db;
    $ecatalog_query_filters = array();
    $posts_per_page = null;

    # Set default WHERE filters here. The product key is the column to filter.
    #
    # Merge $options into the existing query filters.
    # Filters specified in $options will overwrite the options in $ecatalog_query_filters.

    $ecatalog_query_filters = array_merge($ecatalog_query_filters, $options);
    /*var_dump($options);*/

    if (isset($ecatalog_query_filters['product_id'])) {
        $ecatalog_query_filters['id'] = array();
        $sql_qry = "SELECT * FROM `products_categories_relationship` WHERE `product_id` = '" . $ecatalog_query_filters['product_id'] . "'";
        $result = $db->query( $sql_qry );
      	/*echo "SELECT * FROM `products_categories_relationship` WHERE `product_id` = '" . $ecatalog_query_filters['product_id'] . "'";*/
        while ($row = $db->fetch($result, 'assoc')) {
            $ecatalog_query_filters['id'][] = $row['category_id'];
        }
        unset($ecatalog_query_filters['product_id']);
    }
    /*
      if (isset($ecatalog_query_filters['posts_per_page'])) {
      $posts_per_page = $ecatalog_query_filters['posts_per_page'];
      unset($ecatalog_query_filters['posts_per_page']);
      }
     * 
     */
    $additional_filters_sql_array = array();


    foreach ($ecatalog_query_filters as $key => $value) {
        if ((array) $value == $value) {
            $additional_filters_sql_array[] = "`$key` IN('".implode("','",$value)."')";
        } else {
            $additional_filters_sql_array[] = "`$key` = '$value'";
        }
    }


    $additional_filters_sql = implode(' AND ', $additional_filters_sql_array);
    $additional_filters_sql = count($additional_filters_sql_array) > 0 ? 'Where ' . $additional_filters_sql."" : '';

    $limit_sql = $posts_per_page == null ? '' : 'Limit ' . $posts_per_page;
    $additional_filters_sql .= $additional_filters_sql == '' ? 'WHERE hide_category = "N"': "AND `hide_category` = 'N' ";
    $ecatalog_categories = array();
    $lang = cms_get_language();
    $prod_cat_sql = "SELECT `product_categories`.*, `t`.`meta` `translate` FROM `product_categories` LEFT JOIN `cms_translation` `t` ON `t`.`guid` = `product_categories`.`id` and `language` = '{$lang}' and `type` = 'product-category'";

    $result = $db->query("SELECT * FROM ({$prod_cat_sql}) `product_categories` $additional_filters_sql ORDER BY `sort_order` $limit_sql ");

    while ($row = $db->fetch($result, 'assoc')) {
    	if (isset($row['translate'])) {
    		$t = json_decode($row['translate']);
    		if (isset($t->category_name)) {
    			$row['category_name'] = $t->category_name;
    		}
            if (isset($t->category_description)) {
                $row['category_description'] = $t->category_description;
            }
    		if (isset($t->url_slug)) {
    			$row['url_slug'] = $t->url_slug;
    		}
    	}
    	$ecatalog_categories[] = $row;
    }

    return $ecatalog_categories;
}
/**
* ecatalog_get_categories will return based on categories and product_id
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <?php
*   $breadcrumb_categories = ecatalog_get_product_category_hierarchy($ecatalog_category['id']);
* ?>
* </code>
* @return array | array categories
*/
function ecatalog_get_product_category_hierarchy($category_id) {
    global $db;
    $category_hierarchy = array();
    $categories = ecatalog_get_categories(array('id' => $category_id));
    $category = $categories[0];
    $category_hierarchy[] = $category;
    while ($category['category_parent'] != 0) {
        $categories = ecatalog_get_categories(array('id' => $category['category_parent']));
        $category = $categories[0];
        $category_hierarchy[] = $category;
    }
    return array_reverse($category_hierarchy);
}
/**
* ecatalog_get_product_category_link will query based on categories and product_id
* Note: ecatalog_query_products should be called first
* Here is an inline example:
* <code>
* <html>
*   <a href="<?php echo ecatalog_get_product_category_link($breadcrumb_category['id']); ?>" title="<?php echo $breadcrumb_category['category_name']; ?>">Sample</a>
* </html>
* </code>
* @return array | array categories links
*/
function ecatalog_get_product_category_link($category_id) {
    $category_slug_hierarchy = array_map(function($item) {
        return $item['url_slug'];
    }, ecatalog_get_product_category_hierarchy($category_id));

    return get_bloginfo('installation_url') . '/product-category/' . implode('/', $category_slug_hierarchy);
}
/**
* ecatalog_get_latest_products will return last 5 added products
* Here is an inline example:
* <code>
* <?php
*     $latest_products = ecatalog_get_latest_products();
* ?>
* </code>
* @return array | array last 5 added products
*/
function ecatalog_get_latest_products() {
    $rows = ecatalog_get_products(array('posts_per_page' => 5));

    // var_dump($rows);
    return $rows;
}

/**
* ecatalog_get_related_products will return 3 latest related products based on category of the current product
* Here is an inline example:
* <code>
* <?php
*      $related_products = ecatalog_get_related_products();
* ?>
* </code>
* @return array | 3 products
*/

function ecatalog_get_related_products() {
    global $ecatalog_current_product;
    $rows = array();
    $product_ids = ecatalog_get_related_product_id($ecatalog_current_product['id']);

    for ($i = 0; $i < count($product_ids); $i++) {
        $rows[] = ecatalog_get_product($product_ids[$i]);
    }

    return $rows;
}

/**
* ecatalog_get_related_product_id will return 3 latest related products id based on the current product id
* Here is an inline example:
* <code>
* <?php
*     $product_ids = ecatalog_get_related_product_id($ecatalog_current_product['id']);
* ?>
* </code>
* @return array | product ids
*/

function ecatalog_get_related_product_id($product_id) {
    global $db;

    $product_ids = array();
    $table = 'products_categories_relationship';
    $qry = $db->query("SELECT * FROM " . $table . " WHERE `product_id` = '$product_id'");

    if ($qry) {
        $limit =  get_system_option(array('option_name' => 'listing_page_display_related_items_count'));
        if($limit == 0){
            $limit = 5;
        }
        while ($category = $db->fetch($qry, 'array')) {
            $category_id = $category['category_id'];

            $qry_pcr = $db->query("SELECT * FROM " . $table . " WHERE `category_id` = '$category_id' LIMIT ".$limit);

            if ($qry_pcr)
                while ($row = $db->fetch($qry_pcr, 'array'))
                    $product_ids[] = $row['product_id'];
        }
    }

    return $product_ids;
}
/**
* ecatalog_footer will print system option website_footer_copyright_text
* how to use it:  ecatalog_footer();
* @return array | product ids
*/
function ecatalog_footer(){
    echo get_system_option(array('option_name' => 'website_footer_copyright_text'));
}
/**
* ecatalog_get_product_tabs will return the product tabs for product description
* Here is an inline example:
* <code>
* <hmtl>
*      <span><?php ecatalog_footer(); ?></span>
* </html>
* </code>
* @return array | product tabs
*/
function ecatalog_get_product_tabs(){
    global $db;
    global $ecatalog_current_product;

    $id = $ecatalog_current_product['id'];
    $rows = array();

    $prod_tab_sql = "SELECT * FROM `product_tabs` WHERE `product_id` = '$id' ORDER BY `sort_order`";
    $qry = $db->query( $prod_tab_sql );

    if($qry){
        $lang = cms_get_language();
        $prod_tab_translated = $db->select("Select * From `cms_translation` `t` WHERE `t`.`guid` = '{$id}' and `t`.`language` = '{$lang}' and type = 'product-tab'" );
        $tab_trans = array();
        if (count($prod_tab_translated)) {
            $tab_trans = json_decode($prod_tab_translated[0]->meta);
        }

      while($row = $db->fetch($qry,'array')){
        if (isset($tab_trans->$row['id'])) {
            $row['tab_title'] = $tab_trans->$row['id']->title;
            $row['tab_content'] = $tab_trans->$row['id']->content;
        }

        $rows[] = $row;
      }
    }

    return $rows;
}
/**
* ecatalog_get_product_tabs will return the product tabs for product description
* Here is an inline example:
* <code>
* <hmtl>
*      <span><?php ecatalog_get_product_custom_fields(); ?></span>
* </html>
* </code>
* @return array | product tabs
*/
function ecatalog_get_product_custom_fields(){
    global $db;
    global $ecatalog_current_product;

    $id   = $ecatalog_current_product['id'];
    $lang = cms_get_language();
    $rows = array();

    $custom_field_set = "SELECT * FROM `product_items` WHERE `guid` = '$id' and `language` = '{$lang}' and type = 'custom-field' ORDER BY `id`";
    $qry = $db->query( $custom_field_set );
    $custom_fields = array();

    if($qry){
        while($row = $db->fetch($qry,'array')){
            $meta = _ecatalog_get_product_custom_fields_(json_decode($row['meta']));

            foreach ($meta as $key => $value) {
                $custom_fields[$value->key] = $value;
                if ($value->type == 'textarea' || $value->type == 'textarea-simple') {
                    $custom_fields[$value->key]->value = cms_post_content_processor($value->value);
                }
            }
        }
    }

    return $custom_fields;
}
function _ecatalog_get_product_custom_fields_($data = array()){
    $d = array();

    foreach ($data as $key => $value) {
        if (isset($value->type)) {
            # code...
            $d[$value->key] = $value;
        }else{
            foreach ($value as $k => $v) {
                foreach (_ecatalog_get_product_custom_fields_($v) as $kk => $vv) {
                    $d[$vv->key] = $vv;
                }
            }
        }
    }

    return $d;
}
function ecatalog_get_product_custom_field($field_name = null){
    $cf_value = '';

    if ($field_name) {
        global $ecatalog_current_product;
        // cms_post_content_processor($cf_value);
        if (isset($ecatalog_current_product['custom_fields'])) {
            $cf_item = isset($ecatalog_current_product['custom_fields'][$field_name]) ? $ecatalog_current_product['custom_fields'][$field_name] : null;

            if ($cf_item) {
                $cf_item->value = gettype($cf_item->value) == 'object' ? (array) $cf_item->value : $cf_item->value;
                if ($cf_item->type == 'textarea' || 
                    $cf_item->type == 'textarea-simple') {
                    $cf_value = cms_post_content_processor($cf_item->value);
                }elseif ($cf_item->type == 'gallery') {
                    $cf_value = array();
                    foreach ($cf_item->value as $key => $value) {
                        $cf_value[] = (array) $value;
                    }
                }else{
                    $cf_value = $cf_item->value;
                }
            }
        }
    }

    return $cf_value;
}

function ecatalog_get_product_billing_period(){
    global $db;
    global $ecatalog_current_product;

    $id   = $ecatalog_current_product['id'];

    /* Get Billing Period Settings */
    $sql = "SELECT * FROM product_items WHERE type = 'billing-period-setting' and guid = '{$id}'";
    $setting = $db->select( $sql );
    if (count($setting)) {
        $setting = json_decode($setting[0]->meta, true);
    }

    $billing_info = array(
        'enable'        => isset($setting['enable']) ? $setting['enable'] : true,
        'type'          => isset($setting['type']) ? $setting['type'] : 'One-time',
        'product_type'  => isset($setting['product_type']) ? $setting['product_type'] : 'Physical Goods',
        'product_files' => isset($setting['product_files']) ? $setting['product_files'] : 'Physical Goods',
    );

    /* Set Subscription List */
    $billing_period = array();
    if ($billing_info['type'] == 'Subscription') {
        $billing_period_sql = "SELECT  products.id product_id, 
                cms_items.id cms_item_id, 
                product_items.id product_item_id, 
                products.product_name product_name, 
                products.product_description product_description, 
                products.price price, 
                cms_items.meta default_meta, 
                product_items.meta product_meta,
                cms_items.value period_name
            FROM products 
            Left JOIN cms_items ON cms_items.type = 'billing-period-default-item'
            Left JOIN product_items ON product_items.value = cms_items.id and product_items.type = 'billing-period-item' and products.id = product_items.guid
            WHERE products.id = '{$id}'";

        $res = $db->select( $billing_period_sql );
        foreach ($res as $key => $value) {
            $default_meta = json_decode($value->default_meta, true);
            $product_meta = json_decode($value->product_meta, true);

            $d = ecatalog_get_product_billing_period_set_values($value);

            if ($d['enable'] == "YES") {
                $billing_period[] = ecatalog_get_product_billing_period_set_values($value);
            }
        }
    }

    /* Get Selected Global Billing */
    $global_subscription = array();

    if ($billing_info['type'] == 'Global Subscription') {
        $res = $db->select( "SELECT * FROM cms_items WHERE type='paypal-subscription-plan'" );

        $global_subscription_list = array();
        foreach ($res as $key => $value) {
            $global_subscription_list[$value->id]  = json_decode( $value->meta, true );
        }

        $g_list = $setting['required_subs'];

        foreach ($g_list as $key => $value) {
            if (isset($global_subscription_list[$value])) {
                $global_subscription[] = $global_subscription_list[$value];
            }
        }
    }

    $output = array(
        "billing_info"  => $billing_info,
        "subscriptions" => $billing_period,
        "global_subscriptions" => $global_subscription,
    );

    return $output;
}
function ecatalog_get_product_billing_period_set_values($value = array(), $default_meta_over=array(), $product_meta_over=array() ){
    $default_settings = ecatalog_get_product_billign_period_get_default_settings();

    $default_meta = json_decode($value->default_meta, true); //default_meta
    $product_meta = json_decode($value->product_meta, true); //product_meta

    if (count($default_meta_over) > 0) { $default_meta = $default_meta_over; }
    if (count($product_meta_over) > 0) { $product_meta = $product_meta_over; }

    $product_name = isset($value->product_name) ? $value->product_name : "unknown";
    $product_desc   = isset($value->product_description) ? $value->product_description : "";
    $period_name    = isset($value->period_name) ? $value->period_name : "Unknown Period";

    $defaults = array(
        "plan_name"             => "{$product_name} ({$period_name})",
        "plan_description"=> strip_tags($product_desc),
        "plan_url_return" => isset($default_settings['prod_subs_default_return']) ? $default_settings['prod_subs_default_return'] : "",
        "plan_url_cancel" => isset($default_settings['prod_subs_default_cancel']) ? $default_settings['prod_subs_default_cancel'] : "",
        "plan_type"             => isset($default_settings['prod_subs_default_type']) ? $default_settings['prod_subs_default_type'] : "INFINITE",
        "plan_auto_billing" => isset($default_settings['prod_subs_default_auto_billing']) ? $default_settings['prod_subs_default_auto_billing'] : "YES",
        "plan_max_fail_attempts" => isset($default_settings['prod_subs_default_max_fail_attempts']) ? $default_settings['prod_subs_default_max_fail_attempts'] : '1',
        "plan_initial_fail_action" => isset($default_settings['prod_subs_default_initial_fail_action']) ? $default_settings['prod_subs_default_initial_fail_action'] : '1',

        "title"                     => isset($default_meta['title']) ? $default_meta['title'] : "Regular",
        "frequency"             => isset($default_meta['frequency']) ? $default_meta['frequency'] : "MONTH",
        "frequency_interval" => isset($default_meta['frequency_interval']) ? $default_meta['frequency_interval'] : "1",
        "cycle"                     => isset($default_meta['cycle']) ? $default_meta['cycle'] : "0",
        "enable_trial"      => isset($default_meta['enable_trial']) ? $default_meta['enable_trial'] : "NO",
        "title_trial"       => isset($default_meta['title_trial']) ? $default_meta['title_trial'] : "Trial",
        "amount_trial"      => isset($default_meta['amount_trial']) ? $default_meta['amount_trial'] : "0",
        "frequency_trial" => isset($default_meta['frequency_trial']) ? $default_meta['frequency_trial'] : "MONTH",
        "frequency_interval_trial" => isset($default_meta['frequency_interval_trial']) ? $default_meta['frequency_interval_trial'] : "1",
        "cycle_trial"       => isset($default_meta['cycle_trial']) ? $default_meta['cycle_trial'] : "1",
        "default"               => isset($default_meta['default']) ? $default_meta['default'] : "YES",
        "enable"                    => isset($default_meta['enable']) ? $default_meta['enable'] : "NO",
        "amount"                    => number_format($value->price, 2, '.',''),
        "agreement_name"    => isset($default_meta['prod_subs_default_agreement_name']) ? $default_meta['prod_subs_default_agreement_name'] : "",
        "agreement_desc"    => isset($default_meta['prod_subs_default_agreement_description']) ? $default_meta['prod_subs_default_agreement_description'] : "",
    );

    $billing_period = array(
        "plan_name"             => isset($product_meta['plan_name']) ? $product_meta['plan_name'] : $defaults['plan_name'],
        "plan_description"=> isset($product_meta['plan_description']) ? $product_meta['plan_description'] : $defaults['plan_description'],
        "plan_url_return" => isset($product_meta['plan_url_return']) ? $product_meta['plan_url_return'] : $defaults['plan_url_return'],
        "plan_url_cancel" => isset($product_meta['plan_url_cancel']) ? $product_meta['plan_url_cancel'] : $defaults['plan_url_cancel'],
        "plan_type"             => isset($product_meta['plan_type']) ? $product_meta['plan_type'] : $defaults['plan_type'],
        "plan_auto_billing" => isset($product_meta['plan_auto_billing']) ? $product_meta['plan_auto_billing'] : $defaults['plan_auto_billing'],
        "plan_max_fail_attempts" => isset($product_meta['plan_max_fail_attempts']) ? $product_meta['plan_max_fail_attempts'] : $defaults['plan_max_fail_attempts'],
        "plan_initial_fail_action" => isset($product_meta['plan_initial_fail_action']) ? $product_meta['plan_initial_fail_action'] : $defaults['plan_initial_fail_action'],

        "title"                     => isset($product_meta['title']) ? $product_meta['title'] : $defaults['title'],
        "frequency"             => isset($product_meta['frequency']) ? $product_meta['frequency'] : $defaults['frequency'],
        "frequency_interval" => isset($product_meta['frequency_interval']) ? $product_meta['frequency_interval'] : $defaults['frequency_interval'],
        "cycle"                     => isset($product_meta['cycle']) ? $product_meta['cycle'] : $defaults['cycle'],
        "enable_trial"      => isset($product_meta['enable_trial']) ? $product_meta['enable_trial'] : $defaults['enable_trial'],
        "title_trial"       => isset($product_meta['title_trial']) ? $product_meta['title_trial'] : $defaults['title_trial'],
        "amount_trial"      => isset($product_meta['amount_trial']) ? $product_meta['amount_trial'] : $defaults['amount_trial'],
        "frequency_trial" => isset($product_meta['frequency_trial']) ? $product_meta['frequency_trial'] : $defaults['frequency_trial'],
        "frequency_interval_trial" => isset($product_meta['frequency_interval_trial']) ? $product_meta['frequency_interval_trial'] : $defaults['frequency_interval_trial'],
        "cycle_trial"       => isset($product_meta['cycle_trial']) ? $product_meta['cycle_trial'] : $defaults['cycle_trial'],
        "default"               => isset($product_meta['default']) ? $product_meta['default'] : $defaults['default'],
        "enable"                    => isset($product_meta['enable']) ? $product_meta['enable'] : $defaults['enable'],
        "amount"                    => isset($product_meta['amount']) ? $product_meta['amount'] : $defaults['amount'],
        "agreement_name"    => isset($product_meta['agreement_name']) ? $product_meta['agreement_name'] : $defaults['agreement_name'],
        "agreement_desc"    => isset($product_meta['agreement_desc']) ? $product_meta['agreement_desc'] : $defaults['agreement_desc'],
    );

    return $billing_period;
}
function ecatalog_get_product_billign_period_get_default_settings(){
    global $db;

    $sql = "SELECT * FROM payment_gateway_options WHERE option_name LIKE 'prod_subs_default_%'";
    $res = $db->select( $sql );

    $default_settings = array();

    foreach ($res as $key => $value) {
        $default_settings[$value->option_name] = $value->option_value;
    }

    return $default_settings;
}
/**
* ecatalog_cart_add_product will add the product into the cart using sesssion
* Here is an inline example:
* <code>
* <?php
*     ecatalog_cart_add_product($product,$quantity);
* ?>
* </code>
* @return void
*/
function ecatalog_cart_add_product($product, $quantity){
$_SESSION['ecatalog_product_enquiry'] = isset($_SESSION['ecatalog_product_enquiry']) === FALSE ? array() : $_SESSION['ecatalog_product_enquiry'];
    
    $_SESSION['ecatalog_product_enquiry'][$product['id']]['product'] = $product;
    $_SESSION['ecatalog_product_enquiry'][$product['id']]['quantity'] = $quantity;
}
/**
* ecatalog_cart_delete_product will delete the product into the cart using sesssion
* Here is an inline example:
* <code>
* <?php
*     ecatalog_cart_delete_product($id);
* ?>
* </code>
* @return void
*/
function ecatalog_cart_delete_product($id){
     unset($_SESSION['ecatalog_product_enquiry'][$id]);
}
/**
* ecatalog_cart_get_products will return the product into the cart using sesssion
* Here is an inline example:
* <code>
* <?php
*     foreach ($_SESSION['ecatalog_product_enquiry'] as $key => $product_obj) {}
* ?>
* </code>
* @return array | products in cart
*/
function ecatalog_cart_get_products(){
    
    if(isset($_SESSION['ecatalog_product_enquiry'])===false)
        return array();

    return $_SESSION['ecatalog_product_enquiry'];
}
/**
* ecatalog_cart_get_products will return the product into the cart using sesssion
* Here is an inline example:
* <code>
* <?php
*     ecatalog_cart_clear_products();
* ?>
* </code>
* @return array | products in cart
*/
function ecatalog_cart_clear_products(){
unset($_SESSION['ecatalog_product_enquiry']);
}
/**
* get_product_order_display will return the product order query
* Here is an inline example:
* <code>
* <?php
*     $order = get_product_order_display($order);
*     $qry = $db->query("SELECT * FROM `products` WHERE match(`product_name`) against ('$keyword') AND `product_status` = 'active' ".$order);
* ?>
* </code>
* @return string | query order
*/
function get_product_order_display($order){
 switch ($order) {
        case 'price_asc':
            $order = "ORDER BY `price` ASC";
            break;
        case 'price_desc':
            $order = "ORDER BY `price` DESC";
            break;
        case 'featured_desc':
            $order = "ORDER BY `featured_product` ASC";
            break;
        case 'featured_asc':
            $order = "ORDER BY `featured_product` DESC";
            break;
        case 'product_title_asc':
            $order = "ORDER BY `product_name` ASC";
            break;
        case 'product_title_desc':
            $order = "ORDER BY `product_name` DESC";
            break;
        default:
            $order = '';

    } 
    return $order;     
}
/**
* get_product_order_display will return the product order query
* Here is an inline example:
* <code>
* <?php
*     $search_results = ecatalog_get_search_products($input,$order);
* ?>
* </code>
* @return string | query order
*/
function ecatalog_get_search_products($keyword,$order){
    global $db;
    $rows = array();
    $order = get_product_order_display($order);
   
    $qry = $db->query("SELECT * FROM `products` WHERE match(`product_name`) against ('$keyword') AND `product_status` = 'active' ".$order);

    if($qry)
        while($row = $db->fetch($qry, 'array'))
            $rows[] = $row;

    return $rows;

}
/**
* ecatalog_get_default_view will return the default view
* Here is an inline example:
* <code>
* <?php
*      $default_view = ecatalog_get_default_view();
* ?>
* </code>
* @return string | default view
*/
function ecatalog_get_default_view(){
    global $db;
    $qry = $db->query("SELECT * FROM `system_options` WHERE `option_name` = 'category_page_display_view' ");

    $row = $db->fetch($qry, 'array');

    if($row['option_value'] == 'LIST_ONLY')
        return 'hide_others';
    else if($row['option_value'] == 'LIST')
        return '-list';

    return '-grid';


}
/**
* ecatalog_get_product_display_order will return the display order
* ecatalog_get_default_view will return the default view
* Here is an inline example:
* <code>
* <?php
*      $order = ecatalog_get_product_display_order('');
* ?>
* </code>
* @return string | display order
*/
function ecatalog_get_product_display_order($order){
    global $db;
    $order = $order;
    if($order == ' '){
        $order = get_system_option(array('option_name' => 'category_page_display_order'));
    }
    switch($order){
        case 'PRICE_HIGH_TO_LOW':
            $order = 'price_desc';
            break;
        case 'PRICE_LOW_TO_HIGH':
            $order = 'price_asc';
            break;
        case 'FEATURED_LISTING_TOP':
            $order = 'featured_desc';
            break;
        case 'FEATURED_LISTING_BOTTOM':
            $order = 'featured_asc';
            break;
        case 'PRODUCT_TITLE_A_Z':
            $order = 'product_title_asc';
            break;
        default:
            $order = 'product_title_desc';
    }
    return $order;
}
/**
* ecatalog_convert_to_option will return coverted display order
* Here is an inline example:
* <code>
* <html>
*     <a href="#" ><?php echo ecatalog_convert_to_option(SESSION::get('order_type')); ?></a>
* </html>
* </code>
* @return string | coverted display order
*/
function ecatalog_convert_to_option($order){
    switch($order){
        case 'price_desc':
            $order = 'Price (High - Low)';
            break;
        case 'price_asc':
            $order = 'Price (Low - High)';
            break;
        case 'featured_desc':
            $order = 'Featured Listing (Top)';
            break;
        case 'featured_asc':
            $order = 'Featured Listing (Bottom)';
            break;
        case 'product_title_asc':
            $order = 'Product Title (A to Z)';
            break;
        default:
            $order = 'Product Title (Z to A)';
    }

    return $order;
}
/**
* ecatalog_highlight set session if new product is added to cart
* Here is an inline example:
* <code>
* <?php
*     ecatalog_highlight();
* ?>
* </code>
* @return void
*/
function ecatalog_highlight(){
    $_SESSION['update_cart'] = true;
}
/**
* ecatalog_unhighlight unset session 
* Here is an inline example:
* <code>
* <?php
*     ecatalog_unhighlight();
* ?>
* </code>
* @return void
*/
function ecatalog_unhighlight(){
    unset( $_SESSION['update_cart']);
}
/**
* ecatalog_add_contact_form_7 will add data based on submitted contact forms
* Here is an inline example:
* <code>
* <?php
*     if(ecatalog_add_contact_form_7($form_data)){}
* ?>
* </code>
* @return boolean
*/
function ecatalog_add_contact_form_7($form_data){
    global $db;
    $form_id =  $_SESSION['form_id'];
    
    $qry_current_form_data = $db->query("SELECT * FROM `contact_form_7_forms_collected_data` WHERE `form_id` = '$form_id'");
    if($db->numRows($qry_current_form_data) > 0){
        $row = $db->fetch($qry_current_form_data,'array');
        $json_data = json_decode($row['form_data'], true);
        $form_data = stripslashes($form_data);
        $json_decode = json_decode(trim($form_data),true);
        $json_data['rows'][] = $json_decode;
        $json_new_data = json_encode($json_data);
        
        $qry_form_data = $db->query("UPDATE `contact_form_7_forms_collected_data` SET `form_data`= '$json_new_data' WHERE `id` = {$row['id']}");
    }else{

        $json_new_data = array();
        $json_new_data['rows'] = array();
        $form_data = stripslashes($form_data);
        $json_decode = json_decode(trim($form_data),true);
        $json_new_data['rows'][] = $json_decode;
        $json_encode = json_encode($json_new_data);
        $json_new_data = $json_encode;

        $qry_form_data = $db->query("INSERT INTO `contact_form_7_forms_collected_data`(`form_id`,`form_data`) VALUES ('$form_id','$json_new_data')");
   }

   if(ecatalog_send_email($form_data) && $qry_form_data)
        return true;

    return false;
}
function ecatalog_send_email($form_data){
    $form_data = stripslashes($form_data);
    $form_data = json_decode(trim($form_data),true);
    $to      = get_system_option(array('option_name' => 'enquiry_form_email'));
    $subject = $form_data["subject"];
    $message = $form_data["your-message"];
    $headers = 'From:'.$form_data["your-name-(required)"] . "\r\n" .
        'Reply-To:'.$form_data["your-email-(required)"].''. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

   /* print_r($form_data);

    echo $to.' = '.$subject.' = '.$message.' = '.$headers;*/
    return mail($to, $subject, $message, $headers);
   // return true;
}
function ecatalog_get_payment_gateways(){
    global $db;

    $qry = $db->query("SELECT * FROM `payment_gateway`");
    $rows = array();

    while($row = $db->fetch($qry, 'array'))
        $rows[] = $row;

    return $rows;

}

function ecatalog_get_payment_getway_options($option_name){

    global $db;

    $qry = $db->query("SELECT * FROM `payment_gateway_options` where `option_name` = '$option_name'");

    $row = $db->fetch($qry, 'array');

    return $row['option_value'];
}

function ecatalog_get_next_invoice_number(){

   $invoice_number = get_system_option(array('option_name' => 'invoice_next_number'));
   $invoice_prefex = get_system_option(array('option_name' => 'invoice_number_prefix'));

   return $invoice_prefex.$invoice_number;

}

function ecatalog_update_invoice_number(){
    global $db;
    $invoice_number = get_system_option(array('option_name' => 'invoice_next_number'));
    $invoice_number += 1;
    
    $db->query("UPDATE `system_options` SET `option_value` = '$invoice_number' WHERE `option_name` = 'invoice_next_number'");


  
}

function get_ecatalog_cart_count($display_unit, $unit_label){

 if(isset($_SESSION['ecatalog_product_enquiry']))
     if(!$display_unit)
        return count($_SESSION['ecatalog_product_enquiry']);
     else
        return count($_SESSION['ecatalog_product_enquiry']).' '.$unit_label.(count($_SESSION['ecatalog_product_enquiry']) > 1 ? 's' : '');
else
    return '0 items';



}


function ecatalog_cart_count($display_unit, $unit_label){
    echo get_ecatalog_cart_count($display_unit, $unit_label);
}

function ecatalog_event_cart_updated(){
    
    if(isset($_SESSION['update_cart']))
        return true;

    return false;
}

function ecatalog_get_cart_items(){

    if(isset($_SESSION['ecatalog_product_enquiry']))
        return $_SESSION['ecatalog_product_enquiry'];

    return array();
}



/**
* Fetches text content of email template
*
* @return string
*/
function ecatalog_get_order_email_template(){
	$theme_payment_page = ROOT . "views/themes/". ACTIVE_THEME . "/email-templates/order-confirmed-email-template.html";
	if (is_file($theme_payment_page)) {
		return file_get_contents($theme_payment_page);
	}else{
		return "";
	}
}

/**  
* Get order info by id
*
* @param $id
* @return array
*/
function ecatalog_get_order_info($id){
	global $db;
	$sql = "SELECT * FROM `orders` WHERE id = $id";
	return $db->select($sql)[0];
}
/** 
* Get order info by id
*
* @param $id
* @return array
*/
function ecatalog_get_order_items($id){
	global $db;
	$sql = "SELECT od.*, p.product_description
			FROM order_details as od
			JOIN products as p
			ON od.product_id = p.id
			WHERE od.order_id = $id";
	return $db->select($sql);
}

/**
* Get payment info by id
*
* @param int $id
* @param string $key
* @return mixed
*/
function ecatalog_get_payment_info($id, $key = '*'){
	global $db;
	$sql = "SELECT $key FROM payment_gateway WHERE id = $id";

	if ($key === '*')
		return $db->select($sql)[0];
	
	return $db->select($sql)[0]->$key;
}
/**
* Formats number into currency format with two decimal points
*
* @param int $num
* @return string
*/
function currency($num, $prefix = '$'){
	echo $prefix . number_format($num, 2, '.', ',');
}
function ecatalog_get_currency($num, $prefix = '$'){
	return $prefix . number_format($num, 2, '.', ',');
}

function ecatalog_remove_first_and_last_ptag($string){
	return preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $string);
}

/**
* Get tax rate in percentage (decimal)
*
* @return float
*/
function ecatalog_get_tax_rate(){
	return floatval( get_system_option('tax_rate') / 100 );
}

### ECOMMERCE
/**
* Sends order information via email to customer.
*
* @param int $order_id
* @return void
*/
function ecatalog_send_order_details_to_client($order_id){
	if (function_exists('send_order_details_to_client')) {
		send_order_details_to_client($order_id);
	}else{
		$template = ecatalog_get_order_email_template(); // getting the template markup
		$info = ecatalog_get_order_info($order_id); // order info
		$items = ecatalog_get_order_items($order_id); // order cart items
		$payment_details = ecatalog_get_payment_info($info->payment_method_id); // payment details
		$shipping_method = json_decode($info->meta_data); // shipping method details
		// header_json(); print_r($info); exit();

		$items_regex = '/\<\!\-\- item_start \-\-\>([\s\S]+?)\<\!\-\- item_end \-\-\>/';
		if (preg_match_all($items_regex, $template, $matches)){
			$search = $matches[0][0];
			$replacements = $matches[1][0];

			$proccessed_markup = '';
			$grand_total = 0;
			foreach( $items as $key => $item ){
				$temp = $replacements;
				$temp = str_replace('{{ item_name }}', $item->item_name, $temp);
				$temp = str_replace('{{ item_quantity }}', $item->quantity, $temp);
				$temp = str_replace('{{ item_description }}', ecatalog_remove_first_and_last_ptag($item->product_description), $temp);
				$temp = str_replace('{{ item_price }}', ecatalog_get_currency($item->price), $temp);
				$subtotal = floatval($item->price) * floatval($item->quantity);
				$grand_total = $grand_total + $subtotal;
				$temp = str_replace('{{ item_subtotal }}', ecatalog_get_currency($item->price), $temp);
				$proccessed_markup .= $temp;
			}

			$template = str_replace($search, $proccessed_markup, $template);
		}

		$data = [
			'order_id'						=> $order_id,
			'company_email'				=> get_system_option('company_email'),
			'company_contact_number'	=> get_system_option('company_contact_number'),
			'last_name'						=> $info->last_name,
			'first_name'					=> $info->first_name,
			'email'								=> $info->email,
			'phone'								=> $info->phone,
			'company'							=> $info->company,
			'billing_address'			=> $info->billing_address,
			'billing_address_2'		=> $info->billing_address_line_2,
			'billing_city'				=> $info->billing_city,
			'billing_country'			=> $info->billing_country,
			'billing_postal'			=> $info->billing_postal,
			'shipping_address'		=> $info->shipping_address,
			'shipping_address_2'	=> $info->shipping_address_line_2,
			'shipping_city'				=> $info->shipping_city,
			'shipping_country'		=> $info->shipping_country,
			'shipping_postal'			=> $info->shipping_postal,
			'shipping_method'			=> isset($shipping_method->rate_name) ? $shipping_method->rate_name : "",
			'gross_total'					=> ecatalog_get_currency($grand_total),
			'delivery_charge'			=> ecatalog_get_currency(isset($shipping_method->rate_amount)?$shipping_method->rate_amount:0),
			'tax_rate'						=> get_system_option('tax_rate'),
			'tax_percentage'			=> ecatalog_get_currency($grand_total * ecatalog_get_tax_rate()),
			'net_total'						=> ecatalog_get_currency($grand_total + (isset($shipping_method->rate_amount) ? $shipping_method->rate_amount : 0)),
			'payment_method'			=> $payment_details->display_name,
		];

		foreach( $data as $key => $value ){
			$template = str_replace('{{ '.$key.' }}', $value, $template);
		}

		# HEADERS
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: '.$info->first_name.' '.$info->last_name.' <'.$info->email.'>' . "\r\n";
		$headers .= 'From: '.get_system_option('company_name').' <'.get_system_option('system_email').'>' . "\r\n";

		mail($info->email, get_system_option('company_name') . ' - Your Order Summary', $template, $headers);
	}
}