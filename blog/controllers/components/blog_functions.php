<?php
class BlogFunctionsComponent extends Object {

	//Get cateogries With count articles
	function findListCount($options = array()){
		/*$cache = Cache::read('BlogCategoryList');
		$cacheKey = 'BlogCategoryList-'.Configure::read('Config.language');
		if(!empty($cache[$cacheKey])){
			return $cache[$cacheKey];
		}*/
		
		$options['hideEmpty'] = (!isset($options['hideEmpty'])) ? false : $options['hideEmpty'];
		$options['allCategoriesLink'] = (!isset($options['allCategoriesLink'])) ? true : $options['allCategoriesLink'];
		
		$this->BlogCategory = ClassRegistry::init('BlogCategory');
		$this->BlogCategory->recursive = 1;
		$categoryList = array();
		if($options['allCategoriesLink'] == true){
			$chTotal = $this->BlogCategory->BlogPost->find('count');
			$categoryList[0] = __('Toutes les catégories', true).' <span>('.$chTotal.')</span>';
		}
		$this->BlogCategory->hasAndBelongsToMany['BlogPost']['conditions'] = array('BlogPost.active'=>true);
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
		
		/*$cache[$cacheKey] = $categoryList;
		Cache::write('BlogCategoryList', $cache);*/
		
		return $categoryList;
	}
	
	function findArchivesCount($since = null, $lang = 'fre'){
		/*$cache = Cache::read('BlogCategoryList');
		$cacheKey = 'BlogArchivesList-'.Configure::read('Config.language');
		if(!empty($cache[$cacheKey])){
			return $cache[$cacheKey];
		}*/
		
		
		if(!$since){
			$since = strtotime("-12 months",strtotime(date("Y-m-1")));
		} else{
			$since = strtotime($since);
		}
		
		$this->BlogPost = ClassRegistry::init('BlogPost');
		$this->BlogPost->recursive = -1;
		$monthList = array();
		$archiveList = array();
		$bp = $this->BlogPost->find('all', array('order'=>'BlogPost.created DESC', 'conditions'=>array('BlogPost.created >= '=>date('Y-m-d H:i:s', $since))));
		if(!empty($bp)){
			foreach($bp as $k=>$b):
				$monthList[date_('Y-m', strtotime($b['BlogPost']['created']), $lang)][] = $b;
			endforeach;
			
			foreach($monthList as $ym=>$b):
				$cb = count($b);
				$archiveList[$ym] = array(
					'm' => date_('m', strtotime($b[0]['BlogPost']['created']), $lang),
					'y' => date_('Y', strtotime($b[0]['BlogPost']['created']), $lang),
					'date' => date_('Y-m-d', strtotime($b[0]['BlogPost']['created']), $lang),
					'cb' => $cb
				);
			endforeach;
			
		}
		
		
		/*$cache[$cacheKey] = $monthList;
		Cache::write('BlogCategoryList', $cache);*/
		
		return $archiveList;
	}

}
?>