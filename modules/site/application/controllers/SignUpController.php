<?php
class SignUpController extends Site_Controller_Action {

    protected $model;
    
    public function init(){
        $this->_basetable = "user";
        /**
         * @var Site_Model_Common
         */
        $this->model = new Application_Model_Common($this->_basetable);
    }
    
    public function indexAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isPost()){
            $data = $_POST;
            $pass = $data['password'];
            $data['password'] = sha1($data['password']);
            $this->model->insert($data);
            unset($_POST);
            $this->user_login($data['username'],$pass);
            $this->view->message = "Kullanıcı kaydınız başarı ile gerçekleştirilmiştir.Şu andan itibaren hediye kampanyalarımıza katılabilirizsiniz";
        }
        $this->view->locations = $this->model->get_all("location", array("id","city"));
        echo $this->view->render('elements/signUp.phtml');
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
		$this->_redirect('/index');
		exit;
	}
    

}