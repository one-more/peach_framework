_.defer(() => {
    "use strict";

    window.UsersCollection = Backbone.Collection.extend({
        model: UserModel,
        url: '/rest/users',

        parse: data => {
            return data['users']
        }
    });
});
