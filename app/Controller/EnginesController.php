<?php
App::uses('AppController', 'Controller');
/**
 * Engines Controller
 *
 * @property Engine $Engine
 */
class EnginesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Engine->recursive = 0;
		$this->set('engines', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Engine->id = $id;
		if (!$this->Engine->exists()) {
			throw new NotFoundException(__('Invalid engine'));
		}
		$this->set('engine', $this->Engine->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Engine->create();
			if ($this->Engine->save($this->request->data)) {
				$this->Session->setFlash(__('The engine has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The engine could not be saved. Please, try again.'));
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
		$this->Engine->id = $id;
		if (!$this->Engine->exists()) {
			throw new NotFoundException(__('Invalid engine'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Engine->save($this->request->data)) {
				$this->Session->setFlash(__('The engine has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The engine could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Engine->read(null, $id);
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
		$this->Engine->id = $id;
		if (!$this->Engine->exists()) {
			throw new NotFoundException(__('Invalid engine'));
		}
		if ($this->Engine->delete()) {
			$this->Session->setFlash(__('Engine deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Engine was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
