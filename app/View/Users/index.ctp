<div class="users index">
    <h2><?php echo __('Users'); ?></h2>
    <?php
    echo $this->element('datagrid/grid', array(
        'columns' => array(
            'id' => array('sortable' => true),
            'username' => array('sortable' => true),
            'group_id' => array('sortable' => true)
        ),
        'actions' => array(
            'view' => array('icon-left' => 'ui-icon-search'),
            'edit' => array('icon-left' => 'ui-icon-pencil'),
            'delete' => array('classes' => 'ui-button-error', 'icon-left' => 'ui-icon-trash', 'post' => true)
        ),
        'data' => $users,
        'model' => 'User'
    ));
    ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
    </ul>
</div>
