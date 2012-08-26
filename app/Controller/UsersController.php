<?php

App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        
        $this->set('user', $this->User->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            
            if ($this->User->save($this->request->data)) {
                $this->setFlash('The user has been saved', 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('The user could not be saved. Please, try again.', 'bad');
            }
        }
        
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->setFlash('The user has been saved', 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('The user could not be saved. Please, try again.', 'bad');
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
        }
        
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        
        if ($this->User->delete()) {
            $this->setFlash('User deleted', 'good');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->setFlash('User was not deleted', 'bad');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function login() {
        $this->layout = 'install';
        
        //already logged in
        if ($this->Session->read('Auth.User')) {
            $this->setFlash('You are logged in!', 'info');
            $this->redirect('/', null, false);
        }
        //logging in
        else if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->setFlash('Your username or password was incorrect.', 'bad');
            }
        }
    }

    public function logout() {
        $this->setFlash('Good-Bye', 'good');
        $this->redirect($this->Auth->logout());
    }

}
