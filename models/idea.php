<?php

class Idea extends AppModel
{
	var $name = 'Idea';
	var $validate = array(
		'title'  => VALID_NOT_EMPTY
		);
	var $belongsTo = array(
		'user' => array()
	);
	var $hasAndBelongsToMany = array(
		'Tag' => array()
	);
	var $actsAs= array(
		'Image'=>array(
			'fields'=>array(
				'image'=>array(
					'thumbnail'=>array('create'=>true),
					'resize'=>array(
									 'width'=>'800',
									 'height'=>'800',
						),
					'versions'=>array(
						array('prefix'=>'s',
									 'width'=>'400',
									 'height'=>'300',
						),
						array('prefix'=>'l',
									 'width'=>'700',
									 'height'=>'500',
						)
					)
				)
			)
		)
	);
}

?>