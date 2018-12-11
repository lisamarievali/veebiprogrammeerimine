<?php
  class Photoupload
  {
	  private $tempName;
	  private $imageFileType;
	  private $myTempImage;
	  private $myImage;
	  
	  function __construct($name, $type){
		$this->tempName = $name;
		$this->imageFileType = $type;
		$this->createImageFromFile();
      }
	  
	  function __destruct(){
		 
	
	  }
	  
	  private function createImageFromFile(){
		//sõltuvalt failitüübist loon sobiva pildiobjekti
		if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
		  $this->myTempImage = imagecreatefromjpeg($this->tempName);
		}
		if($this->imageFileType == "png"){
		  $this->myTempImage = imagecreatefrompng($this->tempName);
		}
		if($this->imageFileType == "gif"){
		  $this->myTempImage = imagecreatefromgif($this->tempName);
		}
	  }
	  
	  public function changePhotoSize($width, $height){
		//pildi originaalsuurus
		$imageWidth = imagesx($this->myTempImage);
		$imageHeight = imagesy($this->myTempImage);
		//leian suuruse muutmise suhtarvu
		if($imageWidth > $imageHeight){
		  $sizeRatio = $imageWidth / $width;
		} else {
		  $sizeRatio = $imageHeight / $height;
		}
				
		$newWidth = round($imageWidth / $sizeRatio);
		$newHeight = round($imageHeight / $sizeRatio);
				
		$this->myImage = resizeImage($this->myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight); 
	  }
	  
	  private function resizeImage($image, $ow, $oh, $w, $h){
	    $newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
	  }
	  	  
	  
  }//class lõppeb
?>