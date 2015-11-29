(function () {
    'use strict';

    let AdminPanelRouter = Backbone.Router.extend(BaseRouter.extend({

        routes: {
            'admin_panel/edit_user/:id': 'edit_user',
            'admin_panel': 'index',
            'admin_panel/users': 'users',
            'admin_panel/users/page:number': 'users',
            'admin_panel/login': 'login',
            'admin_panel/add_user': 'add_user'
        },

        edit_user(id) {
            let user = new UserModel;
            user.on('sync', () => {
                $('[data-block="main"]').html(new EditUserView({model: user}).render());
                App.trigger('Page:loaded');
            });
            user.fetch({
                url: `/rest/users/${id}`
            });
        },

        add_user() {
            $('[data-block="main"]').html(new AddUserView().render());
            App.trigger('Page:loaded');
        },

        index() {
            $('[data-block="header"]').html(new NavbarView().render());
            $('[data-block="left"]').html(new LeftMenuView().render());

            this.users();
        },

        users(page) {
            let users = new UsersCollection();
            users.on('sync', () => {
                $('[data-block="main"]').html(new UsersTableView({collection: users}).render());
                $(document).scrollTop(0);
                App.trigger('Page:loaded');
            });
            users.fetch({
                data: {
                    page: page || 1
                }
            });
        },

        login() {
            $('[data-block="header"]').html('');
            $('[data-block="left"]').html('');
            $('[data-block="main"]').html(new LoginFormView().render());
            App.trigger('Page:loaded');
        }
    }));

    FileSystem.init().then(() => {
        window.Router = new AdminPanelRouter;
    })
})();