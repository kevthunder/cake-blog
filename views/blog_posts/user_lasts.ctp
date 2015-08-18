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
			<th><?php echo $this->Paginator->sort('title');?></th>			
			<th><?php echo $this->Paginator->sort('short_text');?></th>			
			<th><?php echo $this->Paginator->sort('text');?></th>			
			<th><?php echo $this->Paginator->sort('multimedia');?></th>			
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
						<td class="title"><?php echo $blogPost['BlogPost']['title']; ?>&nbsp;</td>
						<td class="short_text"><?php echo $blogPost['BlogPost']['short_text']; ?>&nbsp;</td>
						<td class="text"><?php echo $blogPost['BlogPost']['text']; ?>&nbsp;</td>
						<td class="multimedia"><?php echo $text->truncate($blogPost['BlogPost']['multimedia'], 150, array('exact' => false)); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('View', true), array('action' => 'view', $blogPost['BlogPost']['id']), array('class' => 'view')); ?>
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
