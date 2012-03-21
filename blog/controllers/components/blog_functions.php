<?php
class BlogFunctionsComponent extends Object {

	//Get cateogries With count articles
	function findListCount($options = array()){
		$options['hideEmpty'] = (!isset($options['hideEmpty'])) ? false : $options['hideEmpty'];
		
		$this->BlogCategory = ClassRegistry::init('BlogCategory');
		$this->BlogCategory->recursive = 1;
		$categoryList = array();
		$categories = $this->BlogCategory->find('all');
		if(!empty($categories)){
			foreach($categories as $k=>$cat):
				$cb = count($cat['BlogPost']);
				if($cb > 0 || $options['hideEmpty'] != true){
					$categoryList[$cat['BlogCategory']['id']] = $cat['BlogCategory']['title'].' <span>('.$cb.')</span>';
				}
			endforeach;
		}
		//pr($categories);
		return $categoryList;
	}

}
?>