<div class="servers view">
<h2><?php  echo __('Server'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($server['Server']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($server['Server']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Host'); ?></dt>
		<dd>
			<?php echo h($server['Server']['host']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Port'); ?></dt>
		<dd>
			<?php echo h($server['Server']['port']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($server['Server']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($server['Server']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Server'), array('action' => 'edit', $server['Server']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Server'), array('action' => 'delete', $server['Server']['id']), null, __('Are you sure you want to delete # %s?', $server['Server']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Servers'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Server'), array('action' => 'add')); ?> </li>
	</ul>
</div>
