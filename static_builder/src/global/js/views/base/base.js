(() => {
    "use strict";

    window.BaseView = Backbone.View.extend({

        template_dirs: [],

        get_template(tpl_name, data = {}) {
            let template;
            for(let i = 0; i < this.template_dirs.length; i++) {
                if(template = FileSystem.get_file(`${this.template_dirs[i]}/${tpl_name}`)) {
                    break;
                }
            }
            let tpl_engine = new jSmart(template);
            this.setElement(document.createElement('div'));
            data = $.extend(data, {template_dirs: this.template_dirs});
            return this.$el.html(tpl_engine.fetch(data))
        },

        set_template_dir(path) {
            this.template_dirs.push(path);
        }
    })
})();