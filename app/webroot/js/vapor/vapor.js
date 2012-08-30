(function($, window, undefined) {
    window.vapor = {
        init: function() {
            //initialize each major component
            $.each(vapor, function(key, comp) {
                if(comp && comp.init &&  $.isFunction(comp.init)) {
                    comp.init();
                }
            });
        },
        temp: {}
    };
    
    $(function() {
        vapor.init();
    });
})(jQuery, window);