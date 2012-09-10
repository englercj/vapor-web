<div class="infrastructure index">
    <h2><?php echo __('Infrastructure'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('host'); ?></th>
            <th><?php echo $this->Paginator->sort('port'); ?></th>
            <th><?php echo $this->Paginator->sort('address_id'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo $this->Paginator->sort('modified'); ?></th>
            <th class="actions"><span><?php echo __('Actions'); ?></span></th>
        </tr>
        <?php foreach ($infrastructure as $node): ?>
            <tr>
                <td><?php echo h($node['Infrastructure']['id']); ?>&nbsp;</td>
                <td><?php echo h($node['Infrastructure']['name']); ?>&nbsp;</td>
                <td><?php echo h($node['Infrastructure']['host']); ?>&nbsp;</td>
                <td><?php echo h($node['Infrastructure']['port']); ?>&nbsp;</td>
                <td><?php echo h($node['Infrastructure']['address_id']); ?>&nbsp;</td>
                <td><?php echo h($node['Infrastructure']['created']); ?>&nbsp;</td>
                <td><?php echo h($node['Infrastructure']['modified']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $node['Infrastructure']['id'])); ?>
                    <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $node['Infrastructure']['id'])); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $node['Infrastructure']['id']), null, __('Are you sure you want to delete # %s?', $node['Infrastructure']['id'])); ?>
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
        <li><?php echo $this->Html->link(__('New Infrastructure Node'), array('action' => 'add')); ?></li>
    </ul>
</div>
