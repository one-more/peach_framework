(() => {
    "use strict";

    window.UsersTableView = Backbone.View.extend({

        render: function () {
            let template = templates.findWhere({
                name: 'UsersTableView'
            });
            let data = template.get('data');
            let tpl = new jSmart(template.get('html'));
            return tpl.fetch(data);
        }
    });
})();
