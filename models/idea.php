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
		'Tag'=>array(
			'table_label'=>'tags',
			'tag_label'=>'term',
			'separator'=>','
		),
		'Image'=>array(
			'fields'=>array(
				'image'=>array(
					'thumbnail'=>array(
						'create'=>true,
						'width'=>'100',
						'height'=>'100',
						'crop'=>true
					),
					'resize'=>array(
						'width'=>'600',
						'height'=>'600',
						'aspect'=>true
						),
					'versions'=>array(
						array('prefix'=>'s',
							'width'=>'300'
						)
					)
				)
			)
		)
	);
	function beforeSave(){
		if (isset($this->data['Idea']['excerpt']) && ($this->data['Idea']['excerpt']=='')){
			$this->data['Idea']['excerpt'] = $this->_mkExcerpt('Idea', 'content');
		}
		return true;
	}
	function _mkExcerpt($model, $field){
		if (isset($this->data[$model][$field]) && ($this->data[$model][$field]!='')){
			return substr($this->data[$model][$field], 0, 140).'&hellip;';
		}
	}
	function afterSave($results) {
		if ( is_array( $results ) ) {
			echo 'is_array - ';
			if (isset($results[0])) {
				echo 'results[0] - ';
				if ( isset( $results[0]['Idea'] ) ) {
					echo 'results[0]["Idea"] - ';
					if (isset($results[0]['Idea']['excerpt']) && ($results[0]['Idea']['excerpt']!='')) {
						echo 'results[0]["Idea"]["excerpt"]';
						echo 'results[0]["Idea"]["content"]';
						$results[0]->Idea->excerpt = $results[0]['Idea']['content'];
					}
				}
			}
		}
		return $results;
	}
	
	function tagsToString() {
	  if (isset($this->data['Tag']) && !empty($this->data['Tag'])) {
      $tags = array();
      foreach ($this->data['Tag'] as $tag) {
        $tags[] = $tag['term'];
      }
      sort($tags);
      return implode(", ", $tags);
	  }
	  return "";
	}
}

?>