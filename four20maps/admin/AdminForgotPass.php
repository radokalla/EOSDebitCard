<?php 

include_once './includes/config.inc.php';

if($_REQUEST['Email'])
{
	$email = $_REQUEST['Email'];
	if(!empty($email))
	{
		$db = db_connect();
		$query = mysql_query("select email from users where is_admin='1' and email='$email'")or die(mysql_error());
		$data = mysql_fetch_array($query);
		if(!empty($data))
		{
			$pass = time();
			$md5pass = md5($pass);
			mysql_query("UPDATE users SET password = '$md5pass' where email = '".$data['email']."' ")or die(mysql_error());
			$email_id = $data['email'];
			$header= "MIME-Version: 1.0\r\n";
			$header.="Content-Type: text/html; charset=ISO-8859-1\r\n";
			$header.="From: Admin Support@four20maps.com";
			$to = $email_id;
			$subject = "Four20Maps Forogot Admin User Password";
			$txt="<img src='http://four20maps.com/img/logo.png' style='background-color:#7dcd1e;width:150px; height:45px'/> <br>";
			$txt.="You have requested to reset your password.<br>";
			$txt.="Your New password is:".$pass." <br>";					
			mail($to,$subject,$txt,$header);
			echo"1";
		}
		else
			echo "2";
	}
	else
		echo "3";
}

?>