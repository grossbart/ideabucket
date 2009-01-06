<h2>Candeo Admin Interface</h2>
<p>Die Universität Bern setzt sich aus gegen 160 einzelne Institute zusammen, welche sich verteilt in Bern befinden. Ungefähr 35 Institute arbeiten mit Chemikalien, Mikroorganismen oder Strahlenquellen. Die Fachstelle Risikomanagment ist Zustädig für die Erfassung der Inventare aller Institute und Ermessung des Gefährdungspotentials. Die technischen Hilfsmittel, um die Daten zu verwalten sind Excel-Tabellen und diverse Datenbanken. Bis anhin gibt es ausser Balkendiagramme noch keine Visualisierungen der Soffflüsse und des Risikopotentials der Universität Bern.</p>
<h2>Shortcuts</h2>
<p id="shortcuts">
	<?php echo $html->link('Add Institute', '/institutes/add', array('class'=>'btn')); ?>
	<?php echo $html->link('Add Building', '/buildings/add', array('class'=>'btn')); ?>
	<?php echo $html->link('Add Record', '/records/add', array('class'=>'btn')); ?>
	<?php echo $html->link('Add Atomic', '/atomics/add', array('class'=>'btn')); ?>
	<?php echo $html->link('Add Biological', '/biologicals/add', array('class'=>'btn')); ?>
	<?php echo $html->link('Add Chemical', '/chemicals/add', array('class'=>'btn')); ?>
	<br class="clear"/>
</p>