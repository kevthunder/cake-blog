<?php
class BlogCategory extends AppModel {
	var $name = 'BlogCategory';
	var $actsAs = array('Locale', 'Order');
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasAndBelongsToMany = array(
		'BlogPost' => array(
			'className' => 'Blog.BlogPost',
			'joinTable' => 'blog_categories_blog_posts',
			'foreignKey' => 'blog_category_id',
			'associationForeignKey' => 'blog_post_id',
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
	
	
	function afterSave(&$model, $created = false) {
		//////// Clear cache ////////
		if(Configure::read('admin') == true) {
			Cache::delete('BlogCategoryList');
		}
	}
	
}
?>