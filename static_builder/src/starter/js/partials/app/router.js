(function() {
    'use strict';
    App.router = Backbone.Router.extend({
        routes: {
            'admin_panel/edit_user/:id': 'edit_user',
            'admin_panel': 'admin_panel',
            'admin_panel/login': 'login',
            'admin_panel/add_user': 'add_user',
            '': 'index'
        },

        initialize: function () {
            this.init_views();
        },

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

        init_views: function () {
            if(location.pathname.indexOf('admin_panel') != -1) {
                var global_views = [
                    new LeftMenuView,
                    new NavbarView
                ];
            }
            switch(this.current().route) {
                case 'edit_user':
                    this.page_views = [
                        new EditUserView
                    ].concat(global_views);
                    break;
                case 'add_user':
                    this.page_views = [
                        new AddUserView
                    ].concat(global_views);
                    break;
                case 'admin_panel':
                    this.page_views = global_views;
                    break;
                case 'login':
                    this.page_views = [
                        new LoginFormView
                    ];
                    break;
            }
        },

        global_views: [],

        page_views: [],

        index: function() {},

        edit_user: function() {
            this.load_positions();
        },

        add_user: function() {
            this.load_positions();
        },

        admin_panel: function () {
            this.load_positions();
        },

        login: function () {
            this.load_positions();
        },

        load_positions: function() {
            var $this = this;
            return $.post(location.pathname, {}, function(response) {
                var blocks = response.blocks;
                for(var name in blocks) {
                    if(blocks.hasOwnProperty(name)) {
                        $('[data-block="'+name+'"]').html(blocks[name]);
                    }
                }

                var views = response.views;
                for(name in views) {
                    if(views.hasOwnProperty(name)) {
                        $('[data-view="'+name+'"]').replaceWith(views[name]);
                    }
                }
                App.trigger('Page:loaded', {
                    page: location.pathname.split('/').slice(-1)[0],
                    response: response
                });
                $this.init_views();
            }, 'json');
        },

        reload: function() {
            this.load_positions();
        }
    });

    App.router = new App.router;
    Backbone.history.start({pushState: true, silent: true});
})();
