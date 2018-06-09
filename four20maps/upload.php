<?php
ini_set("memory_limit", "200000000");
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
$name=$_POST['name'];
$email=$_POST['emailr'];
$phone=$_POST['phone'];
$url=$_POST['url'];
$filer=$_FILES['file']['name'];
$resul=mysql_query("CALL map_proc_ManageAdds('{$filer}','{$url}','{$name}','{$email}','{$phone}')") or die(mysql_error());
$rj=mysql_fetch_assoc($resul);

if($rj['MsgOut']==1) 
{

// upload the file
	
	// file needs to be jpg,gif,bmp,x-png and 4 MB max
	if (($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/x-png") && ($_FILES["file"]["size"] < 4000000))
	{
		
  
		// some settings
		$max_upload_width = 160;
		$max_upload_height = 240;
		  
		// if user chosed properly then scale down the image according to user preferances
		 /*if(isset($_REQUEST['max_width_box']) and $_REQUEST['max_width_box']!='' and $_REQUEST['max_width_box']<=$max_upload_width){
			$max_upload_width = $_REQUEST['max_width_box'];
		}    
		if(isset($_REQUEST['max_height_box']) and $_REQUEST['max_height_box']!='' and $_REQUEST['max_height_box']<=$max_upload_height){
			$max_upload_height = $_REQUEST['max_height_box'];
		}	*/ 

		
		// if uploaded image was JPG/JPEG
		if($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg"){	
			$image_source = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);
		}		
		// if uploaded image was GIF
		if($_FILES["file"]["type"] == "image/gif"){	
			$image_source = imagecreatefromgif($_FILES["file"]["tmp_name"]);
		}	
		// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)	
		// if uploaded image was BMP
		if($_FILES["file"]["type"] == "image/bmp"){	
			$image_source = imagecreatefromwbmp($_FILES["file"]["tmp_name"]);
		}			
		// if uploaded image was PNG
		if($_FILES["file"]["type"] == "image/x-png"){
			$image_source = imagecreatefrompng($_FILES["file"]["tmp_name"]);
		}
		

		$remote_file = getcwd().'/admin/uploads/'.$_FILES["file"]["name"];
		imagejpeg($image_source,$remote_file,100);
		chmod($remote_file,0644);
	
	

		// get width and height of original image
		list($image_width, $image_height) = getimagesize($remote_file);
	
		if($image_width>$max_upload_width || $image_height >$max_upload_height){
			
			$proportions = $image_width/$image_height;
			$new_width=160;
			$new_height=240;
			
			/*if($image_width>$image_height){
				echo $new_width = $max_upload_width;
				echo "<br/>";
				echo $new_height = $max_upload_height; exit;
			}		
			else{
				echo $new_height = $max_upload_height;
				echo "<br/>";
				echo $new_width = round($max_upload_height*$proportions); exit;
			}		
			*/
			
			$new_image = imagecreatetruecolor($new_width , $new_height);
			$image_source = imagecreatefromjpeg($remote_file);
			
			imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			imagejpeg($new_image,$remote_file,100);
			
			imagedestroy($new_image);
		}
		
		imagedestroy($image_source);
		
		$msg="Successfully Submitted";
	}
	else{
		$msg = "Invalid file";
	}
}
else{
$msg = "Already Exist";	
	
}


// Mail functionality by Sravan
$error="";
if(isset($_POST['addenquirysubmit'])){
	$name=$_POST['name'];
	$emailr=$_POST['emailr'];
	$phone=$_POST['phone'];
	$url=$_POST['url'];
	$to = 'sravan.kumar@inducohosting.com';
	
	
	$subject = 'Ads Enquiry Form';
$message='<html>
<body>
<table cellpadding=3 cellspacing=1  bgcolor="#d8d8d8" width=700>
  <tr>
    <td align=center height=30px bgcolor=#122847 colspan="2" class="text-white"><strong>Ads Enquiry</strong></td>
  </tr>
  <tr>
    <td width="185" valign="top" bgcolor=#d3e1f5>First Name</td>
    <td width="301" valign="top" bgcolor=#d3e1f5>'.$name.'</td>
  </tr>
  <tr>
    <td width="185" valign="top" bgcolor=#d3e1f5>Email</td>
    <td width="301" valign="top" bgcolor=#d3e1f5>'.$emailr.'</td>
  </tr>
  <tr>
    <td valign="top" bgcolor=#d3e1f5>Phone Number</td>
    <td valign="top"  bgcolor=#d3e1f5>'.$phone.'</td>
  </tr>
  <tr>
    <td valign="top" bgcolor=#d3e1f5>URL</td>
    <td valign="top"  bgcolor=#d3e1f5>'.$url.'</td>
  </tr>
  
  
 
  </table>
</body>
</html>';
//echo $message;exit;
    $file_size = filesize($_FILES["file"]["tmp_name"]);
    $handle = fopen($_FILES["file"]["tmp_name"], "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
  $header = "From: <".$emailr.">\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
	
			/*$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			// Additional headers
			$headers .= 'To: Sravan <sravan.kumar@inducohosting.com>' . "\r\n";
			$headers .= 'From: '.$fname.' '.$lname.' <'.$email.'>' . "\r\n";*/
	
	
  	$header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: ".$_FILES["file"]["type"]."; name=\"".$_FILES["file"]["name"]."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$_FILES["file"]["name"]."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
	mail($to, $subject, $message, $header);
}



	
?>
<script>
window.location='<?php echo ROOT_URL;?>?msg=<?php echo $msg; ?>';
</script>


