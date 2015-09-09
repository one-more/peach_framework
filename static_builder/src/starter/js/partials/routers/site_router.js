(function () {
    'use strict';

    window.SiteRouter = Backbone.Router.extend($.extend(true, BaseRouter, {
        routes: {},

        init_views: function () {}
    }));

    App.router = new SiteRouter;
    Backbone.history.start({pushState: true, silent: true});
})();