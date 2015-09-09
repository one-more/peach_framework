(function() {
    'use strict';
    window.NodeProcessesTableView = Backbone.View.extend({
        el: '#node-processes-table',

        initialize: function() {
            var $this = this;
            App.on('Page:loaded', function(event) {
                if(event.page == 'node_processes') {
                    $this.setElement('#node-processes-table');
                }
            });
        },

        events: {
            'click .toggle-process button': 'toggle_process'
        },

        toggle_process: function(event) {
            var el = $(event.target);
            el.attr('disabled', true);
            var process = el.data('process');
            var action = el.data('action');
            $.post('/node_processes/'+action, {
                'process': process
            });
            switch (action) {
                case 'enable':
                    el.data('action', 'disable')
                        .removeClass('btn-success')
                        .addClass('btn-danger')
                        .text('disable');
                    el.parents('tr').find('span')
                        .removeClass('glyphicon-remove')
                        .addClass('glyphicon-ok');
                    break;
                case 'disable':
                    el.data('action', 'enable')
                        .removeClass('btn-danger')
                        .addClass('btn-success')
                        .text('enable');
                    el.parents('tr').find('span')
                        .removeClass('glyphicon-ok')
                        .addClass('glyphicon-remove');
                    break;
            }
            el.attr('disabled', null);
        }
    });
    window.NodeProcessesTable = new window.NodeProcessesTable;
})();
