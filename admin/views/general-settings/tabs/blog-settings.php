<div id="blog-setting" class="tab-pane">
	<div class="widget-box">
		<div class="widget-header header-color-blue">
			<h4 class="lighter">Blog Settings</h4>

			<div class="widget-toolbar no-border">
				<button class="btn btn-mini btn-success" id="btn-save-blog-setting">
					<i class="icon-save"></i>
					Save
				</button>
			</div>
		</div>
		<div class="widget-body">
			<div class="widget-main">
				<div class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="blog-post-count"><small>Blog pages show at most</small></label>
						<div class="controls">
							<div class="ace-spinner" id="blog-post-count-container">
								<div class="input-append">
									<input type="text" class="input-mini spinner-input" id="blog-post-count" style="width: 30px;" value="<?php echo isset($blog_settings['blog_post_limit']) ? $blog_settings['blog_post_limit'] : "0"; ?>">
									<div class="spinner-buttons btn-group btn-group-vertical">
										<button type="button" class="btn spinner-up btn-mini btn-info">
											<i class="icon-chevron-up"></i>
										</button>
										<button type="button" class="btn spinner-down btn-mini btn-info">
											<i class="icon-chevron-down"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	$comments_data = array(
		"comments-allow-on-article"=>get_system_option("comments-allow-on-article") == 'Y' ? 'checked="checked"' : '',
		"comments-article-comment-auto-close"=>get_system_option("comments-article-comment-auto-close") == 'Y' ? 'checked="checked"' : '',
		"comments-article-comment-days-old"=>get_system_option("comments-article-comment-days-old"),
		"comments-author-previously-approved"=>get_system_option("comments-author-previously-approved") == 'Y' ? 'checked="checked"' : '',
		"comments-email-me-on-comment"=>get_system_option("comments-email-me-on-comment") == 'Y' ? 'checked="checked"' : '',
		"comments-email-me-on-moderate"=>get_system_option("comments-email-me-on-moderate") == 'Y' ? 'checked="checked"' : '',
		"comments-enable-hold"=>get_system_option("comments-enable-hold") == 'Y' ? 'checked="checked"' : '',
		"comments-enable-nesting"=>get_system_option("comments-enable-nesting") == 'Y' ? 'checked="checked"' : '',
		"comments-hold-count-trigger"=>get_system_option("comments-hold-count-trigger"),
		"comments-list-blacklisted-words"=>get_system_option("comments-list-blacklisted-words"),
		"comments-list-moderated-words"=>get_system_option("comments-list-moderated-words"),
		"comments-manual-approve"=>get_system_option("comments-manual-approve") == 'Y' ? 'checked="checked"' : '',
		"comments-nesting-level"=>get_system_option("comments-nesting-level"),
		"comments-require-email-name"=>get_system_option("comments-require-email-name") == 'Y' ? 'checked="checked"' : '',
		"comments-required-registration"=>get_system_option("comments-required-registration") == 'Y' ? 'checked="checked"' : '',
		); ?>
		
		<div class="widget-box">
			<div class="widget-header header-color-blue">
				<h4 class="lighter">Comment Settings</h4>
				<div class="widget-toolbar no-border">
					<button class="btn btn-mini btn-success" id="btn-save-comment-setting">
						<i class="icon-save"></i>
						Save
					</button>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-main">
					<div class="setting-panel">
						<h4>Default Article Settings</h4>
						<hr>
						<div class="row-fluid">
							<div class="span11 offset1">
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-allow-on-article" <?php echo $comments_data['comments-allow-on-article'] ?>>
									<span class="lbl"> Allow people to post comments on new articles </span>
								</label>
								<p><i>(These settings may be overridden for individual articles.)</i></p>
							</div>
						</div>
					</div>
					<div class="setting-panel">
						<h4>Other comment settings</h4>
						<hr>
						<div class="row-fluid">
							<div class="span11 offset1">
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-require-email-name" <?php echo $comments_data['comments-require-email-name'] ?>>
									<span class="lbl"> Comment author must fill out name and email</span>
								</label>
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-required-registration" <?php echo $comments_data['comments-required-registration'] ?>>
									<span class="lbl"> Users must be registered and logged in to comment </span>
								</label>
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-article-comment-auto-close" <?php echo $comments_data['comments-article-comment-auto-close'] ?>>
									<span class="lbl"> Automatically close comments on articles older than </span>
									<input type="text" id="comments-article-comment-days-old" class="input input-small" style="width: 50px;" placeholder="DD" value="<?php echo $comments_data['comments-article-comment-days-old'] ?>">
									<span class="lbl"> days</span>
								</label>
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-enable-nesting" <?php echo $comments_data['comments-enable-nesting'] ?>>
									<span class="lbl"> Enable threaded (nested) comments </span>
									<select name="comment-close-comment input-mini" style="width: 50px; margin-top: 10px;" id="comments-nesting-level">
										<option value="1" <?php echo $comments_data['comments-require-email-name']==1 ? 'selected' : '' ?>>1</option>
										<option value="2" <?php echo $comments_data['comments-require-email-name']==2 ? 'selected' : '' ?>>2</option>
										<option value="3" <?php echo $comments_data['comments-require-email-name']==3 ? 'selected' : '' ?>>3</option>
										<option value="4" <?php echo $comments_data['comments-require-email-name']==4 ? 'selected' : '' ?>>4</option>
										<option value="5" <?php echo $comments_data['comments-require-email-name']==5 ? 'selected' : '' ?>>5</option>
									</select>
									<span class="lbl"> levels deep </span>
								</label>
							</div>
						</div>
					</div>
					<div class="setting-panel">
						<h4>Email me whenever	</h4>
						<hr>
						<div class="row-fluid">
							<div class="span11 offset1">
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-email-me-on-comment" <?php echo $comments_data['comments-email-me-on-comment'] ?>>
									<span class="lbl"> Anyone posts a comment</span>
								</label>
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-email-me-on-moderate" <?php echo $comments_data['comments-email-me-on-moderate'] ?>>
									<span class="lbl"> A comment is held for moderation </span>
								</label>
							</div>
						</div>
					</div>
					<div class="setting-panel">
						<h4>Before a comment appears</h4>
						<hr>
						<div class="row-fluid">
							<div class="span11 offset1">
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-manual-approve" <?php echo $comments_data['comments-manual-approve'] ?>>
									<span class="lbl">  Comment must be manually approved  </span>
								</label>
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-author-previously-approved" <?php echo $comments_data['comments-author-previously-approved'] ?>>
									<span class="lbl"> Comment author must have a previously approved comment</span>
								</label>
							</div>
						</div>
					</div>
					<div class="setting-panel">
						<h4>Comment Moderation</h4>
						<hr>
						<div class="row-fluid">
							<div class="span11 offset1">
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace" id="comments-enable-hold" <?php echo $comments_data['comments-enable-hold'] ?>>
									<span class="lbl">  Hold a comment in the queue if it contains </span>
									<input type="text" class="input input-small" style="width: 50px; margin-top: 10px;" id="comments-hold-count-trigger" value="<?php echo $comments_data['comments-hold-count-trigger'] ?>">
									<span clss="lbl">or more links. (A common characteristic of comment spam is a large number of hyperlinks.)</span>
								</label>
								<br>
								<label>
									<span class="lbl"> When a comment contains any of these words in its content, name, URL, email, or IP, it will be held in the <a href="<?php echo URL . "comments/moderation/" ?>" target="_blank">Comment Moderation</a>.</span>
									<textarea cols="100" rows="5" class="input span12" id="comments-list-moderated-words"><?php echo $comments_data['comments-list-moderated-words'] ?></textarea>
								</label>
								<br>
								<label>
									<span class="lbl"> When a comment contains any of these words in its content, name, URL, email, or IP, it will be put in the trash. One word or IP per line.</span>
									<textarea cols="100" rows="5" class="input span12" id="comments-list-blacklisted-words"><?php echo $comments_data['comments-list-blacklisted-words'] ?></textarea>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>