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
            echo $this->AssetCompress->css('install');
            echo $this->fetch('css');
            
            echo $this->AssetCompress->script('all-jquery');
            echo $this->AssetCompress->script('all-vapor');
            echo $this->AssetCompress->script('all-install');
            
            //fetch additional scripts
            echo $this->fetch('script');
        ?>
    </head>
    <body>
        <header>
            <h1>Vapor</h1>
        </header>

        <section id="install" class="ui-corner-all ui-helper-clearfix">
            <?php echo $this->fetch('content'); ?>
        </section>
    </body>
</html>