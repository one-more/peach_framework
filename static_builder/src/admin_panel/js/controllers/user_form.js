(() => {
    "use strict";

    window.UserFormController = class {

        static save(action, data) {
            $.post(action, data, json => {
                if(json.status == 'success') {
                    Router.go_to('/admin_panel/users');
                    NotificationView.display(json.message, json.status);
                } else {
                    json.message = '';
                    NotificationView.display(_.values(json['errors']).join('\n'), json.status);
                }
            }, 'json');
        }

        static cancel(event) {
            event.preventDefault();
            Router.go_to('/admin_panel/users');
        }
    }
})();