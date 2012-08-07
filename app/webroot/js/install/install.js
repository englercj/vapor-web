(function($, window, undefined) {
    $(function() {
        var steps = ['index', 'database', 'email', 'superuser', 'server'],
        step = 0,
        stepname = window.location.pathname.split('/')[2];
        
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
        
        window.vapor.backStep = function() {
            vapor.loadStep(--step);
        };
        
        window.vapor.nextStep = function() {
            vapor.loadStep(++step);
        };
        
        window.vapor.loadStep = function(step) {
            //show loader
            
            //load in the page
            $('#install').load('/install/' + steps[step], function() {
                //hide loader
            });
        };
    });
})(jQuery, window);