(function() {
    'use strict';
    App.router = Backbone.Router.extend({
        routes: {}
    });
    App.router = new App.router;
    Backbone.history.start({pushState: true, silent: true});
})();
