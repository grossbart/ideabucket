<h2>Add New Idea</h2>
	<?php echo $form->create('Idea', array('enctype'=>"multipart/form-data"))?>
	<ul>
		<li>
			<?php echo $form->input('title', array('label'=>false, 'class' => 'title')); ?>
			<?php echo $form->error('title', 'Title is required.'); ?>
		</li>
		<li>
			<?php echo $form->input('Idea.image', array('type'=>'file'))?>
		</li>
		<li>
			<?php echo $form->input('content', array())?>
		</li>
		<li>
			<p><a href="#" title="Provide an excerpt" id="excerpt_link">Provide an excerpt</a> (max. 333 characters)</p>
		</li>
		<li>
			<?php echo $form->input('people', array('type'=>'select', 'options'=>array(1=>'1',2=>'2',5=>'-5',10=>'-10',20=>'-20',50=>'-50', 100=>'>50')))?>
		</li>
		<li>
			<?php echo $form->input('costs', array('type'=>'select', 'options'=>array(0=>'0',20=>'-20.-',50=>'-50.-',100=>'-100.-',200=>'-200.-',1000=>'>200.-')))?>
		</li>
		<li>
			<?php echo $form->input('duration', array('type'=>'select', 'options'=>array(1=>'<1h',2=>'2h',3=>'3h',4=>'4h',12=>'-12h',24=>'-24h',48=>'-48h', 72=>'-72h')))?>
		</li>
		<li>
			<?php echo $form->input('weather', array('type'=>'select', 'options'=>array(0=>"doesn't matter", 1=>'sunny', 2=>'rainy', 3=>'snowy')))?>
		</li>
		<li>
			<?php echo $form->input('daytime', array('type'=>'select', 'options'=>array(0=>"doesn't matter", 1=>'day', 2=>'night')))?>
		</li>
		<li>
			<?php echo $form->input('season', array('type'=>'select', 'options'=>array(0=>"doesn't matter", 1=>'spring', 2=>'summer', 3=>'autumn', 4=>'winter')))?>
		</li>
	</ul>
<div id="page_nav">
	<?php echo $form->end('Submit')?>
	<?php echo $html->link('Cancel', array('action'=>'index',),  array('class'=>'back')); ?>
</div>
<script type="text/javascript" charset="utf-8">
	$('#excerpt_link').click(function(){
		$(this).parent().after('<?php echo $form->input("excerpt", array("type"=>"textarea"))?>');
		$(this).parent().hide();
		return false
	});
</script>