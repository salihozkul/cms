<?php
abstract class Cms_Model_Model extends Zend_Db_Table {
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
        return $select->query()
        ->fetchAll();
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
    
}