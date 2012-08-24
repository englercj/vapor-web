<?php

App::uses('ConnectionManager', 'Model');

class InstallController extends AppController {
    
    var $dbSettings = array();

    function beforeFilter() {
        $this->Auth->allow('*');
        parent::beforeFilter();
        //$install = new File(TMP . "inst.txt");
        //if (!$install->exists())
        //exit("You are not allowed to be here!");
        
        if($this->installed) {
            $this->redirect('/');
        }
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
            /* 'url_fopen' => array(
              'title' => 'url_fopen',
              'successText' => '',
              'failText' => '',
              'pass' => ini_get('allow_url_fopen')
              ), */
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
                'pass' => is_writable(APP . 'Config' . DS . 'database.php')
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
                    //store db settings for other methods to use
                    $this->dbSettings = $this->data;
                    
                    //the settings are correct, save settings to file
                    $this->Install->saveDb($this->data);
                    
                    //connect CakePHP to DB with new settings
                    //since we only just saved them we have to
                    //manually create the datasource
                    $this->loadModel('ConnectionManager');
                    $this->ConnectionManager->drop('default');
                    $ds = $this->ConnectionManager->create('default', $this->data);

                    //TODO: Check is schema already exists and/or upgrade is needed

                    //execute SQL files
                    $this->Install->createSchema($ds);

                    //insert static data files
                    $this->Install->insertStatic($ds);

                    //insert SuperUser Group
                    $this->loadModel('Group');
                    $this->Group->create(array('name' => 'SuperUser'));
                    $this->Group->save();

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
            
            if(!isset($this->data['skip'])) {
                $this->Install->saveEmail($this->data);
            }

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
            
            //insert user
            $this->loadModel('User');
            
            try {
                $dataz = $this->data;
                $dataz['group_id'] = 1; //superuser
                $this->User->create($dataz);
                $user = $this->User->save();
                $result = array('success' => !!$user, 'message' => (!!$user ? '' : 'Unable to save superuser account.'), 'user' => $user);
            } catch(Exception $e) {
                $result = array('success' => false, 'message' => $e->getMessage(), 'exception' => $e);
            }
            
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
            
            try {
                $this->Server->create($this->data);
                $server = $this->Server->save();
                $result = array('success' => !!$server, 'message' => (!!$server ? '' : 'Unable to save server record.'), 'server' => $server);
            } catch(Exception $e) {
                $result = array('success' => false, 'message' => $e->getMessage(), 'exception' => $e);
            }

            return new CakeResponse(array('body' => json_encode($result), 'type' => 'json'));
        }
    }

    //Placeholder
    function finish() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
            
            //install completed, store a completed install
            $this->loadModel('ConnectionManager');
            $this->Install->complete($this->ConnectionManager->getDataSource('default'));
        } else if ($this->request->is('post')) {
            return new CakeResponse(array('body' => json_encode(array('success' => 'true')), 'type' => 'json'));
        }
    }

}