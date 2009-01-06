<ul>
	<li><?php echo $html->link('Application', array('controller'=>'ideas', 'action'=>'index', 'admin'=>false), array('id'=>'default')); ?></li>
	<li><?php echo $html->link('Administration', array('controller'=>'ideas', 'action'=>'index', 'admin'=>true), array('id'=>'admin')); ?></li>
	<li><?php echo $html->link('Logout', array('controller'=>'users', 'action'=>'logout'), array('id'=>'logout')); ?></li>
</ul>