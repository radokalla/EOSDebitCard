<?php
 
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class SimpleImage {
 
   var $image;
   var $image_type;
   var $transparent_index;
   var $transparent_color;
 
   function load($filename) {

      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {

         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
                 
         $this->image = imagecreatefromgif($filename);         
         
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
 
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename,$permissions=null) {   
 		
	
		 
		 
      if( $this->image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {          
         imagegif($this->image,$filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }
      
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
	  
	  if(function_exists('exif_read_data'))
		{
			if( $this->image_type != IMAGETYPE_PNG ) {
		  $exif = exif_read_data($filename);
			 if(!empty($exif['Orientation'])) {
				switch($exif['Orientation']) {
				 case 8:
				  $this->image = imagerotate($this->image,90,0);
				  break;
				 case 3:
				  $this->image = imagerotate($this->image,180,0);
				  break;
				 case 6:
				  $this->image = imagerotate($this->image,-90,0);
				  break;
				}
				  if( $this->image_type == IMAGETYPE_JPEG ) {
					 imagejpeg($this->image,$filename);
				  } elseif( $this->image_type == IMAGETYPE_GIF ) {          
					 imagegif($this->image,$filename);
				  } elseif( $this->image_type == IMAGETYPE_PNG ) {
					 imagepng($this->image,$filename);
				  }
			 }	 
			}		 
			 	 
		}
		 
   }
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {       
      $new_image = imagecreatetruecolor($width, $height);

       if($this->image_type==IMAGETYPE_PNG)
       {           
          imagealphablending($new_image, false);
          imagesavealpha($new_image,true);
          $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
          imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
       }
       else if($this->image_type == IMAGETYPE_GIF)
       {
          imagecolortransparent($new_image, imagecolorallocatealpha($new_image, 0, 0, 0, 127));
          imagealphablending($new_image, false);
          imagesavealpha($new_image, true);
       }

      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image; 
   }      
 	
	
	function crop($max_width, $max_height, $method = 'scale', $bgColour = null)
	{
		// get the current dimensions of the image
		$src_width = $this->getWidth();
		$src_height = $this->getheight();
		
	// if either max_width or max_height are 0 or null then calculate it proportionally
	if( !$max_width ){
		$max_width = $src_width / ($src_height / $max_height);
	}
	elseif( !$max_height ){
		$max_height = $src_height / ($src_width / $max_width);
	}

	// initialize some variables
	$thumb_x = $thumb_y = 0;	// offset into thumbination image

	// if scaling the image calculate the dest width and height
	$dx = $src_width / $max_width;
	$dy = $src_height / $max_height;
	if( $method == 'scale' ){
		$d = max($dx,$dy);
	}
	// otherwise assume cropping image
	else{
		$d = min($dx, $dy);
	}
	$new_width = $src_width / $d;
	$new_height = $src_height / $d;
	// sanity check to make sure neither is zero
	$new_width = max(1,$new_width);
	$new_height = max(1,$new_height);

	$thumb_width = min($max_width, $new_width);
	$thumb_height = min($max_height, $new_height);

	// if bgColour is an array of rgb values, then we will always create a thumbnail image of exactly
	// max_width x max_height
	if( is_array($bgColour) ){
		$thumb_width = $max_width;
		$thumb_height = $max_height;
		$thumb_x = ($thumb_width - $new_width) / 2;
		$thumb_y = ($thumb_height - $new_height) / 2;
	}
	else{
		$thumb_x = ($thumb_width - $new_width) / 2;
		$thumb_y = ($thumb_height - $new_height) / 2;
	}

	// create a new image to hold the thumbnail
	$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
	if( is_array($bgColour) ){
		$bg = imagecolorallocate($thumb, $bgColour[0], $bgColour[1], $bgColour[2]);
		imagefill($thumb,0,0,$bg);
	}

	// copy from the source to the thumbnail
	imagecopyresampled($thumb, $this->image, $thumb_x, $thumb_y, 0, 0, $new_width, $new_height, $src_width, $src_height);
	
		//imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height);
      	$this->image = $thumb;
	  
	}
	
	

}
?>