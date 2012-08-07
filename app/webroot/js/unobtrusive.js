(function($, window, undefined) {
    //Docready
    $(function() {
        //initialize buttons
        $('.button').each(function() {
            var $this = $(this),
            left = $this.data('icon-left'),
            right = $this.data('icon-right');
            
            $this.button({
                primary: left,
                secondary: right
            });
        });
        
        //initialize progressbars
        $('.progressbar').progressbar();
    });
})(jQuery, window);