<?php 
if(isset($listCategories) && !empty($listCategories)){
	if(!empty($include)){
		if(!empty($include[0]['id'])){
			$include = Set::extract('{n}.id',$include);
		}
		if(!empty($include[0]['BlogCategory']['id'])){
			$include = Set::extract('{n}.BlogCategory.id',$include);
		}
		$listCategories = array_intersect_key($listCategories,array_flip($include));
	}
	if(!isset($counts)){
		$counts = true;
	}
?>
<div class="widget categories">
	<h3><?php __('Catégories'); ?></h3>
	<ul>
		<?php foreach($listCategories as $catId=>$catName): ?>
			<li><a href="<?php echo $this->Html->url(array('plugin'=>'blog', 'controller'=>'blog_posts', 'action'=>'index', 'blog_category_id'=>$catId)) ?>"><?php echo $catName['title'] ?><?php if( $counts ) { ?> <span>(<?php echo $catName['count'] ?>)</span><?php }?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php } ?>