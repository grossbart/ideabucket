<?php

class IdeasController extends AppController
{
	var $name = 'Ideas';
	var $paginate = array(
	  'limit' => 20,
	  'order' => array(
		  'Idea.modified' => 'desc'
		)
	);
	function add(){
		if (!empty($this->data)){
			$this->Idea->create();
			if ($this->Idea->save($this->data)){
				$this->Session->setFlash('Your Idea has been saved');
				$this->redirect(array('action'=>'index', 'admin'=>false), null, true);
			} else{
				$this->Session->setFlash('Your Idea could no be saved. Please try again!');
			}
		}
	}
	function index(){
		$this->set('ideas', $this->paginate());
	}
	function admin_index(){
		$this->Idea->recursive = 0;
		$this->set('ideas', $this->paginate());
	}
	function admin_add(){
		if (!empty($this->data)){
			if ($this->Idea->save($this->data)){
				$this->flash('Your Idea has been saved.','/admin/ideas');
			}
		}
	}
	function admin_edit($id = null){
		$this->Idea->id = $id;
		if (empty($this->data)){
			$this->data = $this->Idea->read();
			$this->data['Idea']['tags'] = $this->Idea->tagsToString();
		}else{
			if ($this->Idea->save($this->data)){
				$this->flash('Your Idea has been updated.','/admin/ideas');
			}
		}
	}
	function admin_view($id = null){
		$this->Idea->id = $id;
		$this->set('idea', $this->Idea->read());
		$this->pageTitle = 'View Idea';
	}
	function admin_delete($id){
		$this->Idea->del($id);
		$this->flash('The Idea with id: '.$id.' has been deleted.', '/admin/ideas');
	}
	function admin_home(){
		$this->pageTitle = 'Administration';
		$this->layout = 'admin';
	}
}

?>