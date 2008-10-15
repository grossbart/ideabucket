<div id="messages">
	<?php
	if($session->check('Message.error')){
		echo '';
		$session->flash('error');
		echo '';
	}

	if($session->check('Message.warning')){
		echo '';
		$session->flash('warning');
		echo '';
	}

	if($session->check('Message.success')){
		echo '';
		$session->flash('success');
		echo '';
	}
	?>
</div>
