(() => {
    "use strict";

    window.BaseView = Backbone.View.extend({

        render: function () {
            let template = templates.findWhere({
                name: this.name
            });
            let data = template.get('data');
            let tpl = new jSmart(template.get('html'));
            return this.$el.html(tpl.fetch(data));
        }
    })
})();