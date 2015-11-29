(() => {
    "use strict";

    var files = {
        extend(obj) {
            files = $.extend(files, obj.result);
        }
    };

    window.FileSystem = class {
        static init() {
            let loadTemplates = $.post('/rest/admin_panel/templates', {}, files.extend, 'json');
            let loadLangFiles = $.post('/rest/admin_panel/lang_files', {}, files.extend, 'json');
            return $.when(loadTemplates, loadLangFiles);
        }

        static get_file(path) {
            return path.split('/').reduce((path, part) => {
                return path[part];
            }, files);
        }
    }
})();