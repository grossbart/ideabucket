	<?php foreach ($buildings as $building): ?>
<item>
	<id><?php echo $building['Building']['id']; ?></id>
	<name><?php echo $building['Building']['name']; ?></name>
	<street><?php echo $building['Building']['street']; ?></street>
	<number><?php echo $building['Building']['number']; ?></number>
	<visitors><?php echo $building['Building']['visitors']; ?></visitors>
	<employees><?php echo $building['Building']['employees']; ?></employees>
	<area><?php echo $building['Building']['size']; ?></area>
	<floors><?php echo $building['Building']['floors']; ?></floors>
	<?php if($building['Institute']){ ?>
	<institutes>
	<?php foreach ( $building['Institute'] as $institute) { ?>
		<institute>
			<id><?php echo $institute['id']; ?></id>
			<name><?php echo $institute['name']; ?></name>
			<shortcut><?php echo $institute['shortcut']; ?></shortcut>
			<url><?php echo $institute['url']; ?></url>
			<area><?php echo $institute['area']; ?></area>
		</institute>
		<?php } ?>
	</institutes>
	<?php } ?>
</item>
	<?php endforeach; ?>