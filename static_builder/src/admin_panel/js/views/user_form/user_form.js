(function () {
    'use strict';

    window.UserFormView = BaseView.extend({
        events: {
            'submit': 'save',
            'click button': 'cancel'
        },

        save: function (event) {
            var table = event.target;
            $.post(table.action, $(table).serializeArray(), function(json) {
                if(json.status == 'success') {
                    Router.go_to('/admin_panel/users');
                    NotificationView.display(json.message, json.status);
                } else {
                    json.message = '';
                    NotificationView.display(_.values(json['errors']).join('\n'), json.status);
                }
            }, 'json');
        },

        cancel: function (event) {
            event.preventDefault();
            Router.go_to('/admin_panel/users');
        }
    });
})();