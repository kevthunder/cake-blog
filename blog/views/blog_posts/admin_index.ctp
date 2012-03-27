<div class="blogPosts index">
	<?php
		echo $this->Form->create('Blog Post', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Blog Posts');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('title_fre');?></th>			
			<th><?php echo $this->Paginator->sort('title_eng');?></th>			
			<th><?php echo $this->Paginator->sort('home');?></th>		
			<th><?php echo $this->Paginator->sort('active');?></th>		
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true), null => __('No', true));
			foreach ($blogPosts as $blogPost) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $blogPost['BlogPost']['id']; ?>&nbsp;</td>
						<td class="title_fre"><?php echo $blogPost['BlogPost']['title_fre']; ?>&nbsp;</td>
						<td class="title_eng"><?php echo $blogPost['BlogPost']['title_eng']; ?>&nbsp;</td>
						<td class="title_eng"><?php echo $bool[$blogPost['BlogPost']['home']]; ?>&nbsp;</td>
						<td class="title_eng"><?php echo $bool[$blogPost['BlogPost']['active']]; ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $blogPost['BlogPost']['id']), array('class' => 'edit')); ?>
							<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $blogPost['BlogPost']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $blogPost['BlogPost']['id'])); ?>
						</td>
					</tr>
				<?php
			}
		?>
	</table>
	
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
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Blog Post', true)), array('action' => 'add')); ?></li>	</ul>
</div>