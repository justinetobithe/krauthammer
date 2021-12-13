$(function(){
	$('.timepicker').timepicker({
		minuteStep: 1,
		showSeconds: false,
		showMeridian: true
	});
	$('.datepicker').datepicker().next().on(ace.click_event, function() {
		$(this).prev().focus();
	});

	$("#btn-append-custom-field").click(function(e){

		if ($("#input-append-custom-field").val() != "") {
			var ncf = $('#template-custom-field').tmpl({'field_name': $("#input-append-custom-field").val()}).appendTo('#custom-fields-container');

			ncf.find('.custom-field-value').focus()

			initialize_custom_field_remove(ncf.find('.custom-field-btn-remove'));

			$("#input-append-custom-field").val('')
		}else{
			notification("Custome Field", "Enter Field name", "gritter-error");
		}
		
	});

	$.each($(".custom-field-btn-remove"), function(k, v){
		initialize_custom_field_remove($(this));
	});

	function initialize_custom_field_remove(btn){
		btn.click(function(e){
			bootbox.confirm("Are you sure you want to delete selected item?", function(result){
				if (result) {
					btn.parents('.page-custom-fields').remove();
				}
			});
		});
	}
});