(function () {
    'use strict';

    window.ToolsRouter = Backbone.Router.extend($.extend(true, window.BaseRouter, {
        routes: {
            'node_processes' : 'node_processes'
        },

        init_views: function () {
            this.views = [
                new ToolsMenuView
            ];

            switch(this.current().route) {
                case 'node_processes':
                    this.views.push(new NodeProcessesTableView);
                    break;
            }
        },

        node_processes: function () {
            this.load_positions();
        }
    }));

    App.router = new ToolsRouter;
    Backbone.history.start({pushState: true, silent: true});
})();