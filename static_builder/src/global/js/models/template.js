(() => {
    "use strict";

    window.TemplateModel = Backbone.Model.extend({
        defaults: {
            name: null,
            html: null,
            data: null
        }
    })
})();