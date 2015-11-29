(function() {
    'use strict';

    window.BaseRouter = {

        initialize: function () {
            this.init_views();

            this.on('route', () => {
                Meta.load();
            });
        },

        init_views: () => {
            let views = Array.from(document.querySelectorAll('[data-view]'))
                .map(el => el.getAttribute('data-view'));
            Helpers.objects_loaded(views).then(() => {
                views.forEach(view => new window[view]);
            });
        },

        extend: function (obj) {
            return $.extend(true, {}, this, obj);
        },

        go_to: function(url, options = {trigger : true}) {
            this.navigate(url, options);
        }
    };
})();
