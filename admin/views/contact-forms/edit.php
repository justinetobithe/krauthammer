<?php $form_code = $contact['form_code'];
if($form_code == ''){
$form_code = '<p>Your Name (required)<br />
[text* your-name] </p>

<p>Your Name (required)<br />
[text* your-name] </p>

<p>Your Email (required)<br />
[email* your-email] </p>

<p>Subject<br />
[text your-subject] </p>

<p>Your Message<br />
[textarea your-message] </p>

<p>[submit "Send"]</p>';
}

$mail_2_message_body = $contact['mail_2_message_body'];
if($mail_2_message_body =='')
	$mail_2_message_body = 'From: [your-name] <[your-email]>
Subject: [your-subject]

Message Body:
[your-message]

--
This e-mail was sent from a contact form on';
?>

<div class="main-content">
	<div class="page-content">
		<div class="page-header position-relative">
			<h1>
				Edit Contact Form
			</h1>
		</div><!--/.page-header-->
		<input type="hidden" id="contact_id" name="contact_id" value="<?php echo $contact['id']; ?>">
		<input type="hidden" id="action" name="action" value="edit">
		<div>
			<div class="row-fluid">
				<div class="span12">
					<!--PAGE CONTENT BEGINS-->
					<div id="result"></div>
					<div class="well well-small">
						<div class="text-right pull-right">
							<i class="icon-spinner icon-spin gray bigger-140 disappear loader"></i>&nbsp;
							<button class="btn btn-mini btn-success btn-add"><i class="icon-save"></i> Save</button>
							<a href="<?php echo URL . "contact-forms/responses/2/" ?>" class="btn btn-mini btn-primary"><i class="icon-list"></i> Responses</a>
						</div>
						<div>
							<input type="text" id="title" class="span9" style="margin-bottom: 0px;"  value="<?php echo $contact['name']; ?>"/>
						</div>
					</div>

					<div class="widget-box">
						<div class="widget-header widget-header-small header-color-blue">
							<h6>Form</h6>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="row-fluid">
									<div class="span7">
										<input type='hidden' id='contact_form_id' value='<?php //echo $maxid; ?>' />
										<textarea rows="18" id="form" class="span12 text-left"><?php echo $form_code; ?></textarea>
									</div>
									<div class="span5">
										<div class="position-relative">
											<a href="#" class="btn btn-mini btn-primary" data-toggle="dropdown">Generate Tag</a>
											<ul class="user-menu pull-left dropdown-menu dropdown-yellow dropdown-caret dropdown-closer" id="tag-type">
												<li><a href="javascript:void(0)">Text Field</a></li>
												<li><a href="javascript:void(0)">Email Field</a></li>
												<li><a href="javascript:void(0)">URL</a></li>
												<li><a href="javascript:void(0)">Number</a></li>
												<li><a href="javascript:void(0)">Date</a></li>
												<li><a href="javascript:void(0)">Text Area</a></li>
												<li><a href="javascript:void(0)">Drop-down menu</a></li>
												<li><a href="javascript:void(0)">Checkboxes</a></li>
												<li><a href="javascript:void(0)">Radio buttons</a></li>
												<li><a href="javascript:void(0)">Acceptance</a></li>
												<li><a href="javascript:void(0)">Quiz</a></li>
												<li><a href="javascript:void(0)">Captcha</a></li>
												<li><a href="javascript:void(0)" data-rel="tooltip" title="New Field: Google ReCaptcha">ReCaptcha <i class="pull-right icon-warning-sign red"></i></a></li>
												<li><a href="javascript:void(0)">File upload</a></li>
												<li><a href="javascript:void(0)">Submit button</a></li>
											</ul>
										</div>
										<div class="well">
											<div id="tab-generator-container">
												<p>Click <b>Generate Tag</b> button to display the shortcode generator of selected field.</p>
											</div>
											<div></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="widget-box">
						<div class="widget-header widget-header-small header-color-blue">
							<h6>Mail</h6>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="row-fluid">
									<div class="span6">
										<p>To: </p>
										<div><input type="text" id='mail_to' value="<?php echo $contact['mail_1_to']; ?>" class="span12"></div>
										<p>From: </p>
										<?php
										$mail_1_from = $contact['mail_1_from'];
										if($mail_1_from =='')
											$mail_1_from = '[your-name] <[your-email]>';
										?>
										<div><input type="text" id='mail_from' value="<?php echo $mail_1_from; ?>" class="span12"></div>
										<p>Subject: </p>
										<?php
										$mail_1_subject = $contact['mail_1_subject'];
										if($mail_1_subject =='')
											$mail_1_subject = '[your-subject]';
										?>
										<div><input type="text" id='mail_subject' value="<?php echo $mail_1_subject; ?>" class="span12"></div>
										<p>Additional Header: </p>
										<div><textarea class="span12" id='mail_additional_header' cols="30" rows="5"><?php echo $contact['mail_1_additional_headers'];?></textarea></div>
										<p>File Attachments: </p>
										<div><input type="text" id='mail_file_attachment' class="span12"></div>
										<div class="checkbox" style="position:relative;margin-left:-40px;"><label class="checkbox"><input type="checkbox" id="mail_html_content_type"  class="ace" <?php echo $contact['mail_1_use_html_content_type'] == 'Y' ? 'checked' : ''; ?>> <span class="lbl"></span> <small>Use HTML content type</small> </label></div>

									</div>
									<div class="span6">
										<p>Message body: </p>
										<div>
											<?php
											$mail_1_message_body = $contact['mail_1_message_body'];
											if($mail_1_message_body =='')
												$mail_1_message_body = 'From: [your-name] <[your-email]>
											Subject: [your-subject]

											Message Body:
											[your-message]

											--
											This e-mail was sent from a contact form on';
											?>
											<textarea cols="30" id='mail_message_body' rows="20" class="span12"><?php echo $mail_1_message_body;?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="widget-box">
						<div class="widget-header widget-header-small header-color-blue">
							<h6>Mail 2</h6>
							<div class="widget-toolbar">
								<a href="#" data-action="collapse">
									<i class="icon-chevron-up"></i>
								</a>
							</div>
						</div>

						<div class="widget-body">
							<div class="widget-main">
								<div class="row-fluid">
									<div class="span6">
										<div class="checkbox" style="position:relative;margin-left:-40px;"><label class="checkbox"><input type="checkbox" id="mail2_active"  class="ace" <?php echo $contact['mail_2_enabled'] == 'Y' ? 'checked' : ''; ?>> <span class="lbl"></span> <small>Use mail (2)</small> </label></div>
										<br />
										<div class='mail2_panel disappear' style="display: none;">
											<p>To: </p>
											<div><input type="text" id='mail2_to' value="<?php echo $contact['mail_2_to']; ?>" class="span12"></div>
											<p>From: </p>
											<?php
											$mail_2_from = $contact['mail_2_from'];
											if($mail_2_from =='')
												$mail_2_from = '[your-name] <[your-email]>';
											?>
											<div><input type="text" id='mail2_from' value="<?php echo $mail_2_from; ?>" class="span12"></div>
											<p>Subject: </p>
											<?php
											$mail_2_subject = $contact['mail_2_subject'];
											if($mail_2_subject =='')
												$mail_2_subject = '[your-subject]';
											?>
											<div><input type="text" id='mail2_subject' value="<?php echo $mail_2_subject; ?>" class="span12"></div>
											<p>Additional Header: </p>
											<div><textarea class="span12" id='mail2_additional_header' cols="30" rows="5"><?php echo $contact['mail_2_additional_headers'];?></textarea></div>
											<p>File Attachments: </p>
											<div><input type="text" id='mail2_file_attachment' class="span12"></div>
											<div class="checkbox" style="position:relative;margin-left:-40px;"><label class="checkbox"><input type="checkbox" id="mail2_html_content_type"  class="ace"  <?php echo $contact['mail_2_use_html_content_type'] == 'Y' ? 'checked' : ''; ?>> <span class="lbl"></span> <small>Use HTML content type</small> </label></div>
										</div>
									</div>
									<div class="span6">
										<br /><br />
										<div class='mail2_panel disappear' style="display: none;">

											<p>Message body: </p>
											<div>
												<textarea cols="30" id='mail2_message_body' rows="20" class="span12"><?php echo $mail_2_message_body; ?></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="widget-box collapsed">
						<div class="widget-header widget-header-small header-color-blue">
							<h6>Message</h6>
							<div class="widget-toolbar">
								<a href="#" data-action="collapse">
									<i class="icon-chevron-down"></i>
								</a>
							</div>
						</div>
						<div class="widget-body">
							<div class="widget-main messages">
								<div>
									<p># Sender's message was sent successfully</p>
									<div><input type="text" id="mail_sent_ok" value="Your message was sent successfully. Thanks." class="span12"></div>

									<p># Sender's message was failed to send</p>
									<div><input type="text" id="mail_sent_ng" value="Failed to send your message. Please try later or contact the administrator by another method." class="span12"></div>

									<p># Validation errors occurred</p>
									<div><input type="text" id="validation_error" value="Validation errors occurred. Please confirm the fields and submit it again." class="span12"></div>

									<p># Submission was referred to as spam</p>
									<div><input type="text" id="spam" value="Failed to send your message. Please try later or contact the administrator by another method." class="span12"></div>

									<p># There are terms that the sender must accept</p>
									<div><input type="text" id="accept_terms" value="Please accept the terms to proceed." class="span12"></div>

									<p># There is a field that the sender must fill in</p>
									<div><input type="text" id="invalid_required" value="Please fill the required field." class="span12"></div>

									<p># The code that sender entered does not match the CAPTCHA</p>
									<div><input type="text" id="captcha_not_match" value="Your entered code is incorrect." class="span12"></div>

									<p># Number format that the sender entered is invalid</p>
									<div><input type="text" id="invalid_number" value="Number format seems invalid." class="span12"></div>

									<p># Number is smaller than minimum limit</p>
									<div><input type="text" id="number_too_small" value="This number is too small." class="span12"></div>

									<p># Number is larger than maximum limit</p>
									<div><input type="text" id="number_too_large" value="This number is too large." class="span12"></div>

									<p># Email address that the sender entered is invalid</p>
									<div><input type="text" id="invalid_email" value="Email address seems invalid." class="span12"></div>

									<p># URL that the sender entered is invalid</p>
									<div><input type="text" id="invalid_url" value="URL seems invalid." class="span12"></div>

									<p># Telephone number that the sender entered is invalid</p>
									<div><input type="text" id="invalid_tel" value="Telephone number seems invalid." class="span12"></div>

									<p># Sender doesn't enter the correct answer to the quiz</p>
									<div><input type="text" id="quiz_answer_not_correct" value="Your answer is not correct." class="span12"></div>

									<p># Date format that the sender entered is invalid</p>
									<div><input type="text" id="invalid_date" value="Date format seems invalid." class="span12"></div>

									<p># Date is earlier than minimum limit</p>
									<div><input type="text" id="date_too_early" value="This date is too early." class="span12"></div>

									<p># Date is later than maximum limit</p>
									<div><input type="text" id="date_too_late" value="This date is too late." class="span12"></div>

									<p># Uploading a file fails for any reason</p>
									<div><input type="text" id="upload_failed" value="Failed to upload file." class="span12"></div>

									<p># Uploaded file is not allowed file type</p>
									<div><input type="text" id="upload_file_type_invalid" value="This file type is not allowed." class="span12"></div>

									<p># Uploaded file is too large</p>
									<div><input type="text" id="upload_file_too_large" value="This file is too large." class="span12"></div>

									<p># Uploading a file fails for PHP error</p>
									<div><input type="text" id="upload_failed_php_error" value="Failed to upload file. Error occurred." class="span12"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="widget-box collapsed">
						<div class="widget-header widget-header-small header-color-blue">
							<h6>Additional Settings</h6>
							<div class="widget-toolbar">
								<a href="#" data-action="collapse">
									<i class="icon-chevron-down"></i>
								</a>
							</div>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="row-fluid">
									<textarea class="span12" cols="30" rows="10" id="additional_settings"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="widget-box">
						<div class="widget-header widget-header-small header-color-blue">
							<h6>ReCatpcha</h6>
							<div class="widget-toolbar">
								<a href="#" data-action="collapse">
									<i class="icon-chevron-up"></i>
								</a>
							</div>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="form-horizontal">
									<div class="control-group">
										<div class="well well-small">
											<p style="margin: 0;">Make sure to add the <b>recaptcha shortcode</b> in your <b>"Contact Form"</b>.</p>
											<p style="margin: 0;">Don't forget to add <a href="<?php echo URL . "settings/?tab=contact-form" ?>" target="_blank">Google ReCaptcha Key and Secret</a></p>
										</div>
									</div>
									<div class="control-group">
                    <label class="control-label">Google ReCaptcha</label>
                    <div class="controls">
                      <label>
                        <input id="enable-recaptcha" name="enable-recaptcha" type="checkbox" class="ace ace-switch ace-switch-7" <?php echo $contact['enable_captcha'] == 'Y' ? 'checked="checked"' : ''; ?>>
                        <span class="lbl"></span>
                      </label>
	                  </div>
	                </div>
								</div>
							</div>
						</div>
					</div>

					<div class="well well-small">
						<div class="text-right">
							<i class="icon-spinner icon-spin gray bigger-140 disappear loader"></i>&nbsp;
							<button class="btn btn-mini btn-success btn-add"><i class="icon-save"></i> Save</button>
							<a href="<?php echo URL . "contact-forms/responses/2/" ?>" class="btn btn-mini btn-primary"><i class="icon-list"></i> Responses</a>
						</div>
					</div>

					<?php include __DIR__ . "/fragment-tags.php" ?>