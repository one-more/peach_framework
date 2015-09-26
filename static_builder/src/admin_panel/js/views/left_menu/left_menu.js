(function(){
    'use strict';
    window.LeftMenuView = Backbone.View.extend({
        el: '#left-menu',

        initialize: function() {
            App.on('Page:loaded', function() {
                this.$el.find('li').removeClass('active');
                this.$el.find(`a[href="${location.pathname}"]`)
                    .parent('li')
                    .addClass('active');
            }.bind(this))
        }
    });
})();
