<?php
class Site_Config_Memcache {
    private static $instance;
    /**
     *
     * @var Zend_Cache_Core
     */
    private static $cache;
    private function __construct($memCache){
        $config = Site_Config_Manager::getConfig('memcache.ini');
        $frontendOptions = array(
          'lifetime'                 => isset($config['lifetime'])?$config['lifetime']:720,
          'automatic_serialization'  => true);
        $backendOptions = $config;
        if($config['enabled'] && $memCache){
            $cache = Zend_Cache::factory(
          'Core',
          'Memcached',
            $frontendOptions,
            $backendOptions);
        }else{
            $cache = Zend_Cache::factory(
          'Core',
          'Black.hole',
            $frontendOptions,
            $backendOptions);
        }
        self::$cache = $cache;
    }
    // The singleton method
    public static function singleton(){
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
    public function __clone(){
        return self::$instance;
    }
    public static function getCache($memCache=true){
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c($memCache);
        }
        return self::$cache;
    }
}