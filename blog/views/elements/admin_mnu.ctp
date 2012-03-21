<li><?php echo $html->link(__d('blog','Blog', true), array('plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'index')); ?>
    <ul>
        <li><?php echo $html->link(__d('blog','Posts', true), array('plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'index')); ?></li>
        <li><?php echo $html->link(__d('blog','Bloggers', true), array('plugin' => 'blog', 'controller' => 'bloggers', 'action' => 'index')); ?></li>
    </ul>
</li>