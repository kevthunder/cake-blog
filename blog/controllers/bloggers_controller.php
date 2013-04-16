<?php
class BloggersController extends BlogAppController {

	var $name = 'Bloggers';
	var $uses = array('Auth.User','Blog.BlogPost');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->allow_blogger_edit = false;
		if(!empty($this->user['User']['id'])) {
			$this->allow_blogger_edit = $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->user['User']['id']), 'blogger_edit');
		}
		$this->set('allow_blogger_edit',$this->allow_blogger_edit);
	}
	
	function index() {
		$this->User->bindModel(array(
			'hasOne' => array(
				'Aro' => array(
					'className' => 'Aro',
					'foreignKey'=>'foreign_key',
					'conditions'=>array('model'=>'user')
				)
			)
		),false);
		$bloggerNode = $this->User->Aro->find('first',array('conditions'=>array('Aro.alias'=>'blogger'),'recursive'=>-1));
		$this->paginate['conditions']['Aro.lft >']=$bloggerNode['Aro']['lft'];
		$this->paginate['conditions']['Aro.rght <']=$bloggerNode['Aro']['rght'];
		$this->User->recursive = 0;
		//$this->User->paginate();
		$this->set('users', $this->paginate());
	}
	
	
	function admin_index() {
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['Artist']['q']) && strlen(trim($this->data['Artist']['q'])) > 0) {
			$q = $this->data['Artist']['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('User.username LIKE' => '%'.$q.'%',
														'User.password LIKE' => '%'.$q.'%',
														'User.email LIKE' => '%'.$q.'%',
														'User.first_name LIKE' => '%'.$q.'%',
														'User.last_name LIKE' => '%'.$q.'%');
		}
		$this->User->bindModel(array(
			'hasOne' => array(
				'Aro' => array(
					'className' => 'Aro',
					'foreignKey'=>'foreign_key',
					'conditions'=>array('model'=>'user')
				)
			)
		),false);
		
		$bloggerNode = $this->User->Aro->find('first',array('conditions'=>array('Aro.alias'=>'blogger'),'recursive'=>-1));
		$this->paginate['conditions']['Aro.lft >']=$bloggerNode['Aro']['lft'];
		$this->paginate['conditions']['Aro.rght <']=$bloggerNode['Aro']['rght'];
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
		$this->set('fields',$this->User->schema());
	}
	
	function admin_add() {
		if (!empty($this->data)) {
		
			$this->User->create();
			$this->data['User']['group'] = 'blogger';
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'user'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'user'));
			}
		}
		$this->set('fields',$this->User->schema());
		/*
		$this->Acl->Aro->displayField = 'alias';
		$this->Acl->Aro->primaryKey = 'alias';
		$groups = $this->Acl->Aro->generatetreelist(array('Aro.model' => NULL), null, null, '|-----');
		$this->set(compact('groups'));
		*/
	}
	
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'user'));
			$this->redirect(array('action' => 'index'));
		}
		/*
		$user_id = isset($this->data['User']['id']) && is_numeric($this->data['User']['id']) ? $this->data['User']['id'] : $id;

		$aro = $this->Acl->Aro->find('first', array('conditions' => array('Aro.model' => 'User', 'Aro.foreign_key' => $user_id)));
		$group = $this->Acl->Aro->getparentnode($aro['Aro']['id']);
		*/
		
		if (!empty($this->data)) {
			$this->data['User']['group'] = 'blogger';
			
			if(isset($this->data['User']['password']) && (strlen(trim($this->data['User']['password'])) == 0 || $this->Auth->password('') == $this->data['User']['password'])) {
				unset($this->data['User']['password']);
			}

			if ($this->User->save($this->data)) {
				
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'user'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'user'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
			/*
			$this->Acl->Aro->displayField = 'alias';
			$this->Acl->Aro->primaryKey = 'alias';
			$groups = $this->Acl->Aro->generatetreelist(array('Aro.model' => NULL), null, null, '|-----');

			$this->set(compact('groups', 'aro', 'group'));
			*/
		}
	}
	
}
?>