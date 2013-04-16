<?php
class BlogPostsController extends BlogAppController {

	var $name = 'BlogPosts';
	var $components = array('Blog.BlogFunctions');

	function __construct(){
		if(in_array('Comment',App::objects('plugin'))){
			$this->components[] = 'Comment.Commenting';
		}
		parent::__construct();
	}
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->checked_blogger = false;
		if(!empty($this->user['User']['id'])) {
			$this->checked_blogger = $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->user['User']['id']), 'blog_post');
		}
		$this->set('checked_blogger',$this->checked_blogger);
		
		$this->allow_blogger_edit = false;
		if(!empty($this->user['User']['id']) && BlogConfig::load('useBlogger')) {
			$this->allow_blogger_edit = $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->user['User']['id']), 'blogger_edit');
		}
		//$this->allow_blogger_edit = true;
		$this->set('allow_blogger_edit',$this->allow_blogger_edit);
		
		if(!isset($this->params['admin']) || $this->params['admin'] == false) {
			//List of categories of blog
			$listCategories = $this->BlogFunctions->findListCount();
			$this->set(compact('listCategories'));
			
			//List of Archives
			$listArchives = $this->BlogFunctions->findArchivesCount(null, $this->lang);
			$this->set(compact('listArchives'));
		}
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
		
		$bloggerNode = $this->BlogPost->User->Aro->find('first',array('conditions'=>array('Aro.alias'=>'blogger'),'recursive'=>-1));
		$bloggers = $this->BlogPost->User->find('all',array(
			'conditions'=>array(
				'Aro.lft >'=>$bloggerNode['Aro']['lft'],
				'Aro.rght <'=>$bloggerNode['Aro']['rght']
			)
		));
		$this->set('bloggers', $bloggers);
	}
	
	function index($blogger = null, $blog_category_id = null, $m = null, $y = null) {
		if(!$blogger && isset($this->params['named']['blogger']) && is_numeric($this->params['named']['blogger'])) {
			$blogger = $this->params['named']['blogger'];
		}elseif(!$blogger && isset($this->params['blogger']) && is_numeric($this->params['blogger'])) {
			$blogger = $this->params['blogger'];
		}
		if(!$blog_category_id && isset($this->params['named']['blog_category_id']) && is_numeric($this->params['named']['blog_category_id'])) {
			$blog_category_id = $this->params['named']['blog_category_id'];
		}elseif(!$blog_category_id && isset($this->params['blog_category_id']) && is_numeric($this->params['blog_category_id'])) {
			$blog_category_id = $this->params['blog_category_id'];
		}
		if($blog_category_id == 0){ $blog_category_id = null; } //Options for All Categories link
		
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
		if(isset($this->params['named']['m']) && !empty($this->params['named']['m'])){
			$month = $this->params['named']['m'];
		}elseif(isset($this->params['m']) && !empty($this->params['m'])){
			$month = $this->params['m'];
		}
		$year = null;
		if(isset($this->params['named']['y']) && !empty($this->params['named']['y'])){
			$year = $this->params['named']['y'];
		}elseif(isset($this->params['y']) && !empty($this->params['y'])){
			$year = $this->params['y'];
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
		
		if(is_numeric($blog_category_id)){
			$this->BlogPost->BlogCategory->recursive = -1;
			$blogCategory = $this->BlogPost->BlogCategory->read(null, $blog_category_id);
			$this->set('blog_category_id', $blog_category_id);
			$this->set('blogCategory', $blogCategory);
			$this->BlogPost->bindModel(array(
				'hasOne' => array(
					'BlogCategoriesBlogPost' => array(
						'foreignKey'=>'blog_post_id'
					),
					'FirstCategory'=>array(
						'className' => 'BlogCategory',
						'foreignKey'=>false,
						'conditions'=>array('BlogCategoriesBlogPost.blog_category_id = FirstCategory.id')
					)
				)
			), false);
			$this->paginate['conditions']['blog_category_id'] = $blog_category_id;
		}
		
		$this->paginate['order'] = array('BlogPost.created'=>'desc');
		$this->paginate['limit'] = 10;
		$this->BlogPost->recursive = 1;
		$this->set('blogPosts', $this->paginate());
		$this->set('blogCategoryId', $blog_category_id);
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
		}elseif(!$id && isset($this->params['id']) && is_numeric($this->params['id'])) {
			$id = $this->params['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'blog post'));
			$this->redirect(array('action' => 'index'));
		}
		$this->BlogPost->Behaviors->attach('Containable');
		$this->BlogPost->User->bindModel(array(
			'hasMany' => array(
				'BlogPost' => array(
					'className' => 'Blog.BlogPost',
					'foreignKey' => 'user_id',
				)
			)
		));
		$contain = array(
			'User'=>array(
				'BlogPost'=>array(
					'limit'=>4,
					'conditions'=>array('id NOT'=>$id),
					'fields'=>array('id','title'),
					'order'=>array('created'=>'desc')
				)
			),
			'BlogCategory'
		);
		if(in_array('Comment',App::objects('plugin'))){
			$contain[] = 'Comment';
		}
		$this->BlogPost->contain($contain);
		$this->BlogPost->recursive = 1;
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
		
		$bloggerNode = $this->BlogPost->User->Aro->find('first',array('conditions'=>array('Aro.alias'=>'blogger'),'recursive'=>-1));
		$bloggers = $this->BlogPost->User->find('all',array(
			'conditions'=>array(
				'Aro.lft >'=>$bloggerNode['Aro']['lft'],
				'Aro.rght <'=>$bloggerNode['Aro']['rght']
			)
		));
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
		
		if(in_array('Comment',App::objects('plugin'))){
			$this->paginate['joins'][] = $this->BlogPost->getCommentJoin();
			$this->paginate['group'] = 'BlogPost.id';
			$this->paginate['fields'] = array('COUNT(Comment.id) as nb_comment','BlogPost.*');
		}
		
		
		$this->BlogPost->recursive = 0;
		$this->paginate['order'] = 'BlogPost.created DESC';
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
				
			$bloggerNode = $this->BlogPost->User->Aro->find('first',array('conditions'=>array('Aro.alias'=>'blogger'),'recursive'=>-1));
			$bloggers = $this->BlogPost->User->find('all',array(
				'conditions'=>array(
					'Aro.lft >'=>$bloggerNode['Aro']['lft'],
					'Aro.rght <'=>$bloggerNode['Aro']['rght']
				)
			));
			$users = array();
			foreach($bloggers as $user){
				$users[$user['User']['id']] = $user['User']['first_name'].' '.$user['User']['last_name'];
			}
			$this->set('users', $users);
		}
		$this->BlogPost->BlogCategory->displayField = 'title_fre';
		$blogCategories = $this->BlogPost->BlogCategory->find('list');
		$this->set(compact('blogCategories'));
		
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
			$bloggerNode = $this->BlogPost->User->Aro->find('first',array('conditions'=>array('Aro.alias'=>'blogger'),'recursive'=>-1));
			$bloggers = $this->BlogPost->User->find('all',array(
				'conditions'=>array(
					'Aro.lft >'=>$bloggerNode['Aro']['lft'],
					'Aro.rght <'=>$bloggerNode['Aro']['rght']
				)
			));
			$users = array();
			foreach($bloggers as $user){
				$users[$user['User']['id']] = $user['User']['first_name'].' '.$user['User']['last_name'];
			}
			$this->set('users', $users);
		}
		$this->BlogPost->BlogCategory->displayField = 'title_fre';
		$blogCategories = $this->BlogPost->BlogCategory->find('list');
		$this->set(compact('blogCategories'));
		
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