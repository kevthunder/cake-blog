<?php 
	if(isset($listCategories) && !empty($listCategories)){
		echo '<div class="widget categories">';
			echo '<h2>'.__('Catégories', true).'</h2>';
			echo '<ul>';
				foreach($listCategories as $catId=>$catName):
					echo '<li><a href="'.$this->Html->url(array('plugin'=>'blog', 'controller'=>'blog_posts', 'action'=>'index', 'blog_category_id'=>$catId)).'">'.$catName.'</a></li>';
				endforeach;
			echo '</ul>';
		echo '</div>';
	
	}
?>