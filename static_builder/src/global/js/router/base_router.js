(function() {
    'use strict';

    window.BaseRouter = {

        initialize: function () {
            this.init_views();
        },

        init_views: () => {},

        current: function() {
            var Router = this,
                fragment = location.pathname.slice(1),
                routes = _.pairs(Router.routes),
                route = null, params = null, matched;

            matched = _.find(routes, function(handler) {
                route = _.isRegExp(handler[0]) ? handler[0] : Router._routeToRegExp(handler[0]);
                return route.test(fragment);
            });

            if(matched) {
                params = Router._extractParameters(route, fragment);
                route = matched[1];
            }

            return {
                route : route,
                fragment : fragment,
                params : params
            };
        },

        load_positions: function() {
            return $.post(location.pathname, {}, response => {
                var blocks = response['blocks'];
                for(let name in blocks) {
                    if(blocks.hasOwnProperty(name)) {
                        $(`[data-block="${name}"]`).html(blocks[name]);
                    }
                }

                var views = response['views'];
                for(let name in views) {
                    if(views.hasOwnProperty(name)) {
                        $(`[data-view="${name}"]`).replaceWith(views[name]);
                    }
                }

                App.trigger('Page:loaded', {
                    page: location.pathname.split('/').slice(-1)[0],
                    response: response
                });
                this.init_views();
            }, 'json');
        },

        reload: function() {
            this.load_positions();
        },

        extend: function (obj) {
            return $.extend(true, {}, this, obj);
        },

        go_to: function(url, options = {trigger : true}) {
            this.navigate(url, options);
        }
    };
})();
