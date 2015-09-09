(function () {
    'use strict';

    window.SiteRouter = Backbone.Router.extend($.extend(true, BaseRouter, {
        routes: {}
    }));

    App.router = new SiteRouter;
    Backbone.history.start({pushState: true, silent: true});
})();