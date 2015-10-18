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

        init_views: function () {
            let views = ['LeftMenuView', 'NavbarView', 'UserFormView', 'EditUserView',
                'AddUserView', 'LoginFormView'];
            Helpers.objects_loaded(views).then(() => {
                if(this.current().route != 'login') {
                    this.views = [
                        new LeftMenuView({
                            el: '#left-menu'
                        }),
                        new NavbarView({
                            el: '#navbar'
                        })
                    ];
                } else {
                    this.views = [];
                }
                switch (this.current().route) {
                    case 'edit_user':
                        this.views.push(new EditUserView({
                            el: '#edit-user-form'
                        }));
                        break;
                    case 'add_user':
                        this.views.push(new AddUserView({
                            el: '#add-user-form'
                        }));
                        break;
                    case 'login':
                        this.views.push(new LoginFormView({
                            el: '#login-form'
                        }));
                        break;
                    case 'index':
                    case 'users':
                        this.views.push(new UsersTableView({
                            el: 'table.users__table'
                        }));
                        break;
                }
            })
        },

        edit_user: function() {
            (window.templates = new TemplatesCollection(), templates.on('sync', () => {
                $('[data-block="main"]').html(new EditUserView().render());
                App.trigger('Page:loaded');
            }), templates.fetch());
        },

        add_user: function() {
            (window.templates = new TemplatesCollection(), templates.on('sync', () => {
                $('[data-block="main"]').html(new AddUserView().render());
                App.trigger('Page:loaded');
            }), templates.fetch());
        },

        index: function () {
            (window.templates = new TemplatesCollection(), templates.on('sync', () => {
                $('[data-block="header"]').html(new NavbarView().render());
                $('[data-block="left"]').html(new LeftMenuView().render());
                $('[data-block="main"]').html(new UsersTableView().render());
                $(document).scrollTop(0);
                App.trigger('Page:loaded');
            }), templates.fetch());
        },

        users: function () {
            (window.templates = new TemplatesCollection(), templates.on('sync', () => {
                $('[data-block="main"]').html(new UsersTableView().render());
                $(document).scrollTop(0);
                App.trigger('Page:loaded');
            }), templates.fetch());
        },

        login: function () {
            (window.templates = new TemplatesCollection(), templates.on('sync', () => {
                $('[data-block="header"]').html('');
                $('[data-block="left"]').html('');
                $('[data-block="main"]').html(new LoginFormView().render());
                App.trigger('Page:loaded');
            }), templates.fetch());
        }
    }));

    window.Router = new AdminPanelRouter;
})();