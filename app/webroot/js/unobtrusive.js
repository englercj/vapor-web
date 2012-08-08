(function($, window, undefined) {
    //Docready
    $(function() {
        //initialize buttons
        $('.button').each(function() {
            var $this = $(this),
            left = $this.data('icon-left'),
            right = $this.data('icon-right'),
            disabled = $this.data('disabled');
            
            $this.button({
                primary: left,
                secondary: right,
                disabled: disabled
            });
        });
        
        //initialize progressbars
        $('.progressbar').each(function() {
            var $this = $(this),
            val = $this.data('startvalue');
            
            $this.progressbar({
                value: parseInt(val, 10)
            });
        });
    });
})(jQuery, window);