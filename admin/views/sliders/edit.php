
<div class="main-content">

    <div class="page-content">

        <div class="page-header">
            <h1>
                Edit New Slider     
            </h1>
        </div><!-- /.page-header -->
        <div id="result">
        </div>
		 <form id="form_slider" method="post" action="<?php echo URL;?>sliders/update"  enctype="multipart/form-data" >
		 <div class="row-fluid hide" id="slider">
		 	<div class="span7">
		 		 <div class="widget-box">
                    <div class="widget-header">
                        <h5>Main Slider Settings</h5>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main form-horizontal ">
                        	 <div class="control-group">
                                <label class="control-label" for="slider_name"><span class="red">*</span> Slider Name:</label>
                                    <div class="controls">
                                        <input type="text" id="slider_name" name="slider_name" class="input" value="<?php echo $slider['slider_name'];?>">           
                                    	<input type="hidden" name="id" value="<?php echo $slider['id']; ?>">
                                    </div>
                                    <span class="controls grey"><i>The title of the slider. Example: Slider1</i></span>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="slider_nick_name"><span class="red">*</span> Slider Alias:</label>
                                    <div class="controls">
                                        <input type="text" id="slider_nick_name" name="slider_nick_name" class="input" value="<?php echo $slider['slider_alias'];?>"> 
                                    </div>
                                    <span class="controls grey"><i>The alias that will be used for embedding the slider. Example: slider1</i></span>   
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="slider_short_code"> Slider Short Code:</label>
                                    <div class="controls">
                                        <input type="text" id="slider_short_code" name="slider_short_code" class="input" value='[rev_slider <?php echo $slider['slider_alias'];?>]' disabled>           
                                    </div>
                            </div>
							<div class="page-header"></div>
                            <div class="control-group">
								<label class="control-label">Source Type</label>

								<div class="controls">
								
									<input name="source_type" type="radio" value = "POSTS" class="ace" <?php echo $slider['source_type'] == 'POSTS'? 'checked': ''; ?>>
									<span class="lbl"> Posts </span>
									<input name="source_type" type="radio" value = "SPECIFIC_POSTS"class="ace" <?php echo $slider['source_type'] == 'SPECIFIC_POSTS'? 'checked': ''; ?>>
									<span class="lbl"> Specific Posts </span>
									<input name="source_type" type="radio" value = "GALLERY" class="ace" <?php echo $slider['source_type'] == 'GALLERY'? 'checked': ''; ?>>
									<span class="lbl"> Gallery </span>
									
									
								</div>

							</div>
							<div class="page-header"></div>
							<div class="control-group">
								<label class="control-label">Slider Layout:</label>

								<div class="controls">
									
									<input name="slider_layout" type="radio"  value = "FIXED" class="ace" <?php echo $slider['slider_layout'] == 'FIXED'? 'checked': ''; ?>>
									<span class="lbl"> Fixed </span>
									<input name="slider_layout" type="radio"  value = "CUSTOM" class="ace" <?php echo $slider['slider_layout'] == 'CUSTOM'? 'checked': ''; ?>>
									<span class="lbl"> Custom </span>
									<input name="slider_layout" type="radio"  value = "AUTO_RESPONSIVE" class="ace" <?php echo $slider['slider_layout'] == 'AUTO_RESPONSIVE'? 'checked': ''; ?>>
									<span class="lbl"> Auto Responsive </span>
									<input name="slider_layout" type="radio"  value = "FULL_SCREEN" class="ace" <?php echo $slider['slider_layout'] == 'FULL_SCREEN'? 'checked': ''; ?>>
									<span class="lbl"> Full Screen </span>
								
									
								</div>

							</div>
							<div class="control-group">
								<label class="control-label">Unlimited Height:</label>

								<div class="controls">
								
									<input name="unli_height" type="radio" class="ace" value = "ON" <?php echo $slider['unlimited_height'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="unli_height" type="radio" class="ace" value = "OFF" <?php echo $slider['unlimited_height'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
								
									
								</div>

							</div>
							<div class="control-group">
								<label class="control-label">Force Full Width:</label>

								<div class="controls">
								
									<input name="force_full_width" type="radio" class="ace" value="ON" <?php echo $slider['force_full_width'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="force_full_width" type="radio" class="ace" value="OFF" <?php echo $slider['force_full_width'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
								
									
								</div>

							</div>
							<div class="control-group">
								<label class="control-label">Grid Settings:</label>

								<div class="controls">
									<span>Grid Width: </span><input type="text" id="grid_width" class="input-small int_only"  name="grid_width"  value = "<?php echo $slider['grid_width'];?>" disabled> <span> Grid Height: </span><input type="text" id="grid_height" name="grid_height" class="input-small int_only" value = "<?php echo $slider['grid_height'];?>" disabled>
								</div>

							</div>
							<div class="page-header">
								<span class="grey"><strong>Layout Example</strong> (Can be different based on Theme Style)</span>
							</div>
							<div class="row-fluid">
								<div id="layout_container" style="height: 300px; border: 1px solid grey;">
									<div id="left" style="height:298px; width: 100px; float:left">
										<span class="label label-info">Browser</span>
									</div>
									<div id="slider_holder" style="height:298px; width: 456px; background:#EEEEEE; float:left">
										<span class="label label-info">Page</span>
										<div id="slider_container" style="margin:40px 0 0 0; background:#95A5A6; height: 180px;">
											<div style="height: 22px;"><span class="label label-info">Slider</span></div>
											<div id="sider" style="height:130px; border:2px dotted #ffffff; width:400px; margin: 0 0 0 25px;">
											<span class="label label-info" style="margin: 110px 0 0 0; float:right">Caption Grid</span>
											</div>

										</div>
									</div>
									<div id="left" style="height:298px; width: 100px; float:left;">
									</div>
								</div>
							</div>
							
							<div class="row-fluid" style="margin: 10px 0 0 0;">
								<button class="btn btn-success">Save Slider</button>&nbsp; &nbsp; &nbsp;<button class="btn btn-warning" onclick="back_to_slider() ;return false;">Close</button>&nbsp; &nbsp; &nbsp;<button class="btn btn-danger" onclick="back_to_slider() ;return false;">Delete Slider</button>&nbsp; &nbsp; &nbsp;<button class="btn btn-info" onclick="slides_slider(<?php echo $slider['id']; ?>) ;return false;">Edit Slides</button>
							</div>
                        </div>
                    </div>
                 </div>
		 	</div>
		
		 	<div class="span5">
		 		<div id="accordion" class="accordion-style2">
					<!-- GENERAL SETTINGS-->
					<div class="group">

						<h3 class="accordion-header"> General Settings</h3>

						<div>
							
								<div class="row-fluid">
									<label class="span6" for="gn_delay">Delay</label>

									<div class="span5">
										<input type="text" class="int_only" value="<?php echo $slider['general_settings_delay']; ?>" id="delay" name="delay">
									</div>

								</div>
								<div class="row-fluid">
									<label class="span6" for="shuffle_mode">Shuffle Mode</label>

									<div class="span5">
										
										<input name="shuffle_mode" type="radio" class="ace" value="ON" <?php echo $slider['general_settings_shuffle_mode'] == 'ON'? 'checked': ''; ?>>
										<span class="lbl"> On </span>
										<input name="shuffle_mode" type="radio" class="ace" value= "OFF" <?php echo $slider['general_settings_shuffle_mode'] == 'OFF'? 'checked': ''; ?>>
										<span class="lbl"> Off </span>
										
									</div>

								</div>
								<div class="row-fluid">
									<label class="span6" for="lazy_load">Lazy Load</label>

									<div class="span5">
										
										<input name="lazy_load" type="radio" value = "ON" class="ace" <?php echo $slider['general_settings_lazy_load'] == 'ON'? 'checked': ''; ?>>
										<span class="lbl"> On </span>
										<input name="lazy_load" type="radio" value = "OFF" class="ace" <?php echo $slider['general_settings_lazy_load'] == 'OFF'? 'checked': ''; ?>>
										<span class="lbl"> Off </span>
										
									</div>

								</div>
								<div class="row-fluid">
									<label class="span6" for="wmpl">User Multi Language (WMPL)</label>

									<div class="span5">
										
										<input name="wmpl" type="radio" value = "ON" class="ace" <?php echo $slider['general_settings_wmpl'] == 'ON'? 'checked': ''; ?>>
										<span class="lbl"> On </span>
										<input name="wmpl" type="radio" value = "OFF" class="ace" <?php echo $slider['general_settings_wmpl'] == 'OFF'? 'checked': ''; ?>>
										<span class="lbl"> Off </span>
										
									</div>

								</div>
								<div class="row-fluid">
									<label class="span6" for="enable_static_layers">Enable Static Layers</label>

									<div class="span5">
										
										<input name="enable_static_layers" value = "ON" type="radio" class="ace" <?php echo $slider['general_settings_enable_static_layers'] == 'ON'? 'checked': ''; ?>>
										<span class="lbl"> On </span>
										<input name="enable_static_layers" value = "OFF" type="radio" class="ace" <?php echo $slider['general_settings_enable_static_layers'] == 'OFF'? 'checked': ''; ?>>
										<span class="lbl"> Off </span>
										
									</div>

								</div>

								<div class="page-header">
								</div>

								<div class="row-fluid">
									<label class="span6" for="stop_slider">Stop Slider</label>

									<div class="span5">
										<input name="stop_slider" type="radio" value = "ON" class="ace stop_slider" <?php echo $slider['general_settings_stop_slider'] == 'ON'? 'checked': ''; ?>>
										<span class="lbl"> On </span>
										<input name="stop_slider" type="radio" value = "OFF" class="ace stop_slider" <?php echo $slider['general_settings_stop_slider'] == 'OFF'? 'checked': ''; ?>>
										<span class="lbl"> Off </span>
										
									</div>

								</div>
								<div class="row-fluid">
									<label class="span6" for="start_after_loops">Start After Loops</label>

									<div class="span5">
										<input type="text" class="int_only" id = "start_after_loops" name = "start_after_loops" class="text" value="<?php echo $slider['general_settings_start_after_loops']; ?>" >
									</div>

								</div>
								<div class="row-fluid">
									<label class="span6" for="stop_at_slide">Stop At Slide</label>

									<div class="span5">
										<input type="text" class="int_only" id="stop_at_slide" name="stop_at_slide" class="text" value="<?php echo $slider['general_settings_stop_at_slide']; ?>">
									</div>

								</div>
						</div>
					</div><!--END GENERAL SETTINGS-->

					<!--GOOGLE FONT SETTINGS-->
					<div class="group">
						<h3 class="accordion-header">Google Font Settings</h3>

						<div>
							<p>This menu point will be deprecated in V5.0. Please use 
								the Punch Fonts Menu
							</p>
							<div class="row-fluid">
								<label class="span6" for="load_google_font">Load Google Font</label>

								<div class="span5">
								
									<input name="load_google_font" type="radio" value="ON" class="ace" <?php echo $slider['load_google_fonts'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> Yes </span>
									<input name="load_google_font" type="radio" value="OFF" class="ace" <?php echo $slider['load_google_fonts'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> No </span>
									
								</div>

							</div>
							<div class="row-fluid">
								<label class="span12" for="gn_delay">The google font families to load:</label>

								<div class="span12" id="font_holder">
									<span class="input-icon input-icon-right">
										<input type="text" id="google_fonts" name="google_fonts" class="input-xlarge" value='<?php echo $slider['google_fonts']; ?>'>
										
										<i class="icon-trash red delete_font"></i>
									</span>
								</div>
								<div class="span12">
									<a href="#" class="green" id="add_new_font"><i class="icon-plus"></i>&nbsp; Add new</a>
								</div>
							</div>
							
							<p>To add more google fonts plese read <a href="https://developers.google.com/fonts/docs/getting_started" target="_blank">this tutorial</a></p>
						</div>
					</div><!--END GOOGLE FONT SETTINGS-->
					
					<!--POSITION-->
					<div class="group">
						<h3 class="accordion-header">Position</h3>

						<div>
							<div class="row-fluid">
								<label class="span6" for="position">Position on the page</label>

								<div class="span5">
									<select name="position">
										<option value="Top" <?php echo $slider['position'] == 'Top'? 'selected': ''; ?>>Top</option>
										<option value="Center" <?php echo $slider['position'] == 'Center'? 'selected': ''; ?>>Center</option>
										<option value="Bottom" <?php echo $slider['position'] == 'Bottom'? 'selected': ''; ?>>Bottom</option>
									</select>
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="margin_top">Margin Top</label>

								<div class="span5">
									<input type="text" id="margin_top" name="margin_top" class="input-small int_only" value="<?php echo $slider['position_margin_top']; ?>"><span> px</span>
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="margin_bottom">Margin Bottom</label>

								<div class="span5">
									<input type="text" id="margin_bottom" name="margin_bottom" class="input-small int_only" value="<?php echo $slider['position_margin_bottom']; ?>"><span> px</span>
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="margin_left">Margin Left</label>

								<div class="span5">
									<input type="text" id="margin_left" name="margin_left" class="input-small int_only" value="<?php echo $slider['position_margin_left']; ?>"><span> px</span>
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="margin_right">Margin Right</label>

								<div class="span5">
									<input type="text" id="margin_right"  name= "margin_right" class="input-small int_only" value="<?php echo $slider['position_margin_right']; ?>"><span> px</span>
								</div>

							</div>
						</div>
					</div><!--END POSITION-->
					
					<!--APPEARANCE-->
					<div class="group">
						<h3 class="accordion-header">Appearance</h3>

						<div>
							<div class="row-fluid">
								<label class="span6" for="shadow_type">Shadow Type</label>

								<div class="span5">
									<select class="input-small" name="shadow_type" id="shadow_type">
										<option value="0">None</option>
										<option value="1" <?php echo $slider['appearance_shadow_type'] == '1'? 'selected': ''; ?>>1</option>
										<option value="2" <?php echo $slider['appearance_shadow_type'] == '2'? 'selected': ''; ?>>2</option>
										<option value="3" <?php echo $slider['appearance_shadow_type'] == '3'? 'selected': ''; ?>>3</option>
									</select>
								</div>

							</div>
							<div class="row-fluid hide" id="shadow_type_holder">
								<img src="" id="shadow_type_image" alt="shadow">
							</div>
							<div class="row-fluid">
								<label class="span6" for="show_timer_line">Show Timer Line</label>

								<div class="span5" >
									<select name="show_timer_line" class="input-small">
										<option value="Top" >Top</option>
										<option value="Center" <?php echo $slider['appearance_show_timer_line'] == 'Center'? 'selected': ''; ?>>Center</option>
										<option value="Bottom" <?php echo $slider['appearance_show_timer_line'] == 'Bottom'? 'selected': ''; ?>>Bottom</option>
									</select>
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="padding">Padding (Border)</label>

								<div class="span5">
									<input type="text" name= "padding" id="padding" class="input-small int_only" value="<?php echo $slider['appearance_padding']; ?>">
								</div>

							</div>
							<div class="page-header">
								<span class="grey"> Background Image Options (Bestused with transparent slides)</span>
							</div>
							<div class="row-fluid">
								<label class="span6" for="background_color">Background Color</label>

								<div class="span5">
									<input type="hidden" id="hidden_background_color" name="background_color" value="<?php echo $slider['appearance_background_color'];?>">
									<input type="text" id="background_color" class="input-small color_picker" value="<?php echo $slider['appearance_background_color'];?>">
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="dotted_overlay">Dotted Overlay</label>

								<div class="span5">
									<select class="input-small" name="dotted_overlay" id="dotted_overlay">
										<option value="0">none</option>
										<option value="2X2_BLACK" <?php echo $slider['appearance_dotted_overlay'] == '2X2_BLACK'? 'selected': ''; ?>>2X2 BLACK</option>
										<option value="2X2_WHITE" <?php echo $slider['appearance_dotted_overlay'] == '2X2_WHITE'? 'selected': ''; ?>>2X2 WHITE</option>
										<option value="3X3_BLACK" <?php echo $slider['appearance_dotted_overlay'] == '3X3_BLACK'? 'selected': ''; ?>>3X3 BLACK</option>
										<option value="3X3_WHITE" <?php echo $slider['appearance_dotted_overlay'] == '3X3_WHITE'? 'selected': ''; ?>>3X3 WHITE</option>
									</select>
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="show_background_image">Show Background Image</label>

								<div class="span5">
									
									<input name="show_background_image" value="Y" type="radio" class="ace show_background_image" <?php echo $slider['appearance_show_background_image'] == 'Y'? 'checked': ''; ?>>
									<span class="lbl"> Yes </span>
									<input name="show_background_image" value="N" type="radio" class="ace show_background_image" <?php echo $slider['appearance_show_background_image'] == 'N'? 'checked': ''; ?>>
									<span class="lbl"> No </span>
									
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="background_image_url">Background Image Url</label>

								<div class="span5">
									<input type="text" class="input-small" id="background_image_url" name="background_image_url" value="<?php echo $slider['appearance_background_image_url']; ?>">
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="background_fit">Background Fit</label>

								<div class="span5">
									<select class="input-medium" id="background_fit" name="background_fit">
										<option value="COVER">cover</option>
										<option value="CONTAIN" <?php echo $slider['appearance_background_fit'] == 'COVER'? 'selected': ''; ?>>contain</option>
										<option value="NORMAL" <?php echo $slider['appearance_background_fit'] == 'NORMAL'? 'selected': ''; ?>>normal</option>
									</select>
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="background_repeat">Background Repeat</label>

								<div class="span5">
									<select class="input-medium" id="background_repeat" name="background_repeat">
										<option value="NO_REPEAT">no-repeat</option>
										<option value="REPEAT" <?php echo $slider['appearance_background_repeat'] == 'REPEAT'? 'selected': ''; ?>>repeat</option>
										<option value="REPEAT_X" <?php echo $slider['appearance_background_repeat'] == 'REPEAT_X'? 'selected': ''; ?>>repeat-x</option>
										<option value="REPEAT_Y" <?php echo $slider['appearance_background_repeat'] == 'REPEAT_Y'? 'selected': ''; ?>>repeat-y</option>
									</select>
								</div>

							</div>
							<div class="row-fluid">
								<label class="span6" for="background_position">Background Position</label>

								<div class="span5">
									<select class="input-medium" id="background_position" name="background_position">
										<option value="CENTER_TOP">center top</option>
										<option value="CENTER_RIGHT" <?php echo $slider['appearance_background_position'] == 'CENTER_RIGHT'? 'selected': ''; ?>>center right</option>
										<option value="CENTER_BOTTOM" <?php echo $slider['appearance_background_position'] == 'CENTER_BOTTOM'? 'selected': ''; ?>>center bottom</option>
										<option value="CENTER_CENTER" <?php echo $slider['appearance_background_position'] == 'CENTER_CENTER'? 'selected': ''; ?>>center center</option>
										<option value="LEFT_TOP" <?php echo $slider['appearance_background_position'] == 'LEFT_TOP'? 'selected': ''; ?>>left top</option>
										<option value="LEFT_CENTER" <?php echo $slider['appearance_background_position'] == 'LEFT_CENTER'? 'selected': ''; ?>>left center</option>
										<option value="LEFT_BOTTOM" <?php echo $slider['appearance_background_position'] == 'LEFT_BOTTOM'? 'selected': ''; ?>>left bottom</option>
										<option value="RIGHT_TOP" <?php echo $slider['appearance_background_position'] == 'RIGHT_TOP'? 'selected': ''; ?>>right top</option>
										<option value="RIGHT_CENTER" <?php echo $slider['appearance_background_position'] == 'RIGHT_CENTER'? 'selected': ''; ?>>right center</option>
										<option value="RIGHT_BOTTOM" <?php echo $slider['appearance_background_position'] == 'RIGHT_BOTTOM'? 'selected': ''; ?>>right bottom</option>
									</select>
								</div>

							</div>
						</div>
					</div><!--END APPEARANCE-->
					
					<!--PARALLAX-->
					<div class="group">
						<h3 class="accordion-header">Parallax</h3>

						<div>
							<div class="row-fluid">
								<label class="span6" for="enable_parallax">Enable Parallax</label>

								<div class="span5">
									<label>
										<input name="enable_parallax" type="radio" value="Y" class="ace" <?php echo $slider['parallax'] == 'Y'? 'checked': ''; ?>>
										<span class="lbl"> On </span>
										<input name="enable_parallax" type="radio" value="N" class="ace" <?php echo $slider['parallax'] == 'N'? 'checked': ''; ?>>
										<span class="lbl"> Off </span>
									</label>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="disabled_on_mobile">Disable on Mobile</label>

								<div class="span5">
									<select class="input-small" name="disabled_on_mobile">
										<option value="OFF">Off</option>
										<option value="ON" <?php echo $slider['parallax_disabled_on_mobile'] == 'Y'? 'selected': ''; ?>>On</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="type">Type</label>

								<div class="span5">
									<select name="type" id="type">
										<option value="MOUSE_ONLY" >Mouse Position</option>
										<option value="SCROLL_ONLY" <?php echo $slider['parallax_type'] == 'SCROLL_ONLY'? 'selected': ''; ?>>Scroll Position</option>
										<option value="MOUSE_AND_SCROLL" <?php echo $slider['parallax_type'] == 'MOUSE_AND_SCROLL'? 'selected': ''; ?>>Mouse and Scroll</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="bg_freeze">BG Freeze</label>

								<div class="span5">
									<select name="bg_freeze" id="bg_freeze"> 
										<option value="OFF">Off</option>
										<option value="ON" <?php echo $slider['parallax_bg_freez'] == 'ON'? 'selected': ''; ?>>On</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_1">Level Depth 1</label>

								<div class="span5">
									<input type="text" name="level_depth_1" id="level_depth_1" class="int_only input-small" value="<?php echo $slider['parallax_depth_1']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_2">Level Depth 2</label>

								<div class="span5">
									<input type="text" name="level_depth_2" id="level_depth_2" class="int_only input-small" value="<?php echo $slider['parallax_depth_2']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_3">Level Depth 3</label>

								<div class="span5">
									<input type="text" name="level_depth_3" id="level_depth_3" class="int_only input-small" value="<?php echo $slider['parallax_depth_3']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_4" >Level Depth 4</label>

								<div class="span5">
									<input type="text" name="level_depth_4" id="level_depth_4" class="int_only input-small" value="<?php echo $slider['parallax_depth_4']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_5">Level Depth 5</label>

								<div class="span5">
									<input type="text" name="level_depth_5" id="level_depth_5" class="int_only input-small"  value="<?php echo $slider['parallax_depth_5']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_6">Level Depth 6</label>

								<div class="span5">
									<input type="text" name = "level_depth_6" id="level_depth_6" class="int_only input-small"  value="<?php echo $slider['parallax_depth_6']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_7">Level Depth 7</label>

								<div class="span5">
									<input type="text" name="level_depth_7" id="level_depth_7" class="int_only input-small" value="<?php echo $slider['parallax_depth_7']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_8">Level Depth 8</label>

								<div class="span5">
									<input type="text" name="level_depth_8" id="level_depth_8" class="int_only input-small" value="<?php echo $slider['parallax_depth_8']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_9">Level Depth 9</label>

								<div class="span5">
									<input type="text" name="level_depth_9" id="level_depth_9" class="int_only input-small" value="<?php echo $slider['parallax_depth_9']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="level_depth_10">Level Depth 10</label>

								<div class="span5">
									<input type="text" name="level_depth_10" id="level_depth_10" class="int_only input-small" value="<?php echo $slider['parallax_depth_10']; ?>">
								</div>	
							</div>
						</div>
					</div><!--END PARALLAX-->

					<!--SPINNER-->
					<div class="group">
						<h3 class="accordion-header">Spinner</h3>

						<div>
							<div class="row-fluid">
								<div>
									<div id="spinner_holder" style="height:80px;">

									</div>
								</div>
							</div>
							<div class="row-fluid">
								<label class="span6" for="spinner">Choose Spinner</label>

								<div class="span5">
									<select class="input-small" name="spinner" id="spinner">
										<option value="1">1</option>
										<option value="2" <?php echo $slider['spinner'] == '2'? 'selected': ''; ?>>2</option>
										<option value="3" <?php echo $slider['spinner'] == '3'? 'selected': ''; ?>>3</option>
										<option value="4" <?php echo $slider['spinner'] == '4'? 'selected': ''; ?>>4</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="spinner_color">Spinner Color</label>

								<div class="span5">
									<input type="hidden" id="hidden_spinner_color" name="spinner_color" value="<?php echo $slider['spinner_color']; ?>">
									<input type="text" class="input-small" id="spinner_color" value="<?php echo $slider['spinner_color']; ?>">
								</div>	
							</div>
						</div>
					</div><!--END SPINNER-->
					
					<!--NAVIGATIONS-->
					<div class="group">
						<h3 class="accordion-header">Navigation</h3>

						<div>
							<div class="row-fluid">
								<label class="span6" for="stop_on_hover">Stop On Hover</label>

								<div class="span5">
									
									<input name="stop_on_hover" value= "ON" type="radio" class="ace" <?php echo $slider['navigation_stop_on_hover'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="stop_on_hover" value = "OFF" type="radio" class="ace" <?php echo $slider['navigation_stop_on_hover'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
									
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="keyboard_navigation">Keyboard Navigation</label>

								<div class="span5">
									
									<input name="keyboard_navigation" value="ON" type="radio" class="ace" <?php echo $slider['navigation_keyboard'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="keyboard_navigation" value="OFF" type="radio" class="ace" <?php echo $slider['navigation_keyboard'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
									
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="navition_style">Navigation Style</label>

								<div class="span5">
									<select name="navition_style">
										<option value="ROUND" >Round</option>
										<option value="NAVBAR" <?php echo $slider['navigation_style'] == 'NAVBAR'? 'selected': ''; ?>>Navbar</option>
										<option value="PREVIEW_1" <?php echo $slider['navigation_style'] == 'PREVIEW_1'? 'selected': ''; ?>>Preview 1</option>
										<option value="PREVIEW_2" <?php echo $slider['navigation_style'] == 'PREVIEW_2'? 'selected': ''; ?>>Preview 2</option>
										<option value="PREVIEW_3" <?php echo $slider['navigation_style'] == 'PREVIEW_3'? 'selected': ''; ?>>Preview 3</option>
										<option value="PREVIEW_4" <?php echo $slider['navigation_style'] == 'PREVIEW_4'? 'selected': ''; ?>>Preview 4</option>
										<option value="OLD_ROUND" <?php echo $slider['navigation_style'] == 'OLD_ROUND'? 'selected': ''; ?>>Old Round</option>
										<option value="OLD_SQUARE" <?php echo $slider['navigation_style'] == 'OLD_SQUARE'? 'selected': ''; ?>>Old Square</option>
										<option value="OLD_NAVBAR" <?php echo $slider['navigation_style'] == 'OLD_NAVBAR'? 'selected': ''; ?>>Old Navbar</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="bullet_type">Bullet Type</label>

								<div class="span5">
									<select name="bullet_type">
										<option value="NONE">None</option>
										<option value="BULLET" <?php echo $slider['navigation_bullet_type'] == 'BULLET'? 'selected': ''; ?>>Bullet</option>
										<option value="THUMB" <?php echo $slider['navigation_bullet_type'] == 'THUMB'? 'selected': ''; ?>>Thumb</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="navigation_arrows">Navigation Arrows</label>

								<div class="span5">
									<select name="navigation_arrows">
										<option value="WITH_BULLETS" <?php echo $slider['navigation_arrow'] == 'WITH_BULLETS'? 'selected': ''; ?>>With Bullets</option>
										<option value="SOLO" <?php echo $slider['navigation_arrow'] == 'SOLO'? 'selected': ''; ?>>Solo</option>
										<option value="NONE" <?php echo $slider['navigation_arrow'] == ''? 'selected': ''; ?> >None</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="show_navigation">Always Show Navigation</label>

								<div class="span5">
									<select name="show_navigation">
										<option value="NO">No</option>
										<option value="YES" <?php echo $slider['navigation_show'] == 'YES'? 'checked': ''; ?>>Yes</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_navigation_after">Hide Navigation After</label>

								<div class="span5">
									<input type="text" name="hide_navigation_after" id="hide_navigation_after" class="int_only input-small" value="200"><span> ms</span>
								</div>	
							</div>
							<div class="page-header">
								<span class="grey">Bullets/Thumbnail Position</span>
							</div>
							<div class="row-fluid">
								<label class="span6" for="navigation_horizontal_align">Navigation Horizontal Align</label>

								<div class="span5">
									<select name="navigation_horizontal_align">
										<option value="Top">Top</option>
										<option value="Center" <?php echo $slider['navigation_horizontal_align'] == 'Center'? 'selected': ''; ?>>Center</option>
										<option value="Bottom" <?php echo $slider['navigation_horizontal_align'] == 'Bottom'? 'selected': ''; ?>>Bottom</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="navigation_vertical_align">Navigation Vertical Align</label>

								<div class="span5">
									<select name="navigation_vertical_align">
										<option value="Top" >Top</option>
										<option value="Center" <?php echo $slider['navigation_vertical_align'] == 'Center'? 'selected': ''; ?>>Center</option>
										<option value="Bottom" <?php echo $slider['navigation_vertical_align'] == 'Bottom'? 'selected': ''; ?>>Bottom</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="navigation_horizontal_offset">Navigation Horizontal Offset</label>

								<div class="span5">
									<input type="text" name="navigation_horizontal_offset" id="navigation_horizontal_offset" class="int_only input-small" value="<?php echo $slider['navigation_horizontal_offset'];?>"><span> px</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="navigation_vertical_offset">Navigation Vertical Offset</label>

								<div class="span5">
									<input type="text" name="navigation_vertical_offset" id="navigation_vertical_offset" class="int_only input-small" value="<?php echo $slider['navigation_vertical_offset']; ?>"><span> px</span>
								</div>	
							</div>
							<div class="page-header">
								<span class="grey">Left Arrow Position</span>
							</div>
							<div class="row-fluid">
								<label class="span6" for="left_arrow_horizontal_align">Left Arrow Horizontal Align</label>

								<div class="span5">
									<select name="left_arrow_horizontal_align">
										<option value="LEFT">Left</option>
										<option value="CENTER" <?php echo $slider['navigation_left_arrow_horizontal_align'] == 'CENTER'? 'selected': ''; ?>>Center</option>
										<option value="RIGHT" <?php echo $slider['navigation_left_arrow_horizontal_align'] == 'RIGHT'? 'selected': ''; ?>>Right</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="left_arrow_vertical_align">Left Arrow Vertical Align</label>

								<div class="span5">
									<select name="left_arrow_vertical_align">
										<option value="TOP">Top</option>
										<option value="CENTER" <?php echo $slider['navigation_left_arrow_vertical_align'] == 'CENTER'? 'selected': ''; ?>>Center</option>
										<option value="BOTTOM" <?php echo $slider['navigation_left_arrow_vertical_align'] == 'BOTTOM'? 'selected': ''; ?>>Bottom</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="left_arrow_horizontal_offset">Left Arrow Horizontal Offset</label>

								<div class="span5">
									<input type="text" name= "left_arrow_horizontal_offset" id="left_arrow_horizontal_offset" class="int_only input-small" value="<?php echo $slider['navigation_left_arrow_horizontal_offset']; ?>"><span> px</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="left_arrow_vertical_offset">Left Arrow Vertical Offset</label>

								<div class="span5">
									<input type="text" name="left_arrow_vertical_offset" id="left_arrow_Vertical_offset" class="int_only input-small" value="<?php echo $slider['navigation_left_arrow_vertical_offset'];?>"><span> px</span>
								</div>	
							</div>
							
							<div class="page-header">
								<span class="grey">Right Arrow Position</span>
							</div>
							<div class="row-fluid">
								<label class="span6" for="right_arrow_horizontal_align">Right Arrow Horizontal Align</label>

								<div class="span5">
									<select name="right_arrow_horizontal_align">
										<option value="LEFT">LEFT</option>
										<option value="CENTER" <?php echo $slider['navigation_right_arrow_horizontal_align'] == 'CENTER'? 'selected': ''; ?>>CENTER</option>
										<option value="RIGHT" <?php echo $slider['navigation_right_arrow_horizontal_align'] == 'RIGHT'? 'selected': ''; ?>>RIGHT</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="right_arrow_vertical_align">Right Arrow Vertical Align</label>

								<div class="span5">
									<select name="right_arrow_vertical_align">
										<option value="TOP">RIGHT</option>
										<option value="CENTER" <?php echo $slider['navigation_right_arrow_vertical_align'] == 'CENTER'? 'selected': ''; ?>>CENTER</option>
										<option value="BOTTOM" <?php echo $slider['navigation_right_arrow_vertical_align'] == 'BOTTOM'? 'selected': ''; ?>>BOTTOM</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="right_arrow_horizontal_offset">Right Arrow Horizontal Offset</label>

								<div class="span5">
									<input type="text" name="right_arrow_horizontal_offset" id="right_arrow_horizontal_offset" class="int_only input-small" value="<?php echo $slider['navigation_right_arrow_horizontal_offset']; ?>"><span> px</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="right_arrow_vertical_offset">Right Arrow Vertical Offset</label>

								<div class="span5">
									<input type="text" name="right_arrow_vertical_offset" id="right_arrow_vertical_offset" class="int_only input-small" value="<?php echo $slider['navigation_right_arrow_vertical_offset']; ?>"><span> px</span>
								</div>	
							</div>
						</div>
					</div><!--END NAVIGATIONS-->

					<!--THUMBNAILS-->
					<div class="group">
						<h3 class="accordion-header">Thumbnails</h3>

						<div>
							<div class="row-fluid">
								<label class="span6" for="thumb_width">Thumb Width</label>

								<div class="span5">
									<input type="text" name="thumb_width" id="thumb_width" class="int_only input-small" value="<?php echo $slider['thumb_width'];?>"><span> px</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="thumb_height">Thumb Height</label>

								<div class="span5">
									<input type="text" name="thumb_height" id="thumb_height" class="int_only input-small" value="<?php echo $slider['thumb_height']; ?>"><span> px</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="thumb_amount">Thumb Amount</label>

								<div class="span5">
									<input type="text" name="thumb_amount" id="thumb_amount" class="int_only input-small" value="<?php echo $slider['thumb_amount']; ?>"><span> px</span>
								</div>	
							</div>
						</div>
					</div><!--EBD THUMBNAILS-->
					
					<!--MOBILE TOUCH-->
					<div class="group">
						<h3 class="accordion-header">Mobile Touch</h3>

						<div>
							<div class="row-fluid">
								<label class="span6" for="touch_enabled">Touch Enabled</label>

								<div class="span5">
										
									<input name="touch_enabled" value="ON" type="radio" class="ace" <?php echo $slider['touch_enabled'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="touch_enabled" value="OFF" type="radio" class="ace" <?php echo $slider['touch_enabled'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
									
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="swipe_velocity">Swipe Velocity (0 - 1)</label>

								<div class="span5">
									<input type="text" name="swipe_velocity" id="swipe_velocity" class=" int_only input-small" value="<?php echo $slider['swipe_velocity']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="swipe_min_touches">Swipe Min Touches</label>

								<div class="span5">
									<input type="text" name="swipe_min_touches" id="swipe_min_touches" class="int_only input-small" value="<?php echo $slider['swipe_min_touches']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="swipe_max_touches" >Swipe Max Touches</label>

								<div class="span5">
									<input type="text" name="swipe_max_touches" id="swipe_max_touches" class="int_only input-small" value="<?php echo $slider['swipe_max_touches']; ?>">
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="drag_block_vertical">Drag Block Vertical</label>

								<div class="span5">
										
									<input name="drag_block_vertical" value="ON" type="radio" class="ace" <?php echo $slider['swipe_drag_block_vertical'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="drag_block_vertical" value="OFF" type="radio" class="ace" <?php echo $slider['swipe_drag_block_vertical'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
								
								</div>	
							</div>
						</div>
					</div><!--END MOBILE TOUCH-->
					
					<!--MOBILE VISIBILITY-->
					<div class="group">
						<h3 class="accordion-header">Mobile Visibility</h3>

						<div>
							<div class="row-fluid">
								<label class="span6" for="disabled_slider_on_mobile">Disabled Slider on Mobile</label>

								<div class="span5">
										
									<input name="disabled_slider_on_mobile" value="ON" type="radio" class="ace" <?php echo $slider['disable_slider_on_mobile'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="disabled_slider_on_mobile" value="OFF" type="radio" class="ace" <?php echo $slider['disable_slider_on_mobile'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
										
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_slider_under_width">Hide Slider Under Width</label>

								<div class="span5">
									<input type="text" name="hide_slider_under_width" id="hide_slider_under_width" class="int_only input-small" value="<?php echo $slider['hide_slider_under_width']; ?>"><span> px</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_defined_layers_under_width">Hide Defined Layers Under Width</label>

								<div class="span5">
									<input type="text" name="hide_defined_layers_under_width" id="hide_defined_layers_under_width" class="int_only input-small" value="<?php echo $slider['hide_defined_layers_under_width']?>"><span> px</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_all_layers_under_width">Hide All Layers Under Width</label>

								<div class="span5">
									<input type="text" name="hide_all_layers_under_width" id="hide_all_layers_under_width" class="int_only input-small" value="<?php echo $slider['hide_all_layers_under_width']; ?>"><span> px</span>
								</div>	
							</div>
							<div class="page-header">
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_arrows_on_mobile">Hide Arrows on Mobile</label>

								<div class="span5">
										
									<input name="hide_arrows_on_mobile" value="ON" type="radio" class="ace" <?php echo $slider['hide_arrows_on_mobile'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="hide_arrows_on_mobile" value="OFF" type="radio" class="ace" <?php echo $slider['hide_arrows_on_mobile'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
										
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_bullets_on_mobile">Hide Bullets on Mobile</label>

								<div class="span5">
										
									<input name="hide_bullets_on_mobile" value="ON" type="radio" class="ace" <?php echo $slider['hide_bullets_on_mobile'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="hide_bullets_on_mobile" value="OFF" type="radio" class="ace" <?php echo $slider['hide_bullets_on_mobile'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
									
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_thumbnails_on_mobile">Hide Thumbnails on Mobile</label>

								<div class="span5">
									
									<input name="hide_thumbnails_on_mobile" value="ON" type="radio" class="ace" <?php echo $slider['hide_thumbnails_on_mobile'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="hide_thumbnails_on_mobile" value="OFF" type="radio" class="ace" <?php echo $slider['hide_thumbnails_on_mobile'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
									
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_thumbs_under_width">Hit Thumbs Under Width</label>

								<div class="span5">
									<input type="text" name="hide_thumbs_under_width" id="hide_thumbs_under_width" class="int_only input-small" value="<?php echo $slider['hide_thumbs_under_width']; ?>"><span> px</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="hide_mobile_nav_after">Hide Mobile Nav After</label>

								<div class="span5">
									<input type="text" name="hide_mobile_nav_after" id="hide_mobile_nav_after" class="int_only input-small" value="<?php echo $slider['hide_mobile_nav_after']; ?>"> <span> ms</span>
								</div>	
							</div>
						</div>
					</div><!--END MOBILE VISIBILITY-->
					
					<!--SINGLE SLIDE-->
					<div class="group">
						<h3 class="accordion-header">Single Slide</h3>

						<div>
						<div class="row-fluid">
								<label class="span6" for="loop_slide">Loop Slide</label>

								<div class="span5">
									
									<input name="loop_slide" value="ON" type="radio" class="ace" <?php echo $slider['loop_slide'] == 'ON'? 'checked': ''; ?>>
									<span class="lbl"> On </span>
									<input name="loop_slide" value="OFF" type="radio" class="ace" <?php echo $slider['loop_slide'] == 'OFF'? 'checked': ''; ?>>
									<span class="lbl"> Off </span>
										
								</div>	
							</div>
						</div>
					</div><!--END SINGLE SLIDE-->

					<!--ALTERATIVE FIRST SLIDE-->
					<div class="group">
						<h3 class="accordion-header">Alternative First Slide</h3>

						<div>
							<div class="row-fluid">
								<label class="span6" for="start_with_slide">Start With Slide</label>

								<div class="span5">
									<input type="text" name="start_with_slide" id="start_with_slide" class="int_only input-small" value = "<?php echo $slider['start_slide']; ?>">
								</div>	
							</div>
							<div class="page-header">
							</div>
							<div class="row-fluid">
								<label class="span6" for="start_with_slide_enabled">Start With Slide</label>

								<div class="span5">
									<label>
										<input name="start_with_slide_enabled" value="ON" type="radio" class="ace start_with_slide_enabled" <?php echo $slider['start_slide_enabled'] == 'ON'? 'checked': ''; ?>>
										<span class="lbl"> On </span>
										<input name="start_with_slide_enabled" value="OFF" type="radio" class="ace start_with_slide_enabled" <?php echo $slider['start_slide_enabled'] == 'OFF'? 'checked': ''; ?>>
										<span class="lbl"> Off </span>
									</label>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="first_transition_type">First Transition Type</label>
								<input type="hidden" id="first_transtitiontion_type_hidden" value="<?php echo $slider['first_transition_type']; ?>">
								<div class="span5">
									<select name="first_transition_type" id="first_transition_type">
										<option value="RANDOM_TRANSITIONS">RANDOM TRANSITIONS</option>
										<option value="RANDOM_FLAT">Random Flat</option>
										<option value="RANDOM_PREMUIM">Random Premuim</option>
										<option value="RANDOM_FLAT_AND_PREMUIM">Random Flat and Premium</option>
										<option value="SLIDING_TRANSITIONS">SLIDING TRANSITIONS</option>
										<option value="SLIDE_TO_TOP">Slide to Top</option>
										<option value="SLIDE_TO_BOTTOM">Slide to Bottom</option>
										<option value="SLIDE_TO_RIGHT">Slide to Right</option>
										<option value="SLIDE_TO_LEFT">Slide to Left</option>
										<option value="SLIDE_HORIZONTAL">Slide Horizontal</option>
										<option value="SLIDE_VERTICAL">Slide Vertical</option>
										<option value="SLIDE_BOXES">Slide Boxes</option>
										<option value="SLIDE_SLOTS_HORIZONTAL">Slide Slots Horizontal</option>
										<option value="SLIDE_SLOTS_VERTICAL">Slide Slots Vertical</option>
										<option value="FADE_TRANSITIONS">FADE TRANSITIONS</option>
										<option value="NO_TRANSITION">No Transition</option>
										<option value="FADE">Fade</option>
										<option value="FADE_BOXES">Fade Boxes</option>
										<option value="FADE_SLOTS_HORIZONTAL">Fade Slots Horizontal</option>
										<option value="FADE_SLOTS_VERTICAL">Fade Slots Vertical</option>
										<option value="FADE_AND_SLIDE_FROM_RIGHT">Fade and Slide from Right</option>
										<option value="FADE_AND_SLIDE_FROM_LEFT">Fade and Slide from Left</option>
										<option value="FADE_AND_SLIDE_FROM_TOP">Fade and Slide from Top</option>
										<option value="FADE_AND_SLIDE_FROM_BOTTOM">Fade and Slide from Bottom</option>
										<option value="FADE_TO_LEFT_AND_FADE_FROM_RIGHT">Fade to Left and Fade from Right</option>
										<option value="FADE_TO_RIGHT_AND_FADE_FROM_RIGHT">Fade to Right and Fade from Right</option>
										<option value="FADE_TO_TOP_AND_FADE_FROM_BOTTOM">Fade to Top and Fade from Bottom</option>
										<option value="FADE_TO_BOTTOM_AND_FADE_FROM_TOP">Fade to Bottom and Fade from Top</option>
										<option value="PARALLAX_TRANSITIONS">PARALLAX TRANSITIONS</option>
										<option value="PARALLAX_TO_RIGHT">Parallax to Right</option>
										<option value="PARALLAX_TO_LEFT">Parallax to Left</option>
										<option value="PARALLAX_TO_TOP">Parallax to Top</option>
										<option value="PARALLAX_TOP_BOTTOM">Parallax Top Bottom</option>
										<option value="PARALLAX_HORIZONTAL">Parallax Horizontal</option>
										<option value="PARALLAX_VERTICAL">Parallax Vertical</option>
										<option value="ZOOM_TRANSITIONS">ZOOM TRANSITIONS</option>
										<option value="ZOOM_OUT_AND_FADE_FROM_RIGHT">Zoom Out and Fade from Right</option>
										<option value="ZOOM_OUT_AND_FADE_FROM_LEFT">Zoom Out and Fade from Left</option>
										<option value="ZOOM_OUT_AND_FADE_FROM_TOP">Zoom Out and Fade from Top</option>
										<option value="ZOOM_OUT_AND_FADE_FROM_BOTTOM">Zoom out and Fade from Bottom</option>
										<option value="ZOOM_OUT">Zoom Out</option>
										<option value="ZOOM_IN">Zoom In</option>
										<option value="ZOOM_SLOTS_HORIZONTAL">Zoom Slots Horizontal</option>
										<option value="ZOOM_SLOTS_VERTICAL">Zoom Slots Vertical</option>
										<option value="CURTAIN_TRANSITIONS">CURTAIN TRANSITIONS</option>
										<option value="CURTAIN_FROM_LEFT">Curtain from Left</option>
										<option value="CURTAIN_FROM_RIGHT">Curtain from Right</option>
										<option value="CURTAIN_FROM_MIDDLE">Curtain from Middle</option>
										<option value="PREMIUM_TRANSITIONS">PREMIUM TRANSITIONS</option>
										<option value="3D_CURTAIN_HORIZONTAL">3D Curtain Horizontal</option>
										<option value="3D_CURTAIN_VERTICAL">3D Curtain Vertical</option>
										<option value="CUBE_VERTICAL">Cube Vertical</option>
										<option value="CUBE_HORIZONTAL">Cube Horizontal</option>
										<option value="IN_CUBE_VERTICAL">In Cube Vertical</option>
										<option value="IN_CUBE_HORIZONTAL">In Cube Horizontal</option>
										<option value="TURNOFF_HORIZONTAL">TurnOff Horizontal</option>
										<option value="TURNOFF_VERTICAL">TurnOff Vertical</option>
										<option value="PAPER_CUT">Paper Cut</option>
										<option value="FLY_IN">Fly In</option>
									</select>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="first_transition_duration">First Transition Duration</label>

								<div class="span5">
									<input type="text" name="first_transition_duration" id="first_transition_duration" class="int_only input-small" value="<?php echo $slider['first_transition_duration']; ?>"><span> ms</span>
								</div>	
							</div>
							<div class="row-fluid">
								<label class="span6" for="first_transition_slot_amount">First Transition Slot Amount</label>

								<div class="span5">
									<input type="text" name="first_transition_slot_amount" id="first_transition_slot_amount" class="int_only input-small" value="<?php echo $slider['first_transition_slot_amount']; ?>"><span> ms</span>
								</div>	
							</div>
						</div>
					</div><!--END ALTERATIVE FIRST SLIDE-->

					<!--TROUBLESHOOTING-->
					<div class="group">
						<h3 class="accordion-header">Troubleshooting</h3>

						<div>
							<p>
								Troubleshooting
							</p>

						</div>
					</div><!--END TROUBLESHOOTING-->
					
				</div><!-- #accordion -->
		 	</div>

		</div>
		</form>
    </div>

</div>