<?php
class Application_Model_Common extends Cms_Model_Model {
    /**
     * Base table
     * @var string
     */
    protected $_name ;
    /**
     * 
     * Sayfalarin listesini gelen parametrelere gore olusturur.
     * @param array $params
     */
    public function list_pages($params=array()){
        $this->_name = "page";
        $select = $this->select()->from("page",array("id","name"));
        if (isset($params['query']) && isset($params['qtype'])){
            $select->where($params['qtype']." LIKE ? ","%".$params['query']."%");
        }
        $subselect = clone $select;
        if (isset($params['sortname']) && isset($params['sortorder'])){
            $select->order($params['sortname']." ".$params['sortorder']);
        }
        $select->limitPage($params['page'],$params['rp']);
        return array(
        	"data"     =>    $select->query()->fetchAll(),
            "total"    =>    $subselect->query()->rowCount(),
            "page"	   =>    $params['page'],
            "column"   =>    array("name"),
            "defaultId"=>    "id"
        );
    }
    /**
     * 
     * İceriklerin listesini gelen parametrelere gore olusturur.
     * @param array $params
     */
    public function list_contents($params=array()){
        $this->_name = "content";
        $select = $this->select()->from("content",array("id","title","IF(status>0, 'Yayında', 'Yayında Değil') as status"))
        ->joinLeft("page", "page.id=content.page_id", array("name"));
        if (isset($params['query']) && isset($params['qtype'])){
            $select->where($params['qtype']." LIKE ? ","%".$params['query']."%");
        }
        $subselect = clone $select;
        if (isset($params['sortname']) && isset($params['sortorder'])){
            $select->order($params['sortname']." ".$params['sortorder']);
        }
        $select->limitPage($params['page'],$params['rp']);
        return array(
        	"data"     =>    $select->query()->fetchAll(),
            "total"    =>    $subselect->query()->rowCount(),
            "page"	   =>    $params['page'],
            "column"   =>    array("title","name","status"),
            "defaultId"=>    "id"
        );
    }
    /**
     * 
     * Sirketlerin listesini gelen parametrelere gore olusturur.
     * @param array $params
     */
     public function list_companies($params=array()){
        $this->_name = "content";
        $select = $this->select()->from("company",array("id","company_name") )
        ->joinLeft("location", "company.location_id=location.id", array("city"))
        ->joinLeft("company_category", "company.company_category_id=company_category.id", array("category"));
        if (isset($params['query']) && isset($params['qtype'])){
            $select->where($params['qtype']." LIKE ? ","%".$params['query']."%");
        }
        $subselect = clone $select;
        if (isset($params['sortname']) && isset($params['sortorder'])){
            $select->order($params['sortname']." ".$params['sortorder']);
        }
        $select->limitPage($params['page'],$params['rp']);
        return array(
        	"data"     =>    $select->query()->fetchAll(),
            "total"    =>    $subselect->query()->rowCount(),
            "page"	   =>    $params['page'],
            "column"   =>    array("company_name","city","category"),
            "defaultId"=>    "id"
        );
    }
    /**
     * 
     * Sezonlarin listesini gelen parametrelere gore olusturur.
     * @param array $params
     */
    public function list_seasons($params=array()){
        $this->_name = "season";
        $select = $this->select()->from("season",array("id","season_name","IF(status>0, 'Yayında', 'Yayında Değil') as status","start_date","end_date"))
        ->joinLeft("company", "company.id=season.company_id", array("company_name"));
        if (isset($params['query']) && isset($params['qtype'])){
            $select->where($params['qtype']." LIKE ? ","%".$params['query']."%");
        }
        $subselect = clone $select;
        if (isset($params['sortname']) && isset($params['sortorder'])){
            $select->order($params['sortname']." ".$params['sortorder']);
        }
        $select->limitPage($params['page'],$params['rp']);
        return array(
        	"data"     =>    $select->query()->fetchAll(),
            "total"    =>    $subselect->query()->rowCount(),
            "page"	   =>    $params['page'],
            "column"   =>    array("season_name","company_name","start_date","end_date","status"),
            "defaultId"=>    "id"
        );
    }
    
	/**
     * 
     * Seanslarin listesini gelen parametrelere gore olusturur.
     * @param array $params
     */
    public function list_sessions($params=array()){
        $this->_name = "session";
        $select = $this->select()->from("session",array("id","session_name","product_limit","start_date","end_date"))
        ->joinLeft("season", "season.id=session.season_id", array("season_name"));
        if (isset($params['query']) && isset($params['qtype'])){
            $select->where($params['qtype']." LIKE ? ","%".$params['query']."%");
        }
        $subselect = clone $select;
        if (isset($params['sortname']) && isset($params['sortorder'])){
            $select->order($params['sortname']." ".$params['sortorder']);
        }
        $select->limitPage($params['page'],$params['rp']);
        return array(
        	"data"     =>    $select->query()->fetchAll(),
            "total"    =>    $subselect->query()->rowCount(),
            "page"	   =>    $params['page'],
            "column"   =>    array("session_name","season_name","product_limit","start_date","end_date"),
            "defaultId"=>    "id"
        );
    }
    
    
	/**
     * 
     * Urunlerin listesini gelen parametrelere gore olusturur.
     * @param array $params
     */
    public function list_products($params=array()){
        $this->_name = "product";
        $select = $this->select()->from("product",array("id","name","total"))
        ->joinLeft("season", "product.season_id=season.id", array("season_name"));
        if (isset($params['query']) && isset($params['qtype'])){
            $select->where($params['qtype']." LIKE ? ","%".$params['query']."%");
        }
        $subselect = clone $select;
        if (isset($params['sortname']) && isset($params['sortorder'])){
            $select->order($params['sortname']." ".$params['sortorder']);
        }
        $select->limitPage($params['page'],$params['rp']);
        return array(
        	"data"     =>    $select->query()->fetchAll(),
            "total"    =>    $subselect->query()->rowCount(),
            "page"	   =>    $params['page'],
            "column"   =>    array("name","season_name","total"),
            "defaultId"=>    "id"
        );
    }
    /**
     * 
     * Modul ismine gore resim ayarlarini getirir.
     * @param string $module_name
     */
    public function get_imageSetting($module_name){
        return $this->select()
        ->from("image_settings")
        ->joinLeft("module", "module.id=image_settings.module_id", array())
        ->where("module.module_name LIKE ?","%".$module_name."%")
        ->query()
        ->fetchAll();
    }
    /**
     * 
     * Resimleri kaydeder.
     * @param array $data
     */
    public function save_image($data=array()){
        $this->_name = "images";
        foreach ($data as $dt) {
            $this->insert($dt);
        }
    }
	/**
     * 
     * Modul ismine gore en kucuk resim standatini ve module id sini gonderir.
     * @param string $module_name
     */
    public function get_small_identifier($module_name){
        $settings = $this->select()
        ->from("image_settings")
        ->joinLeft("module", "module.id=image_settings.module_id", array())
        ->where("module.module_name LIKE ?","%".$module_name."%")
        ->query()
        ->fetchAll();
        $first = null;
	    $data = array();
        foreach ($settings as $setting) {
            if ($first){
			    if ($first > $setting['width']){
			        $first = $setting['width'];
			        $data =array("module_id"=>$setting['module_id'],"type"=> $setting['type']);
			    }
			}else{
			    $first = $setting['width'];
			    $data =array("module_id"=>$setting['module_id'],"type"=> $setting['type']);
			}
        }
        return $data;
    }
    /**
     * 
     * Module id, item id ve resim tipine gore ilgili resimleri dondurur. 
     * @param integer $module_id
     * @param integer $item_id
     * @param string  $type
     */
    public function get_images($module_id,$item_id,$type){
        return $this->select()
        ->from("images")
        ->where("module_id=?",$module_id)
        ->where("item_id=?",$item_id)
        ->where("type=?",$type)
        ->query()
        ->fetchAll();
    }
    /**
     * Yeni eklenen resimin diger boyuttakileriyle ortak eslesmesi icin, counter adiyla ortak bir sayi tutulur
     * Bu sayi da eklenmis olanlardan farkli olsun diye son eklenenin bir fazlasi alinir
     */
    public function get_last_count(){
        $counter = $this->select()->from("images", array("MAX(counter)"))->query()->fetchColumn();
        return $counter;
    }
    /**
     * 
     * Module id ve counter a gore ilgili resimleri siler.
     * @param integer $module_id
     * @param integer $counter
     */
    public function removeImage($module_id,$counter){
        $this->_name = "images";
        
        $deleted = $this->select()->from("images", array("directory","name"))
        ->where("module_id=?",$module_id)
        ->where("counter=?",$counter)
        ->query()->fetchAll();
        foreach ($deleted as $del) {
            try {
                unlink($del['directory'].$del['name']);
            } catch (Exception $e) {
            }
        }
        
        $this->_db->query("DELETE FROM images WHERE module_id=? AND counter=?", array($module_id,$counter));
    }
    
    public function get_product_number($season_id) {
        return $this->select()->from("product",array("total"))->where("season_id=?",$season_id)->query()->fetchColumn();
    }
}