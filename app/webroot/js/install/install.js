(function($, window, undefined) {
    $(function() {
        var steps = ['index', 'database', 'email', 'superuser', 'server', 'finish'],
        step = 0,
        stepname = window.location.pathname.split('/')[2],
        $install = $('#install');
        
        //check if we are on a different page than index at load
        //and if so determine our step number, and store it
        if(stepname) {
            for(var i = 0, len = steps.length; i < len; ++i) {
                if(steps[i] == stepname) {
                    step = i;
                    console.log('Setting step to',step);
                    break;
                }
            }
        }
        
        //setup global vapor install object
        window.vapor.install = {
            previousStep:function() {
                vapor.install.loadStep(--step);
            },
            nextStep: function() {
                vapor.install.loadStep(++step);
            },
            loadStep: function(step) {
                $('#btnNext').hide();
                $('#btnBack').hide();
                $('.button-container').append(vapor.html.getLoader());
                
                //load in the page
                $install.load('/install/' + steps[step], function() {
                    vapor.initialize();
                    //vapor.ui.hideLoader($install);
                });
            },
            submitAndCheck: function() {
                var $form = $('#config');
                
                $('#btnNext').hide();
                $('#btnBack').hide();
                $('.button-container').append(vapor.html.getLoader());
                
                vapor.ajax.submitForm($form, { host: 'localhost', port: '3306' }, function(result) {
                    //check result
                    if(result.success) {
                        //move to next step
                        vapor.install.nextStep();
                    } else {
                        $('.button-container .loader').remove();
                        $('#btnNext').show();
                        $('#btnBack').show();
                        
                        alert('Error: ' + result.message);
                    }
                });
            }
        };
    });
})(jQuery, window);