	<?php echo $html->docType(); ?>
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<!-- ——————————————————————————————————————————————————————————————————— TITLE -->
	<title>IdeaBucket | <?php echo $title_for_layout?></title>
	<!-- ——————————————————————————————————————————————————————————————————— META -->
	<?php echo $html->charset(); ?>
	<?php echo $html->meta('keywords','enter any meta keyword here',array());?>
	<?php echo $html->meta('description','enter any meta description here',array());?>
	<meta name="Robots" content="ALL" />
	<meta name="Author" content="Benjamin Wiederkehr / Artillery.ch" />
	<meta name="Copyright" content="<?php date('Y'); ?> Benjamin Wiederkehr" />
	<!-- ——————————————————————————————————————————————————————————————————— FAVICON -->
	<?php echo $html->meta('icon','img/favicon.ico',array());?>
	<!-- ——————————————————————————————————————————————————————————————————— CSS -->
	<?php echo $html->css('base', 'stylesheet', array('media'=>'all')); ?>
	<!-- ——————————————————————————————————————————————————————————————————— JS -->
	<?php echo $javascript->link(array('jquery-1.2.6.min', 'accessibleUISlider.jQuery')) ?>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.5.2/jquery-ui.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			if($('.message') != null){
				setTimeout(function(){
					$('.message').fadeOut('slow');
				}, 4000)
			};
			$('select#IdeaPeople').accessibleUISlider({width: 310});
			$('select#IdeaPeople').hide();
			$('select#IdeaCosts').accessibleUISlider({width: 310});
			$('select#IdeaCosts').hide();
			$('select#IdeaDuration').accessibleUISlider({width: 310});
			$('select#IdeaDuration').hide();
			$('select#IdeaWeather').accessibleUISlider({width: 310});
			$('select#IdeaWeather').hide();
			$('select#IdeaDaytime').accessibleUISlider({width: 310});
			$('select#IdeaDaytime').hide();
			$('select#IdeaSeason').accessibleUISlider({width: 310});
			$('select#IdeaSeason').hide();
		})
	</script>
</head>
	<!-- ——————————————————————————————————————————————————————————————————— BODY -->
<body class="default">
	<div id="header">
		<h1><?php echo $html->link('IdeaBucket', '/',  array()); ?></h1>
		<?php echo $this->element('meta'); ?>
	</div><!-- #header -->
	<?php echo $this->element('flash'); ?>
	<div id="container">
		<div id="content">
			<?php echo $content_for_layout ?>
		</div><!-- #content -->
		<div id="footer">
		</div><!-- #footer -->
	</div><!-- #container -->
</body>
</html>