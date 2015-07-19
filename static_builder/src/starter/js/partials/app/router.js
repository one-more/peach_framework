(function() {
    'use strict';
    App.router = Backbone.Router.extend({
        routes: {
            'admin_panel/edit_user/:id': 'edit_user',
            'admin_panel': 'admin_panel',
            'admin_panel/add_user': 'add_user'
        },

        initialize: function () {
            if(location.pathname.indexOf('admin_panel') !== -1) {
                this.global_views = [
                    new LeftMenuView,
                    new NavbarView
                ];
            }
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
            switch(this.current().route) {
                case 'edit_user':
                    this.page_views = [
                        new EditUserView
                    ];
                    break;
                case 'add_user':
                    this.page_views = [
                        new AddUserView
                    ];
                    break;
                case 'admin_panel':
                    if(!App.get_cookie('user')) {
                        this.page_views = [
                            new LoginFormView
                        ];
                    }
                    break;
            }
        },

        global_views: [],

        page_views: [],

        edit_user: function() {
            this.load_positions();
        },

        add_user: function() {
            this.load_positions();
        },

        admin_panel: function () {
            this.load_positions();
        },

        load_positions: function() {
            var $this = this;
            return $.post(location.pathname, {}, function(data) {
                for(var i in data) {
                    $('#'+i).html(data[i]);
                     App.trigger('Page:loaded', {
                        page: location.pathname.split('/').slice(-1)[0]
                    });
                }
            }, 'json')
                .then(function() {
                    $this.init_views();
                });
        },

        reload: function() {
            this.load_positions();
        }
    });
    App.router = new App.router;
    Backbone.history.start({pushState: true, silent: true});
})();
