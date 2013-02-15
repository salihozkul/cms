<?php
class Application_Model_Common extends Site_Model_Model {
    /**
     * Base table
     * @var string
     */
    protected $_name ;
    
    public function checkusername($username){
        return  $this->select()->from("user", array("id"))->where("username=?",$username)->query()->rowCount();
    }
    
    public function get_gift( $id = null ){
        $this->_name = "season";
        $select = $this->select()
                    ->from("season",array("id as season_id","season_name","company_id"))
                    ->joinLeft("session", "session.season_id=season.id",array("id as session_id","session_name","product_limit","start_date","end_date","click_limit","award_numbers"))
                    ->joinLeft("product", "product.season_id=season.id",array("id as product_id","name","description","total"))
                    ->joinLeft("company", "season.company_id=company.id",array("company_name","website","description as company_description"))
                    ->where("season.showon_mainpage=?",1)
                    ->where("status=?",1);
        if ($id){
            $select->where("session.id=?",$id);
        }            
        return $select->query()->fetch();
    }
    public function get_picture($module_id,$item_id,$type){
        $select=$this->select()
                    ->from("images",array("order","directory","name"))
                    ->where("module_id=?",$module_id)
                    ->where("item_id=?",$item_id);
        if ($type){
            $select->where("type=?",$type);
        }            
        return $select->query()->fetchAll();
    }
    
    public function add_session_record($session_id,$user_id,$click_limit){
        $this->_name = "result";
        $last_count = $this->select()->from("result",array("IFNULL(MAX(click_counter)+1,1)"))->where("session_id=?",$session_id)->query()->fetchColumn();
        $last_insert = $this->get_last_insert($session_id, $user_id);
        $date = date("Y-m-d H:i:s");
        $data = array("user_id"=>$user_id,"session_id"=>$session_id,"click_counter"=>$last_count,"date"=>$date);
        if ($last_insert['user_count'] < $click_limit){
            $this->insert($data);
            return array("last_count"=>$last_count,"user_count"=>$last_insert['user_count']+1,"date"=>$date);
        }else{
            return array("user_count"=>$last_insert['user_count']);
        }
    }
    public function get_winners($session_id){
        $this->_name = "award";
        $select = $this->select()->from($this->_name,array("click_number","date"));
        $select->joinLeft("user", "user.id = award.user_id",array("username"));
        $select->where("session_id=?",$session_id);
        return $select->query()->fetchAll();
    }
    
    public function get_last_insert($session_id,$user_id){
        return $this->select()->from("result",array("count(user_id) as user_count","MAX(date) as date"))->where("session_id=?",$session_id)->where("user_id=?",$user_id)->query()->fetch();
    }
    
    public function add_award($user_id,$gift,$click_number,$date){
        $this->_name = "award";
        $data = array("season_id" => $gift['season_id'],"session_id"=> $gift['session_id'],"product_id" => $gift['product_id'],"user_id" => $user_id,"click_number" => $click_number,"date" => $date);
        $this->insert($data);
    }
    
    public function is_awarded_user($season_id,$user_id){
        $this->_name = "award";
        $count = $this->select()->from("award")->where("season_id=?",$season_id)->where("user_id=?",$user_id)->query()->rowCount();
        if ($count > 0) {
            return true;
        }else{
            return false;
        }
    }
    
    
    public function get_static_content($page_id){
        $select = $this->select()->from("content");
        $select->where("status=1 AND page_id=?",$page_id);
        return $select->query()->fetch();
    }
    
    
    
    
    
    
    
    
    
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
}