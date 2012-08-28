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
            
            echo $this->AssetCompress->css('all');
            echo $this->AssetCompress->css('login');
            echo $this->fetch('css');
            
            echo $this->AssetCompress->script('all-jquery');
            echo $this->AssetCompress->script('all-vapor');
            
            //fetch additional scripts
            echo $this->fetch('script');
        ?>
    </head>
    <body>
        <header>
            <!--<h1>Vapor</h1>-->
        </header>

        <section id="login" class="ui-corner-all ui-helper-clearfix">
            <?php echo $this->fetch('content'); ?>
        </section>

        <footer>
            
        </footer>
    </body>
</html>