<div class="servers index">
    <h2><?php echo __('Servers'); ?></h2>
    <?php
    echo $this->element('datagrid/grid', array(
        'columns' => array(
            'id' => array('sortable' => true),
            'name' => array('sortable' => true),
            'host' => array('sortable' => true),
            'port' => array('sortable' => true)
        ),
        'actions' => array(
            'view' => array(),
            'edit' => array(),
            'delete' => array('post' => true, 'type' => 'error')
        ),
        'data' => $servers,
        'model' => 'Server'
    ));
    ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Server'), array('action' => 'add')); ?></li>
    </ul>
</div>
