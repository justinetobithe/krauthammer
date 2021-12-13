var products = {};
var edit = 0;
var monthNames = [
        "January", "February", "March",
        "April", "May", "June", "July",
        "August", "September", "October",
        "November", "December"
    ];
$(document).ready(function(){
	var action = $('#action').val();

	if(action == 'manage_appointments')
		load_manage_appointments();
	else if(action == 'edit')
		load_edit_appointments();
});
function load_manage_appointments(){
	load_table();
}
function load_edit_appointments(){
	load_products();	
}
function load_trip_periods(id){
	var apps_id = $('#hidden_apps_id').val();
	$('#trip_period').empty();
	$('#trip_period_div').addClass('hide');
	$.post(CONFIG.get('URL')+'appointments/get_trip_period',{action:'get', product_id:id }, function(response,status){
		obj = JSON.parse(response);
		var count = 0;
		$.each(obj, function(i, trip){
			
				if(apps_id == trip['id']){
					edit = 0;
					$('#trip_period').append('<option value="'+trip['id']+'" data-foo="'+trip['spots']+'" selected>'+trip['trip_period']+'</option>');
					$('#spots').html(trip['spots']);
				}
				else
					$('#trip_period').append('<option value="'+trip['id']+'" data-foo="'+trip['spots']+'">'+trip['trip_period']+'</option>');

			
			count++;
		});

		if(count == 0){
			$('#trip_period_div').addClass('hide');
			$('#trip_period_warning').removeClass('hide');
		}
		else{
			$('#trip_period_warning').addClass('hide');
			$('#trip_period_div').removeClass('hide');
		}
		
		

		$('#trip_period').change(function(){
			//alert(apps_id);
			edit++;
			var selected = $(this).find('option:selected');
       		var extra = selected.data('foo'); 
       		$('#spots').html(extra);
       		if($('#trip_period').find('option:selected').val() == apps_id) {
       			edit = 0;
       		}
		});
		if($('#trip_period').find('option:selected').val() != apps_id) {
		    var selected = $('#trip_period').find('option:selected')
       		var extra = selected.data('foo');
       		$('#spots').html(extra);
		}
		 
	});
}
function load_table(){

	$('#appointments_table').dataTable().fnDestroy();

	$.post(CONFIG.get('URL')+'appointments/get_appointments',{action:'get'}, function(response,status){
		var result = JSON.parse(response);
		if(result.length > 0)
		    $.each(result, function(i, field){
		     
		     var date_from = new Date(field['date_from']);
		  	 var date_f = date_from.toString().split(' ');
		  	// alert(date_f);
		     var date_to = new Date(field['date_to']);
		     var date_t = date_to.toString().split(' ');
		     date = date_f[2] +' '+monthNames[date_from.getMonth() ]+ ' '+ date_from.getFullYear() +' - '+ date_t[2] +' '+monthNames[date_to.getMonth()]+ ' '+ date_to.getFullYear();
		     //alert((date.getMonth() + 1).toString() + '/' + date.getDate() + '/' +  date.getFullYear());
		      $('#appointments_table tbody').append('<tr><td class="center"><label><input type="checkbox" class="ace"/><span class="lbl"></span></label></td><td>'+field['country']+'</td><td>'+field['name']+'</td><td>'+field['contact_no']+'</td><td>'+field['email']+'</td><td>'+date+'</td><td>'+field['special_request']+'</td><td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group"><button class="btn btn-minier btn-warning" data-rel="tooltip" data-placement="top" title="View" onclick="view_appointments('+field['id']+');"><i class="icon-info-sign bigger-120"></i></button><button class="btn btn-minier btn-info" data-rel="tooltip" data-placement="top" title="Edit" onclick="edit_appointments('+field['id']+')"><i class="icon-edit bigger-120"></i></button></div></td></tr>');
			
			});
		 $('[data-rel=tooltip]').tooltip();
	     $('#appointments_table').dataTable( {
	      "aoColumns": [
	       { "bSortable": false },
	       null,
	       null,
	       null,
	       null,
	       null,
	       null,
	       { "bSortable": false }
	      ]});
	});
}
function edit_appointments(id){
	window.location.href = CONFIG.get('URL')+'appointments/edit/'+id;
}
function view_appointments(id){
	window.location.href = CONFIG.get('URL')+'appointments/view/'+id;
}
function back_to_appointments(){
	window.location.href = CONFIG.get('URL')+'appointments/';
}
function load_appointments(){
	var id = $('#hidden_id').val();
	var apps_id = $('#hidden_apps_id').val();

	$.post(CONFIG.get('URL')+'appointments/get_appointments_by_id', {action:'get', id:id},function(response,status){
		var result = JSON.parse(response);
		
		$('#products').val(result['product_id']).trigger("chosen:updated");
		$('#products').trigger('change');
		$('#name').val(result['name']);
		//alert(result['product_appointment_id']);
		//$('#trip_period').val(result['product_appointment_id']);
		$('#request').val(result['special_request']);
		$('#email').val(result['email']);
		$('#contact_number').val(result['contact_no']);
		$('#country').val(result['country']);
		//$('#trip_period').trigger('change');
	});
}
function load_products(){
	$.post(CONFIG.get('URL')+'appointments/load_products', {action:'load'},function(response,status){
		products = JSON.parse(response);
		$('#products').change(function(e){
			var id = $(this).val();

			$.each(products, function(i, product){
				edit++;
				if(id == product['id']){

					var image = '/images/uploads/default.png';

					if(product['featured_image_url'] != '')
						image = product['featured_image_url'];

					image = image.replace('/images/', '/thumbnails/234x155/');
					$('#product_image').attr('src', CONFIG.get('FRONTEND_URL')+image);
				}
				
			});
			
			load_trip_periods(id);
		});
			
		load_appointments();
	});
}
function save_appointment(){
	if(validate()){

		var data = {};
		var selected = $('#trip_period').find('option:selected');
    	var spots = selected.data('foo'); 

		data['product_id'] = $('#products').val();
		data['product_appointment_id'] = $('#trip_period').val();
		data['name'] = $('#name').val();
		data['contact_no'] = $('#contact_number').val();
		data['email'] = $('#email').val();
		data['country'] = $('#products').find('option:selected').text();
		data['special_request'] = $('#request').val();
		data['id'] = $('#hidden_id').val();
		data['old_apps_id'] = 0;
		if(edit > 0){
			data['old_apps_id'] = $('#hidden_apps_id').val();
			data['spots'] =  parseInt(spots) - 1;
		}
		else
			data['spots'] = parseInt(spots);

		if(data['spots'] < 0)
			data['spots'] = 0;
		
		$('#alert_appointments').empty();
		//alert(data['date_to']);
		$.post(CONFIG.get('URL')+'appointments/save_appointment', {action:'save', data: data },function(response,status){
			//alert(response);
			var result = JSON.parse(response);
			if(!result){
				$('#alert_appointments').append(alertMessage('Error while saving.','error','error'));

			}else{
				$('#alert_appointments').append(alertMessage('Successfully Updated Appointments.','success','success'));
				setTimeout(function(){ window.location.href = CONFIG.get('URL')+'appointments/'; }, 2000);
			}

		});
	}
}
function validate(){

	var error = false;

	var selected = $('#trip_period').find('option:selected');
    var spots = selected.data('foo'); 
   // alert(spots+' = '+edit);
	$('#alert_appointments').empty();

	if($('#date_from').val() == ''){
		$('#alert_appointments').append(alertMessage('Please Select Date From.','error','error'));
		error = true;
	}
	else if($('#date_to').val() == ''){
		$('#alert_appointments').append(alertMessage('Please Select Date To.','error','error'));
		error = true;
	}
	else if($('#name').val() == ''){
		$('#alert_appointments').append(alertMessage('Please Insert Customer Name.','error','error'));
		error = true;
	}
	else if(spots == '0' && edit >= 1){
		$('#alert_appointments').append(alertMessage('There are no Spots Left.','error','error'));
		error = true;
	}

	if(!error)
		return true;

	return false;
}
function convert(string){
	var date = string.split('-');

	return date[1]+'/'+date[2]+'/'+date[0];
}


/*function load_time_period_html(obj){
	$('#time_period').empty();
	$('#date_from').val('');
	$('#date_to').val('');
	$('#date_from').datepicker('destroy');
	$('#date_to').datepicker('destroy');

	if($.isEmptyObject(obj) == false){
		time_action = 'edit';
		$('#div_time_period').removeClass('hide');
		$('#time_period_label').html('Edit Trip Period:');

		$.each(obj, function(i, field){

			var datef = new Date(field['date_from']);
			var datefrom = datef.toString().split(' ');
		    var datet = new Date(field['date_to']);
		    var dateto = datet.toString().split(' ');
		   // alert(datefrom);
			date = datefrom[2]+ ' ' + monthNames[datef.getMonth()] + ' ' + datefrom[3] + ' - ' +dateto[2]+ ' ' + monthNames[datet.getMonth()] + ' ' + dateto[3];
			
			$('#time_period').append('<option value="'+field['id']+'">'+date+'</option>');
		});

		$('#time_period').val($('#hidden_apps_id').val());

		if($('#time_period').val() == null)
			$("#time_period").val($("#time_period option:first").val());

		var date = $('#time_period option:selected').text().split('-');

		date_f = date[0].split(' ');
		date_f_m = (monthNames.indexOf(date_f[1])+1);
		if(date_f_m < 10)
			date_f_m = '0'+date_f_m;
		date_from = date_f[0]+'/'+date_f_m+'/'+date_f[2];

		date_t = date[1].split(' ');
		date_t_m = (monthNames.indexOf(date_t[2])+1);
		if(date_t_m < 10)
			date_t_m = '0'+date_t_m;
		date_to = date_t[1]+'/'+date_t_m+'/'+date_t[3];

		load_dates(date_from,date_to);
		}else{
			time_action = 'add';
			$('#div_time_period').addClass('hide');
			$('#time_period_label').html('Add Trip Period:');
			$('#date_from').datepicker({dateFormat: 'dd/mm/yy'});
			$('#date_to').attr('disabled','true');

		}
	
}*/
/*
function load_dates(date_from, date_to){

	//alert(convert_to_datepicker($.trim(date_from)));

	$('#date_from').val($.trim(date_from));
	$('#date_to').val($.trim(date_to));
	$('#date_from').datepicker({ dateFormat: 'dd/mm/yy'});

	$('#date_from').change(function(e){

		var date_to = $('#date_to');
		date_to.datepicker("destroy");
		

		if($(this).val() != ''){

			var date2 = $(this).datepicker('getDate', '+1d');
	    	date2.setDate(date2.getDate()+1);

			date_to.datepicker({minDate:date2, dateFormat: 'dd/mm/yy'});
			date_to.removeAttr('disabled');
		}else{
			date_to.val('');
			date_to.attr('disabled','true');
		}
	});

	$('#date_from').trigger('change');
}*/