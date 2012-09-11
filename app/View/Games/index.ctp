<div class="games index">
    <h2><?php echo __('Games'); ?></h2>
    <?php
    echo $this->element('datagrid/grid', array(
        'columns' => array(
            'id' => array('sortable' => true),
            'title' => array('sortable' => true),
            'launch' => array('sortable' => true),
            'update' => array('sortable' => true),
            'icon' => array('sortable' => true),
            'url' => array('sortable' => true),
            'beta' => array('sortable' => true),
            'external' => array('sortable' => true),
            'engine_id' => array('sortable' => true)
        ),
        'actions' => array(
            'view' => array(),
            'edit' => array(),
            'delete' => array('post' => true, 'type' => 'error')
        ),
        'data' => $games,
        'model' => 'Game'
    ));
    ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Engines'), array('controller' => 'engines', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Engine'), array('controller' => 'engines', 'action' => 'add')); ?> </li>
    </ul>
</div>
