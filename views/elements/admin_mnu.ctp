<li><?php echo $html->link(__d('blog','Blog', true), array('plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'index')); ?>
    <ul>
        <li><?php echo $html->link(__d('blog','Posts', true), array('plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'index')); ?></li>
        <li><?php echo $html->link(__d('blog','Categories / Tags', true), array('plugin' => 'blog', 'controller' => 'blog_categories', 'action' => 'index')); ?></li>
		<?php 
		App::import('Lib', 'Blog.BlogConfig');
		if(BlogConfig::load('useBlogger')) { ?>
        <li><?php echo $html->link(__d('blog','Bloggers', true), array('plugin' => 'blog', 'controller' => 'bloggers', 'action' => 'index')); ?></li>
		<?php }?>
    </ul>
</li>