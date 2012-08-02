<?php

class Install extends AppModel {

  public $name = 'Install';
  public $useTable = false;

  //Save database configuration
  function saveDb($data = array()) {
    $config = APP . 'Config/database.php';
    $template = APP . 'Config/database.php.default';
    
    $tokens = array(
		 'datasource' => '%datasource%',
		 'host' => '%host%',
		 'port' => '%port%',
		 'login' => '%login%',
		 'password' => '%password%',
		 'database' => '%database%',
		 'prefix' => '%prefix%',
		 'encoding' => '%encoding%'
		 );

    $new_file = implode(file($template));

    $str = str_replace($tokens, $data, $new_file);

    //now, TOTALLY rewrite the file
    $fp = fopen($config, 'w');

    fwrite($fp, $str, strlen($str));

    return $str;
  }
}