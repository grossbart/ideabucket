<ul>
	<li><?php echo $html->link('Application', array('controller'=>'Pages', 'action'=>'home'), array('id'=>'default')); ?></li>
	<li><?php echo $html->link('Administration', array('controller'=>'ideas', 'action'=>'home'), array('id'=>'admin')); ?></li>
	<li><?php echo $html->link('Logout', array('controller'=>'users', 'action'=>'logout'), array('id'=>'logout')); ?></li>
</ul>