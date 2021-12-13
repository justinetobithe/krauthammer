var Popup = {
	layout : 0,
	contentType : '',
}

$(document).ready(function(){
	/*$("#previewer").();*/
	$(window).resize(function(){
		$("#tab_content").css('min-height', (window.innerHeight - 200) + "px");
		$("#popup-option").css('min-height', (window.innerHeight - 200) + "px");
		$("#popup-previewer-container").css('max-height', (window.innerHeight - 150) + "px");
	}).trigger('resize');
	$("#modal-popup-previewer").modal({
		show : false
	});
	$("#modal-popup-code-preview").modal({
		show : false
	});
	$(".input-select").chosen({
		width : '100%',
	});
	$(".input-chosen").chosen({
		width : "200px",
	});
	$("#page-certain").chosen({
		width : "500px",
	});
	$("#post-certain").chosen({
		width : "500px",
	});
	$("#products-certain").chosen({
		width : "500px",
	});
	$("#popup-type").chosen({
		width : '300px',
	}).change(function(e){
		var selected_type = $(this).val();

		$(".contect-type-container").hide();

		if (selected_type == 'default-form') {
			$("#content-type-default").show();
		}else if(selected_type == 'contact-form'){
			$("#content-type-cf").show();
		}
	}).trigger('change');

	$("#timing-type").change(function(){
		if ($(this).val() == 'timing-time') {
			$(".timing-container").hide();
			$("#timing-time-container").show();
		}else if($(this).val() == 'timing-scroll'){
			$(".timing-container").hide();
			$("#timing-scroll-container").show();
		}else{
			$("#timing-time-container").show();
			$("#timing-scroll-container").show();
		}
	}).trigger('change');	

	$("#page-type").change(function(){
		if ($(this).val() == 'page-certain') {
			$("#certain-page-container").show();
		}else{
			$("#certain-page-container").hide();
		}
	}).trigger('change');	

	tinyMCE.init({selector: "#content-type-default-body",
    menubar: " view edit format table tools",
    width : '100%',
    height : 200,
    init_instance_callback : function(editor) {
      // tinyMCE.get("custom-field-textarea-"+ id).setContent(value);
      init();
    },
    toolbar:[
    "formatselect nonbreaking undo redo bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent indent",
    "image imageuploader cmsmedia  link "
    ],
    plugins: [
      "paste advlist autolink link image lists charmap print preview hr anchor pagebreak ",
      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
      "imageuploader cmsmedia",
      "textcolor colorpicker","nonbreaking table code"
    ],
    external_plugins:{
      'imageuploader' :'plugins/imageuploader/editor_plugin_src.js',
      'cmsmedia'      :'plugins/cmsmedia/editor_plugin_src.js',
    },
    relative_urls: false,
    convert_urls: false,
    nonbreaking_force_tab:true,
    tools: "inserttable",
    verify_html: false
  });

	$("#btn-view-demo").click(function(){
		$("#popup-previewer").attr('src', "http://pvs-cms.com/?layout=" + Popup.layout);
		$("#modal-popup-previewer").modal('show');
	});

	$("#popup-cf").change(function(e){
		
	});

	$("#btn-view-save").click(function(e){
		saveSettings();
	});

	$("#page-certain-select-all").click(function(e){
		$("#page-certain option").prop('selected', true);
		$("#page-certain").trigger('chosen:updated');
	});
	$("#page-certain-remove-all").click(function(e){
		$("#page-certain option").prop('selected', false);
		$("#page-certain").trigger('chosen:updated');
	});
	$("#post-certain-select-all").click(function(e){
		$("#post-certain option").prop('selected', true);
		$("#post-certain").trigger('chosen:updated');
	});
	$("#post-certain-remove-all").click(function(e){
		$("#post-certain option").prop('selected', false);
		$("#post-certain").trigger('chosen:updated');
	});
	$("#products-certain-select-all").click(function(e){
		$("#products-certain option").prop('selected', true);
		$("#products-certain").trigger('chosen:updated');
	});
	$("#products-certain-remove-all").click(function(e){
		$("#products-certain option").prop('selected', false);
		$("#products-certain").trigger('chosen:updated');
	});

	$("#toggle-page").change(function(e){
		if ($(this).is(":checked")) {
			$("#page-container").show();
		}else{
			$("#page-container").hide();
		}
	});
	$("#toggle-post").change(function(e){
		if ($(this).is(":checked")) {
			$("#post-container").show();
		}else{
			$("#post-container").hide();
		}
	});
	$("#toggle-products").change(function(e){
		if ($(this).is(":checked")) {
			$("#products-container").show();
		}else{
			$("#products-container").hide();
		}
	});

	$("#btn-show-popup-editor").click(function(e){
		$("#custom-popup-guide").slideToggle();
		// popup-editor
	});
	$(".promotional-popup-preview-code").click(function(e){
		var file_url = $(this).data('value');

		jQuery.post(file_url, {}, function(response, status){
			$("#popup-code-previewer").text(response);

			$("#modal-popup-code-preview").modal('show');
		});
	});

});

function init(){
	getSettings();

	$.each($(".popup-imge-preview").find("a"), function(e){
		$(this).click(function(e){
			var curr = $(this);

			$(".popup-layout-thumb").removeClass('selected');
	    Popup.layout = curr.data('value');
			curr.closest('.popup-layout-thumb').addClass('selected');
			
			// bootbox.confirm("Do you want to set layout?", function(result){
			//   if (result) {
			    
			//   }
			// });
		});
	});

	setContentType();
}
function setLayout(){
	Popup.layout = $(".popup-layout-thumb.selected>.popup-imge-preview>a").data('value');
	saveSettings();
}
function setContentType(){
	Popup.contentType = $("#popup-type").val();
}
function setContentLayout(){
	if (Popup.layout == 'layout-1') {

	}
}
function getSettings(){
	jQuery.post(CONFIG.get('URL')+'promotional-popup/get_popup_settings/', {}, function(response, status){
		var data = JSON.parse(response);
		
		if (data.layout.template !== undefined) {
			Popup.layout = data.layout.template;
			$(".popup-imge-preview>a[data-value="+ data.layout.template +"]").closest(".popup-layout-thumb").addClass("selected");
		}
		if (data.layout.enable !== undefined) {
			if (data.layout.enable == "Y") {
				if (!$("#enable-popup").is(":checked")) {
					$("#enable-popup").trigger('click');
				}
			}else{
				if ($("#enable-popup").is(":checked")) {
					$("#enable-popup").trigger('click');
				}
			}
		}

		if (data.content !== undefined) {
			if (data.content.type !== undefined) {
				$("#popup-type").val(data.content.type).trigger('change').trigger('chosen:updated');
			}
			if (data.content.headline !== undefined) {
				$("#content-type-default-head").val(data.content.headline);
			}
			if (data.content.body !== undefined) {
				tinyMCE.get("content-type-default-body").setContent(data.content.body)
			}
		}

		if (data.timing !== undefined) {
			if (data.timing.type !== undefined) {
				$("#page-type").val(data.timing.type).trigger('change');
			}
			if (data.timing.pages !== undefined) {
				$("#page-certain").val(data.timing.pages).trigger("chosen:updated");
				$("#post-certain").val(data.timing.pages).trigger("chosen:updated");
				$("#products-certain").val(data.timing.pages).trigger("chosen:updated");
			}
			if (data.timing.group !== undefined) {
				if (data.timing.group.indexOf('page') < 0 ) {
					$("#toggle-page").prop("checked", false).trigger('change');
				}
				if (data.timing.group.indexOf('post') < 0 ) {
					$("#toggle-post").prop("checked", false).trigger('change');
				}
				if (data.timing.group.indexOf('products') < 0 ) {
					$("#toggle-products").prop("checked", false).trigger('change');
				}
			}
			if (data.timing.trigger !== undefined) {
				$("#timing-type").val(data.timing.trigger).trigger('change');
			}
			if (data.timing.time !== undefined) {
				$("#timing-time").val(data.timing.time);
			}
			if (data.timing.scroll !== undefined) {
				$("#timing-scroll").val(data.timing.scroll);
			}
			if (data.timing.freq !== undefined) {
				$("#frequency-type").val(data.timing.freq);
			}
			if (data.timing.signup !== undefined && data.timing.signup == 'Y') {
				if (!$("#show-before-signup").is(":checked")) {
					$("#show-before-signup").trigger('click');
				}
			}
			if (data.timing.mobile !== undefined && data.timing.mobile == 'Y') {
				if (!$("#show-on-mobile").is(":checked")) {
					$("#show-on-mobile").trigger('click');
				}
			}
		}
	});
}
function saveSettings(){
	var page_type	= $("#page-type").val();
	var pages 		= $("#page-certain").val();
	pages = pages !== null && page_type != 'page-all' && $("#toggle-page").is(":checked") ? pages : [];
	var posts 		= $("#post-certain").val();
	posts = posts !== null && page_type != 'page-all' && $("#toggle-post").is(":checked") ? posts : [];
	var products 	= $("#products-certain").val();
	products = products !== null && page_type != 'page-all' && $("#toggle-products").is(":checked") ? products : [];
	var page_group = [];

	if ($("#toggle-page").is(":checked")) { page_group.push('page') }
	if ($("#toggle-post").is(":checked")) { page_group.push('post') }
	if ($("#toggle-products").is(":checked")) { page_group.push('products') }

	var data = {
		layout 	: {
			enable 		: $("#enable-popup").is(":checked") ? "Y" : "N",
			template 	: $(".popup-layout-thumb.selected>.popup-imge-preview>a").data('value'),
		},
		content : {
			type 			: $("#popup-type").val(),
			headline 	: $("#content-type-default-head").val(),
			body 			: tinyMCE.get("content-type-default-body").getContent(),
			form 			: $("#popup-cf").val(),
		},
		timing	: {
			type 		: page_type,
			pages 	: pages.concat(posts.concat(products)),
			group 	: page_group,
			trigger : $("#timing-type").val(),
			freq 		: $("#frequency-type").val(),
			signup	: $("#show-before-signup").is(":checked") ? "Y" : "N",
			mobile	: $("#show-on-mobile").is(":checked") ? "Y" : "N",
			time		: $("#timing-time").val(),
			scroll	: $("#timing-scroll").val(),
		},
	}

	jQuery.post(CONFIG.get('URL')+'promotional-popup/save_settings/', {
		data : JSON.stringify(data)
	}, function(response, status){
		notification("Promotional Popup", "Saved", "gritter-success");
	});
}