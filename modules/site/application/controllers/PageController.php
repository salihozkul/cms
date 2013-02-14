<?php

class PageController extends Site_Controller_Action{
    protected $params = array();
    public function init(){
        parent::init();
        $this->_basetable = "content";
        $this->_model = new Application_Model_Common($this->_basetable);
        $this->params = explode('/',substr($_SERVER['REQUEST_URI'],1));
    }
    public function get_page_id($name,$pages){
        foreach ($pages as $page) {
            if ($this->makeUrl(trim($page['name'])) === $this->makeUrl($name)){
                return $page['id'];
            }
        }
        return null;
    }
    public function indexAction(){
       $name = $this->params[0];
       $pages = $this->_model->get_all("page");
       $this->view->content = $this->_model->get_static_content($this->get_page_id($name, $pages));
    }
    
    
}

