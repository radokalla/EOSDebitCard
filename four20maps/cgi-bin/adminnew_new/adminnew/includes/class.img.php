<?php
class Image {
	// variable image filename
	var $filename = '';
	// variable image object resource
	var $image;
	// variable image type
	var $image_type;
	// variable image width
	var $width;
	// variable image height
	var $height;
	
	
	var $errors = array();
	
	var $debug = 0;
	

	function Image($params=array()) {
		if(!empty($params)) {
			foreach($params as $k=>$v) {
				if(isset($this->{$k})) {
					$this->{$k} = $v;
				}
			}
		}
		
		if(empty($this->filename)) {
			$this->errors[] = 'Filename not set';
			return FALSE;
		}
		
	return $this->load($this->filename);
	}
	

	function load($filename) {
		if(!file_exists($filename)) {
			$this->errors[] = 'File doesn\'t exist: '.$filename;
		return FALSE;
		}
		
		$image_info = getimagesize($filename);
		$this->width 		= $image_info[0];
		$this->height 		= $image_info[1];
		$this->image_type 	= $image_info[2];

		
		if($this->image_type == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif($this->image_type == IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif($filename);
		} elseif($this->image_type == IMAGETYPE_PNG) {

			$this->image = imagecreatefrompng($filename);
		} else {
			$this->errors[] = 'Invalid image type';
		}
		
		$this->print_debug($this->errors);
	return TRUE;
	}
	

	function resize_to_width($width) {
		$ratio = $width / $this->width;
		$height = $this->height * $ratio;

	return $this->resize($width,$height);
	}
	

	function resize_to_height($height) {
		$ratio = $height / $this->height;
		$width = $this->width * $ratio;
	return $this->resize($width,$height);
	}

	function scale($scale) {
		$width = $this->width * ($scale/100);
		$height = $this->height * ($scale/100);
	return $this->resize($width, $height);
	}
	

	function resize($width,$height) {
		$new_image = @imagecreatetruecolor($width, $height);
		if($new_image === FALSE) {
			$this->errors[] = 'Error resizing image, problem with imagecreatetruecolor()';
			return FALSE;
		}
		
		imagealphablending($new_image, false);
		imagesavealpha($new_image, true);
		
		if(!imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height)) {
			$this->errors[] = 'Error resizing image, problem with imagecopyresampled()';
		return FALSE;
		}
		
		$this->image = $new_image;
		
		// handle transparency
	    //$this->setTransparency($new_image,$this->image);
		
		$this->print_debug($this->errors);
		
	return TRUE;
	}
	
	function setTransparency($new_image,$image_source) 
    { 
     
		
            $transparencyIndex = imagecolortransparent($image_source); 
            $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255); 
             
            if ($transparencyIndex >= 0) { 
                $transparencyColor    = imagecolorsforindex($image_source, $transparencyIndex);    
            } 
            
            $transparencyIndex    = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']); 
            imagefill($new_image, 0, 0, $transparencyIndex); 
            imagecolortransparent($new_image, $transparencyIndex); 
		
		

        
    } 
	

	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75) {
		$result = FALSE;
		if($this->image_type == IMAGETYPE_JPEG) {
			$result = imagejpeg($this->image, $filename,$compression);
			imagedestroy($this->image);
		} elseif($this->image_type == IMAGETYPE_GIF) {
			$result = imagegif($this->image, $filename);
			imagedestroy($this->image);
		} elseif($this->image_type == IMAGETYPE_PNG) {

			$result = imagepng($this->image, $filename);
			imagedestroy($this->image);
		} else {
			$this->errors[] = 'Invalid image type';
		}
	return $result;
	}
	

	function output() {
		header('Content-Type: '.$this->image_type);
		if($this->image_type == IMAGETYPE_JPEG) {
			imagejpeg($this->image);
		} elseif($this->image_type == IMAGETYPE_GIF) {
			imagegif($this->image);
		} elseif($this->image_type == IMAGETYPE_PNG) {
			imagepng($this->image);
		} else {
			$this->errors[] = 'Invalid image type';
		}
	}
	

	function print_debug($arr,$heading='') {
		if($this->debug && !empty($arr)) {
			echo '<pre>';
			if($heading) {
				echo '<strong>'.$heading.'</strong><br/>';
			}
			if(is_string($arr)) {
				echo $arr;
			} else {
				print_r($arr);
			}
			echo '</pre>';
		}
	}
}