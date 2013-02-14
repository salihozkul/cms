<?php 
/**
 * 
 * Viewler arası mesaj taşımak için kullanılacak olan view helper
 * 
 * @author ersin
 *
 */
class Common_Views_Helpers_FlashMessage extends Zend_View_Helper_Abstract {
	/**
	 * 
	 * Viewler arası mesaj taşıma için kullanılır.
	 * @return string
	 */
	public function flashMessage(){
		$_args = func_get_args();
		$_session = new Zend_Session_Namespace('flashMessage');
		$_message = '';
		if(isset($_session->message) && count($_args)==0){
			$_message = $_session->message;
			$_session->unlock();
			$_session->unsetAll();
		}elseif(count($_args)>0){
			$_session->message = $_args[0];
			$_message = $_session->message;
			$_session->lock();
		}
		else {
			$_session->unlock();
			$_session->message = isset($_args[0])?$_args[0]:'';
			$_message = $_session->message;
			$_session->lock();
		}
		if(!empty($_message)){
			return '<div class="message-box">'.$_message.'</div>';
		}
	}
	
	
}
?>