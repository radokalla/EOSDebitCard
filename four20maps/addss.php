<?php 
ob_start();
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
include_once './admin/image_resize.php';
$name=$_POST['name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$url=$_POST['url'];
 if (!preg_match("~^(?:f|ht)tps?://~i", $url))
	$url = "http://" . $url;
$file = 'ghghh.jpg';
if(count($_FILES) > 0)
{
	$filename = $_FILES["file"]["name"];
	//$imgtype = pathinfo($filename, PATHINFO_EXTENSION);
	$imgtype = substr($filename,strrpos($filename,'.',-1),strlen($filename));
	if(($imgtype== '.jpeg') || ( $imgtype== '.JPEG') || ($imgtype== '.PNG') || ($imgtype== '.png') || ($imgtype== '.jpg') || ($imgtype== '.JPG') || ($imgtype== '.gif') || ($imgtype== '.GIF')  )
	{
		$dest = time().$filename;
		move_uploaded_file($_FILES["file"]["tmp_name"], "admin/uploads/".$dest);
		$file = $dest;
		$target_file = "admin/uploads/".$dest;  
		$image = new SimpleImage();
		$image->load($target_file);
		$image->resize(200, 200);
		$image->save($target_file);
		$s = "CALL map_proc_ManageAdds('{$file}','{$url}','{$name}','{$email}','{$phone}')";
		$resul=mysql_query($s) or die(mysql_error());
		$rj=mysql_fetch_assoc($resul);
		echo $rj['MsgOut'];
		die;
	}
	else
	{
		echo "3";
		die;
	}
}

?>



