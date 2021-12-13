$(function(){
	notify_patch();

	$(".sidebar-item-cms-patch-notification").tooltip();
});


var _patch_timer = null;
function patcher_timeout() {
	_patch_timer = setTimeout(function () {
		notify_patch();
	}, (1000 * 60)); //Do a fetching every 1 hour.
}
function stop_patcher() {
	clearInterval(_patch_timer);
}

var current_patch_notification = null;
function notify_patch(){
	jQuery.post(CONFIG.get('URL')+'patcher/updates/',{}, function(response,status){
		var updates = response['updates'];
		var update_link = '<a href="'+ CONFIG.get('URL')+'patcher/' +'">View Updates</a>';

		if (updates.length) {
			$(".sidebar-item-cms-patch-notification").html(updates.length);
			if (updates.length > 0) {
				$(".sidebar-item-cms-patch-notification").show();
			}else{
				$(".sidebar-item-cms-patch-notification").hide();
			}

			if (typeof get_update == 'function') {
				get_update();
			}
		}

		patcher_timeout();
	});
}