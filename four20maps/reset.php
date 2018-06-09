<?php
$email=$_POST['email'];
$temp_pass=$_POST['temp_pass'];
$newpass=md5($_POST['new_pass']);
$cpass=md5($_POST['c_pass']);
if(!empty($email) &&($temp_pass) &&($newpass) &&($cpass))
{
	$con=mysql_connect("localhost","four20maps","induco123") or die('Connect eror');
	if($con==true)
	{
		mysql_select_db("four20ma_storefinder",$con);
		$type = substr($temp_pass, -1);
		$password = md5($temp_pass);
		if($type == 1)
			$query = mysql_query("select * from StoreUsers where Email='$email' and Password = '$password' ");
		else
			$query=mysql_query("select * from users where email='$email' and password='$password' ");
		
		$result=mysql_fetch_array($query);
		if(!empty($result))
		{
			if($newpass==$cpass)
			{
				if($type == 1)
					$return = mysql_query("UPDATE StoreUsers set password = '$newpass' where Email ='$email' ");
				else
					$return = mysql_query("UPDATE users SET password='$newpass' where email='$email' ");
				
				if($return==1)
				{
					if($type == 1)
						echo "1";
					else
						echo "2";
				}
				else
					echo "Please Try After some time..";
			}
			else
			{
				echo "Password and Confirm Password Mis-Match Try Again";
			}
		}
		else 
		{
			echo "Invalid Email or Invalid Temporary Password";
		}
	}
}
else 
{
	echo "Please fill all the details";
}
?>