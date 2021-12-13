$(function(){
	$("#cfforms").chosen().change(function(){
		getContactFormCollection();
	}).trigger('change');

	$("#modal-btn-edit-save").click(function(e){
		var selected_id = $("#modal-selected-id").val();
		var data = {};

		$.each($("#modal-container-items > .control-group"), function(e){
			var current_item = $(this);
			var key		= current_item.find('.modal-item-name').val();
			var value = current_item.find('.modal-item-value').val();
			data[key]  = value;
		});

		$.post(CONFIG.get('URL')+'contact-forms/response_processor/',{
	  	action 	: 'save-item',
	  	id 			: selected_id,
	  	data 		: JSON.stringify(data),
	  },function(response) {
	  	if (response == '1') {
	  		notification("Contact Form Data Collected", "Successfully saved.", "gritter-success");
	  		getContactFormCollection();
	  		$("#modal-edit-item").modal('hide')
	  	}else{
	  		notification("Contact Form Data Collected", "Error: " + response.toString(), "gritter-error");
	  	}
	  });
	});

	$("#modal-edit-item").modal({
		'show' : false,
	});
});

function getContactFormCollection(){
	$.post(CONFIG.get('URL')+'contact-forms/response_processor/',{
		action : 'get-collection',
		id : $("#cfforms").val()
	},
	function(response) {
		response = response;

		if (typeof response['headers'] != 'undefined') {
			clearTable();

			var formHead = $("#contact-form-collection thead").html('');
			var formResponse = $("#contact-form-collection tbody").html('');
			var formHeadTemp = $("<tr></tr>");
			$.each(response['headers'], function(k, v){
				v = v.replace(/[-_]/g, ' ');
				v = v.toLowerCase().replace(/\b[a-z]/g, function(letter) {
						    return letter.toUpperCase();
						});
				formHeadTemp.append('<th>'+ v +'</th>');
			});
			/* Add action */
			formHeadTemp.append('<th>Action</th>');

			formHead.append(formHeadTemp);
			$.each(response['responses'], function(kk, vv){
				var tableRow = $("<tr></tr>");
				$.each(response['headers'], function(k, v){
					if (typeof vv[v]!='undefined') {
						tableRow.append('<td>'+ vv[v] +'</td>')
					}else{
						tableRow.append('<td></td>');
					}
				});

				/* Append Edit Button*/
				var btn_edit 	= '<button class="btn btn-mini btn-success btn-action-edit" onclick="setUpdateItem('+ kk +')" data-id="'+ kk +'" data-rel="tooltip" data-placement="top" title="Edit"><i class="icon icon-edit"></i></button>';
				var btn_delete = '<button class="btn btn-mini btn-danger btn-action-delete" data-id="'+ kk +'" data-rel="tooltip" data-placement="top" title="Delete"><i class="icon icon-trash"></i></button>';

				tableRow.append('<td><div class="btn-group">'+ btn_edit + btn_delete +'</div></td>')

				formResponse.append(tableRow)
			});

			resetTable();
		}
	});
}
function clearTable(){	
	var ex = document.getElementById('contact-form-collection');
	if ( $.fn.DataTable.fnIsDataTable( ex ) ) {
		$("#contact-form-collection").dataTable().fnDestroy();
	}
}
function resetTable(){	
	var theader = [];
	$("#contact-form-collection").find("thead").find('tr>th').each(function(k, v){
		theader.push({});
		// theader.push({"sWidth": "20%", "bSortable": false});
	});

	theader[theader.length-1] = {"sWidth": "50px", "bSortable": false};

	if (theader.length > 0) {
		$("#contact-form-collection-container").show();
		$("#contact-form-collection-container-empty").hide();
		$("#contact-form-collection").dataTable({
		  //"bProcessing": true,
		  //"bServerSide": true,
		  //"sAjaxSource": CONFIG.get('URL') + "contact-forms/response-processor/",
		  "iDisplayLength": 25,
		  "aaSorting": [[0,'desc']],
		  "aoColumns": theader,
		  "fnDrawCallback": function(oSettings) {
		  }, "fnServerParams": function(aoData) {
		  	aoData.push({"name": "action", "value": "get"});
		  	aoData.push({"name": "form-id", "value": $(contact_form_id).val()});
		  }
		});
	}else{
		$("#contact-form-collection-container").hide();
		$("#contact-form-collection-container-empty").show();
	}

	$.each($("#contact-form-collection .btn-action-delete"), function(e){
		$(this).click(function(e){
			removeItem($(this).data('id'));
		});
	});

	$("[data-rel=tooltip]").tooltip();
}
function removeItem(selected_id){
	bootbox.confirm("Do you want to delete selected item?", function(result){
	  if (result) {
	    $.post(CONFIG.get('URL')+'contact-forms/response_processor/',{
	    	action 	: 'get-delete',
	    	id 			: selected_id,
	    },function(response) {
	    	if (response == '1') {
	    		notification("Data Form Data Collected", "Successfully deleted selected item.", "gritter-success");
	    		getContactFormCollection();
	    	}else{
	    		notification("Data Form Data Collected", "Error: " + response, "gritter-danger");
	    	}
	    });
	  }
	});
}
function setUpdateItem(selected_id){
	$("#modal-edit-item").modal('show');
	$("#modal-selected-id").val(selected_id);
		
	/* clear current container*/
	$("#modal-container-items").html("");

	$.post(CONFIG.get('URL')+'contact-forms/response_processor/',{
  	action 	: 'get-item',
  	id 			: selected_id,
  },function(response) {
  	$.each(response.values, function(k, v){
  		 $('#tmpl-items').tmpl({'key':k,'value':v,}).appendTo('#modal-container-items');
  	});
  });
}
function updateItem(){
	var selected_item = $("#modal-selected-id").val();
}