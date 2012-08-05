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
	<?php if (!empty($engine['Game'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Launch'); ?></th>
		<th><?php echo __('Update'); ?></th>
		<th><?php echo __('Icon'); ?></th>
		<th><?php echo __('Url'); ?></th>
		<th><?php echo __('Beta'); ?></th>
		<th><?php echo __('External'); ?></th>
		<th><?php echo __('Engine Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($engine['Game'] as $game): ?>
		<tr>
			<td><?php echo $game['id']; ?></td>
			<td><?php echo $game['title']; ?></td>
			<td><?php echo $game['launch']; ?></td>
			<td><?php echo $game['update']; ?></td>
			<td><?php echo $game['icon']; ?></td>
			<td><?php echo $game['url']; ?></td>
			<td><?php echo $game['beta']; ?></td>
			<td><?php echo $game['external']; ?></td>
			<td><?php echo $game['engine_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'games', 'action' => 'view', $game['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'games', 'action' => 'edit', $game['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'games', 'action' => 'delete', $game['id']), null, __('Are you sure you want to delete # %s?', $game['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
