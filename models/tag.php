<?php

class Tag extends AppModel
{
	var $name = 'Tag';
	var $validate = array(
		'term'		=> VALID_NOT_EMPTY
		);
	var $hasAndBelongsToMany = array(
		'Idea' => array()
	);
}

?>