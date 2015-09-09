(function() {
    'use strict';

    window.ToolsMenuView = Backbone.View.extend({
        el: '#tools-menu',

        initialize: function() {
            App.on('Page:loaded', function() {
                this.$el.find('li').removeClass('active');
                this.$el.find('a[href="'+location.pathname+'"]')
                    .parent('li')
                    .addClass('active');
            }.bind(this))
        }
    });
    window.ToolsMenu = new window.ToolsMenu;
})();
