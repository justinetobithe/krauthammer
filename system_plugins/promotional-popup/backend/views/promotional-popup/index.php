<?php 
$promo_settings_data = '{
    "layout":{
        "enable":"Y",
        "template":"layout-custom-1"
    },
    "content":{
        "type":"default-form",
        "headline":"Sign Up for Free Demo<\/strong>",
        "body":" Try out our software with a live demo ...",
        "form":"1"
    },
    "timing":{
        "type":"page-certain",
        "pages":[
            "homepage",
            "secondary"
        ],
        "trigger":"timing-time",
        "freq":"frequency-day-next",
        "signup":"Y",
        "mobile":"Y",
        "time":0,
        "scroll":"25"
    },
    "shown":false
}';

$promo_settings_file_guild = '&lt;?php
/*
Layout Name : [ Name Custom Promotional Popup Layout ]
Key : [ Layout Key (ex: custom-1, custom-2) ] 
Description : [ Add some description (optional)]
Preview : [ Image file name. Save your image under "/promotional-popup/layout/<b>img</b>/" ]

*/

... 
your html code
...';
?>

<div class="main-content">
    <div class="page-content">
        <div class="page-header">
            <h1> Manage Promotional Pop-Up</h1>
        </div><!-- /.page-header -->
        <input type="hidden" id="action" value="mange_product">
        <div class="row-fluid">
            <div class="span12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="widget-box">
                    <div class="hide">
                        <input type="hidden" id="popup-layout-id">
                    </div>

                    <div class="tabbable tabs-left">
                        <ul class="nav nav-tabs" id="myTab3">
                            <li class="active">
                                <a data-toggle="tab" href="#container-layout">
                                    <i class="icon icon-desktop bigger-110"></i>
                                    Popup Layout
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#container-content">
                                    <i class="icon icon-barcode bigger-110"></i>
                                    Content
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#container-timing">
                                    <i class="icon icon-dashboard bigger-110"></i>
                                    Display and Timing
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo URL . "contact-forms/responses/promotional/" ?>" class="btn btn-primary btn-small span12" target="_blank">
                                    <i class="icon icon-table bigger-110"></i>
                                    Check Result
                                </a>
                                <button class="btn btn-primary btn-small span12 hide" id="btn-view-demo" style="display: none;">Preview</button>
                            </li>
                            <li>
                                <button class="btn btn-success btn-small span12" id="btn-view-save">Save</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="tab_content">
                            <div id="container-layout" class="tab-pane in active">
                                <div class="widget-box">
                                    <div class="widget-header header-color-blue widget-header-small">
                                        <h5 class="lighter">Layout </h5>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <label>
                                                        Enable Promotional Popup. 
                                                        <input name="switch-field-1" class="ace ace-switch ace-switch-7" type="checkbox" id="enable-popup" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <h5><b>Default Template</b></h5>
                                                    <p>Select how your Promotional Pop appear on your site. </p>
                                                </div>
                                            </div>
                                            <br>
                                            <?php $tmpl_ctr = 0; ?>
                                            <div class="row-fluid">
                                            <?php foreach ($template_default as $key => $value): ?>
                                                <div class="span4 popup-layout-thumb">
                                                    <div class="popup-imge-preview">
                                                        <a href="javascript:void(0)" data-value="<?php echo $value['key'] ?>">
                                                            <img src="<?php echo $value['prev'] ?>" alt="">
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php if (++$tmpl_ctr%3 == 0): ?>
                                            </div>
                                            <div class="row-fluid">
                                            <?php endif ?>
                                            <?php endforeach ?>
                                            </div>

                                            <hr>
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <h5><b>Custom Template</b></h5>
                                                    <?php if (count($template_custom)): ?>
                                                    <p>Select how your Promotional Pop appear on your site. </p>
                                                    <?php else: ?>
                                                    <p>No Custom Template/Layout. </p>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                            <?php $tmpl_ctr = 0; ?>
                                            <?php if (count($template_custom)): ?>
                                            <br>
                                            <div class="row-fluid">
                                            <?php foreach ($template_custom as $key => $value): ?>
                                                <div class="span4 popup-layout-thumb">
                                                    <div class="popup-imge-preview">
                                                        <a href="javascript:void(0)" data-value="<?php echo $value['key'] ?>">
                                                            <img src="<?php echo $value['prev'] ?>" alt="">
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php if (++$tmpl_ctr%3 == 0): ?>
                                            </div>
                                            <div class="row-fluid">
                                            <?php endif ?>
                                            <?php endforeach ?>
                                            </div>
                                            <?php endif ?>

                                            <hr>
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <p>Here's the guide in creating your own Custom Promotional Popup: <a href="javascript:void(0)" id="btn-show-popup-editor">Toggle Guide</a></p>
                                                    <div class="well well-small" id="custom-popup-guide" style="display: <?php echo count($template_custom) ? 'none' : 'block' ?>;">
                                                        <p><b>1:</b> Create a folder <b>"/promotional-popup/layout/"</b> under actived theme.</p>
                                                        <p><b>2:</b> Create a PHP file with your preferred file name. This will contain the HTML/CSS/Javascript of your Promotional Popup.</p>
                                                        <p><b>3:</b> Add the following comment at the beginning of your php file.</p>
                                                        
                                                        <pre><?php echo $promo_settings_file_guild ?></pre>

                                                        <p><b>4:</b> Inside your PHP file, you can use the variable: <b>"$promo_settings"</b>, to get the promotional settings data:</p>

                                                        <pre><?php echo $promo_settings_data; ?></pre>

                                                        <p>You can download a the default promotional popup layout as reference: </p>
                                                        <ul>
                                                            <li>
                                                                 Layout 1 : 
                                                                <a href="<?php echo URL ?>promotional-popup/download/default-layout-1" target="_blank">Download</a> | 
                                                                <a href="javascript:void(0)" class="promotional-popup-preview-code" data-value="<?php echo URL ?>promotional-popup/view/default-layout-1" target="_blank">View</a>
                                                            </li>
                                                            <li>
                                                                Layout 2 : 
                                                                <a href="<?php echo URL ?>promotional-popup/download/default-layout-2" target="_blank">Download</a> | 
                                                                <a href="javascript:void(0)" class="promotional-popup-preview-code" data-value="<?php echo URL ?>promotional-popup/view/default-layout-2"  target="_blank">View</a>
                                                            </li>
                                                        </ul>
                                                        <br>

                                                        <p>On your frontend files (inside the active theme), include the following function (at the bottom part of after the other scripts): </p>
                                                        <pre><strong>&lt;?php cms_promotional_popup(); ?&gt;</strong></pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="container-content" class="tab-pane">
                                <!-- Contact Form -->
                                <div class="widget-box popup-content-box" id="content-type">
                                    <div class="widget-header header-color-blue widget-header-small">
                                        <h5 class="lighter">Contact Source </h5>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div class="form-horizontal hide">
                                                <div class="control-group">
                                                    <label class="control-label"><small>Content Type: </small></label>
                                                    <div class="controls">
                                                        <select id="popup-type">
                                                            <option value="default-form">Default</option>
                                                            <option value="contact-form">Contact Form</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>

                                            <div class="contect-type-container" id="content-type-default">
                                                <div class="form-horizontal">
                                                    <div class="control-group">
                                                        <label for="" class="control-label">Headline</label>
                                                        <div class="controls">
                                                            <input type="text" class="input span12" id="content-type-default-head">
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label for="" class="control-label">Body</label>
                                                        <div class="controls">
                                                            <textarea id="content-type-default-body"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="contect-type-container" id="content-type-cf" style="display: none;">
                                                <div class="form-horizontal">
                                                    <div class="control-group">
                                                        <label class="control-label"><small>Content Form: </small></label>
                                                        <div class="controls span4">
                                                            <select id="popup-cf" class="input-select">
                                                            <?php foreach ($cf as $key => $value): ?>
                                                                <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                                                            <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div id="contact-form-layout"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="container-timing" class="tab-pane">
                                <div class="widget-box popup-content-box" id="content-custom">
                                    <div class="widget-header header-color-blue widget-header-small">
                                        <h5 class="lighter">Display &amp; Timing </h5>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h5><b>PAGES</b></h5>
                                            <p>The pop-up will appear on the first page the visitor opens. You can limit the pop-up to certain pages. </p>
                                            <div class="form-horizontal">
                                                <div class="control-group">
                                                    <label for="" class="control-label">Show Popup On:</label>
                                                    <div class="controls">
                                                        <select id="page-type" style="width:300px;">
                                                            <option value="page-all">All Pages</option>
                                                            <option value="page-certain">Certain Page</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group" id="certain-page-container" style="display: none;">
                                                    <div class="controls">
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                <label>
                                                                    <input name="form-field-checkbox" type="checkbox" checked="checked" class="ace" id="toggle-page">
                                                                    <span class="lbl"> Pages</span>
                                                                </label>
                                                            </div>
                                                            <div class="span2">
                                                                <label>
                                                                    <input name="form-field-checkbox" type="checkbox" checked="checked" class="ace" id="toggle-post">
                                                                    <span class="lbl"> Posts</span>
                                                                </label>
                                                            </div>
                                                            <div class="span2">
                                                                <label>
                                                                    <input name="form-field-checkbox" type="checkbox" checked="checked" class="ace" id="toggle-products">
                                                                    <span class="lbl"> Products</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="controls" id="page-container">
                                                        <h4>Pages</h4>
                                                        <select id="page-certain" class="tag-input-style" multiple="multiple">
                                                            <option value="homepage">Homepage</option>
                                                            <?php foreach ($pages as $key => $value): ?>
                                                            <option value="<?php echo $value->url_slug ?>"><?php echo $value->post_title ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <br>
                                                        <a href="javascript:void(0)" id="page-certain-select-all">Select All</a> | 
                                                        <a href="javascript:void(0)" id="page-certain-remove-all">Remove All</a>
                                                    </div>
                                                    <br>
                                                    <div class="controls" id="post-container">
                                                        <h4>Posts</h4>
                                                        <select id="post-certain" class="tag-input-style" multiple="multiple">
                                                            <?php foreach ($posts as $key => $value): ?>
                                                            <option value="<?php echo $value->url_slug ?>"><?php echo $value->post_title ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <br>
                                                        <a href="javascript:void(0)" id="post-certain-select-all">Select All</a> | 
                                                        <a href="javascript:void(0)" id="post-certain-remove-all">Remove All</a>
                                                    </div>
                                                    <br>
                                                    <div class="controls" id="products-container">
                                                        <h4>Products</h4>
                                                        <select id="products-certain" class="tag-input-style" multiple="multiple">
                                                            <?php foreach ($products as $key => $value): ?>
                                                            <option value="<?php echo $value->url_slug ?>"><?php echo $value->product_name ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <br>
                                                        <a href="javascript:void(0)" id="products-certain-select-all">Select All</a> | 
                                                        <a href="javascript:void(0)" id="products-certain-remove-all">Remove All</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-main">
                                            <h5><b>TIMING</b></h5>
                                            <p>Choose when to display the pop-up. The pop-up will show on scroll or the set timer, whichever comes first.</p>
                                            <div class="form-horizontal">
                                                <div class="control-group">
                                                    <label for="" class="control-label">Trigger</label>
                                                    <div class="controls">
                                                        <select id="timing-type">
                                                            <option value="timing-time">Timer</option>
                                                            <option value="timing-scroll">Scroll</option>
                                                            <option value="timing-timer-scroll">Timer and Scroll</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group timing-container" id="timing-time-container" style="display: none;">
                                                    <label for="" class="control-label">Time</label>
                                                    <div class="controls">
                                                        <select id="timing-time">
                                                            <option value="0">Immediately</option>
                                                            <option value="5">After 5 seconds</option>
                                                            <option value="10">After 10 seconds</option>
                                                            <option value="30">After 30 seconds</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group timing-container" id="timing-scroll-container" style="display: none;">
                                                    <label for="" class="control-label">Scroll Percent</label>
                                                    <div class="controls">
                                                        <select id="timing-scroll">
                                                            <option value="25">25% Down</option>
                                                            <option value="50">50% Down</option>
                                                            <option value="75">75% Down</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-main">
                                            <h5><b>FREQUENCY</b></h5>
                                            <p>After a visitor sees the pop-up, show it again:</p>
                                            <div class="form-horizontal">
                                                <div class="control-group">
                                                    <label for="" class="control-label">Select Frequency:</label>
                                                    <div class="controls">
                                                        <select id="frequency-type">
                                                            <option value="frequency-always">Always</option>
                                                            <option value="frequency-never">Never</option>
                                                            <option value="frequency-day-next">Next Day</option>
                                                            <option value="frequency-day-30">30 Days</option>
                                                            <option value="frequency-week-1">1 Week</option>
                                                            <option value="frequency-week-2">2 Weeks</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <div class="controls">
                                                        <label>
                                                            <input name="form-field-checkbox" class="ace ace-checkbox-2" type="checkbox" id="show-before-signup" />
                                                            <span class="lbl"> Don't Show Again after Newsletter Signup</span>
                                                        </label>
                                                        <label>
                                                            <input name="form-field-checkbox" class="ace ace-checkbox-2" type="checkbox" id="show-on-mobile" />
                                                            <span class="lbl"> Show on Mobile</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--PAGE SPAN END-->
    </div><!--PAGE Row END-->
</div><!--MAIN CONTENT END-->
<div id="modal-popup-previewer" class="modal modal-large fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Promotional Popup Preview
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>

            <div class="modal-body" id="popup-previewer-container">
                <!-- <iframe src="http://pvs-cms.com/" class="previewer" frameborder="0" style="zoom: 0.5" id="popup-previewer"></iframe> -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>

<div id="modal-popup-code-preview" class="modal modal-large fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    Custom Promotional Popup Layout Preview
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                </div>
            </div>

            <div class="modal-body" id="popup-code-previewer-container">
                <pre id="popup-code-previewer"></pre>
            </div><!-- /.modal-content -->

            <div class="modal-footer">
                <button class="btn btn-small btn-primary" data-dismiss="modal">Close</button>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>