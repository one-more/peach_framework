_.defer(() => {
    'use strict';
    window.LeftMenuView = Backbone.View.extend(BaseView.extend({
        tagName: 'div',

        name: 'LeftMenuView',

        initialize: function() {
            App.on('Page:loaded', () => {
                if(location.pathname == '/admin_panel' || location.pathname.indexOf('users')) {
                    let menu_item = this.$el.find('a[href="/admin_panel/users"]');
                    if(!menu_item.hasClass('active')) {
                        this.$el.find('li.active').removeClass('active');
                        menu_item.parent('li').addClass('active');
                    }
                }
            });
        }
    }));
});
