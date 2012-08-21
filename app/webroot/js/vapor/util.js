(function($, window, undefined) {
    window.vapor.util = {
        applyFormDefaults: function($form, defaults) {
            var data = $form.serializeArray();

            for(var i = 0, len = data.length; i < len; ++i) {
                if(data[i].value === '' && defaults[data[i].name]) {
                    data[i].value = defaults[data[i].name];
                }
            }

            return data;
        }
    };
})(jQuery, window);