<ul>
	<li><?php echo $html->link('Ideas', array('controller'=>'ideas', 'action'=>'index', 'admin'=> true),  array('id'=>'Ideas')); ?></li>
	<li><?php echo $html->link('Users', array('controller'=>'users', 'admin'=> true),  array('id'=>'Users')); ?></li>
	<li class="secondary"><?php echo $html->link('Tags', array('controller'=>'tags', 'admin'=> true),  array('id'=>'Tags')); ?></li>
</ul>
<a href='#' id="ex_secondary">extend</a>
<a href='#' id="con_secondary">reduce</a>