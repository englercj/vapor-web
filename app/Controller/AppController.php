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
        $allowed = array('install');
        
        $this->install = new File(APP . 'installed');
        $this->installed = $this->install->exists();
        
        if (!$this->installed && !(in_array($this->params["controller"], $allowed))) {
            $this->redirect(array('controller' => 'install', 'action' => 'index'));
        }
    }
    
    //utility method to make setFlash more usable for how the views use it
    public function setFlash($message, $key = 'flash', $params = array()) {
        $this->Session->setFlash($message, 'flash', $params, $key);
    }
}
