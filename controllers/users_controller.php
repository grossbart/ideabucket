<?php
class UsersController extends AppController {
	var $uses = 0;
	var $name = 'Users';
	var $helpers = array('Html', 'Form' );
	
	function login(){
		$this->layout = 'default';
		if(!empty($this->data)){
			if($this->data['User']['name'] != 'admin' or $this->data['User']['password'] != 'id3a'){
				$this->Session->setFlash('Invalid user or password, please try again.');
			}else{
				$this->Session->write('admin', 1);
				$this->Session->setFlash("You've kindly been logged in, good work!", 'default', array(), 'success');
				$this->redirect(array('admin'=>true, 'controller'=>'ideas', 'action'=>'home'));
			}
		}
	}
	function admin_logout(){
		$this->Session->setFlash("You've kindly been logged out, good bye!", 'default', array(), 'success');
		$this->redirect(array('admin'=>false, 'controller'=>'users', 'action'=>'login'), true);
	}
}
?>