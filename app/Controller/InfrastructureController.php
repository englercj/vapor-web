<?php

App::uses('AppController', 'Controller');

/**
 * Infrastructures Controller
 *
 * @property Infrastructure $Infrastructure
 */
class InfrastructureController extends AppController {

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Infrastructure->recursive = 0;
        $this->set('infrastructure', $this->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Infrastructure->id = $id;
        if (!$this->Infrastructure->exists()) {
            throw new NotFoundException(__('Invalid infrastructure'));
        }
        $this->set('infrastructure', $this->Infrastructure->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Infrastructure->create();
            if ($this->Infrastructure->save($this->request->data)) {
                $this->Session->setFlash(__('The infrastructure has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The infrastructure could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->Infrastructure->id = $id;
        if (!$this->Infrastructure->exists()) {
            throw new NotFoundException(__('Invalid infrastructure'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Infrastructure->save($this->request->data)) {
                $this->Session->setFlash(__('The infrastructure has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The infrastructure could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Infrastructure->read(null, $id);
        }
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
        $this->Infrastructure->id = $id;
        if (!$this->Infrastructure->exists()) {
            throw new NotFoundException(__('Invalid infrastructure'));
        }
        if ($this->Infrastructure->delete()) {
            $this->Session->setFlash(__('Infrastructure deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Infrastructure was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}
