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
			echo $this->element('categories_list',array('plugin'=>'blog','include'=>$blogPost['BlogCategory'],'counts'=>false));
		?>
		<?php
			if(in_array('Comment',App::objects('plugin'))){
				echo $this->element('comments_box',array('plugin'=>'comment'));
			}
		?>
	</div>
</div>
