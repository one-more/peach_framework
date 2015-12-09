Helpers.object_loaded('BaseView').then(() => {
    'use strict';

    window.NavbarView = BaseView.extend({
        el: '#navbar',

        events: {
            'click #logout': 'logout'
        },

        initialize() {
            this.set_template_dir('templates/navbar');
        },

        logout: function(event) {
            let target = $(event.currentTarget);
            NavbarController.logout(target.data('action'), target.data('redirect'))
        },

        render() {
            let lang_vars = FileSystem.get_file('lang/views/admin_panel/navbarview.json');
            lang_vars = JSON.parse(lang_vars);
            let data = {lang_vars};
            return this.get_template('navbar.tpl.html', data);
        }
    });
});
