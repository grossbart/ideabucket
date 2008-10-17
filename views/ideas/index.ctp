<h2>Ideas</h2>
<div class="actions">
	<ul>
		<li><?php echo $html->link('Add', array('action'=>'add'), array('class'=>'btn')); ?></li>
	</ul>
</div>
<ul class="ideas">
	<?php foreach ($ideas as $idea): ?>
	<li>
		<h3><?php echo $html->link($idea['Idea']['title'], array('action'=>'view', $idea['Idea']['id']), array('class'=>'view_idea', 'title'=>'view this idea')); ?></h3>
		<?php echo $html->image($idea['Idea']['image']['thumb']); ?>
		<p><?php echo $idea['Idea']['excerpt']; ?></p>
		<dl>
			<dt>Tags</dt><dd><?php echo $idea['Idea']['tags']; ?></dd>
			<dt>People</dt><dd><?php echo $idea['Idea']['people']; ?></dd>
			<dt>Costs</dt><dd><?php echo $idea['Idea']['costs']; ?></dd>
			<dt>Duration</dt><dd><?php echo $idea['Idea']['duration']; ?></dd>
			<dt>Weather</dt><dd><?php echo $idea['Idea']['weather']; ?></dd>
			<dt>Daytime</dt><dd><?php echo $idea['Idea']['daytime']; ?></dd>
			<dt>Season</dt><dd><?php echo $idea['Idea']['season']; ?></dd>
		</dl>
	</li>
	<?php endforeach; ?>
</ul>
<div id="page_nav" class="pagination">
	<?php echo $paginator->prev('« '.__('previous', true), array('class'=>'back prev'), null, array('class'=>'disabled prev'));?>
	<?php echo $paginator->numbers( array(null, '|'));?>
	<?php echo $paginator->next(__('next', true).' »', array('class'=>'back next'), null, array('class'=>'disabled next'));?>
</div>