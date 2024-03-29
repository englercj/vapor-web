<?php $paginate = (isset($paginate) ? $paginate : true); ?>

<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <?php
            foreach($columns as $name => $def) {
                echo '<th>';

                if(isset($def['sortable']))
                    echo $this->Paginator->sort($name);
                else if(isset($def['title']))
                    echo '<span>' . $def['title'] . '</span>';
                else
                    echo '<span>' . Inflector::humanize ($name) . '</span>';

                echo '</th>';
            }

            if(isset($actions))
                echo '<th class="actions"><span>' . __('Actions') . '</span></th>';
            ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach($data as $item):
            $dat = (isset($model) ? $item[$model] : $item);
        ?>
        <tr>
            <?php 
            foreach($columns as $name => $def) {
                echo '<td>' . $dat[$name] . '</td>';
            }

            if(isset($actions)) {
                echo '<td class="actions">';

                foreach($actions as $action => $opts) {
                    $opts['classes'] = (isset($opts['classes']) ? ' ' . $opts['classes'] : '');
                    $opts['post'] = (isset($opts['post']) ? $opts['post'] : false);
                    $opts['title'] = (isset($opts['title']) ? $opts['title'] : Inflector::humanize($action));
                    
                    echo $this->Html->link(__($opts['title']),
                            array(
                                'action' => $action, 
                                $dat['id']
                            ),
                            array(
                                'data-id' => $dat['id'],
                                'data-icon-left' => (isset($opts['icon-left']) ? $opts['icon-left'] : ''),
                                'data-icon-right' => (isset($opts['icon-right']) ? $opts['icon-right'] : ''),
                                'class' => 'button' . ($opts['post'] ?  ' ajax-post' : '') . $opts['classes']
                            )
                        );
                }

                echo '</td>';
            }
            ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p>
    <?php
    if($paginate) { 
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
    }
    ?>	
</p>

<div class="paging">
    <?php
    if($paginate) {
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
    }
    ?>
</div>
