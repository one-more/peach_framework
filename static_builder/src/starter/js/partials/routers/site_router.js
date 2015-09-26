(function () {
    'use strict';

    let SiteRouter = Backbone.Router.extend(BaseRouter.extend({
        routes: {},

        init_views: function () {}
    }));

    window.Router = new SiteRouter;
})();