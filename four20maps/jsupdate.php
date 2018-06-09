<?php
// include config file
session_start();
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
$idd=$_POST['idd'];
$firstname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$Address=$_POST['address'];
$upquery = 'Update users set firstname="'.$firstname.'",lastname="'.$lastname.'",address="'.$Address.'" where id='.$idd;
$qryexc=mysql_query($upquery);
if($qryexc)
{
	echo "Successfully Submitted";
}
else
{
	echo "Not Submitted";
}

		
	
?>

