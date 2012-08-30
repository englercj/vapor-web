<?php

$config['vapor'] = array(
    
    //******************************
    //DO NOT MODIFY BELOW THIS LINE
    //******************************
    'permissions' => array(
        'dashboard' => array(
        ),
        'servers' => array(
            'add',
            'edit',
            'delete',
            'view'
        ),
        'users' => array(
            'add',
            'edit',
            'delete',
            'view'
        ),
        'games' => array(
            'add',
            'edit',
            'delete',
            'view',
            'install'
        ),
        'players' => array(
            'kill',
            'kick',
            'ban',
            'view'
        ),
        'gameservers' => array(
            'start',
            'stop',
            'restart',
            'backup',
            'restore',
            'upload',
            'view'
        )
    ),
    'version' => '0.0.0',
    'version_str' => 'v0.0.0 Pre-Alpha'
);

?>