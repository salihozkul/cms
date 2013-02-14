<?php

class SessionController extends Cms_Controller_Action {

    public function init(){
        parent::init();
        $this->_basetable = "session";
        /**
         * @var Application_Model_Page
         */
        $this->_model = new Application_Model_Common($this->_basetable);

    }

    public function indexAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isXmlHttpRequest()){
            $params = $this->_getAllParams();
            $flexiData = $this->_model->list_sessions($params);
            $this->_js->setData($flexiData);
            exit;
        }
        $colModel['session_name'] 	= array('Seans Adı',300,TRUE,'left',1);
        $colModel['season_name'] 	= array('Sezon Adı',300,TRUE,'left',1);
        $colModel['product_limit'] 	= array('Ürün Sayısı',100,TRUE,'left',1);
        $colModel['start_date'] 	= array('Başlangıç Tarihi',100,TRUE,'left',1);
        $colModel['end_date'] 		= array('Bitiş Tarihi',100,TRUE,'left',1);
        
        
        $params['colModel']     = $colModel;
        $params['sortname']     = "session_name";
        $params['sortorder']    = "ASC";
        $params['title']        = "Seanslar";
        $this->table($params);
    }

    public function editAction(){
        $this->_helper->layout->disableLayout();
        $id = $this->_getParam("id",false);
        if (isset($_POST['session_name'])){
            $data = $_POST;
            $this->_model->update_item($this->_basetable,$id,$data);
        }else{
            $this->view->session = $this->_model->findById($this->_basetable,$id);
            $this->view->season = $this->_model->get_all("season",array("id","season_name"));
        }

    }
    public function createAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->season = $this->_model->get_all("season",array("id","season_name"));
        if (isset($_POST['session_name'])){
            $data = $_POST;
            $this->_model->insert_item($this->_basetable,$data);
        }
        echo $this->view->render('session/edit.phtml');
    }
    
    public function getProductNumberAction(){
        $season_id = $this->_getParam("season_id",false);
        $product_limit = $this->_getParam("product_limit",1);
        $number = $this->_model->get_product_number($season_id);
        $html = "";
        for ($i=$number;$i>0;$i--){
            $selected = ($i == $product_limit) ? 'selected="selected"' : '';
            $html .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        }
        echo $html;
        exit;
    }
}

