<?php

class InstallController extends AppController {

  function beforeFilter() {
    $this->Auth->allow('*');
    parent::beforeFilter();
    $install = new File(TMP . "inst.txt");
    
    if (!$install->exists())
      exit("You are not allowed to be here!");

    App::uses('ConnectionManager', 'Model');
  }
  
  //runs environment checks
  function index() {
    $this->layout = 'install';

    $this->set('checks', array(
			       'version' => version_compare(PHP_VERSION, '5.2.8', '>='),
			       'curl' => function_exists('curl_init'),
			       'url_fopen' => ini_get('allow_url_fopen'),
			       'temp' => is_writable(TMP),
			       'database' => is_writable(APP . 'Config/database.php')
			       )
	       );
  }

  //installs database schema and static data
  function database() {
    $this->layout = 'install';

    if($this->request->is('post')) {
      $this->Install->saveDb($this->data);
    }
  }

  //installs SMTP configuration
  function email() {
    $this->layout = 'install';

    if($this->request->is('post')) {
      $this->Install->saveEmail($this->data);
    }
  }

  //installs the superuser
  function superuser() {
    $this->layout = 'install';

    if($this->request->is('post')) {
      $this->loadModel('User');
      $this->data['user_id'] = 1; //superuser
      $this->User->save($this->data);
    }
  }

  //installs a server
  function server() {
    $this->layout = 'install';

    if($this->request->is('post')) {
      $this->loadModel('Server');

      $this->Server->save($this->data);
    }
  }
}