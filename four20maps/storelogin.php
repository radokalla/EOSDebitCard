<?php
ob_start();
session_start();
include_once './includes/config.inc.php';
include_once './includes/class.database.php';

$username = $_POST['username'];
$password = md5($_POST['password']);
	$table1 = mysql_query("select UserId,Status,Username,Payment,SubscriptionTypeId from StoreUsers where Username='$username' AND Password='$password' and Status!=0") or die(mysql_error());
	$data1=mysql_fetch_array($table1);
		if(!empty($data1))
		{
			if($data1['Status']=='2')
			{
				echo "9";
				die;
			}
			$subs = mysql_query("select InitialAmount from SubscriptionTypes where SubscriptionTypeId=".$data1['SubscriptionTypeId'])or die(mysql_error());
			$subs_rows = mysql_fetch_array($subs);
			if($subs_rows['InitialAmount']==0)
			{
				$_SESSION["regSuccess"] = $username;
				$_SESSION["StoreID"] = $data1["UserId"];
				$_SESSION["NOPAYMENT"] = "0";
					echo "a";
					die;
			}
			else if (($subs_rows['InitialAmount']!=0) && ($data1["Payment"]==1))
			{
				$_SESSION["regSuccess"] = $username;
				$_SESSION["StoreID"] = $data1["UserId"];
				$_SESSION["NOPAYMENT"] = "0";
				echo "a";
				die;
			}
			else if(($subs_rows['InitialAmount']!=0) && ($data1["Payment"]==0))
			{
				$_SESSION["regSuccess"] = $username;
				$_SESSION["StoreID"] = $data1["UserId"];
				$_SESSION["NOPAYMENT"] = "1";
				$_SESSION["payAmount"] = $data1['SubscriptionTypeId'];
				echo $data1['SubscriptionTypeId'];
				die;
			}
		}
		else
		{
			$table2 = mysql_query("select id,status,username from users where username='$username' AND password='$password' and status!=0 and is_admin='0'") or die(mysql_error());
			$data2=mysql_fetch_array($table2);
			if(!empty($data2))
			{
				$_SESSION['userid'] = $data2['id'];
				echo "8";
				die; 
				
			}
			else
			{
				echo "0";
				die;
			}
		}
	
?>