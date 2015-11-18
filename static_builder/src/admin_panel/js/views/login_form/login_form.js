_.defer(() => {
    'use strict';

    window.LoginFormView = Backbone.View.extend(BaseView.extend({

        tagName: 'div',

        name: 'LoginFormView',

        events: {
            'submit' : 'login'
        },

        login: function(event) {
            var form = event.target;
            $.post(form.action, $(form).serializeArray(), response => {
                if(response.status == 'error') {
                    NotificationView.display(_.values(response['errors']).join('\n'), 'error')
                } else {
                    Router.go_to('/admin_panel');
                }
            }, 'json');
        }
    }));
});
