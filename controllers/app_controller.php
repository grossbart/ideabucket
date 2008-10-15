<?
class AppController extends Controller{
	function beforeFilter(){
		if(isset($this->params[Configure::read('Routing.admin')])) {
			$this->layout = 'admin';
		}
		$this->Session->activate('/');
		if(isset($this->params['admin'])  ){
			$this->layout = 'admin';
			$this->checkLogin();
		}
	}
	function checkLogin(){
		$admin = $this->Session->read('admin');
		if( empty( $admin ) ){
			$this->redirect('/users/login');
		}
	}
}
?>