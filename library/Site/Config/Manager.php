<?php
class Site_Config_Manager {
	/**
	 *
	 * @var Zend_Cache_Backend_Memcached
	 */
	protected static $_cache	 = null ;
	protected static $_cacheable = true;
	/**
	 *
	 * inits cache system
	 */
	protected static function initCache(){
		if(null == self::$_cache) {
			$frontendOptions = array(
         'lifetime' => 86400, // cache lifetime of 1 day
         'automatic_serialization' => true
			);
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/memcache.ini', APPLICATION_ENV);
			$backendOptions = $config->toArray();
			self::$_cacheable = $backendOptions ['enabled'];
			if(self::$_cacheable) {
				self::$_cache = Zend_Cache::factory('Core',
                                   'Memcached',
				$frontendOptions,
				$backendOptions);
			}
		}
	}
	/**
	 *
	 * Retrives config from cache , if not exists constructs it and saves to memcache
	 *
	 * @param string $config
	 * @param string $env
	 */
	public static function getConfig($config,$env=APPLICATION_ENV,$toArray=true){
		if(empty($config)) {
			return array();
		}
		self::initCache();
		if(!self::$_cacheable) {
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/'.$config, $env);
			if($toArray) {
				$config = $config->toArray();
			}
			return $config;
		}
		$configKey = md5($config.$env);
		if(self::$_cache->test($configKey)){
			return self::$_cache->load($configKey);
		}
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/'.$config, $env);
		if($toArray) {
			$config = $config->toArray();
		}
		self::$_cache->save($config, $configKey);
		return $config;
	}
}
?>