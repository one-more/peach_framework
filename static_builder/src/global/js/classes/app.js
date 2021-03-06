(function(window) {
    'use strict';

    window.App = {};

    _.extend(App, Backbone.Events);

    Object.defineProperty(App, 'start', {
        value: function () {
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

            jSmart.prototype.registerPlugin(
                'function',
                'include',
                function(params, data) {
                    var file = params.__get('file',null,0);
                    if(!data['inclusions']) {
                        throw new Error('data must contain inclusions section');
                    }
                    let template = data['inclusions'][file] || '';
                    let tpl = new jSmart(template);
                    return tpl.fetch(data);
                }
            );

            jSmart.prototype.registerPlugin(
                'modifier',
                'strpos',
                function(str, needle) {
                    str.indexOf(needle)
                }
            );

            Backbone.history.start({pushState: true, silent: true});
        }
    });

    Object.defineProperty(App, 'register_events', {
        value: function () {
            $(document).on('click', 'a[href]:not(.link--external)', function(e) {
                let href = this.getAttribute('href');
                let navigate = href.indexOf('http') == -1
                    && href.indexOf('www') == -1
                    && href.indexOf('javascript') == -1;
                if(navigate) {
                    e.preventDefault();
                    Router.navigate(href, {trigger:true});
                }
            });

            $(document).on('submit', 'form', function(e) {
                e.preventDefault();
            });
        }
    });

    Object.defineProperty(App, 'get_token', {
        value: function(get_params, post_params) {
            var str_to_hash = (Cookie.get_cookie('user') || '') +
                (Cookie.get_cookie('pfm_session_id') || '')
                + JSON.stringify($.extend(get_params, post_params));
            return CryptoJS.MD5(str_to_hash).toString();
        }
    });

    Object.defineProperty(App, 'parse_url_params', {
        value: function (url) {
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
