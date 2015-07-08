(function(window) {
    'use strict';
    window.App = {};
    _.extend(App, Backbone.Events, {
        start: function() {
            this.register_events();
        },

        register_events: function() {
            $(document).on('click', 'a[href]:not(.external)', function(e) {
                var href = $(this).attr('href');
                if(href.slice(-1) == '/') {
                    href = href.slice(0,-1);
                }
                if(href.indexOf('http') == -1 && href.indexOf('www') == -1) {
                    e.preventDefault();
                    App.router.navigate(href, {trigger:true});
                }
            });

            $(document).on('submit', 'form', function(e) {
                e.preventDefault();
            });
        },

        get_cookie : function(name) {
            var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
              ));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        },

        set_cookie : function(name, value, options) {
              options = options || {};

              var expires = options.expires;

              if (typeof expires == "number" && expires) {
                var d = new Date();
                d.setTime(d.getTime() + expires*1000);
                expires = options.expires = d;
              }
              if (expires && expires.toUTCString) {
                options.expires = expires.toUTCString();
              }

              value = encodeURIComponent(value);

              var updatedCookie = name + "=" + value;

              for(var propName in options) {
                updatedCookie += "; " + propName;
                var propValue = options[propName];
                if (propValue !== true) {
                  updatedCookie += "=" + propValue;
                 }
              }

              document.cookie = updatedCookie;
        },

        delete_cookie : function(name) {
            this.set_cookie(name, "", { expires: -1 })
        },

        go_to: function(url, options) {
            options = options || {trigger : true};
            App.router.navigate(url, options);
        }
    });
    App.start();
})(window);
