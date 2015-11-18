_.defer(() => {
    'use strict';

    window.NavbarView = BaseView.extend({
        tagName: 'div',

        name: 'NavbarView',

        events: {
            'click #logout': 'logout'
        },

        logout: function(event) {
            let target = $(event.currentTarget);
            $.post(target.data('action')).success(function() {
                    Router.go_to(target.data('redirect'));
                });
        }
    });
});
