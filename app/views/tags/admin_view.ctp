<h2><?php echo $building['Building']['name']?></h2>
	<?php echo $html->link('Edit', '/buildings/edit/'.$building['Building']['id'], array('class'=>'edit')); ?>
<dl>
	<dt>Address</dt><dd><?php echo $building['Building']['street']." ".$building['Building']['number']?></dd>
	<dt>Employees</dt><dd><?php echo $building['Building']['employees']?></dd>
	<dt>Visitors</dt><dd><?php echo $building['Building']['visitors']." per day"?></dd>
	<dt>Size</dt><dd><?php echo $building['Building']['size']." m2"?></dd>
	<dt>Floors</dt><dd><?php echo $building['Building']['floors']?></dd>
	<dt>Institutes</dt>
	<?php
		foreach ( $building['Institute'] as $institute) {
			echo '<dd>'.$html->link($institute['name'], '/institutes/view/'.$institute['id'], array()).'</dd>';
		}
	?>
</dl>
<div id="page_nav">
	<?php echo $html->link('Back', '/buildings',  array('class'=>'back')); ?>
</div>