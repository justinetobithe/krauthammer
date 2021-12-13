$(function(){
	$('#tax-rate-enable').change(function(){
		if ($(this).is(":checked")) {
			$("#tax-rate-container").show();
		}else{
			$("#tax-rate-container").hide();
		}
	}).trigger('change');

	$("#save_tax").click(function(){
		bootbox.confirm("Are you sure you want to delete selected menu?", function(result){
			if (result) {
  			saveTax();
			}
		});
	});

	getTax();
});

function saveTax(){
	var data = {}

	data['tax_enable'] = $('#tax-rate-enable:checked').length > 0 ? 'Y' : 'N';
	data['tax_rate'] =  $("#tax-rate").val();

	$.post(CONFIG.get('URL')+'tax/process',{action:'save', data: data}, function(response,status){
		var response_data = JSON.parse(response);
		notification ("Tax", response_data, "gritter-success");
	});
}

function getTax(){
	$.post(CONFIG.get('URL')+'tax/process',{action:'get'}, function(response,status){
		var response_data = JSON.parse(response);
		var tax_enable = typeof response_data['tax_enable'] != 'undefined' ? response_data['tax_enable'] : "N";
		var tax_rate = typeof response_data['tax_rate'] != 'undefined' ? response_data['tax_rate'] : "";

		if (tax_enable == 'N') {
			if ($('#tax-rate-enable:checked').length) {
				$('#tax-rate-enable').trigger('click');
			}
		}else{
			if (!$('#tax-rate-enable:checked').length) {
				$('#tax-rate-enable').trigger('click');
			}
		}
		$('#tax-rate').val(tax_rate);
	});
}