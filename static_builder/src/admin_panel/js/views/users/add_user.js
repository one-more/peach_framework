Helpers.object_loaded('UserFormView').then(() => {
    'use strict';
    window.AddUserView = UserFormView.extend({
        el: '#add-user-form',

        render() {
            let lang_vars = JSON.parse(FileSystem.get_file('lang/views/admin_panel/adduserview.json'));
            let data = {lang_vars};
            return this.get_template('add_user.tpl.html', data);
        }
    });
});
