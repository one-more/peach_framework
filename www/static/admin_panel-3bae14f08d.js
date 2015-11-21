"use strict";!function(e,t,n){function r(e,t){return typeof e===t}function o(){var e,t,n,o,a,i,s;for(var l in C)if(C.hasOwnProperty(l)){if(e=[],t=C[l],t.name&&(e.push(t.name.toLowerCase()),t.options&&t.options.aliases&&t.options.aliases.length))for(n=0;n<t.options.aliases.length;n++)e.push(t.options.aliases[n].toLowerCase());for(o=r(t.fn,"function")?t.fn():t.fn,a=0;a<e.length;a++)i=e[a],s=i.split("."),1===s.length?S[s[0]]=o:(!S[s[0]]||S[s[0]]instanceof Boolean||(S[s[0]]=new Boolean(S[s[0]])),S[s[0]][s[1]]=o),y.push((o?"":"no-")+s.join("-"))}}function a(e){var t=x.className,n=S._config.classPrefix||"";if(b&&(t=t.baseVal),S._config.enableJSClass){var r=new RegExp("(^|\\s)"+n+"no-js(\\s|$)");t=t.replace(r,"$1"+n+"js$2")}S._config.enableClasses&&(t+=" "+n+e.join(" "+n),b?x.className.baseVal=t:x.className=t)}function i(e){return e.replace(/([a-z])-([a-z])/g,function(e,t,n){return t+n.toUpperCase()}).replace(/^-/,"")}function s(e,t){return!!~(""+e).indexOf(t)}function l(){return"function"!=typeof t.createElement?t.createElement(arguments[0]):b?t.createElementNS.call(t,"http://www.w3.org/2000/svg",arguments[0]):t.createElement.apply(t,arguments)}function u(e,t){return function(){return e.apply(t,arguments)}}function f(e,t,n){var o;for(var a in e)if(e[a]in t)return n===!1?e[a]:(o=t[e[a]],r(o,"function")?u(o,n||t):o);return!1}function c(e){return e.replace(/([A-Z])/g,function(e,t){return"-"+t.toLowerCase()}).replace(/^ms-/,"-ms-")}function d(){var e=t.body;return e||(e=l(b?"svg":"body"),e.fake=!0),e}function p(e,n,r,o){var a,i,s,u,f="modernizr",c=l("div"),p=d();if(parseInt(r,10))for(;r--;)s=l("div"),s.id=o?o[r]:f+(r+1),c.appendChild(s);return a=l("style"),a.type="text/css",a.id="s"+f,(p.fake?p:c).appendChild(a),p.appendChild(c),a.styleSheet?a.styleSheet.cssText=e:a.appendChild(t.createTextNode(e)),c.id=f,p.fake&&(p.style.background="",p.style.overflow="hidden",u=x.style.overflow,x.style.overflow="hidden",x.appendChild(p)),i=n(c,e),p.fake?(p.parentNode.removeChild(p),x.style.overflow=u,x.offsetHeight):c.parentNode.removeChild(c),!!i}function m(t,r){var o=t.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(c(t[o]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var a=[];o--;)a.push("("+c(t[o])+":"+r+")");return a=a.join(" or "),p("@supports ("+a+") { #modernizr { position: absolute; } }",function(e){return"absolute"==getComputedStyle(e,null).position})}return n}function h(e,t,o,a){function u(){c&&(delete F.style,delete F.modElem)}if(a=r(a,"undefined")?!1:a,!r(o,"undefined")){var f=m(e,o);if(!r(f,"undefined"))return f}for(var c,d,p,h,v,g=["modernizr","tspan"];!F.style;)c=!0,F.modElem=l(g.shift()),F.style=F.modElem.style;for(p=e.length,d=0;p>d;d++)if(h=e[d],v=F.style[h],s(h,"-")&&(h=i(h)),F.style[h]!==n){if(a||r(o,"undefined"))return u(),"pfx"==t?h:!0;try{F.style[h]=o}catch(y){}if(F.style[h]!=v)return u(),"pfx"==t?h:!0}return u(),!1}function v(e,t,n,o,a){var i=e.charAt(0).toUpperCase()+e.slice(1),s=(e+" "+N.join(i+" ")+i).split(" ");return r(t,"string")||r(t,"undefined")?h(s,t,o,a):(s=(e+" "+_.join(i+" ")+i).split(" "),f(s,t,n))}function g(e,t,r){return v(e,n,n,t,r)}var y=[],C=[],E={_version:"3.1.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,t){var n=this;setTimeout(function(){t(n[e])},0)},addTest:function(e,t,n){C.push({name:e,fn:t,options:n})},addAsyncTest:function(e){C.push({name:null,fn:e})}},S=function(){};S.prototype=E,S=new S;var x=t.documentElement,b="svg"===x.nodeName.toLowerCase();b||!function(e,t){function n(e,t){var n=e.createElement("p"),r=e.getElementsByTagName("head")[0]||e.documentElement;return n.innerHTML="x<style>"+t+"</style>",r.insertBefore(n.lastChild,r.firstChild)}function r(){var e=C.elements;return"string"==typeof e?e.split(" "):e}function o(e,t){var n=C.elements;"string"!=typeof n&&(n=n.join(" ")),"string"!=typeof e&&(e=e.join(" ")),C.elements=n+" "+e,u(t)}function a(e){var t=y[e[v]];return t||(t={},g++,e[v]=g,y[g]=t),t}function i(e,n,r){if(n||(n=t),c)return n.createElement(e);r||(r=a(n));var o;return o=r.cache[e]?r.cache[e].cloneNode():h.test(e)?(r.cache[e]=r.createElem(e)).cloneNode():r.createElem(e),!o.canHaveChildren||m.test(e)||o.tagUrn?o:r.frag.appendChild(o)}function s(e,n){if(e||(e=t),c)return e.createDocumentFragment();n=n||a(e);for(var o=n.frag.cloneNode(),i=0,s=r(),l=s.length;l>i;i++)o.createElement(s[i]);return o}function l(e,t){t.cache||(t.cache={},t.createElem=e.createElement,t.createFrag=e.createDocumentFragment,t.frag=t.createFrag()),e.createElement=function(n){return C.shivMethods?i(n,e,t):t.createElem(n)},e.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+r().join().replace(/[\w\-:]+/g,function(e){return t.createElem(e),t.frag.createElement(e),'c("'+e+'")'})+");return n}")(C,t.frag)}function u(e){e||(e=t);var r=a(e);return!C.shivCSS||f||r.hasCSS||(r.hasCSS=!!n(e,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),c||l(e,r),e}var f,c,d="3.7.3",p=e.html5||{},m=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,h=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,v="_html5shiv",g=0,y={};!function(){try{var e=t.createElement("a");e.innerHTML="<xyz></xyz>",f="hidden"in e,c=1==e.childNodes.length||function(){t.createElement("a");var e=t.createDocumentFragment();return"undefined"==typeof e.cloneNode||"undefined"==typeof e.createDocumentFragment||"undefined"==typeof e.createElement}()}catch(n){f=!0,c=!0}}();var C={elements:p.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video",version:d,shivCSS:p.shivCSS!==!1,supportsUnknownElements:c,shivMethods:p.shivMethods!==!1,type:"default",shivDocument:u,createElement:i,createDocumentFragment:s,addElements:o};e.html5=C,u(t),"object"==typeof module&&module.exports&&(module.exports=C)}("undefined"!=typeof e?e:this,t);var w="Moz O ms Webkit",_=E._config.usePrefixes?w.toLowerCase().split(" "):[];E._domPrefixes=_;var N=E._config.usePrefixes?w.split(" "):[];E._cssomPrefixes=N;var j=function(t){var r,o=prefixes.length,a=e.CSSRule;if("undefined"==typeof a)return n;if(!t)return!1;if(t=t.replace(/^@/,""),r=t.replace(/-/g,"_").toUpperCase()+"_RULE",r in a)return"@"+t;for(var i=0;o>i;i++){var s=prefixes[i],l=s.toUpperCase()+"_"+r;if(l in a)return"@-"+s.toLowerCase()+"-"+t}return!1};E.atRule=j;var k={elem:l("modernizr")};S._q.push(function(){delete k.elem});var F={style:k.elem.style};S._q.unshift(function(){delete F.style});E.testProp=function(e,t,r){return h([e],n,t,r)};E.testAllProps=v;E.prefixed=function(e,t,n){return 0===e.indexOf("@")?j(e):(-1!=e.indexOf("-")&&(e=i(e)),t?v(e,t,n):v(e,"pfx"))};E.testAllProps=g,S.addTest("cssanimations",g("animationName","a",!0)),o(),a(y),delete E.addTest,delete E.addAsyncTest;for(var P=0;P<S._q.length;P++)S._q[P]();e.Modernizr=S}(window,document);
"use strict";!function(s){function e(s){return new RegExp("(^|\\s+)"+s+"(\\s+|$)")}function n(s,e){var n=a(s,e)?c:t;n(s,e)}var a,t,c;"classList"in document.documentElement?(a=function(s,e){return s.classList.contains(e)},t=function(s,e){s.classList.add(e)},c=function(s,e){s.classList.remove(e)}):(a=function(s,n){return e(n).test(s.className)},t=function(s,e){a(s,e)||(s.className=s.className+" "+e)},c=function(s,n){s.className=s.className.replace(e(n)," ")});var i={hasClass:a,addClass:t,removeClass:c,toggleClass:n,has:a,add:t,remove:c,toggle:n};"function"==typeof define&&define.amd?define(i):s.classie=i}(window);
"use strict";!function(t){function i(t,i){for(var n in i)i.hasOwnProperty(n)&&(t[n]=i[n]);return t}function n(t){this.options=i({},this.options),i(this.options,t),this._init()}var s=(t.document.documentElement,{animations:Modernizr.cssanimations}),o={WebkitAnimation:"webkitAnimationEnd",OAnimation:"oAnimationEnd",msAnimation:"MSAnimationEnd",animation:"animationend"},e=o[Modernizr.prefixed("animation")];n.prototype.options={wrapper:document.body,message:"yo!",layout:"growl",effect:"slide",type:"error",ttl:6e3,onClose:function(){return!1},onOpen:function(){return!1}},n.prototype._init=function(){this.ntf=document.createElement("div"),this.ntf.className="ns-box ns-"+this.options.layout+" ns-effect-"+this.options.effect+" ns-type-"+this.options.type;var t='<div class="ns-box-inner">';t+=this.options.message,t+="</div>",t+='<span class="ns-close"></span></div>',this.ntf.innerHTML=t,this.options.wrapper.insertBefore(this.ntf,this.options.wrapper.firstChild);var i=this;this.dismissttl=setTimeout(function(){i.active&&i.dismiss()},this.options.ttl),this._initEvents()},n.prototype._initEvents=function(){var t=this;this.ntf.querySelector(".ns-close").addEventListener("click",function(){t.dismiss()})},n.prototype.show=function(){this.active=!0,classie.remove(this.ntf,"ns-hide"),classie.add(this.ntf,"ns-show"),this.options.onOpen()},n.prototype.dismiss=function(){var t=this;this.active=!1,clearTimeout(this.dismissttl),classie.remove(this.ntf,"ns-show"),setTimeout(function(){classie.add(t.ntf,"ns-hide"),t.options.onClose()},25);var i=function n(i){if(s.animations){if(i.target!==t.ntf)return!1;this.removeEventListener(e,n)}t.options.wrapper.removeChild(this)};s.animations?this.ntf.addEventListener(e,i):i()},t.NotificationFx=n}(window);
"use strict";!function(){window.BaseRouter={initialize:function(){this.init_views()},init_views:function(){},current:function(){var t,n=this,i=location.pathname.slice(1),e=_.pairs(n.routes),r=null,a=null;return t=_.find(e,function(t){return r=_.isRegExp(t[0])?t[0]:n._routeToRegExp(t[0]),r.test(i)}),t&&(a=n._extractParameters(r,i),r=t[1]),{route:r,fragment:i,params:a}},load_positions:function(){var t=this;return $.post(location.pathname,{},function(n){var i=n.blocks;for(var e in i)i.hasOwnProperty(e)&&$('[data-block="'+e+'"]').html(i[e]);var r=n.views;for(var a in r)r.hasOwnProperty(a)&&$('[data-view="'+a+'"]').replaceWith(r[a]);App.trigger("Page:loaded",{page:location.pathname.split("/").slice(-1)[0],response:n}),t.init_views()},"json")},reload:function(){this.load_positions()},extend:function(t){return $.extend(!0,{},this,t)},go_to:function(t){var n=arguments.length<=1||void 0===arguments[1]?{trigger:!0}:arguments[1];this.navigate(t,n)}}}();
"use strict";!function(e){e.App={},_.extend(App,Backbone.Events),Object.defineProperty(App,"start",{value:function(){this.register_events(),$.ajaxSetup({beforeSend:function(){this.url+=(this.url.indexOf("?")>-1?"&":"?")+"ajax=1";var e=this.data||"",t=App.parse_url_params(this.url.split("?")[1]),r=App.parse_url_params(e);this.url+="&token="+App.get_token(t,r)},error:function(){NotificationView.display("Request completed with an error","error")}}),jSmart.prototype.registerPlugin("function","include",function(e,t){var r=e.__get("file",null,0);if(!t.inclusions)throw new Error("data must contain inclusions section");var n=t.inclusions[r]||"",i=new jSmart(n);return i.fetch(t)}),jSmart.prototype.registerPlugin("modifier","strpos",function(e,t){e.indexOf(t)}),Backbone.history.start({pushState:!0,silent:!0})}}),Object.defineProperty(App,"register_events",{value:function(){$(document).on("click","a[href]:not(.link--external)",function(e){var t=this.getAttribute("href"),r=-1==t.indexOf("http")&&-1==t.indexOf("www")&&-1==t.indexOf("javascript");r&&(e.preventDefault(),Router.navigate(t,{trigger:!0}))}),$(document).on("submit","form",function(e){e.preventDefault()})}}),Object.defineProperty(App,"get_token",{value:function(e,t){var r=(Cookie.get_cookie("user")||"")+(Cookie.get_cookie("pfm_session_id")||"")+JSON.stringify($.extend(e,t));return CryptoJS.MD5(r).toString()}}),Object.defineProperty(App,"parse_url_params",{value:function(t){"undefined"==typeof t&&(t=e.location.search);var t=t.split("#")[0],r={},n=t.split("?")[1];if(n||t.search("=")!==!1&&(n=t),n)for(var i=n.split("&"),o=0;o<i.length;o++){var a=i[o].split("="),s=a[0],p=a[1]||"";r[s]=decodeURIComponent(p.replace(/\+/g," "))}return r}}),App.start()}(window);
"use strict";!function(){window.Cookie={get_cookie:function(e){var o=document.cookie.match(new RegExp("(?:^|; )"+e.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,"\\$1")+"=([^;]*)"));return o?decodeURIComponent(o[1]):void 0},set_cookie:function(e,o,i){i=i||{};var t=i.expires;if("number"==typeof t&&t){var n=new Date;n.setTime(n.getTime()+1e3*t),t=i.expires=n}t&&t.toUTCString&&(i.expires=t.toUTCString()),o=encodeURIComponent(o);var r=e+"="+o;for(var c in i){r+="; "+c;var s=i[c];s!==!0&&(r+="="+s)}document.cookie=r},delete_cookie:function(e){this.set_cookie(e,"",{expires:-1})}}}();
"use strict";!function(){window.Helpers={object_loaded:function(n){return new Promise(function(e,t){window[n]&&e();var r=0,o=setInterval(function(){window[n]?(clearInterval(o),e()):r++>300&&(clearInterval(o),t())},50)})},objects_loaded:function(){var n=this,e=arguments.length<=0||void 0===arguments[0]?[]:arguments[0],t=new Promise(function(n){return n()});return e.reduce(function(e,t){return e.then(function(){return n.object_loaded(t)})},t)}}}();
"use strict";_.defer(function(){window.TemplatesCollection=Backbone.Collection.extend({model:TemplateModel,url:function(){return"/rest/templates"+location.pathname},parse:function(e){return e.title&&(document.title=e.title),e.templates}})});
"use strict";!function(){window.TemplateModel=Backbone.Model.extend({defaults:{name:null,html:null,data:null}})}();
"use strict";!function(){window.UserModel=Backbone.Model.extend({defaults:{id:null,login:null,password:null,credentials:null,remember_hash:null,deleted:null}})}();
"use strict";!function(){window.BaseView=Backbone.View.extend({render:function(){var e=templates.findWhere({name:this.name}),t=e.get("data"),n=new jSmart(e.get("html"));return this.$el.html(n.fetch(t))}})}();
"use strict";_.defer(function(){window.LoginFormView=BaseView.extend({tagName:"div",name:"LoginFormView",events:{submit:"login"},login:function(i){var e=i.target;LoginFormController.login(e.action,$(e).serializeArray())}})});
"use strict";_.defer(function(){window.NavbarView=BaseView.extend({tagName:"div",name:"NavbarView",events:{"click #logout":"logout"},logout:function(t){var e=$(t.currentTarget);$.post(e.data("action")).success(function(){Router.go_to(e.data("redirect"))})}})});
"use strict";!function(){window.NotificationView={display:function(e,c){var a=document.createElement("span");switch(c){case"notice":a.className="icon icon-info";break;case"warning":a.className="icon icon-warning";break;case"error":a.className="icon icon-cross";break;case"success":a.className="icon icon-checkmark";break;default:c="notice",a.className="icon icon-info"}var n=$(".ns-box");n.length&&n.remove(),new NotificationFx({message:a.outerHTML+"<p>"+e+"</p>",layout:"bar",effect:"slidetop",type:c}).show()}}}();
"use strict";_.defer(function(){window.LeftMenuView=BaseView.extend({tagName:"div",name:"LeftMenuView",initialize:function(){var e=this;App.on("Page:loaded",function(){var a=void 0;a=e.$el.find("/admin_panel"==location.pathname||location.pathname.indexOf("users")>1?'a[href="/admin_panel/users"]':'a[href="'+location.pathname+'"]'),a&&!a.hasClass("active")&&(e.$el.find("li.active").removeClass("active"),a.parent("li").addClass("active"))})}})});
"use strict";_.defer(function(){window.AddUserView=UserFormView.extend({tagName:"div",name:"AddUserView"})});
"use strict";_.defer(function(){window.EditUserView=UserFormView.extend({tagName:"div",name:"EditUserView"})});
"use strict";!function(){window.UserFormView=BaseView.extend({events:{submit:"save","click button":"cancel"},save:function(e){var s=e.target;$.post(s.action,$(s).serializeArray(),function(e){"success"==e.status?(Router.go_to("/admin_panel/users"),NotificationView.display(e.message,e.status)):(e.message="",NotificationView.display(_.values(e.errors).join("\n"),e.status))},"json")},cancel:function(e){e.preventDefault(),Router.go_to("/admin_panel/users")}})}();
"use strict";_.defer(function(){window.UsersTableView=BaseView.extend({tagName:"div",name:"UsersTableView",events:{"click .users__delete":"delete_user"},delete_user:function(e){var t=$(e.currentTarget);confirm(t.data("message"))&&$.post(t.data("action"),{id:t.data("param")},function(e){t.parents("tr").remove(),NotificationView.display(e.message,e.status)},"json")}})});
"use strict";!function(){var e=Backbone.Router.extend(BaseRouter.extend({routes:{"admin_panel/edit_user/:id":"edit_user",admin_panel:"index","admin_panel/users":"users","admin_panel/users/page:number":"users","admin_panel/login":"login","admin_panel/add_user":"add_user"},init_views:function(){var e=this,n=["LeftMenuView","NavbarView","UserFormView","EditUserView","AddUserView","LoginFormView"];Helpers.objects_loaded(n).then(function(){switch(e.views="login"!=e.current().route?[new LeftMenuView({el:"#left-menu"}),new NavbarView({el:"#navbar"})]:[],e.current().route){case"edit_user":e.views.push(new EditUserView({el:"#edit-user-form"}));break;case"add_user":e.views.push(new AddUserView({el:"#add-user-form"}));break;case"login":e.views.push(new LoginFormView({el:"#login-form"}));break;case"index":case"users":e.views.push(new UsersTableView({el:"table.users__table"}))}})},edit_user:function(){window.templates=new TemplatesCollection,templates.on("sync",function(){$('[data-block="main"]').html((new EditUserView).render()),App.trigger("Page:loaded")}),templates.fetch()},add_user:function(){window.templates=new TemplatesCollection,templates.on("sync",function(){$('[data-block="main"]').html((new AddUserView).render()),App.trigger("Page:loaded")}),templates.fetch()},index:function(){window.templates=new TemplatesCollection,templates.on("sync",function(){$('[data-block="header"]').html((new NavbarView).render()),$('[data-block="left"]').html((new LeftMenuView).render()),$('[data-block="main"]').html((new UsersTableView).render()),$(document).scrollTop(0),App.trigger("Page:loaded")}),templates.fetch()},users:function(){window.templates=new TemplatesCollection,templates.on("sync",function(){$('[data-block="main"]').html((new UsersTableView).render()),$(document).scrollTop(0),App.trigger("Page:loaded")}),templates.fetch()},login:function(){window.templates=new TemplatesCollection,templates.on("sync",function(){$('[data-block="header"]').html(""),$('[data-block="left"]').html(""),$('[data-block="main"]').html((new LoginFormView).render()),App.trigger("Page:loaded")}),templates.fetch()}}));window.Router=new e}();
"use strict";function _classCallCheck(n,e){if(!(n instanceof e))throw new TypeError("Cannot call a class as a function")}var _createClass=function(){function n(n,e){for(var r=0;r<e.length;r++){var o=e[r];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(n,o.key,o)}}return function(e,r,o){return r&&n(e.prototype,r),o&&n(e,o),e}}();!function(){window.LoginFormController=function(){function n(){_classCallCheck(this,n)}return _createClass(n,null,[{key:"login",value:function(n,e){$.post(n,e,function(n){"error"==n.status?NotificationView.display(_.values(n.errors).join("\n"),"error"):Router.go_to("/admin_panel")},"json")}}]),n}()}();
//# sourceMappingURL=maps/admin_panel-3bae14f08d.js.map