<?php
	if(!isset($counts)){
		$counts = true;
	}
?>
<div class="blogArchives">
	<h3><?php __('Archives'); ?></h3>
	<ul>
	<?php foreach ($listArchives as $month) { ?>
		<li>
			<a href="<?php 
				echo $this->Html->url(array('action'=>'index','m'=>$month['m'],'y'=>$month['y']))
			?>"><?php echo ucfirst(date_($month['y']==date('Y')?'F':'F Y',strtotime($month['date']))); ?> <span class="count">(<?php echo $month['cb'] ?>)</span></a>
		</li>
	<?php } ?>
	</ul>
</div>