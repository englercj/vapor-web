<div class="infrastructure form">
    <?php echo $this->Form->create('Infrastructure'); ?>
    <fieldset>
        <legend><?php echo __('Add Infrastructure Node'); ?></legend>
        <?php
        echo $this->Form->input('name');
        echo $this->Form->input('host');
        echo $this->Form->input('port');
        echo $this->Form->input('address_id');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Html->link(__('List Infrastructure Nodes'), array('action' => 'index')); ?></li>
    </ul>
</div>
