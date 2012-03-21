<div class="users form">
	<?php echo $this->Form->create('User',array('url'=>array('controller'=>'bloggers')));?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('User', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				$this->Multimedia->display('m_photo');
				//echo $this->Form->input('group');
				echo $this->Form->input('username');
				echo $this->Form->input('password');
				echo $this->Form->input('email');
				echo $this->Form->input('first_name');
				echo $this->Form->input('last_name');
				?>
					<fieldset class="francais">
						<legend><?php __('Français'); ?></legend>
						<?php
							echo $this->Form->input('function_fre');
						?>
					</fieldset>
				<?php

				?>
					<fieldset class="english">
						<legend><?php __('English'); ?></legend>
						<?php
							echo $this->Form->input('function_eng');
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