<div class="engines view">
<h2><?php  echo __('Engine'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($engine['Engine']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($engine['Engine']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Icon'); ?></dt>
		<dd>
			<?php echo h($engine['Engine']['icon']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Engine'), array('action' => 'edit', $engine['Engine']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Engine'), array('action' => 'delete', $engine['Engine']['id']), null, __('Are you sure you want to delete # %s?', $engine['Engine']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Engines'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Engine'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Games'); ?></h3>
	<?php 
            if (!empty($engine['Game'])) {
                echo $this->element('datagrid/grid', array(
                    'columns' => array(
                        'id' => array(),
                        'title' => array(),
                        'launch' => array(),
                        'update' => array(),
                        'icon' => array(),
                        'url' => array(),
                        'beta' => array(),
                        'external' => array(),
                        'engine_id' => array()
                    ),
                    'actions' => array(
                        'view' => array('icon-left' => 'ui-icon-search'),
                        'edit' => array('icon-left' => 'ui-icon-pencil'),
                        'delete' => array('classes' => 'ui-button-error', 'icon-left' => 'ui-icon-trash', 'post' => true)
                    ),
                    'data' => $engine['Game'],
                    'paginate' => false
                ));
            }
        ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
