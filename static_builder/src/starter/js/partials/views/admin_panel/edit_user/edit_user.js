(function() {
    'use strict';
    window.EditUserView = Backbone.View.extend({
        el: '#edit-user-form',

        events: {
            'submit': 'save',
            'click button': 'cancel'
        },

        save: function(event) {
            var table = event.target;
            $.post('/edit_user', $(table).serializeArray(), function(json) {
                if(json.status == 'success') {
                    App.go_to('/admin_panel');
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
