Helpers.object_loaded('UserFormView').then(() => {
    'use strict';

    window.EditUserView = UserFormView.extend({
        el: '#edit-user-form',

        render() {
            let lang_vars = JSON.parse(FileSystem.get_file('lang/views/admin_panel/edituserview.json'));
            let data = {
                lang_vars,
                user: this.model.toJSON()
            };
            return this.get_template('edit_user.tpl.html', data);
        }
    });
});
