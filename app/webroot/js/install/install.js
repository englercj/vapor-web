(function($, window, undefined) {
    $(function() {
        var steps = ['index', 'database', 'email', 'superuser', 'server'],
        step = 0,
        stepname = window.location.pathname.split('/')[2],
        $install = $('#install');
        
        //check if we are on a different page than index at load
        //and if so determine our step number, and store it
        if(stepname) {
            for(var i = 0, len = steps.length; i < len; ++i) {
                if(steps[i] == stepname) {
                    step = i;
                    break;
                }
            }
        }
        
        //setup global vapor install object
        window.vapor.install = {
            previousStep:function() {
                vapor.loadStep(--step);
            },
            nextStep: function() {
                vapor.loadStep(++step);
            },
            loadStep: function(step) {
                vapor.ui.showLoader($install);

                //load in the page
                $install.load('/install/' + steps[step], function() {
                    vapor.initialize();
                    //vapor.ui.hideLoader($install);
                });
            }
        };
    });
})(jQuery, window);