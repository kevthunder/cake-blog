<?php
class BlogCategoriesController extends AppController {

	var $name = 'BlogCategories';

	function index() {
		$this->BlogCategory->recursive = 0;
		$this->set('blogCategories', $this->paginate());
	}

	function view($id = null) {
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'blog category'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('blogCategory', $this->BlogCategory->read(null, $id));
	}

	
	function admin_index() {
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['BlogCategory']['q']) && strlen(trim($this->data['BlogCategory']['q'])) > 0) {
			$q = $this->data['BlogCategory']['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('BlogCategory.title_fre LIKE' => '%'.$q.'%',
														'BlogCategory.title_eng LIKE' => '%'.$q.'%',
														'BlogCategory.desc_fre LIKE' => '%'.$q.'%',
														'BlogCategory.desc_eng LIKE' => '%'.$q.'%');
		}

		$this->BlogCategory->recursive = 0;
		$this->set('blogCategories', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->BlogCategory->create();
			if ($this->BlogCategory->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'blog category'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'blog category'));
			}
		}
		$this->BlogCategory->BlogPost->displayField = 'title_fre';
		$blogPosts = $this->BlogCategory->BlogPost->find('list');
		$this->set(compact('blogPosts'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'blog category'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->BlogCategory->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'blog category'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'blog category'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->BlogCategory->read(null, $id);
		}
		$this->BlogCategory->BlogPost->displayField = 'title_fre';
		$blogPosts = $this->BlogCategory->BlogPost->find('list');
		$this->set(compact('blogPosts'));
	}
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'blog category'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->BlogCategory->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Blog category'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Blog category'));
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_move($id = null, $direction = 'up') {
		$this->BlogCategory->move($id, $direction);
		$this->redirect(array('action' => 'index'));
	}
	
}
?>