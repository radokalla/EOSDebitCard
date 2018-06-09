<?php
session_start();
ob_start();
$username=$_REQUEST['username'];
$password=md5($_REQUEST['password']);
include_once './includes/config.inc.php';
	$res = mysql_query("select * from users where username='$username' and password='$password' and status= '1' ") or die(mysql_error());
	$user = mysql_fetch_array($res);
		if(!empty($user))
		{
			$_SESSION['userid'] = $user['id'];
			echo "1";
			die;
		}
		else
		{
			$res1 = mysql_query("select * from StoreUsers where Username='$username' and Password='$password' and status= '1' ") or die(mysql_error());
			$user1 = mysql_fetch_array($res1);
			if(!empty($user1))
			{
				$_SESSION['StoreID'] = $user1['UserId'];
				$_SESSION["regSuccess"] = $username;
				$_SESSION["payAmount"] = $user1['SubscriptionTypeId'];
				$_SESSION["NOPAYMENT"] = "0";
				$udetails = mysql_query("select SubscriptionCategoryId from StoreUsers where Username ='$username'")or die(mysql_error()) ;
				$urow = mysql_fetch_array($udetails);
				$_SESSION["SubscriptionCategoryId"] = $urow['SubscriptionCategoryId'];
				echo "2";
				die;
			}
			else
			{
				echo "0";
				die;
			}
		}
		
?>

