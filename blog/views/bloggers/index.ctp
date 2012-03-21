<div class="users index">
	<?php
		echo $this->Form->create('User', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Users');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('username');?></th>			
			<th><?php echo $this->Paginator->sort('email');?></th>			
			<th><?php echo $this->Paginator->sort('first_name');?></th>			
			<th><?php echo $this->Paginator->sort('last_name');?></th>				
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true));
			foreach ($users as $user) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $user['User']['id']; ?>&nbsp;</td>
						<td class="username"><?php echo $user['User']['username']; ?>&nbsp;</td>
						<td class="email"><?php echo $user['User']['email']; ?>&nbsp;</td>
						<td class="first_name"><?php echo $text->truncate($user['User']['first_name'], 150, array('exact' => false)); ?>&nbsp;</td>
						<td class="last_name"><?php echo $text->truncate($user['User']['last_name'], 150, array('exact' => false)); ?>&nbsp;</td>
						<td class="actions">
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