<?php
	$rating=$_POST['rate'];
	$id=$_POST['id'];
	$con=mysql_connect("localhost","four20maps","induco123") or die('Connect eror');
	if($con==true)
	{
		mysql_select_db("four20ma_storefinder",$con);
		mysql_query("UPDATE users SET rating='$rating' where id='$id' ");
		header("location:index.php");
	}
	else
	{
		echo "failed";
	}
 ?>