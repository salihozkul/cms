<?php

class ProductController extends Cms_Controller_Action {
    
    public function init(){
        parent::init();
        $this->_basetable = "product";
        /**
         * @var Application_Model_Page
         */
        $this->_model = new Application_Model_Common($this->_basetable);
        /**
         * @var Cms_Util_Images
         */
             
    }

    public function indexAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isXmlHttpRequest()){
            $params = $this->_getAllParams();
            $flexiData = $this->_model->list_products($params);
            $this->_js->setData($flexiData);
            exit;
        }
        $colModel['name'] 			= array('Ürün Adı',300,TRUE,'left',1);
        $colModel['season_name'] 	= array('Sezon Adı',300,TRUE,'left',1);
        $colModel['total'] 		    = array('Toplam Sayı',300,TRUE,'left',1);
        
        $params['colModel'] = $colModel;
        $params['sortname'] = "name";
        $params['sortorder'] = "ASC";
        $params['title'] = "Ürünler";
        
        $params['buttons'] = array('Resim','image','test');
        
        $this->table($params);
    }

    public function editAction(){
        $this->_helper->layout->disableLayout();
        $id = $this->_getParam("id",false);
        if (isset($_POST['name'])){
            $data = $_POST;
            $this->_model->update_item($this->_basetable,$id,$data);
        }else{
            $this->view->product = $this->_model->findById($this->_basetable,$id);
            $this->view->season = $this->_model->get_all("season");
        }

    }
    public function createAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->season = $this->_model->get_all("season");
        if (isset($_POST['name'])){
            $data = $_POST;
            $this->_model->insert_item($this->_basetable,$data);
        }
        echo $this->view->render('product/edit.phtml');
    }
    
    
}

