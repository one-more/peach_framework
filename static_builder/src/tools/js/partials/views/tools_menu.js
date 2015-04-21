(function() {
    'use strict';
    window.ToolsMenu = Backbone.View.extend({
        el: '#tools-menu',

        events: {
            'click a': 'change_active'
        },

        change_active: function(event) {
            var el = $(event.target);
            ToolsMenu.$el.find('li').removeClass('active');
            el.parent('li').addClass('active');
        }
    });
    window.ToolsMenu = new window.ToolsMenu;
})();
