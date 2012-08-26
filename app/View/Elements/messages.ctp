<div id="messages">
    <?php
        $keys = array(
            'flash' => array('classes' => 'ui-state-default ui-corner-all', 'icon' => 'ui-icon-mail-closed'),
            'info' => array('classes' => 'ui-state-primary ui-corner-all', 'icon' => 'ui-icon-info'),
            'good' => array('classes' => 'ui-state-success ui-corner-all', 'icon' => 'ui-icon-check'),
            'bad' => array('classes' => 'ui-state-error ui-corner-all', 'icon' => 'ui-icon-alert')
        );
        
        foreach($keys as $key => $data) {
            if($this->Session->check('Message.' . $key)) {
                echo '<div class="' . $data['classes'] . '">';
                    echo '<p>';
                        echo '<span class="ui-icon ' . $data['icon'] . '" style="float:left;margin-right:0.3em;"></span>';
                        echo $this->Session->flash($key);
                    echo '</p>';
                echo '</div>';
            }
        }
    ?>
</div>
echo $this->Session->flash(); ?>