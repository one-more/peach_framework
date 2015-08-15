(function() {
    'use strict';
    window.AddUserView = Backbone.View.extend({
        el: '#add-user-form',

        events: {
            'submit': 'save',
            'click button': 'cancel'
        },

        save: function(event) {
            var table = event.target;
            $.post('/add_user', $(table).serializeArray(), function(json) {
                if(json.status == 'success') {
                    App.go_to('/admin_panel');
                } else {
                    json.message = '';
                    $.each(json.errors, function (i, error) {
                        json.message += error+'\n';
                    })
                }
                NotificationView.display(json.message, json.status);
            }, 'json');
        },

        cancel: function(event) {
            event.preventDefault();
            App.go_to('/admin_panel');
        }
    });
})();
