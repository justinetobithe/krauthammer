<?php


class Sliders extends Controller{

	function __construct(){
		parent::__construct();
		Session::handleLogin();
	}

	function add(){
		$this->view->render('header');
		$this->view->render('sliders/add');
		$this->view->render('footer');
	}

	function index(){
		$this->view->render('header');
		$this->view->render('sliders/index');
		$this->view->render('footer');
	}
	function slides($id="",$action ="",$slider_id=""){
		if($action == '' && $id > 0){
			$this->view->set('slider_id', $id);
			$this->view->render('header');
			$this->view->render('sliders/slides');
			$this->view->render('footer');
		}else if($action=='edit' && $id > 0){
			if($slider_id !== '' && $slider_id > 0)
				$this->load_edit_slides($id);
			else
				header('location:'.URL.'sliders/slides/'.$id);
		}else
		  header('location:'.URL.'sliders/');
	}
	function load_edit_slides($id){
		    $this->view->set('slider_id', $id);
		    $this->view->set('slides', $this->model->get_slides_by_revolution_id($id));
			$this->view->render('header');
			$this->view->render('sliders/slides_edit');
			$this->view->render('footer');
	}
	function edit($url=""){

		if($url > 0 && $url != ""){

			$slider = $this->model->get_slider_by_id($url);

			if(empty($slider))
				header('location:'.URL.'sliders/');

			$this->view->set('slider', $slider);
			$this->view->render('header');
			$this->view->render('sliders/edit');
			$this->view->render('footer');

		}else
			header('location:'.URL.'sliders/');
	}
	function set_tab_header(){
		if(hasPost('action','slider')){
			Session::set('slider_tab', post('id'));
		}
	}
	function add_slides(){
		if(isset($_POST['slider_id'])){
			$result = true;

			$slides_id = $_POST['slider_id'];
			$sort_order = 0;
			if($_POST['datas'] !== ''){
				$sort_order = explode(',',$_POST['datas']);

				foreach ($sort_order as $key => $id) {
					if(!$this->model->update_sort_order($key,$id))
						$result = false;

					$sort_order = $key;
				}
			}
			

			
			if(!empty($_FILES['banner']['name'][0])){
				foreach ($_FILES['banner']['name'] as $i => $name) {

						$sort_order += 1;
						$title = $name;
						$name = uniqid().seoUrl($name);
						$tmp =  $_FILES['banner']['tmp_name'][$i];
						$u_path = "../images/uploads/slide_banner/".$slides_id."/";
						$path_i = FRONTEND_URL."/images/uploads/slide_banner/".$slides_id."/";
						if(!is_dir($u_path)){
							$path = "../images/uploads/slide_banner/".$slides_id;
							if(!mkdir($path, 0755, TRUE)){}
						}
					if (!is_valid_image_type($tmp)) {
						echo "Invalid File!"; exit();
					}
					if(move_uploaded_file($tmp, $u_path . $name)){   
						if(!$this->model->add_banner_slide($path_i.$name, $slides_id,$title,$sort_order)) 
							$result = false; 
					}
					else{
						$result = false; 
					}
				}
			}


		if(!$result)
			echo 0;
		else
			echo 1;
		}
	}
	function load_slides_by_id(){
		if(hasPost('action','slider')){
			$id = post('id');
			echo json_encode($this->model->load_slides_by_id($id));
		}
	}
	function delete_slides_by_id(){
		if(hasPost('action','slider')){
			$id = post('id');
			echo json_encode($this->model->delete_slides_by_id($id));
		}
	}
	function load_slider(){
		if(hasPost('action', 'slider'))
			echo json_encode($this->model->load_slider());
	}
	function delete_slider(){

		if(hasPost('action', 'slider'))
			echo json_encode($this->model->delete_slider(post('id')));
	}
	function create(){
		if(isset($_POST['slider_name'])){
			$insert_row = array();
			#main window
			$insert_row['slider_name'] = post('slider_name');
			$insert_row['slider_alias'] = post('slider_nick_name');
			$insert_row['source_type'] = post('source_type');
			$insert_row['slider_layout'] = post('slider_layout');
			$insert_row['unlimited_height'] = post('unli_height');
			$insert_row['force_full_width'] = post('force_full_width');
			$insert_row['grid_width'] = post('grid_width');
			$insert_row['grid_height'] = post('grid_height');

			#general settings
			$insert_row['general_settings_delay'] = post('delay');
			$insert_row['general_settings_shuffle_mode'] = post('shuffle_mode');
			$insert_row['general_settings_lazy_load'] = post('lazy_load');
			$insert_row['general_settings_wmpl'] = post('wmpl');
			$insert_row['general_settings_enable_static_layers'] = post('enable_static_layers');
			$insert_row['general_settings_stop_slider'] = post('stop_slider');
			$insert_row['general_settings_start_after_loops'] = post('start_after_loops');
			$insert_row['general_settings_stop_at_slide'] = post('stop_at_slide');

			#google font settings
			$insert_row['load_google_fonts'] = post('load_google_font');
			$insert_row['google_fonts'] = post('google_fonts');

			#position
			$insert_row['position'] = post('position');
			$insert_row['position_margin_top'] = post('margin_top');
			$insert_row['position_margin_bottom'] = post('margin_bottom');
			$insert_row['position_margin_left'] = post('margin_left');
			$insert_row['position_margin_right'] = post('margin_right');

			#appearance
			$insert_row['appearance_shadow_type'] = post('shadow_type');
			$insert_row['appearance_show_timer_line'] = post('show_timer_line');
			$insert_row['appearance_padding'] = post('padding');
			$insert_row['appearance_background_color'] = post('background_color');
			$insert_row['appearance_dotted_overlay'] = post('dotted_overlay');
			$insert_row['appearance_show_background_image'] = post('show_background_image');
			$insert_row['appearance_background_image_url'] = post('background_image_url');
			$insert_row['appearance_background_fit'] = post('background_fit');
			$insert_row['appearance_background_repeat'] = post('background_repeat');
			$insert_row['appearance_background_position'] = post('background_position');

			#parallax
			$insert_row['parallax'] = post('enable_parallax');
			$insert_row['parallax_disabled_on_mobile'] = post('disabled_on_mobile');
			$insert_row['parallax_type'] = post('type');
			$insert_row['parallax_bg_freeze'] = post('bg_freeze');
			$insert_row['parallax_depth_1'] = post('level_depth_1');
			$insert_row['parallax_depth_2'] = post('level_depth_2');
			$insert_row['parallax_depth_3'] = post('level_depth_3');
			$insert_row['parallax_depth_4'] = post('level_depth_4');	
			$insert_row['parallax_depth_5'] = post('level_depth_5');
			$insert_row['parallax_depth_6'] = post('level_depth_6');
			$insert_row['parallax_depth_7'] = post('level_depth_7');
			$insert_row['parallax_depth_8'] = post('level_depth_8');
			$insert_row['parallax_depth_9'] = post('level_depth_9');
			$insert_row['parallax_depth_10'] = post('level_depth_10');

			#spinner
			$insert_row['spinner'] = post('spinner');
			$insert_row['spinner_color'] = post('spinner_color');

			#navigation
			$insert_row['navigation_stop_on_hover'] = post('stop_on_hover');
			$insert_row['navigation_keyboard'] = post('keyboard_navigation');
			$insert_row['navigation_style'] = post('navition_style');
			$insert_row['navigation_bullet_type'] = post('bullet_type');
			$insert_row['navigation_arrows'] = post('navigation_arrows');
			$insert_row['navigation_show'] = post('show_navigation');
			$insert_row['navigation_hide_after'] = post('hide_navigation_after');
			$insert_row['navigation_horizontal_align'] = post('navigation_horizontal_align');
			$insert_row['navigation_vertical_align'] = post('navigation_vertical_align');
			$insert_row['navigation_horizontal_offset'] = post('navigation_horizontal_offset');
			$insert_row['navigation_vertical_offset'] = post('navigation_vertical_offset');
			$insert_row['navigation_left_arrow_horizontal_align'] = post('left_arrow_horizontal_align');
			$insert_row['navigation_left_arrow_vertical_align'] = post('left_arrow_vertical_align');
			$insert_row['navigation_left_arrow_horizontal_offset'] = post('left_arrow_horizontal_offset');
			$insert_row['navigation_left_arrow_vertical_offset'] = post('left_arrow_vertical_offset');
			$insert_row['navigation_right_arrow_horizontal_align'] = post('right_arrow_horizontal_align');
			$insert_row['navigation_right_arrow_vertical_align'] = post('right_arrow_vertical_align');
			$insert_row['navigation_right_arrow_horizontal_offset'] = post('right_arrow_horizontal_offset');
			$insert_row['navigation_right_arrow_vertical_offset'] = post('right_arrow_vertical_offset');

			#thumbnails
			$insert_row['thumb_width'] = post('thumb_width');
			$insert_row['thumb_height'] = post('thumb_height');
			$insert_row['thumb_amount'] = post('thumb_amount');

			#mobile touch
			$insert_row['touch_enabled'] = post('touch_enabled');
			$insert_row['swipe_velocity'] = post('swipe_velocity');
			$insert_row['swipe_min_touches'] = post('swipe_min_touches');
			$insert_row['swipe_max_touches'] = post('swipe_max_touches');
			$insert_row['swipe_drag_block_vertical'] = post('drag_block_vertical');

			#mobile visibility
			$insert_row['disable_slider_on_mobile'] = post('disabled_slider_on_mobile');
			$insert_row['hide_slider_under_width'] = post('hide_slider_under_width');
			$insert_row['hide_defined_layers_under_width'] = post('hide_defined_layers_under_width');
			$insert_row['hide_all_layers_under_width'] = post('hide_all_layers_under_width');
			$insert_row['hide_arrows_on_mobile'] = post('hide_arrows_on_mobile');
			
			$insert_row['hide_bullets_on_mobile'] = post('hide_bullets_on_mobile');
			$insert_row['hide_thumbnails_on_mobile'] = post('hide_thumbnails_on_mobile');
			$insert_row['hide_thumbs_under_width'] = post('hide_thumbs_under_width');
			$insert_row['hide_mobile_nav_after'] = post('hide_mobile_nav_after');

			#single slide
			$insert_row['loop_slide'] = post('loop_slide');

			#alternative first slide
			$insert_row['start_slide'] = post('start_with_slide');
			$insert_row['start_slide_enabled'] = post('start_with_slide_enabled');
			$insert_row['first_transition_type'] = post('first_transition_type');
			$insert_row['first_transition_duration'] = post('first_transition_duration');
			$insert_row['first_transition_slot_amount'] = post('first_transition_slot_amount');

		    echo $this->model->create($insert_row);

			
		}
	}
	function update(){
		if(isset($_POST['slider_name'])){
			$insert_row = array();
			#main window

			$insert_row['id'] = post('id');
			$insert_row['slider_name'] = post('slider_name');
			$insert_row['slider_alias'] = post('slider_nick_name');
			$insert_row['source_type'] = post('source_type');
			$insert_row['slider_layout'] = post('slider_layout');
			$insert_row['unlimited_height'] = post('unli_height');
			$insert_row['force_full_width'] = post('force_full_width');
			$insert_row['grid_width'] = post('grid_width');
			$insert_row['grid_height'] = post('grid_height');

			#general settings
			$insert_row['general_settings_delay'] = post('delay');
			$insert_row['general_settings_shuffle_mode'] = post('shuffle_mode');
			$insert_row['general_settings_lazy_load'] = post('lazy_load');
			$insert_row['general_settings_wmpl'] = post('wmpl');
			$insert_row['general_settings_enable_static_layers'] = post('enable_static_layers');
			$insert_row['general_settings_stop_slider'] = post('stop_slider');
			$insert_row['general_settings_start_after_loops'] = post('start_after_loops');
			$insert_row['general_settings_stop_at_slide'] = post('stop_at_slide');

			#google font settings
			$insert_row['load_google_fonts'] = escape_string('load_google_font');
			$insert_row['google_fonts'] = post('google_fonts');

			#position
			$insert_row['position'] = post('position');
			$insert_row['position_margin_top'] = post('margin_top');
			$insert_row['position_margin_bottom'] = post('margin_bottom');
			$insert_row['position_margin_left'] = post('margin_left');
			$insert_row['position_margin_right'] = post('margin_right');

			#appearance
			$insert_row['appearance_shadow_type'] = post('shadow_type');
			$insert_row['appearance_show_timer_line'] = post('show_timer_line');
			$insert_row['appearance_padding'] = post('padding');
			$insert_row['appearance_background_color'] = post('background_color');
			$insert_row['appearance_dotted_overlay'] = post('dotted_overlay');
			$insert_row['appearance_show_background_image'] = post('show_background_image');
			$insert_row['appearance_background_image_url'] = post('background_image_url');
			$insert_row['appearance_background_fit'] = post('background_fit');
			$insert_row['appearance_background_repeat'] = post('background_repeat');
			$insert_row['appearance_background_position'] = post('background_position');

			#parallax
			$insert_row['parallax'] = post('enable_parallax');
			$insert_row['parallax_disabled_on_mobile'] = post('disabled_on_mobile');
			$insert_row['parallax_type'] = post('type');
			$insert_row['parallax_bg_freeze'] = post('bg_freeze');
			$insert_row['parallax_depth_1'] = post('level_depth_1');
			$insert_row['parallax_depth_2'] = post('level_depth_2');
			$insert_row['parallax_depth_3'] = post('level_depth_3');
			$insert_row['parallax_depth_4'] = post('level_depth_4');	
			$insert_row['parallax_depth_5'] = post('level_depth_5');
			$insert_row['parallax_depth_6'] = post('level_depth_6');
			$insert_row['parallax_depth_7'] = post('level_depth_7');
			$insert_row['parallax_depth_8'] = post('level_depth_8');
			$insert_row['parallax_depth_9'] = post('level_depth_9');
			$insert_row['parallax_depth_10'] = post('level_depth_10');

			#spinner
			$insert_row['spinner'] = post('spinner');
			$insert_row['spinner_color'] = post('spinner_color');

			#navigation
			$insert_row['navigation_stop_on_hover'] = post('stop_on_hover');
			$insert_row['navigation_keyboard'] = post('keyboard_navigation');
			$insert_row['navigation_style'] = post('navition_style');
			$insert_row['navigation_bullet_type'] = post('bullet_type');
			$insert_row['navigation_arrows'] = post('navigation_arrows');
			$insert_row['navigation_show'] = post('show_navigation');
			$insert_row['navigation_hide_after'] = post('hide_navigation_after');
			$insert_row['navigation_horizontal_align'] = post('navigation_horizontal_align');
			$insert_row['navigation_vertical_align'] = post('navigation_vertical_align');
			$insert_row['navigation_horizontal_offset'] = post('navigation_horizontal_offset');
			$insert_row['navigation_vertical_offset'] = post('navigation_vertical_offset');
			$insert_row['navigation_left_arrow_horizontal_align'] = post('left_arrow_horizontal_align');
			$insert_row['navigation_left_arrow_vertical_align'] = post('left_arrow_vertical_align');
			$insert_row['navigation_left_arrow_horizontal_offset'] = post('left_arrow_horizontal_offset');
			$insert_row['navigation_left_arrow_vertical_offset'] = post('left_arrow_vertical_offset');
			$insert_row['navigation_right_arrow_horizontal_align'] = post('right_arrow_horizontal_align');
			$insert_row['navigation_right_arrow_vertical_align'] = post('right_arrow_vertical_align');
			$insert_row['navigation_right_arrow_horizontal_offset'] = post('right_arrow_horizontal_offset');
			$insert_row['navigation_right_arrow_vertical_offset'] = post('right_arrow_vertical_offset');

			#thumbnails
			$insert_row['thumb_width'] = post('thumb_width');
			$insert_row['thumb_height'] = post('thumb_height');
			$insert_row['thumb_amount'] = post('thumb_amount');

			#mobile touch
			$insert_row['touch_enabled'] = post('touch_enabled');
			$insert_row['swipe_velocity'] = post('swipe_velocity');
			$insert_row['swipe_min_touches'] = post('swipe_min_touches');
			$insert_row['swipe_max_touches'] = post('swipe_max_touches');
			$insert_row['swipe_drag_block_vertical'] = post('drag_block_vertical');

			#mobile visibility
			$insert_row['disable_slider_on_mobile'] = post('disabled_slider_on_mobile');
			$insert_row['hide_slider_under_width'] = post('hide_slider_under_width');
			$insert_row['hide_defined_layers_under_width'] = post('hide_defined_layers_under_width');
			$insert_row['hide_all_layers_under_width'] = post('hide_all_layers_under_width');
			$insert_row['hide_arrows_on_mobile'] = post('hide_arrows_on_mobile');
			
			$insert_row['hide_bullets_on_mobile'] = post('hide_bullets_on_mobile');
			$insert_row['hide_thumbnails_on_mobile'] = post('hide_thumbnails_on_mobile');
			$insert_row['hide_thumbs_under_width'] = post('hide_thumbs_under_width');
			$insert_row['hide_mobile_nav_after'] = post('hide_mobile_nav_after');

			#single slide
			$insert_row['loop_slide'] = post('loop_slide');

			#alternative first slide
			$insert_row['start_slide'] = post('start_with_slide');
			$insert_row['start_slide_enabled'] = post('start_with_slide_enabled');
			$insert_row['first_transition_type'] = post('first_transition_type');
			$insert_row['first_transition_duration'] = post('first_transition_duration');
			$insert_row['first_transition_slot_amount'] = post('first_transition_slot_amount');

			echo $this->model->update($insert_row);

			
		}
	}


}