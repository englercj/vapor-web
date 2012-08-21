(function($, window, undefined) {
    window.vapor.ui = {
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
    };
})(jQuery, window);