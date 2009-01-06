<h2>Ideas<span class="count"><strong><?php echo $paginator->counter(array('format' => __('%count% ', true))); __('recorded')?></span></h2>
<div class="actions">
	<ul>
		<li><?php echo $html->link('Add', array('action'=>'add', 'admin'=>false), array('class'=>'btn')); ?></li>
		<li><?php echo $html->link('Export', array('action'=>'export', 'admin'=>false), array('class'=>'btn')); ?></li>
	</ul>
</div>
<table>
	<tr>
		<th><?php echo $paginator->sort('Id', 'id'); ?></th>
		<th><?php echo $paginator->sort('Title', 'title'); ?></th>
		<th><?php echo $paginator->sort('Date', 'created'); ?></th>
		<th><?php echo $paginator->sort('Excerpt', 'excerpt'); ?></th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
	<?php
	$i = 0;
	foreach ($ideas as $idea):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	?>
	<tr <?php echo $class; ?>>
		<td><?php echo $idea['Idea']['id']; ?></td>
		<td>
			<?php echo $html->link($idea['Idea']['title'], array('action'=>'view', $idea['Idea']['id']), array('class'=>'view_idea', 'title'=>'view this item')); ?>
		</td>
		<td>
			<?php echo $idea['Idea']['created']; ?>
		</td>
		<td>
			<?php echo $idea['Idea']['excerpt']; ?>
		</td>
		<td>
			<?php echo $html->link('Edit', array('action'=>'edit', $idea['Idea']['id']), array('class'=>'edit_item', 'title'=>'edit this item'));?>
		</td>
		<td>
			<?php echo $html->link('Delete', array('action'=>'delete', $idea['Idea']['id']), array('class'=>'delete_item', 'title'=>'delete this item'), 'Are you sure?')?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<div id="page_nav" class="pagination">
	<?php echo $paginator->prev('« '.__('previous', true), array('class'=>'back prev'), null, array('class'=>'disabled prev'));?>
	<?php echo $paginator->numbers( array(null, '|'));?>
	<?php echo $paginator->next(__('next', true).' »', array('class'=>'back next'), null, array('class'=>'disabled next'));?>
</div>