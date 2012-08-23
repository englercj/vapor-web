<!doctype html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title><?php echo $title_for_layout; ?></title>
        <?php
            echo $this->Html->meta('icon');

            echo $this->fetch('meta');

            //echo $this->Html->css('bootstrap/bootstrap');
            echo $this->Html->css('bootstrap/jquery-ui-1.8.16.custom');
            //echo $this->Html->css('bootstrap/jquery.ui.1.8.16.ie');
            echo $this->Html->css('chosen/chosen');
            echo $this->Html->css('install/install');
            echo $this->Html->css('loader');
            echo $this->Html->css('main');
            echo $this->fetch('css');

            //jquery
            echo $this->Html->script('jquery/jquery.min');
            echo $this->Html->script('jquery/jquery-ui.min');
            echo $this->Html->script('jquery/jquery.validate.min');
            echo $this->Html->script('jquery/chosen.jquery.min');
            
            //vapor framework
            echo $this->Html->script('vapor/vapor');
            echo $this->Html->script('vapor/ajax');
            echo $this->Html->script('vapor/html');
            echo $this->Html->script('vapor/ui');
            echo $this->Html->script('vapor/util');
            
            //install scripts
            echo $this->Html->script('install/install');
            
            //fetch additional scripts
            echo $this->fetch('script');
        ?>
    </head>
    <body>
        <header>
            <h1>Vapor</h1>
        </header>

        <section id="install" class="ui-widget ui-widget-content ui-corner-all ui-helper-clearfix">
            <?php echo $this->fetch('content'); ?>
        </section>

        <footer>

        </footer>
    </body>
</html>