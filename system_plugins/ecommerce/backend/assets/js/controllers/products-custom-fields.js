$(document).ready(function(){

  /* Select Gallery Item */
  $("#modal-btn-confirm-gallery-item").unbind("click").click(function(){
    var item_name = $("#gallery-item-name").val();
    var item_desc = $("#gallery-item-desc").val();
    var item_id   = $("#gallery-item-id").val();
    var item_url  = $("#gallery-item-url").val();
    var curr_item = "#" + $("#current-gallery-item-id").val() + "-gallery-list";
    var item = null;

    if (selected_item_element != null) {
      item = selected_item_element;
    }else{
      item = $('#tag-gallery-item').tmpl({}).appendTo( curr_item );

      item.find(".tag-gallery-item-btn-edit").data('value', item_id).click(function(e){
        var _item = $(this).closest('li');
        selected_item_element = _item;
        var id    = _item.find('.item-id').val();
        var url   = _item.find('.item-image').attr('src');
        var name  = _item.find('.item-name').val();
        var desc  = _item.find('.item-desc').val();

        $("#modal-custom-field-gallery-editor").modal('show');
        $("#current-gallery-item-id").val(_item.closest('.accordion-group').attr('id'));
        $("#gallery-item-id").val(id);
        $("#gallery-item-name").val(name);
        $("#gallery-item-desc").val(desc);
      });
      item.find(".tag-gallery-item-btn-remove").data('value', item_id).click(function(e){
        var _item = $(this).closest("li");
        bootbox.confirm("Do you continue deleting selected item?", function(result){
          if (result) {
            _item.fadeOut(1000, function(){
              _item.remove();
            });
          }
        });
      });
    }


    item.find(".item-name").val(item_name);
    item.find(".item-desc").val(item_desc);
    // item.find(".item-image").attr('src', item_url);
    item.find(".item-id").val(item_id);

    item.fadeIn(1000);
    $("#modal-custom-field-gallery-editor").modal('hide');
  });

  $("#btn-toggle-guide").click(function(e){
    $("#cf-guide-container").slideToggle();
  }); 

  // $("#map-item-address").change(function(e){
  //   // navigateMap($(this).val());
  // });
  $("#modal-btn-confirm-map").click(function(e){
    var lat   = $("#map-item-coordinate-lat").val();
    var lng   = $("#map-item-coordinate-lng").val();
    var item  = $("#" + ($("#current-map-id").val()));

    item.find(".map-lat").val(lat);
    item.find(".map-lng").val(lng);

    $("#modal-custom-field-address").modal('hide');
  });

  /* Gallery: Open Media Container */
  $("#btn-gallery-import").click(function(e){
    $("#gallery-list-media-container").slideDown();
    galleryGetMediaImages();
  });

  // setCustomField();
  // initializeMap();
});

/* Gallery: Upload image End */
function galleryGetMediaImages(){
  $.post(CONFIG.get('URL') + "media/get_files/",{
    type : 'image'
  },function(response) {
    var data = JSON.parse(response);
    $("#gallery-tab-image .galley-list").html("");
    $.each(data, function(k, v){
      var item = $('#modal-gallery-items').tmpl({
        id  : v.id, 
        url : v.url, 
      }).prependTo( "#gallery-tab-image .galley-list" );

      item.find(".gallery-item").click(function(e){
        $(this).closest('li').addClass('active').siblings("li").removeClass('active');
        $("#gallery-item-id").val($(this).data('value'));
        $("#gallery-item-url").val($(this).find("img").attr('src'));
      });
    });

    $("#gallery-item-name").focus();
  });
}


var map;
var geocoder;
var marker;
var latlng;

function initializeMap(){
  var uluru = {lat: 0, lng: 0};
  if (typeof google == 'undefined') { return; }
  map = new google.maps.Map(document.getElementById('map_canvas'), {
    zoom: 15,
    center: uluru
  });
  marker = new google.maps.Marker({
    map       : map,
    position  : uluru,
    draggable : true,
  });
  google.maps.event.addListener(marker, "dragend", function(event) { 
    var lat = event.latLng.lat(); 
    var lng = event.latLng.lng(); 

    $("#map-item-coordinate-lat").val(lat);
    $("#map-item-coordinate-lng").val(lng);
    // console.log(event.latLng);
  });

  geocoder = new google.maps.Geocoder();
  latlng = new google.maps.LatLng(0,0);
}
function navigateMap(address, lat, lng){
  if (lat !== undefined && lng !== undefined && lat != "" && lng != "") {
    latlng = new google.maps.LatLng(lat,lng);
    map.setCenter(latlng);
    marker.setPosition(latlng);
  }else if (geocoder && address !==undefined && address !== "") {
    geocoder.geocode({
      'address': address
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          map.setCenter(results[0].geometry.location);
          marker.setPosition(results[0].geometry.location);

          $("#map-item-coordinate-lat").val(results[0].geometry.location.lat());
          $("#map-item-coordinate-lng").val(results[0].geometry.location.lng());

        } else {
          notification("Map", "No results found", "gritter-error");
        }
      } else {
        notification("Map", "Geocode was not successful for the following reason: " + status, "gritter-error");
      }
    });
  }
}

// function addField(type, key, name, value, option, collapsed){
//   var id = custom_field_counter++;
//   var test_field = $('#' + type).tmpl({
//     field_id    : id, 
//     field_key   : key, 
//     field_name  : name, 
//     field_val   : value !== undefined ? value : '', 
//     field_opt   : option !== undefined ? option : '', 
//   }).prependTo( "#custom_fields .field-container" );

//   test_field.find('.title')
//   .blur(function(){
//     var t = $(this).val();
//     var n = slugify(t);

//     if (n == '') {
//       $(this).closest(".accordion-group").find('.custom-field-name-warning').text("[ Missing field name ]");
//     }else{

//     }

//     $(this).closest(".accordion-group").find(".key").val(n);
//     $(this).closest(".accordion-group").find('.accordion-heading>a>b').text(t);

//     resetWarning();
//   })
//   .focus(function(){
//     $(this).closest(".accordion-group").find('.custom-field-name-warning').text("");
//   }).focus();

//   test_field.find(".key")
//   .blur(function(){
//     resetWarning();
//   });

//   if (type == 'tag-text') {
//     test_field.find('.value').val( value );
//   }else if (type == 'tag-textarea-simple'){
//     test_field.find('.value').val( value );
//   }else if (type == 'tag-textarea'){
//     if ($("#custom-field-id-"+ id +" textarea").length > 0) {
//       tinyMCE.init({selector: "#custom-field-textarea-"+ id,
//         menubar: " view edit format table tools",
//         width : '100%',
//         height : 200,
//         init_instance_callback : function(editor) {
//           tinyMCE.get("custom-field-textarea-"+ id).setContent(value);

//           // editor.on('change', function(ed, l){
//           //   console.log(this.getContent());
//           // });
//         },
//         toolbar:[
//         "formatselect nonbreaking undo redo bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent indent",
//         "image imageuploader cmsmedia  link "
//         ],
//         plugins: [
//           "paste advlist autolink link image lists charmap print preview hr anchor pagebreak ",
//           "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
//           "imageuploader cmsmedia",
//           "textcolor colorpicker","nonbreaking table code"
//         ],
//         external_plugins:{
//           'imageuploader' :'plugins/imageuploader/editor_plugin_src.js',
//           'cmsmedia'      :'plugins/cmsmedia/editor_plugin_src.js',
//         },
//         relative_urls: false,
//         convert_urls: false,
//         nonbreaking_force_tab:true,
//         tools: "inserttable",
//         verify_html: false
//       });
//     }
//   }else if (type == 'tag-dropdown'){
//     test_field.find('.custom-field-select').val(value);
//     test_field.find('.custom-field-select-add-item').click(function(e){
//       var curr_id = $(this).closest(".accordion-group").attr("id");
//       var curr_selection = $("#" + curr_id).find(".custom-field-select");
//       var curr = [];

//       $("#modal-custom-field-list-currrent-group").val(curr_id);

//       $.each(curr_selection.find("option"), function(k, v){
//         curr.push($(this).attr("value"))
//       });

//       $("#modal-custom-field-list-item").val(curr.join("\n"));
//       $("#modal-custom-field-select-add-item").modal('show');
//       $("#modal-btn-confirm-select-items").click(function(e){
//         var items = $("#modal-custom-field-list-item").val();
//         var curr_id = $("#modal-custom-field-list-currrent-group").val();
//         var curr_selection = $("#" + curr_id).find(".custom-field-select");
//         curr_selection.html("");

//         $.each((items.trim()).split("\n"), function(k, v){
//           curr_selection.append('<option value="'+ v +'">'+ v +'</option>')
//         });

//         $("#modal-custom-field-select-add-item").modal('hide');
//       });
//     });
//   }else if (type == 'tag-tags'){
//     var item = test_field.find(".value");

//     item.chosen({
//       width : "100%"
//     })
//     .siblings('.chosen-container').
//     find(".chosen-choices input")
//     .focus(function(){
//       $(this).closest(".accordion-body").css("overflow", "unset")
//     })

//     test_field.find(".accordion-toggle").click(function(){
//       $(this).closest(".accordion-group").find(".accordion-body").css("overflow", "hidden")
//     });

//     test_field.find('.custom-field-select').val(value).trigger("chosen:updated");
//     test_field.find('.custom-field-select-add-item').click(function(e){
//       var curr_id = $(this).closest(".accordion-group").attr("id");
//       var curr_selection = $("#" + curr_id).find(".custom-field-select");
//       var curr = [];

//       $("#modal-custom-field-list-currrent-group").val(curr_id);

//       $.each(curr_selection.find("option"), function(k, v){
//         curr.push($(this).attr("value"))
//       });

//       $("#modal-custom-field-list-item").val(curr.join("\n"));
//       $("#modal-custom-field-select-add-item").modal('show');
//       $("#modal-btn-confirm-select-items").click(function(e){
//         var items = $("#modal-custom-field-list-item").val();
//         var curr_id = $("#modal-custom-field-list-currrent-group").val();
//         var curr_selection = $("#" + curr_id).find(".custom-field-select");
//         curr_selection.html("");

//         $.each((items.trim()).split("\n"), function(k, v){
//           curr_selection.append('<option value="'+ v +'">'+ v +'</option>')
//         });

//         curr_selection.trigger("chosen:updated")

//         $("#modal-custom-field-select-add-item").modal('hide');
//       });
//     });
//   }else if (type == 'tag-switch'){
//     if (value == "Y") {
//       var item = test_field.find(".value");
//       if (!item.is(":checked")) {
//         item.trigger("click");
//       }
//     }
//   }else if (type == 'tag-gallery'){
//     initializeGallery(test_field);

//     $.each(value, function(k, v){
//       var item_name = v.name;
//       var item_desc = v.desc;
//       var item_id   = v.id;
//       var item_url  = v.img;
//       var curr_item = "#" + test_field.attr('id') + "-gallery-list";

//       var item = $('#tag-gallery-item').tmpl({}).appendTo( curr_item )

//       item.find(".tag-gallery-item-btn-edit").data('value', item_id).click(function(e){
//         var _item = $(this).closest('li');
//         selected_item_element = _item;
//         var id    = _item.find('.item-id').val();
//         var url   = _item.find('.item-image').attr('src');
//         var name  = _item.find('.item-name').val();
//         var desc  = _item.find('.item-desc').val();

//         $("#modal-custom-field-gallery-editor").modal('show');
//         $("#current-gallery-item-id").val(_item.closest('.accordion-group').attr('id'));
//         $("#gallery-item-id").val(id);
//         $("#gallery-item-name").val(name);
//         $("#gallery-item-desc").val(desc);

//         // $.post(CONFIG.get('URL') + "media/get_files/",{
//         //   type : 'image'
//         // },function(response) {
//         //   var data = JSON.parse(response);
//         //   $("#gallery-tab-image .galley-list").html("");
//         //   $.each(data, function(k, v){
//         //     var item = $('#modal-gallery-items').tmpl({
//         //       id  : v.id, 
//         //       url : v.url, 
//         //     }).prependTo( "#gallery-tab-image .galley-list" );

//         //     item.find(".gallery-item").click(function(e){
//         //       $(this).closest('li').addClass('active').siblings("li").removeClass('active');
//         //       $("#gallery-item-id").val($(this).data('value'));
//         //       $("#gallery-item-url").val($(this).find("img").attr('src'));
//         //     });

//         //     if (v.id == id) {
//         //       item.find(".gallery-item").trigger('click')
//         //     }
//         //   });
//         // });
//       });
//       item.find(".tag-gallery-item-btn-remove").data('value', item_id).click(function(e){
//         var _item = $(this).closest("li");
//         bootbox.confirm("Do you continue deleting selected item?", function(result){
//           if (result) {
//             _item.fadeOut(1000, function(){
//               _item.remove();
//             });
//           }
//         });
//       });

//       item.find(".item-name").val(item_name);
//       item.find(".item-desc").val(item_desc);
//       item.find(".item-image").attr('src', item_url);
//       item.find(".item-id").val(item_id);

//       item.fadeIn(1000);
//     });
//   }else if (type == 'tag-address'){
//     test_field.find('.value').val( value.addr );
//     test_field.find('.map-lng').val( value.lng );
//     test_field.find('.map-lat').val( value.lat );
//     test_field.find('.value').change( function(){
//       var item = $(this).closest(".accordion-group");
//       var addr = $(this).val();

//       item.find(".map-lat").val("");
//       item.find(".map-lng").val("");

//       item.find(".map-loading").show();

//       geocoder.geocode({
//         'address': addr
//       }, function(results, status) {
//         if (status == google.maps.GeocoderStatus.OK) {
//           if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
//             item.find(".map-lat").val(results[0].geometry.location.lat());
//             item.find(".map-lng").val(results[0].geometry.location.lng());
//           } else {
//             notification("Map", "No results found", "gritter-error");
//           }
//         } else {
//           notification("Map", "Geocode was not successful for the following reason: " + status, "gritter-error");
//         }

//         item.find(".map-loading").hide()
//       });
//     });

//     test_field.find('.btn-check-map').click(function(e){
//       var item  = $(this).closest(".accordion-group");
//       var addr  = item.find(".value").val();
//       var lat   = item.find(".map-lat").val();
//       var lng   = item.find(".map-lng").val();

//       $("#current-map-id").val( item.attr('id') );
//       $("#map-item-address").val( addr );
//       $("#map-item-coordinate-lat").val( lat );
//       $("#map-item-coordinate-lng").val( lng );
//       navigateMap(addr, lat, lng);

//       $("#modal-custom-field-address").modal('show');
//     });
//   }

//   collapsed = collapsed !== undefined ? collapsed : false;
//   if (collapsed) {
//     test_field.find(".accordion-toggle").addClass('collapsed');
//     test_field.find(".accordion-body").removeClass('in');
//   }else{
//     test_field.find(".accordion-toggle").removeClass('collapsed');
//     test_field.find(".accordion-body").addClass('in');
//   }

//   test_field.find('.remove-custom-field').click(function(){
//     bootbox.confirm("Do you continue deleting selected item? <br /><i>Action will take effect after saving</i>.", function(result){
//       if (result) {
//         test_field.remove();
//       }
//     });
//   })
// }
// function resetWarning(){
//   $("#custom_fields .accordion-group").find('.custom-field-name-warning').text("")

//   $.each($("#custom_fields .accordion-group"), function(){
//     var key   = $(this).find(".key");
//     var title = $(this).find(".title").val();

//     var s = key.val();
//     var heading = key.closest(".accordion-group").find('.accordion-heading>a>b');
//     var warning = key.closest(".accordion-group").find('.custom-field-name-warning');
//     heading.html(title);

//     if (s != '') {
//       if (existFieldName(key)) {
//         heading.html(title + ' <i class="icon icon-ban-circle text-error"></i>');
//         warning.text("[ Already used in other field ]");
//       }
//     }else{
//       heading.html(title + ' <i class="icon icon-ban-circle text-error"></i>');
//       warning.text("[ Missing field name ]");
//     }
//   });
// }

// function saveCustomFields_(product_id, fn_callback){
//   if (validateCustomFields()) {
//     var data  = [];
//     var pid   = product_id;
//     var cfid  = $("#custom-field-id").val();

//     $.each($("#custom_fields .accordion-group"), function(){
//       var type    = $(this).data('type');
//       var name    = $(this).find('.title').val();
//       var key     = $(this).find('.key').val();
//       var value   = "";
//       var id      = $(this).data('id');
//       var option  = [];

//       if (type == 'text'|| type == 'textarea-simple') {
//         value = $(this).find('.value').val();
//       }else if (type == 'switch') {
//         value = $(this).find('.value').is(":checked") ? "Y" : "N";
//       }else if (type == 'tags') {
//         value = $(this).find('.value').val();
//         option = [];
//         $.each($(this).find("option"), function(){
//           option.push($(this).attr('value'));
//         });
//       }else if (type == 'dropdown') {
//         value = $(this).find('.value').val();
//         option = [];
//         $.each($(this).find("option"), function(){
//           option.push($(this).attr('value'));
//         });
//       }else if (type == 'textarea') {
//         value = tinyMCE.get("custom-field-textarea-" + id).getContent();
//       }else if (type == 'gallery') {
//         value = [];
//         $.each($(this).find(".galley-list").find(".tag-gallery-item"), function(){
//           var x = {
//             id    : $(this).find(".item-id").val(),
//             name  : $(this).find(".item-name").val(),
//             desc  : $(this).find(".item-desc").val(),
//             img   : $(this).find(".item-image").attr('src'),
//           }
//           value.push(x);
//         });
//       }else if (type == 'address') {
//         value = {
//           addr  : $(this).find(".value").val(),
//           lat   : $(this).find(".map-lat").val(),
//           lng   : $(this).find(".map-lng").val(),
//         }
//       }

//       data.push({
//         type    : type,
//         key     : key,
//         name    : name,
//         value   : value,
//         option  : option,
//       });
//     });

//     jQuery.post(CONFIG.get('URL')+'products/product_custom_fields/',{
//       action        : 'save_custom_field',
//       product_id    : pid,
//       product_cf_id : cfid,
//       language      : $("#language").val(),
//       data          : JSON.stringify(data),
//     }, function(response){
//       notification("Product Custom Fields", "Save Successfully Custom Fields", "gritter-success");
//       fn_callback();
//     });
//   }else{
//     notification("Product Custom Fields", "Error found on custom fields...", "gritter-error");
//     fn_callback();
//   }
// }
// function setCustomField(){
//   /* Clear custom fields */
//   $( "#custom_fields .field-container" ).html("");

//   var pid   = $("#product_id").val();

//   jQuery.post(CONFIG.get('URL')+'products/product_custom_fields/',{
//     action      : 'get_custom_field',
//     product_id  : pid,
//     language    : $("#language").val(),
//   }, function(response){
//     var response = JSON.parse(response);

//     $("#custom-field-id").val(response.cf_id);

//     while(response.cf_data.length > 0){
//       var v = response.cf_data.pop();
//       var o = typeof v.option !== undefined ? v.option : [];
//       addField("tag-" + v.type, v.key, v.name, v.value, o, true);
//     }
//   });
// }
// function validateCustomFields(){
//   var isValid = true;

//   $.each($("#custom_fields .accordion-group").find('.title'), function(){
//     var s = $(this).val();

//     if (s == '') {
//       isValid = false
//     }else{
//       if (existFieldName($(this))) {
//         isValid = false;
//       }
//     }
//   });

//   return isValid;
// }
// function initializeGallery(field_group){
//   if (field_group.find('.galley-list')) {
//     var dropzone = field_group.find(".file-drop-zone");

//     field_group.on("dragenter",function(e){
//       dropzone.stop().fadeIn(200);
//     });

//     dropzone.on("dragleave", function(e){
//       dropzone.stop().fadeOut(200);
//     })
//     .on("drop", function(e){
//       e.stopPropagation()
//       e.preventDefault()
//       dropzone.stop().fadeOut(200);

//       var f = e.originalEvent.dataTransfer.files;
//       var c = field_group.find(".galley-list");

//       var cms_uploader = new CMSMediaUploader();
//       cms_uploader.files = f;
//       cms_uploader.container = c;
//       cms_uploader.upload_files();

//       // console.log(cms_media_uploader.upload_queue);

//       // $("#file-viewer").modal('hide')
//       // $("#cboxClose").trigger('click')
//       // process_upload(f)
//     });

//     var add_btn = field_group.find(".btn-gallery-add-item");
//     add_btn.click(function(){
//       $("#modal-custom-field-gallery-editor").modal('show');
//       $("#current-gallery-item-id").val(add_btn.closest('.accordion-group').attr('id'));
//       $("#gallery-item-id").val('');
//       $("#gallery-item-url").val('');
//       $("#gallery-item-name").val('');
//       $("#gallery-item-desc").val('');

//       $("#modal-custom-field-gallery-tab>li:first-child").addClass("active").siblings("li").removeClass('active');
//       $("#modal-custom-field-gallery-tab").siblings(".tab-content").find(".tab-pane:first-child").addClass("in active").siblings(".tab-pane").removeClass("in active");

//       // galleryGetMediaImages();
//     });
//   }
// }

/* Gallery: Upload image Start */
// function CMSMediaUploader(){
//   this.files = [];
//   this.upload_queue = [];
//   this.upload_files = function(){
//     if (this.files.length > 0) {
//       var upload_queue = this.upload_queue;

//       $.each(this.files, function(k, file){
//         var file_info = {
//           name : file['name'],
//           size : cms_function.fn.bytesToSize(file['size']),
//           type : file['type'].split('/')[0],
//         }

//         /* add to overall queue */
//         upload_queue.push({
//           file    : file,
//           data    : file_info,
//           // element : item,
//         });
//       });

//       this.start_upload();
//     }
//   }
//   this.start_upload = function(){
//     if (this.upload_queue.length > 0) {
//       // var file_limit = upload_setting['actual_max_upload_size'];
//       var uploader = this;
//       var file = this.upload_queue.shift();
//       var fd = new FormData();    
//       fd.append( 'file', file.file );

//       if (file.file.size) {
//         // file.element.find('.file-size').addClass('text-success');
//         $.ajax({
//           url: CONFIG.get('URL')+'media/upload/',
//           data : fd,
//           processData: false,
//           contentType: false,
//           type: 'POST',
//           success: function(response) {
//             uploader.success( response );
//             uploader.start_upload();
//           },
//           xhr: function () {
//             var xhr = new window.XMLHttpRequest();

//             xhr.upload.addEventListener("progress", function (evt) {
//               if (evt.lengthComputable) {
//                 var percentComplete = evt.loaded / evt.total;
//                 percentComplete = parseInt(percentComplete * 100);
//                 // console.log(percentComplete);
//                 // file.element.find('.progress .bar').css('width', percentComplete + "%")
//               /*progressWrap.attr('data-percent', percentComplete)
//               progressBar.css('width', percentComplete + '%')*/
//             }
//           }, false);

//             return xhr;
//           },
//           error: function() { },
//           progress: function(e) {
//             /*make sure we can compute the length*/
//             if(e.lengthComputable) {
//               /*calculate the percentage loaded*/
//               var pct = (e.loaded / e.total) * 100;
//               /*log percentage loaded*/
//               // console.log(pct);
//             }
//             /*this usually happens when Content-Length isn't set*/
//             else {
//               console.warn('Content Length not reported!');
//             }
//           },
//         });
//       }else{
//         this.start_upload();
//       }
//     }
//   }
//   this.success = function(file){
//     // console.log(file)
//     var item = $('#tag-gallery-item').tmpl({}).appendTo( this.container );
//     item.fadeIn(200);
//     item.find('img').attr('src', file);
//   }
// }


// function process_upload(f){
//   if (f.length > 0) {
//     $("#file-upload-modal").modal('show');
//     var upload_container = $("#file-upload-modal").find('.upload-container');
    
//     $.each(f, function(k, file){
//       var file_info = {
//         name : file['name'],
//         size : cms_function.fn.bytesToSize(file['size']),
//         type : file['type'].split('/')[0],
//       }
//       /* Adding Item to Upload Queue List */
//       var item = null;
//       item = $('#media-upload-item-template').tmpl(file_info).appendTo(upload_container);

//       upload_queue.push({
//         file    : file,
//         data    : file_info,
//         element : item,
//       });

//       /* Set default image thumbnail */
//       add_default_thumbnail(item.find('img'), file['type'].split('/')[0]);
//       /* Creating Blob for image */
//       var img = new Image()
//       img.src = window.URL.createObjectURL(file);
//       img.onload = function() {
//         item.find('img').attr('src', this.src);
//         window.URL.revokeObjectURL(this.src);
//       }
//     });
//     $("#file-upload-modal").find('.modal-footer').hide();
//     upload_photo();
//   }else{
//     current_notification.push(notification("Dragged Files...", "No file to upload", "gritter-warning"));
//   }
// }
// function add_default_thumbnail(image, type){
//   if (type=='image') {
//     image.attr('src', CONFIG.get('FRONTEND_URL')+'/files/defaults/icon-image.png');
//   }else if(type=='video'){
//     image.attr('src', CONFIG.get('FRONTEND_URL')+'/files/defaults/icon-video.png');
//   }else if(type=='audio'){
//     image.attr('src', CONFIG.get('FRONTEND_URL')+'/files/defaults/icon-audio.png');
//   }else{
//     image.attr('src', CONFIG.get('FRONTEND_URL')+'/files/defaults/icon-file.png');
//   }
// }
// function upload_photo(){
//   if (upload_queue.length > 0) {
//     var file_limit = upload_setting['actual_max_upload_size'];
//     var file = upload_queue.shift();
//     var fd = new FormData();    
//     fd.append( 'file', file.file );

//     if (file.file.size <= file_limit) {
//       file.element.find('.file-size').addClass('text-success');
//       $.ajax({
//         url: CONFIG.get('URL')+'media/upload/',
//         data : fd,
//         processData: false,
//         contentType: false,
//         type: 'POST',
//         success: function() {
//           file.element.find('.progress').addClass('progress-success').removeClass('progress-primary');
//           file.element.find('.action').html('<b class="pull-right text-success">Successfully Uploaded</b>').removeClass("action");
//           file.element.addClass('process-done');
//           upload_photo();
//         },
//         xhr: function () {
//           var xhr = new window.XMLHttpRequest();

//           xhr.upload.addEventListener("progress", function (evt) {
//             if (evt.lengthComputable) {
//               var percentComplete = evt.loaded / evt.total;
//               percentComplete = parseInt(percentComplete * 100);
//               console.log(percentComplete);
//               file.element.find('.progress .bar').css('width', percentComplete + "%")
//             /*progressWrap.attr('data-percent', percentComplete)
//             progressBar.css('width', percentComplete + '%')*/
//           }
//         }, false);

//           return xhr;
//         },
//         error: function() { },
//         progress: function(e) {
//           alert(e);
//           console.log(e);
//           /*make sure we can compute the length*/
//           if(e.lengthComputable) {
//             /*calculate the percentage loaded*/
//             var pct = (e.loaded / e.total) * 100;
//             /*log percentage loaded*/
//             console.log(pct);
//           }
//           /*this usually happens when Content-Length isn't set*/
//           else {
//             console.warn('Content Length not reported!');
//           }
//         },
//       });
//     }else{
//       file.element.find('.progress').hide();
//       file.element.find('.file-size').append(" (exceeded upload limit <b>"+ cms_function.fn.bytesToSize(upload_setting['actual_max_upload_size']) +"</b>) ").addClass('text-error');
//       upload_photo();
//     }
//   }else{
//     get_files();
//     $("#file-upload-modal").find('.modal-footer').show();
//     current_notification.push(notification("Upload", "Upload Complete", "gritter-success"));
//   }
// }
