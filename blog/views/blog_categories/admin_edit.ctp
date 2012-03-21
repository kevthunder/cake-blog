<div class="blogCategories form">
	<?php echo $this->Form->create('BlogCategory');?>
		<fieldset>
			<legend><?php printf(__('Edit %s', true), __('Blog Category', true)); ?></legend>
			<?php
				echo $this->Form->input('active');
				echo $this->Form->input('id');

				?>
					<fieldset class="francais">
						<legend><?php __('Français'); ?></legend>
						<?php
							echo $this->Form->input('title_fre');
							echo $this->Form->input('desc_fre', array('class'=>'tinymce'));
						?>
					</fieldset>
				<?php

				?>
					<fieldset class="english">
						<legend><?php __('English'); ?></legend>
						<?php
							echo $this->Form->input('title_eng');
							echo $this->Form->input('desc_eng', array('class'=>'tinymce'));
						?>
					</fieldset>
				<?php
				echo $this->Form->input('BlogPost', array('multiple'=>'checkbox', 'div'=>array('class'=>'multiple_checkbox')));
				$this->Multimedia->sidebar();
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('BlogCategory.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('BlogCategory.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Blog Categories', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Blog Posts', true)), array('controller' => 'blog_posts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Blog Post', true)), array('controller' => 'blog_posts', 'action' => 'add')); ?> </li>
	</ul>
</div>