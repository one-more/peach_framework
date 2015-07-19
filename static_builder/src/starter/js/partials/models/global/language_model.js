(function() {
    'use strict';

    window.LanguageModel = Backbone.Model.extend({
        url: '/language_model',

        initialize: function() {
            this.fetch();
        }
    });
})();
