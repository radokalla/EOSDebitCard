<?php
	session_start();
	include_once 'includes/class.img.php';
	$email=$_POST['emailr'];
	$aid=$_POST['aid'];
	$name=$_POST['namer'];
	$phone=$_POST['phone'];
	$url=$_POST['url'];
	$img=$_FILES['file'];
	$last_modified = date('Y-m-d H:i:s',time());
	if((!empty($email) &&($aid) &&($name) &&($phone) &&($url)) || ($img))
	{
		$con=mysql_connect("localhost","four20maps","induco123") or die('Connect eror');
		if($con==true)
		{
			mysql_select_db("four20ma_storefinder",$con);
			$query=mysql_query("select * from adds where aid='$aid'");
			$details=mysql_fetch_array($query);
			if(!empty($details))
			{
				$dest ='';
				if(count($_FILES) > 0 &&  $_FILES["file"]["error"]==0)
				{
					include_once 'image_resize.php';
					$filename = $_FILES["file"]["name"];
					$dest = time().$filename;
					move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/".$dest);
					$file = $dest;
					$target_file = "uploads/".$dest;  
					/*$image = new SimpleImage();
					$image->load($target_file);
					$image->resize(160, 240);
					$image->save($target_file);*/
					$img1  = new Image(array('filename'=>$target_file));
					$img1->resize(160,240);
					$img1->save($target_file);
				}
				
				
				if ($dest !='')
				{
					mysql_query("UPDATE adds SET Email='$email', Name='$name', PhoneNumber='$phone' , url='$url' , image='$dest',
					last_modified='$last_modified' WHERE aid='$aid' ");
				}else
					mysql_query("UPDATE adds SET Email='$email', Name='$name', PhoneNumber='$phone' , url='$url', last_modified='$last_modified'  WHERE aid='$aid' ");
				
				$_SESSION['notification'] = array('type'=>'good','msg'=>"Successfully Saved");
				header('location:addslist.php');
			}
			else
			{
				$_SESSION['notification'] = array('type'=>'bad','msg'=>"Invalid Page");
				header('location:addslist.php');
				echo "<script> alert('Invalid Add'),history.go(-1)</script>";
			}
		}
	}
	else
	{
		echo "<script> alert('_Please fill all the details'),history.go(-1)</script>";
	}
 ?>