<?php

class Install extends AppModel {

  public $name = 'Install';
  public $useTable = false;

  //Save database configuration
  function saveDb($data = array()) {
    $this->saveX(array(
		'datasource' => '%datasource%',
		'host' => '%host%',
		'port' => '%port%',
		'login' => '%login%',
		'password' => '%password%',
		'database' => '%database%',
		'prefix' => '%prefix%',
		'encoding' => '%encoding%'
		),
	  $data,
	  'database'
	  );
  }

  function saveEmail($data = array()) {
    $this->saveX(array(
		'from' => '%from%',
		'host' => '%host%',
		'port' => '%port%',
		'username' => '%username%',
		'password' => '%password%',
		),
	  $data,
	  'email'
	  );
  }

  function saveX($tokens, $data, $which) {
    $config = APP . 'Config/' . $which . '.php';
    $template = APP . 'Config/' . $which . '.php.default';

    $new_file = implode(file($template));

    $str = str_replace($tokens, $data, $new_file);

    //now, TOTALLY rewrite the file
    $fp = fopen($config, 'w');

    fwrite($fp, $str, strlen($str));

    return $str;
  }
}