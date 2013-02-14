<?php
abstract class Site_Model_Model extends Zend_Db_Table {
    protected $cache;
    public function init(){
        parent::init();
        $this->cache = Site_Config_Memcache::getCache();
    }
    protected function setCacheState(){
		$this->cacheable = true;
	}
	
    public function __construct($config = array(), $definition = null){
	  $this->setCacheState();
      $frontendOptions = array(
         'lifetime' => 7200, // cache lifetime of 2 hours
         'automatic_serialization' => true
      );
      $backendOptions = Site_Config_Manager::getConfig('memcache.ini');
      $this->cache = Zend_Cache::factory('Core',
                                   'Memcached',
                                   $frontendOptions,
                                   $backendOptions);   
		//$this->setDefaultMetadataCache($this->cache);
		parent::__construct($config,$definition);
	}
	
    /**
     * (non-PHPdoc)
     * @see library/Zend/Db/Table/Zend_Db_Table_Abstract::select()
     */
    public function select($withFromPart=Zend_Db_Table::SELECT_WITHOUT_FROM_PART){
        $parent = parent::select($withFromPart);
        $parent->setIntegrityCheck(false);
        return $parent;
    }

    /**
     *
     * Verilen id ye gore base table dan tek satir veya birden fazla satir ceker
     * $cols istenilen satirdaki sutun isimleri
     * @param int $id
     * @param array $cols
     */
    public function findById ($basetable,$id,$cols=array("*")){
        $this->_name = $basetable;
        $select = $this->select()->from($this->_name,$cols);
        $select->where("id=?",$id);
        $subselect = clone $select;
        if ($subselect->query()->rowCount() > 0 ){
            return $select->query()->fetch();
        }else{
            return $select->query()->fetchAll();
        }
    }
    /**
     *
     * Record is deleted by given id
     * @param $id
     * @return boolean
     */
    public function deleteById($basetable,$id){
        $this->_name = $basetable;
        $_where = $this->_db->quoteInto("id IN (?)",explode(",", $id));
        return $this->delete($_where);
    }

    /**
     *
     * Tablo ismine gore tum icerigi veya istenen kolonlari getirir.
     * @param String $table
     * @param array $cols
     */
    public function get_all($table,$cols = array("*"),$where = null,$order=null){
        $select =  $this->select()
        ->from($table,$cols);
        if ($where){
            $select->where($where);
        }
        if ($order){
            $select->order($order);
        }
        return $this->cacheAll($select->__toString());
    }
    /**
     * 
     * Verilen id li kayiti update eder.
     * @param string $basetable
     * @param integer $id
     * @param array $data
     */
    public function update_item($basetable,$id,$data){
        $this->_name = $basetable;
        $where = $this->_db->quoteInto("id=?", $id);
        $this->update($data, $where);
    }
    
    public function insert_item($basetable,$data){
        $this->_name = $basetable;
        $this->insert($data);
    }
    
    public function cacheAll($sql){
        $cacheKey = 'result_'.md5($sql);
		if($this->cacheable && $this->cache->test($cacheKey)){
			return $this->cache->load($cacheKey);
		}
		$results  = Array();
		try{
            $a = debug_backtrace();
            $b= '';
            $i = 1;
            if(isset($a[$i]))
                $b = $a[$i]['function'];
			$results =  $this->_db->query($sql.'#'.$b)->fetchAll();
		}catch(Exception $e){
			echo  $e->getMessage();
		}
		if($this->cacheable){
			$this->cache->save($results, $cacheKey);
		}
		return $results;
    }
    public function cacheOne($sql){
        $cacheKey = 'result_'.md5($sql);
		if($this->cacheable && $this->cache->test($cacheKey)){
			return $this->cache->load($cacheKey);
		}
		$results  = Array();
		try{
            $a = debug_backtrace();
            $b= '';
            $i = 1;
            if(isset($a[$i]))
                $b = $a[$i]['function'];
			$results =  $this->_db->query($sql.'#'.$b)->fetch();
		}catch(Exception $e){
			echo  $e->getMessage();
		}
		if($this->cacheable){
			$this->cache->save($results, $cacheKey);
		}
		return $results;
    }
    
    
    
}