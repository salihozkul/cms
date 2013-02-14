<?php

class CompanyController extends Cms_Controller_Action {

    public function init(){
        parent::init();
        $this->_basetable = "company";
        /**
         * @var Application_Model_Page
         */
        $this->_model = new Application_Model_Common($this->_basetable);

    }

    public function indexAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isXmlHttpRequest()){
            $params = $this->_getAllParams();
            $flexiData = $this->_model->list_companies($params);
            $this->_js->setData($flexiData);
            exit;
        }
        $colModel['company_name'] 			= array('Şirket Adı',300,TRUE,'left',1);
        $colModel['city'] 			= array('İl',300,TRUE,'left',1);
        $colModel['category'] 		= array('Şirket Tipi',300,TRUE,'left',1);
        $params['colModel'] = $colModel;
        $params['sortname'] = "company_name";
        $params['sortorder'] = "ASC";
        $params['title'] = "Şirketler";
        $this->table($params);
    }

    public function editAction(){
        $this->_helper->layout->disableLayout();
        $id = $this->_getParam("id",false);
        if (isset($_POST['company_name'])){
            $data = $_POST;
            $this->_model->update_item($this->_basetable,$id,$data);
        }
            $this->view->company = $this->_model->findById($this->_basetable,$id);
            $this->view->location = $this->_model->get_all("location");
            $this->view->company_category = $this->_model->get_all("company_category");
       

    }
    public function createAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->location = $this->_model->get_all("location");
        $this->view->company_category = $this->_model->get_all("company_category");
        if (isset($_POST['company_name'])){
            $data = $_POST;
            $this->_model->insert_item($this->_basetable,$data);
        }
        echo $this->view->render('company/edit.phtml');
    }
}

