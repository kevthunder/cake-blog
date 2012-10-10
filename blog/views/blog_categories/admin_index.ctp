<div class="blogCategories index">
	<?php
		echo $this->Form->create('Blog Category', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __d('blog', 'Blog Categories / Tags');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('title_fre');?></th>			
			<th><?php echo $this->Paginator->sort('title_eng');?></th>		
			<th><?php __('order'); ?></th>		
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true), null => __('No', true));
			foreach ($blogCategories as $blogCategory) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $blogCategory['BlogCategory']['id']; ?>&nbsp;</td>
						<td class="title_fre"><?php echo $blogCategory['BlogCategory']['title_fre']; ?>&nbsp;</td>
						<td class="title_eng"><?php echo $blogCategory['BlogCategory']['title_eng']; ?>&nbsp;</td>
						<td class="order">
							<a href="<?php echo $this->Html->url(array('action' => 'move', $blogCategory['BlogCategory']['id'], 'up')); ?>"><img src="<?php echo $this->Html->url('/img/admin/up_arrow_hover.png'); ?>" /></a>
							<a href="<?php echo $this->Html->url(array('action' => 'move', $blogCategory['BlogCategory']['id'], 'down')); ?>"><img src="<?php echo $this->Html->url('/img/admin/down_arrow_hover.png'); ?>" /></a>
						</td>
						<td class="actions">
							<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $blogCategory['BlogCategory']['id']), array('class' => 'edit')); ?>
							<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $blogCategory['BlogCategory']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $blogCategory['BlogCategory']['id'])); ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Blog Category', true)), array('action' => 'add')); ?></li>		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Blog Posts', true)), array('controller' => 'blog_posts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Blog Post', true)), array('controller' => 'blog_posts', 'action' => 'add')); ?> </li>
	</ul>
</div>