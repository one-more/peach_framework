(function () {
    'use strict';

    window.ToolsRouter = Backbone.Router.extend(BaseRouter.extend({
        routes: {},

        init_views: function () {
            let views = ['ToolsMenuView'];
            Helpers.objects_loaded(views).then(() => {
                this.views = [
                    new ToolsMenuView
                ];
            })
        }
    }));

    window.Router = new ToolsRouter;
})();