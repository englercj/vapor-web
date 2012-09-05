(function($, window, undefined) {
    window.vapor.ui = {
        init: function() {
            //initialize buttons
            $('.button').button('destroy').each(function() {
                var $this = $(this),
                left = $this.data('icon-left'),
                right = $this.data('icon-right'),
                disabled = $this.data('disabled');

                $this.button({
                    icons: {
                        primary: left,
                        secondary: right
                    },
                    disabled: disabled
                });
            });

            //initialize progressbars
            $('.progressbar').progressbar('destroy').each(function() {
                var $this = $(this),
                val = $this.data('startvalue');

                $this.progressbar({
                    value: parseInt(val, 10)
                });
            });
            
            //initialize select boxes
            $('select').chosen('destroy').chosen();
            
            //make icons change on hover
            $('[data-hover-element]').hover(
                function(e) {
                    var $this = $(this),
                    elm = $this.data('hover-element'),
                    $elm;
                    
                    if(elm == 'this') $elm = $this;
                    else $elm = $this.find(elm);
                    
                    $elm.addClass('hover');
                },
                function(e) {
                    var $this = $(this),
                    elm = $this.data('hover-element'),
                    $elm;
                    
                    if(elm == 'this') $elm = $this;
                    else $elm = $this.find(elm);
                    
                    $elm.removeClass('hover');
                }
            );
            /*$('.icon-hover-blue').hover(
                function(e) {
                    var $this = $(this), $elm, cls, blu;
                    
                    if($this.data('icon-element'))
                        $elm = $this.find($this.data('icon-element'));
                    else
                        $elm = $this;
                    
                    cls = $elm.prop('class').match(/icon-[\d]+-[\w]+/)[0],
                    blu = cls + '-blue';
                    
                    if(cls)
                        $elm.removeClass(cls).addClass(blu);
                },
                function(e) {
                    var $this = $(this), $elm, cls, blu;
                    
                    if($this.data('icon-element'))
                        $elm = $this.find($this.data('icon-element'));
                    else
                        $elm = $this;
                    
                    blu = $elm.prop('class').match(/icon-[\d]+-[\w]+-blue/)[0],
                    cls = blu.replace('-blue', '');
                    
                    if(blu)
                        $elm.removeClass(blu).addClass(cls);
                }
            );*/
        },
        showLoader: function($target) {
            $target = vapor.util.jquerify($target, 'body');

            if($target.find('.loader').length > 0) {
                $target.find('.loader').show();
            } else {
                $target.prepend(vapor.html.createLoader());
            }
        },
        hideLoader: function($target) {
            $target = vapor.util.jquerify($target);

            $target.find('.loader').hide();
        }
    };
})(jQuery, window);