<?php

class ContentController extends Cms_Controller_Action {
    
    public function init(){
        parent::init();
        $this->_basetable = "content";
        /**
         * @var Application_Model_Page
         */
        $this->_model = new Application_Model_Common($this->_basetable);
        
    }

    public function indexAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isXmlHttpRequest()){
            $params = $this->_getAllParams();
            $flexiData = $this->_model->list_contents($params);
            $this->_js->setData($flexiData);
            exit;
        }
        $colModel['title'] 			= array('Başlık',300,TRUE,'left',1);
        $colModel['name'] 			= array('Sayfa',300,TRUE,'left',1);
        $colModel['status'] 		= array('Yayın Durumu',300,TRUE,'left',1);
        $params['colModel'] = $colModel;
        $params['sortname'] = "name";
        $params['sortorder'] = "ASC";
        $params['title'] = "İçerikler";
        $this->table($params);
    }

    public function editAction(){
        $this->_helper->layout->disableLayout();
        $id = $this->_getParam("id",false);
        if (isset($_POST['title'])){
            $data = $_POST;
            $this->_model->update_item($this->_basetable,$id,$data);
        }else{
            $this->view->content = $this->_model->findById($this->_basetable,$id);
            $this->view->pages = $this->_model->get_all("page");
        }
        
    }
    public function createAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->pages = $this->_model->get_all("page");
        if (isset($_POST['title'])){
            $data = $_POST;
            $this->_model->insert_item($this->_basetable,$data);
        }
        echo $this->view->render('content/edit.phtml');
    }
}

