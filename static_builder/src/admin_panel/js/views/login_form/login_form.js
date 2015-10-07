(function() {
    'use strict';

    window.LoginFormView = Backbone.View.extend({
        el: '#login-form',

        events: {
            'submit' : 'login'
        },

        login: function(event) {
            var form = event.currentTarget;
            $.post(form.action, $(form).serializeArray(), response => {
                if(response.status == 'error') {
                    NotificationView.display(response.errors, 'error')
                } else {
                    Router.go_to('/admin_panel');
                }
            }, 'json');
        }
    });
})();
