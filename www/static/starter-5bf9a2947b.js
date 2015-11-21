"use strict";!function(){window.BaseRouter={initialize:function(){this.init_views()},init_views:function(){},current:function(){var t,n=this,i=location.pathname.slice(1),e=_.pairs(n.routes),r=null,a=null;return t=_.find(e,function(t){return r=_.isRegExp(t[0])?t[0]:n._routeToRegExp(t[0]),r.test(i)}),t&&(a=n._extractParameters(r,i),r=t[1]),{route:r,fragment:i,params:a}},load_positions:function(){var t=this;return $.post(location.pathname,{},function(n){var i=n.blocks;for(var e in i)i.hasOwnProperty(e)&&$('[data-block="'+e+'"]').html(i[e]);var r=n.views;for(var a in r)r.hasOwnProperty(a)&&$('[data-view="'+a+'"]').replaceWith(r[a]);App.trigger("Page:loaded",{page:location.pathname.split("/").slice(-1)[0],response:n}),t.init_views()},"json")},reload:function(){this.load_positions()},extend:function(t){return $.extend(!0,{},this,t)},go_to:function(t){var n=arguments.length<=1||void 0===arguments[1]?{trigger:!0}:arguments[1];this.navigate(t,n)}}}();
"use strict";!function(e){e.App={},_.extend(App,Backbone.Events),Object.defineProperty(App,"start",{value:function(){this.register_events(),$.ajaxSetup({beforeSend:function(){this.url+=(this.url.indexOf("?")>-1?"&":"?")+"ajax=1";var e=this.data||"",t=App.parse_url_params(this.url.split("?")[1]),r=App.parse_url_params(e);this.url+="&token="+App.get_token(t,r)},error:function(){NotificationView.display("Request completed with an error","error")}}),jSmart.prototype.registerPlugin("function","include",function(e,t){var r=e.__get("file",null,0);if(!t.inclusions)throw new Error("data must contain inclusions section");var n=t.inclusions[r]||"",i=new jSmart(n);return i.fetch(t)}),jSmart.prototype.registerPlugin("modifier","strpos",function(e,t){e.indexOf(t)}),Backbone.history.start({pushState:!0,silent:!0})}}),Object.defineProperty(App,"register_events",{value:function(){$(document).on("click","a[href]:not(.link--external)",function(e){var t=this.getAttribute("href"),r=-1==t.indexOf("http")&&-1==t.indexOf("www")&&-1==t.indexOf("javascript");r&&(e.preventDefault(),Router.navigate(t,{trigger:!0}))}),$(document).on("submit","form",function(e){e.preventDefault()})}}),Object.defineProperty(App,"get_token",{value:function(e,t){var r=(Cookie.get_cookie("user")||"")+(Cookie.get_cookie("pfm_session_id")||"")+JSON.stringify($.extend(e,t));return CryptoJS.MD5(r).toString()}}),Object.defineProperty(App,"parse_url_params",{value:function(t){"undefined"==typeof t&&(t=e.location.search);var t=t.split("#")[0],r={},n=t.split("?")[1];if(n||t.search("=")!==!1&&(n=t),n)for(var i=n.split("&"),o=0;o<i.length;o++){var a=i[o].split("="),s=a[0],p=a[1]||"";r[s]=decodeURIComponent(p.replace(/\+/g," "))}return r}}),App.start()}(window);
"use strict";!function(){window.Cookie={get_cookie:function(e){var o=document.cookie.match(new RegExp("(?:^|; )"+e.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,"\\$1")+"=([^;]*)"));return o?decodeURIComponent(o[1]):void 0},set_cookie:function(e,o,i){i=i||{};var t=i.expires;if("number"==typeof t&&t){var n=new Date;n.setTime(n.getTime()+1e3*t),t=i.expires=n}t&&t.toUTCString&&(i.expires=t.toUTCString()),o=encodeURIComponent(o);var r=e+"="+o;for(var c in i){r+="; "+c;var s=i[c];s!==!0&&(r+="="+s)}document.cookie=r},delete_cookie:function(e){this.set_cookie(e,"",{expires:-1})}}}();
"use strict";!function(){window.Helpers={object_loaded:function(n){return new Promise(function(e,t){window[n]&&e();var r=0,o=setInterval(function(){window[n]?(clearInterval(o),e()):r++>300&&(clearInterval(o),t())},50)})},objects_loaded:function(){var n=this,e=arguments.length<=0||void 0===arguments[0]?[]:arguments[0],t=new Promise(function(n){return n()});return e.reduce(function(e,t){return e.then(function(){return n.object_loaded(t)})},t)}}}();
"use strict";_.defer(function(){window.TemplatesCollection=Backbone.Collection.extend({model:TemplateModel,url:function(){return"/rest/templates"+location.pathname},parse:function(e){return e.title&&(document.title=e.title),e.templates}})});
"use strict";!function(){window.TemplateModel=Backbone.Model.extend({defaults:{name:null,html:null,data:null}})}();
"use strict";!function(){window.UserModel=Backbone.Model.extend({defaults:{id:null,login:null,password:null,credentials:null,remember_hash:null,deleted:null}})}();
"use strict";!function(){window.BaseView=Backbone.View.extend({render:function(){var e=templates.findWhere({name:this.name}),t=e.get("data"),n=new jSmart(e.get("html"));return this.$el.html(n.fetch(t))}})}();
"use strict";!function(){var e=Backbone.Router.extend(BaseRouter.extend({routes:{},init_views:function(){}}));window.Router=new e}();
//# sourceMappingURL=maps/starter-5bf9a2947b.js.map