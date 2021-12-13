<script id="tag-text" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Text field</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" id="name" class="span12">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" id="id" class="span12">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" id="class" class="span12">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>size (optional)</small></label>
			<input type="text" id="size" class="span12">
		</div>
		<div class="span6">
			<label><small>maxlength (optional)</small></label>
			<input type="text" id="maxlength" class="span12">
		</div>
	</div>
	<br>
	<p>Akismet (optional)</p>
	<div class="checkbox">
		<input type="checkbox" name="akismet:author" id="author">
		<span>${"This field requires author's name"}</span>
		<br>
		<input type="checkbox" name="akismet:author_url" id="author_url">
		<span>${"This field requires author's URL"}</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Default value (optional)</small></label>
			<input type="text" class="span12" id="watermark_value">
		</div>
		<div class="span6">
			<label><small><br></small></label>
			<div class="checkbox"><input type="checkbox" id="watermark"><small> Use this text as watermark?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-url" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>URL</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" id="name" class="span12">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" id="id" class="span12">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" id="class" class="span12">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>size (optional)</small></label>
			<input type="text" id="size" class="span12">
		</div>
		<div class="span6">
			<label><small>maxlength (optional)</small></label>
			<input type="text" id="maxlength" class="span12">
		</div>
	</div>
	<br>
	<p>Akismet (optional)</p>
	<div class="checkbox">
		<input type="checkbox" name="akismet:author" id="author">
		<span>${"This field requires author's URL"}</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Default value (optional)</small></label>
			<input type="text" class="span12" id="watermark_value">
		</div>
		<div class="span6">
			<label><small><br></small></label>
			<div class="checkbox"><input type="checkbox" id="watermark"><small> Use this text as watermark?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-number" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Number</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" id="name" class="span12">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" id="id" class="span12">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" id="class" class="span12">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>min (optional)</small></label>
			<input type="text" id="min" class="span12">
		</div>
		<div class="span6">
			<label><small>max (optional)</small></label>
			<input type="text" id="max" class="span12">
		</div>
	</div>

	<div class="row-fluid">
		<div class="span6">
			<label><small>step (optional)</small></label>
			<input type="text" id="step" class="span12">
		</div>
	</div>
	<br>
	<p>Akismet (optional)</p>
	<div class="checkbox">
		<input type="checkbox" name="akismet:author" id="author">
		<span>${"This field requires author's URL"}</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Default value (optional)</small></label>
			<input type="text" class="span12" id="watermark_value">
		</div>
		<div class="span6">
			<label><small><br></small></label>
			<div class="checkbox"><input type="checkbox" id="watermark"><small> Use this text as watermark?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-date" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Date</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" id="name" class="span12">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" id="id" class="span12">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" id="class" class="span12">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>min (optional)</small></label>
			<input type="date" id="minDate" class="span12">
		</div>
		<div class="span6">
			<label><small>max (optional)</small></label>
			<input type="date" id="maxDate" class="span12">
		</div>
	</div>

	<div class="row-fluid">
		<div class="span6">
			<label><small>step (optional)</small></label>
			<input type="text" id="step" class="span12">
		</div>
	</div>
	<br>
	<p>Akismet (optional)</p>
	<div class="checkbox">
		<input type="checkbox" name="akismet:author" id="author">
		<span>${"This field requires author's URL"}</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Default value (optional)</small></label>
			<input type="text" class="span12" id="watermark_value">
		</div>
		<div class="span6">
			<label><small><br></small></label>
			<div class="checkbox"><input type="checkbox" id="watermark"><small> Use this text as watermark?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-email" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Email Field</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>size (optional)</small></label>
			<input type="text" class="span12" id="size">
		</div>
		<div class="span6">
			<label><small>maxlength (optional)</small></label>
			<input type="text" class="span12" id="maxlength">
		</div>
	</div>
	<br>
	<p>Akismet (optional)</p>
	<label class="checkbox">
		<input type="checkbox" id="author">
		<span><small>${"This field requires author's email address"}</small></span>
	</label>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Default value (optional)</small></label>
			<input type="text" class="span12" id="watermark_value">
		</div>
		<div class="span6">
			<label><small><br></small></label>
			<div class="checkbox"><input type="checkbox" id="watermark"><small> Use this text as watermark?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-textarea" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Text area</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>cols (optional)</small></label>
			<input type="text" class="span12" id="cols">
		</div>
		<div class="span6">
			<label><small>rows (optional)</small></label>
			<input type="text" class="span12" id="rows">
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Default value (optional)</small></label>
			<input type="text" class="span12" id="watermark_value">
		</div>
		<div class="span6">
			<label><small><br></small></label>
			<div class="checkbox"><input type="checkbox" id="watermark"><small> Use this text as watermark?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-dropdown" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Drop-down menu</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Choice</small></label>
			<textarea class="span12" cols="30" rows="5" id="option"></textarea>
			<p><small>* One choice per line.</small></p>
		</div>
		<div class="span6">
			<br>
			<div class="checkbox"><input type="checkbox" id="multiple"><small> Allow multiple selections?</small></div>
			<div class="checkbox"><input type="checkbox" id="blank"><small> Insert a blank item as the first option?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-checkbox" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Checkboxes</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Choice</small></label>
			<textarea class="span12" cols="30" rows="5" id="option"></textarea>
			<p><small>* One choice per line.</small></p>
		</div>
		<div class="span6">
			<br>
			<div class="checkbox"><input type="checkbox" id="first"><small> Put a label first, a checkbox last?</small></div>
			<div class="checkbox"><input type="checkbox" id="element"><small> Wrap each item with &lt;label&gt; tag?</small></div>
			<div class="checkbox"><input type="checkbox" id="exclusive"><small> Make checkboxes exclusive?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-radio" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Radio buttons</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Choice</small></label>
			<textarea class="span12" cols="30" rows="5" id="option"></textarea>
			<p><small>* One choice per line.</small></p>
		</div>
		<div class="span6">
			<br>
			<div class="checkbox"><input type="checkbox" id="first"><small> Put a label first, a checkbox last?</small></div>
			<div class="checkbox"><input type="checkbox" id="element"><small> Wrap each item with &lt;label&gt; tag?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-acceptance" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Acceptance</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span12">
			<div class="checkbox"><input type="checkbox" id="default"><small> Make this checkbox checked by default?</small></div>
			<div class="checkbox"><input type="checkbox" id="inverse"><small> Make this checkbox work inversely?</small></div>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
</script>

<script id="tag-quiz" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Quiz</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>size (optional)</small></label>
			<input type="text" class="span12" id="size">
		</div>
		<div class="span6">
			<label><small>maxlength (optional)</small></label>
			<input type="text" class="span12" id="maxlength">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Quizzes</small></label>
			<textarea class="span12" cols="30" rows="5" id="option"></textarea>
			<p><small>* quiz|answer (e.g. 1+1=?|2)</small></p>
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
</script>

<script id="tag-captcha" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>CAPTCHA</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<p><small class="red"><b>Note:</b> To use CAPTCHA, you need Really Simple CAPTCHA plugin installed.</small></p>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<h5><small>Image settings</small></h5>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Foreground color (optional)</small></label>
			<input type="text" class="span12" id="forecolor">
		</div>
		<div class="span6">
			<label><small>Background color (optional)</small></label>
			<input type="text" class="span12" id="backcolor">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<label><small>Image size (optional)</small></label>
			<div class="checkbox">
				<input type="checkbox" id="small">
				<span>Small</span>
			</div>
			<div class="checkbox">
				<input type="checkbox" id="medium">
				<span>Medium</span>
			</div>
			<div class="checkbox">
				<input type="checkbox" id="large">
				<span>Large</span>
			</div>
		</div>
	</div>
	<br><br>
	<h5><small>Input field settings</small></h5>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id-2">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class-2">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>size (optional)</small></label>
			<input type="text" class="span12" id="size">
		</div>
		<div class="span6">
			<label><small>maxlength (optional)</small></label>
			<input type="text" class="span12" id="maxlength">
		</div>
	</div>
	<br>

	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p>1) For image</p>
		<p class="alert"><small id="text-code-3"></small></p>
		<p>2) For input field</p>
		<p class="alert"><small id="text-code-4"></small></p>
	</div>
</script>

<script id="tag-recaptcha" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>CAPTCHA</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<p class="alert"><small class="blue"><b>Note:</b><br> include: &lt;script src='https://www.google.com/recaptcha/api.js'&gt;&lt;/script&gt;</small></p>
	<br>
	<div class="row-fluid">
		<div class="span12">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>

	<div>
		<p><em>Copy this code and paste it into the form left.</em></p>
		<p><em>Make sure <b>Google ReCaptcha</b> option is <b>enabled</b> at the bottom part of the page.</em></p>
		<p>1) For image</p>
		<p class="alert"><small id="text-code"></small></p>
		<p>2) For input field</p>
		<p class="alert alert-success"><small id="text-code-2"></small></p>
	</div>
</script>

<script id="tag-file" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>File upload</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<div class="checkbox">
		<input type="checkbox" id="required">
		<span>Required field?</span>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Name</small></label>
			<input type="text" class="span12" id="name">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>File size limit (bytes) (optional)</small></label>
			<input type="text" class="span12" id="limit">
		</div>
		<div class="span6">
			<label><small>Acceptable file types (optional)</small></label>
			<input type="text" class="span12" id="file_type">
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>

	<div>
		<p class="text-right">And, put this code into the Mail fields below.</p>
		<p class="text-right alert alert-success"><b><small id="text-code-2"></small></b></p>
	</div>
</script>

<script id="tag-submit" type="text/x-jquery-tmpl">  
	<p class="field-title"><b>Submit button</b> <a href="javascript:void(0)" class="close-tag-generator close"><small>x</small></a></p>
	<br>
	<div class="row-fluid">
		<div class="span6">
			<label><small>id (optional)</small></label>
			<input type="text" class="span12" id="id">
		</div>
		<div class="span6">
			<label><small>class (optional)</small></label>
			<input type="text" class="span12" id="class">
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<label><small>Label (option)</small></label>
			<input type="text" class="span12" id="label">
		</div>
	</div>
	<br>
	<div>
		<p>Copy this code and paste it into the form left.</p>
		<p class="alert"><small id="text-code"></small></p>
	</div>
</script>