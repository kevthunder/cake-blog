<div class="users form">
	<?php echo $this->Form->create('User',array('url'=>array('controller'=>'bloggers')));?>
		<fieldset>
			<legend><?php printf(__('Edit %s', true), __('User', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				echo $this->Form->input('id');
				$this->Multimedia->display('m_photo');
				
				/*$groupOptions = array();

				if(isset($group['Aro']['alias'])) {
					$groupOptions['value'] = $group['Aro']['alias'];
				}
				
				echo $this->Form->input('group', $groupOptions);*/
				echo $this->Form->input('username');
				echo $this->Form->input('password', array('value' => '', 'label' => __('Password (leave blank except for changing)', true)));
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