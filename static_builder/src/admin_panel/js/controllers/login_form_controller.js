(() => {
    "use strict";

    window.LoginFormController = class LoginFormController {

        static login(action, data) {
            $.post(action, data, response => {
                if(response.status == 'error') {
                    NotificationView.display(_.values(response['errors']).join('\n'), 'error')
                } else {
                    Router.go_to('/admin_panel');
                }
            }, 'json');
        }
    }
})();