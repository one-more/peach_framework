_.defer(() => {
    'use strict';

    window.LoginFormView = BaseView.extend({

        tagName: 'div',

        name: 'LoginFormView',

        events: {
            'submit' : 'login'
        },

        login: function(event) {
            var form = event.target;
            LoginFormController.login(form.action, $(form).serializeArray());
        }
    });
});
