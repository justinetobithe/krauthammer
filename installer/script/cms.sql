DROP TABLE IF EXISTS `album`;
CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_name` varchar(255) DEFAULT NULL,
  `description` text,
  `guid` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `meta` text NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  `sort_order` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `album_photos`;
CREATE TABLE `album_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL,
  `album_id` int(11) NOT NULL,
  `meta` text NOT NULL,
  `status` enum('active','deleted') NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `product_appointment_id` int(11) NOT NULL,
  `country` varchar(50) NOT NULL,
  `contact_no` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `special_request` text NOT NULL,
  `date_reserve` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cms_contact_forms`;
CREATE TABLE `cms_contact_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `form_code` text,
  `mail_1_to` varchar(150) DEFAULT NULL,
  `mail_1_from` varchar(150) DEFAULT NULL,
  `mail_1_subject` varchar(150) DEFAULT NULL,
  `mail_1_additional_headers` text,
  `mail_1_message_body` text,
  `mail_1_use_html_content_type` enum('Y','N') NOT NULL DEFAULT 'N',
  `mail_2_to` varchar(150) DEFAULT NULL,
  `mail_2_from` varchar(150) DEFAULT NULL,
  `mail_2_subject` varchar(150) DEFAULT NULL,
  `mail_2_additional_headers` text,
  `mail_2_message_body` text,
  `mail_2_use_html_content_type` enum('Y','N') NOT NULL DEFAULT 'N',
  `mail_2_enabled` enum('Y','N') NOT NULL DEFAULT 'N',
  `enable_captcha` enum('Y','N') NOT NULL DEFAULT 'Y',
  `message_notifiactions` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cms_files`;
CREATE TABLE `cms_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` text,
  `url` text,
  `type` varchar(255) DEFAULT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `meta` text,
  `status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cms_items`;
CREATE TABLE `cms_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` int(11) DEFAULT '0',
  `type` varchar(255) DEFAULT NULL,
  `value` text,
  `meta` text,
  `status` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cms_posts`;
CREATE TABLE `cms_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_author` int(11) DEFAULT '0',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_content` longtext,
  `post_title` text,
  `post_excerpt` text,
  `post_status` enum('active','trashed') NOT NULL DEFAULT 'active',
  `post_type` enum('post','page') NOT NULL DEFAULT 'post',
  `url_slug` varchar(255) DEFAULT NULL,
  `old_slug` varchar(255) NOT NULL DEFAULT '',
  `seo_canonical_url` text,
  `page_template` varchar(50) DEFAULT NULL,
  `seo_title` text,
  `seo_description` text,
  `seo_no_index` enum('Y','N') DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `status` enum('publish','draft') NOT NULL DEFAULT 'draft',
  `featured_image` text,
  `featured_image_crop` text,
  `featured_image_crop_data` text,
  `meta_data` text,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`id`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cms_posts_archived`;
CREATE TABLE `cms_posts_archived` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `post_content` longtext,
  `post_title` text,
  `post_excerpt` text,
  `status` enum('publish','draft') DEFAULT NULL,
  `url_slug` varchar(50) DEFAULT NULL,
  `page_template` varchar(50) DEFAULT NULL,
  `seo_title` text,
  `seo_description` text,
  `seo_no_index` enum('Y','N') NOT NULL DEFAULT 'N',
  `parent_id` int(111) DEFAULT '0',
  `post_categories` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=769 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cms_posts_translate`;
CREATE TABLE `cms_posts_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `language` varchar(12) DEFAULT NULL,
  `post_author` int(11) NOT NULL DEFAULT '0',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_content` longtext,
  `post_title` text,
  `post_excerpt` text,
  `post_status` enum('active','trashed') NOT NULL DEFAULT 'active',
  `post_type` enum('post','page') NOT NULL DEFAULT 'post',
  `url_slug` varchar(255) NOT NULL,
  `old_slug` varchar(255) NOT NULL DEFAULT '',
  `seo_canonical_url` text,
  `page_template` varchar(50) DEFAULT NULL,
  `seo_title` text,
  `seo_description` text,
  `seo_no_index` enum('Y','N') NOT NULL DEFAULT 'N',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` enum('publish','draft') NOT NULL DEFAULT 'publish',
  `featured_image` text,
  `featured_image_crop` text,
  `featured_image_crop_data` text,
  `meta_data` text,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`id`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cms_translation`;
CREATE TABLE `cms_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL,
  `meta` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `content` text,
  `type` varchar(64) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `author_email` varchar(255) NOT NULL DEFAULT '',
  `author_url` text,
  `author_ip` varchar(32) DEFAULT NULL,
  `status` enum('approved','pending','trashed','deleted') NOT NULL DEFAULT 'approved',
  `rate` int(11) DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `contact_form_7_forms_collected_data`;
CREATE TABLE `contact_form_7_forms_collected_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `form_data` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(64) NOT NULL,
  `code` varchar(24) NOT NULL,
  `meta` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `password_reset_hash` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_address_2` text NOT NULL,
  `shipping_postal_code` int(11) NOT NULL,
  `shipping_city` varchar(255) NOT NULL,
  `shipping_country` varchar(255) NOT NULL,
  `shipping_state` varchar(255) NOT NULL,
  `shipping_email` varchar(255) NOT NULL,
  `shipping_phone` varchar(255) NOT NULL,
  `billing_address` text NOT NULL,
  `billing_address_2` text NOT NULL,
  `billing_postal_code` int(11) NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_country` varchar(255) NOT NULL,
  `billing_state` varchar(255) NOT NULL,
  `billing_email` varchar(255) NOT NULL,
  `billing_phone` varchar(255) NOT NULL,
  `different_shipping_address` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('active','pending','trashed','') NOT NULL,
  `sync_status` enum('Y','N') NOT NULL,
  `meta` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `maps`;
CREATE TABLE `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_code` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `width` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL DEFAULT '0',
  `guid` int(11) NOT NULL DEFAULT '0',
  `label` varchar(255) DEFAULT '',
  `url` text,
  `title` varchar(255) NOT NULL DEFAULT '',
  `tag_target` enum('_self','_blank') NOT NULL DEFAULT '_self',
  `description` text,
  `type` enum('page','post','product','link','categories-page','categories-product') NOT NULL DEFAULT 'link',
  `css` text,
  `parent` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `meta` text,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `module_sub_pages`;
CREATE TABLE `module_sub_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `sub_page_name` text NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) NOT NULL,
  `module_label` varchar(255) NOT NULL,
  `meta_data` text NOT NULL,
  `super_admin_only` enum('Y','N') NOT NULL DEFAULT 'N',
  `sort_index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_name` (`module_name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `order_delivery_detail`;
CREATE TABLE `order_delivery_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mode` enum('self-collection','delivery-to-home') NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_date` varchar(255) NOT NULL,
  `delivery_time` varchar(255) NOT NULL,
  `delivery_address` text NOT NULL,
  `delivery_postal` varchar(20) NOT NULL,
  `delivery_type` enum('normal','express') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_option_id` varchar(255) NOT NULL,
  `product_option` varchar(255) NOT NULL,
  `image_url` text NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(9,2) NOT NULL,
  `status` enum('active','trash') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `order_payments`;
CREATE TABLE `order_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_issue_date` datetime NOT NULL,
  `payment_ref_number` varchar(150) NOT NULL,
  `payment_mode_id` int(11) NOT NULL,
  `payment_amount` double NOT NULL,
  `payment_gst` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `payment_total_amount` double NOT NULL,
  `payment_status` int(11) NOT NULL,
  `payment_description` text NOT NULL,
  `voucher_number` int(11) NOT NULL,
  `recieved_by` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `billing_name` varchar(255) NOT NULL,
  `billing_address` text NOT NULL,
  `billing_address_line_2` text NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_postal` int(11) NOT NULL,
  `billing_state` varchar(255) NOT NULL,
  `billing_country` varchar(255) NOT NULL,
  `billing_email` varchar(255) NOT NULL,
  `billing_phone` varchar(255) NOT NULL,
  `shipping_name` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_address_line_2` text NOT NULL,
  `shipping_city` varchar(255) NOT NULL,
  `meta_data` longtext NOT NULL,
  `shipping_postal` int(11) NOT NULL,
  `shipping_state` varchar(255) NOT NULL,
  `shipping_country` varchar(255) NOT NULL,
  `shipping_email` varchar(255) NOT NULL,
  `shipping_phone` varchar(255) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `order_status` enum('active','trashed','cancelled') NOT NULL DEFAULT 'active',
  `order_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `orders_additional`;
CREATE TABLE `orders_additional` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `new_product_name` varchar(150) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `patch`;
CREATE TABLE `patch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('normal','force') NOT NULL DEFAULT 'normal',
  `meta` longtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `patch_log`;
CREATE TABLE `patch_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL DEFAULT 'unknown',
  `description` text,
  `type` enum('normal','force') NOT NULL DEFAULT 'normal',
  `meta` longtext,
  `status` enum('pending','done') NOT NULL DEFAULT 'pending',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_installed` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `payment_gateway`;
CREATE TABLE `payment_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) NOT NULL,
  `gateway_type` enum('PAYPAL_EXPRESS','PAYPAL_STANDARD','OFFLINE_PAYMENT') NOT NULL DEFAULT 'PAYPAL_EXPRESS',
  `tax` enum('Y','N') NOT NULL DEFAULT 'N',
  `enabled` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `payment_gateway_options`;
CREATE TABLE `payment_gateway_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_gateway_id` int(11) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `payment_statuses`;
CREATE TABLE `payment_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(75) NOT NULL,
  `description` text NOT NULL,
  `payment_color` varchar(6) NOT NULL,
  `deletable` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `plugin_api_keys`;
CREATE TABLE `plugin_api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(40) NOT NULL,
  `api_key_description` text NOT NULL,
  `api_key_expiry` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `post_category`;
CREATE TABLE `post_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `category_parent` int(11) NOT NULL,
  `url_slug` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `categories_type` enum('products','e-products') NOT NULL DEFAULT 'products',
  `status` enum('active','trashed') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `posts_categories_relationship`;
CREATE TABLE `posts_categories_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=959 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_addtiontal_files`;
CREATE TABLE `product_addtiontal_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `src` text NOT NULL,
  `sort_index` int(11) NOT NULL,
  `status` enum('trashed','active') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_appointments`;
CREATE TABLE `product_appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `spot` int(11) NOT NULL,
  `sort_index` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_attribute_selection`;
CREATE TABLE `product_attribute_selection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_attribute_id` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  `item_on_sale` enum('yes','no') NOT NULL,
  `sale_price` varchar(255) NOT NULL,
  `calculate_shipping_fee` enum('yes','no') NOT NULL,
  `shipping_fee` varchar(255) NOT NULL,
  `track_inventory` enum('yes','no') NOT NULL,
  `delivery_method` enum('shipped','downloaded') NOT NULL,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE `product_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `label` text NOT NULL,
  `is_color_selection` enum('yes','no') NOT NULL,
  `required` enum('yes','no') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_brands`;
CREATE TABLE `product_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(50) NOT NULL,
  `brand_desc` varchar(255) NOT NULL DEFAULT '',
  `logo_main_url` varchar(255) NOT NULL,
  `logo_alt_url` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_brands_items`;
CREATE TABLE `product_brands_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `model` varchar(50) NOT NULL,
  `weight` varchar(20) NOT NULL,
  `year_released` year(4) NOT NULL,
  `_value` decimal(10,2) NOT NULL,
  `warranty` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(150) DEFAULT NULL,
  `category_parent` int(11) NOT NULL DEFAULT '0',
  `url_slug` varchar(255) NOT NULL,
  `old_slug` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_description` text,
  `sort_order` int(11) DEFAULT NULL,
  `hide_category` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_slug` (`url_slug`),
  KEY `category_parent` (`category_parent`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_items`;
CREATE TABLE `product_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text,
  `meta` text,
  `status` varchar(24) DEFAULT 'Y',
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_option`;
CREATE TABLE `product_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_option_name` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_option_items`;
CREATE TABLE `product_option_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_option_value_labes` varchar(255) NOT NULL,
  `product_option_sku` varchar(255) NOT NULL,
  `product_option_quantity` int(11) NOT NULL,
  `product_option_price` decimal(10,2) NOT NULL,
  `product_option_enable` enum('Y','N') NOT NULL,
  `meta_data` longtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_option_values`;
CREATE TABLE `product_option_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_option_id` int(11) NOT NULL,
  `product_option_values` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `product_tabs`;
CREATE TABLE `product_tabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `tab_title` varchar(100) NOT NULL,
  `tab_content` text NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` text,
  `product_description` longtext,
  `product_status` enum('trashed','active') NOT NULL DEFAULT 'active',
  `product_brand_id` int(11) NOT NULL DEFAULT '0',
  `featured_image_url` text,
  `featured_product` enum('yes','no') NOT NULL DEFAULT 'no',
  `recommended_for_checkout` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `price` decimal(10,2) DEFAULT '0.00',
  `sku` varchar(150) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `min_order_qty` int(11) DEFAULT '0',
  `max_order_qty` int(11) NOT NULL DEFAULT '0',
  `qty_interval` int(11) NOT NULL DEFAULT '0',
  `qty_label` varchar(255) DEFAULT NULL,
  `out_of_stock_status` varchar(150) DEFAULT NULL,
  `product_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `product_viewed` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `url_slug` text NOT NULL,
  `old_slug` text,
  `seo_no_index` enum('Y','N') NOT NULL DEFAULT 'N',
  `seo_description` text,
  `seo_title` text,
  `track_inventory` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `product_type` enum('products','e-products') NOT NULL DEFAULT 'products',
  `meta_data` text,
  `status` enum('publish','draft') NOT NULL DEFAULT 'publish',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `ft_product_name` (`product_name`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `products_categories_relationship`;
CREATE TABLE `products_categories_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=232 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `products_gallery_images`;
CREATE TABLE `products_gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `revolution_sliders`;
CREATE TABLE `revolution_sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_name` varchar(255) NOT NULL,
  `slider_alias` varchar(255) NOT NULL,
  `source_type` enum('POSTS','SPECIFIC_POTS','GALLERY') NOT NULL,
  `slider_layout` enum('FIXED','CUSTOM','AUTO_RESPONSIVE','FULL_SCREEN') NOT NULL,
  `unlimited_height` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `force_full_width` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `grid_width` int(11) NOT NULL,
  `grid_height` int(11) NOT NULL,
  `general_settings_delay` int(11) NOT NULL,
  `general_settings_shuffle_mode` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `general_settings_lazy_load` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `general_settings_wmpl` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `general_settings_enable_static_layers` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `general_settings_stop_slider` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `general_settings_start_after_loops` int(11) NOT NULL,
  `general_settings_stop_at_slide` int(11) NOT NULL,
  `load_google_fonts` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `google_fonts` text NOT NULL,
  `position` enum('LEFT','CENTER','RIGHT') NOT NULL DEFAULT 'CENTER',
  `position_margin_top` int(11) NOT NULL,
  `position_margin_bottom` int(11) NOT NULL,
  `position_margin_left` int(11) NOT NULL,
  `position_margin_right` int(11) NOT NULL,
  `appearance_shadow_type` int(11) NOT NULL,
  `appearance_show_timer_line` enum('TOP','HIDE','BOTTOM') NOT NULL DEFAULT 'TOP',
  `appearance_padding` int(11) NOT NULL,
  `appearance_background_color` varchar(255) NOT NULL,
  `appearance_dotted_overlay` enum('2X2_BLACK','2X2_WHITE','3X3_BLACK','3X3_WHITE') NOT NULL DEFAULT '2X2_BLACK',
  `appearance_show_background_image` enum('Y','N') NOT NULL DEFAULT 'N',
  `appearance_background_image_url` text NOT NULL,
  `appearance_background_fit` enum('COVER','CONTAIN','NORMAL') NOT NULL,
  `appearance_background_repeat` enum('NO_REPEAT','REPEAT','REPEAT_X','REPEAT_Y') NOT NULL DEFAULT 'NO_REPEAT',
  `appearance_background_position` enum('CENTER_TOP','CENTER_RIGHT','CENTER_BOTTOM','CENTER_CENTER',';EFT_TOP','LEFT_CENTER','LEFT_BOTTOM','RIGHT_TOP','RIGHT_CENTER','RIGHT_BOTTOM') NOT NULL,
  `parallax` enum('Y','N') NOT NULL DEFAULT 'N',
  `parallax_disabled_on_mobile` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `parallax_type` enum('MOUSE_ONLY','SCROLL_ONLY','MOUSE_AND_SCROLL') NOT NULL DEFAULT 'MOUSE_ONLY',
  `parallax_bg_freeze` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `parallax_depth_1` int(11) NOT NULL,
  `parallax_depth_2` int(11) NOT NULL,
  `parallax_depth_3` int(11) NOT NULL,
  `parallax_depth_4` int(11) NOT NULL,
  `parallax_depth_5` int(11) NOT NULL,
  `parallax_depth_6` int(11) NOT NULL,
  `parallax_depth_7` int(11) NOT NULL,
  `parallax_depth_8` int(11) NOT NULL,
  `parallax_depth_9` int(11) NOT NULL,
  `parallax_depth_10` int(11) NOT NULL,
  `spinner` int(11) NOT NULL,
  `spinner_color` varchar(255) NOT NULL,
  `navigation_stop_on_hover` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `navigation_keyboard` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `navigation_style` enum('ROUND','NAVBAR','PREVIEW_1','PREVIEW_2','PREVIEW_3','PREVIEW_4','CUSTOM','OLD_ROUND','OLD_SQUARE','OLD_NAV') NOT NULL DEFAULT 'ROUND',
  `navigation_bullet_type` enum('BULLET','THUMB') NOT NULL,
  `navigation_arrows` enum('WITH_BULLETS','SOLO') NOT NULL,
  `navigation_show` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `navigation_hide_after` int(11) NOT NULL,
  `navigation_horizontal_align` varchar(255) NOT NULL,
  `navigation_vertical_align` varchar(255) NOT NULL,
  `navigation_horizontal_offset` int(11) NOT NULL,
  `navigation_vertical_offset` int(11) NOT NULL,
  `navigation_left_arrow_horizontal_align` enum('LEFT','RIGHT','CENTER') NOT NULL,
  `navigation_left_arrow_vertical_align` enum('TOP','CENTER','BOTTOM') NOT NULL,
  `navigation_left_arrow_horizontal_offset` int(11) NOT NULL,
  `navigation_left_arrow_vertical_offset` int(11) NOT NULL,
  `navigation_right_arrow_horizontal_align` enum('LEFT','CENTER','RIGHT') NOT NULL,
  `navigation_right_arrow_vertical_align` enum('TOP','CENTER','BOTTOM') NOT NULL,
  `navigation_right_arrow_horizontal_offset` int(11) NOT NULL,
  `navigation_right_arrow_vertical_offset` int(11) NOT NULL,
  `thumb_width` int(11) NOT NULL,
  `thumb_height` int(11) NOT NULL,
  `thumb_amount` int(11) NOT NULL,
  `touch_enabled` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `swipe_velocity` decimal(10,0) NOT NULL,
  `swipe_min_touches` int(11) NOT NULL,
  `swipe_max_touches` int(11) NOT NULL,
  `swipe_drag_block_vertical` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `disable_slider_on_mobile` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `hide_slider_under_width` int(11) NOT NULL,
  `hide_defined_layers_under_width` int(11) NOT NULL,
  `hide_all_layers_under_width` int(11) NOT NULL,
  `hide_arrows_on_mobile` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `hide_bullets_on_mobile` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `hide_thumbnails_on_mobile` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `hide_thumbs_under_width` int(11) NOT NULL,
  `hide_mobile_nav_after` int(11) NOT NULL,
  `loop_slide` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `start_slide` int(11) NOT NULL,
  `start_slide_enabled` enum('ON','OFF') NOT NULL DEFAULT 'OFF',
  `first_transition_type` varchar(255) NOT NULL,
  `first_transition_duration` int(11) NOT NULL,
  `first_transition_slot_amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `revolution_slides`;
CREATE TABLE `revolution_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `revolution_slide_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` text NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `shipping_areas`;
CREATE TABLE `shipping_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_name` varchar(255) NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `shipping_country`;
CREATE TABLE `shipping_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `shipping_rates`;
CREATE TABLE `shipping_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) NOT NULL,
  `rate_name` varchar(255) NOT NULL,
  `rate_description` text NOT NULL,
  `rate_type` enum('price-base','weight-base','item-base','order-base') NOT NULL,
  `rate_min` int(11) NOT NULL,
  `rate_max` int(11) NOT NULL,
  `rate_free` enum('Y','N') NOT NULL,
  `rate_amount` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `system_options`;
CREATE TABLE `system_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(150) NOT NULL,
  `option_value` longtext,
  `meta_data` text,
  `auto_load` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `system_users`;
CREATE TABLE `system_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_role` enum('administrator','editor','super_admin') NOT NULL DEFAULT 'editor',
  `username` varchar(150) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `user_fullname` varchar(150) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_registered` datetime NOT NULL,
  `user_profpic` text,
  `password_reset_hash` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `urls`;
CREATE TABLE `urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `table` varchar(255) NOT NULL,
  `status` enum('active','trashed') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `user_modules`;
CREATE TABLE `user_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

INSERT INTO `countries` (`id`, `name`, `value`, `code`, `meta`) VALUES
(1, 'Singapore', '65', 'SG', ''),
(2, 'Algeria', '213', 'DZ', 'others'),
(3, 'Andorra', '376', 'AD', 'others'),
(4, 'Angola', '244', 'AO', 'others'),
(5, 'Anguilla', '1264', 'AI', 'others'),
(6, 'Antigua & Barbuda', '1268', 'AG', 'others'),
(7, 'Argentina', '54', 'AR', 'others'),
(8, 'Armenia', '374', 'AM', 'others'),
(9, 'Aruba', '297', 'AW', 'others'),
(10, 'Australia', '61', 'AU', 'others'),
(11, 'Austria', '43', 'AT', 'others'),
(12, 'Azerbaijan', '994', 'AZ', 'others'),
(13, 'Bahamas', '1242', 'BS', 'others'),
(14, 'Bahrain', '973', 'BH', 'others'),
(15, 'Bangladesh', '880', 'BD', 'others'),
(16, 'Barbados', '1246', 'BB', 'others'),
(17, 'Belarus', '375', 'BY', 'others'),
(18, 'Belgium', '32', 'BE', 'others'),
(19, 'Belize', '501', 'BZ', 'others'),
(20, 'Benin', '229', 'BJ', 'others'),
(21, 'Bermuda', '1441', 'BM', 'others'),
(22, 'Bhutan', '975', 'BT', 'others'),
(23, 'Bolivia', '591', 'BO', 'others'),
(24, 'Bosnia Herzegovina', '387', 'BA', 'others'),
(25, 'Botswana', '267', 'BW', 'others'),
(26, 'Brazil', '55', 'BR', 'others'),
(27, 'Brunei', '673', 'BN', 'others'),
(28, 'Bulgaria', '359', 'BG', 'others'),
(29, 'Burkina Faso', '226', 'BF', 'others'),
(30, 'Burundi', '257', 'BI', 'others'),
(31, 'Cambodia', '855', 'KH', 'others'),
(32, 'Cameroon', '237', 'CM', 'others'),
(33, 'Canada', '1', 'CA', 'others'),
(34, 'Cape Verde Islands', '238', 'CV', 'others'),
(35, 'Cayman Islands', '1345', 'KY', 'others'),
(36, 'Central African Republic', '236', 'CF', 'others'),
(37, 'Chile', '56', 'CL', 'others'),
(38, 'China', '86', 'CN', 'others'),
(39, 'Colombia', '57', 'CO', 'others'),
(40, 'Comoros', '269', 'KM', 'others'),
(41, 'Congo', '242', 'CG', 'others'),
(42, 'Cook Islands', '682', 'CK', 'others'),
(43, 'Costa Rica', '506', 'CR', 'others'),
(44, 'Croatia', '385', 'HR', 'others'),
(45, 'Cuba', '53', 'CU', 'others'),
(46, 'Cyprus North', '90392', 'CY', 'others'),
(47, 'Cyprus South', '357', 'CY', 'others'),
(48, 'Czech Republic', '42', 'CZ', 'others'),
(49, 'Denmark', '45', 'DK', 'others'),
(50, 'Djibouti', '253', 'DJ', 'others'),
(51, 'Dominica', '1809', 'DM', 'others'),
(52, 'Dominican Republic', '1809', 'DO', 'others'),
(53, 'Ecuador', '593', 'EC', 'others'),
(54, 'Egypt', '20', 'EG', 'others'),
(55, 'El Salvador', '503', 'SV', 'others'),
(56, 'Equatorial Guinea', '240', 'GQ', 'others'),
(57, 'Eritrea', '291', 'ER', 'others'),
(58, 'Estonia', '372', 'EE', 'others'),
(59, 'Ethiopia', '251', 'ET', 'others'),
(60, 'Falkland Islands', '500', 'FK', 'others'),
(61, 'Faroe Islands', '298', 'FO', 'others'),
(62, 'Fiji', '679', 'FJ', 'others'),
(63, 'Finland', '358', 'FI', 'others'),
(64, 'France', '33', 'FR', 'others'),
(65, 'French Guiana', '594', 'GF', 'others'),
(66, 'French Polynesia', '689', 'PF', 'others'),
(67, 'Gabon', '241', 'GA', 'others'),
(68, 'Gambia', '220', 'GM', 'others'),
(69, 'Georgia', '7880', 'GE', 'others'),
(70, 'Germany', '49', 'DE', 'others'),
(71, 'Ghana', '233', 'GH', 'others'),
(72, 'Gibraltar', '350', 'GI', 'others'),
(73, 'Greece', '30', 'GR', 'others'),
(74, 'Greenland', '299', 'GL', 'others'),
(75, 'Grenada', '1473', 'GD', 'others'),
(76, 'Guadeloupe', '590', 'GP', 'others'),
(77, 'Guam', '671', 'GU', 'others'),
(78, 'Guatemala', '502', 'GT', 'others'),
(79, 'Guinea', '224', 'GN', 'others'),
(80, 'Guinea - Bissau', '245', 'GW', 'others'),
(81, 'Guyana', '592', 'GY', 'others'),
(82, 'Haiti', '509', 'HT', 'others'),
(83, 'Honduras', '504', 'HN', 'others'),
(84, 'Hong Kong', '852', 'HK', 'others'),
(85, 'Hungary', '36', 'HU', 'others'),
(86, 'Iceland', '354', 'IS', 'others'),
(87, 'India', '91', 'IN', 'others'),
(88, 'Indonesia', '62', 'ID', 'others'),
(89, 'Iran', '98', 'IR', 'others'),
(90, 'Iraq', '964', 'IQ', 'others'),
(91, 'Ireland', '353', 'IE', 'others'),
(92, 'Israel', '972', 'IL', 'others'),
(93, 'Italy', '39', 'IT', 'others'),
(94, 'Jamaica', '1876', 'JM', 'others'),
(95, 'Japan', '81', 'JP', 'others'),
(96, 'Jordan', '962', 'JO', 'others'),
(97, 'Kazakhstan', '7', 'KZ', 'others'),
(98, 'Kenya', '254', 'KE', 'others'),
(99, 'Kiribati', '686', 'KI', 'others'),
(100, 'Korea North', '850', 'KP', 'others'),
(101, 'Korea South', '82', 'KR', 'others'),
(102, 'Kuwait', '965', 'KW', 'others'),
(103, 'Kyrgyzstan', '996', 'KG', 'others'),
(104, 'Laos', '856', 'LA', 'others'),
(105, 'Latvia', '371', 'LV', 'others'),
(106, 'Lebanon', '961', 'LB', 'others'),
(107, 'Lesotho', '266', 'LS', 'others'),
(108, 'Liberia', '231', 'LR', 'others'),
(109, 'Libya', '218', 'LY', 'others'),
(110, 'Liechtenstein', '417', 'LI', 'others'),
(111, 'Lithuania', '370', 'LT', 'others'),
(112, 'Luxembourg', '352', 'LU', 'others'),
(113, 'Macao', '853', 'MO', 'others'),
(114, 'Macedonia', '389', 'MK', 'others'),
(115, 'Madagascar', '261', 'MG', 'others'),
(116, 'Malawi', '265', 'MW', 'others'),
(117, 'Malaysia', '60', 'MY', 'others'),
(118, 'Maldives', '960', 'MV', 'others'),
(119, 'Mali', '223', 'ML', 'others'),
(120, 'Malta', '356', 'MT', 'others'),
(121, 'Marshall Islands', '692', 'MH', 'others'),
(122, 'Martinique', '596', 'MQ', 'others'),
(123, 'Mauritania', '222', 'MR', 'others'),
(124, 'Mayotte', '269', 'YT', 'others'),
(125, 'Mexico', '52', 'MX', 'others'),
(126, 'Micronesia', '691', 'FM', 'others'),
(127, 'Moldova', '373', 'MD', 'others'),
(128, 'Monaco', '377', 'MC', 'others'),
(129, 'Mongolia', '976', 'MN', 'others'),
(130, 'Montserrat', '1664', 'MS', 'others'),
(131, 'Morocco', '212', 'MA', 'others'),
(132, 'Mozambique', '258', 'MZ', 'others'),
(133, 'Myanmar', '95', 'MN', 'others'),
(134, 'Namibia', '264', 'NA', 'others'),
(135, 'Nauru', '674', 'NR', 'others'),
(136, 'Nepal', '977', 'NP', 'others'),
(137, 'Netherlands', '31', 'NL', 'others'),
(138, 'New Caledonia', '687', 'NC', 'others'),
(139, 'New Zealand', '64', 'NZ', 'others'),
(140, 'Nicaragua', '505', 'NI', 'others'),
(141, 'Niger', '227', 'NE', 'others'),
(142, 'Nigeria', '234', 'NG', 'others'),
(143, 'Niue', '683', 'NU', 'others'),
(144, 'Norfolk Islands', '672', 'NF', 'others'),
(145, 'Northern Marianas', '670', 'NP', 'others'),
(146, 'Norway', '47', 'NO', 'others'),
(147, 'Oman', '968', 'OM', 'others'),
(148, 'Palau', '680', 'PW', 'others'),
(149, 'Panama', '507', 'PA', 'others'),
(150, 'Papua New Guinea', '675', 'PG', 'others'),
(151, 'Paraguay', '595', 'PY', 'others'),
(152, 'Peru', '51', 'PE', 'others'),
(153, 'Philippines', '63', 'PH', 'others'),
(154, 'Poland', '48', 'PL', 'others'),
(155, 'Portugal', '351', 'PT', 'others'),
(156, 'Puerto Rico', '1787', 'PR', 'others'),
(157, 'Qatar', '974', 'QA', 'others'),
(158, 'Reunion', '262', 'RE', 'others'),
(159, 'Romania', '40', 'RO', 'others'),
(160, 'Russia', '7', 'RU', 'others'),
(161, 'Rwanda', '250', 'RW', 'others'),
(162, 'San Marino', '378', 'SM', 'others'),
(163, 'Sao Tome & Principe', '239', 'ST', 'others'),
(164, 'Saudi Arabia', '966', 'SA', 'others'),
(165, 'Senegal', '221', 'SN', 'others'),
(166, 'Serbia', '381', 'CS', 'others'),
(167, 'Seychelles', '248', 'SC', 'others'),
(168, 'Sierra Leone', '232', 'SL', 'others'),
(169, 'Slovak Republic', '421', 'SK', 'others'),
(170, 'Slovenia', '386', 'SI', 'others'),
(171, 'Solomon Islands', '677', 'SB', 'others'),
(172, 'Somalia', '252', 'SO', 'others'),
(173, 'South Africa', '27', 'ZA', 'others'),
(174, 'Spain', '34', 'ES', 'others'),
(175, 'Sri Lanka', '94', 'LK', 'others'),
(176, 'St. Helena', '290', 'SH', 'others'),
(177, 'St. Kitts', '1869', 'KN', 'others'),
(178, 'St. Lucia', '1758', 'SC', 'others'),
(179, 'Sudan', '249', 'SD', 'others'),
(180, 'Suriname', '597', 'SR', 'others'),
(181, 'Swaziland', '268', 'SZ', 'others'),
(182, 'Sweden', '46', 'SE', 'others'),
(183, 'Switzerland', '41', 'CH', 'others'),
(184, 'Syria', '963', 'SI', 'others'),
(185, 'Taiwan', '886', 'TW', 'others'),
(186, 'Tajikstan', '7', 'TJ', 'others'),
(187, 'Thailand', '66', 'TH', 'others'),
(188, 'Togo', '228', 'TG', 'others'),
(189, 'Tonga', '676', 'TO', 'others'),
(190, 'Trinidad & Tobago', '1868', 'TT', 'others'),
(191, 'Tunisia', '216', 'TN', 'others'),
(192, 'Turkey', '90', 'TR', 'others'),
(193, 'Turkmenistan', '7', 'TM', 'others'),
(194, 'Turkmenistan', '993', 'TM', 'others'),
(195, 'Turks & Caicos Islands', '1649', 'TC', 'others'),
(196, 'Tuvalu', '688', 'TV', 'others'),
(197, 'Uganda', '256', 'UG', 'others'),
(198, 'UK', '44', 'GB', 'others'),
(199, 'Ukraine', '380', 'UA', 'others'),
(200, 'United Arab Emirates', '971', 'AE', 'others'),
(201, 'Uruguay', '598', 'UY', 'others'),
(202, 'USA', '1', 'US', 'others'),
(203, 'Uzbekistan', '7', 'UZ', 'others'),
(204, 'Vanuatu', '678', 'VU', 'others'),
(205, 'Vatican City', '379', 'VA', 'others'),
(206, 'Venezuela', '58', 'VE', 'others'),
(207, 'Vietnam', '84', 'VN', 'others'),
(208, 'Virgin Islands - British', '84', 'VG', 'others'),
(209, 'Virgin Islands - US', '84', 'VI', 'others'),
(210, 'Wallis & Futuna', '681', 'WF', 'others'),
(211, 'Yemen', '969', 'YE', 'others'),
(212, 'Yemen', '967', 'YE', 'others'),
(213, 'Zambia', '260', 'ZM', 'others'),
(214, 'Zimbabwe', '263', 'ZW', 'others');

INSERT INTO `modules` (`id`, `module_name`, `module_label`, `meta_data`, `super_admin_only`, `sort_index`) VALUES
(1, 'products', 'Products', 'Products', 'N', 1),
(2, 'orders', 'Orders', 'Orders', 'N', 2),
(3, 'invoices', 'Invoices', 'Invoices', 'N', 3),
(4, 'customers', 'Customers', 'Customers', 'N', 4),
(5, 'posts', 'Posts', 'Post', 'N', 6),
(6, 'pages', 'Pages', 'Pages', 'N', 7),
(7, 'enquiries', 'Enquiries', 'Enquiries', 'N', 8),
(8, 'appearance', 'Appearance', 'Menus,ContactForms,Appointments,Sliders', 'N', 9),
(9, 'users', 'Users', 'Users', 'N', 10),
(10, 'ecommerce', 'Ecommerce', 'PaymentGateways,Shipping', 'N', 11),
(11, 'payment', 'Payment Gateway', 'PaymentGateways', 'N', 12),
(12, 'shipping', 'Shipping', 'Shipping', 'N', 13),
(13, 'settings', 'General Settings', 'Settings', 'N', 14),
(14, 'super-admin', 'Super Admin', 'SystemSettings', 'Y', 15),
(15, 'newsletter', 'Newsletter', 'Newsletter', 'N', 5);

INSERT INTO `patch_log` (`id`, `version`, `description`, `type`, `meta`, `status`, `date_added`, `date_installed`) VALUES 
  ('302', '1.2.11', '', 'normal', 'a:8:{i:0;a:3:{s:4:"type";s:4:"file";s:7:"command";s:96:"download libraries/extra-functions/url-checker-2.php libraries/extra-functions/url-checker-2.php";s:11:"description";s:53:"Download: libraries/extra-functions/url-checker-2.php";}i:1;a:3:{s:4:"type";s:4:"file";s:7:"command";s:44:"download libraries/Url.php libraries/Url.php";s:11:"description";s:27:"Download: libraries/Url.php";}i:2;a:3:{s:4:"type";s:4:"file";s:7:"command";s:160:"download system_plugins/ecatalog/backend/assets/js/controllers/product-categories.js system_plugins/ecatalog/backend/assets/js/controllers/product-categories.js";s:11:"description";s:85:"Download: system_plugins/ecatalog/backend/assets/js/controllers/product-categories.js";}i:3;a:3:{s:4:"type";s:4:"file";s:7:"command";s:144:"download system_plugins/ecatalog/backend/models/product-categories_model.php system_plugins/ecatalog/backend/models/product-categories_model.php";s:11:"description";s:77:"Download: system_plugins/ecatalog/backend/models/product-categories_model.php";}i:4;a:3:{s:4:"type";s:4:"file";s:7:"command";s:140:"download system_plugins/ecatalog/frontend/controllers/product-category.php system_plugins/ecatalog/frontend/controllers/product-category.php";s:11:"description";s:75:"Download: system_plugins/ecatalog/frontend/controllers/product-category.php";}i:5;a:3:{s:4:"type";s:4:"file";s:7:"command";s:162:"download system_plugins/ecommerce/backend/assets/js/controllers/product-categories.js system_plugins/ecommerce/backend/assets/js/controllers/product-categories.js";s:11:"description";s:86:"Download: system_plugins/ecommerce/backend/assets/js/controllers/product-categories.js";}i:6;a:3:{s:4:"type";s:4:"file";s:7:"command";s:146:"download system_plugins/ecommerce/backend/models/product-categories_model.php system_plugins/ecommerce/backend/models/product-categories_model.php";s:11:"description";s:78:"Download: system_plugins/ecommerce/backend/models/product-categories_model.php";}i:7;a:3:{s:4:"type";s:4:"file";s:7:"command";s:142:"download system_plugins/ecommerce/frontend/controllers/product-category.php system_plugins/ecommerce/frontend/controllers/product-category.php";s:11:"description";s:76:"Download: system_plugins/ecommerce/frontend/controllers/product-category.php";}}', 'done', '2018-02-22 17:47:36', '2018-02-22 17:47:36'); INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('website_logo', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('website_footer_copyright_text', 'Copyright Â© 2017 Rachan Test. All Rights Reserved', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Copyright Â© 2017 Rachan Test. All Rights Reserved';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('system_email', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('system_email_name', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('enquiry_form_email', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('category_page_display_view', 'LIST', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='LIST';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('category_page_display_order', 'FEATURED_LISTING_TOP', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='FEATURED_LISTING_TOP';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('listing_page_display_related_items_count', '8', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='8';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('customer_login_required', 'ON', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='ON';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('google_event_tracking', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('google_analytics_code', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('conversion_tracking_code', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('system_type', 'CMS', '["posts","pages","enquiries","appearance","users","settings"]', 'no') ON DUPLICATE KEY UPDATE `option_value`='CMS', `option_value`='CMS';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('currency_symbol', '$', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='$';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('currency_code', 'SGD', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='SGD';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('invoice_currency_name', 'MySite', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='MySite';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('invoice_company_address', 'MySite Station', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='MySite Station';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('invoice_number_prefix', 'MySite-Item-', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='MySite-Item-';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('invoice_next_number', '111', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='111';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('company_name', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('company_address', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('company_contact_number', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('company_fax_number', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('company_email', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('business_registration_number', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('disallow_indexing', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('website_name', 'MySite', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='MySite';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('shipping_origin_postal', '8000', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='8000';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('shipping_origin_address_2', 'Test Address 1', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Test Address 1';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('shipping_origin_address_1', 'Test Address 1', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Test Address 1';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('shipping_origin_name', 'Test Shipping Address Name', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Test Shipping Address Name';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('shipping_origin_city', 'Test City', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Test City';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('shipping_origin_country', '0000', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='0000';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('shipping_origin_phone', '0129347233', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='0129347233';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('frontend_theme', 'default', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='default';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('blog_post_limit', '3', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='3';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('homepage', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('blog_page', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('system_patch_version', '2', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='2';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('site_url', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('mailchimp-api-key', 'cbbecbeb82286ac71cecc4174dbe05be-us14', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='cbbecbeb82286ac71cecc4174dbe05be-us14';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('mailchimp-checkbox-label', 'Subscribe Me!!!', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Subscribe Me!!!';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('mailchimp-checkbox-default', 'No', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='No';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('mailchimp-autoupdate', 'Yes', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Yes';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('customer_login_enable', 'Y', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Y';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_no_index_ecommerce_ecatalog', 'N', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='N';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_no_index_category_page', 'Y', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Y';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_no_index_detail_page', 'N', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='N';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_no_index_cart', 'N', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='N';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_no_index_checkout', 'N', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='N';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_no_index_order_enquiry', 'N', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='N';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_home_page', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_cart_page', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_checkout_page', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_enquire_page', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_confirmation_page', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_confirmed_page', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('product_payment_method_page', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('system_robot_txt', 'User-agent: AhrefsBot
Disallow: /
User-agent: Alexibot
Disallow: /
User-agent: MJ12bot
Disallow: /
User-agent: SurveyBot
Disallow: /
User-agent: rogerbot
Disallow: /
User-agent: sitebot
Disallow: /
User-agent: exabot
Disallow: /
User-agent: ia_archiver
Disallow: /
User-agent: BLEXBot
Disallow: /
User-agent: YakazBot
Disallow: /
User-agent: proximic
Disallow: /', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='User-agent: AhrefsBot
Disallow: /
User-agent: Alexibot
Disallow: /
User-agent: MJ12bot
Disallow: /
User-agent: SurveyBot
Disallow: /
User-agent: rogerbot
Disallow: /
User-agent: sitebot
Disallow: /
User-agent: exabot
Disallow: /
User-agent: ia_archiver
Disallow: /
User-agent: BLEXBot
Disallow: /
User-agent: YakazBot
Disallow: /
User-agent: proximic
Disallow: /';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('sitemap-enable', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('post_url_format', 'postname', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='postname';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('disallow_blog_indexing', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('blacklisted_url', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('disallow_blog_post_indexing', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('disallow_blog_search_indexing', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('disallow_blog_pagination_indexing', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('disallow_blog_category_indexing', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('enable_https_redirect', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('enable_customer_registration', 'OFF', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='OFF';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('ecommerce-enable-delivery-detail', 'Y', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='Y';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('ecommerce-self-collection-discount', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('ecommerce-normal-delivery-charge', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('ecommerce-normal-delivery-time', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('ecommerce-express-delivery-surcharge', '', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('ecommerce-shipping-detail-enable', 'N', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='N';
INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES('ecommerce-billing-detail-enable', 'N', '', 'no') ON DUPLICATE KEY UPDATE `option_value`='N';
INSERT INTO `system_users` (`user_role`, `username`, `salt`, `password`, `user_fullname`, `user_email`, `user_registered`, `user_profpic`, `password_reset_hash`) VALUES('super_admin', 'j2admin', '9671d5ca99c1fc3f172243ec78103086', '62a71b8cb47b41e51747c1ed38654e7c2e75fb4e', 'Super Admin', 'admin@dyna.sg', '2018-03-27 16:01:25', '', '');
