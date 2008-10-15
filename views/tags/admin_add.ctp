<h2>Add New Building</h2>
	<?php echo $form->create('Building')?>
<ul>
	<li>
		<?php echo $form->input('Building/name', array('class' => 'text'))?>
		<?php echo $form->error('Building/name', 'A name is required.') ?>
	</li>
	<li>
		<?php echo $form->input('Building/street', array('class' => 'text'))?>
		<?php echo $form->error('Building/street', 'A street is required.') ?>
	</li>
	<li>
		<?php echo $form->input('Building/number', array('class' => 'text'))?>
	</li>
	<li>
		<?php echo $form->input('Building/visitors', array('after' => '<span>per day</span>', 'class' => 'text'))?>
	</li>
	<li>
		<?php echo $form->input('Building/employees', array('class' => 'text'))?>
	</li>
	<li>
		<?php echo $form->input('Building/size', array('after' => '<span>m2</span>', 'class' => 'text'))?>
	</li>
	<li>
		<?php echo $form->input('Building/floor', array('class' => 'text'))?>
	</li>
</ul>
<div id="page_nav">
	<?php echo $form->end('Save')?>
	<?php echo $html->link('Cancel', '/buildings',  array('class' => 'back')); ?>
</div><!-- #name -->