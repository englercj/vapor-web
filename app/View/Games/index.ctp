<div class="games index">
	<h2><?php echo __('Games'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('launch'); ?></th>
			<th><?php echo $this->Paginator->sort('update'); ?></th>
			<th><?php echo $this->Paginator->sort('icon'); ?></th>
			<th><?php echo $this->Paginator->sort('url'); ?></th>
			<th><?php echo $this->Paginator->sort('beta'); ?></th>
			<th><?php echo $this->Paginator->sort('external'); ?></th>
			<th><?php echo $this->Paginator->sort('engine_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($games as $game): ?>
	<tr>
		<td><?php echo h($game['Game']['id']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['title']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['launch']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['update']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['icon']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['url']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['beta']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['external']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($game['Engine']['name'], array('controller' => 'engines', 'action' => 'view', $game['Engine']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $game['Game']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $game['Game']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $game['Game']['id']), null, __('Are you sure you want to delete # %s?', $game['Game']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Engines'), array('controller' => 'engines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Engine'), array('controller' => 'engines', 'action' => 'add')); ?> </li>
	</ul>
</div>
