<h2>Add New Idea</h2>
	<?php echo $form->create('Idea')?>
	<ul>
		<li>
			<?php echo $form->input('title', array('label'=>false, 'class' => 'title')); ?>
			<?php echo $form->error('title', 'Title is required.'); ?>
		</li>
		<li class="hint">
			<h3>Hint:</h3>
			Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure d.
			<a href="#" title="close this hint" class="close">close this hint</a>
		</li>
		<li>
			<?php echo $form->input('User', array())?>
		</li>
		<li>
			<?php echo $form->input('image', array('type'=>'file'))?>
		</li>
		<li>
			<?php echo $form->input('excerpt', array())?>
		</li>
		<li>
			<?php echo $form->input('content', array())?>
		</li>
		<li>
			<?php echo $form->input('people', array())?>
		</li>
		<li>
			<?php echo $form->input('weather', array())?>
		</li>
		<li>
			<?php echo $form->input('costs', array())?>
		</li>
		<li>
			<?php echo $form->input('season', array())?>
		</li>
		<li>
			<?php echo $form->input('daytime', array())?>
		</li>
		<li>
			<?php echo $form->input('duration', array())?>
		</li>
	</ul>
<div id="page_nav">
	<?php echo $form->end('Save')?>
	<?php echo $html->link('Cancel', array('action'=>'index',),  array('class'=>'back')); ?>
</div>