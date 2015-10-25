_.defer(() => {
    'use strict';
    window.LeftMenuView = Backbone.View.extend(BaseView.extend({
        tagName: 'div',

        name: 'LeftMenuView',

        initialize: function() {
            App.on('Page:loaded', () => {
                let menu_item;
                if(location.pathname == '/admin_panel' || location.pathname.indexOf('users') > 1) {
                    menu_item = this.$el.find('a[href="/admin_panel/users"]');
                } else {
                    menu_item = this.$el.find(`a[href="${location.pathname}"]`);
                }
                if(menu_item && !menu_item.hasClass('active')) {
                    this.$el.find('li.active').removeClass('active');
                    menu_item.parent('li').addClass('active');
                }
            });
        }
    }));
});
