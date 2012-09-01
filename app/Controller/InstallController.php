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
            'core' => array(
                'title' => 'Core Configuration',
                'successText' => 'Your core configuration is writtable.',
                'failText' => 'Please allow your webserver write permissions to ' . APP . 'Config' . DS . 'core.php',
                'pass' => is_writable(APP . 'Config' . DS . 'core.php')
            ),
            'cache' => array(
                'title' => 'Cache Settings',
                'successText' => 'The <em>' . $cacheSettings['engine'] . 'Engine</em> is being used for core caching. To change the config edit ' . APP . DS .'Config' . DS . 'core.php',
                'failText' => 'Your cache is NOT working. Please check the settings in ' . APP . DS .'Config' . DS . 'core.php',
                'pass' => !!$cacheSettings
            )
        ));
    }
    
    //installs a random Security.salt and Secuirt.cipherSeed
    function security() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install'; //just display page
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax'; //unused
        } else if ($this->request->is('post')) {
            //generate security codes
            $result = array(
                'success' => true,
                'message' => 'Generated a random Security Salt and Cipher Seed',
                'codes' => array()
            );
            
            $result['codes']['salt'] = $this->Install->saveSalt();
            $result['codes']['seed'] = $this->Install->saveCipherSeed();
            
            return $this->jsonResponse($result);
        }
    }

    //installs database schema and static data
    //TODO: This takes a long time (especially the ACO part), need feedback of some kind
    function database() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install'; //just display page
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax'; //unused
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
                    $this->_insertStatic();

                    //insert SuperUser Group
                    $this->loadModel('Group');
                    $this->Group->create(array('name' => 'SuperUser'));
                    $su = $this->Group->save();

                    //add ACOs
                    $this->_build_acos();

                    //give superuser access to all ACOs
                    $group = $this->Group;
                    $group->id = $su['Group']['id'];
                    $this->Acl->allow($group, 'controllers');
                    $this->Acl->allow($group, 'permissions');

                    //setup result
                    $result = array('success' => true);
                }
            }

            return $this->jsonResponse($result);
        }
    }

    //installs SMTP configuration
    function email() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install'; //just display page
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax'; //unused
        } else if ($this->request->is('post')) {
            if (!isset($this->data['skip'])) {
                $this->Install->saveEmail($this->data);
            }

            $result = array('success' => true);
            return $this->jsonResponse($result);
        }
    }

    //installs the superuser
    function superuser() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install'; //just display page
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax'; //unused
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

            return $this->jsonResponse($result);
        }
    }

    //installs a server
    function server() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install'; //just display page
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax'; //unused
        } else if ($this->request->is('post')) {
            $this->loadModel('Server');

            try {
                $this->Server->create($this->data);
                $server = $this->Server->save();
                $result = array('success' => !!$server, 'message' => (!!$server ? '' : 'Unable to save server record.'), 'server' => $server);
            } catch (Exception $e) {
                $result = array('success' => false, 'message' => $e->getMessage(), 'exception' => $e);
            }

            return $this->jsonResponse($result);
        }
    }
    
    function finish() {
        if (!$this->request->isAjax()) {
            $this->layout = 'install'; //when page is loaded, mark install completed
            
            //install completed, store a completed install
            //TODO: Checks to ensure it is actually completed.
            $this->install_file->create();
        } else if ($this->request->is('get')) {
            $this->layout = 'ajax'; //when page is loaded, mark install completed

            //install completed, store a completed install
            //TODO: Checks to ensure it is actually completed.
            $this->install_file->create();
        } else if ($this->request->is('post')) {
            return $this->jsonResponse(array('success' => 'true'));
        }
    }
    
    function _insertStatic() {
        //Engines
        $engines = array(
            array('Engine' => array('id' => 1, 'name' => 'Source')),
            array('Engine' => array('id' => 2, 'name' => 'Goldsource')),
            array('Engine' => array('id' => 3, 'name' => 'Unreal')),
            array('Engine' => array('id' => 4, 'name' => 'Quake')),
            array('Engine' => array('id' => 5, 'name' => 'Unkown'))
        );
        
        $this->loadModel('Engine');
        $this->Engine->saveMany($engines);
        
        //Games
        $games = array(
            array('Game' => array('id' => 1, 'title' => 'Counter-Strike: Source', 'launch' => 'cstrike', 'update' => 'Counter-Strike Source', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 2, 'title' => 'Counter-Strike: Source Beta', 'launch' => 'cstrike', 'update' => 'cssbeta', 'icon' => 'icon', 'url' => 'url', 'beta' => 1, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 3, 'title' => 'Dat of Defeat: Source', 'launch' => 'dod', 'update' => 'dods', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 4, 'title' => 'Half-Life Deathmatch: Source', 'launch' => 'hl1mp', 'update' => null, 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 5, 'title' => 'Half-Life 2: Deathmatch', 'launch' => 'hl2mp', 'update' => 'hl2mp', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 6, 'title' => 'Team Fortress 2', 'launch' => 'tf', 'update' => 'tf', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 7, 'title' => 'Team Fortress 2 Beta', 'launch' => 'tf', 'update' => 'tf_beta', 'icon' => 'icon', 'url' => 'url', 'beta' => 1, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 8, 'title' => 'Left 4 Dead', 'launch' => 'left4dead', 'update' => 'left4dead', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 9, 'title' => 'Left 4 Dead', 'launch' => 'left4dead', 'update' => 'l4d_full', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 10, 'title' => 'Left 4 Dead 2', 'launch' => 'left4dead2', 'update' => 'left4dead2', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 11, 'title' => 'Left 4 Dead 2 Demo', 'launch' => null, 'update' => 'left4dead2_demo', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 12, 'title' => 'Counter-Strike', 'launch' => 'cstrike', 'update' => 'cstrike', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 13, 'title' => 'Counter-Strike Beta', 'launch' => 'cstrike', 'update' => 'cstrike', 'icon' => 'icon', 'url' => 'url', 'beta' => 1, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 14, 'title' => 'Counter-Strike: Condition Zero', 'launch' => 'czero', 'update' => 'czero', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 15, 'title' => 'Deathmatch Classic', 'launch' => 'dmc', 'update' => 'dmc', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 16, 'title' => 'Day of Defeat', 'launch' => 'dod', 'update' => 'dod', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 17, 'title' => 'Half-Life: Opposing Force', 'launch' => 'gearbox', 'update' => 'gearbox', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 18, 'title' => 'Ricochet', 'launch' => 'ricochet', 'update' => 'ricochet', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 19, 'title' => 'Team Fortress Classic', 'launch' => 'tfc', 'update' => 'tfc', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 20, 'title' => 'Half-Life', 'launch' => 'valve', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 2)),
            array('Game' => array('id' => 21, 'title' => 'Dark Messiah', 'launch' => 'mmdarkmessiah', 'update' => 'darkmessiah', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 22, 'title' => 'Garry\'s Mod', 'launch' => 'garrysmod', 'update' => 'garrysmod', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 23, 'title' => 'Red Orchestra: Ostfront 41-45', 'launch' => 'redorchestra', 'update' => 'redorchestra', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 3)),
            array('Game' => array('id' => 24, 'title' => 'Red Orchestra: Ostfront 41-45 Beta', 'launch' => 'redorchestra', 'update' => 'redorchestra_beta', 'icon' => 'icon', 'url' => 'url', 'beta' => 1, 'external' => 0, 'engine_id' => 3)),
            array('Game' => array('id' => 25, 'title' => 'Darkest Hour: Europe 44-45', 'launch' => null, 'update' => 'darkesthour', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 3)),
            array('Game' => array('id' => 26, 'title' => 'Mare Nostrum', 'launch' => null, 'update' => 'marenostrum', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 3)),
            array('Game' => array('id' => 27, 'title' => 'The Ship', 'launch' => 'ship', 'update' => 'ship', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 28, 'title' => 'SiN 1', 'launch' => null, 'update' => 'sin', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 4)),
            array('Game' => array('id' => 29, 'title' => 'ThreadSpace: Hyperbol', 'launch' => null, 'update' => 'tshb', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 5)),
            array('Game' => array('id' => 30, 'title' => 'Sven Co-op', 'launch' => 'svencoop', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 31, 'title' => 'Firearms', 'launch' => 'firearms', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 32, 'title' => 'Natural Selection', 'launch' => 'ns', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 33, 'title' => 'Action Half-Life', 'launch' => 'action', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 34, 'title' => 'Brain Bread', 'launch' => 'brainbread', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 35, 'title' => 'Earth\'s Special Forces', 'launch' => 'esf', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 36, 'title' => 'Hostile Intent', 'launch' => 'hostileintent', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 37, 'title' => 'International Online Soccer', 'launch' => 'ios', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 38, 'title' => 'The Specialists', 'launch' => 'ts', 'update' => 'valve', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 2)),
            array('Game' => array('id' => 39, 'title' => 'Half-Life 2: Capture The Flag', 'launch' => 'hl2ctf', 'update' => null, 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 1)),
            array('Game' => array('id' => 40, 'title' => 'Source Forts', 'launch' => 'sourceforts', 'update' => 'episode1,hl2mp', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 1)),
            array('Game' => array('id' => 41, 'title' => 'Insurgency', 'launch' => 'insurgency', 'update' => 'insurgency', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 42, 'title' => 'GoldenEye: Source', 'launch' => 'gesource', 'update' => 'orangebox', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 1)),
            array('Game' => array('id' => 43, 'title' => 'Fortress Forever', 'launch' => 'ff', 'update' => null, 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 1)),
            array('Game' => array('id' => 44, 'title' => 'Half-Life 2: Coop', 'launch' => 'hl2coop', 'update' => null, 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 1, 'engine_id' => 1)),
            array('Game' => array('id' => 45, 'title' => 'Age of Chivalry', 'launch' => null, 'update' => 'ageofchivalry', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 46, 'title' => 'Alien Swarm', 'launch' => null, 'update' => 'alienswarm', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 47, 'title' => 'D.I.P.R.I.P. Warm Up', 'launch' => null, 'update' => 'diprip', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 48, 'title' => 'Killing Floor', 'launch' => null, 'update' => 'killingfloor', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 3)),
            array('Game' => array('id' => 49, 'title' => 'Killing Floor Beta', 'launch' => null, 'update' => 'killingfloor_beta', 'icon' => 'icon', 'url' => 'url', 'beta' => 1, 'external' => 0, 'engine_id' => 3)),
            array('Game' => array('id' => 50, 'title' => 'Defence Alliance 2', 'launch' => null, 'update' => 'defencealliance2', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 3)),
            array('Game' => array('id' => 51, 'title' => 'Monday Night Combat', 'launch' => null, 'update' => 'mondaynightcombat', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 3)),
            array('Game' => array('id' => 52, 'title' => 'Dystopia', 'launch' => null, 'update' => 'dystopia', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 53, 'title' => 'Eternal Silence', 'launch' => null, 'update' => 'esmod', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 54, 'title' => 'Pirates Vikings & Knights II', 'launch' => null, 'update' => 'pvkii', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 55, 'title' => 'Smashball', 'launch' => null, 'update' => 'smashball', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 56, 'title' => 'Synergy', 'launch' => null, 'update' => 'synergy', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 57, 'title' => 'Zombie Panic! Source', 'launch' => null, 'update' => 'zps', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 1)),
            array('Game' => array('id' => 58, 'title' => 'Serious Sam HD', 'launch' => null, 'update' => 'seriossamhdse', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 5)),
            array('Game' => array('id' => 59, 'title' => 'Aliens vs. Predator', 'launch' => null, 'update' => 'alienvspredator', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 5)),
            array('Game' => array('id' => 60, 'title' => 'Natural Selection 2', 'launch' => null, 'update' => 'naturalselection2', 'icon' => 'icon', 'url' => 'url', 'beta' => 0, 'external' => 0, 'engine_id' => 5))
        );
        
        $this->loadModel('Game');
        $this->Engine->saveMany($games);
    }

    /////////////////////////////////////////////////////////////////////////
    // ACO builders
    /////////////////////////////////////////////////////////////////////////
    function _build_acos($respond = false) {
        /*if (!Configure::read('debug')) {
            return $this->_stop();
        }*/
        $log = array();

        $aco = & $this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
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
            $controllerNode = $aco->node('controllers/' . str_replace('.', '/', $ctrlName));
            if (!$controllerNode) {
                if ($this->_isPlugin($ctrlName)) {
                    $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
                    $aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . str_replace('.', '/', $ctrlName);
                } else {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . str_replace('.', '/', $ctrlName);
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
                // find / make controller node
                $methodNode = $aco->node('controllers/' . str_replace('.', '/', $ctrlName) . '/' . $method);
                if (!$methodNode) {
                    $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                    $methodNode = $aco->save();
                    $log[] = 'Created Aco node for ' . str_replace('.', '/', $ctrlName) . '/' . $method;
                }
            }
        }
        
        //Now add custom permissions
        $permRoot = $aco->node('permissions');
        if (!$permRoot) {
            $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'permissions'));
            $permRoot = $aco->save();
            $permRoot['Aco']['id'] = $aco->id;
            $log[] = 'Created Aco node for permissions';
        } else {
            $permRoot = $permRoot[0];
        }
        
        $perms = Configure::read('vapor.permissions');
        foreach($perms as $perm => $subs) {
            $node = $aco->node('permissions/' . $perm);
            
            if(!$node) {
                $aco->create(array('parent_id' => $permRoot['Aco']['id'], 'model' => null, 'alias' => $perm));
                $node = $aco->save();
                $node['Aco']['id'] = $aco->id;
                $log[] = 'Created Aco node for ' . $perm;
            }
            
            if(!empty($subs)) {
                foreach($subs as $sub) {
                    $subNode = $aco->node('permissions/' . $perm . '/' . $sub);
                    if(!$subNode) {
                        $aco->create(array('parent_id' => $node['Aco']['id'], 'model' => null, 'alias' => $sub));
                        $aco->save();
                        $log[] = 'Created Aco node for ' . $perm . '/' . $sub;
                    }
                }
            }
        }
        
        //log output to debug if necessary
        if (count($log) > 0 && Configure::read('debug') > 0) {
            foreach($log as $entry) {
                $this->log($entry, 'debug');
            }
        }
        
        if($respond) //for running a GET to this action, will be removed on release
            return $this->jsonResponse(array('success' => true));
    }

    function _getClassMethods($ctrlName = null) {
        if ($this->_isPlugin($ctrlName)) {
            App::uses($this->_getPluginControllerName($ctrlName), $this->_getPluginName($ctrlName) . '.Controller');
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
        // NO SCAFFOLDS, THIS CODE ALWAYS RETURNS TRUE ANYWAY
        /*$properties = get_class_vars($ctrlclass);
        if (array_key_exists('scaffold', $properties)) {
            if ($properties['scaffold'] == 'admin') {
                $methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
            } else {
                $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
            }
        }*/
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
        $arr = String::tokenize($ctrlName, '.');
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
                    $plugin = str_replace(' ', '', Inflector::humanize($pluginName));
                    if (!preg_match('/^' . $plugin . 'App/', $file)) {
                        $file = str_replace('.php', '', $file);

                        /// Now prepend the Plugin name ...
                        // This is required to allow us to fetch the method names.
                        $arr[] = $pluginName . '.' . $file;
                    }
                }
            }
        }


        return $arr;
    }

}