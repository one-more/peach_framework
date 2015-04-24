(function(window) {
    'use strict';
    window.LoginFormView = Backbone.View.extend({
        el: '#login-form',

        events: {
            'submit' : 'login'
        },

        login: function(event) {
            var form = event.target;
            $.post('/login', $(form).serializeArray(), function(user) {
                if(typeof user !== 'object') {
                    NotificationView.display(LanguageModel.get('login_error'), 'error')
                } else {
                    var credentials = user['credentials'];
                    var is_admin = credentials == 'administrator' ||
                        credentials == 'super_administrator';
                    if(is_admin) {
                        location.reload();
                    } else {
                        NotificationView.display(LanguageModel.get('credentials_error'), 'error');
                    }
                }
            }, 'json');
        }
    });
    window.LoginFormView = new window.LoginFormView;
})(window);
