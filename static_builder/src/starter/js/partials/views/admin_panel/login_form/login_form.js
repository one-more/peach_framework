(function(window) {
    'use strict';
    window.LoginFormView = Backbone.View.extend({
        el: '#login-form',

        events: {
            'submit' : 'login'
        },

        login: function(event) {
            var form = event.target;
            $.post('/login', $(form).serializeArray(), function(response) {
                if(response.status == 'error') {
                    var msg = Factory.get_class('LanguageModel').get('login_error');
                    NotificationView.display(msg, 'error')
                } else {
                    var user = response.user;
                    var credentials = user['credentials'];
                    var is_admin = credentials == 'administrator' ||
                        credentials == 'super_administrator';
                    if(is_admin) {
                        App.go_to('/admin_panel');
                    } else {
                        msg = Factory.get_class('LanguageModel').get('credentials_error');
                        NotificationView.display(msg, 'error');
                    }
                }
            }, 'json');
        }
    });
})(window);
