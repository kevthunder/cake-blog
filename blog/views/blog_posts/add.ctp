<div class="blogPosts form">
	<?php echo $this->Form->create('BlogPost');?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('Blog Post', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				$this->Multimedia->display('multimedia');

				?>
					<fieldset class="francais">
						<legend><?php __('Français'); ?></legend>
						<?php
							echo $this->Form->input('title_fre');
							echo $this->Form->input('short_text_fre', array('class'=>'tinymce'));
							echo $this->Form->input('text_fre', array('class'=>'tinymce'));
						?>
					</fieldset>
				<?php

				?>
					<fieldset class="english">
						<legend><?php __('English'); ?></legend>
						<?php
							echo $this->Form->input('title_eng');
							echo $this->Form->input('short_text_eng', array('class'=>'tinymce'));
							echo $this->Form->input('text_eng', array('class'=>'tinymce'));
						?>
					</fieldset>
				<?php
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Blog Posts', true)), array('action' => 'index'));?></li>
	</ul>
</div>