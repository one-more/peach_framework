Helpers.object_loaded('BaseView').then(() => {
    'use strict';

    window.UserFormView = BaseView.extend({
        events: {
            'submit': 'save',
            'click button': 'cancel'
        },

        initialize() {
            this.set_template_dir('templates/users');
        },

        save(event) {
            var table = event.target;

            UserFormController.save(table.action, $(table).serializeArray());
        },

        cancel(event) {
            UserFormController.cancel(event)
        }
    });
});