<?php
class Cms_Controller_Action extends Common_Controller_Action {
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
    
	public function init(){
		if (!Zend_Auth::getInstance()->hasIdentity()){
			$this->_redirect('/login');
			exit;
		}else{
		    parent::init();
			$identity = Zend_Auth::getInstance()->getIdentity();
			$this->view->user = $identity;
			$this->view->headTitle(' - Admin Panel ');
		    $this->_js = new Cms_Util_Javascript();
		    if ($this->_request->isXmlHttpRequest()){
		        $this->_helper->layout->disableLayout();
		    }
		    $this->_image_path = "files/".$this->_request->getControllerName()."/";
		}
	}
	
	
	public function table($params=array()){
	    $grid_id = "gridtable";
        
        $colModel = $params['colModel'];
        $sortname = $params['sortname'];
        $sortorder = $params['sortorder'];
        /*
         * Aditional Parameters
         */
        $gridParams = array(
    		'width' => 'auto',
    		'height' => 350,
    		'rp' => 15,
    		'rpOptions' => '[10,15,20,25,40]',
    		'pagestat' => 'Gösterilen: {from} den {to} e , Toplam {total} kayıt.',
    		'blockOpacity' => 0.5,
    		'title' => $params['title'],
    		'showTableToggleBtn' => false
        );
        /*
         * 0 - display name
         * 1 - bclass
         * 2 - onpress
         */

        $buttons[] = array('Hepsi','check','test');
        $buttons[] = array('Hiçbiri','uncheck','test');
        $buttons[] = array('separator');
        $buttons[] = array('Yeni','add','test');
        $buttons[] = array('Düzenle','edit','test');
        $buttons[] = array('Sil','delete','test');
        $buttons[] = array('separator');
        
        if (isset($params['buttons'])){
            $buttons[] = $params['buttons'];
        }

        $url = "/".$this->_request->getControllerName()."/index";
        $this->view->flexigrid = $this->_js->initFlexigrid($grid_id, $url, $colModel, $sortname, $sortorder,$gridParams,$buttons);
        /**
         * Default action urls
         */
        $this->view->url_edit = "/".$this->_request->getControllerName()."/edit";
        $this->view->url_new = "/".$this->_request->getControllerName()."/create";
        $this->view->url_delete = "/".$this->_request->getControllerName()."/delete";
        $this->view->url_image = "/".$this->_request->getControllerName()."/image";
        echo $this->view->render('elements/table.phtml');
	}
	
    public function deleteAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		try {
			$this->_model->deleteById($this->_basetable,$this->_request->getParam('items'));
			$this->view->flashMessage('Kayıt Silindi !');
		}catch(Exception $e){
			$this->view->flashMessage($e->getMessage());
		}
		echo "1";
		exit();
	}
	/**
	 * 
	 * random bir isim dondurur
	 */
    public function generate_random_name (){
		return time () . substr ( md5 ( microtime () ), 0, rand ( 5, 12 ) );
	}
	
	/**
	 * gelen degeri url ye uygun olacak yapiya getirir.
	 * @param string $string
	 */
	public function make_url($string){
		$string = str_replace ( "İ", "i", "$string");
		$string = str_replace ( "ı", "i", "$string");
		$string = str_replace ( "Ü", "u", "$string");
		$string = str_replace ( "ü", "u", "$string");
		$string = str_replace ( "ö", "o", "$string");
		$string = str_replace ( "Ö", "o", "$string");
		$string = str_replace ( "ş", "s", "$string");
		$string = str_replace ( "Ş", "s", "$string");
		$string = str_replace ( "ç", "c", "$string");
		$string = str_replace ( "Ç", "c", "$string");
		$string = str_replace ( "ğ", "g", "$string");
		$string = str_replace ( "Ğ", "g", "$string");
		$string = str_replace ( " ", "_", "$string");
		$string = str_replace ( "-", "_", "$string");
		$string = str_replace ( ".", "", "$string");
		$string = str_replace ( ",", "", "$string");
		$string = str_replace ( "?", "", "$string");
		$string = str_replace ( ":", "", "$string");
		$string = str_replace ( ";", "", "$string");
		$string = str_replace ( "!", "", "$string");
		$string = str_replace ( "&", "ve", "$string");
		$string = str_replace ( "%", " ", "$string");
		$string = str_replace ( "'", "", "$string");
		$string = strtolower ( $string );
		
		return $string;
	}
	
    public function saveImageAction(){
        
        $this->_image = new Cms_Util_Images();  
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->controller = $this->_request->getControllerName();
        $this->view->id = $this->_request->getParam("id");
        if (empty($_FILES)) {
			
			
			$input = fopen("php://input", "r");
	        $temp = tmpfile();
	        $realSize = stream_copy_to_stream($input, $temp);
	        fclose($input);
	        
	        $settings = $this->_model->get_imageSetting($this->view->controller);
	        $save_name = $this->generate_random_name() . "_default.jpg";
	        
	        $target = fopen($this->_image_path.$save_name, "w");        
	        fseek($temp, 0, SEEK_SET);
	        stream_copy_to_stream($temp, $target);
	        fclose($target);
			
	        $uploaded_file = $this->_image_path.$save_name; 
	        $data = array();
	        $first = null;
	        $thumb = false;
	        $rand_name = $this->generate_random_name();
	        $counter = $this->_model->get_last_count() + 1; // 
	        foreach ($settings as $setting){
	            $save_name = $rand_name ."_".$this->make_url($setting['type']).".jpg";
    			$this->_image->resize($uploaded_file, $setting['width'], $setting['height'] , $this->_image_path, $save_name , 255, 255, 255);
    			$data[] = array(	"module_id"    =>$setting['module_id'],
    			                    "item_id"      =>$this->view->id,
    			                    "counter"	   =>$counter,
    			                    "directory"    =>$this->_image_path,
    			                    "name"         =>$save_name,
    			                    "type"         =>$setting['type']);
    			if ($first){
    			    if ($first > $setting['width']){
    			        $first = $setting['width'];
    			        $thumb = $save_name;
    			    }
    			}else{
    			    $first = $setting['width'];
    			    $thumb = $save_name;
    			}
	        }
	        $this->_model->save_image($data);
	        
	        try {
	            unlink($uploaded_file);
	        } catch (Exception $e) {
	        }
			echo htmlspecialchars(json_encode(array("name"=>$this->_image_path.$thumb,"success"=>TRUE)), ENT_NOQUOTES);
		}
		exit;
    }
    
    public function imageAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->controller = $this->_request->getControllerName();
        $this->view->id = $this->_request->getParam("id");
        $settings = $this->_model->get_small_identifier($this->view->controller);
        $this->view->images = $this->_model->get_images($settings['module_id'],$this->view->id,$settings['type']);
        echo $this->view->render('elements/image.phtml');
    }
    
    public function removeImageAction(){
        $module_id = $this->_getParam("module_id",false);
        $counter = $this->_getParam("counter",false);
        $this->_model->removeImage($module_id,$counter);
        echo "1";
        exit;
    }
}