<div class="servers form">
<?php echo $this->Form->create('Server'); ?>
	<fieldset>
		<legend><?php echo __('Add Server'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('host');
		echo $this->Form->input('port');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Servers'), array('action' => 'index')); ?></li>
	</ul>
</div>
