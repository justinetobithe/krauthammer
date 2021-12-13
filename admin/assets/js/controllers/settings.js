
$('#loading_save_image').hide();
$('#tab_content').hide();
$('#loading_save_email').hide();
$('#loading_save_email_inquiry').hide();

var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var switch_page = 0;
var map;
var latlng;
var latd;
var lng;
var marker 
var switch_google = 0;
var initialize = 0;
var id = 0;
var indexing = 0;

$(document).ready(function(){

  $('#id-input-file-3').ace_file_input({
    no_file:'No File ...',
    btn_choose:'Choose',
    btn_change:'Change',
    droppable:false,
    onchange:null,
  }).on('change', function(){
    jQuery('div').remove('#errorImage');
    var ext = $('#id-input-file-3').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
    {

      globalerror = true;
      jQuery('#msg_alert_company_image').append(alertMessage('Invalid Image File','error','errorImage'));
    }
    else
      globalerror = false;

  });
  $('.modal').on('show', function(e){
    $('#modal_container').removeClass('hide');
    setTimeout(function(){ initialize_map(); }, 1000); 
  });
  $('.modal').on('hidden', function(e){
    $('#map_canvas').empty();
    $('#map_canvas').removeAttr('style');
    $('#map_canvas').attr('style', 'height:300px; width:100%;');
    $('#modal_container').addClass('hide');
  });
  loadData();

  $('#general_settings_form').ajaxForm({
    complete: function(xhr) {
      var obj = JSON.parse(xhr.responseText);
      IsValidImageUrl(obj['image']);
      if(obj['status'] == true)
        $('#messageAlert1').append(alertMessage('Successfully Saved Settings.','success'));
      else
        $('#messageAlert1').append(alertMessage('Error While Saving Settings.','error'));
      $('html, body').animate({ scrollTop: 0 }, 'slow');
    },
    error:  function(xhr, desc, err) { 
      console.debug(xhr); 
      console.log("Desc: " + desc + "\nErr:" + err); 
    } 
  });

  $("#btn-save-sitemap").click(function(e){
    saveSiteMap();
  });
  $("#modal-sitemap-loading").modal({
    show : false,
    keyboard : false,
    backdrop : 'static',
  });
  $("#btn-ping-sitemap").click(function(e){
    $("#modal-sitemap-loading").modal('show').find(".message").text("Notifying Goggle... This will take less that a minute...");
    $.post(CONFIG.get("URL") + "settings/sitemap_processor/",{
      action : 'ping'
    },function(response) {
      if (cms_function.isJSON(response)) {
        response = JSON.parse(response);

        if (typeof response['status'] != 'undefined') {
          if (response['status']=='success') {
            notification("Sitemap", response['data']['google']['message'], "gritter-success");
          }else{
            notification("Sitemap", response['data']['google']['message'], "gritter-error");
          }
        }else{
          notification("Sitemap", "Response Error: Unable to determine response status.", "gritter-warning");
        }

        if (response['data']['google']['status']) {
          $("#notification-google").html('<strong>'+ response['data']['google']['message'] +'</strong>').removeClass('text-error').addClass('text-success');
        }else{
          $("#notification-google").html('<strong>'+ response['data']['google']['message'] +'</strong>').removeClass('text-success').addClass('text-error');
        }

        if (typeof response['data']['google']['last-ping'] != 'undefined') {
          $("#notification-google-last-ping").html("<em>(Last ping: " + response['data']['google']['last-ping'] + ")</em>");
        }else{
          $("#notification-google-last-ping").html("");
        }

      }else{
        $("#notification-google").html('<strong>Server Error</strong>').removeClass('text-error text-success').addClass('text-error');
        notification("Sitemap", "Response Error: There's an error from server", "gritter-error");
      }

      $("[data-rel=tooltip]").tooltip();

      $("#modal-sitemap-loading").modal('hide').find(".message").text("");
    });
  });
  open_on_load_tab()
});
function open_on_load_tab(){
  var _url_string = window.location.href;
  var _url = new URL(_url_string);
  var c = _url.searchParams.get("tab");
  var s = _url.searchParams.get("section");
  
  if (c == 'contact-form') {
    $("#myTab3 li:nth-child(8) a").trigger('click');
  }else if (c == 'product') {
    $("#myTab3 li:nth-child(9) a").trigger('click');
  }

  if (s == 'product-category-format-url') {
    scrollToSection("#anchor-product-category-format-url")
  }else if(s == 'product-custom-field'){
    scrollToSection("#product-custom-field")
  }
}
function scrollToSection(section){
  setTimeout(function(){
    scrollAnimation(section)
  }, 500);
}
function scrollAnimation(section){
  var $target = $(section);

  if(typeof $target.offset() !== "undefined") {
    $('html, body').stop().animate({
        'scrollTop': $target.offset().top - $(".page-nav").outerHeight()
    }, 500, 'swing');
  }
} 
function loadImage(){
  var src = $('#txt_image').val();
  IsValidImageUrl(src);
  /* $('#company_logo').attr("src",src); */
}
function IsValidImageUrl(url) {
  $("<img>", {
    src: url,
    error: function() { add_text(url); },
    load: function() { add_photo(url); }
  });
}
function add_text(url){
  $('#text_div').removeClass('hide');
  $('#image_div').addClass('hide');
  $('#text_logo').html(url);
}
function add_photo(url){

  $('#image_div').removeClass('hide');
  $('#text_div').addClass('hide');
  $('#company_logo').attr("src",url);
}
function change_switch_google(){
  if(switch_google == 0)
    switch_google = 1;
  else
    switch_google = 0;

  if(switch_google == 1)
    $('#switch').val('ON');
  else
    $('#switch').val('OFF');
}
/*function saveImage()
{
var formData = new FormData($('')[0]);
var image = $('#txt_image').val();
var copyright_footer = $('#copyright_text').val();
var con_tracking_code = $('#conversion_tracking_code').val();
var web_analytics = $('#web_analytics').val();
var switch_event = '';
if(switch_google == 0)
switch_event = 'OFF';
else
switch_event = 'ON';

jQuery('div').remove('#alert_status');
jQuery.post(CONFIG.get('URL')+'settings/saveSetting',{action:'save_image',image:image, copyright_footer:copyright_footer, con_tracking_code:con_tracking_code,web_analytics:web_analytics,switch_event:switch_event},function(response,status){
$('#loading_save_image').show();
$('#btn_save_image').attr('disabled','disabled');
if(status=='success'){

eval(response);

$('#loading_save_image').hide();
$('#btn_save_image').removeAttr('disabled');
}
else
saveImage();

});
}*/
function saveEmail()
{

  var email = $('#txt_email').val();
  var name = $('#txt_name').val();
  jQuery('div').remove('#alert_status');
  if(regex.test(email))
    jQuery.post(CONFIG.get('URL')+'settings/saveSetting',{action:'save_email',email:email, name:name},function(response,status){
      $('#loading_save_email').show();
      $('#btn_save_email').attr('disabled','disabled');

      if(status=='success'){

        eval(response);

        $('#loading_save_email').hide();
        $('#btn_save_email').removeAttr('disabled');
      }
      else
        saveEmail();

    });
  else
    jQuery('#messageAlert2').append(alertMessage('Invalid Email.','error'));
}

function loadData(){
  jQuery.post(CONFIG.get('URL')+'settings/loadData/',{action:'loadData'},function(response,status){
    var settings_data = [];
    if(status=='success'){
      var newResult = JSON.parse(response);

      var settings_data = {};
      $.each(newResult, function(k, v){
        settings_data[v['option_name']] = v['option_value'];
      });

      $('#txt_image').val(settings_data["website_logo"]);
      if(settings_data["website_logo"]!=''){
        $('#company_logo').attr('src', settings_data["website_logo"]);
        $('#logo_url').val(settings_data["website_logo"]);
      }
      $('#copyright_text').append(settings_data["website_footer_copyright_text"]);
      $('#txt_email').val(settings_data["system_email"]);
      $('#txt_name').val(settings_data["system_email_name"]);
      $('#order').val(settings_data["category_page_display_order"]);
      $('#view').val(settings_data["category_page_display_view"]);
      $('#related_items').val(settings_data["listing_page_display_related_items_count"]);
      if(settings_data["customer_login_required"] == 'ON')
        $('#r_view_products').trigger('click');
      if(settings_data["google_event_tracking"] == 'ON')
        $('#switch_google_event_tracking').trigger('click');

      $('#web_analytics').val(settings_data["google_analytics_code"]);
      $('#conversion_tracking_code').val(settings_data["conversion_tracking_code"]);
      $('#txt_company_name').val(settings_data["company_name"]);
      $('#txt_company_address').val(settings_data["company_address"]);
      $('#txt_contact_number').val(settings_data["company_contact_number"]);
      $('#txt_company_fax_number').val(settings_data["company_fax_number"]);
      $('#txt_company_email').val(settings_data["company_email"]);
      $('#txt_business_registration_number').val(settings_data["business_registration_number"]);
        
      if(settings_data["disallow_indexing"] == 'YES')
        $('#switch_indexing').trigger('click');

      if(settings_data["disallow_blog_indexing"] == 'ON'){
        $('#blog_indexing').trigger('click');
      }
      if(settings_data["disallow_blog_post_indexing"] == 'ON'){
        $('#blog_post_indexing').trigger('click');
      }
      if(settings_data["disallow_blog_search_indexing"] == 'ON'){
        $('#blog_search_indexing').trigger('click');
      }
      if(settings_data["disallow_blog_pagination_indexing"] == 'ON'){
        $('#blog_pagination_indexing').trigger('click');
      }
      if(settings_data["disallow_blog_category_indexing"] == 'ON'){
        $('#blog_category_indexing').trigger('click');
      }
      if(settings_data["enable_https_redirect"] == 'ON'){
        $('#https_redirect').trigger('click');
      }
      if(settings_data["enable_customer_registration"] == 'ON'){
        $('#enable_customer_registration').trigger('click');
      }

      $('#tab_content').show();
      $('#website_name').val(settings_data["website_name"]);
      $('#text_gst_number').val(settings_data['company_gst_number']);

      if (typeof newResult['sitemap-options'] != 'undefined') {
        var sitemap_option_meta = JSON.parse(newResult['sitemap-options']['meta_data']);

        if (typeof sitemap_option_meta['last-google-ping'] != 'undefined') {
          $("#notification-google-last-ping").html("<em>(Last ping: " + sitemap_option_meta['last-google-ping'] + ")</em>");
        }else{
          $("#notification-google-last-ping").html("");
        }

        if (typeof sitemap_option_meta['ping-google'] != 'undefined') {
          if (sitemap_option_meta['ping-google']) {
            $("#notification-google").html("<strong>Successfully notified Google.</strong>").removeClass("text-error").addClass('text-success');
          }else{
            $("#notification-google").html("<strong>Unable to notify Google.</strong>").removeClass("text-success").addClass('text-error');
          }
        }else{
          $("#notification-google").html("<strong>Unable to notify Google.</strong>").removeClass("text-success").addClass('text-error');
        }
      }else{
        $("#notification-google").html("<strong>Unable to notify Google.</strong>").removeClass("text-success").addClass('text-error');
      }
    }
    else
      window.location.href=CONFIG.get('URL')+"/settings";

  });
}
function save_page_settings(){
  $('#alert_listing_page').empty();
  var order = $('#order').val();
  var display = $('#view').val();
  var r_item = $('#related_items').val();
  var require = '';

  if(switch_page == 0)
    require = 'OFF';
  else
    require = 'ON';

  var arr_page_settings = [];

  arr_page_settings.push(order);
  arr_page_settings.push(display);
  arr_page_settings.push(r_item);
  arr_page_settings.push(require);

  jQuery.post(CONFIG.get('URL')+'settings/saveSetting',{action: 'save_page_settings', arr_page_settings:arr_page_settings}, function(response, status){
    if(status == 'success')
      if(JSON.parse(response) == '1'){
        $('#alert_listing_page').append(alertMessage('Successfully Saved.','success'));
      }else
      $('#alert_listing_page').append(alertMessage('Error while saving.','error'));
      else
        $('#alert_listing_page').append(alertMessage('Unable to connect network.','error'));
    });

}

function change_switch(){
  if(switch_page == 0)
    switch_page = 1;
  else
    switch_page = 0;

  if(switch_page == 1)
    $('#switch').val('ON');
  else
    $('#switch').val('OFF');
}
function change_index(){
  if(indexing == 0)
    indexing = 1;
  else
    indexing = 0;

  if(indexing == 1)
    $('#indexing').val('YES');
  else
    $('#indexing').val('NO');
}


function save_company_profile(){
  var company_profile = {};

  company_profile['company_name'] = $('#txt_company_name').val();
  company_profile['company_address'] = $('#txt_company_address').val();
  company_profile['company_contact_number'] = $('#txt_contact_number').val();
  company_profile['company_fax_number'] = $('#txt_company_fax_number').val();
  company_profile['company_email'] = $('#txt_company_email').val();
  company_profile['business_registration_number'] = $('#txt_business_registration_number').val();
  company_profile['company_gst_number'] = $('#text_gst_number').val();

  /* alert(company_profile['business_registration_number']); */ 
  $.post(CONFIG.get('URL')+'settings/save_company_profile',{action: 'save_company_profile', arr_company_profile:company_profile }, function(response, status){

    if(status == 'success')
      if(JSON.parse(response) == '1'){
        $('#message_alert_company_profile').append(alertMessage('Successfully Saved.','success'));
      }else
      $('#message_alert_company_profile').append(alertMessage('Error while saving.','error'));
      else
        $('#message_alert_company_profile').append(alertMessage('Unable to connect network.','error'));

    });

}



function initialize_map() {
  var mapOptions = {
    zoom: 15
  };
  map = new google.maps.Map(document.getElementById('map_canvas'),
    mapOptions);

  /*Try HTML5 geolocation*/
  if(latlng == null)
    if(navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = new google.maps.LatLng(position.coords.latitude,
          position.coords.longitude);
        latlng = pos;
/*var infowindow = new google.maps.InfoWindow({
map: map,
position: pos,
content: 'Location found using HTML5.'
});*/
marker = new google.maps.Marker({
  position: pos,
  map: map,
  draggable: true,
  title: 'test'
});
map.setCenter(pos);

load_map();

}, function() {
  handleNoGeolocation(true);
});
    } else {
      /*Browser doesn't support Geolocation*/
      handleNoGeolocation(false);
    }
    else{

/*var infowindow = new google.maps.InfoWindow({
map: map,
position: pos,
content: 'Location found using HTML5.'
});*/

marker = new google.maps.Marker({
  position: latlng,
  map: map,
  draggable: true,
  title: 'test'
});
map.setCenter(latlng);

load_map();
}



}

function handleNoGeolocation(errorFlag) {
  if (errorFlag) {
    var content = 'Error: The Geolocation service failed.';
  } else {
    var content = 'Error: Your browser doesn\'t support geolocation.';
  }

  var options = {
    map: map,
    position: new google.maps.LatLng(60, 105),
    content: content
  };

  var infowindow = new google.maps.InfoWindow(options);
  map.setCenter(options.position);
}

function navigate(){

  var geocoder;
  var address = $('#map_address').val();
  var mapOptions = {
    zoom: 15
  };
  map = new google.maps.Map(document.getElementById('map_canvas'),
    mapOptions);
  geocoder = new google.maps.Geocoder();
  if (geocoder) {
    geocoder.geocode({
      'address': address
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          map.setCenter(results[0].geometry.location);

          var infowindow = new google.maps.InfoWindow({
            content: '<b>' + address + '</b>',
            size: new google.maps.Size(150, 50)
          });
          latd = null;
          lng = null;
          latlng = results[0].geometry.location;
          marker = new google.maps.Marker({
            position: results[0].geometry.location,
            map: map,
            draggable: true,
            title: address
          });
          load_map();

        } else {
          alert("No results found");
        }
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
}

function save_map(){
  var data = {};
  var pos;
  if(latd != null && lng != null)
    pos = new google.maps.LatLng(latd, lng);
  else
    pos = latlng;

  latlng = pos;
  map.setCenter(latlng);
  load_map();
  $('#hidden_map_position').val(latlng);
  var position =  $('#hidden_map_position').val();
  data['position'] = position;
  data['title'] = $('#map_title').val();
  data['description'] = $('#map_description').val();
  data['width'] = $('#map_w').val();
  data['height'] = $('#map_h').val();

  save_to_db(data);


}
function save_to_db(data){
  if(id == 0)
    $.post(CONFIG.get('URL')+'settings/save_map',{action: 'save_map', data:data }, function(response, status){
      location.reload();
    });
  else{
    data['id'] = id;
    $.post(CONFIG.get('URL')+'settings/update_map',{action: 'update_map', data:data }, function(response, status){
      location.reload();
    });
  }
}
function load_map(){

  google.maps.event.addListener(marker, 'dragend', function (event) {
    latd = this.getPosition().lat();
    lng = this.getPosition().lng();
  });
}

function show_add_maps(){
  latlng = null;
  $('#map_title').val('');
  $('#map_description').val('');
  $('#map_w').val('');
  $('#map_h').val('');
  $('#google_maps').modal('show');
}

function edit_map(m_id,position,title,description,width,height){
  position = position.replace('(','');
  position = position.replace(')','');
  var arr = position.split(',');

  latlng = new google.maps.LatLng($.trim(arr[0]),$.trim(arr[1]));
  id = m_id;

  $('#google_maps').modal('show');
  $('#map_title').val(title);
  $('#map_description').val(description);
  $('#map_w').val(width);
  $('#map_h').val(height);
}

function delete_map(id){
  $('#hidden_map_id').val(id);
  $('#delete').modal('show');
}
function delete_map_db(){
  $.post(CONFIG.get('URL')+'settings/delete_map',{action: 'delete_map', id: $('#hidden_map_id').val() }, function(response, status){
    location.reload();
  });
}

function saveSiteMap(){
  $("#xml-loading").modal({
    keyboard : false,
    backdrop : 'static',
  });

  var data ={
    "sitemap" : $("#switch-xml-sitemap").is(":checked") ? "ON" : "OFF",
    "auto_ping_google" : $("#sitemap-auto-notify-google").is(":checked") ? "ON" : "OFF",
  };

  $.post(CONFIG.get('URL')+'settings/sitemap_processor/',{
    action : "save-sitemap",
    data : JSON.stringify(data),
  },function(response) {

    if (cms_function.isJSON(response)) {
      var parsedResponse = JSON.parse(response);

      if (typeof parsedResponse != 'undefined') {
        $status = "";
        if (parsedResponse['status']=='success') {
          $status = "gritter-success";
        }else if(parsedResponse['status']=='warning'){
          $status = "gritter-warning";
        }else{
          $status = "gritter-error";
        }
        notification("XML SiteMap", parsedResponse['message'], $status);

        if (parsedResponse['data']['google']['status']) {
          $("#notification-google").html('<strong>'+ parsedResponse['data']['google']['message'] +'</strong>').removeClass('text-error').addClass('text-success');
        }else{
          $("#notification-google").html('<strong>'+ parsedResponse['data']['google']['message'] +'</strong>').removeClass('text-success').addClass('text-error');
        }

        if (typeof parsedResponse['data']['google']['last-ping'] != 'undefined') {
          $("#notification-google-last-ping").html("<em>(Last ping: " + parsedResponse['data']['google']['last-ping'] + ")</em>");
        }else{
          $("#notification-google-last-ping").html("");
        }
      }else{
        notification("XML SiteMap", "Something went wrong while saving SiteMap", "gritter-error");
        $("#notification-google").html('<strong>Server Error</strong>').removeClass('text-error text-success').addClass('text-error');
        
      }
    }else{
      notification("Response Error: ", "Unable to parse response", "gritter-error");
    }

    $("#xml-loading").modal('hide');
  });
}
