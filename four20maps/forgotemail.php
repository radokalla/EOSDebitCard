<?php 
if($_POST)
{
	$email = $_REQUEST['email'];
	$type = $_REQUEST['type'];
	if(!empty($email))
	{
		$con=mysql_connect("localhost","four20maps","induco123") or die('Connect eror');
		if($con==true)
		{
			mysql_select_db("four20ma_storefinder",$con);
			if($type == 1)
			{
				$query = mysql_query("select * from users where email='$email'");
				$result=mysql_fetch_assoc($query);
				if(!empty($result))
				{
					$username=$result['username'];
					$email_id=$result['email'];
					$header= "MIME-Version: 1.0\r\n";
					$header.="Content-Type: text/html; charset=ISO-8859-1\r\n";
					$header.="From: support@four20maps.com";
					$to = $email_id;
					$subject = "Four20maps Forgot User Name";
					$txt="<img src='".ROOT_URL."img/logo.png'/> <br>";
					$txt.="You have requested for your User Name.<br>";
					$txt.="Your User Name is: $username <br>";					
					mail($to,$subject,$txt,$header);
					echo"1";
				}
				else
				{
					echo "2";
				}
			}
			if($type == 2)
			{
				$query = mysql_query("select * from StoreUsers where Email='$email'");
				$result=mysql_fetch_assoc($query);
				if(!empty($result))
				{
					$username = $result['Username'];
					$email_id = $result['Email'];
					$header= "MIME-Version: 1.0\r\n";
					$header.="Content-Type: text/html; charset=ISO-8859-1\r\n";
					$header.="From: support@four20maps.com";
					$to = $email_id;
					$subject = "Four20maps Forgot User Name";
					$txt="<img src='".ROOT_URL."img/logo.png'/> <br>";
					$txt.="You have requested for your User Name.<br>";
					$txt.="Your User Name is: $username <br>";					
					mail($to,$subject,$txt,$header);
					$_SESSION['message'] = "A mail has been sent to your Email Account.";
					echo"1";
				}
				else
				{
					echo "2";
				}
			}
			
				
		}
	}
	else
	{
		echo "2";
	}
}
 ?>