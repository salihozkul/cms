<?php
class Site_Views_Helpers_Navigation extends Zend_View_Helper_Abstract {

    public function Navigation($key){
        $params = explode('/',substr($_SERVER['REQUEST_URI'],1));
        return ($params[0] == $key ) ? 'active' : 'passive';
	}


}
?>