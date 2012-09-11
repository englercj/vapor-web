<div class="infrastructure index">
    <h2><?php echo __('Infrastructure'); ?></h2>
    <?php
    echo $this->element('datagrid/grid', array(
        'columns' => array(
            'id' => array('sortable' => true),
            'name' => array('sortable' => true),
            'host' => array('sortable' => true),
            'port' => array('sortable' => true),
            'address_id' => array('sortable' => true)
        ),
        'actions' => array(
            'view' => array('icon-left' => 'ui-icon-search'),
            'edit' => array('icon-left' => 'ui-icon-pencil'),
            'delete' => array('classes' => 'ui-button-error', 'icon-left' => 'ui-icon-trash', 'post' => true)
        ),
        'data' => $infrastructure,
        'model' => 'Infrastructure'
    ));
    ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Infrastructure Node'), array('action' => 'add')); ?></li>
    </ul>
</div>
