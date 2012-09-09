(function($, window, undefined) {
    //default settings
    var settings = {
    },

    methods = {
        init: function(options) {
            return this.each(function() { //ensures chainability
                if(options) $.extend(settings, options);

                var $this = $(this).hide(),
                $label = $('label[for="' + $this.attr('id') + '"]').hide(),
                $div = $('<div/>', { 'class': 'cb-container' }).insertAfter($this),
                $box = $('<div/>', { 'class': 'cb-box' }).appendTo($div),
                $text = $('<span/>', { 'class': 'cb-label' }).appendTo($div);
                
                $text.text($label.text());
                
                $div.on('click', function() {
                    $this.prop('checked', !$this.prop('checked'));
                    $div.toggleClass('cb-checked');
                }).on('mousedown', function(e) {
                    $div.addClass('cb-focused');
                }).on('mouseup', function(e) {
                    $div.removeClass('cb-focused');
                });
            });
        }
    };

    $.fn.checkbox = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist in jQuery.checkbox');
        }
    }
})(jQuery, window);