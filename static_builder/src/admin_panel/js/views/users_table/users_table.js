(() => {
    "use strict";

    window.UsersTableView = Backbone.View.extend({
        className: 'users-table',

        initialize: function () {
            this.listenTo(this.collection, 'sync', this.render)
        },

        render: function () {
            $('[data-block="main"]').html(this.collection.template)
        }
    });
})();
