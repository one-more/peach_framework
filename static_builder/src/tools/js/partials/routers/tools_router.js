(function () {
    'use strict';

    window.ToolsRouter = Backbone.Router.extend($.extend(true, window.BaseRouter, {
        routes: {},

        init_views: function () {
            this.views = [
                new ToolsMenuView
            ];
        }
    }));

    App.router = new ToolsRouter;
    Backbone.history.start({pushState: true, silent: true});
})();