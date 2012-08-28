<?php

class EmailConfig {

    public $default = array(
        'transport' => 'Smtp',
        'from' => '%from%',
        'host' => '%host%',
        'port' => '%port%',
        'timeout' => 60,
        'username' => '%username%',
        'password' => '%password%',
        'client' => null,
        'log' => false,
        //'charset' => 'utf-8',
        //'headerCharset' => 'utf-8',
    );

}
