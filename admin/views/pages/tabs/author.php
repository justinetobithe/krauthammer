<?php
/*
Title: Author
Order: 4
ID: container-author
*/ 

?>
<div id="container-author" class="tab-pane" style="overflow-x: hidden; min-height: 350px;">
	<h4>Author</h4>
	<hr>

	<div class="well well-small">
		<select name="author" id="author-field">
		<option value="0">-Select Author-</option>
		<?php if (isset($authors) && count($authors)): ?>
		<?php foreach ($authors as $key => $value): ?>
			<option value="<?php echo $value->id ?>" <?php echo $value->current_user == 'Y' ? 'selected="selected"' : '' ?>><?php echo $value->username; ?></option>
		<?php endforeach ?>
		<?php endif ?>
		</select>
	</div>

	<div class="well well-small">
		<p><i>Front-end Function: <b>cms_get_author</b>()</i></p>
		<p>Usage: <br>-<b>cms_meta_data()</b> <br><b>returns:</b> [Author Name of the current post/page]</p>
	</div>
</div>

<!-- removed unused script template template-gallery-album-extra -->