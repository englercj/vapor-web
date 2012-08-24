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
        
        try {
            $this->loadModel('Install');
            $this->installed = ($this->Install->find('count') > 0);
        } catch(Exception $e) {
            $this->installed = false;
        }
        
        if (!$this->installed && !(in_array($this->params["controller"], $allowed))) {
            $this->redirect(array('controller' => 'install', 'action' => 'index'));
        }
    }
}
