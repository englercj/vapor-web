<!doctype html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title><?php echo $title_for_layout; ?></title>
	<?php
		echo $this->Html->meta('icon');
                
		echo $this->fetch('meta');
                
                echo $this->Html->css('install/install');
                echo $this->Html->css('jquery-ui/jquery-ui-1.8.16.custom');
                echo $this->Html->css('jquery-ui/jquery.ui.1.8.16.ie');
		echo $this->fetch('css');
                
                echo $this->Html->script('jquery/jquery.min');
                echo $this->Html->script('jquery/jquery-ui.min');
                echo $this->Html->script('vapor');
                echo $this->Html->script('unobtrusive');
                echo $this->Html->script('install/install');
		echo $this->fetch('script');
	?>
    </head>
    <body>
        <header>
            <h1>Vapor</h1>
        </header>
        
        <section id="install" class="ui-widget-shadow ui-corner-all">
            <?php echo $this->fetch('content'); ?>
        </section>
        
        <footer>
            
        </footer>
    </body>
</html>