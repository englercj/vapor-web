<div class="infrastructure view">
    <h2><?php echo __('Infrastructure'); ?></h2>
    <dl>
        <dt><?php echo __('Id'); ?></dt>
        <dd>
            <?php echo h($infrastructure['Infrastructure']['id']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Name'); ?></dt>
        <dd>
            <?php echo h($infrastructure['Infrastructure']['name']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Host'); ?></dt>
        <dd>
            <?php echo h($infrastructure['Infrastructure']['host']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Port'); ?></dt>
        <dd>
            <?php echo h($infrastructure['Infrastructure']['port']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Address Id'); ?></dt>
        <dd>
            <?php echo h($infrastructure['Infrastructure']['address_id']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Created'); ?></dt>
        <dd>
            <?php echo h($infrastructure['Infrastructure']['created']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Modified'); ?></dt>
        <dd>
            <?php echo h($infrastructure['Infrastructure']['modified']); ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Infrastructure Node'), array('action' => 'edit', $infrastructure['Infrastructure']['id'])); ?> </li>
        <li><?php echo $this->Form->postLink(__('Delete Infrastructure Node'), array('action' => 'delete', $infrastructure['Infrastructure']['id']), null, __('Are you sure you want to delete # %s?', $infrastructure['Infrastructure']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Infrastructures Node'), array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Infrastructure Node'), array('action' => 'add')); ?> </li>
    </ul>
</div>
