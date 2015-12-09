(() => {
    "use strict";

    window.Meta = class {

        static add(obj) {
            document.title = obj.title;
        }

        static load() {
            $.post('/rest/meta', {
                url: location.pathname
            }, response => Meta.add(response), 'json')
        }
    }
})();