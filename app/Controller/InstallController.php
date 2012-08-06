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

    if($this->request->isAjax()) {
      $this->disableCache();

      //try to connect to DB
      $link = mysql_connect($this->data['host'],
			    $this->data['login'],
			    $this->data['password']);
      //if connection failed
      if (!$link) {
	$result = array(
			'success' => false,
			'error' => mysql_error()
			);
      } else {
	$dbcheck = mysql_select_db($this->data['database']);
	//if db doesn't exist
	if (!$dbcheck) {
	  $result = mysql_error();
	} else {
	  //the settings are correct, save and open a handle
	  $this->Install->saveDb($this->data);
	  $db = new mysqli($this->data['host'],
			   $this->data['login'],
			   $this->data['password'],
			   $this->data['database']);

	  //execute SQL files
	  $this->Install->execSqlFile($db, APP . 'Config/Sql/schema.sql');
	  $this->Install->execSqlFile($db, App . 'Config/Schema/db_acl.sql');

	  //insert static data files
	  $this->Install->execSqlFile($db, APP . 'Config/Sql/engines.sql');
	  $this->Install->execSqlFile($db, APP . 'Config/Sql/games.sql');

	  //insert SuperUser Group
	  $this->loadModel('Group');
	  $this->Group->save(array('name' => 'SuperUser'));

	  //add base ACO
	  $this->Acl->Aco->create(array(
					'parent_id' => null,
					'alias' => 'controllers'
					)
				  );
	  $this->Acl->Aco->save();

	  //give superuser access to all ACOs
	  $group = $this->Group;
	  $group->id = 1;
	  $this->Acl->allow($group, 'controllers');

	  //setup result
	  $result = array('success' => true);
	}
      }

      return new CakeResponse(array('body' => json_encode($result)),
			      'type' => 'json');
    }
  }

  //installs SMTP configuration
  function email() {
    $this->layout = 'install';

    if($this->request->isAjax()) {
      $this->disableCache();

      $this->Install->saveEmail($this->data);

      $result = array('success' => true);
      return new CakeResponse(array('body' => json_encode($result)),
			      'type' => 'json');
    }
  }

  //installs the superuser
  function superuser() {
    $this->layout = 'install';

    if($this->request->isAjax()) {
      $this->disableCache();

      $this->loadModel('User');

      $this->data['group_id'] = 1; //superuser
      $user = $this->User->save($this->data);

      $result = array('success' => !!$user);
      return new CakeResponse(array('body' => json_encode($result)),
			      'type' => 'json');
    }
  }

  //installs a server
  function server() {
    $this->layout = 'install';

    if($this->request->isAjax()) {
      $this->disableCache();

      $this->loadModel('Server');

      $server = $this->Server->save($this->data);

      $result = array('success' => !!$server);
      return new CakeResponse(array('body' => json_encode($result)),
			      'type' => 'json');
    }
  }
}