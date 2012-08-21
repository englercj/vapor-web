(function($, window, undefined) {
    window.vapor.ajax = {
        post: $.post,
        get: $.get,
        getJSON: $.getJSON,
        req: $.ajax,
        submitForm: function($form, defaults, cb) {
            //if form is valid
            if($form.valid()) {
                //apply defaults
                var data = vapor.util.applyFormDefaults($form, defaults);

                //attempt to send data
                vapor.ajax.post($form.prop('action'), data, cb);
            }
        }
    };
})(jQuery, window);