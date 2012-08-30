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
            'loginRedirect' => '/',
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login')
        ),
        'Session'
    );
    
    public $helpers = array('Html', 'Form', 'Session', 'AssetCompress.AssetCompress');

    public function beforeFilter() {
        //Check if we need to redirect to install pages
        $allowed = array('install');
        
        $this->install = new File(APP . 'installed');
        $this->installed = $this->install->exists();
        
        if (!$this->installed && !(in_array($this->params["controller"], $allowed))) {
            $this->redirect(array('controller' => 'install', 'action' => 'index'));
        }
        
        //Setup logged_in and current_user variables
        $logged_in = $this->Auth->loggedIn();
        $current_user = $this->Auth->user();
        
        $this->set(compact('logged_in', 'current_user'));
    }
    
    //utility method to make setFlash more usable for how the views use it
    public function setFlash($message, $key = 'flash', $params = array()) {
        $this->Session->setFlash(__($message), 'flash', $params, $key);
    }
}
