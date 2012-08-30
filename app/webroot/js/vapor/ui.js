(function($, window, undefined) {
    window.vapor.ui = {
        init: function() {
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
            
            //initialize select boxes
            $('select').chosen('destroy').chosen();
        },
        showLoader: function($target) {
            $target = vapor.util.jquerify($target, 'body');

            if($target.find('.loader').length > 0) {
                $target.find('.loader').show();
            } else {
                $target.prepend(vapor.html.createLoader());
            }
        },
        hideLoader: function($target) {
            $target = vapor.util.jquerify($target);

            $target.find('.loader').hide();
        }
    };
})(jQuery, window);