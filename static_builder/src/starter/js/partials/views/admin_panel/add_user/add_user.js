(function() {
    'use strict';
    window.EditUserView = Backbone.View.extend({
        el: '#add-user-form',

        initialize: function() {
            var $this = this;
            App.on('Page:loaded', function(event) {
                if(event.page == 'add_user') {
                    $this.setElement('#add-user-form');
                }
            })
        },

        events: {
            'submit': 'save',
            'click button': 'cancel'
        },

        save: function(event) {
            var table = event.target;
            $.post('/add_user', $(table).serializeArray(), function(json) {
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
    window.EditUserView = new window.EditUserView;
})();
