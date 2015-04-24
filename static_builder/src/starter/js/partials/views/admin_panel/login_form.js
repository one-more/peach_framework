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
                    console.log('wrong data');
                } else {
                    console.log('ok');
                }
            }, 'json');
        }
    });
    window.LoginFormView = new window.LoginFormView;
})(window);
