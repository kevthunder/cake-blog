<?php
class BlogConfig extends Object {
	/*
		App::import('Lib', 'Blog.BlogConfig');
		BlogConfig::load();
	*/
	
	var $loaded = false;
	var $defaultConfig = array(
		'categoryList' => array(
			'hideEmpty' => true,
			'allCategoriesLink' => array(
				'label' => 'Toutes les catégories',
			)
		),
		'useBlogger' => false,
	);
	var $trueToDefault = array(
		'categoryList.allCategoriesLink',
	);
	
	//$_this =& BlogConfig::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new BlogConfig();
		}
		return $instance[0];
	}
	
	function _parseTrueToDefault($config){
		$_this =& BlogConfig::getInstance();
		$trueToDefault = Set::normalize($_this->trueToDefault);
		foreach($trueToDefault as $path => $options){
			if(Set::extract($path,$config) === true){
				if(empty($options)){
					$options = Set::extract($path,$_this->defaultConfig);
				}
				$config = Set::insert($config,$path,$options);
			}
		}
		return $config;
	}
	
	function load($path = true){
		$_this =& BlogConfig::getInstance();
		if(!$_this->loaded){
			config('plugins/blog');
			$config = Configure::read('Blog');
			$config = Set::merge($_this->defaultConfig,$config);
			$config = $_this->_parseTrueToDefault($config);
			Configure::write('Blog',$config);
			$_this->loaded = true;
		}
		if(!empty($path)){
			return Configure::read('Blog'.($path!==true?'.'.$path:''));
		}
	}
	
}
?>