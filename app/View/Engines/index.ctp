<div class="engines index">
    <h2><?php echo __('Engines'); ?></h2>
    <?php
    echo $this->element('datagrid/grid', array(
        'columns' => array(
            'id' => array('sortable' => true),
            'name' => array('sortable' => true),
            'icon' => array('sortable' => true)
        ),
        'actions' => array(
            'view' => array('icon-left' => 'ui-icon-search'),
            'edit' => array('icon-left' => 'ui-icon-pencil'),
            'delete' => array('classes' => 'ui-button-error', 'icon-left' => 'ui-icon-trash', 'post' => true)
        ),
        'data' => $engines,
        'model' => 'Engine'
    ));
    ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Engine'), array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
    </ul>
</div>
