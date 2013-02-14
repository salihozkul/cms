<?php
class Site_Controller_Action extends Common_Controller_Action {
    /**
     * @var Cms_Util_Javascript
     */
    protected $_js;

    /**
     * @var Zend_Db_Table
     */
    protected $_model;

    protected $_basetable;

    protected $_image;

    protected $_image_path;

    protected $user;
    
    public function init(){
        if (Zend_Auth::getInstance()->hasIdentity()){
            $identity = Zend_Auth::getInstance()->getIdentity();
            $this->user = $identity;
            $this->view->user = $identity;
        }
        $this->view->authantication = (isset($this->user)) ? $this->view->render('elements/userLinks.phtml'): $this->view->render('elements/login.phtml');
    }

    public function user_login($username,$password){
        if ($username && $password){
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter(),'user','username','password','sha1(?)');
            $authAdapter->setCredential($password);
            $authAdapter->setIdentity($username);
            $_result =$authAdapter->authenticate();
            if($_result->isValid()) {
                $auth = Zend_Auth::getInstance();
                $storage = $auth->getStorage();
                $idendity = $authAdapter->getResultRowObject(array('id','username','name','surname'));
                $idendity->canBrowse = true;
                $storage->write($idendity);
            }else {
                $this->view->message=print_r($_result->getMessages());
            }
        }else {
            $this->view->message='Giriş Başarısız...';
        }
    }

}