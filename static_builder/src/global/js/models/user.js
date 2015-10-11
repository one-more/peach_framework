(() => {
    "use strict";

    window.UserModel = Backbone.Model.extend({
        defaults: {
            id: null,
            login: null,
            password: null,
            credentials: null,
            remember_hash: null,
            deleted: null
        }
    })
})();
