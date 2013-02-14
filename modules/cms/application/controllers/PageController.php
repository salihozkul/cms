<?php

class PageController extends Cms_Controller_Action {
    
    public function init(){
        parent::init();
        $this->_basetable = "page";
        /**
         * @var Application_Model_Page
         */
        $this->_model = new Application_Model_Common($this->_basetable);
    }

    public function indexAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isXmlHttpRequest()){
            $params = $this->_getAllParams();
            $flexiData = $this->_model->list_pages($params);
            $this->_js->setData($flexiData);
            exit;
        }
        $colModel['name'] 			= array('Başlık',900,TRUE,'left',1);
        $params['colModel'] = $colModel;
        $params['sortname'] = "name";
        $params['sortorder'] = "ASC";
        $params['title'] = "Sayfalar";
        $this->table($params);
    }

    public function editAction(){
        $this->_helper->layout->disableLayout();
        $id = $this->_getParam("id",false);
        if (isset($_POST['name'])){
            $data = $_POST;
            $where = Zend_Db_Table::getDefaultAdapter()->quoteInto("id=?", $id);
            $this->_model->update($data, $where);
        }else{
            $this->view->page = $this->_model->findById("page",$id);
        }
        
    }
    public function createAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if (isset($_POST['name'])){
            $data = $_POST;
            $this->_model->insert($data);
        }
        echo $this->view->render('page/edit.phtml');
    }
}

