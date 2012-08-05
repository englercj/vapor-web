<div class="games view">
<h2><?php  echo __('Game'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($game['Game']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($game['Game']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Launch'); ?></dt>
		<dd>
			<?php echo h($game['Game']['launch']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Update'); ?></dt>
		<dd>
			<?php echo h($game['Game']['update']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Icon'); ?></dt>
		<dd>
			<?php echo h($game['Game']['icon']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($game['Game']['url']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Beta'); ?></dt>
		<dd>
			<?php echo h($game['Game']['beta']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('External'); ?></dt>
		<dd>
			<?php echo h($game['Game']['external']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Engine'); ?></dt>
		<dd>
			<?php echo $this->Html->link($game['Engine']['name'], array('controller' => 'engines', 'action' => 'view', $game['Engine']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Game'), array('action' => 'edit', $game['Game']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Game'), array('action' => 'delete', $game['Game']['id']), null, __('Are you sure you want to delete # %s?', $game['Game']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Games'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Engines'), array('controller' => 'engines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Engine'), array('controller' => 'engines', 'action' => 'add')); ?> </li>
	</ul>
</div>
