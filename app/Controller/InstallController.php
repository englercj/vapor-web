<?php

class InstallController extends AppController {

    function beforeFilter() {
        $this->Auth->allow('*');
        parent::beforeFilter();
        //$install = new File(TMP . "inst.txt");

        //if (!$install->exists())
	//exit("You are not allowed to be here!");

        App::uses('ConnectionManager', 'Model');
    }

    //runs environment checks
    function index() {
        $this->layout = 'install';

        $this->set('checks', array(
            'version' => array(
                'title' => 'PHP Version',
                'successText' => 'Your PHP version is ' . PHP_VERSION,
                'failText' => 'Please update your PHP version to 5.2.8 or above, currently you are using ' . PHP_VERSION,
                'pass' => version_compare(PHP_VERSION, '5.2.8', '>=')
            ),
            'curl' => array(
                'title' => 'cURL',
                'successText' => 'The PHP cURL module is installed and enabled.',
                'failText' => 'Please install and enable the cURL module.',
                'pass' => function_exists('curl_init')
            ),
            /*'url_fopen' => array(
                'title' => 'url_fopen',
                'successText' => '',
                'failText' => '',
                'pass' => ini_get('allow_url_fopen')
            ),*/
            'temp' => array(
                'title' => 'Temp Directory',
                'successText' => 'Your temporary directory is writtable',
                'failText' => 'Please allow your webserver write permissions to ' . TMP,
                'pass' => is_writable(TMP)
            ),
            'database' => array(
                'title' => 'Database Configuration',
                'successText' => 'Your database configuration is writtable.',
                'failText' => 'Please allow your webserver write permissions to ' . APP . 'Config' . DS . 'database.php',
                'pass' => is_writable(APP . 'Config' . DS. 'database.php')
            )
        ));
    }

    //installs database schema and static data
    function database() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
            //never cache this page, and do not output debug in our json
            $this->disableCache();
            Configure::write('debug', 0);

            //try to connect to DB
            $link = mysql_connect($this->data['host'], $this->data['login'], $this->data['password']);
            /*try {
                $link = mysql_connect($this->data['host'], $this->data['login'], $this->data['password']);
            } catch(Exception $e) {
                return new CakeResponse(array('body' => json_encode(array(
                    'success' => false,
                    'message' => $e->getMessage()
                )), 'type' => 'json'));
            }*/
            
            //if connection failed
            if (!$link) {
                $result = array(
                    'success' => false,
                    'message' => 'Unable to connect to MySQL Database. Message: ' . mysql_error()
                );
            } else {
                $dbcheck = mysql_select_db($this->data['database']);
                //if db doesn't exist
                if (!$dbcheck) {
                    $result = array(
                        'success' => false,
                        'message' => mysql_error()
                    );
                } else {
                    //the settings are correct, save settings
                    $this->Install->saveDb($this->data);
                    
                    //now open a handle to insert the schema
                    //TODO: Check is schema already exists and don't insert if it does.
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

            return new CakeResponse(array('body' => json_encode($result), 'type' => 'json'));
        }
    }

    //installs SMTP configuration
    function email() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
            //never cache this page, and do not output debug in our json
            $this->disableCache();
            Configure::write('debug', 0);

            $this->Install->saveEmail($this->data);

            $result = array('success' => true);
            return new CakeResponse(array('body' => json_encode($result), 'type' => 'json'));
        }
    }

    //installs the superuser
    function superuser() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
            //never cache this page, and do not output debug in our json
            $this->disableCache();
            Configure::write('debug', 0);

            $this->loadModel('User');

            $this->data['group_id'] = 1; //superuser
            $user = $this->User->save($this->data);

            $result = array('success' => !!$user, 'message' => (!!$user ? '' : 'Unable to save superuser account!'));
            return new CakeResponse(array('body' => json_encode($result), 'type' => 'json'));
        }
    }

    //installs a server
    function server() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
            //never cache this page, and do not output debug in our json
            $this->disableCache();
            Configure::write('debug', 0);

            $this->loadModel('Server');

            $server = $this->Server->save($this->data);

            $result = array('success' => !!$server, 'message' => (!!$server ? '' : 'Unable to save server record!'));
            return new CakeResponse(array('body' => json_encode($result), 'type' => 'json'));
        }
    }
    
    //Placeholder
    function finish() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
            return new CakeResponse(array('body' => json_encode(array('success' => 'true')), 'type' => 'json'));
        }
    }

}