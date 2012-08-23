(function($, window, undefined) {
    window.vapor = {
        initialize: function() {
            //initialize buttons
            $('.button').button('destroy').each(function() {
                var $this = $(this),
                left = $this.data('icon-left'),
                right = $this.data('icon-right'),
                disabled = $this.data('disabled');

                $this.button({
                    icons: {
                        primary: left,
                        secondary: right
                    },
                    disabled: disabled
                });
            });

            //initialize progressbars
            $('.progressbar').progressbar('destroy').each(function() {
                var $this = $(this),
                val = $this.data('startvalue');

                $this.progressbar({
                    value: parseInt(val, 10)
                });
            });
        },
        temp: {}
    };
    
    $(function() {
        vapor.initialize();
    });
})(jQuery, window);