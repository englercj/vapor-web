(function($, window, undefined) {
    window.vapor.ajax = {
        //success(data, textStatus, jqXHR)
        //error(jqXHR, textStatus, errorThrown)
        post: function(url, data, success, error, datatype) {
            if(typeof(error) === 'string') {
                datatype = error;
                error = null;
            }
            
            return vapor.ajax.req({
                type: 'POST',
                url: url,
                data: data,
                success: success,
                error: error,
                dataType: datatype
            });
        },
        get: function(url, data, success, error, datatype) {
            if(typeof(error) === 'string') {
                datatype = error;
                error = null;
            }
            
            return vapor.ajax.req({
                url: url,
                data: data,
                success: success,
                error: error,
                dataType: datatype
            });
        },
        getJSON: function(url, data, success, error) {
            return vapor.ajax.req({
                url: url,
                data: data,
                success: success,
                error: error,
                dataType: 'json'
            });
        },
        req: $.ajax,
        submitForm: function($form, data, success, error) {
            //attempt to send data
            vapor.ajax.post($form.prop('action'), data, success, error);
        }
    };
})(jQuery, window);