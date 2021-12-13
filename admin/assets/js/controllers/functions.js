
/**
* Base URL
*/

var existUsername = false;

/**
*Get the value by id
*/
function getVal(id){
	return jQuery('#'+id).val();
}

/**
*Set the value by id
*/
function setVal(id,val){
	return jQuery('#'+id).val(val);	
}

/**
*Get the text by id
*/
function getText(id){
	return jQuery('#'+id).text();
}

/**
*Set the text by id
*/
function setText(id,val){
	return jQuery('#'+id).text(val);	
}



/**
* if field is empty
*/
function empty(id){
	if(getVal(id)==''){
		return true;
	}
	return false;
}


function getMessage(message, type,errorId){
 return "<div id='"+errorId+"' class='alert alert-block alert-"+type+"'><a class='close' data-dismiss='alert' href='#'>x</a> "+message+"</div><br /><br />";
}

function alertMessage(message,type,errorId){
  return "<div id='"+errorId+"' style='margin-bottom:10px;'><div class='alert alert-"+type+"'><button type='button' class='close' data-dismiss='alert'><i class='icon-remove'></i></button>"+message+"</div>";
}

/**
*validate email
*/
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function redirect(page){
	return window.location.href=page;
}

jQuery('#msgAlert').delay(2000).fadeOut(400);


function hoverRow(id,page){
  $('tr#row'+id+' td div div .row_actions_'+page +  ' a').show();
  $('tr#row'+id+' td div div .row_actions_'+page +  ' a').css({'visibility':'visible'}); 
}

function outRow(id,page){
  $('tr#row'+id+' td div div .row_actions_'+page +  ' a').hide();
  $('tr#row'+id+' td div div .row_actions_'+page +  ' a').css({'visibility':'hidden'});
}


$('.table tbody tr').on('mouseover mouseleave',function(evt){
  $('.row_actions_users').toggleClass('show');
  evt.preventDefault();
});


$("#chkSelectAll_Item").click(function() {      

 if(this.checked){     
  $('input:checkbox[name="item[]"]').each(function(){
    $("#item_"+$(this).val()).prop("checked", true);
  });
}else{
 $('input:checkbox[name="item[]"]').each(function(){
   $("#item_"+$(this).val()).prop("checked", false);
 });
}   

});

$(".table tbody tr").css("background-color", function(index) {
  return index%2===0?"#fdfdfd":"transparent";
});

function redirect(page){
  return window.location.href=page;
}

$(function(){
  // $(".date-picker").datepicker();
  //$(".form-date-picker").datepicker();
  $("[data-rel=tooltip]").tooltip();
  $('[data-rel=popover]').popover({container:'body'});
  //$(".form-select").chosen();

  
  $(".input_alphanumber_only").on('keydown keyup', function(e){
    var name = $(this).val();
    name = name.replace(/[^a-z+A-Z+0-9\s-]/g, '');
    $(this).val(name);
  });
  $(".input_number_only").change(function(e){
    var name = $(this).val();
    name = name.replace(/[^0-9+.]/g, '');
    $(this).val(name);
    return;

    var charCode = (e.which) ? e.which : event.keyCode;
    if (
      charCode>31 && 
      (charCode<48 || charCode>57) && 
      charCode != 45 && 
      charCode != 190 && 
      charCode != 37 && 
      charCode != 38 && 
      charCode != 39 && 
      charCode != 40 &&
      charCode != 96 &&
      charCode != 97 &&
      charCode != 98 &&
      charCode != 99 &&
      charCode != 100 &&
      charCode != 101 &&
      charCode != 102 &&
      charCode != 103 &&
      charCode != 104 &&
      charCode != 105
      )  {
      return false;
  }
  return true;
});
  $(".input_letters_only").keydown(function(e){
    var k;
    document.all ? k = e.keyCode : k = e.which;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8  || k == 32 || k == 9 || k == 36 || k == 35);
  });
});

function convert_to_datepicker(string){
  var date = string.split('-');

  return date[0]+'/'+date[1]+'/'+date[2];
}

function sort_gallery_image(){
  var data = [];
  $('.template-download').each(function(i){
    var sorts = {};
      //alert($(this).find('.hidden_gallery_image_id').val() +' = '+ i);
      sorts['id'] = $(this).find('.hidden_gallery_image_id').val();
      sorts['index'] = i;
      data.push(sorts);
    });
  $.post(CONFIG.get('URL')+'products/sort_product_image',{action:'sort',data:data}, function(response,status){
  });
} 
function convertToSlug(Text){
  // console.log(Text);
  return Text
  .toLowerCase()
  .replace(/ /g,'-')
  // .replace(/[^\w-]+/g,'')
  ;
}
function slugify(Text){
  return cms_function.fn.remove_accents(Text.trim('-').toLowerCase().replace(/ /g,'-'));
}
function toCurrency(value){
  return accounting.formatMoney( value );
}
function toUnit(value, unit){
  var v = accounting.unformat( value );
  return v.toString() + unit;
}

function currency_field(field){
  if (field) {
    number_field(field);
    field.unbind('change');
    field.change(function(e){
      field.val( toCurrency(field.val()) );
    })
  }
}
function unit_field(field, unit){
  if (field) {
    number_field(field);
    field.unbind('change');
    field.change(function(e){
      field.val( toUnit(field.val(), unit));
    })
  }
}
function number_field(field){
  if (field) {
    field.keypress(function(e){
      return isNumeric(e);
    })
  }
}
function isNumber(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode; 
  console.log(charCode);
  if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
    return false;
  }
  return true;
}
function isNumeric(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode; 
  console.log(charCode);
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}
function notification(_title, _text, _type, _sticky, _time){
  var type = typeof _type != "undefined" ? _type : "";
  var text = typeof _text != "undefined" ? _text : "";
  var title = typeof _title != "undefined" ? _title : "";
  var sticky = typeof _sticky != "undefined" ? _sticky : false;
  var time = typeof _time != "undefined" ? _time : 2000;

  return $.gritter.add({
    title: title,
    text: text,
    sticky: sticky,
    class_name: type,
    time: time,
  });
}
function notification_remove(){
  $.gritter.removeAll();
}
function strip_tag(html){
 var tmp = document.createElement("DIV");
 tmp.innerHTML = html;
 return tmp.textContent || tmp.innerText || "";
}
function image_loader($image){
  var img = new Image();
  img.onload = function () {
    $image.attr('src', img.src);
  }
  img.onerror = function () {
    var img2 = new Image();

    img2.onload = function () {
      $image.attr('src', img2.src);
    }
    img2.onerror = function () {
      console.log("Unable to load Image: " + img2.src)
    }

    img2.src = $image.attr('alt')
  }
  img.src = $image.attr('src');
}

var cms_function = {
  isJSON : function(str){
    return /^[\],:{}\s]*$/.test(str.replace(/\\["\\\/bfnrtu]/g, '@').
      replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
      replace(/(?:^|:|,)(?:\s*\[)+/g, ''));
  },
  default_image : CONFIG.get('FRONTEND_URL').replace(/\/$/, "") + "/thumbnails/78x66/uploads/default.png",
  elements : {
    overlay : $('<div class="overlay-container"></div>'),
    overlay_loading : $('<div class="progress"><div class="bar bar-success" style="width: 0%;"></div></div>'),
  },
  fn : {
    image_loader : function(element, _src, fn_callback){
      var img = new Image();
      img.onload = function () {
        element.attr('src', _src);

        if (typeof fn_callback != 'undefined' && typeof fn_callback == 'function') {
          fn_callback(element, _src);
        }
      }
      img.onerror = function () {
        element.attr('src', cms_function.default_image);
        console.log("Unable to load image: " + _src);
      }
      img.src = _src;
    },
    url_loader : function(url, callback){
      $.ajax(url,{
        statusCode: {
          404: function() {
            callback(false);
          },
        }
      }).done(function(response,status){
        callback(true);
      });
    },
    remove_accents : function(str){
      var s = str;
      var unwanted_array = {
        'ª' : 'a', 'º' : 'o','À' : 'A', 'Á' : 'A','Â' : 'A', 'Ã' : 'A','Ä' : 'A', 'Å' : 'A','Æ' : 'AE','Ç' : 'C','È' : 'E', 'É' : 'E','Ê' : 'E', 'Ë' : 'E','Ì' : 'I', 'Í' : 'I','Î' : 'I', 'Ï' : 'I','Ð' : 'D', 'Ñ' : 'N','Ò' : 'O', 'Ó' : 'O','Ô' : 'O', 'Õ' : 'O','Ö' : 'O', 'Ù' : 'U','Ú' : 'U', 'Û' : 'U','Ü' : 'U', 'Ý' : 'Y','Þ' : 'TH','ß' : 's','à' : 'a', 'á' : 'a','â' : 'a', 'ã' : 'a','ä' : 'a', 'å' : 'a','æ' : 'ae','ç' : 'c','è' : 'e', 'é' : 'e','ê' : 'e', 'ë' : 'e','ì' : 'i', 'í' : 'i','î' : 'i', 'ï' : 'i','ð' : 'd', 'ñ' : 'n','ò' : 'o', 'ó' : 'o','ô' : 'o', 'õ' : 'o','ö' : 'o', 'ø' : 'o','ù' : 'u', 'ú' : 'u','û' : 'u', 'ü' : 'u','ý' : 'y', 'þ' : 'th','ÿ' : 'y', 'Ø' : 'O','Ā' : 'A', 'ā' : 'a','Ă' : 'A', 'ă' : 'a','Ą' : 'A', 'ą' : 'a','Ć' : 'C', 'ć' : 'c','Ĉ' : 'C', 'ĉ' : 'c','Ċ' : 'C', 'ċ' : 'c','Č' : 'C', 'č' : 'c','Ď' : 'D', 'ď' : 'd','Đ' : 'D', 'đ' : 'd','Ē' : 'E', 'ē' : 'e','Ĕ' : 'E', 'ĕ' : 'e','Ė' : 'E', 'ė' : 'e','Ę' : 'E', 'ę' : 'e','Ě' : 'E', 'ě' : 'e','Ĝ' : 'G', 'ĝ' : 'g','Ğ' : 'G', 'ğ' : 'g','Ġ' : 'G', 'ġ' : 'g','Ģ' : 'G', 'ģ' : 'g','Ĥ' : 'H', 'ĥ' : 'h','Ħ' : 'H', 'ħ' : 'h','Ĩ' : 'I', 'ĩ' : 'i','Ī' : 'I', 'ī' : 'i','Ĭ' : 'I', 'ĭ' : 'i','Į' : 'I', 'į' : 'i','İ' : 'I', 'ı' : 'i','Ĳ' : 'IJ','ĳ' : 'ij','Ĵ' : 'J', 'ĵ' : 'j','Ķ' : 'K', 'ķ' : 'k','ĸ' : 'k', 'Ĺ' : 'L','ĺ' : 'l', 'Ļ' : 'L','ļ' : 'l', 'Ľ' : 'L','ľ' : 'l', 'Ŀ' : 'L','ŀ' : 'l', 'Ł' : 'L','ł' : 'l', 'Ń' : 'N','ń' : 'n', 'Ņ' : 'N','ņ' : 'n', 'Ň' : 'N','ň' : 'n', 'ŉ' : 'n','Ŋ' : 'N', 'ŋ' : 'n','Ō' : 'O', 'ō' : 'o','Ŏ' : 'O', 'ŏ' : 'o','Ő' : 'O', 'ő' : 'o','Œ' : 'OE','œ' : 'oe','Ŕ' : 'R','ŕ' : 'r','Ŗ' : 'R','ŗ' : 'r','Ř' : 'R','ř' : 'r','Ś' : 'S','ś' : 's','Ŝ' : 'S','ŝ' : 's','Ş' : 'S','ş' : 's','Š' : 'S', 'š' : 's','Ţ' : 'T', 'ţ' : 't','Ť' : 'T', 'ť' : 't','Ŧ' : 'T', 'ŧ' : 't','Ũ' : 'U', 'ũ' : 'u','Ū' : 'U', 'ū' : 'u','Ŭ' : 'U', 'ŭ' : 'u','Ů' : 'U', 'ů' : 'u','Ű' : 'U', 'ű' : 'u','Ų' : 'U', 'ų' : 'u','Ŵ' : 'W', 'ŵ' : 'w','Ŷ' : 'Y', 'ŷ' : 'y','Ÿ' : 'Y', 'Ź' : 'Z','ź' : 'z', 'Ż' : 'Z','ż' : 'z', 'Ž' : 'Z','ž' : 'z', 'ſ' : 's','Ș' : 'S', 'ș' : 's','Ț' : 'T', 'ț' : 't','€' : 'E','£' : '','Ơ' : 'O', 'ơ' : 'o','Ư' : 'U', 'ư' : 'u','Ầ' : 'A', 'ầ' : 'a','Ằ' : 'A', 'ằ' : 'a','Ề' : 'E', 'ề' : 'e','Ồ' : 'O', 'ồ' : 'o','Ờ' : 'O', 'ờ' : 'o','Ừ' : 'U', 'ừ' : 'u','Ỳ' : 'Y', 'ỳ' : 'y','Ả' : 'A', 'ả' : 'a','Ẩ' : 'A', 'ẩ' : 'a','Ẳ' : 'A', 'ẳ' : 'a','Ẻ' : 'E', 'ẻ' : 'e','Ể' : 'E', 'ể' : 'e','Ỉ' : 'I', 'ỉ' : 'i','Ỏ' : 'O', 'ỏ' : 'o','Ổ' : 'O', 'ổ' : 'o','Ở' : 'O', 'ở' : 'o','Ủ' : 'U', 'ủ' : 'u','Ử' : 'U', 'ử' : 'u','Ỷ' : 'Y', 'ỷ' : 'y','Ẫ' : 'A', 'ẫ' : 'a','Ẵ' : 'A', 'ẵ' : 'a','Ẽ' : 'E', 'ẽ' : 'e','Ễ' : 'E', 'ễ' : 'e','Ỗ' : 'O', 'ỗ' : 'o','Ỡ' : 'O', 'ỡ' : 'o','Ữ' : 'U', 'ữ' : 'u','Ỹ' : 'Y', 'ỹ' : 'y','Ấ' : 'A', 'ấ' : 'a','Ắ' : 'A', 'ắ' : 'a','Ế' : 'E', 'ế' : 'e','Ố' : 'O', 'ố' : 'o','Ớ' : 'O', 'ớ' : 'o','Ứ' : 'U', 'ứ' : 'u','Ạ' : 'A', 'ạ' : 'a','Ậ' : 'A', 'ậ' : 'a','Ặ' : 'A', 'ặ' : 'a','Ẹ' : 'E', 'ẹ' : 'e','Ệ' : 'E', 'ệ' : 'e','Ị' : 'I', 'ị' : 'i','Ọ' : 'O', 'ọ' : 'o','Ộ' : 'O', 'ộ' : 'o','Ợ' : 'O', 'ợ' : 'o','Ụ' : 'U', 'ụ' : 'u','Ự' : 'U', 'ự' : 'u','Ỵ' : 'Y', 'ỵ' : 'y','ɑ' : 'a','Ǖ' : 'U', 'ǖ' : 'u','Ǘ' : 'U', 'ǘ' : 'u','Ǎ' : 'A', 'ǎ' : 'a','Ǐ' : 'I', 'ǐ' : 'i','Ǒ' : 'O', 'ǒ' : 'o','Ǔ' : 'U', 'ǔ' : 'u','Ǚ' : 'U', 'ǚ' : 'u','Ǜ' : 'U', 'ǜ' : 'u',
      }

      for (var i = 0, len = str.length; i < len; i++) {
        if (typeof unwanted_array[str[i]] != 'undefined') {
          s=s.replace(str[i], unwanted_array[str[i]]);
        }
      }
      return s;
    },
    bytesToSize : function (bytes) {
       var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
       if (bytes == 0) return '0 Byte';
       var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
       return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    },
  },
  form :{
    validate : function(field){
      /*
      -this function will check of the fields is empty
      */

      if (field !== undefined) {
        if (field.prop("tagName") == 'INPUT') {
          var text_fields = ['text', 'email', 'number', 'hidden', 'password'];

          if (text_fields.indexOf(field.prop('type')) >= 0) {
            return field.val() != "";
          }else{
            return true;
          }
        }else if (field.prop("tagName") == 'SELECT') {
          return field.val() != "";
        }else if (field.prop("tagName") == 'TEXTAREA') {
          return field.val() != "";
        }

        return true;
      }else{
        return false;
      }
    }
  }
}

var cms_cookie = {
  set : function(name, value, duration){
    var additional_day = duration!==undefined ? duration : 1; // default day count
    var base_time = 86400; // 86400 = one day

    var d = new Date();
    d.setDate(d.getDate())
    d.setSeconds(d.getSeconds() + ( base_time * additional_day));

    document.cookie = name + " = "+ value +"; expires = " + d.toUTCString() + "; path=/;";
  },
  get : function(name){
    var cookie_name = name;
    var _value = "";

    var _t = document.cookie.split(";");

    for (var i = 0; i < _t.length; i++) {
      var x = _t[i].split("=");

      if (x[0].trim() == cookie_name) {
        _value = x[1].trim();
      }
    }

    return _value;
  },
  delete : function(name){
    var d = new Date();
    document.cookie = name + " = deleted; expires = " + d.toUTCString() + "; path=/;";
  },
  is : function(name){
    return this.get(name) ? true : false;
  }
}

// $str = strtr( $str, $unwanted_array );
//   $str = preg_replace("/[\s\W]+/", "-", $str);
//   return $str;