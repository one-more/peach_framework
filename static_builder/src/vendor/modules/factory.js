(function () {
    'use strict';

    window.Factory = {
        instances: [],

        get_class: function(name/*, arguments*/) {
            if(typeof this.instances[name] == "undefined") {
                if(typeof window[name] !== 'undefined') {
                    this.instances[name] = new (Function.prototype.bind.apply(window[name], arguments));
                }
            }
            return this.instances[name];
        }
    };
})();
