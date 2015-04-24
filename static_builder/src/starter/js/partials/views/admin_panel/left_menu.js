(function(){
    'use strict';
    window.LeftMenuView = Backbone.View.extend({
        el: '#left-menu',

        events: {
            'click a': 'change_active'
        },

        change_active: function(event) {
            var el = $(event.target);
            el.parents('ul').find('li').removeClass('active');
            el.parent().addClass('active');
        }
    });
    window.LeftMenuView = new window.LeftMenuView;
})();
