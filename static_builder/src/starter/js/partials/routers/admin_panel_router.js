(function () {
    'use strict';

    window.AdminPanelRouter = Backbone.Router.extend($.extend(true, BaseRouter, {

        routes: {
            'admin_panel/edit_user/:id': 'edit_user',
            'admin_panel': 'index',
            'admin_panel/login': 'login',
            'admin_panel/add_user': 'add_user'
        },

        init_views: function () {
            if(this.current().route != 'login') {
                this.views = [
                    new LeftMenuView,
                    new NavbarView
                ];
            } else {
                this.views = [];
            }
            switch (this.current().route) {
                case 'edit_user':
                    this.views.push(new EditUserView);
                    break;
                case 'add_user':
                    this.views.push(new AddUserView);
                    break;
                case 'login':
                    this.views.push(new LoginFormView);
                    break;
            }
        },

        edit_user: function() {
            this.load_positions();
        },

        add_user: function() {
            this.load_positions();
        },

        index: function () {
            this.load_positions();
        },

        login: function () {
            this.load_positions();
        }
    }));

    App.router = new AdminPanelRouter;
    Backbone.history.start({pushState: true, silent: true});
})();