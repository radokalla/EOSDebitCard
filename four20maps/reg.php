<?php
// include config file
#var_dump($_POST);die;
ob_start();
session_start();
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
$frsname=$_REQUEST['regfname'];
$lsname=$_REQUEST['reglname'];
$email=$_REQUEST['regemail'];
$username=$_REQUEST['reguser'];
$password=md5($_REQUEST['regpassword']);
$address=$_REQUEST['regaddress'];

$resul=mysql_query("CALL map_proc_RegisterUser_new('{$username}','{$password}','{$frsname}','{$lsname}','{$address}','{$email}')") or die(mysql_error());
$rj=mysql_fetch_assoc($resul);
$rgg = $rj['MsgOut'];
$_SESSION['userid']=$rgg;

if($rj['MsgOut']==0)
{
	session_unset($_SESSION['userid']);
	echo "Invalid";
}
else
{
	/* ************************************** Email to User registered ***************************************/
	
		/* OLD CODE *************************************************
		$header= "MIME-Version: 1.0\r\n";
		$header.="Content-Type: text/html; charset=ISO-8859-1\r\n";
		$header.="";
		$to = ;
		$subject = "Four20maps Registration Confirmation";
		$txt="";
		$txt.="Thank you for registering with <a href='www.four20maps.com'>four20maps.com</a> where you can find legal dispensaries 
		with delivery services. 
		Please send us any comments you may have and please don't forget to rate the the dispensaries in our network.";

		$txt.="<br><br><br>Sincerely,<br> four20maps.com Team<br>";
		mail($to,$subject,$txt,$header); *****************************
		*/
	
		$header = "MIME-Version: 1.0" . "\r\n";
		$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$header.= 'From: <support@four20maps.com>' . "\r\n";
		$to = $_REQUEST['regemail'];
		$subject = "Four20maps Registration Confirmation";
		$txt="<html><head><title>Four20maps Confirmation</title></head><body><table cellpadding='5' cellspacing='5'> <tbody> <tr>";
		$txt.="<tr style='background-color:#7dcd1e'><td align='center'><img src='".ROOT_URL."'/img/logo.png'/></td></tr>";
		$txt.="<tr><b>Hello ".$_REQUEST['regfname']."$nbsp".$_REQUEST['reglname']."</b></tr>";
		$txt.="<tr>Thank you for registering with <a href='".ROOT_URL."'>four20maps.com</a>where you can find legal dispensaries with delivery services. </tr>";
		$txt.="<tr>Please send us any comments you may have and please don't forget to rate the the dispensaries in our 	network.</tr>";
		$txt.="<tr>Please use below Details to login</tr>";
		$txt.="<tr>User ID :&nbsp;".$_REQUEST['reguser']."</tr>";
		$txt.="<tr>Please <a href='".ROOT_URL."'>Click Here</a> to redirect.</tr>";
		$txt.="<tr>Sincerely,<br> four20maps.com Team</tr>";
		$txt.="</tbody></table></body></html>";
		mail($to,$subject,$txt,$header);
	
	/*********************************** Email to Admin With User registation Details ***********************************/
	/*$header= "MIME-Version: 1.0\r\n"; 
    $header.="Content-Type: text/html; charset=ISO-8859-1\r\n";
    $header.="From: admin@four20maps.com";
	$to = 'vamsi.krishna@inducosolutions.com';
	$subject = "Four20maps User Registration Alert";
	$txt="<table style='background-color:#eee'>";
	$txt.="<tr style='background-color:#7dcd1e'><td align='center'><img src='".ROOT_URL."img/logo.png'/></td></tr>";
	$txt.="<tr><b>A new user has registered with <a href='".ROOT_URL."'>four20maps.com</a> </b></tr>";
	$txt.="<tr><b>Name:</b> ".$frsname." ".$lsname." </tr>";
	$txt.="<tr><b>User Email:</b> ".$email." </tr>";
	$txt.="<tr><b>Login UserName:</b> ".$username."</tr>";
	$txt.="<tr><b>Address:</b> ".$address." </tr>";
	$txt.="<tr><b>Click <a href='".ROOT_URL."admin'>here</a> to login to admin panel. </b></tr>";
	$txt.="</table>";
	mail($to,$subject,$txt,$header);*/
	echo "1";
}
?>




