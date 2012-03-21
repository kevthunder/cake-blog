<?php
class BlogPost extends BlogAppModel {
	var $name = 'BlogPost';
	var $actsAs = array('Locale' ,'Search.Searchable');
	var $allow_blogger_edit = true;
	
	var $multimedia = array(
		'multimedia' => array(
			'types' => array('photo'),
			'fields' => array('desc')
		)
	);
	
	var $belongsTo = array(
		'User' => array(
			'className'    => 'Auth.User',
			'foreignKey'    => 'user_id'
		)/*,
		'Store' => array(
			'className'    => 'Store',
			'foreignKey'    => 'store_id'
		)*/
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
	

	
}
?>