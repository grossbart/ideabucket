<?php

class User extends AppModel
{
	var $name = 'User';
	var $validate = array(
		'name'  => VALID_NOT_EMPTY
		);
	var $hasMany = array(
		'idea' => array()
	);
}

?>