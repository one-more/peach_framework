_.defer(() => {
    "use strict";

    window.UsersCollection = Backbone.Collection.extend({
        model: UserModel,

        url: '/rest/users',

        parse(response) {
            this.paging_model = response.paging_model;
            return response.result;
        }
    })
});