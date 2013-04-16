<?php
class BlogPost extends BlogAppModel {
	var $name = 'BlogPost';
	var $actsAs = array('Locale');
	var $displayField = 'title';
	var $allow_blogger_edit = true;
	
	var $multimedia = array(
		'multimedia' => array(
			'types' => array('photo'),
			'fields' => array('desc', 'format'=>array()) //uses config : Blog.cropFormats
		)
	);
	
	var $belongsTo = array(
		'User' => array(
			'className'    => 'Auth.User',
			'foreignKey'    => 'user_id'
		)
	);  
	
	
	var $hasAndBelongsToMany = array(
		'BlogCategory' => array(
			'className' => 'Blog.BlogCategory',
			'joinTable' => 'blog_categories_blog_posts',
			'foreignKey' => 'blog_post_id',
			'associationForeignKey' => 'blog_category_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	
	
	function __construct( $id = false, $table = NULL, $ds = NULL ){
		if(in_array('Search',App::objects('plugin'))){
			$this->actsAs[] = 'Search.Searchable';
		}
		if(in_array('Comment',App::objects('plugin'))){
			$this->actsAs[] = 'Comment.Commented';
		}
		App::import('Lib', 'Blog.BlogConfig');
		$this->multimedia['multimedia']['fields']['format'] = BlogConfig::load('cropFormats');
		
		parent::__construct( $id, $table, $ds );
	}
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		unset($extra['joins']);
		unset($extra['group']);
		return $this->find('count',array_merge(compact('conditions','recursive'),$extra));
	}
	
	function afterSave(&$model, $created = false) {
		//////// Clear cache ////////
		if(Configure::read('admin') == true) {
			Cache::delete('BlogCategoryList');
		}
	}
	
}
?>