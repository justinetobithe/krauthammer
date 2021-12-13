<div id="contact-form-setting" class="tab-pane">
  <div class="widget-box">
    <div class="widget-header header-color-blue">
      <h4 class="lighter">Google ReCaptcha Keys</h4>
      <div class="widget-toolbar no-border">
        <button class="btn btn-mini btn-success" id="btn-save-contact-form-setting">
          <i class="icon-save"></i>
          Save
        </button>
      </div>
    </div>
    <div class="widget-body">
      <div class="widget-main">
        <?php 
        $google_recaptcha_key     = get_system_option('GOOGLE_RECAPTCHA_KEY'); 
        $google_recaptcha_secret  = get_system_option('GOOGLE_RECAPTCHA_SECRET'); 
        ?>
        <div class="form-horizontal">
          <div class="control-group">
            <label class="control-label" for="blog-post-count"><small>RECAPTCHA_KEY</small></label>
            <div class="controls">
              <input type="text" id="contact-form-key" class="input input-xxlarge" value="<?php echo $google_recaptcha_key ?>">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="blog-post-count"><small>RECAPTCHA_SECRET</small></label>
            <div class="controls">
              <input type="text" id="contact-form-secret" class="input input-xxlarge" value="<?php echo $google_recaptcha_secret ?>">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>