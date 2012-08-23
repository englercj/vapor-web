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
        },
        setupFormValidation: function($form, rules, messages) {
            $form = vapor.util.jquerify($form);
            
            //setup validation of form
            $form.validate({
                rules: rules,
                messages: messages,
                errorClass: 'invalid',
                validClass: 'valid',
                errorElement: 'label'
            });
        },
        jquerify: function($o, defaultSelector) {
            if(!$o) $o = $(defaultSelector);

            if(!($o instanceof $))
                $o = $($o);
            
            return $o;
        }
    };
})(jQuery, window);