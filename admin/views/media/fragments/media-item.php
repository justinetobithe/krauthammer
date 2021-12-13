<li class="gallery-item">
	<a href="javascript:void(0)" class="gallery-image-container" title="${filename}">
		<img alt="150x150" src="<?php echo FRONTEND_URL ?>/files/defaults/icon-image.png" class="gallery-item-image" data-title="${filename}" />
		<div class="text">
			<div class="inner"><small>${filename}</small></div>
		</div>
	</a>
	<a href="javascript:void(0)" data-rel="colorbox" class="gallery-image-container-hidden hide" title="${filename}">
		<img alt="150x150" src="<?php echo FRONTEND_URL ?>/files/defaults/icon-image.png" class="gallery-item-image" data-title="${filename}" />
	</a>

	<div class="tools tools-top">
		<a href="javascript:void(0)" class="gallery-button-url" data-value="">
			<i class="icon-link"></i>
		</a>
		{{if type=='image'}}
		<a href="javascript:void(0)" class="gallery-button-zoom">
			<i class="icon-zoom-in"></i>
		</a>
		{{/if}}
		<a href="#">
			<i class="icon-remove red"></i>
		</a>
	</div>
</li>