(function($, window, undefined) {
    window.vapor.ajax = {
        post: $.post,
        get: $.get,
        getJSON: $.getJSON,
        req: $.ajax
    };
})(jQuery, window);