<?php

class IndexController extends Site_Controller_Action{
    protected $gift;
    public function init(){
        parent::init();
        $this->_model = new Application_Model_Common();
    }
    
    public function indexAction(){
        $id = (int) $this->_getParam("id",null);
        $this->view->gift = $this->gift =  $this->_model->get_gift($id);
        
        
        if ($this->_request->isPost()){
            $data = $_POST;
            unset($_POST);
            $this->user_login($data['username'],$data['password']);
            $this->_redirect("/index");
        }
       $id = (int) $this->_getParam("id",null);
       $this->view->model= $this->_model; 

       $this->view->slider = $this->view->render('elements/slider.phtml');
       $this->view->sidebar= $this->view->render('elements/sideBar.phtml');
       if(isset($this->user)){
           $is_awarded = $this->_model->is_awarded_user($this->gift['season_id'],$this->user->id);
           if ($is_awarded){
               $this->view->is_awarded = "Tebrikler hediyemizi kazandığınız için artık bu sezonda aynı hediyeden başka kazanamazsınız...";
               $this->view->result = $this->view->render('elements/win.phtml');
           }else{
               $last_insert = $this->_model->get_last_insert($this->gift['session_id'],$this->user->id);
               $this->view->deal_left = $this->gift['click_limit']-$last_insert['user_count'];
               $this->view->time_left = $this->calculateTimeLimit($last_insert['date']); 
           }
           $this->view->deal = $this->view->render('elements/deal.phtml');
           
       }else{
           $this->view->deal = $this->view->render('elements/dealredirect.phtml');   
       }
    }
    
    public function checkDealAction(){
        $id = (int) $this->_getParam("id",null);
        $this->view->gift = $this->gift =  $this->_model->get_gift($id);

        $result = $this->_model->add_session_record($id,$this->user->id,$this->gift['click_limit']);
        if (isset($result['last_count'])){
            $this->view->last_count = $result['last_count'];
            if (in_array($result['last_count'], explode("|", $this->gift['award_numbers']))){
                $date = date("Y-m-d H:i:s");
                $this->_model->add_award($this->user->id,$this->gift,$result['last_count'],$date);
                $this->view->result = $this->view->render('elements/win.phtml'); 
                $this->view->is_awarded = "Tebrikler hediyemizi kazandığınız için artık bu sezonda aynı hediyeden başka kazanamazsınız...";
            }else{
                $this->winners = $this->_model->get_winners($this->gift['session_id']);
                $this->view->result = $this->view->render('elements/winners.phtml');
                $this->view->deal_left = $this->gift['click_limit']-$result['user_count'];
                $this->view->time_left = $this->calculateTimeLimit($result['date']); 
            }
           
        }else{
            $this->view->result = "Bu Seansda tık hakkınız kalmamıştır. Kampanyalarımızı takip edin, bir sonraki seansda şansınızı deneyiniz...";
        }
        echo $this->view->render('elements/deal.phtml'); 
        exit;
    }
    
    public function dealAction(){
        $id = (int) $this->_getParam("id",null);
        $this->view->gift = $this->gift =  $this->_model->get_gift($id);
        
        $last_insert = $this->_model->get_last_insert($this->gift['session_id'],$this->user->id);
        $this->view->deal_left = $this->gift['click_limit']-$last_insert['user_count'];
        echo $this->view->render('elements/deal.phtml');
        exit;
    }
    
    public function calculateTimeLimit($date){
        return abs(strtotime(date("Y-m-d H:i:s")) - strtotime($date)-4);
    }
    
    public function howtoAction(){
        
        
    }
    
    
}

