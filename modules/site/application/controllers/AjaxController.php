<?php

class AjaxController extends Site_Controller_Action {

    public function init(){
        parent::init();
        $this->_basetable = "company";
        /**
         * @var Application_Model_Page
         */
        $this->_model = new Application_Model_Common($this->_basetable);

    }
    public function tcCheckAction(){
        $tcno = (int)$this->_getParam("tc",false);
        $name = $this->_getParam("name",false);
        $surname = $this->_getParam("surname",false);
        $birthdate = $this->_getParam("birthdate",false);
        
        $tc =   new Site_Util_Tckimlik($tcno, $name, $surname, $birthdate);
        if($tc->soap_cevap == 'd'){
            // verilen tc kimlik numarası doğrudur ve diğer bilgiler uyuyor (ad,soyad,doğum tarihi)
            echo '1';
        }elseif($tc->soap_cevap == 'y'){
            // verilen tc kimlik numarası doğrudur fakat diğer bilgiler uymuyor (ad,soyad,doğum tarihi)
            echo 'Verilen tc kimlik numarasına diğer bilgiler uymuyor. (ad,soyad,doğum tarihi)';
        }else{
            // verilen tc kimlik numarası yanlıştır.
            echo 'verilen tc kimlik numarası yanlıştır';
            
        }
        exit;
    }
    
    public function checkusernameAction(){
        $username = $this->_getParam("username",false);
        if ( $this->_model->checkusername($username) < 1) {
            echo "1";
        }else{
            echo "0";    
        }
        exit;
    }
    
    
    
}

