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
	<?php echo $html->css('admin/admin', 'stylesheet', array('media'=>'all')); ?>
	<!-- ——————————————————————————————————————————————————————————————————— JS -->
	<?php echo $javascript->link(array('jquery-1.2.6.min')) ?>
	<script type="text/javascript">
		<!--
		$(document).ready(function(){
			if($('.hint') != null){
				$('.close').click(function(){
					var hint = $(this).parent();
					$(hint).fadeOut('slow');
				})
			}
			if($('.message') != null){
				setTimeout(function(){
					$('.message').fadeOut('slow');
				}, 4000)
			}
			//if($('.extended') == null){
				$('.secondary').hide();
				$('#con_secondary').hide();
				$('#ex_secondary').click(function(){
					$('.secondary').fadeIn();
					$(this).hide();
					$('#con_secondary').show();
				})
				$('#con_secondary').click(function(){
					$('.secondary').fadeOut();
					$(this).hide();
					$('#ex_secondary').show();
				})
			//}
		})
		//-->
	</script>
</head>
	<!-- ——————————————————————————————————————————————————————————————————— BODY -->
<body class="admin">
	<div id="header">
		<h1><?php echo $html->link('IdeaBucket', '/',  array()); ?></h1>
		<?php echo $this->element('meta'); ?>
	</div><!-- #header -->
	<?php echo $this->element('flash'); ?>
	<div id="container">
		<div id="nav" class="<?php echo $title_for_layout?>">
			<?php echo $this->element('admin/nav'); ?>
		</div><!-- #nav -->
		<div id="content">
			<?php echo $content_for_layout; ?>
		</div><!-- #content -->
		<div id="footer">
			<?php echo $this->element('admin/footer'); ?>
		</div><!-- #footer -->
	</div><!-- #container -->
</body>
</html>