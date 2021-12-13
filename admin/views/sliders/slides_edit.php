
<div class="main-content">

    <div class="page-content">

        <div class="page-header">
            <h1>
                Edit Slides    
            </h1>
        </div><!-- /.page-header -->

        <input type="hidden" id="action" value="edit_slides"/>

        <div class="row-fluid">
            <div class="span12">
                <div class="tabbale">
                    <ul class="nav nav-tabs" id="myTab">
                    <?php foreach ($slides as $key => $slide) {
                        if(!Session::hasSet('slider_tab'))
                            Session::set('slider_tab', $key);
                     ?>
                        <li class="<?php echo $key;?> tab_header <?php echo $key == Session::get('slider_tab') ? 'active':''; ?>">
                            <a data-toggle="tab" href="#<?php echo $key; ?>">
                                Slide
                                
                            </a>
                        </li>
                    <?php } 
                    ?>
                    <li>
                            <a data-toggle="tab" href="#home">
                                Add Slide    
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <?php foreach ($slides as $key => $slide) {
                           
                        ?>
                        <div id="<?php echo $key; ?>" class="tab-pane <?php echo $key == Session::get('slider_tab') ? 'active':''; ?> form-horizontal">
                            <div class="widget-box transparent">
                                <div class="widget-header">
                                   <h4>General Slide Settings</h4>
                                   <span class="widget-toolbar">
                                        <a href="#" data-action="collapse">
                                            <i class="icon-chevron-up"></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_title"> Slide Title: </label>

                                            <div class="span10">
                                                <input type="text" id="slider_name" name="slider_name" class="input-xlarge" value="<?php echo $slide['title'];?>">     
                                                <span class="help-inline">
                                                    <span class="middle">The title of the slide, willbe shown in the slides list.</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slider_state"> State: </label>

                                            <div class="span10">
                                                <select name="slider_state" id="slider_state" class="input-xlarge">
                                                    <option value="Y">Published</option>
                                                    <option value="N">Unpublished</option>
                                                </select>     
                                                <span class="help-inline">
                                                    <span class="middle">The state of the slide. The unpublished slide will be excluded from the slider.</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_visible_from"> Visible From: </label>

                                            <div class="span10">
                                                <input type="text" id="slide_visible_from" name="slide_visible_from" class="input-xlarge datepicker">   
                                                <span class="help-inline">
                                                    <span class="middle">If set, slide will be visible after the date is reached.</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_visible_until"> Visible Until: </label>

                                            <div class="span10">
                                                <input type="text" id="slide_visible_until" name="slide_visible_until" class="input-xlarge datepicker">
                                                <span class="help-inline">
                                                    <span class="middle">If set, slide will be visible till the date is reached.</span>
                                                </span>
                                            </div>
                                        </div>

                                        <h3 class="header smaller lighter green"></h3>

                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_transition"> Transition: </label>

                                            <div class="span10">
                                                <select id="slide_transition" name="slide_transition" class="input-xlarge">
                                                    <option>No Transition</option>
                                                </select>
                                                <span class="help-inline">
                                                    <span class="middle">The appearance transitions of this slide.</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_slot_amount"> Slot Amount: </label>

                                            <div class="span10">
                                                <input type="text" id="slide_slot_amount" name="slide_slot_amount" class="input-xlarge">
                                                <span class="help-inline">
                                                    <span class="middle">The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy.</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_Rotation"> Rotation: </label>

                                            <div class="span10">
                                                <input type="text" id="slide_Rotation" name="slide_Rotation" class="input-xlarge">
                                                <span class="help-inline">
                                                    <span class="middle">Rotation(-729->720,999 = random) Only for Simpe Transitions.</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_transition_duration"> Transition Duration: </label>

                                            <div class="span10">
                                                <input type="text" id="slide_transition_duration" name="slide_transition_duration" class="input-xlarge" value="300">
                                                <span class="help-inline">
                                                    <span class="middle">the duration of the transition (Default:300, min:100 max 2000).</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_transition_delay"> Delay: </label>

                                            <div class="span10">
                                                <input type="text" id="slide_transition_delay" name="slide_transition_delay" class="input-xlarge">
                                                <span class="help-inline">
                                                    <span class="middle">A new delay value for the Slide. If no delay defined per slide, the delay defined via Options(300ms) will be used.</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_save_performance"> Save Performance: </label>

                                            <div class="span10">
                                                <input name="slide_save_performance" type="radio" value = "ON" class="ace">
                                                <span class="lbl"> On </span>
                                                <input name="slide_save_performance" type="radio" value = "OFF" class="ace">
                                                <span class="lbl"> Off </span>
                                               
                                            
                                            </div>
                                        </div>
                                        <h3 class="header smaller lighter green"></h3>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_enable_link"> Enable Link: </label>

                                            <div class="span10">
                                                <select name="slide_enable_link" id="slide_enable_link" class="input-xlarge">
                                                    <option value="Y">Enabled</option>
                                                    <option value="N">Disabled</option>
                                                </select>     
                                            </div>
                                        </div>
                                        <h3 class="header smaller lighter green"></h3>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slider_thumbnails"> Thumbnails: </label>

                                            <div class="span10">
                                                <div>
                                                    <div style="width:120px; height:90px; border: 1px dotted black;">
                                                    </div>
                                                </div>
                                                <br>
                                               <button class="btn btn-info choose_image">Choose Image</button> &nbsp; <button class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                        <h3 class="header smaller lighter green"></h3>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_class_attribute"> Class: </label>

                                            <div class="span10">
                                                <input type="text" class="input-xlarge" name="slide_class_attribute" id="slide_class_attribute">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> ID: </label>

                                            <div class="span10">
                                                <input type="text" class="input-xlarge" name="slide_id_attribute" id="slide_id_attribute">   
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_attribute"> Attribute: </label>

                                            <div class="span10">
                                                <input type="text" class="input-xlarge" name="slide_attribute" id="slide_attribute">  
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_enable_link"> Custom Fields: </label>

                                            <div class="span10">
                                                <textarea></textarea>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-box transparent">
                                <div class="widget-header">
                                    <h4>Slide Main Image / Background</h4>
                                    <span class="widget-toolbar">
                                        <a href="#" data-action="collapse">
                                            <i class="icon-chevron-up"></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_source"> Background Source: </label>

                                            <div class="span10">
                                                <input name="slide_background_source" type="radio" value = "image_bg" class="ace" checked>
                                                <span class="lbl"> Image BG </span>&nbsp;<button class="btn btn-info btn-small">Change Image</button>
                                                <input name="slide_background_source" type="radio" value = "external_url"class="ace">
                                                <span class="lbl"> External URL </span>
                                                <input name="slide_background_source" type="radio" value = "transparent" class="ace">
                                                <span class="lbl"> Transparent </span>
                                                <input name="slide_background_source" type="radio" value = "solid_colored" class="ace" >
                                                <span class="lbl"> Solid Colored </span>   
                                            </div>
                                        </div>
                                        <h5 class="header smaller lighter green">Background Settings</h5>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> Background Fit: </label>

                                            <div class="span10">
                                                <select name="slide_background_fit" id="slide_background_fit">
                                                    <option>cover</option>
                                                </select>   
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_ken_burns_pan_zoom"> Ken Burns / Pan Zoom Settings: </label>

                                            <div class="span10">
                                                <input name="slide_ken_burns_pan_zoom" type="radio" value = "Y" class="ace" >
                                                <span class="lbl"> On </span>
                                                <input name="slide_ken_burns_pan_zoom" type="radio" value = "N"class="ace" checked>
                                                <span class="lbl"> Off </span> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-box transparent">
                                <div class="widget-header">
                                    <h4>Slide</h4>
                                    <span class="widget-toolbar">
                                        <a href="#" data-action="collapse">
                                            <i class="icon-chevron-up"></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="control-group">
                                            
                                            <div class="control-label span7">
                                                <span>Helper Grid: </span><select><option>Disabled</option></select><span> Snap to: </span><select><option>Disabled</option></select>
                                            </div>

                                        </div>
                                        <div style="width:900px; height:350px; border:2px dotted black;">
                                            <img src="<?php echo $slide['image_url'];?>" style="width:900px; height:350px;">
                                        </div>
                                        <br>
                                        <div class="well">
                                            <div class="span7">
                                                <button class="btn btn-info btn-small"><i class="icon-plus bigger-110"></i> Add Layer</button>
                                                <button class="btn btn-info btn-small"><i class="icon-picture bigger-110"></i> Add Layer: Image</button>
                                                <button class="btn btn-info btn-small"><i class="icon-film bigger-110"></i> Add Layer: Video</button>
                                                <button class="btn btn-warning btn-small"><i class="icon-copy bigger-110"></i> Duplicate Layer</button>
                                            </div>
                                            <div class="span5 align-right">
                                                 <button class="btn btn-danger btn-small"><i class="icon-trash bigger-110"></i> Delete Layer</button>
                                                 <button class="btn btn-danger btn-small"><i class="icon-trash bigger-110"></i> Delete All Layer</button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="widget-box transparent">
                                <div class="widget-header">
                                    <h4>Layers Timing & Sorting</h4>
                                    <span class="widget-toolbar">
                                        <a href="#" data-action="collapse">
                                            <i class="icon-chevron-up"></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div>
                                           <div class="span10">
                                                z-Index &nbsp;  &nbsp;  &nbsp;
                                                <a href="#" title="Hide All Layers"> <i class="icon-eye-open bigger-110"></i></a> &nbsp;  &nbsp;  &nbsp;
                                                <a href="#" title="Lock All Layers"> <i class="icon-unlock bigger-110"></i></a> &nbsp;  &nbsp;  &nbsp;
                                                <a href="#" title="Snap to Slide End/ Custom End"> <i class="icon-refresh bigger-110"></i></a> &nbsp;  &nbsp;  &nbsp;
                                                <button class="btn btn-primary btn-small"> <i class="icon-cogs bigger-110"></i>- on</button>
                                           </div>
                                           <div class="span2">
                                               &nbsp;  &nbsp; <span>  Start  </span> &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp; <span>    End  </span>
                                           </div>
                                        </div>
                                        <br/>
                                        <br>
                                        <div style="height: 200px;">
                                            <div>
                                                <div class="span10">
                                                    <div class="span1">
                                                        <div class="label label-large label-primary" style="width:100%;">
                                                            <i class="icon-exchange bigger-110"></i> &nbsp;&nbsp; 1
                                                        </div>
                                                    </div>
                                                    <div class="span2 ">
                                                        <div class="label label-large label-primary" style="width:100%;">
                                                            &nbsp;  &nbsp;&nbsp;&nbsp;
                                                            <a href="#" style="text-decoration:none; color: #ffffff;" title="show/hide layer"><i class="icon-eye-open bigger-110"></i></a>
                                                            &nbsp;  &nbsp;&nbsp;&nbsp;
                                                            <a href="#" style="text-decoration:none; color: #ffffff;" title="lock/unlock layer"><i class="icon-unlock bigger-110"></i></a>
                                                            &nbsp;  &nbsp;&nbsp;&nbsp;
                                                            <a href="#" style="text-decoration:none; color: #ffffff;" title="Snap to Slide End/Custom End"><i class="icon-refresh bigger-110"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="span9">
                                                       <div class="label label-large label-primary" style="width:100%;">
                                                           &nbsp;  &nbsp;&nbsp;
                                                            <i class="icon-font bigger-110"></i> 
                                                            &nbsp;  &nbsp;&nbsp;
                                                            <span>Read More</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="label label-large label-primary" style="width:100%;">
                                                            &nbsp; &nbsp; 
                                                            <span>1450</span>
                                                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                            <span>1450</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-box transparent">
                                <div class="widget-header">
                                    <h4>Layers General Parameters</h4>
                                    <span class="widget-toolbar">
                                        <a href="#" data-action="collapse">
                                            <i class="icon-chevron-up"></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h5 class="header smaller lighter green">Layer Content</h5>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_ken_burns_pan_zoom"> Style: </label>

                                            <div class="span10">
                                                <select>
                                                    <option>btn-rm</option>
                                                </select>
                                                &nbsp;
                                                <button class="btn btn-primary btn-small"><i class="icon-edit bigger-110"></i>Edit Style</button>
                                                &nbsp;
                                                <button class="btn btn-primary btn-small"><i class="icon-dashboard bigger-110"></i>Edit Global Style</button>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_ken_burns_pan_zoom"> Text/HTML: </label>

                                            <div class="span10">
            
                                                <button class="btn btn-primary btn-small">Insert Button</button>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_ken_burns_pan_zoom"></label>
                                            <div class="controls span10">

                                                <textarea cols="200" rows="4">Read More</textarea>
                                            </div>
                                        </div>
                                         <h5 class="header smaller lighter green">Align, Position & Styling</h5>
                                         <div style="height:200px;">
                                            <div class="span2">
                                                <div style="height: 150px; width:91%; border: 1px solid grey;">
                                                    <div style="height:50px; width: 50px; float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                    <div style="height:50px; width: 50px; float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                    <div style="height:50px; width: 50px; float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                    <div style="height:50px; width: 50px;  float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                    <div style="height:50px; width: 50px; float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                    <div style="height:50px; width: 50px; float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                    <div style="height:50px; width: 50px;  float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                    <div style="height:50px; width: 50px;  float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                    <div style="height:50px; width: 50px;  float: left;">
                                                        <div style="height:45px; width: 45px; background:grey; margin: 2px 0 0 2px;">
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span8">
                                               <span>X </span><input type="text" class="input-small">&nbsp;<span>Y </span><input type="text" class="input-small">
                                               <br>
                                               <br>
                                               <span> White Space </span><select class="input-small"><option>No-wrap</option></select>
                                               <br>
                                               <br>
                                               <span>Max Width </span><input type="text" class="input-small" value="auto"> <span class="grey"> (Example:50px, 50%, auto)</span>
                                               <br>
                                               <br>
                                               <span>Max Height </span><input type="text" class="input-small" value="auto"> <span class="grey"> (Example:50px, 50%, auto)</span>
                                            </div>
                                            <div class="span2">
                                                <button class="btn btn-danger btn-small">Reset Size</button>
                                            </div>
                                         </div>
                                         <h5 class="header smaller lighter green">Find Rotation</h5>
                                         <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> 2D Rotation: </label>

                                            <div class="span10">
                                                <input type="text" class="input-small" value="0"><span class="grey">(-360 - 360)</span>   
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> Rotation Origin X: </label>

                                            <div class="span10">
                                                <input type="text" class="input-small" value="50"><span class="grey">%(-100 - 200)</span>    
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> Rotation Origin Y: </label>

                                            <div class="span10">
                                                <input type="text" class="input-small" value="50"><span class="grey">%(-100 - 200)</span>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-box transparent">
                                <div class="widget-header">
                                    <h4>Layer Animation</h4>
                                    <span class="widget-toolbar">
                                        <a href="#" data-action="collapse">
                                            <i class="icon-chevron-up"></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h5 class="header smaller lighter green">Preview Transition(Star and Endtime is Ingnored during Demo)</h5>
                                        <div style="width:100%; height:250px; background:url('../../../../../images/slider/animation_background.png') repeat; margin: 0 0 20px 0;">
                                        </div>

                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> Start Animation: </label>

                                            <div class="span10">
                                                <select class="input-xlarge">
                                                    <option>Fade</option>
                                                </select> 
                                                &nbsp;
                                                <button class="btn btn-primary btn-small"><i class="icon-cogs bigger-110"></i>Custom Animation</button>  
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> Start Easing: </label>

                                            <div class="span10">
                                                <select class="input-xxlarge">
                                                    <option>Power3.easeInOut</option>
                                                </select> 
                                                
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> Start Duration: </label>

                                            <div class="span10">
                                                <input type="text" class="input-small" value="300"><span> ms</span>
                                                
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> Split Text per: </label>

                                            <div class="span10">
                                                <select class="input-small">
                                                    <option>No Split</option>
                                                </select> 
                                                
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> Split Delay: </label>

                                            <div class="span10">
                                                <input type="text" class="input-small" value="10"><span> ms</span>
                                                
                                            </div>
                                        </div>
                                        <h5 class="header smaller lighter green">End Transition (optional)</h5>
                                        <div style="width:900px; height:200px">
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> End Animation: </label>

                                            <div class="span10">
                                                <select class="input-xlarge">
                                                    <option>Fade</option>
                                                </select> 
                                                &nbsp;
                                                <button class="btn btn-primary btn-small"><i class="icon-cogs bigger-110"></i>Custom Animation</button>  
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> End Easing: </label>

                                            <div class="span10">
                                                <select class="input-xxlarge">
                                                    <option>Power3.easeInOut</option>
                                                </select> 
                                                
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> End Duration: </label>

                                            <div class="span10">
                                                <input type="text" class="input-small" value="300"><span> ms</span>
                                                
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> Split Text per: </label>

                                            <div class="span10">
                                                <select class="input-small">
                                                    <option>No Split</option>
                                                </select> 
                                                
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> End Delay: </label>

                                            <div class="span10">
                                                <input type="text" class="input-small" value="10"><span> ms</span>
                                                
                                            </div>
                                        </div>
                                        <h5 class="header smaller lighter green">Loop Animation</h5>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_background_fit"> Animation: </label>

                                            <div class="span10">
                                                <select class="input-small">
                                                    <option>Disabled</option>
                                                </select> 
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-box transparent">
                                <div class="widget-header">
                                    <h4>Layers Links & Advanced Params</h4>
                                    <span class="widget-toolbar">
                                        <a href="#" data-action="collapse">
                                            <i class="icon-chevron-up"></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h5 class="header smaller lighter green">Links (Optional)</h5>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> Link to Slide: </label>

                                            <div class="span10">
                                                <select class="input-xlarge">
                                                    <option>--Not Chosen--</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <h5 class="header smaller lighter green">Caption Sharp Corners (Optional only with BG color)</h5>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> Left Corner: </label>

                                            <div class="span10">
                                                <select class="input-xlarge">
                                                    <option>No Corner</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> Right Corner: </label>

                                            <div class="span10">
                                                <select class="input-xlarge">
                                                    <option>No Corner</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <h5 class="header smaller lighter green">Advanced Responsive Settings</h5>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> Responsive Through All Levels: </label>

                                            <div class="span10">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="form-field-checkbox" type="checkbox">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="span2 control-label" for="slide_id_attribute"> Hide Under Width </label>
                                        </div>
                                        <h5 class="header smaller lighter green">Attributes (Optional)</h5>
                                        <div class="control-group">
                                            <div class="span8">
                                                <span>ID <span><input type="text" class="input-small">
                                                &nbsp;
                                                <span>Title <span><input type="text" class="input-small">
                                            </div>
                                            <div class="span4">
                                                 <span>Rel <span><input type="text" class="input-small">
                                                &nbsp;
                                                <span>Classes <span><input type="text" class="input-small">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--END WIDGETS-->
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div><!--PAGE Row END-->

    </div><!--PAGE CONTENT END-->

</div>

<div id="choose_image" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="modal-body">
                    Wait for a moment adding customer..
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
