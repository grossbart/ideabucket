<h2>Browsing tag “<?php echo $tag['Tag']['term']; ?>”</h2>

<ul class="ideas">
	<?php foreach ($tag['Idea'] as $idea): ?>
	<li>
		<h3><?php echo $html->link($idea['title'], array('action'=>'view', $idea['id']), array('class'=>'view_idea', 'title'=>'view this idea')); ?></h3>
		<?php
		  if (!empty($idea['image'])) {
		    echo $html->image($idea['image']['thumb']);
	    }
		?>
		<p><?php echo $idea['excerpt']; ?></p>
		<dl>
			<dt>People</dt><dd><?php echo $idea['people']; ?></dd>
			<dt>Costs</dt><dd><?php echo $idea['costs']; ?></dd>
			<dt>Duration</dt><dd><?php echo $idea['duration']; ?></dd>
			<dt>Weather</dt><dd><?php echo $idea['weather']; ?></dd>
			<dt>Daytime</dt><dd><?php echo $idea['daytime']; ?></dd>
			<dt>Season</dt><dd><?php echo $idea['season']; ?></dd>
		</dl>
	</li>
	<?php endforeach; ?>
</ul>