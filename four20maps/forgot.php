<?php
$email=$_POST['email'];
if(!empty($email))
{
	$con=mysql_connect("localhost","four20maps","induco123") or die('Connect eror');
	if($con==true)
	{
		mysql_select_db("four20ma_storefinder",$con);
		$type = $_POST['type'];
		if($type==1)
		{
			$query=mysql_query("select * from StoreUsers where Email='$email' and Status='1' ");
			$result=mysql_fetch_array($query);
			if(!empty($result))
			{
				$email_id=$result['Email'];
				$uniq=(uniqid(rand(), true));
				$break=explode('.',$uniq);
				$temp_pass=$break['1'].$type;
				$md5pass = md5($temp_pass);
				mysql_query("UPDATE StoreUsers SET Password='$md5pass' where Email='$email_id' ");
					$header= "MIME-Version: 1.0\r\n";
					$header.="Content-Type: text/html; charset=ISO-8859-1\r\n";
					$header.="From: support@four20maps.com";
					$to = $email_id;
					$subject = "Four20maps Password Reset";
					$txt="<img src='".ROOT_URL."/img/logo.png'/> <br>";
					$txt.="You have requested for the change of Password.<br>";
					$txt.="Your Temporary Password is: <b>$temp_pass</b> <br>";
					$txt.="Please click on below link to change your password. <br>";
					$txt.="<a href='".ROOT_URL."forgot_form.php'>Click Here</a>";
					mail($to,$subject,$txt,$header);
					echo "1";
					die;
			}
			else
			{
				echo "2";
				die;
			}
		}
		else if($type==2)
		{
			$query=mysql_query("select * from users where email='$email' ");
			$result=mysql_fetch_array($query);
			if(!empty($result))
			{
				$email_id=$result['email'];
				$uniq=(uniqid(rand(), true));
				$break=explode('.',$uniq);
				$temp_pass=$break['1'].$type;	
				$md5pass = md5($temp_pass);
				mysql_query("UPDATE users SET password='$md5pass' where email='$email_id' ");
					$header= "MIME-Version: 1.0\r\n";
					$header.="Content-Type: text/html; charset=ISO-8859-1\r\n";
					$header.="From: support@four20maps.com";
					$to = $email_id;
					$subject = "Four20maps Password Reset";
					$txt="<img src='".ROOT_URL."img/logo.png'/> <br>";
					$txt.="You have requested for the change of Password.<br>";
					$txt.="Your Temporary Password is: $temp_pass <br>";
					$txt.="Please click on below link to change your password. <br>";
					$txt.="<a href='".ROOT_URL."forgot_form.php'>Click Here</a>";
					mail($to,$subject,$txt,$header);
					echo "1";
					die;
			}
			else
			{
				echo "2";
				die;
			}
		}
	}
}
?>