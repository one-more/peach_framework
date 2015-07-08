(function() {
    'use strict';
    window.NotificationView = {
        display: function(msg, type) {
            var span = document.createElement('span');
            switch (type) {
                case 'notice':
                    span.className = 'icon icon-info';
                    break;
                case 'warning':
                    span.className = 'icon icon-warning';
                    break;
                case 'error':
                    span.className = 'icon icon-cross';
                    break;
                case 'success':
                    span.className = 'icon icon-checkmark';
                    break;
                default:
                    type = 'notice';
                    span.className = 'icon icon-info';
                    break;
            }
            var ns_box = $('.ns-box');
            if(ns_box.length) {
                ns_box.remove();
            }
            new NotificationFx({
                message : span.outerHTML+'<p>'+msg+'</p>',
                layout : 'bar',
                effect : 'slidetop',
                type : type
            }).show();
        }
    };
})();
