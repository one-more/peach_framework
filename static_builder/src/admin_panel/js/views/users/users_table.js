Helpers.object_loaded('BaseView').then(() => {
    "use strict";

    window.UsersTableView = BaseView.extend({

        el: '#users',

        events: {
            'click .users__delete': 'delete_user'
        },

        initialize() {
            this.set_template_dir('templates/users');
            this.set_template_dir('templates/common');
        },

        delete_user: function (event) {
            let target = $(event.currentTarget);
            if(confirm(target.data('message'))) {
                $.post(target.data('action'), {id: target.data('param')}, response => {
                    target.parents('tr').remove();
                    NotificationView.display(response['message'], response['status']);
                }, 'json');
            }
        },

        render() {
            let lang_vars = FileSystem.get_file('lang/views/admin_panel/userstableview.json');
            let data = {
                users: this.collection.toJSON(),
                lang_vars: JSON.parse(lang_vars),
                my_id: User.get('id'),
                is_super_admin: User.get('is_super_admin'),
                base_url: '/admin_panel/users',
                paging_model: this.collection.paging_model
            };
            return this.get_template('users_table.tpl.html', data)
        }
    });
});
