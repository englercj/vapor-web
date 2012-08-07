(function($, window, undefined) {
    window.vapor = {
        ui: {
            showLoader: function($target) {
                if(!($target instanceof $))
                    $target = $($target);

                if($target.find('.loader').length > 0) {
                    $target.find('.loader').show();
                } else {
                    $target.prepend('<div class="ui-widget-overlay loader">\
                        <div class="loaderCircle"></div>\
                        <div class="loaderCircle1"></div>\
                    </div>');
                }
            },
            hideLoader: function($target) {
                if(!($target instanceof $))
                    $target = $($target);

                $target.find('.loader').hide();
            }
        },
        temp: {}
    };
})(jQuery, window);