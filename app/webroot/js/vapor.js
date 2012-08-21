(function($, window, undefined) {
    window.vapor = {
        ui: {
            showLoader: function($target) {
                if(!$target) $target = $('body');
                
                if(!($target instanceof $))
                    $target = $($target);

                if($target.find('.loader').length > 0) {
                    $target.find('.loader').show();
                } else {
                    $target.prepend(vapor.html.createLoader());
                }
            },
            hideLoader: function($target) {
                if(!($target instanceof $))
                    $target = $($target);

                $target.find('.loader').hide();
            }
        },
        html: {
            getLoader: function() { return '<div class="loaderCircle"></div><div class="loaderCircle1"></div>'; }
        },
        initialize: function() {
            //initialize buttons
            $('.button').button('destroy').each(function() {
                var $this = $(this),
                left = $this.data('icon-left'),
                right = $this.data('icon-right'),
                disabled = $this.data('disabled');

                $this.button({
                    primary: left,
                    secondary: right,
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
})(jQuery, window);