(() => {
    "use strict";

    window.LoginFormController = class {

        static login(action, data) {
            $.post(action, data, response => {
                if(response.status == 'error') {
                    NotificationView.display(_.values(response['errors']).join('\n'), 'error')
                } else {
                    User.on('sync', () => {
                        Router.go_to('/admin_panel');
                    });
                    App.trigger('User:login');
                }
            }, 'json');
        }
    }
})();