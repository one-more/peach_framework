(function() {
    'use strict';
    App.router = Backbone.Router.extend({
        routes: {
            'admin_panel/edit_user/:id': 'load_positions',
            'admin_panel': 'load_positions',
            'admin_panel/add_user': 'load_positions'
        },

        load_positions: function() {
            $.post(location.pathname, {}, function(data) {
                for(var i in data) {
                    $('#'+i).html(data[i]);
                     App.trigger('Page:loaded', {
                        page: location.pathname.split('/').slice(-1)[0]
                    });
                }
            }, 'json');
        },

        reload: function() {
            this.load_positions();
        }
    });
    App.router = new App.router;
    Backbone.history.start({pushState: true, silent: true});
})();
