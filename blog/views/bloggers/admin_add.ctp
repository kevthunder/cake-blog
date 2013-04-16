<div class="users form">
	<?php echo $this->Form->create('User',array('url'=>array('controller'=>'bloggers')));?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('User', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				//echo $this->Form->input('group');
				echo $this->Form->input('username');
				echo $this->Form->input('password');
				echo $this->Form->input('email');
				echo $this->Form->input('first_name');
				echo $this->Form->input('last_name');
				
				if(!empty($fields['m_photo'])) $this->Multimedia->display('m_photo');
				?>
					<fieldset class="francais">
						<legend><?php __('Français'); ?></legend>
						<?php
							if(!empty($fields['function_fre'])) echo $this->Form->input('function_fre');
							if(!empty($fields['bio_fre'])) echo $this->Form->input('bio_fre',array('class'=>'tinymce'));
						?>
					</fieldset>
				<?php

				?>
					<fieldset class="english">
						<legend><?php __('English'); ?></legend>
						<?php
							if(!empty($fields['function_eng'])) echo $this->Form->input('function_eng');
							if(!empty($fields['bio_eng'])) echo $this->Form->input('bio_eng',array('class'=>'tinymce'));
						?>
					</fieldset>
				<?php
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Users', true)), array('action' => 'index'));?></li>
	</ul>
</div>