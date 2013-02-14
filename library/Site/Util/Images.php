<?php
class Cms_Util_Images{
	public function images(){
	}
	
	public function listImages(){
		
	}
	
/*
 * 
 * Image Resize Related Methods
 */
	
/**
 * 
 * @param $imorig
 * @param $width
 * @param $height
 * @param $save_dir
 * @param $save_name
 * 
 * Resizes an image from direct source, not a temp directory.
 */
	public function resizeFromSource( $imorig, $width, $height , $save_dir, $save_name ) {
		$save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
		$gis       = GetImageSize($imorig);
	
		$image_width = $gis[0];
		$image_height = $gis[1];
		
		if( $image_width > $width && $image_height <= $height ) {
			
			$temp_ratio = ($image_width-$width) / $image_width ;
			$new_height = $image_height - ( $temp_ratio * $image_height );
			
			$img = imagecreate( $width , $new_height );
			$img = imagecreatetruecolor( $width , $new_height );
			
			imagecopyresampled($img,$imorig , 0 , 0, 0, 0 , $width,$new_height,$image_width,$image_height);
			
		}elseif( $image_height > $height && $image_width <= $width ) {

			$temp_ratio = ($image_height-$height) / $image_height ;
			$new_width = $image_width - ( $temp_ratio * $image_width );

			$img = imagecreate( $new_width , $height );
			$img = imagecreatetruecolor( $new_width , $height );
			
			imagecopyresampled($img,$imorig , 0 , 0, 0, 0 , $new_width,$height,$image_width,$image_height);
			
		}elseif( $image_height > $height && $image_width > $width ) {
			$ratio_width = $width / $image_width;
			$ratio_height = $height / $image_height ;
			
			if( $ratio_width < $ratio_height ) {
				$ratio = 1 - $ratio_width;
			}else {
				$ratio = 1 - $ratio_height ;
			}
			
			$new_width = $image_width - ( $ratio * $image_width );
			$new_height = $image_height - ( $ratio * $image_height ) ;
			
			$img = imagecreate( $new_width, $new_height );
			$img = imagecreatetruecolor( $new_width, $new_height );
			
			imagecopyresampled($img,$imorig , 0,0 ,0,0,$new_width,$new_height,$image_width,$image_height);
			
		}else {
			$img = imagecreate( $image_width, $image_height );
			$img = imagecreatetruecolor( $image_width, $image_height );
			
			imagecopyresampled($img,$imorig , 0,0 ,0,0,$image_width,$image_height,$image_width,$image_height);
		}
		
		imagejpeg($img, $save_dir.$save_name);
		chmod($save_dir.$save_name ,0777);
		return TRUE;
	}

/**
 * 
 * @param unknown_type $image
 * @param unknown_type $width
 * @param unknown_type $height
 * @param unknown_type $save_dir
 * @param unknown_type $save_name
 * @param unknown_type $red
 * @param unknown_type $green
 * @param unknown_type $blue
 * 
 * Resizes an image from a source but cut if any dimensions overflow
 */
	
	public function resizeCutFromSource( $image, $width, $height , $save_dir, $save_name , $red, $green, $blue){
		$gis = GetImageSize($image);
		if( !is_array( $fis ) ) {
			$save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
			
			$image_width = $gis[0];
			$image_height = $gis[1];
			
			$imorig = imagecreatefromjpeg($image);
			
			$left = 0 ;
			$top = 0 ;
			if( ($width-$image_width) != 0 )
				$left = ($width-$image_width)/2 ;
			if( ($height-$image_height) != 0 )
				$top = ($height-$image_height)/2 ;
			$ratio = 0 ;
			if( $image_width > $width && $image_height > $height ){
				$wr = $width / $image_width;
				$hr = $height / $image_height;
				if( $wr > $hr ) {
					$ratio = 1 - $wr ;
					$top = ($image_height-$height)/2*( $ratio/$hr);
					$left = 0 ;
				}else {
					$ratio = 1 - $hr ;
					$left = ($image_width-$width)/2*( $ratio/$wr);
					$top = 0 ;
				}
			}
			if( $ratio == 0 ) {
				$im = imagecreate( $width , $height );
				$im = imagecreatetruecolor( $width , $height );
				$imtemp = imagecreate( 1 , 1 );
				$imtemp = imagecreatetruecolor( 1 , 1 );
				$spacer = imagecolorallocate($imtemp, $red, $green, $blue);
				for ( $i=0 ; $i < $height ; $i++){
					for( $j = 0 ; $j < $width ; $j++ ) {
						imagefill($im,$j,$i,$spacer);
					}
				}
				if( $left <= 0 && $top >= 0 ) {
					imagecopyresampled($im,$imorig , 0,abs($top),abs($left),0,$width,$image_height,$width,$image_height);
					imagejpeg($im, $save_dir.$save_name);
					chmod($save_dir.$save_name ,0777);
				}elseif( $top <= 0 && $left >= 0 ) {
					imagecopyresampled($im,$imorig , abs($left),0,0,abs($top),$image_width,$height,$image_width,$height);
					imagejpeg($im, $save_dir.$save_name);
					chmod($save_dir.$save_name ,0777);
				}elseif( $left > 0 && $top > 0 ) {
					imagecopyresampled($im,$imorig , abs($left),abs($top),0,0,$image_width,$image_height,$image_width,$image_height);
					imagejpeg($im, $save_dir.$save_name);
					chmod($save_dir.$save_name ,0777);
				}
			}else {
				$im = imagecreate( $image_width - $image_width * $ratio, $image_height - $image_height * $ratio);
				$im = imagecreatetruecolor( $image_width - $image_width * $ratio, $image_height - $image_height * $ratio);
				$new_image_width = $image_width - $image_width * $ratio;
				$new_image_height =  $image_height - $image_height * $ratio;
				imagecopyresampled($im,$imorig , 0,0,0,0, $new_image_width, $new_image_height,$image_width ,$image_height);
				$img = imagecreate($width, $height);
				$img = imagecreatetruecolor($width, $height);
				if( $width ==  $new_image_width ) {
					$top = ( $new_image_height - $height ) / 2 ;
					imagecopyresampled($img,$im , 0, 0,0, $top ,$width,$height,$width, $height);
					imagejpeg($img, $save_dir.$save_name);
					chmod($save_dir.$save_name ,0777);
				}else {
					$left = ( $new_image_width - $width ) / 2 ;
					imagecopyresampled($img,$im , 0,0,$left,0,$width,$height,$width, $height);
					imagejpeg($img, $save_dir.$save_name);
					chmod($save_dir.$save_name ,0777);
				}
			}
			return TRUE;
		}else {
			return FALSE;
		}
	}
	
	public function resize( $tmpname, $width, $height , $save_dir, $save_name ) {
		$save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
		$gis       = GetImageSize($tmpname);
		$type       = $gis[2];
		switch($type){
			case "1":
				$imorig = imagecreatefromgif($tmpname);
				break;
			case "2":
				$imorig = imagecreatefromjpeg($tmpname);
				break;
			case "3":
				$imorig = imagecreatefrompng($tmpname);
				break;
			default:
				$imorig = imagecreatefromjpeg($tmpname);
				break;
		}
		$image_width = imageSX($imorig);
		$image_height = imageSY($imorig);
		
		if( $image_width > $width && $image_height <= $height ) {
			
			$temp_ratio = ($image_width-$width) / $image_width ;
			$new_height = $image_height - ( $temp_ratio * $image_height );
			
			$img = imagecreate( $width , $new_height );
			$img = imagecreatetruecolor( $width , $new_height );
			
			imagecopyresampled($img,$imorig , 0 , 0, 0, 0 , $width,$new_height,$image_width,$image_height);
			
		}elseif( $image_height > $height && $image_width <= $width ) {

			$temp_ratio = ($image_height-$height) / $image_height ;
			$new_width = $image_width - ( $temp_ratio * $image_width );

			$img = imagecreate( $new_width , $height );
			$img = imagecreatetruecolor( $new_width , $height );
			
			imagecopyresampled($img,$imorig , 0 , 0, 0, 0 , $new_width,$height,$image_width,$image_height);
			
		}elseif( $image_height > $height && $image_width > $width ) {
			$ratio_width = $width / $image_width;
			$ratio_height = $height / $image_height ;
			
			if( $ratio_width < $ratio_height ) {
				$ratio = 1 - $ratio_width;
			}else {
				$ratio = 1 - $ratio_height ;
			}
			
			$new_width = $image_width - ( $ratio * $image_width );
			$new_height = $image_height - ( $ratio * $image_height ) ;
			
			$img = imagecreate( $new_width, $new_height );
			$img = imagecreatetruecolor( $new_width, $new_height );
			
			imagecopyresampled($img,$imorig , 0,0 ,0,0,$new_width,$new_height,$image_width,$image_height);
			
		}else {
			$img = imagecreate( $image_width, $image_height );
			$img = imagecreatetruecolor( $image_width, $image_height );
			
			imagecopyresampled($img,$imorig , 0,0 ,0,0,$image_width,$image_height,$image_width,$image_height);
		}
		
		imagejpeg($img, $save_dir.$save_name);
		chmod($save_dir.$save_name ,0777);
		return TRUE;
	}
	
	public function resize_cut($tmpname, $width, $height , $save_dir, $save_name , $red, $green, $blue){
		$save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
		$gis       = GetImageSize($tmpname);
		$type       = $gis[2];
		switch($type){
			case "1":
				$imorig = imagecreatefromgif($tmpname);
				break;
			case "2":
				$imorig = imagecreatefromjpeg($tmpname);
				break;
			case "3":
				$imorig = imagecreatefrompng($tmpname);
				break;
			default:
				$imorig = imagecreatefromjpeg($tmpname);
				break;
		}
		$image_width = imageSX($imorig);
		$image_height = imageSY($imorig);
		$left = 0 ;
		$top = 0 ;
		if( ($width-$image_width) != 0 )
			$left = ($width-$image_width)/2 ;
		if( ($height-$image_height) != 0 )
			$top = ($height-$image_height)/2 ;
		$ratio = 0 ;
		if( $image_width > $width && $image_height > $height ){
			$wr = $width / $image_width;
			$hr = $height / $image_height;
			if( $wr > $hr ) {
				$ratio = 1 - $wr ;
				$top = ($image_height-$height)/2*( $ratio/$hr);
				$left = 0 ;
			}else {
				$ratio = 1 - $hr ;
				$left = ($image_width-$width)/2*( $ratio/$wr);
				$top = 0 ;
			}
		}
		if( $ratio == 0 ) {
			$im = imagecreate( $width , $height );
			$im = imagecreatetruecolor( $width , $height );
			$imtemp = imagecreate( 1 , 1 );
			$imtemp = imagecreatetruecolor( 1 , 1 );
			$spacer = imagecolorallocate($imtemp, $red, $green, $blue);
			for ( $i=0 ; $i < $height ; $i++){
				for( $j = 0 ; $j < $width ; $j++ ) {
					imagefill($im,$j,$i,$spacer);
				}
			}
			if( $left <= 0 && $top >= 0 ) {
				imagecopyresampled($im,$imorig , 0,abs($top),abs($left),0,$width,$image_height,$width,$image_height);
				imagejpeg($im, $save_dir.$save_name);
				chmod($save_dir.$save_name ,0777);
			}elseif( $top <= 0 && $left >= 0 ) {
				imagecopyresampled($im,$imorig , abs($left),0,0,abs($top),$image_width,$height,$image_width,$height);
				imagejpeg($im, $save_dir.$save_name);
				chmod($save_dir.$save_name ,0777);
			}elseif( $left > 0 && $top > 0 ) {
				imagecopyresampled($im,$imorig , abs($left),abs($top),0,0,$image_width,$image_height,$image_width,$image_height);
				imagejpeg($im, $save_dir.$save_name);
				chmod($save_dir.$save_name ,0777);
			}
		}else {
			$im = imagecreate( $image_width - $image_width * $ratio, $image_height - $image_height * $ratio);
			$im = imagecreatetruecolor( $image_width - $image_width * $ratio, $image_height - $image_height * $ratio);
			$new_image_width = $image_width - $image_width * $ratio;
			$new_image_height =  $image_height - $image_height * $ratio;
			imagecopyresampled($im,$imorig , 0,0,0,0, $new_image_width, $new_image_height,$image_width ,$image_height);
			$img = imagecreate($width, $height);
			$img = imagecreatetruecolor($width, $height);
			if( $width ==  $new_image_width ) {
				$top = ( $new_image_height - $height ) / 2 ;
				imagecopyresampled($img,$im , 0, 0,0, $top ,$width,$height,$width, $height);
				imagejpeg($img, $save_dir.$save_name);
				chmod($save_dir.$save_name ,0777);
			}else {
				$left = ( $new_image_width - $width ) / 2 ;
				imagecopyresampled($img,$im , 0,0,$left,0,$width,$height,$width, $height);
				imagejpeg($img, $save_dir.$save_name);
				chmod($save_dir.$save_name ,0777);
			}
		}
		return TRUE;
	}
}