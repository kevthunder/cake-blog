<div class="blogPosts index">
	<?php
		echo $this->Form->create('BlogPost', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h1><?php __('Blog Posts');?></h1>
	
	<div class="posts">
	
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true), null => __('No', true));
			foreach ($blogPosts as $blogPost) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' altrow';
				}
				?>
				<div class="post<?php echo $class ?>">
					<?php
						echo $this->Multimedia->img($blogPost['BlogPost']['multimedia'], array(
							'size' => '150x150',
							'method' => 'crop',
						));
					?>
					<div class="post_info">
						<span class="date"><?php echo date_('j F Y',strtotime($blogPost['BlogPost']['created'])) ?></span><br />
						<h2><a href="<?php echo $this->Html->url(array('action'=>'view',$blogPost['BlogPost']['id'])); ?>"><?php echo $blogPost['BlogPost']['title'] ?></a></h2>
						<?php echo $blogPost['BlogPost']['short_text'] ?>
						<a class="readmore" href="<?php echo $this->Html->url(array('action'=>'view',$blogPost['BlogPost']['id'])); ?>"><?php __('Lire la suite'); ?></a>
					</div>
				</div>
				<?php
			}
		?>
	</div>
	
	<p class="paging">
		<?php
			echo $this->Paginator->counter(array(
				'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
			));
		?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('« '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 |
		<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' »', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
