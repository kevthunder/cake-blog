<?php
class BlogFunctionsComponent extends Object {

	//Get cateogries With count articles
	function findListCount($options = array()){
		/*$cache = Cache::read('BlogCategoryList');
		$cacheKey = 'BlogCategoryList-'.Configure::read('Config.language');
		if(!empty($cache[$cacheKey])){
			return $cache[$cacheKey];
		}*/
		App::import('Lib', 'Blog.BlogConfig');
		$defOpt = BlogConfig::load('categoryList');
		$opt = Set::merge($defOpt,$options);
		
		$this->BlogCategory = ClassRegistry::init('Blog.BlogCategory');
		$this->BlogCategory->recursive = 1;
		$categoryList = array();
		if($opt['allCategoriesLink']){
			$chTotal = $this->BlogCategory->BlogPost->find('count');
			$categoryList[0] = array(
				'id' => null,
				'title' => __($opt['allCategoriesLink']['label'], true),
				'count' => $chTotal,
			);
		}
		$joinModel = $this->BlogCategory->BlogCategoriesBlogPost;
		$findOpt = array(
			'fields'=>array(
				$this->BlogCategory->alias.'.id',$this->BlogCategory->alias.'.title',
				'COUNT('.$this->BlogCategory->BlogPost->alias.'.'.$this->BlogCategory->BlogPost->primaryKey.') as `count`',
			),
			'conditions'=>array(
				$this->BlogCategory->BlogPost->alias.'.active'=>true
			),
			'group'=>array(
				$this->BlogCategory->alias.'.'.$this->BlogCategory->primaryKey,
			),
			'joins' => array(
				array(
					'alias' => $joinModel->alias,
					'table'=> $joinModel->table,
					'type' => 'INNER',
					'conditions' => array(
						$joinModel->alias.'.blog_category_id = '.$this->BlogCategory->alias.'.'.$this->BlogCategory->primaryKey,
					)
				),
				array(
					'alias' => $this->BlogCategory->BlogPost->alias,
					'table'=> $this->BlogCategory->BlogPost->table,
					'type' => 'INNER',
					'conditions' => array(
						$joinModel->alias.'.blog_post_id = '.$this->BlogCategory->BlogPost->alias.'.'.$this->BlogCategory->BlogPost->primaryKey,
					)
				)
			),
			'recursive' => -1,
		);
		$categories = $this->BlogCategory->find('all',$findOpt);
		if(!empty($categories)){
			foreach($categories as $k=>$cat):
				$cb = $cat[0]['count'];
				if($cb > 0 || $opt['hideEmpty'] != true){
					$categoryList[$cat['BlogCategory']['id']] = array(
						'id' => $cat['BlogCategory']['id'],
						'title' => $cat['BlogCategory']['title'],
						'count' => $cb,
					);
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
					'date' => date_('Y-m-01', strtotime($b[0]['BlogPost']['created']), $lang),
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