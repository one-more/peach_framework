(function() {
    'use strict';
    window.NavbarView = Backbone.View.extend({
        el: '#navbar',

        events: {
            'click #logout': 'logout'
        },

        logout: function() {
            $.post('/logout')
                .success(function() {
                    location.reload();
                });
        }
    });
    window.NavbarView = new window.NavbarView;
})();
