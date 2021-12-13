/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';
    if ($('#fileupload').length <= 0) { return; }
    
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');
    var product_id = $('#hidden_product_id_for_gallery').val();
    var urls = '';

    // if($('#action').val() == 'add_product')
    //     urls = CONFIG.get('URL')+'products/upload_gallery';
    // else
    
    urls = CONFIG.get('URL')+'products/manage_event_file_upload/'+$('#product_id').val();

    //alert(urls);
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: urls,
        stop: function(e){
          closeModal();
          /*setTimeout(function(){
                         
                    },3000);*/
        }/* */

    }).bind('fileuploadprogressall', function (e, data){
        
        var percentVal = '0%';
        bar.width(percentVal);
        percent.html(percentVal)
        var progress = parseInt(data.loaded / data.total * 100, 10);
        var percentVal = progress + '%';
        bar.width(percentVal);
        percent.html(percentVal);
    });
     $(".files").sortable({
        update:function(e){
          sort_gallery_image();
        }
     });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 999000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        if ($('#fileupload').length) {
            $.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: $('#fileupload').fileupload('option', 'url'),
                dataType: 'json',
                context: $('#fileupload')[0]
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            }).done(function (result) {
                $(this).fileupload('option', 'done')
                    .call(this, $.Event('done'), {result: result});
            });
        }
    }

});
