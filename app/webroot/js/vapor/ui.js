(function($, window, undefined) {
    window.vapor.ui = {
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