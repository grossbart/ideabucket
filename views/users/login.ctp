<h2>Idea Bucket Admin Login</h2>
	<?php echo $form->create('User',array('action'=>'login')); ?>
	<fieldset>
		<?php echo $form->input('name'); ?>
		<?php echo $form->input('password'); ?>
	</fieldset>
<?php echo $form->end('Submit'); ?>
