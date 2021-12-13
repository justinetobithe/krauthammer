<?php
/**
* cms_get_post_sliders: get list of albums of the current post/page
* @param Array $filter filter by (`album` column)
* @param String $current_posts URL Slug of the post/page
* @return array()
*/
function cms_get_post_sliders($filter=array(), $current_posts="") {
	global $db;
	global $cms_current_post;
	$cms_post_id = $cms_current_post['id'];
	$addon = "";

	if (count($filter)) {
		foreach ($filter as $key => $value) {
			$addon = " And `album`.`{$key}` = '{$value}' ";
		}
	}

	if(isset($cms_current_post)) {
		$album_sql = "SELECT `album`.*,`album_photos`.`id` `photo_id`, `album_photos`.`url`, `album_photos`.`description` FROM `album_photos` LEFT JOIN `album` ON `album_photos`.`album_id` = `album`.`id` WHERE `album`.`guid`= {$cms_post_id} {$addon} and `album_photos`.`status` = 'active' Order By `album`.`id`";

		$result = $db->select($album_sql);
		$album = array();

		foreach ($result as $key => $value) {
			$f = json_decode($value->meta);
			$album[$value->id]['album']  = array(
				"id"=> $value->id,
				"name"=> $value->album_name,
				"featured_image"=> isset($f->featured) ? $f->featured : "",
				"status"=> $value->status,
				);
			$album[$value->id]['photos'][] = array(
				"url"=>$value->url,
				"description"=>$value->description,
				);
		}

		return $album;
	}else{
		return array();
	}
}