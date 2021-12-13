var patch_queue = [];
var cancelled = false;

$(function(){
	$("#patch-modal").modal({
		backdrop : 'static',
		keyboard : false,
		show : false,
	});
	$("#patch-progress-cancel").click(function(e){
	  cancelled = true;
	});
	get_update();
	get_prev_update();
});
function reload_patch(){
	get_update();
	get_prev_update();
}
function get_update(){
	$("#loading-patch").show();
	$("#patch-action").hide();
	$("#patch-container").hide();

	jQuery.post(CONFIG.get('URL')+'patcher/updates/',{}, function(response,status){
		var updates = response['updates'];

		/* Set Sidebar Update Count */
		if (updates.length > 0) {
			$(".sidebar-item-cms-patch-notification").show();
			$(".sidebar-item-cms-patch-notification").html(updates.length);
		}else{
			$(".sidebar-item-cms-patch-notification").html('');
			$(".sidebar-item-cms-patch-notification").hide();
		}

		if (updates.length) {
			$("#alert-patch").hide();
			$("#patch-action").find('>span').html('Updates: <span class="badge badge-important">' + updates.length + '</span> ');
			$("#patch-action").show();
		}else{
			$("#alert-patch").show();
			$("#patch-action").find('>span').html('');
			$("#patch-action").hide();
		}

		$("#patch-container").html('');
		patch_queue = [];
		$.each(updates, function(k, v){
			patch_queue.push(v);

			var patch_group = $('#tmpl-patch-container').tmpl(v).appendTo('#patch-container');

			$.each(v['meta'], function(kk, vv){
				if (vv['description'] == '') {return;}
				var patch_item = $('#tmpl-patch-list-container').tmpl(vv).appendTo( patch_group.find('.update-list') );
			});
		});
		$("#patch-container").show();
		$("#loading-patch").hide();
	});
}
function get_prev_update(){
	$("#loading-prev-patch").show();
	jQuery.post(CONFIG.get('URL')+'patcher/prev_updates/',{}, function(response,status){
		var updates = response['updates'];

		if (updates.length) {
			$("#alert-prev-patch").hide();
		}else{
			$("#alert-prev-patch").show();
		}

		$("#patch-prev-container").html('');
		$.each(updates, function(k, v){
			var patch_group = $('#tmpl-prev-patch-container').tmpl(v).appendTo('#patch-prev-container');
			var ul_list_container = patch_group.find('.update-list');

			$.each(v['meta'], function(kk, vv){
				if (vv['description'] == '') {return;}
				var patch_item = $('#tmpl-patch-list-container').tmpl(vv).appendTo( ul_list_container );
			});

			if (ul_list_container.find('>li').length > 1) {
				ul_list_container.before('<a href="javascript:void(0)" class="toggle-btn">View Detail</a>');
				var toggle_btn = ul_list_container.siblings('.toggle-btn');
				
				toggle_btn.click(function(){
					ul_list_container.slideToggle('fast');
				});
				ul_list_container.hide();
			}

		});
		$("#loading-prev-patch").hide();
	});
}

function install_patch(){
	bootbox.confirm("Do you want to continue installing the updates?", function(result){
		if (result) {

			if (typeof current_patch_notification != "undefined" && current_patch_notification != null) {
				$.gritter.remove(current_patch_notification, { 
				    fade: true, // optional
				    speed: 'fast' // optional
				  });
			}

			$("#patch-modal").modal('show');

			$("#patch-progress-container").html('');
			start_patching();
		}
	});
}

function start_patching(){
	cancelled = false;
	stop_patcher();
	process_patch_queue();
}

function process_patch_queue(){
	if (patch_queue.length > 0) {
		$("#patch-progress-close").hide();
		$("#patch-progress-cancel").show();
		$("#patch-modal-detail").html("Remaining Updates: " + patch_queue.length);

		var current_patch = patch_queue.shift();
		var v = "Paching: " + current_patch['version'];
		var patch_item = $('#tmpl-patch-item').tmpl({'patch_detail' : v}).appendTo('#patch-progress-container');

		jQuery.post(CONFIG.get('URL')+'patcher/install/',{
			current_patch : typeof current_patch['id'] != 'undefined' ? current_patch['id'] : 0,
		}, function(response,status){
			$("#patch-modal-detail").html("Remaining Updates: " + patch_queue.length);
			var updates = JSON.parse(response);

			if (updates['status']) {
				patch_item.html(patch_item.html() + ' (<b>Success</b> <i class="icon icon-check"></i>)');
				patch_item.addClass('text-success');
			}else{
				patch_item.html(patch_item.html() + ' (<b>Error</b> <i class="icon icon-ban-circle"></i>)');
				patch_item.addClass('text-error');
			}

			if (!cancelled) {
				process_patch_queue();
			}else{
				$("#patch-progress-cancel").hide();
				$("#patch-progress-close").show();
				notify_patch();
				get_prev_update();
			}
		});
		return;

		/*jQuery.post(CONFIG.get('URL')+'patcher/install/',{
			current_patch : current_patch,
		}, function(response,status){
			var updates = response['installed'];

			if (updates > 0) {
				notification("Updates", "Successfully installed "+ updates +" items.", 'gritter-success');
			}else{
				notification("Updates", "Error occured during installation.", 'gritter-error');
			}

			get_update();
			get_prev_update();
		});*/
	}else{
		$("#patch-progress-cancel").hide();
		$("#patch-progress-close").show();
		notify_patch();
		get_update();
		get_prev_update();
	}
}