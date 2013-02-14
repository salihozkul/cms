<?php
class Site_Views_Helpers_GetPicture extends Zend_View_Helper_Abstract {

    public function getPicture($model,$module_id,$item_id,$type="medium"){
        $images = $model->get_picture($module_id,$item_id,$type);
        $pictures = array();
        $spot = "";
        foreach ($images as $image){
 /*           if (@$image['order'] === "1"){
                $spot = CMS_URL."/".$image['directory'].$image['name'];
            }else{*/
                $pictures [] = CMS_URL."/".$image['directory'].$image['name'];
//            }
        }
        return array("spot" => $spot,"pictures"=>$pictures);
	}


}
?>