<?php

App::uses('Controller', 'Controller');

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class AppController extends Controller {

    public $components = array(
        'Acl',
        'Auth' => array(
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
            ),
            'loginAction' => array('controller' => 'users', 'action' => 'login'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'loginRedirect' => array('controller' => 'posts', 'action' => 'add')
        ),
        'Session'
    );
    public $helpers = array('Html', 'Form', 'Session', 'AssetCompress.AssetCompress');

    public function beforeFilter() {
        $install = new File(TMP . 'inst');
        $allowed = array('install');
        
        $this->installed = $install->exists();
        
        if (!$this->installed && !(in_array($this->params["controller"], $allowed))) {
            $this->redirect(array('controller' => 'install', 'action' => 'index'));
        }
    }
    
    /*/*compile less
    public function beforeRender() {
        // only compile it on development mode
        //if (Configure::read('debug') > 0)
        //{
            // import the file to application
            App::import('Vendor', 'lessc');

            // set the LESS file location
            $less = ROOT . DS . APP_DIR . DS . 'webroot' . DS . 'less' . DS . 'main.less';

            // set the CSS file to be written
            $css = ROOT . DS . APP_DIR . DS . 'webroot' . DS . 'css' . DS . 'main.css';

            // compile the file
            lessc::ccompile($less, $css);
        //}
        //parent::beforeRender();
    }*/

}
