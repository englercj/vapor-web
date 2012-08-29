(function($, window, undefined) {
    window.vapor.html = {
        createLoader: function() {
            return '<div class="loader"><div class="loaderCircle"></div><div class="loaderCircle1"></div></div>';
        },
        createBubble: function(msg, type, title, icon) {
            var h = '<div class="ui-corner-all ui-state-' + type + '"><p>';
            
            if(icon) {
                h += '<span class="ui-icon ui-icon-' + icon + '" style="float: left; margin-right: .3em;"></span>';
            }
                    
            if(title) {
                h += '<strong>' + title + ':</strong> ';
            }
            
            h += msg + '</p></div>';
            
            return h;
        }
    };
})(jQuery, window);