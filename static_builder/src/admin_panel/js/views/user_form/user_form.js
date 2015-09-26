(function () {
    'use strict';

    window.UserFormView = {
        events: {
            'submit': 'save',
            'click button': 'cancel'
        },
        save: function (event) {
            var table = event.currentTarget;
            $.post(table.action, $(table).serializeArray(), function(json) {
                if(json.status == 'success') {
                    Router.go_to('/admin_panel');
                } else {
                    json.message = '';
                    $.each(json.errors, function (i, error) {
                        json.message += error+'\n';
                    })
                }
                NotificationView.display(json.message, json.status);
            }, 'json');
        },
        cancel: function (event) {
            event.preventDefault();
            Router.go_to('/admin_panel');
        },
        extend: function (obj) {
            return $.extend(true, this, obj);
        }
    };
})();