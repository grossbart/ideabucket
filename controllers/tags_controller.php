<?php

class TagsController extends AppController
{
	var $name = 'Tags';
	var $helpers = array('Html', 'Form' );
	var $paginate = array(
		'limit' => 50,
		'order' => array(
			'Tag.id' => 'asc'
			)
		);
	function admin_index(){
		$this->set('tags', $this->paginate());
	}
	function admin_view($id = null){
		$this->pageTitle = 'View Tag';
		$this->Tag->id = $id;
		$this->set('tag', $this->Tag->read());
	}
	function admin_add(){
		$this->pageTitle = 'Add Tag';
		if (!empty($this->data)){
			if ($this->Tag->save($this->data)){
				$this->flash('Your Tag has been saved.','/admin/tags');
			}
		}
	}
	function admin_delete($id){
		$this->Tag->del($id);
		$this->flash('The Tag with id: '.$id.' has been deleted.', '/admin/tags');
		$this->redirect('/admin/tags');
	}
	function admin_edit($id = null){
		$this->pageTitle = 'Edit Tag';
		if (empty($this->data)){
			$this->Tag->id = $id;
			$this->data = $this->Tag->read();
		}else{
			if ($this->Tag->save($this->data)){
				$this->flash('Your Tag has been updated.','/admin/tags');
			}
		}
	}
}

?>