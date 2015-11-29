_.defer(() => {
    'use strict';

    window.LoginFormView = BaseView.extend({

        el: '#login-form',

        events: {
            'submit' : 'login'
        },

        initialize() {
            this.set_template_dir('templates/login');
        },

        render() {
            let lang_vars = FileSystem.get_file('lang/views/admin_panel/loginformview.json');
            lang_vars = JSON.parse(lang_vars);
            let data = {lang_vars};
            return this.get_template('login.tpl.html', data)
        },

        login(event) {
            var form = event.target;
            LoginFormController.login(form.action, $(form).serializeArray());
        }
    });
});
