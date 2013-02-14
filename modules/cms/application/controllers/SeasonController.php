<?php

class SeasonController extends Cms_Controller_Action {

    public function init(){
        parent::init();
        $this->_basetable = "season";
        /**
         * @var Application_Model_Page
         */
        $this->_model = new Application_Model_Common($this->_basetable);

    }

    public function indexAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isXmlHttpRequest()){
            $params = $this->_getAllParams();
            $flexiData = $this->_model->list_seasons($params);
            $this->_js->setData($flexiData);
            exit;
        }
        $colModel['season_name'] 			= array('Sezon Adı',300,TRUE,'left',1);
        $colModel['company_name'] 		= array('Şirket Adı',300,TRUE,'left',1);
        $colModel['start_date'] 		= array('Başlangıç Tarihi',100,TRUE,'left',1);
        $colModel['end_date'] 		= array('Bitiş Tarihi',100,TRUE,'left',1);
        $colModel['status'] 			= array('Yayın Durumu',100,TRUE,'left',1);
        
        $params['colModel'] = $colModel;
        $params['sortname'] = "season_name";
        $params['sortorder'] = "ASC";
        $params['title'] = "Sezonlar";
        $this->table($params);
    }

    public function editAction(){
        $this->_helper->layout->disableLayout();
        $id = $this->_getParam("id",false);
        if (isset($_POST['season_name'])){
            $data = $_POST;
            $this->_model->update_item($this->_basetable,$id,$data);
        }else{
            $this->view->season = $this->_model->findById($this->_basetable,$id);
            $this->view->company = $this->_model->get_all("company");
        }

    }
    public function createAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->company = $this->_model->get_all("company");
        if (isset($_POST['season_name'])){
            $data = $_POST;
            $this->_model->insert_item($this->_basetable,$data);
        }
        echo $this->view->render('season/edit.phtml');
    }
}

