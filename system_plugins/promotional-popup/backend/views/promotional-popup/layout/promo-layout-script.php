<script>
  var promotional_popup_options = {
    shown   : <?php echo $promo_settings->shown ? 'true' : 'false' ?>,
    date_modified   : "<?php echo $promo_settings->date_modified ? $promo_settings->date_modified : date("Y:m:d:H:i:s") ?>",
    closed  : false,
    layout  : {
      enable    : "<?php echo $promo_settings->layout->enable ?>",
      template  : "<?php echo $promo_settings->layout->template ?>",
    },
    timing  : {
      type    : "<?php echo $promo_settings->timing->type ?>",
      pages   : "<?php echo gettype($promo_settings->timing->pages) == 'array' ? implode(", ", $promo_settings->timing->pages) : '' ?>",
      trigger : "<?php echo $promo_settings->timing->trigger ?>",
      freq    : "<?php echo $promo_settings->timing->freq ?>",
      signup  : "<?php echo $promo_settings->timing->signup ?>",
      mobile  : "<?php echo $promo_settings->timing->mobile ?>",
      time    : "<?php echo $promo_settings->timing->time ?>",
      scroll  : "<?php echo $promo_settings->timing->scroll ?>",
    },
    page_info  : {
      type : "<?php echo Session::get('page_type') ?>"
    }
  }

  $(document).ready(function(){
    promo_update();

    $(".promotional-popup-close").click(function(){
      promotional_popup_options.closed = true;
      $('.promotional-banner-container').fadeOut();
      cookie_set_shown();
    });

    $(".promotional-banner-container").submit(function(e){
      e.preventDefault();
      promo_form_submit($(this))
    });
  });

  function promo_initialize_popup(){
    if (typeof promotional_popup_options.timing.trigger !== undefined) {
      if (promotional_popup_options.timing.trigger == 'timing-time') {
        if (typeof promotional_popup_options.timing.time !== undefined) {
          promo_initialize_time(promotional_popup_options.timing.time);
        }
      }else if(promotional_popup_options.timing.trigger == 'timing-scroll'){
        if (typeof promotional_popup_options.timing.scroll !== undefined) {
          promo_initialize_scroll(promotional_popup_options.timing.scroll);
        }
      }else{
        if (typeof promotional_popup_options.timing.time !== undefined) {
          promo_initialize_time(promotional_popup_options.timing.time);
        }
        if (typeof promotional_popup_options.timing.scroll !== undefined) {
          promo_initialize_scroll(promotional_popup_options.timing.scroll);
        }
      }
    }
  }
  function promo_initialize_time(trigger_time){
    setTimeout(function(){
      promo_pop()
    }, trigger_time)
  }
  var promo_scroll_action = null;
  function promo_initialize_scroll(trigger_percentage){
    if (promo_scroll_action != null) {
      promo_scroll_action.off('scroll', "**" );
      promo_scroll_action.unbind('scroll');
    }
    promo_scroll_action = $(window).scroll(function(){
      /* source: https://www.sitepoint.com/jquery-capture-vertical-scroll-percentage/ */

      var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
      var scrolltrigger = (trigger_percentage/100)*100;
      var scroll_percent = (wintop/(docheight-winheight))*100;

      if  (scroll_percent > scrolltrigger) {
        if (!promotional_popup_options.shown) {
          promo_pop();
        }
      }
    });
  }
  function promo_pop(){
    if (!promotional_popup_options.shown && !promotional_popup_options.closed) {
      promotional_popup_options.shown = true;
      $(".promotional-banner-container").fadeIn(500);
    }
  }

  function cookie_set_shown(){
    var additional_day = 1; // default day count
    var base_time = 86400; // 86400 = one day

    if (promotional_popup_options.timing.freq !== undefined) {
      if (promotional_popup_options.timing.freq == 'frequency-always') {
        base_time = 1;
      }else if (promotional_popup_options.timing.freq == 'frequency-never') {
        additional_day = (356 * 2); // set to 2 years
      }else if (promotional_popup_options.timing.freq == 'frequency-day-next') {
        // default 1 day
      }else if (promotional_popup_options.timing.freq == 'frequency-day-30') {
        additional_day = 30; // set to 30 days
      }else if (promotional_popup_options.timing.freq == 'frequency-week-1') {
        additional_day = 7; // set to 1 week
      }else if (promotional_popup_options.timing.freq == 'frequency-week-2') {
        additional_day = 14; // set to 1 week
      }
    }

    var d = new Date();
    d.setSeconds(d.getSeconds() + ( base_time * additional_day));

    document.cookie = "promotional-popup-shown = true; expires = " + d.toUTCString() + "; path=/;";
    document.cookie = "promotional-popup-freq = "+ promotional_popup_options.timing.freq +"; expires = " + d.toUTCString() + "; path=/;";

    $('.promotional-banner-container').fadeOut();
    promo_update();
  }
  function cookie_set_signup(){
    var additional_day = 1; // default day count
    var base_time = 86400; // 86400 = one day

    if (promotional_popup_options.timing.signup == 'Y') {
      additional_day = (356 * 2); // set to 2 years

      var d = new Date();
      d.setDate(d.getDate())
      d.setSeconds(d.getSeconds() + ( base_time * additional_day));

      document.cookie = "promotional-signup = Y; expires = " + d.toUTCString() + "; path=/;";
    }
  }

  function cookie_check_shown(){
    if (promotional_popup_options.closed) {
      promotional_popup_options.shown = true;
      return promotional_popup_options.shown;
    }

    var cookie_shown = 'promotional-popup-shown';
    var cookie_freq = 'promotional-popup-freq';
    var cookie_mod  = 'promotional-modified';
    var cookie_sign = 'promotional-signup';
    var _shown = "";
    var _freq = "";
    var _mod  = "";
    var _sign = "";

    var _t = document.cookie.split(";");
    var f = false; // flag

    for (var i = 0; i < _t.length; i++) {
      var x = _t[i].split("=");

      if (x[0].trim() == cookie_shown) {
        _shown = x[1].trim();
      }
      if (x[0].trim() == cookie_freq) {
        _freq = x[1].trim();
      }
      if (x[0].trim() == cookie_mod) {
        _mod = x[1].trim();
      }
      if (x[0].trim() == cookie_sign) {
        _sign = x[1].trim();
      }
    }

    if (_shown != "") {
      promotional_popup_options.shown = true;
    }

    if (promotional_popup_options.timing.signup == 'Y' && _sign == 'Y') {
      promotional_popup_options.shown = true;
    }

    if (_mod != promotional_popup_options.date_modified) { // if promotional popup settings is modified
      var d = new Date();
      d.setDate(d.getDate() - 1);

      document.cookie = "promotional-popup-shown = true; expires = " + d.toUTCString() + "; path=/;";
      document.cookie = "promotional-popup-freq = "+ promotional_popup_options.timing.freq +"; expires = " + d.toUTCString() + "; path=/;";
      document.cookie = "promotional-signup = Y; expires = " + d.toUTCString() + "; path=/;";

      d.setSeconds(d.getSeconds() + ( 86400 * 356)); //expire date_modified til 356 days 
      document.cookie = "promotional-modified = "+ promotional_popup_options.date_modified +"; expires = " + d.toUTCString() + "; path=/;";
      promotional_popup_options.shown = false;
    }

    return promotional_popup_options.shown;
  }

  function promo_update(){
    if (promotional_popup_options.layout.enable == 'Y') {
      var pages = promotional_popup_options.timing.pages.split(",").map(function(v){ return v.trim(); });
      var curr  = promotional_popup_options.page_info.type;

      if (promotional_popup_options.timing.type != 'page-all' && pages.indexOf(curr) < 0) {
        return;
      }

      if (promo_is_mobile() && promotional_popup_options.timing.mobile == 'N') {
        return;
      }

      setTimeout(function(){
          if (cookie_check_shown()) {
            promo_update();
          }else{
            promo_initialize_popup();
          }
        }, 1000)
    }
  }

  function promo_form_submit(the_form){
    var _d = {};
    $.each(the_form.serializeArray(), function(k, v){
      _d[v.name] = v.value;
    });
    
    $.post(the_form.attr('action'), _d,
    function(response) {
      if (response == '1') {
        cookie_set_signup();
        cookie_set_shown();
      }else{

      }
    });
  }

  function promo_is_mobile() {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
  };

</script>