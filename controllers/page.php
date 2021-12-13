<?php
class Page extends Controller {
  private $url;
  private $uc;
  private $url_system_info;

  private $cached_language_current;
  private $cached_language_default;
  private $cached_language_reserve;
  private $cached_posts_pages;

	function __construct() {
    Session::set('page_type', 'secondary');

		parent::__construct();

    $this->uc = new UC(); 
    $this->url_system_info = $this->uc->uc_get_current_url_settings( get_current_url() );
    $this->url = trim($this->url_system_info['language']['slug_url'],'/');

    $this->cached_language_current = cms_get_language();
    $this->cached_language_default = cms_get_language_default();
    $this->cached_language_reserve = cms_get_language_reserved();
	}

	function index() {
		cms_initialize();

		if ($this->url != '') {
			$url =  $this->url;
			$this->validate_url(array($url));

			$info = $this->get_post_info( $url );
      
			if ( count($info) ) {
				if ($info->url_slug == cms_get_homepage_slug()) {
          $r_url = $this->cached_language_current==$this->cached_language_default ? URL : trim(URL) . $this->cached_language_default ;
					header_redirect($r_url); 
					exit();
				}elseif ($info->url_slug == cms_get_blog_slug()) {
					$page_type = 'isblog';
					if (isset($_GET['page'])) {
						$page_type = "isblogpagination";
					}elseif(isset($_GET['q'])){
						$page_type = "isblogsearch";
					}
					$this->render_slug( $url, $page_type);
				}else{
					$this->render_slug( $url );
				}
			}else{
				$this->view->error();
			}
		}else{
      Session::set('page_type', 'homepage');

      if (cms_get_homepage_slug() != "") {
				$this->render_slug(cms_get_homepage_slug());
			}else{
				$this->view->render('index');
			}
		}
	}
  function __other($url = "") {
    cms_initialize();
    cms_check_link(URL.$_GET['url']);
    $len = sizeof($url) - 1;
    $this->validate_url($url);

    $url_slug = $url[$len];

    $info = $this->get_post_info( $url_slug );
    if ( count($info) ) {
      if ($info->url_slug == cms_get_homepage_slug()) {
        header_redirect(URL); 
        exit();
      }else{
        $this->render_slug( $url_slug );
      }
    }else{
      $blog_info = $this->get_blog_page_info($url);

      if (count($blog_info) > 0) {
        $blog_info = $blog_info[0];
        array_shift($url);
        $url_slug = $blog_info->url_slug;

        $_GET['page'] = count($url) == 2 ? $url[1] : 1;
        
        $this->render_slug( $url_slug );
      }else{
        $this->view->error();
      }
    }
  }

	private function get_post_info($url_slug = ''){
		global $db;
    $sql = "Select * From (Select * From `cms_posts` UNION ( Select `post_id` `id`, `post_author`, `post_date`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_type`, `url_slug`, `old_slug`, `seo_canonical_url`, `page_template`, `seo_title`, `seo_description`, `seo_no_index`, `parent_id`, `status`, `featured_image`, `featured_image_crop`, `featured_image_crop_data`, `meta_data`, `date_added` From `cms_posts_translate` `c`)) `cms_posts` Where `url_slug` = '{$url_slug}';";
		$info = $db->select($sql); 
		return count($info) ? $info[0] : array();
	}
	private function render_slug($url_slug = '', $overwrite_type = ""){
		cms_query_posts('url_slug=' . $url_slug);

		if (cms_have_posts()) {
			cms_the_post();

			$page_template = cms_get_page_template();

			if (cms_get_post_type() == 'post') {
				Session::set('ispage', 'ispost');
			}else{
				Session::set('ispage', 'ispage');
			}

			if ($overwrite_type != "") {
				Session::set('ispage', $overwrite_type);
			}

			if (function_exists('is_product_page') && is_product_page(cms_get_post_id())) {
				product_page(cms_get_post_id(), $this);
      }
			$this->view->render($page_template);
    }else{
      $this->view->error();
    }
  }
  private function validate_url($url = array()){
  	if (!isset($this->cached_posts_pages)) {
  		$this->cached_posts_pages = $this->load_posts_pages();
  	}

  	$url_slug = end($url);

    $sql = "SELECT * FROM (
              SELECT * FROM (
                SELECT *, '{$this->cached_language_reserve}' `language` FROM `cms_posts`
                ) t1
              UNION ( 
                SELECT `post_id` `id`, 
                `post_author`, 
                `post_date`, 
                `post_content`, 
                `post_title`, 
                `post_excerpt`, 
                `post_status`, 
                `post_type`, 
                `url_slug`, 
                `old_slug`, 
                `seo_canonical_url`, 
                `page_template`, 
                `seo_title`, 
                `seo_description`, 
                `seo_no_index`, 
                `parent_id`, 
                `status`, 
                `featured_image`, 
                `featured_image_crop`, 
                `featured_image_crop_data`, 
                `meta_data`, 
                `date_added`,
                `language` 
                FROM `cms_posts_translate` `c`
                )
              ) `cms_posts` 
            WHERE `url_slug` = '{$url_slug}' and language = '{$this->cached_language_current}';";

  	$page_info = $this->model->db->select( $sql );
  	if (!count($page_info)) {
      $blog_info = $this->get_blog_page_info($url);
      if (count($blog_info) < 1) {
        $this->view->error();
        exit();
      }else{
        return;
      }
  	}
  	$page_info = $page_info[0];

  	if ($page_info->post_type == 'post') {
  		$post_url_info = cms_validate_post_url( $page_info );
  		if ($post_url_info) {
  			return;
  		}else{
  			$possible_url = cms_get_post_url( $page_info );
  			redirect( $possible_url );
  			exit();
  		}
  	}

  	/* Get Parent Pages */
  	$_p = $this->cached_posts_pages[$page_info->id];
  	$parent = "{$_p->trans_slug}/";
  	$_temp = array();
  	while ($_p->parent_id != 0) {
  		if (!in_array($_p->id, $_temp)) {
  			$_temp[] = $_p->id;
  		}else{
  			break;
  		}
  		$_p = $this->cached_posts_pages[$_p->parent_id];
  		$parent = "{$_p->trans_slug}/{$parent}";
  	}

  	/*
  	$parent = '';
  	if($page_info->id !=0){
      $sql = "SELECT `url_slug`,`parent_id` 
							FROM (
						    SELECT `id`, `url_slug`,`parent_id` 
						    FROM `cms_posts` 
						    UNION ( 
					        SELECT `post_id` `id`, `url_slug`, `parent_id` 
					        FROM `cms_posts_translate` `c`
				        )
					    ) `cms_posts` 
							WHERE `url_slug` = '{$url_slug}' and `id` = '{$page_info->id}';";

  		$qry = $this->model->db->query( $sql );
  		$row = $this->model->db->fetch($qry,'array');
  		$parent = $row['url_slug'].'/';
  		$parent_id = $row['parent_id'];
  		while($parent_id != 0){
        $sql = "SELECT `url_slug`,`parent_id` 
          FROM (
            SELECT `id`, `url_slug`,`parent_id` 
            FROM `cms_posts` 
            UNION ( 
              SELECT `post_id` `id`, `url_slug`,`parent_id` 
              FROM `cms_posts_translate` `c`
              )
            ) `cms_posts` 
          WHERE `url_slug` = '{$url_slug}' 
          AND `id` = '{$parent_id}';";
  			$qryx = $this->model->db->query("SELECT `url_slug`,`parent_id` FROM `cms_posts` WHERE `id` = '$parent_id'");
  			$rowx = $this->model->db->fetch($qryx,'array');
  			$parent = $rowx['url_slug'].'/'.$parent;
  			$parent_id = $rowx['parent_id'];
  		}
  	}
  	*/

  	if ($parent == implode("/", $url) ."/") {
  		return;
  	}else{
  		$parts = explode('?', $_SERVER['REQUEST_URI'], 2);
  		if (isset($parts[1]) && $parts[1] != "") { $parent .= "?{$parts[1]}"; }
  		header_redirect(URL . $parent);
  	}
  }

  private function load_posts_pages(){
  	$temp = array();

  	$sql = "SELECT c.id, c.url_slug, c.parent_id, IFNULL(cp.url_slug, c.url_slug) trans_slug
						FROM cms_posts c 
						LEFT JOIN cms_posts_translate cp 
						ON c.id = cp.post_id and language = '{$this->cached_language_current}'";

		$post_pages = $this->model->db->select( $sql );

		foreach ($post_pages as $key => $value) {
			$temp[$value->id] = $value;
		}

		return $temp;
  }

  private function get_blog_page_info($url = array()){
    $blog_slug = array_shift($url);
    $sql = "SELECT * FROM (
              SELECT *, `cms_posts`.`url_slug` `orig_slug` FROM `cms_posts` 
              UNION ( 
                SELECT `post_id` `id`, `c`.`post_author`, `c`.`post_date`, `c`.`post_content`, `c`.`post_title`, `c`.`post_excerpt`, `c`.`post_status`, `c`.`post_type`, `c`.`url_slug`, `c`.`old_slug`, `c`.`seo_canonical_url`, `c`.`page_template`, `c`.`seo_title`, `c`.`seo_description`, `c`.`seo_no_index`, `c`.`parent_id`, `c`.`status`, `c`.`featured_image`, `c`.`featured_image_crop`, `c`.`featured_image_crop_data`, `c`.`meta_data`, `c`.`date_added`, `cms_posts`.`url_slug` `orig_slug`
                FROM `cms_posts_translate` `c`
                Left Join `cms_posts` On `c`.`post_id` = `cms_posts`.`id`
              )
            ) `cms_posts` 
            WHERE `url_slug` = '{$blog_slug}';";

    $page_info = $this->model->db->select($sql);
    return $page_info;
  }
}
