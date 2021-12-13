/*
Script for Media Module
*/

var upload_queue = [];
var upload_setting = {};
var current_notification = []

$(document).ready(function(){
  $("body").on("dragenter",function(e){
    $("#file-drop-zone").stop().fadeIn(300);
  });
  $("#file-drop-zone").on("dragleave", function(e){
    $("#file-drop-zone").stop().fadeOut(300);
  }).on("drop", function(e){
    e.stopPropagation()
    e.preventDefault()
    $("#file-drop-zone").stop().fadeOut(300);
    var f = e.originalEvent.dataTransfer.files;

    $("#file-viewer").modal('hide')
    $("#cboxClose").trigger('click')
    process_upload(f)
  });

  $(window).on("dragover drop", function(e){
    e = e || event;
    e.preventDefault();
  });

  $("#file-type").chosen({
    width : "100px",
  });
  $("#file-upload-modal").modal({
    backdrop  : "static",
    keyboard  : false,
    show      : false,
  }).on('hidden', function(e){
    $.each($("#file-upload-modal").find('.upload-container .process-done'), function(k, v){
      v.remove();
    });
  });
  $("#file-url").modal({
    backdrop  : "static",
    keyboard  : false,
    show      : false,
  }).on('shown', function(e){

  }).on('hidden', function(e){
    $(this).find(".file-url").val('');
  });
  $("#file-url-copy").click(function(e){
    $("#file-url").find('.file-url').get(0).select();
    document.execCommand("Copy");
  });

  $("#file-viewer").modal({
    backdrop  : "static",
    keyboard  : false,
    show      : false,
  }).on('shown', function(e){
    if ($("#file-viewer").find(".file-container video").length>0) {
      $("#file-viewer").find(".file-container video").get(0).play();
    }
    if ($("#file-viewer").find(".file-container audio").length>0) {
      $("#file-viewer").find(".file-container audio").get(0).play();
    }
  }).on('hidden', function(e){
    if ($("#file-viewer").find(".file-container video").length>0) {
      $("#file-viewer").find(".file-container video").get(0).pause();
    }
    if ($("#file-viewer").find(".file-container audio").length>0) {
      $("#file-viewer").find(".file-container audio").get(0).pause();
    }
    $("#file-viewer").find(".file-container").html("");
  });

  $("#file-filter-btn").click(function(e){
    get_files();
  });
  $("#file-type").change(function(e){
    get_files();
  });

});



function process_upload(f){
  if (f.length > 0) {
    $("#file-upload-modal").modal('show');
    var upload_container = $("#file-upload-modal").find('.upload-container');
    
    $.each(f, function(k, file){
      var file_info = {
        name : file['name'],
        size : cms_function.fn.bytesToSize(file['size']),
        type : file['type'].split('/')[0],
      }
      /* Adding Item to Upload Queue List */
      var item = null;
      item = $('#media-upload-item-template').tmpl(file_info).appendTo(upload_container);

      upload_queue.push({
        file    : file,
        data    : file_info,
        element : item,
      });

      /* Set default image thumbnail */
      add_default_thumbnail(item.find('img'), file['type'].split('/')[0]);
      /* Creating Blob for image */
      var img = new Image()
      img.src = window.URL.createObjectURL(file);
      img.onload = function() {
        item.find('img').attr('src', this.src);
        window.URL.revokeObjectURL(this.src);
      }
    });
    $("#file-upload-modal").find('.modal-footer').hide();
    upload_photo();
  }else{
    cmsmedia_notification("Dragged Files...", "No file to upload", "gritter-warning");
  }
}
function add_default_thumbnail(image, type){
  if (type=='image') {
    image.attr('src', CONFIG.get('FRONTEND_URL')+'/files/defaults/icon-image.png');
  }else if(type=='video'){
    image.attr('src', CONFIG.get('FRONTEND_URL')+'/files/defaults/icon-video.png');
  }else if(type=='audio'){
    image.attr('src', CONFIG.get('FRONTEND_URL')+'/files/defaults/icon-audio.png');
  }else{
    image.attr('src', CONFIG.get('FRONTEND_URL')+'/files/defaults/icon-file.png');
  }
}
function upload_photo(){
  if (upload_queue.length > 0) {
    var file_limit = upload_setting['actual_max_upload_size'];
    var file = upload_queue.shift();
    var fd = new FormData();    
    fd.append( 'file', file.file );

    if (file.file.size <= file_limit) {
      file.element.find('.file-size').addClass('text-success');
      $.ajax({
        url: CONFIG.get('URL')+'media/upload/',
        data : fd,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function() {
          file.element.find('.progress').addClass('progress-success').removeClass('progress-primary');
          file.element.find('.action').html('<b class="pull-right text-success">Successfully Uploaded</b>').removeClass("action");
          file.element.addClass('process-done');
          upload_photo();
        },
        xhr: function () {
          var xhr = new window.XMLHttpRequest();

          xhr.upload.addEventListener("progress", function (evt) {
            if (evt.lengthComputable) {
              var percentComplete = evt.loaded / evt.total;
              percentComplete = parseInt(percentComplete * 100);
              console.log(percentComplete);
              file.element.find('.progress .bar').css('width', percentComplete + "%")
            /*progressWrap.attr('data-percent', percentComplete)
            progressBar.css('width', percentComplete + '%')*/
          }
        }, false);

          return xhr;
        },
        error: function() { },
        progress: function(e) {
          alert(e);
          console.log(e);
          /*make sure we can compute the length*/
          if(e.lengthComputable) {
            /*calculate the percentage loaded*/
            var pct = (e.loaded / e.total) * 100;
            /*log percentage loaded*/
            console.log(pct);
          }
          /*this usually happens when Content-Length isn't set*/
          else {
            console.warn('Content Length not reported!');
          }
        },
      });
    }else{
      file.element.find('.progress').hide();
      file.element.find('.file-size').append(" (exceeded upload limit <b>"+ cms_function.fn.bytesToSize(upload_setting['actual_max_upload_size']) +"</b>) ").addClass('text-error');
      upload_photo();
    }
  }else{
    get_files();
    $("#file-upload-modal").find('.modal-footer').show();
    // cmsmedia_notification("Upload", "Error during upload", "gritter-error");
  }
}
function get_upload_settings(){
  $.post(CONFIG.get('URL')+'media/get_system_setting/',{},function(response) {
    if (cms_function.isJSON(response)) {
      var res = JSON.parse(response);

      upload_setting = res;

      console.log(upload_setting);
    }
  });
}
function get_files(){
  $("#media-gallery").html(""); /* Clearing Media Gallery */
  $("#drop-guide").remove(); /* Clearing Media Gallery */
  $.post(CONFIG.get('URL')+'media/get_files/',{ type : $("#file-type").val() },function(response) {
    if (cms_function.isJSON(response)) {
      var res = JSON.parse(response);

      if (res.length > 0) {
        $.each(res, function(k, v){
          var gallery_item = $('#media-item-template').tmpl({
            filename  : v.filename,
            siteurl   : CONFIG.get('FRONTEND_URL'),
            type      : v.type,
          }).appendTo($("#media-gallery"));

          /* Set default image thumbnail */
          add_default_thumbnail(gallery_item.find('.gallery-item-image'), v.type);

          if (v.type == 'image') {
            /* Creating Blob for image */
            gallery_item.find('.gallery-image-container-hidden').attr('href', v.url);
            var img = new Image()
            img.src = v.url;
            img.onload = function() {
              gallery_item.find('.gallery-item-image').attr('src', this.src);
              gallery_item.find('.gallery-image-container-hidden').attr('href', this.src);
            }

            /* Initializing color box */
            initialize_color_box(gallery_item.find('[data-rel="colorbox"]'));
            gallery_item.find('.gallery-button-zoom').click(function(e){
              gallery_item.find('.gallery-image-container-hidden').trigger('click');
            });
            gallery_item.find('.gallery-image-container').click(function(e){
              preview_file(v);
            });
          }else if(v.type == 'video'){
            gallery_item.find('.gallery-image-container').click(function(e){
              preview_file(v);
            });
          }else if(v.type == 'audio'){
            gallery_item.find('.gallery-image-container').click(function(e){
              preview_file(v);
            });
          }else{
            gallery_item.find('.gallery-image-container').click(function(e){
              preview_file(v);
            });
          }

          gallery_item.find('.gallery-button-url').attr('data-value', v.url).click(function(e){
            var _u = $(this).attr('data-value');
            $("#file-url").modal('show');
            $("#file-url").find('.file-url').val(_u);
          });
        });
      }else{
        $("#media-gallery").before('<div id="drop-guide"><h3>Drop Files Here</h3><p>Files will automatically upload.</p></div>'); /* Display Drag Drop Guide */
      }
    }
  });
}
function initialize_color_box(color_box){
  var colorbox_params = {
    reposition: true,
    scalePhotos: true,
    scrolling: false,
    previous: '<i class="icon-arrow-left"></i>',
    next: '<i class="icon-arrow-right"></i>',
    close: '&times;',
    current: '{current} of {total}',
    maxWidth: '100%',
    maxHeight: '100%',
    onOpen: function() {
      document.body.style.overflow = 'hidden';
    },
    onClosed: function() {
      document.body.style.overflow = 'auto';
    },
    onComplete: function() {
      $.colorbox.resize();
    }
  };

  color_box.colorbox(colorbox_params);

  /**$(window).on('resize.colorbox', function() {
   try {
     this function has been changed in recent versions of colorbox, so it won't work
     $.fn.colorbox.load();//to redraw the current frame
     } catch(e){}
   });*/
 }

 function preview_file(file){
  $("#file-viewer").modal('show');
  remove_prev_notifications(); /* Remove current notifications */

  if (file.type == 'video') {
    $("#file-viewer").find(".file-container").html('<video width="100%" height="auto" controls><source src="'+ file.url +'" type="'+ file.mime +'"></video>');
    $("#file-viewer").find(".table-header").html("Image");
    cmsmedia_notification("Play Video", "Playing Video...", "gritter-info");
  }else if (file.type == 'audio'){
    $("#file-viewer").find(".file-container").html('<audio width="100%" height="auto" controls><source src="'+ file.url +'" type="'+ file.mime +'"></audio>');
    $("#file-viewer").find(".table-header").html("Image");
    cmsmedia_notification("Play Audio", "Playing Audio...", "gritter-info");
  }else if (file.type == 'image'){
    $("#file-viewer").find(".file-container").html('<div class="image-previewer"><img src="'+ file.url +'"></div>');
    $("#file-viewer").find(".table-header").html("Image");
    cmsmedia_notification("Previewing Image", "Previewing Image...", "gritter-info");
  }else{
    $("#file-viewer").find(".file-container").html('<iframe src="http://docs.google.com/gview?url='+ file.url +'&embedded=true"></iframe>');
    $("#file-viewer").find(".table-header").html("Image");
    cmsmedia_notification("Previewing", "Previewing a file...", "gritter-info");
  }

  $("#file-viewer").find(".item-name").html(file.filename);
  $("#file-viewer").find(".item-url").val(file.url);
}
function remove_prev_notifications(){
  $.each(current_notification, function(k, v){
    $.gritter.remove(v, { 
      fade: true, 
      speed: 'fast',
    });
  })
}
function cmsmedia_notification(title, message, notification){
  $("#media-message").removeClass("text-success text-error text-warning text-info");
  $("#media-message").html("<p><b>"+ message +"</b></p>");

  if (notification == 'gritter-success') {
    $("#media-message").addClass('text-success');
  }else if(notification == 'gritter-info'){
    $("#media-message").addClass('text-info');
  }else if(notification == 'gritter-warning'){
    $("#media-message").addClass('text-warning');
  }else if(notification == 'gritter-error'){
    $("#media-message").addClass('text-error');
  }
}