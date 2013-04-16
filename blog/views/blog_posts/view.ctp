<div class="blogPosts view">
	<?php
		echo $this->Multimedia->img($blogPost['BlogPost']['multimedia'], array(
			'size' => '150x150',
			'method' => 'crop',
		));
	?>
	<div class="post_info">
		<span class="date"><?php echo date_('j F Y',strtotime($blogPost['BlogPost']['created'])) ?></span><br />
		<h2><?php echo $blogPost['BlogPost']['title'] ?></h2>
		<?php echo $blogPost['BlogPost']['text'] ?>
		<?php
			if(in_array('Comment',App::objects('plugin'))){
				echo $this->element('comments_box',array('plugin'=>'comment'));
			}
		?>
	</div>
	
	<aside class="sidebar">
		<div class="categoriesList">
			<?php
				echo $this->element('categories_list',array('plugin'=>'blog','include'=>$blogPost['BlogCategory'],'counts'=>false));
			?>
		</div>
		<?php if( !empty($blogPost['User']['id']) ) { ?>
		
		<div class="author">
			<h3><?php __('Author'); ?><br><span class="name"><?php echo $blogPost['User']['first_name'].' '.$blogPost['User']['last_name']  ?></span></h3>
			<?php if( !empty($blogPost['User']['m_photo']) ) { ?>
			<img src="<?php
				echo $this->Multimedia->path($blogPost['User']['m_photo'], array(
					'size' => '100x100',
					'method' => 'crop',
				));
			?>" alt="" class="photo" />
			<?php }?>
			<?php if( !empty($blogPost['User']['bio']) ) { ?>
			<div class="bio"><?php echo $blogPost['User']['bio']  ?></div>
			<?php }?>
			<?php if(!empty($blogPost['User']['BlogPost']) ) { ?>
			<div class="moreFromAuthor">
				<h4><?php echo str_replace('%name%',$blogPost['User']['first_name'].' '.$blogPost['User']['last_name'],__('More from %name%',true)); ?></h4>
				<ul>
					<?php foreach ($blogPost['User']['BlogPost'] as $m_post) { ?>
					<li>
						<a href="<?php echo $this->Html->url(array('action'=>'view',$m_post['id'])); ?>"><?php echo $m_post['title'] ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php }?>
		</div>
		<?php } ?>
	</aside>
</div>
