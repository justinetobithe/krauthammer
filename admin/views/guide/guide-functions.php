<div class="page-header">
	<h1>
		User Guide - CMS Functions
	</h1>
</div><!-- /.page-header -->

<div class="row-fluid" style="z-index: inherit;">
	<div class="span12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="widget-box">
			<div class="row-fluid">
				<div class="span12">
					<p>These are the following function for front-end development. </p>
				</div>
			</div>

			<hr>

			<?php foreach ($functions_list as $key => $value): ?>
			<?php
				if (strpos($value, "@var")) {
					continue;
				}

				$temp = explode("\n", trim(ltrim($value, "*")));

				$temp = array_map(function($s){
					$s = strpos($s, "<?php") || strpos($s, "?>") ? "[php]" : trim($s, "* ");
					$s = strpos($s, "inline example") ? "\n".$s : preg_replace("/(.*?):/", "@function <b>$1 ( ) :</b>", $s);
					return $s;
				}, $temp);

				$str = implode("\n", $temp);


			?>
				<div class="row-fluid">
					<div class="span12 well well-small">
						<p class=""><?php echo nl2br($str); ?></p>
					</div>
				</div>
			<?php endforeach ?>

		</div>
	</div>
</div><!-- /.col -->