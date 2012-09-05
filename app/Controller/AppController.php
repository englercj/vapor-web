<?php

App::uses('Controller', 'Controller');

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class AppController extends Controller {

    public $components = array(
        'Acl',
        'AutoLogin',
        'Auth' => array(
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
            ),
            'loginAction' => array('controller' => 'users', 'action' => 'login'),
            'loginRedirect' => array('controller' => 'dashboard', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'loginError' => 'Your username or password is incorrect.',
            'authError' => 'You do not have access to that page.',
            'flash' => array('element' => 'flash/flash', 'key' => 'bad'),
            'autoRedirect' => false
        ),
        'Session',
        'Cookie'
    );
    
    public $helpers = array('Html', 'Form', 'Session', 'AssetCompress.AssetCompress');
    
    public $allowedActions = array();

    public function beforeFilter() {
        if(!$this->Session->valid()) {
            $this->Session->renew();
        }
        
        //Set cookie names
        $this->Cookie->name = 'vapor';
        
        //Check if we need to redirect to install pages
        $allowed = array('install');
        
        $this->install_file = new File(APP . '.installed');
        $this->installed = $this->install_file->exists();
        
        if (!$this->installed && !(in_array($this->params["controller"], $allowed))) {
            $this->redirect(array('controller' => 'install', 'action' => 'index'));
        }
        
        //Setup logged_in and current_user variables
        $logged_in = $this->Auth->loggedIn();
        $current_user = $this->Auth->user();
        
        $this->set(compact('logged_in', 'current_user'));
        
        //Check for auth errors and redirect to main page
        /*$path = 'controllers/' . $this->params['controller'] . '/' . $this->params['action'];
        if($this->Acl->check($current_user, $path)) {
            return true;
        } else if(in_array($this->params['action'], $this->allowedActions)) {
            return true;
        } else {
            if(!$logged_in) {
                $this->setFlash('You must log in to access this page', 'bad');
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            } else {
                //render the access denied page
                $this->redirect(array('controller' => 'pages', 'action' => 'display', 'denied'));
            }
        }*/
    }
    
    //utility method to make setFlash more usable for how the views use it
    public function setFlash($message, $key = 'flash', $params = array()) {
        $this->Session->setFlash(__($message), 'flash/flash', $params, $key);
    }
    
    public function jsonResponse($body = array()) {
        return new CakeResponse(array('body' => json_encode($body), 'type' => 'json'));
    }
}
