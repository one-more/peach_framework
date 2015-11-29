(() => {
    "use strict";

    window.UserModel = Backbone.Model.extend({
        defaults: {
            id: null,
            login: null,
            is_admin: null,
            is_super_admin: null,
            is_guest: null
        },

        parse(response) {
            return response.result ? response.result : response
        }
    })
})();
