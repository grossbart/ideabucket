<h2><?php echo $idea['Idea']['title']?></h2>
<div class="actions">
	<ul>
		<li><?php echo $html->link('Edit', array('action'=>'edit', $idea['Idea']['id']), array('class'=>'btn')); ?></li>
	</ul>
</div>
<dl>
	<dt>Title</dt><dd><?php echo $idea['Idea']['title']; ?></dd>
	<dt>Date</dt><dd><?php echo $idea['Idea']['created']; ?></dd>
	<dt>User</dt><dd><?php echo $idea['Idea']['user_id']; ?></dd>
	<dt>Image</dt><dd><?php echo $idea['Idea']['image']; ?></dd>
	<dt>Content</dt><dd><?php echo $idea['Idea']['content']; ?></dd>
	<dt>Content</dt><dd><?php echo $idea['Idea']['tags']; ?></dd>
	<dt>People</dt><dd><?php echo $idea['Idea']['people']; ?></dd>
	<dt>Weather</dt><dd><?php echo $idea['Idea']['weather']; ?></dd>
	<dt>Costs</dt><dd><?php echo $idea['Idea']['costs']; ?></dd>
	<dt>Season</dt><dd><?php echo $idea['Idea']['season']; ?></dd>
	<dt>Duration</dt><dd><?php echo $idea['Idea']['duration']; ?></dd>
</dl>
<div id="page_nav">
	<?php echo $html->link('Back', array('action'=>'index',),  array('class'=>'back')); ?>
</div>