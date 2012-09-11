<div class="groups index">
    <h2><?php echo __('Groups'); ?></h2>

    <?php
    echo $this->element('datagrid/grid', array(
        'columns' => array(
            'id' => array('sortable' => true),
            'name' => array('sortable' => true)
        ),
        'actions' => array(
            'view' => array('icon-left' => 'ui-icon-search'),
            'edit' => array('icon-left' => 'ui-icon-pencil'),
            'delete' => array('classes' => 'ui-button-error', 'icon-left' => 'ui-icon-trash', 'post' => true)
        ),
        'data' => $groups,
        'model' => 'Group'
    ));
    ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Group'), array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
    </ul>
</div>
