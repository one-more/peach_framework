(function () {
    'use strict';

    window.Helpers = {
        object_loaded(name) {
            return new Promise((resolve, reject) => {
                if(window[name]) {
                    resolve();
                }
                var i=0, interval = setInterval(() => {
                    if(window[name]) {
                        clearInterval(interval);
                        resolve();
                    } else if(i++ > 300) {
                        clearInterval(interval);
                        reject();
                    }
                }, 50);
            })
        },

        objects_loaded(objects = []) {
            var promise = new Promise(resolve => resolve());
            return objects.reduce((promise, name) => promise.then(() => this.object_loaded(name)), promise)
        }
    };
})();