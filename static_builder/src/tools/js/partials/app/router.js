(function() {
    'use strict';
    App.router = Backbone.Router.extend({
        routes: {
            '' : 'load_positions',
            'node_processes' : 'load_positions'
        },

        load_positions: function() {
            $.post(location.pathname, {}, function(data) {
                Object.keys(data).forEach(function(key) {
                    $('#'+key).html(data[key]);
                });
                App.trigger('Page:loaded', {
                    page: location.pathname.split('/').slice(-1)[0]
                })
            }, 'json');
        }
    });
    App.router = new App.router;
    Backbone.history.start({pushState: true, silent: true});
})();
