<?php
class BlogPost extends BlogAppModel {
	var $name = 'BlogPost';
	var $actsAs = array('Locale','Search.Searchable');
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
		'Store' => array(
			'className' => 'Store',
			'joinTable' => 'blog_posts_stores',
			'foreignKey' => 'blog_post_id',
			'associationForeignKey' => 'store_id',
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