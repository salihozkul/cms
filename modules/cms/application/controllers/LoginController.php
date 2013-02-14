<?php
class LoginController extends Zend_Controller_Action {
	/**
	 *
	 * Login action
	 */
	public function indexAction(){
		$this->_helper->layout->disableLayout();
		if($this->_request->isPost()){
			$passw = $this->_request->getParam('password');
			$username = $this->_request->getParam('username');
			if ($passw && $username){
				$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter(),'admins','username','password','sha1(?)');
				$authAdapter->setCredential($passw);
				$authAdapter->setIdentity($username);
				$_result =$authAdapter->authenticate();
				if($_result->isValid()) {
					$auth = Zend_Auth::getInstance();
					$storage = $auth->getStorage();
					$idendity = $authAdapter->getResultRowObject(array('id','name_surname','username','email','privileges','language','status','role'));
					$idendity->canBrowse = true;
					$storage->write($idendity);
					$this->_redirect('/index');
					exit;
				}else {
					$this->view->flashMessage('Giriş Başarısız...');
				}
			}else {
				$this->view->flashMessage('Giriş Başarısız...');
			}
		}
	}
	/**
	 *
	 * logout action
	 */
	public function logoutAction() {
		$_auth =Zend_Auth::getInstance();
		if( $_auth->hasIdentity()){
			$_auth->getStorage()->clear();
		}
		$this->_redirect('/login');
		exit;
	}

}