_.defer(() => {
    "use strict";

    window.UsersTableView = BaseView.extend({

        tagName: 'div',

        name: 'UsersTableView',

        events: {
            'click .users__delete': 'delete_user'
        },

        delete_user: function (event) {
            let target = $(event.currentTarget);
            if(confirm(target.data('message'))) {
                $.post(target.data('action'), {id: target.data('param')}, response => {
                    target.parents('tr').remove();
                    NotificationView.display(response['message'], response['status']);
                }, 'json');
            }
        }
    });
});
