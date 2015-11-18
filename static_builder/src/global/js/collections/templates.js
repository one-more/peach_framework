_.defer(() => {
    "use strict";

    window.TemplatesCollection = Backbone.Collection.extend({
        model: TemplateModel,

        url: () => '/rest/templates'+location.pathname,

        parse: response => {
            if(response.title) {
                document.title = response.title
            }
            return response.templates;
        }
    })
});
