(function(){tinymce.PluginManager.requireLangPack('perfwed');tinymce.create('tinymce.plugins.PerfWed',{init:function(ed,url){ed.addCommand('mcePerfWed',function(){ed.windowManager.open({file:url+'/dialog.php',width:450+parseInt(ed.getLang('example.delta_width',0)),height:300+parseInt(ed.getLang('example.delta_height',0)),inline:1},{plugin_url:url,some_custom_arg:'custom arg'})});ed.addButton('perfwed',{title:'perfwed.desc',cmd:'mcePerfWed',image:url+'/img/example.gif'});ed.onNodeChange.add(function(ed,cm,n){cm.setActive('example',n.nodeName=='IMG')})},createControl:function(n,cm){return null},getInfo:function(){return{longname:'Example plugin',author:'Some author',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',version:"1.0"}}});tinymce.PluginManager.add('perfwed',tinymce.plugins.PerfWed)})();