<div class="blogCategories view">
<h2><?php  __('Blog Category');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogCategory['BlogCategory']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogCategory['BlogCategory']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Desc'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogCategory['BlogCategory']['desc']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogCategory['BlogCategory']['active']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogCategory['BlogCategory']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogCategory['BlogCategory']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Blog Categories', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Blog Posts', true)), array('controller' => 'blog_posts', 'action' => 'index')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php printf(__('Related %s', true), __('Blog Posts', true));?></h3>
	<?php if (!empty($blogCategory['BlogPost'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Title Fre'); ?></th>
		<th><?php __('Title Eng'); ?></th>
		<th><?php __('Short Text Fre'); ?></th>
		<th><?php __('Short Text Eng'); ?></th>
		<th><?php __('Text Fre'); ?></th>
		<th><?php __('Text Eng'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Multimedia'); ?></th>
		<th><?php __('Active'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($blogCategory['BlogPost'] as $blogPost):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $blogPost['id'];?></td>
			<td><?php echo $blogPost['title_fre'];?></td>
			<td><?php echo $blogPost['title_eng'];?></td>
			<td><?php echo $blogPost['short_text_fre'];?></td>
			<td><?php echo $blogPost['short_text_eng'];?></td>
			<td><?php echo $blogPost['text_fre'];?></td>
			<td><?php echo $blogPost['text_eng'];?></td>
			<td><?php echo $blogPost['user_id'];?></td>
			<td><?php echo $blogPost['multimedia'];?></td>
			<td><?php echo $blogPost['active'];?></td>
			<td><?php echo $blogPost['created'];?></td>
			<td><?php echo $blogPost['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'blog_posts', 'action' => 'view', $blogPost['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'blog_posts', 'action' => 'edit', $blogPost['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'blog_posts', 'action' => 'delete', $blogPost['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $blogPost['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Blog Post', true)), array('controller' => 'blog_posts', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
