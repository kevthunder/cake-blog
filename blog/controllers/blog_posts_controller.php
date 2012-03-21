<?php
class BlogPostsController extends BlogAppController {

	var $name = 'BlogPosts';

	function beforeFilter() {
		parent::beforeFilter();
		$this->checked_blogger = false;
		if(!empty($this->user['User']['id'])) {
			$this->checked_blogger = $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->user['User']['id']), 'blog_post');
		}
		$this->set('checked_blogger',$this->checked_blogger);
		
		$this->allow_blogger_edit = false;
		if(!empty($this->user['User']['id'])) {
			$this->allow_blogger_edit = $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->user['User']['id']), 'blogger_edit');
		}
		//$this->allow_blogger_edit = true;
		$this->set('allow_blogger_edit',$this->allow_blogger_edit);
	}
	
	function _boxes(){
		$first = $this->BlogPost->find('first',array('fields'=>array('created'),'order'=>array('created'=>'asc')));
		$this->set('startDate', $first['BlogPost']['created']);
		
		$this->BlogPost->User->bindModel(array(
			'hasOne' => array(
				'Aro' => array(
					'className' => 'Aro',
					'foreignKey'=>'foreign_key',
					'conditions'=>array('model'=>'user')
				)
			)
		));
		$this->BlogPost->User->recursive = 0;
		$bloggers = $this->BlogPost->User->find('all',array('conditions'=>array('Aro.parent_id'=>8)));
		$this->set('bloggers', $bloggers);
	}
	
	function index($blogger = null) {
		if(!$blogger && isset($this->params['named']['blogger']) && is_numeric($this->params['named']['blogger'])) {
			$blogger = $this->params['named']['blogger'];
		}
		
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['BlogPost']['q']) && strlen(trim($this->data['BlogPost']['q'])) > 0) {
			$q = $this->data['BlogPost']['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('BlogPost.title LIKE' => '%'.$q.'%',
														'BlogPost.short_text LIKE' => '%'.$q.'%',
														'BlogPost.text LIKE' => '%'.$q.'%');
		}
		
		$month = null;
		if(!empty($this->params['named']['m'])){
			$month = $this->params['named']['m'];
		}
		$year = null;
		if(!empty($this->params['named']['y'])){
			$year = $this->params['named']['y'];
		}
		if($month){
			if($year == null){
				$year = date('Y');
			}
			$firstDay = new DateTime("$year-$month-01");
			$lastDay = new DateTime("$year-$month-01");
			$lastDay->modify("+1 month");
			$this->paginate['conditions']["BlogPost.created <"] = $lastDay->format("Y-m-d H:i:s");
			$this->paginate['conditions']["BlogPost.created >"] = $firstDay->format("Y-m-d H:i:s");
			$this->set('month',$month);
			$this->set('year',$year);
		}elseif($year){
			$firstDay = new DateTime("$year-01-01");
			$lastDay = new DateTime("$year-01-01");
			$lastDay->modify("+1 year");
			$this->paginate['conditions']["BlogPost.created <"] = $lastDay->format("Y-m-d H:i:s");
			$this->paginate['conditions']["BlogPost.created >"] = $firstDay->format("Y-m-d H:i:s");
			$this->set('year',$year);
		}
		
		if(!empty($blogger)){
			$user_id = $blogger;
			$this->set('user_id', $user_id);
			$this->set('blogger', $this->BlogPost->User->read(null,$user_id));
			$this->paginate['conditions']['user_id']=$user_id;
		}
		
		$this->paginate['order'] = array('BlogPost.created'=>'desc');
		$this->BlogPost->recursive = 0;
		$this->set('blogPosts', $this->paginate());
		
		$this->_boxes();
	}

	
	function user_lasts() {
		$this->BlogPost->recursive = -1;
		$this->paginate = array(
			'fields'=>array('user_id','MAX(BlogPost.created) as lastdate'), 
			'order'=>'MAX(BlogPost.created) DESC',
			'group'=>'BlogPost.user_id'
		);
		$cond = array();
		$posts = $this->paginate($this->BlogPost);
		//debug($posts);
		foreach($posts as $post){
			$cond['or'][] = array('BlogPost.user_id'=>$post['BlogPost']['user_id'],'BlogPost.created'=>$post[0]['lastdate']);
		}
		$this->BlogPost->recursive = 0;
		$posts = $this->BlogPost->find('all', array('conditions'=>$cond,'order'=>'BlogPost.created DESC'));
		$this->set('blogPosts', $posts);
		
		
		
		$this->_boxes();
		//debug($posts);
	}
	
	function view($id = null) {
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'blog post'));
			$this->redirect(array('action' => 'index'));
		}
		$this->BlogPost->Behaviors->attach('Containable');
		$this->BlogPost->contain(array(
				'User'=>array(
					'BlogPost'=>array(
						'limit'=>4,
						'conditions'=>array('id NOT'=>$id),
						'fields'=>array('id','title'),
						'order'=>array('created'=>'desc')
					)
				),
				'Store'
			));
		$blogPost = $this->BlogPost->read(null, $id);
		$this->set('blogPost', $blogPost);
		
		$this->BlogPost->User->bindModel(array(
			'hasOne' => array(
				'Aro' => array(
					'className' => 'Aro',
					'foreignKey'=>'foreign_key',
					'conditions'=>array('model'=>'user')
				)
			)
		));
		$this->BlogPost->User->recursive = 0;
		$bloggers = $this->BlogPost->User->find('all',array('conditions'=>array('User.id NOT'=>$blogPost['BlogPost']['user_id'],'Aro.parent_id'=>8)));
		$this->set('bloggers', $bloggers);
	}

	function add() {
		if(!$this->checked_blogger){
			$this->redirect(array('plugin' => 'auth', 'controller' => 'users', 'action' => 'login', 'admin' => false));
			exit;
		}
		
		if (!empty($this->data)) {
			if(empty($this->data['user_id']) || !$this->allow_blogger_edit){
				$this->data['user_id'] = $this->user['User']['id'];
			}
			$this->BlogPost->create();
			if ($this->BlogPost->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'blog post'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'blog post'));
			}
		}
		/* Specific */
		$stores = $this->BlogPost->Store->find('list');
		$this->set(compact('stores'));
		/* End Specific */
	}

	function edit($id = null) {
		if(!$this->checked_blogger){
			$this->redirect(array('plugin' => 'auth', 'controller' => 'users', 'action' => 'login', 'admin' => false));
			exit;
		}
	
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'blog post'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if(!$this->allow_blogger_edit){
				unset($this->data['user_id']);
			}
			if ($this->BlogPost->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'blog post'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'blog post'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->BlogPost->read(null, $id);
		}
		/* Specific */
		$stores = $this->BlogPost->Store->find('list',array('fields'=>array('title_fre')));
		$this->set(compact('stores'));
		/* End Specific */
	}
	
	function admin_index() {
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['BlogPost']['q']) && strlen(trim($this->data['BlogPost']['q'])) > 0) {
			$q = $this->data['BlogPost']['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('BlogPost.title_fre LIKE' => '%'.$q.'%',
														'BlogPost.title_eng LIKE' => '%'.$q.'%',
														'BlogPost.short_text_fre LIKE' => '%'.$q.'%',
														'BlogPost.short_text_eng LIKE' => '%'.$q.'%',
														'BlogPost.text_fre LIKE' => '%'.$q.'%',
														'BlogPost.text_eng LIKE' => '%'.$q.'%');
		}

		$this->BlogPost->recursive = 0;
		$this->set('blogPosts', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			if(empty($this->data['user_id']) || !$this->allow_blogger_edit){
				$this->data['user_id'] = $this->user['User']['id'];
			}
			$this->BlogPost->create();
			if ($this->BlogPost->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'blog post'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'blog post'));
			}
		}
		if($this->allow_blogger_edit){
			$this->BlogPost->User->bindModel(array(
				'hasOne' => array(
					'Aro' => array(
						'className' => 'Aro',
						'foreignKey'=>'foreign_key',
						'conditions'=>array('model'=>'user')
					)
				)
			));
			$this->BlogPost->User->recursive = 0;
			$f_users = $this->BlogPost->User->find('all',array('fields'=>array('User.id','User.first_name','User.last_name'),'conditions'=>array('Aro.parent_id'=>8)));
			$users = array();
			foreach($f_users as $user){
				$users[$user['User']['id']] = $user['User']['first_name'].' '.$user['User']['last_name'];
			}
			$this->set('users', $users);
		}
		/* Specific */
		//$stores = $this->BlogPost->Store->find('list');
		$stores = $this->BlogPost->Store->find('list',array('order'=>'title_fre ASC', 'fields'=>array('title_fre')));
		$this->set(compact('stores'));
		/* End Specific */
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'blog post'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if(!$this->allow_blogger_edit){
				unset($this->data['user_id']);
			}
			if ($this->BlogPost->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'blog post'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'blog post'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->BlogPost->read(null, $id);
		}
		
		if($this->allow_blogger_edit){
			$this->BlogPost->User->bindModel(array(
				'hasOne' => array(
					'Aro' => array(
						'className' => 'Aro',
						'foreignKey'=>'foreign_key',
						'conditions'=>array('model'=>'user')
					)
				)
			));
			$this->BlogPost->User->recursive = 0;
			$f_users = $this->BlogPost->User->find('all',array('fields'=>array('User.id','User.first_name','User.last_name'),'conditions'=>array('Aro.parent_id'=>8)));
			$users = array();
			foreach($f_users as $user){
				$users[$user['User']['id']] = $user['User']['first_name'].' '.$user['User']['last_name'];
			}
			$this->set('users', $users);
		}
		
		/* Specific */
		$stores = $this->BlogPost->Store->find('list',array('order'=>'title_fre ASC','fields'=>array('title_fre')));
		$this->set(compact('stores'));
		/* End Specific */
	}
	
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'blog post'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->BlogPost->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Blog post'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Blog post'));
		$this->redirect(array('action' => 'index'));
	}
	
}
?>