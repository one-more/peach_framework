(function(window) {
    'use strict';

    window.App = {};

    _.extend(App, Backbone.Events, {

        start: function() {
            this.register_events();

            $.ajaxSetup({
                beforeSend  : function() {
                    this.url += (this.url.indexOf('?') > -1 ? '&' : '?') + 'ajax=1';
                    var data = this.data || '';
                    var get_params = App.parse_url_params(this.url.split('?')[1]);
                    var post_params = App.parse_url_params(data);
                    this.url += '&token='+App.get_token(get_params, post_params);
                },
                error : function(xhr,status, error) {
                    NotificationView.display('Request completed with an error', 'error');
                }
            });

            Backbone.history.start({pushState: true, silent: true});
        },

        register_events: function() {
            $(document).on('click', 'a[href]:not(.link_external)', function(e) {
                var href = this.getAttribute('href');
                if(href.slice(-1) == '/') {
                    href = href.slice(0,-1);
                }
                if(href.indexOf('http') == -1 && href.indexOf('www') == -1) {
                    e.preventDefault();
                    Router.navigate(href, {trigger:true});
                }
            });

            $(document).on('submit', 'form', function(e) {
                e.preventDefault();
            });
        },

        get_token: function(get_params, post_params) {
            var str_to_hash = (Cookie.get_cookie('user') || '') +
                (Cookie.get_cookie('pfm_session_id') || '')
                + JSON.stringify($.extend(get_params, post_params));
            return CryptoJS.MD5(str_to_hash).toString();
        },

        parse_url_params: function(url) {
            // http://stackoverflow.com/a/23946023/2407309
            if (typeof url == 'undefined') {
                url = window.location.search
            }
            var url = url.split('#')[0] // Discard fragment identifier.
            var urlParams = {}
            var queryString = url.split('?')[1]
            if (!queryString) {
                if (url.search('=') !== false) {
                    queryString = url
                }
            }
            if (queryString) {
                var keyValuePairs = queryString.split('&')
                for (var i = 0; i < keyValuePairs.length; i++) {
                    var keyValuePair = keyValuePairs[i].split('=')
                    var paramName = keyValuePair[0]
                    var paramValue = keyValuePair[1] || ''
                    urlParams[paramName] = decodeURIComponent(paramValue.replace(/\+/g, ' '))
                }
            }
            return urlParams
        }
    });

    App.start();
})(window);
