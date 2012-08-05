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
  
  function index() {
    $this->layout = 'install';
    
    /* Check environment */
  }

  function createSchema() {
    
  }
}