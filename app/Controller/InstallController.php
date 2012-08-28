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

        if ($this->installed) {
            $this->redirect('/');
        } else {
            //never cache this page, and do not output debug in our json
            $this->disableCache();
            Configure::write('debug', 0);
        }
    }

    //runs environment checks
    function index() {
        $this->layout = 'install';
        $cacheSettings = Cache::settings();

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
            ),
            'email' => array(
                'title' => 'Email Configuration',
                'successText' => 'Your email configuration is writtable.',
                'failText' => 'Please allow your webserver write permissions to ' . APP . 'Config' . DS . 'email.php',
                'pass' => is_writable(APP . 'Config' . DS . 'email.php')
            ),
            'cache' => array(
                'title' => 'Cache Settings',
                'successText' => 'The <em>' . $cacheSettings['engine'] . 'Engine</em> is being used for core caching. To change the config edit ' . APP . DS .'Config' . DS . 'core.php',
                'failText' => 'Your cache is NOT working. Please check the settings in ' . APP . DS .'Config' . DS . 'core.php',
                'pass' => !!$cacheSettings
            )
        ));
    }

    //installs database schema and static data
    function database() {
        //never cache this page, and do not output debug in our json
        $this->disableCache();
        Configure::write('debug', 0);

        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
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

                    //add ACOs
                    $this->build_acl();

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
        //never cache this page, and do not output debug in our json
        $this->disableCache();
        Configure::write('debug', 0);

        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
            if (!isset($this->data['skip'])) {
                $this->Install->saveEmail($this->data);
            }

            $result = array('success' => true);
            return new CakeResponse(array('body' => json_encode($result), 'type' => 'json'));
        }
    }

    //installs the superuser
    function superuser() {
        //never cache this page, and do not output debug in our json
        $this->disableCache();
        Configure::write('debug', 0);

        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
            //insert user
            $this->loadModel('User');

            try {
                $dataz = $this->data;
                $dataz['group_id'] = 1; //superuser
                $this->User->create($dataz);
                $user = $this->User->save();
                $result = array('success' => !!$user, 'message' => (!!$user ? '' : 'Unable to save superuser account.'), 'user' => $user);
            } catch (Exception $e) {
                $result = array('success' => false, 'message' => $e->getMessage(), 'exception' => $e);
            }

            return new CakeResponse(array('body' => json_encode($result), 'type' => 'json'));
        }
    }

    //installs a server
    function server() {
        //never cache this page, and do not output debug in our json
        $this->disableCache();
        Configure::write('debug', 0);

        if (!$this->request->isAjax()) {
            $this->layout = 'install';
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax';
        } else if ($this->request->is('post')) {
            $this->loadModel('Server');

            try {
                $this->Server->create($this->data);
                $server = $this->Server->save();
                $result = array('success' => !!$server, 'message' => (!!$server ? '' : 'Unable to save server record.'), 'server' => $server);
            } catch (Exception $e) {
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
            //TODO: Checks to ensure it is completed.
            $this->install->create();
        } else if ($this->request->is('post')) {
            return new CakeResponse(array('body' => json_encode(array('success' => 'true')), 'type' => 'json'));
        }
    }

    /////////////////////////////////////////////////////////////////////////
    // ACO builders
    /////////////////////////////////////////////////////////////////////////
    function build_acl() {
        /*if (!Configure::read('debug')) {
            return $this->_stop();
        }*/
        $log = array();

        $aco = & $this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
            $this->log("Creating root", LOG_DEBUG);
            $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id;
            $log[] = 'Created Aco node for controllers';
        } else {
            $root = $root[0];
        }

        App::uses('File', 'Utility');
        $ControllersFresh = App::objects('Controller');

        foreach ($ControllersFresh as $cnt) {
            $Controllers[] = str_replace('Controller', '', $cnt);
        }
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'build_acl';

        $appcontr = get_class_methods('AppController');

        foreach ($appcontr as $appc) {
            $baseMethods[] = $appc;
        }

        $baseMethods = array_unique($baseMethods);

        $Plugins = $this->_getPluginControllerNames();
        $Controllers = array_merge($Controllers, $Plugins);

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            $methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

            // Do all Plugins First
            if ($this->_isPlugin($ctrlName)) {
                $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
                if (!$pluginNode) {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
                    $pluginNode = $aco->save();
                    $pluginNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
                }
            }
            // find / make controller node
            $controllerNode = $aco->node('controllers/' . $ctrlName);
            if (!$controllerNode) {
                if ($this->_isPlugin($ctrlName)) {
                    $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
                    $aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
                } else {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . $ctrlName;
                }
            } else {
                $controllerNode = $controllerNode[0];
            }

            //clean the methods. to remove those in Controller and private actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = $aco->node('controllers/' . $ctrlName . '/' . $method);
                if (!$methodNode) {
                    $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                    $methodNode = $aco->save();
                    $log[] = 'Created Aco node for ' . $method;
                }
            }
        }
        if (count($log) > 0) {
            debug($log);
        }
    }

    function _getClassMethods($ctrlName = null) {
        if ($this->_isPlugin($ctrlName)) {
            App::uses($this->_getPluginControllerName($ctrlName), $this->_getPluginName($ctrlName) . 'Controller');
        }
        else
            App::uses($ctrlName . 'Controller', 'Controller');


        if (strlen(strstr($ctrlName, '.')) > 0) {
            // plugin's controller
            $ctrlName = str_replace('Controller', '', $this->_getPluginControllerName($ctrlName));
        }
        $ctrlclass = $ctrlName . 'Controller';
        $methods = get_class_methods($ctrlclass);

        // Add scaffold defaults if scaffolds are being used
        $properties = get_class_vars($ctrlclass);
        if (array_key_exists('scaffold', $properties)) {
            if ($properties['scaffold'] == 'admin') {
                $methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
            } else {
                $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
            }
        }
        return $methods;
    }

    function _isPlugin($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '.');
        if (count($arr) > 1) {
            return true;
        } else {
            return false;
        }
    }

    function _getPluginControllerPath($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0] . '.' . $arr[1];
        } else {
            return $arr[0];
        }
    }

    function _getPluginName($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '.');
        if (count($arr) == 2) {
            return $arr[0];
        } else {
            return false;
        }
    }

    function _getPluginControllerName($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[1];
        } else {
            return false;
        }
    }

    /**
     * Get the names of the plugin controllers ...
     *
     * This function will get an array of the plugin controller names, and
     * also makes sure the controllers are available for us to get the
     * method names by doing an App::import for each plugin controller.
     *
     * @return array of plugin names.
     *
     *
     */
    function _getPluginControllerNames() {
        App::uses('Folder', 'Utility');
        $folder = & new Folder();
        $folder->cd(APP . 'Plugin');

        // Get the list of plugins
        $Plugins = $folder->read();
        $Plugins = $Plugins[0];
        $arr = array();

        // Loop through the plugins
        foreach ($Plugins as $pluginName) {
            // Change directory to the plugin
            $didCD = $folder->cd(APP . 'Plugin' . DS . $pluginName . DS . 'Controller');
            if ($didCD) {
                // Get a list of the files that have a file name that ends
                // with controller.php
                $files = $folder->findRecursive('.*Controller\.php');

                // Loop through the controllers we found in the plugins directory
                foreach ($files as $fileName) {
                    // Get the base file name
                    $file = basename($fileName);

                    // Get the controller name
                    //$file = Inflector::camelize(substr($file, 0, strlen($file) - strlen('Controller.php')));
                    if (!preg_match('/^' . Inflector::humanize($pluginName) . 'App/', $file)) {
                        $file = str_replace('.php', '', $file);

                        /// Now prepend the Plugin name ...
                        // This is required to allow us to fetch the method names.
                        $arr[] = Inflector::humanize($pluginName) . "." . $file;
                    }
                }
            }
        }


        return $arr;
    }

}