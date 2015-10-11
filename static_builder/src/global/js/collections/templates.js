_.defer(() => {
    "use strict";

    window.TemplatesCollection = Backbone.Collection.extend({
        model: TemplateModel,
        url: () => '/rest/templates'+location.pathname
    })
});
