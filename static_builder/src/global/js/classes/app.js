(window => {
    'use strict';

    let preload_objects = ['UserModel', 'Meta'];

    window.App = _.extend({
        start() {

            this.set_ajax_params();
            this.set_smarty_params();
            this.add_current_user();

            this.register_events();

            Backbone.history.start({pushState: true, silent: true});
        },

        set_ajax_params() {
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
                    console.log(xhr, status, error);
                }
            });
        },

        set_smarty_params() {
            jSmart.prototype.registerPlugin(
                'function',
                'include',
                function(params, data) {
                    let view = BaseView.extend({
                        template_dirs: data.template_dirs
                    });
                    return (new view).get_template(params.file, data).html();
                }
            );

            jSmart.prototype.registerPlugin(
                'modifier',
                'strpos',
                function(str, needle) {
                    str.indexOf(needle)
                }
            );
        },

        add_current_user() {
            let user = new UserModel();
            user.on('sync', () => {
                window.User = user;
            });
            user.fetch({url: '/rest/users/current'});
            this.on('User:login', () => user.fetch({url: '/rest/users/current'}));
            this.on('User:logout', () => user.clear())
        },

        register_events() {
            $(document).on('click', 'a[href]:not(.link_external)', function(e) {
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
        },

        get_token(get_params, post_params) {
            var str_to_hash = (Cookie.get_cookie('user') || '') +
                (Cookie.get_cookie('pfm_session_id') || '')
                + JSON.stringify($.extend(get_params, post_params));
            return CryptoJS.MD5(str_to_hash).toString();
        },

        parse_url_params(url) {
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

    }, Backbone.Events);

    Helpers.objects_loaded(preload_objects).then(() => App.start());
})(window);
