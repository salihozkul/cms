<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function __construct($application){
      parent::__construct($application);
      $locale  = new Zend_Locale();
      Zend_Registry::set('Zend_Locale', $locale);
      date_default_timezone_set('Asia/Istanbul');
    }
    /** Route ayarlarini yapar
     */
    protected function _initRoutes() {
        $config           = Common_Config_Manager::getConfig('routes.ini','routes',false);
        $frontController  = Zend_Controller_Front::getInstance ();
        $router           = $frontController->getRouter();
        $router->addConfig($config, 'routes');
    }

}

