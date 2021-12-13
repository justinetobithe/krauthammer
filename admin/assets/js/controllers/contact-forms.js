var pathFile = CONFIG.get("URL") + "contact-forms/";

$(function(){
	load_table();
	$('.icon-spinner').hide();
	if(getVal('action')==='list'){
		// $('#tableContactForm').dataTable( {
		// 	"oLanguage": {
		// 		"sEmptyTable": 'No items found.'
		// 	},
		// 	"aoColumns": [
		// 	{ "sWidth": "30px", "sClass": "center", "bSortable": false },
		// 	null, null,null, null,{ "sWidth": "80px", "bSortable": false }
		// 	],
		// 	"bAutoWidth": false
		// } );

		$("#tableContactForm").dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": CONFIG.get('URL')+'contact-forms/getContactForms',
			"aoColumns": [
				{"sWidth": 50, "bSortable": false},
				{"sWidth": 300},
				{"bSortable": false},
				{"sWidth": 50, "bSortable": false},
				{"sWidth": 50, "bSortable": false},
				{"sWidth": 50, "bSortable": false},
			], "fnDrawCallback": function(oSettings) {
				
			}, "fnServerParams": function(aoData) {
				/*aoData.push({"name": "variable_name", "value": "variable_value"});*/
			}
		});

		$.post(CONFIG.get('URL')+'contact-forms/getContactForms',function(results){
			$.each(JSON.parse(results),function(index, result){
				var action_btn = "";
				action_btn += "<a href='"+CONFIG.get('URL')+"contact-forms/edit/"+result['id']+"'>Edit</a> ";
				action_btn += "<br /><a href='"+CONFIG.get('URL')+"contact-forms/responses/"+result['id']+"'>Response</a> ";
				action_btn += "<br /><a href='javascript:copyContactForm("+result['id']+")'>Copy</a> ";

				$('#tableContactForm').dataTable().fnAddData( [
					"<label><input type='checkbox' value='"+result['id']+"' class='ace' /><span class='lbl'></span></label>",
					"<a href='"+CONFIG.get('URL')+"contact-forms/edit/"+result['id']+"'>"+result['name']+"</a>", 
					"<input type='text' value='[contact-form id="+'"'+result['id']+'"'+" title="+'"'+result['name']+'"'+"]' readonly class='span12' />",
					" ",
					" ", 
					action_btn]
					);
			});
		});
	}else if(getVal('action')==='response'){
		//response();
	}

	$("#tag-type a").click(function(){
		$("#tab-generator-container").html('');
		var selected_field = $(this).text().trim();

		if(selected_field==="Text Field"){
			$('#tag-text').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #size, #maxlength, #id, #class, #author, #author_url, #watermark, #watermark_value, #required").change(function(){
				if($(this).is(":checked") && (($(this).attr('id')==='author') || ($(this).attr('id')==='author_url'))){
					if($("#author").is(":checked")){
						$("#author").trigger('click');
					}
					if($("#author_url").is(":checked")){
						$("#author_url").trigger('click');
					}
					$(this).trigger('click');
				}

				generateCode('text',{
					'required':true,
					'name':true,
					'author': true,
					'author_url': true,
					'id': true,
					'class': true,
					'size': true,
					'maxlength': true,
					'watermark': true,
					'watermark_value': true,
					'text-code-2': true
				});
			});

			$("#name").val(generateTagId('text')).trigger('change');
		}
		else if(selected_field==='Email Field'){
			$('#tag-email').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #size, #maxlength, #id, #class, #author, #watermark, #watermark_value, #required").change(function(){
				generateCode('email',{
					'required':true,
					'name':true,
					'author': true,
					'id': true,
					'class': true,
					'size': true,
					'maxlength': true,
					'watermark': true,
					'watermark_value': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('email')).trigger('change');
		}
		else if(selected_field==='URL'){
			$('#tag-url').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #size, #maxlength, #id, #class, #author, #watermark, #watermark_value, #required").change(function(){
				generateCode('url',{
					'required':true,
					'name':true,
					'author': true,
					'id': true,
					'class': true,
					'size': true,
					'maxlength': true,
					'watermark': true,
					'watermark_value': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('url')).trigger('change');
		}
		else if(selected_field==='Number'){
			$('#tag-number').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #min, #max, #step, #id, #class, #author, #watermark, #watermark_value, #required").change(function(){
				generateCode('number',{
					'required':true,
					'name':true,
					'author': true,
					'id': true,
					'class': true,
					'min': true,
					'max': true,
					'step': true,
					'watermark': true,
					'watermark_value': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('number')).trigger('change');
		}
		else if(selected_field==='Date'){
			$('#tag-date').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #minDate, #maxDate, #step, #id, #class, #author, #watermark, #watermark_value, #required").change(function(){
				generateCode('date',{
					'required':true,
					'name':true,
					'author': true,
					'id': true,
					'class': true,
					'minDate': true,
					'maxDate': true,
					'step': true,
					'watermark': true,
					'watermark_value': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('date')).trigger('change');
		}
		else if(selected_field==='Text Area'){
			$('#tag-textarea').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #cols, #rows, #id, #class, #author, #watermark, #watermark_value, #required").change(function(){
				generateCode('textarea',{
					'required':true,
					'name':true,
					'id': true,
					'class': true,
					'cols': true,
					'rows': true,
					'watermark': true,
					'watermark_value': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('textarea')).trigger('change');
		}
		else if(selected_field==='Drop-down menu'){
			$('#tag-dropdown').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #cols, #rows, #id, #class, #multiple, #blank, #required, #option").change(function(){
				generateCode('dropdown',{
					'required':true,
					'name':true,
					'id': true,
					'class': true,
					'multiple': true,
					'blank': true,
					'option': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('dropdown')).trigger('change');
		}
		else if(selected_field==='Checkboxes'){
			$('#tag-checkbox').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #cols, #rows, #id, #class, #first, #element, #exclusive, #required, #option").change(function(){
				generateCode('checkbox',{
					'required':true,
					'name':true,
					'id': true,
					'class': true,
					'first': true,
					'element': true,
					'exclusive': true,
					'option': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('checkbox')).trigger('change');
		}
		else if(selected_field==='Radio buttons'){
			$('#tag-radio').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #cols, #rows, #id, #class, #first, #element, #required, #option").change(function(){
				generateCode('radio',{
					'required':true,
					'name':true,
					'id': true,
					'class': true,
					'first': true,
					'element': true,
					'option': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('radio')).trigger('change');
		}
		else if(selected_field==='Acceptance'){
			$('#tag-acceptance').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #cols, #rows, #id, #class, #default, #inverse, #required").change(function(){
				generateCode('acceptance',{
					'required':true,
					'name':true,
					'id': true,
					'class': true,
					'default': true,
					'inverse': true
				});
			});
			$("#name").val(generateTagId('acceptance')).trigger('change');
		}
		else if(selected_field==='Quiz'){
			$('#tag-quiz').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #cols, #rows, #id, #class, #size, #maxlength, #required, #option").change(function(){
				generateCode('quiz',{
					'required':true,
					'name':true,
					'id': true,
					'class': true,
					'size': true,
					'maxlength': true,
					'option': true
				});
			});
			$("#name").val(generateTagId('quiz')).trigger('change');
		}
		else if(selected_field==='Captcha'){
			$('#tag-captcha').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #cols, #rows, #id, #class, #id-2, #class-2, #size, #maxlength, #forecolor, #backcolor, #small, #medium, #large, #required, #option").change(function(){
				if($(this).is(":checked") && (($(this).attr('id')==='small') || ($(this).attr('id')==='medium') || ($(this).attr('id')==='large'))){
					if($("#small").is(":checked")){
						$("#small").trigger('click');
					}
					if($("#medium").is(":checked")){
						$("#medium").trigger('click');
					}
					if($("#large").is(":checked")){
						$("#large").trigger('click');
					}
					$(this).trigger('click');
				}

				generateCode('captcha',{
					'required':true,
					'name':true,
					'id': true,
					'id-2': true,
					'class': true,
					'class-2': true,
					'size': true,
					'maxlength': true,
					'forecolor': true,
					'backcolor': true,
					'small': true,
					'medium': true,
					'large': true,
					'text-code-3': true,
					'text-code-4': true
				});
			});
			$("#name").val(generateTagId('captcha')).trigger('change');
		}
		else if(selected_field==='ReCaptcha'){
			$('#tag-recaptcha').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name").change(function(){

				generateCode('recaptcha',{
					'required':true,
					'name':true,
					'text-code-2':true,
				});
			});
			$("#name").val(generateTagId('recaptcha')).trigger('change');
		}
		else if(selected_field==='File upload'){
			$('#tag-file').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #file_type, #limit, #id, #class, #size, #maxlength, #required, #option").change(function(){
				generateCode('file',{
					'required':true,
					'name':true,
					'id': true,
					'class': true,
					'limit': true,
					'file_type': true,
					'text-code-2': true
				});
			});
			$("#name").val(generateTagId('file')).trigger('change');
		}
		else if(selected_field==='Submit button'){
			$('#tag-submit').tmpl({'sample1':'sample 1 value'}).appendTo('#tab-generator-container');
			$("#name, #id, #class, #label").change(function(){
				generateCode('submit',{
					'id': true,
					'class': true,
					'label': true
				});
			});
			$("#id").trigger('change');
		}
		$(".close-tag-generator").click(function(){
			var not_field_selected_msg = "<p>Click Generate Tag button to display the shortcode generator.</p>";
			$("#tab-generator-container").html(not_field_selected_msg);
		});
	});

	
});

function load_table(){

}

function generateTagId(prefix){
	var new_number = Math.floor((Math.random()*1000)+1); // random nunber
	return prefix+"-"+new_number;
}

function generateCode(type, fields){
	var code = type;
	var code_3 = type;
	var code_4 = type;
	
	/*PARAMS START*/
	if(fields['required'] && $("#required").is(":checked")){
		code += '*';
	}

	if(fields['name']){
		if(getVal('name')!==''){
			setVal('name', underscoreSpace(getVal('name')));
			code += ' '+ underscoreSpace(getVal('name'));
			code_3 += ' '+ underscoreSpace(getVal('name'));
			code_4 += ' '+ underscoreSpace(getVal('name'));
		}
		else{ $("#name").val(generateTagId(type)).trigger('change'); return; }
	}

	if((fields['size'] && getVal('size')!=="") || (fields['maxlength'] && getVal('maxlength')!=="")){
		if(!isNumber(getVal('size'))){
			setVal('size', '');
		}
		if(!isNumber(getVal('maxlength'))){
			setVal('maxlength', '');
		}
	}
	if((fields['size'] && getVal('size')!=="") || (fields['maxlength'] && getVal('maxlength')!=="")){
		code += ' ' + getVal('size') + "/" + getVal('maxlength');
		code_4 += ' ' + getVal('size') + "/" + getVal('maxlength');  
	}
	if((fields['cols'] && getVal('cols')!=="") || (fields['rows'] && getVal('rows')!=="")){
		if(!isNumber(getVal('cols'))){
			setVal('cols', '');
		}
		if(!isNumber(getVal('rows'))){
			setVal('rows', '');
		}
	}
	if((fields['cols'] && getVal('cols')!=="") || (fields['rows'] && getVal('rows')!=="")){
		code += ' ' + getVal('cols') + "x" + getVal('rows');
	}
	if(fields['id'] && getVal('id')!==""){

		setVal('id', underscoreSpace(getVal('id')));
		code += ' id:' + underscoreSpace(getVal('id'));
		code_3 += ' id:' + underscoreSpace(getVal('id'));
	}
	if(fields['class'] && getVal('class')!==""){
		var class_value = getVal('class');
		class_value = class_value.split(" ");

		$.each(class_value, function(k, v){
			code += ' class:' + underscoreSpace(v);
			code_3 += ' class:' + underscoreSpace(v);
		});
	}
	if(fields['id-2'] && getVal('id-2')!==""){
		setVal('id-2', underscoreSpace(getVal('id-2')));
		code_4 += ' id:' + underscoreSpace(getVal('id-2'));
	}
	if(fields['class-2'] && getVal('class-2')!==""){
		setVal('class-2', underscoreSpace(getVal('class-2')));
		code_4 += ' class:' + underscoreSpace(getVal('class-2'));
	}
	if(fields['forecolor'] && getVal('forecolor')!==""){
		var hex = getVal('forecolor')+"";
		code_3 += ' fg:#' + hex.replace('#', '');
	}
	if(fields['backcolor'] && getVal('backcolor')!==""){
		var hex = getVal('backcolor') +"";
		code_3 += ' bg:#' + hex.replace('#', '');
	}
	
	if(fields['limit'] && getVal('limit')!==""){
		code += ' limit:' + getVal('limit');
	}

	if(fields['min'] && getVal('min')!==""){
		if(!isNumber(getVal('min'))){
			setVal('min', '');
		}else{
			code += ' min:' + getVal('min');
		}
	}

	if(fields['max'] && getVal('max')!==""){
		if(!isNumber(getVal('max'))){
			setVal('max', '');
		}else{
			code += ' max:' + getVal('max');
		}
	}

	if(fields['minDate'] && getVal('minDate')!==""){
		code += ' min:' + getVal('minDate');
	}

	if(fields['maxDate'] && getVal('maxDate')!==""){
		code += ' max:' + getVal('maxDate');
	}

	if(fields['step'] && getVal('step')!==""){
		if(!isNumber(getVal('step'))){
			setVal('step', '');
		}else{
			code += ' step:' + getVal('step');
		}
	}

	if(fields['file_type'] && getVal('file_type')!==""){
		var type_trim = getVal('file_type') + "";
		type_trim = type_trim.replace(',', ' ');
		var type = type_trim.split(' ');
		code += ' filetypes:' + type.join('|');
	}

	if(fields['label'] && getVal('label')!==""){
		code += ' "'+ getVal('label') +'"';
	}
	if(fields['author'] && $("#author").is(":checked")){
		code += ' akismet:author';
	}
	else if(fields['author_url'] && $("#author_url").is(":checked")){
		code += ' akismet:author_url';
	}
	if(fields['multiple'] && $("#multiple").is(":checked")){
		code += ' multiple';
	}
	if(fields['small'] && $("#small").is(":checked")){
		code_3 += ' size:s';
	}
	else if(fields['medium'] && $("#medium").is(":checked")){
		code_3 += ' size:m';
	}
	else if(fields['large'] && $("#large").is(":checked")){
		code_3 += ' size:l';
	}
	if(fields['blank'] && $("#blank").is(":checked")){
		code += ' include_blank';
	}
	if(fields['first'] && $("#first").is(":checked")){
		code += ' label_first';
	}
	if(fields['element'] && $("#element").is(":checked")){
		code += ' use_label_element';
	}
	if(fields['exclusive'] && $("#exclusive").is(":checked")){
		code += ' exclusive';
	}
	if(fields['default'] && $("#default").is(":checked")){
		code += ' default:on';
	}
	if(fields['inverse'] && $("#inverse").is(":checked")){
		code += ' invert';
	}
	if(fields['watermark'] && $("#watermark").is(":checked")){
		code += ' watermark';
	}
	if(fields['watermark_value'] && getVal('watermark_value')!==''){
		code += ' "'+ getVal('watermark_value')+'"';
	}
	if(fields['option'] && getVal('option')!==''){
		var options = getVal('option').split("\n");
		$.each(options, function(k, v){
			if(v!=='')code += ' "'+ v +'"';
		})
	}
	/*PARAMS END*/

	$("#text-code").text("["+ code +"]");
	if(fields['text-code-2'] && fields['name']){
		$("#text-code-2").text("["+ getVal('name') +"]");
	}
	if(fields['text-code-3'] && fields['name']){
		$("#text-code-3").text("["+ code_3 +"]");
	}
	if(fields['text-code-4'] && fields['name']){
		$("#text-code-4").text("["+ code_4 +"]");
	}
}

function underscoreSpace(str){
	var str_ = str.split(' ');
	var new_str = str_.join('');
	return new_str;
}

function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function validHex(n) {
	n = n + "";
	n = n.replace('#', '');
	var patt1=new RegExp("/^[a-zA-Z_][0-9]$/");
	return patt1.test(n);
}

$('.btn-add').on('click',function(evt){
	evt.preventDefault(); 
	$('.icon-spinner').show();
	var messages={
		mail_sent_ok: getVal('mail_sent_ok'),
		mail_sent_ng: getVal('mail_sent_ng'),
		validation_error: getVal('validation_error'),
		spam: getVal('spam'),
		accept_terms: getVal('accept_terms'),
		invalid_required: getVal('invalid_required'),
		captcha_not_match: getVal('captcha_not_match'),
		invalid_number: getVal('invalid_number'),
		number_too_small: getVal('number_too_small'),
		number_too_large: getVal('number_too_large'),
		invalid_email: getVal('invalid_email'),
		invalid_url: getVal('invalid_url'),
		invalid_tel: getVal('invalid_tel'),
		quiz_answer_not_correct: getVal('quiz_answer_not_correct'),
		invalid_date: getVal('invalid_date'),
		date_too_early: getVal('date_too_early'),
		date_too_late: getVal('date_too_late'),
		upload_failed: getVal('upload_failed'),
		upload_file_type_invalid: getVal('upload_file_type_invalid'),
		upload_file_too_large: getVal('upload_file_too_large'),
		upload_failed_php_error: getVal('upload_failed_php_error')
	};

	var mail2_html_content_type = ($('#mail2_html_content_type').is(':checked'))?'1':'0';
	var mail2_active = ($('#mail2_active').is(':checked'))?'1':'0';
	var mail2 = {
		mail2_active: mail2_active,
		mail2_to: getVal('mail2_to'),
		mail2_from: getVal('mail2_from'),
		mail2_subject: getVal('mail2_subject'),
		mail2_additional_header: getVal('mail2_additional_header'),
		mail2_file_attachment: getVal('mail2_file_attachment'),
		mail2_message_body: getVal('mail2_message_body'),
		mail2_html_content_type: mail2_html_content_type
	}

	var mail_html_content_type = ($('#mail_html_content_type').is(':checked'))?'1':'0';
	var mail = {
		mail_to: getVal('mail_to'),
		mail_from: getVal('mail_from'),
		mail_subject: getVal('mail_subject'),
		mail_additional_header: getVal('mail_additional_header'),
		mail_file_attachment: getVal('mail_file_attachment'),
		mail_message_body: getVal('mail_message_body'),
		mail_html_content_type: mail_html_content_type
	}

	var toggle_option = {
		"enable_captcha" : ($('#enable-recaptcha').is(':checked'))?'Y':'N',
	}
	
	var action = 'create';
	if($('#action').val() == 'edit')
		action = 'update';
	$.post(CONFIG.get('URL')+'contact-forms/'+action,{
		action:action,
		id:getVal('contact_id'),
		title: getVal('title'),
		content: getVal('content'),
		form: getVal('form'),
		mail: mail,
		mail2: mail2,
		messages: messages,
		additional_settings: getVal('additional_settings'),
		toggle_option: toggle_option,
	},function(res){
		$('.icon-spinner').hide();
		var result = JSON.parse(res);
		if(result > 0){
			$('#result').append(alertMessage('Contact Form Successfully Saved','success','success1'));
			if(action == 'create')
				setTimeout(function(){ window.location = CONFIG.get('URL') + 'contact-forms/edit/'+result; }, 1000);
			else
				setTimeout(function(){ window.location = CONFIG.get('URL') + 'contact-forms/'; }, 1000);
		}
		else
			$('#result').append(alertMessage('Error while adding','error','error1'));
	});
});

$('#mail2_active').on('change',function(){
	if($(this).is(':checked')){
		$('.mail2_panel').slideDown();
	}else{
		$('.mail2_panel').slideUp();
	}
});

$('.btn-edit').on('click',function(evt){
	evt.preventDefault();
	$('.loader').removeClass('disappear');
	var messages={
		mail_sent_ok: getVal('mail_sent_ok'),
		mail_sent_ng: getVal('mail_sent_ng'),
		validation_error: getVal('validation_error'),
		spam: getVal('spam'),
		accept_terms: getVal('accept_terms'),
		invalid_required: getVal('invalid_required'),
		captcha_not_match: getVal('captcha_not_match'),
		invalid_number: getVal('invalid_number'),
		number_too_small: getVal('number_too_small'),
		number_too_large: getVal('number_too_large'),
		invalid_email: getVal('invalid_email'),
		invalid_url: getVal('invalid_url'),
		invalid_tel: getVal('invalid_tel'),
		quiz_answer_not_correct: getVal('quiz_answer_not_correct'),
		invalid_date: getVal('invalid_date'),
		date_too_early: getVal('date_too_early'),
		date_too_late: getVal('date_too_late'),
		upload_failed: getVal('upload_failed'),
		upload_file_type_invalid: getVal('upload_file_type_invalid'),
		upload_file_too_large: getVal('upload_file_too_large'),
		upload_failed_php_error: getVal('upload_failed_php_error')
	};

	var mail_html_content_type = ($('#mail_html_content_type').is(':checked'))?'1':'0';
	var mail = {
		mail_to: getVal('mail_to'),
		mail_from: getVal('mail_from'),
		mail_subject: getVal('mail_subject'),
		mail_additional_header: getVal('mail_additional_header'),
		mail_file_attachment: getVal('mail_file_attachment'),
		mail_message_body: getVal('mail_message_body'),
		mail_html_content_type: mail_html_content_type
	}

	var mail2_html_content_type = ($('#mail2_html_content_type').is(':checked'))?'1':'0';
	var mail2_active = ($('#mail2_active').is(':checked'))?'1':'0';
	var mail2 = {
		mail2_active: mail2_active,
		mail2_to: getVal('mail2_to'),
		mail2_from: getVal('mail2_from'),
		mail2_subject: getVal('mail2_subject'),
		mail2_additional_header: getVal('mail2_additional_header'),
		mail2_file_attachment: getVal('mail2_file_attachment'),
		mail2_message_body: getVal('mail2_message_body'),
		mail2_html_content_type: mail2_html_content_type
	}

	$.post(pathFile+'update',{
		action:'update',
		id:getVal('contact_form_id'),
		title: getVal('title'),
		content: getVal('content'),
		form: getVal('form'),
		mail: mail,
		mail2: mail2,
		messages: messages,
		additional_settings: getVal('additional_settings')
	},function(res){
		$('.loader').addClass('disappear');
		$('#shortcode').html('[contact-form-7 id="'+getVal('contact_form_id')+'" title="'+getVal('title')+'"]');
		var msg="<div class='alert alert-success black'><strong>Contact form saved.</div>";
		$('#showMessage').html(msg);
	});
});


$('#contact-form-delete').on('click',function(evt){
	evt.preventDefault();
	if(confirm("You are about to delete this contact form? \n 'Cancel' to stop, 'OK' to delete.")){
		$.post(pathFile+'delete',{id:$(this).data('id')},function(response){
			eval(response);
		});
	}
});

$('#contact-form-copy').on('click',function(evt){
	evt.preventDefault();
	var messages={
		mail_sent_ok: getVal('mail_sent_ok'),
		mail_sent_ng: getVal('mail_sent_ng'),
		validation_error: getVal('validation_error'),
		spam: getVal('spam'),
		accept_terms: getVal('accept_terms'),
		invalid_required: getVal('invalid_required'),
		captcha_not_match: getVal('captcha_not_match'),
		invalid_number: getVal('invalid_number'),
		number_too_small: getVal('number_too_small'),
		number_too_large: getVal('number_too_large'),
		invalid_email: getVal('invalid_email'),
		invalid_url: getVal('invalid_url'),
		invalid_tel: getVal('invalid_tel'),
		quiz_answer_not_correct: getVal('quiz_answer_not_correct'),
		invalid_date: getVal('invalid_date'),
		date_too_early: getVal('date_too_early'),
		date_too_late: getVal('date_too_late'),
		upload_failed: getVal('upload_failed'),
		upload_file_type_invalid: getVal('upload_file_type_invalid'),
		upload_file_too_large: getVal('upload_file_too_large'),
		upload_failed_php_error: getVal('upload_failed_php_error')
	};

	var mail2_html_content_type = ($('#mail2_html_content_type').is(':checked'))?'1':'0';
	var mail2_active = ($('#mail2_active').is(':checked'))?'1':'0';
	var mail2 = {
		mail2_active: mail2_active,
		mail2_to: getVal('mail2_to'),
		mail2_from: getVal('mail2_from'),
		mail2_subject: getVal('mail2_subject'),
		mail2_additional_header: getVal('mail2_additional_header'),
		mail2_file_attachment: getVal('mail2_file_attachment'),
		mail2_message_body: getVal('mail2_message_body'),
		mail2_html_content_type: mail2_html_content_type
	}

	var mail_html_content_type = ($('#mail_html_content_type').is(':checked'))?'1':'0';
	var mail = {
		mail_to: getVal('mail_to'),
		mail_from: getVal('mail_from'),
		mail_subject: getVal('mail_subject'),
		mail_additional_header: getVal('mail_additional_header'),
		mail_file_attachment: getVal('mail_file_attachment'),
		mail_message_body: getVal('mail_message_body'),
		mail_html_content_type: mail_html_content_type
	}

	$.post(pathFile+'copy',{
		action:'copy',
		id:getVal('maxid'),
		title: getVal('title')+'_copy',
		content: getVal('content'),
		form: getVal('form'),
		mail: mail,
		mail2: mail2,
		messages: messages,
		additional_settings: getVal('additional_settings')
	},function(response){
		eval(response);
	});
});


$('#btnApply').on('click',function(evt){
	evt.preventDefault();
	var countSelected = 0,
	ids=[],
	status='';

	$('table th input:checkbox').closest('table').find('tr > td:first-child input:checkbox:checked')
	.each(function(){
		ids.push(this.value);
		countSelected++;
	});

	if(countSelected!==0){
		$('#showMessage').empty();
		var msg ="";

		if(getVal('applyAction')=='delete'){

			$.post(pathFile+'deleteContactForms',{action:'delete',ids:ids},function(response){
				if (response == '1') {
					msg+="<div class='alert alert-warning black'> <span class='black'>Contact form deleted.</span>";
					$('#showMessage').html(msg);
					notification("Contact Form", "Contact Form deleted", "gritter-success");
				}else{
					notification("Contact Form", "Server Error", "gritter-error");
				}

				$('#chkAll').prop('checked', false);

				$('table th input:checkbox').closest('table').find('tr > td:first-child input:checkbox:checked')
				.each(function(){
					$(this).parent().parent().parent().remove();
				});
			});
		}else{
			alert('Please select a bulk action.');
		}		

		$('#showMessage').html(msg);

	}else{
		alert('Nothing selected.');
	}
});		


function copyContactForm(id){	
	$.post(pathFile+'copy',{action:'copy2',id:id},function(response){
		eval(response);
	});
}
function response(){
	$.post(CONFIG.get('URL')+'contact-forms/load_data',{action:'get',id:$('#contact_form_id').val()}, function(response,status){
		if(status == 'success'){
			var result = JSON.parse(response);
			var rows = result['rows'];
			/*getting the columnames and models*/
			if(rows.length != 0){
				col_models = [];
				col_models.push({name:'myac',index:'', width:80, fixed:true, sortable:false, resize:false,
					formatter:'actions', 
					formatoptions:{ 
						keys:true,

						delOptions:{recreateForm: true, beforeShowForm:beforeDeleteCallback},
						/*editformbutton:true, editOptions:{recreateForm: true, beforeShowForm:beforeEditCallback}*/
					}});
				var col_names =[' '];
				for(var key in rows[0]) {
					col_names.push(key);
					col_models.push({name:key,index:key});
				}

				var grid_selector = "#grid-table";
				var pager_selector = "#grid-pager";

				jQuery(grid_selector).jqGrid({
					/*direction: "rtl",*/
					
					data: rows,
					datatype: "local",
					height: 250,
					colNames:col_names,
					colModel:col_models, 

					viewrecords : true,
					rowNum:10,
					rowList:[10,20,30],
					pager : pager_selector,
					altRows: true,
					/*toppager: true,*/
					
					multiselect: true,
					/*multikey: "ctrlKey",*/
					multiboxonly: true,

					loadComplete : function() {
						var table = this;
						setTimeout(function(){
							styleCheckbox(table);
							
							updateActionIcons(table);
							updatePagerIcons(table);
							enableTooltips(table);
						}, 0);
					},

					caption: "jqGrid with inline editing",


					autowidth: true

				});

				jQuery(grid_selector).jqGrid('navGrid',pager_selector,{
					/*navbar options*/
					edit: true,
					editicon : 'icon-pencil blue',
					add: true,
					addicon : 'icon-plus-sign purple',
					del: true,
					delicon : 'icon-trash red',
					search: true,
					searchicon : 'icon-search orange',
					refresh: true,
					refreshicon : 'icon-refresh green',
					view: true,
					viewicon : 'icon-zoom-in grey',
				},
				{
					/*edit record form*/
					/*closeAfterEdit: true,*/
					recreateForm: true,
					beforeShowForm : function(e) {
						var form = $(e[0]);
						form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
						style_edit_form(form);
					}
				},
				{
					/*new record form*/
					closeAfterAdd: true,
					recreateForm: true,
					viewPagerButtons: false,
					beforeShowForm : function(e) {
						var form = $(e[0]);
						form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
						style_edit_form(form);
					}
				},
				{
					/*delete record form*/
					recreateForm: true,
					beforeShowForm : function(e) {
						var form = $(e[0]);
						if(form.data('styled')) return false;

						form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
						style_delete_form(form);

						form.data('styled', true);
					},
					onClick : function(e) {
						alert(1);
					}
				},
				{
					/*search form*/
					recreateForm: true,
					afterShowSearch: function(e){
						var form = $(e[0]);
						form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
						style_search_form(form);
					},
					afterRedraw: function(){
						style_search_filters($(this));
					}
					,
					multipleSearch: true,
					/* multipleGroup:true,*/
					/*showQuery: true*/
				},
				{
					/*view record form*/
					recreateForm: true,
					beforeShowForm: function(e){
						var form = $(e[0]);
						form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
					}
				}
				);
			}
		}
	});
}
function style_edit_form(form) {
	/*enable datepicker on "sdate" field and switches for "stock" field*/
	form.find('input[name=sdate]').datepicker({format:'yyyy-mm-dd' , autoclose:true})
	.end().find('input[name=stock]')
	.addClass('ace ace-switch ace-switch-5').wrap('<label class="inline" />').after('<span class="lbl"></span>');

	/*update buttons classes*/
	var buttons = form.next().find('.EditButton .fm-button');
	buttons.addClass('btn btn-sm').find('[class*="-icon"]').remove();//ui-icon, s-icon
	buttons.eq(0).addClass('btn-primary').prepend('<i class="icon-ok"></i>');
	buttons.eq(1).prepend('<i class="icon-remove"></i>')
	
	buttons = form.next().find('.navButton a');
	buttons.find('.ui-icon').remove();
	buttons.eq(0).append('<i class="icon-chevron-left"></i>');
	buttons.eq(1).append('<i class="icon-chevron-right"></i>');		
}
function style_delete_form(form) {
	var buttons = form.next().find('.EditButton .fm-button');
	buttons.addClass('btn btn-sm').find('[class*="-icon"]').remove();//ui-icon, s-icon
	buttons.eq(0).addClass('btn-danger').prepend('<i class="icon-trash"></i>');
	buttons.eq(1).prepend('<i class="icon-remove"></i>')
}
function style_search_filters(form) {
	form.find('.delete-rule').val('X');
	form.find('.add-rule').addClass('btn btn-xs btn-primary');
	form.find('.add-group').addClass('btn btn-xs btn-success');
	form.find('.delete-group').addClass('btn btn-xs btn-danger');
}
function style_search_form(form) {
	var dialog = form.closest('.ui-jqdialog');
	var buttons = dialog.find('.EditTable')
	buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'icon-retweet');
	buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'icon-comment-alt');
	buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'icon-search');
}
function beforeDeleteCallback(e) {
	var form = $(e[0]);
	if(form.data('styled')) return false;
	
	form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	style_delete_form(form);
	
	form.data('styled', true);
}
function beforeEditCallback(e) {
	var form = $(e[0]);
	form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	style_edit_form(form);
}
/*it causes some flicker when reloading or navigating grid
it may be possible to have some custom formatter to do this as the grid is being created to prevent this
or go back to default browser checkbox styles for the grid*/

function styleCheckbox(table) {
	/**
	$(table).find('input:checkbox').addClass('ace')
	.wrap('<label />')
	.after('<span class="lbl align-top" />')


	$('.ui-jqgrid-labels th[id*="_cb"]:first-child')
	.find('input.cbox[type=checkbox]').addClass('ace')
	.wrap('<label />').after('<span class="lbl align-top" />');
	*/
}

/*unlike navButtons icons, action icons in rows seem to be hard-coded*/
/*you can change them like this in here if you want*/
function updateActionIcons(table) {
	/**
	var replacement = 
	{
		'ui-icon-pencil' : 'icon-pencil blue',
		'ui-icon-trash' : 'icon-trash red',
		'ui-icon-disk' : 'icon-ok green',
		'ui-icon-cancel' : 'icon-remove red'
	};
	$(table).find('.ui-pg-div span.ui-icon').each(function(){
		var icon = $(this);
		var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
		if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
	})
	*/
}

/*replace icons with FontAwesome icons like above*/
function updatePagerIcons(table) {
	var replacement = 
	{
		'ui-icon-seek-first' : 'icon-double-angle-left bigger-140',
		'ui-icon-seek-prev' : 'icon-angle-left bigger-140',
		'ui-icon-seek-next' : 'icon-angle-right bigger-140',
		'ui-icon-seek-end' : 'icon-double-angle-right bigger-140'
	};
	$('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
		var icon = $(this);
		var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
		
		if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
	})
}

function enableTooltips(table) {
	$('.navtable .ui-pg-button').tooltip({container:'body'});
	$(table).find('.ui-pg-div').tooltip({container:'body'});
}
/*switch element when editing inline*/
function aceSwitch( cellvalue, options, cell ) {
	setTimeout(function(){
		$(cell) .find('input[type=checkbox]')
		.wrap('<label class="inline" />')
		.addClass('ace ace-switch ace-switch-5')
		.after('<span class="lbl"></span>');
	}, 0);
}
/*enable datepicker*/
function pickDate( cellvalue, options, cell ) {
	setTimeout(function(){
		$(cell) .find('input[type=text]')
		.datepicker({format:'yyyy-mm-dd' , autoclose:true}); 
	}, 0);
}


$('.loader').addClass('disappear');