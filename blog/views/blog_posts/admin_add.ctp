<div class="blogPosts form">
	<?php echo $this->Form->create('BlogPost');?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('Blog Post', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				echo $this->Form->input('home', array('checked' => 'checked'));
				echo $this->Form->input('hide_link_moreinfo');
				if($allow_blogger_edit){
					echo $this->Form->input('user_id',array('default'=>$user['User']['id']));
				}
				$this->Multimedia->display('multimedia');

				?>
					<fieldset class="francais">
						<legend><?php __('Français'); ?></legend>
						<?php
							echo $this->Form->input('title_fre');
							echo $this->Form->input('text_fre', array('class'=>'tinymce'));
							echo $this->Form->input('short_text_fre', array('class'=>'tinymce'));
							echo $this->Form->input('short_home_text_fre', array('class'=>'tinymce'));
						?>
					</fieldset>
				<?php

				?>
					<fieldset class="english">
						<legend><?php __('English'); ?></legend>
						<?php
							echo $this->Form->input('title_eng');
							echo $this->Form->input('text_eng', array('class'=>'tinymce'));
							echo $this->Form->input('short_text_eng', array('class'=>'tinymce'));
							echo $this->Form->input('short_home_text_eng', array('class'=>'tinymce'));
						?>
					</fieldset>
				<?php
				
				echo $this->Form->input('BlogCategory', array('multiple'=>'checkbox', 'div'=>array('class'=>'multiple_checkbox')));
				$this->Multimedia->sidebar();
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Blog Posts', true)), array('action' => 'index'));?></li>
	</ul>
</div>