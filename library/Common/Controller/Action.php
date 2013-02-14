<?php
class Common_Controller_Action extends Zend_Controller_Action {
	/**
	 * gelen degeri url ye uygun olacak yapiya getirir.
	 * @param string $string
	 */
	public function makeUrl($string){
		$string = str_replace ( "İ", "I", "$string");
		$string = str_replace ( "ı", "i", "$string");
		$string = str_replace ( "Ü", "U", "$string");
		$string = str_replace ( "ü", "u", "$string");
		$string = str_replace ( "ö", "o", "$string");
		$string = str_replace ( "Ö", "O", "$string");
		$string = str_replace ( "ş", "s", "$string");
		$string = str_replace ( "Ş", "S", "$string");
		$string = str_replace ( "ç", "c", "$string");
		$string = str_replace ( "Ç", "C", "$string");
		$string = str_replace ( "ğ", "g", "$string");
		$string = str_replace ( "Ğ", "G", "$string");
		$string = str_replace ( " ", "_", "$string");
		$string = str_replace ( "-", "_", "$string");
		$string = str_replace ( ".", "", "$string");
		$string = str_replace ( ",", "", "$string");
		$string = str_replace ( "?", "", "$string");
		$string = str_replace ( ":", "", "$string");
		$string = str_replace ( ";", "", "$string");
		$string = str_replace ( "!", "", "$string");
		$string = str_replace ( "&", "ve", "$string");
		$string = str_replace ( "%", " ", "$string");
		$string = str_replace ( "'", "", "$string");
		$string = strtolower ( $string );
		
		return $string;
	}
}