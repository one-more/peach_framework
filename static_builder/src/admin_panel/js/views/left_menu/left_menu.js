_.defer(() => {
    'use strict';
    window.LeftMenuView = BaseView.extend({
        el: 'left-menu',

        initialize: function() {
            this.set_template_dir('templates/left_menu');

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
        },

        render() {
            let lang_vars = FileSystem.get_file('lang/views/admin_panel/leftmenuview.json');
            lang_vars = JSON.parse(lang_vars);
            let data = {lang_vars};
            return this.get_template('left_menu.tpl.html', data);
        }
    });
});
