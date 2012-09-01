<!doctype html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title><?php echo $title_for_layout; ?></title>
        <?php
            echo $this->Html->meta('icon');

            echo $this->fetch('meta');
            
            ///////////////////////
            // CSS
            ///////////////////////
            //Bootstrap
            //echo $this->Html->css('bootstrap/bootstrap');
            echo $this->Html->css('bootstrap/jquery-ui-1.8.16.custom');
            //echo $this->Html->css('bootstrap/jquery.ui.1.8.16.ie');
            
            echo $this->Html->css('chosen/chosen');
            echo $this->AssetCompress->css('all');
            
            //fetch any additional css
            echo $this->fetch('css');
            
            ///////////////////////
            // JavaScript
            ///////////////////////
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
            
            <?php 
                echo $this->element('messages');
                echo $this->element('common/userbar');
                echo $this->element('common/navbar');
            ?>
        </header>

        <section>
            <?php echo $this->fetch('content'); ?>
        </section>

        <footer>
            <?php echo $this->element('common/footer'); ?>
        </footer>
        
        <?php if(Configure::read('debug') == 2): ?>
        
        <section id="debug">
            <a href="#" id="btnToggleDebug" class="button" data-icon-left="ui-icon-refresh">Toggle Debug Info</a>
            <?php echo $this->element('sql_dump'); ?>
        </section>

        <script>
            $(function() {
                //setup handler for debug table
                $('#btnToggleDebug').on('click', function(e) {
                    e.preventDefault();

                    $('table.cake-sql-log').toggle();
                })
            });
        </script>
        
        <?php endif; ?>
    </body>
</html>
