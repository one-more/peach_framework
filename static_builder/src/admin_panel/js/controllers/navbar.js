(() => {
    "use strict";

    window.NavbarController = class {

        static logout(url, redirect) {
            $.post(url).success(function() {
                Router.go_to(redirect);
                App.trigger('User:logout')
            });
        }
    }
})();